<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Event;
use App\Models\IndependentTrainer;
use App\Models\InternalManager;
use App\Models\Program;
use App\Models\ProgramCompletionRequest;
use App\Models\ProgramManagerCredential;
use App\Models\Vendor;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(Event $event): View
    {
        $this->ensureCollegeScope($event);

        $programs = Program::where('event_id', $event->id)
            ->where('college_id', Auth::user()->college_id)
            ->with('departments')
            ->latest()
            ->paginate(15);

        return view('college.programs.index', compact('event', 'programs'));
    }

    public function create(Event $event): View
    {
        $this->ensureCollegeScope($event);

        $collegeId = Auth::user()->college_id;
        $vendors = Vendor::where('college_id', $collegeId)->orderBy('name')->get();
        $independents = IndependentTrainer::where('college_id', $collegeId)->orderBy('name')->get();
        $internals = InternalManager::where('college_id', $collegeId)->with('department')->orderBy('name')->get();
        $departments = Department::where('college_id', $collegeId)->orderBy('name')->get();

        return view('college.programs.create', compact('event', 'vendors', 'independents', 'internals', 'departments'));
    }

    public function store(Request $request, Event $event): RedirectResponse
    {
        $this->ensureCollegeScope($event);

        $collegeId = Auth::user()->college_id;

        $request->merge([
            'internal_manager_id' => $request->filled('internal_manager_id')
                ? (int) $request->input('internal_manager_id')
                : null,
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Training,Hackathon,Seminar,Other,Subject'],
            'department_ids' => ['required', 'array', 'min:1'],
            'department_ids.*' => [
                'integer',
                Rule::exists('departments', 'id')->where(fn ($q) => $q->where('college_id', $collegeId)),
            ],
            'duration_days' => ['required', 'integer', 'min:1'],
            'mode' => ['required', 'in:On-Campus,Online,Hybrid'],
            'status' => ['nullable', 'in:Draft,Manager_Assigned,Registration_Open,In_Progress,Completed,Approved'],
            'manager_type' => ['required', 'in:Vendor,Independent'],
            'vendor_manager_id' => ['nullable', 'integer'],
            'independent_manager_id' => ['nullable', 'integer'],
            'internal_manager_id' => [
                'nullable',
                'integer',
                Rule::exists('internal_managers', 'id')->where(fn ($q) => $q->where('college_id', $collegeId)),
            ],
        ]);

        $managerId = $this->resolveManagerId($validated);

        if (empty($managerId)) {
            return redirect()->back()->with('error', 'Please select who runs the subject/program (Vendor or Professors/Trainers).')->withInput();
        }

        if (! $this->managerBelongsToCollege($validated['manager_type'], $managerId, $collegeId)) {
            return redirect()->back()->with('error', 'Selected executor does not belong to this college.')->withInput();
        }

        $internalManagerId = $validated['internal_manager_id'];

        $name = trim($validated['name']);
        if ($name === '') {
            return redirect()->back()
                ->withErrors(['name' => 'Please enter a subject/program name.'])
                ->withInput();
        }

        if ($this->programNameTakenForEvent($event, $name)) {
            return redirect()->back()
                ->withErrors(['name' => 'A subject/program with this name already exists for this year/semester/event.'])
                ->withInput();
        }

        $status = $validated['status'] ?? 'Draft';
        $status = 'Manager_Assigned';

        $departmentIds = array_values(array_unique(array_map('intval', $validated['department_ids'])));
        $departmentLabel = Department::whereIn('id', $departmentIds)->orderBy('name')->pluck('name')->implode(', ');

        try {
            $program = Program::create([
                'college_id' => $collegeId,
                'event_id' => $event->id,
                'name' => $name,
                'type' => $validated['type'],
                'department' => $departmentLabel,
                'duration_days' => $validated['duration_days'],
                'mode' => $validated['mode'],
                'status' => $status,
                'manager_type' => $validated['manager_type'],
                'manager_id' => $managerId,
                'internal_manager_id' => $internalManagerId,
            ]);
        } catch (QueryException $e) {
            if ($this->isUniqueConstraintViolation($e)) {
                return redirect()->back()
                    ->withErrors(['name' => 'A subject/program with this name already exists for this year/semester/event.'])
                    ->withInput();
            }
            throw $e;
        }

        $program->departments()->sync($departmentIds);

        $generatedCredentials = $this->createManagerCredential($program);

        // Activate in the database directly so Draft→Active is reliable (not dependent on stale $event state).
        Event::where('id', $event->id)
            ->where('college_id', $collegeId)
            ->where('status', 'Draft')
            ->update(['status' => 'Active']);

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'action' => 'program.created',
            'description' => "Subject/program '{$program->name}' was created",
        ]);

        if (! empty($generatedCredentials)) {
            return redirect()->route('college.events.programs.show', [$event, $program])
                ->with('success', 'Subject/program created successfully. Manager login ID and password are saved on this page.')
                ->with('highlight_new_manager_credentials', true);
        }

        return redirect()->route('college.events.programs.index', $event)
            ->with('success', 'Subject/program created successfully.');
    }

    public function show(Event $event, Program $program): View
    {
        $this->ensureProgramScope($event, $program);

        $program->load(['completionRequests', 'students', 'departments']);
        $credentials = ProgramManagerCredential::where('program_id', $program->id)
            ->where('college_id', Auth::user()->college_id)
            ->orderByDesc('status')
            ->orderByDesc('id')
            ->get();
        $highlightNewCredentials = (bool) session('highlight_new_manager_credentials', false);

        return view('college.programs.show', compact('event', 'program', 'credentials', 'highlightNewCredentials'));
    }

    public function edit(Event $event, Program $program): View
    {
        $this->ensureProgramScope($event, $program);

        $collegeId = Auth::user()->college_id;
        $vendors = Vendor::where('college_id', $collegeId)->orderBy('name')->get();
        $independents = IndependentTrainer::where('college_id', $collegeId)->orderBy('name')->get();
        $internals = InternalManager::where('college_id', $collegeId)->with('department')->orderBy('name')->get();
        $departments = Department::where('college_id', $collegeId)->orderBy('name')->get();
        $program->load('departments');

        return view('college.programs.edit', compact('event', 'program', 'vendors', 'independents', 'internals', 'departments'));
    }

    public function update(Request $request, Event $event, Program $program): RedirectResponse
    {
        $this->ensureProgramScope($event, $program);

        $collegeId = Auth::user()->college_id;

        $request->merge([
            'internal_manager_id' => $request->filled('internal_manager_id')
                ? (int) $request->input('internal_manager_id')
                : null,
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Training,Hackathon,Seminar,Other,Subject'],
            'department_ids' => ['required', 'array', 'min:1'],
            'department_ids.*' => [
                'integer',
                Rule::exists('departments', 'id')->where(fn ($q) => $q->where('college_id', $collegeId)),
            ],
            'duration_days' => ['required', 'integer', 'min:1'],
            'mode' => ['required', 'in:On-Campus,Online,Hybrid'],
            'status' => ['nullable', 'in:Draft,Manager_Assigned,Registration_Open,In_Progress,Completed,Approved'],
            'manager_type' => ['required', 'in:Vendor,Independent'],
            'vendor_manager_id' => ['nullable', 'integer'],
            'independent_manager_id' => ['nullable', 'integer'],
            'internal_manager_id' => [
                'nullable',
                'integer',
                Rule::exists('internal_managers', 'id')->where(fn ($q) => $q->where('college_id', $collegeId)),
            ],
        ]);

        $managerId = $this->resolveManagerId($validated);

        if (empty($managerId)) {
            return redirect()->back()->with('error', 'Please select who runs the subject/program (Vendor or Professors/Trainers).')->withInput();
        }

        if (! $this->managerBelongsToCollege($validated['manager_type'], $managerId, $collegeId)) {
            return redirect()->back()->with('error', 'Selected executor does not belong to this college.')->withInput();
        }

        $internalManagerId = $validated['internal_manager_id'];

        $name = trim($validated['name']);
        if ($name === '') {
            return redirect()->back()
                ->withErrors(['name' => 'Please enter a subject/program name.'])
                ->withInput();
        }

        if ($this->programNameTakenForEvent($event, $name, $program->id)) {
            return redirect()->back()
                ->withErrors(['name' => 'A subject/program with this name already exists for this year/semester/event.'])
                ->withInput();
        }

        $executorChanged = ($program->manager_type !== $validated['manager_type'])
            || ($program->manager_id != $managerId);

        $status = $validated['status'] ?? $program->status;

        $departmentIds = array_values(array_unique(array_map('intval', $validated['department_ids'])));
        $departmentLabel = Department::whereIn('id', $departmentIds)->orderBy('name')->pluck('name')->implode(', ');

        try {
            $program->update([
                'name' => $name,
                'type' => $validated['type'],
                'department' => $departmentLabel,
                'duration_days' => $validated['duration_days'],
                'mode' => $validated['mode'],
                'status' => $status,
                'manager_type' => $validated['manager_type'],
                'manager_id' => $managerId,
                'internal_manager_id' => $internalManagerId,
            ]);
        } catch (QueryException $e) {
            if ($this->isUniqueConstraintViolation($e)) {
                return redirect()->back()
                    ->withErrors(['name' => 'A subject/program with this name already exists for this year/semester/event.'])
                    ->withInput();
            }
            throw $e;
        }

        $program->departments()->sync($departmentIds);

        $generatedCredentials = [];
        if ($executorChanged) {
            ProgramManagerCredential::where('program_id', $program->id)
                ->where('college_id', Auth::user()->college_id)
                ->get()
                ->each(function (ProgramManagerCredential $c) {
                    $c->forceFill([
                        'status' => 'inactive',
                        'last_plain_password' => null,
                    ])->save();
                });

            $generatedCredentials = $this->createManagerCredential($program);
        }

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'action' => 'program.updated',
            'description' => "Subject/program '{$program->name}' was updated",
        ]);

        if (! empty($generatedCredentials)) {
            return redirect()->route('college.events.programs.show', [$event, $program])
                ->with('success', 'Subject/program updated successfully. New manager login ID and password are saved on this page.')
                ->with('highlight_new_manager_credentials', true);
        }

        return redirect()->route('college.events.programs.index', $event)
            ->with('success', 'Subject/program updated successfully.');
    }

    public function destroy(Event $event, Program $program): RedirectResponse
    {
        $this->ensureProgramScope($event, $program);

        $programName = $program->name;
        $program->delete();

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'action' => 'program.deleted',
            'description' => "Subject/program '{$programName}' was deleted",
        ]);

        return redirect()->route('college.events.programs.index', $event)
            ->with('success', 'Subject/program deleted successfully.');
    }

    public function approveCompletion(Event $event, Program $program): RedirectResponse
    {
        $this->ensureProgramScope($event, $program);

        $request = ProgramCompletionRequest::where('program_id', $program->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (! $request) {
            return redirect()->back()->with('error', 'No pending completion request found.');
        }

        $request->update([
            'status' => 'approved',
            'reviewed_by_user_id' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $program->update(['status' => 'Approved']);

        $remaining = Program::where('event_id', $event->id)
            ->where('college_id', Auth::user()->college_id)
            ->where('status', '!=', 'Approved')
            ->count();

        if ($remaining === 0) {
            $event->update(['status' => 'Completed']);
        }

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'action' => 'program.approved',
            'description' => "Subject/program '{$program->name}' completion was approved",
        ]);

        return redirect()->back()->with('success', 'Subject/program completion approved.');
    }

    private function createManagerCredential(Program $program): array
    {
        $managerLabel = $program->managerLabel();
        $username = $this->generateUsername($managerLabel, $program);
        $password = Str::random(12);

        $credential = ProgramManagerCredential::updateOrCreate(
            [
                'program_id' => $program->id,
                'manager_type' => $program->manager_type,
                'manager_id' => $program->manager_id,
            ],
            [
                'college_id' => $program->college_id,
                'username' => $username,
                'password' => Hash::make($password),
                'last_plain_password' => $password,
                'status' => 'active',
            ]
        );

        return [
            'program' => $program,
            'username' => $username,
            'password' => $password,
            'credential' => $credential,
        ];
    }

    private function generateUsername(string $managerLabel, Program $program): string
    {
        $managerSlug = Str::slug($managerLabel, '_');
        $baseUsername = strtolower($managerSlug).'_'.$program->id;
        $username = $baseUsername;
        $counter = 1;

        while (ProgramManagerCredential::where('username', $username)->exists()) {
            $username = $baseUsername.'_'.$counter;
            $counter++;
        }

        return $username;
    }

    private function ensureCollegeScope(Event $event): void
    {
        if ($event->college_id !== Auth::user()->college_id) {
            abort(403, 'Unauthorized access to this year/semester/event.');
        }
    }

    private function ensureProgramScope(Event $event, Program $program): void
    {
        $this->ensureCollegeScope($event);

        if ($program->college_id !== Auth::user()->college_id || $program->event_id !== $event->id) {
            abort(403, 'Unauthorized access to this subject/program.');
        }
    }

    private function resolveManagerId(array $validated): ?int
    {
        return match ($validated['manager_type'] ?? null) {
            'Vendor' => $validated['vendor_manager_id'] ?? null,
            'Independent' => $validated['independent_manager_id'] ?? null,
            default => null,
        };
    }

    private function managerBelongsToCollege(?string $type, int $managerId, int $collegeId): bool
    {
        return match ($type) {
            'Vendor' => Vendor::where('id', $managerId)->where('college_id', $collegeId)->exists(),
            'Independent' => IndependentTrainer::where('id', $managerId)->where('college_id', $collegeId)->exists(),
            'Internal' => InternalManager::where('id', $managerId)->where('college_id', $collegeId)->exists(),
            default => false,
        };
    }

    /** Case-insensitive uniqueness of program name within an event. */
    private function programNameTakenForEvent(Event $event, string $name, ?int $exceptProgramId = null): bool
    {
        $normalized = Str::lower($name);

        $query = Program::where('event_id', $event->id)
            ->whereRaw('LOWER(TRIM(name)) = ?', [$normalized]);

        if ($exceptProgramId !== null) {
            $query->where('id', '!=', $exceptProgramId);
        }

        return $query->exists();
    }

    private function isUniqueConstraintViolation(QueryException $e): bool
    {
        $message = strtolower($e->getMessage());

        return str_contains($message, 'unique constraint')
            || str_contains($message, 'duplicate entry')
            || str_contains($message, 'integrity constraint violation');
    }
}
