<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Event;
use App\Models\IndependentTrainer;
use App\Models\InternalManager;
use App\Models\Program;
use App\Models\ProgramCompletionRequest;
use App\Models\ProgramManagerCredential;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(Event $event): View
    {
        $this->ensureCollegeScope($event);

        $programs = Program::where('event_id', $event->id)
            ->where('college_id', Auth::user()->college_id)
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
        $internals = InternalManager::where('college_id', $collegeId)->orderBy('name')->get();

        return view('college.programs.create', compact('event', 'vendors', 'independents', 'internals'));
    }

    public function store(Request $request, Event $event): RedirectResponse
    {
        $this->ensureCollegeScope($event);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Training,Hackathon,Seminar'],
            'department' => ['required', 'string', 'max:255'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'mode' => ['required', 'in:On-Campus,Online,Hybrid'],
            'status' => ['nullable', 'in:Draft,Manager_Assigned,Registration_Open,In_Progress,Completed,Approved'],
            'manager_type' => ['required', 'in:Vendor,Independent'],
            'vendor_manager_id' => ['nullable', 'integer'],
            'independent_manager_id' => ['nullable', 'integer'],
            'internal_manager_id' => ['required', 'integer'],
        ]);

        $managerId = $this->resolveManagerId($validated);

        if (empty($managerId)) {
            return redirect()->back()->with('error', 'Please select who runs the program (Vendor or Independent Trainer).')->withInput();
        }

        if (!$this->managerBelongsToCollege($validated['manager_type'], $managerId, Auth::user()->college_id)) {
            return redirect()->back()->with('error', 'Selected executor does not belong to this college.')->withInput();
        }

        $internalManagerId = (int) $validated['internal_manager_id'];
        if (! InternalManager::where('id', $internalManagerId)->where('college_id', Auth::user()->college_id)->exists()) {
            return redirect()->back()->with('error', 'Selected internal manager does not belong to this college.')->withInput();
        }

        $status = $validated['status'] ?? 'Draft';
        $status = 'Manager_Assigned';

        $program = Program::create([
            'college_id' => Auth::user()->college_id,
            'event_id' => $event->id,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'department' => $validated['department'],
            'duration_days' => $validated['duration_days'],
            'mode' => $validated['mode'],
            'status' => $status,
            'manager_type' => $validated['manager_type'],
            'manager_id' => $managerId,
            'internal_manager_id' => $internalManagerId,
        ]);

        $generatedCredentials = $this->createManagerCredential($program);

        if ($event->status === 'Draft') {
            $event->update(['status' => 'Active']);
        }

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'action' => 'program.created',
            'description' => "Program '{$program->name}' was created",
        ]);

        if (!empty($generatedCredentials)) {
            return redirect()->route('college.events.programs.show', [$event, $program])
                ->with('generated_program_credentials', [$generatedCredentials])
                ->with('success', 'Program created successfully. Manager credentials generated below.');
        }

        return redirect()->route('college.events.programs.index', $event)
            ->with('success', 'Program created successfully.');
    }

    public function show(Event $event, Program $program): View
    {
        $this->ensureProgramScope($event, $program);

        $program->load(['completionRequests', 'students']);
        $credentials = ProgramManagerCredential::where('program_id', $program->id)
            ->where('college_id', Auth::user()->college_id)
            ->get();
        $generatedCredentials = session('generated_program_credentials', []);

        return view('college.programs.show', compact('event', 'program', 'credentials', 'generatedCredentials'));
    }

    public function edit(Event $event, Program $program): View
    {
        $this->ensureProgramScope($event, $program);

        $collegeId = Auth::user()->college_id;
        $vendors = Vendor::where('college_id', $collegeId)->orderBy('name')->get();
        $independents = IndependentTrainer::where('college_id', $collegeId)->orderBy('name')->get();
        $internals = InternalManager::where('college_id', $collegeId)->orderBy('name')->get();

        return view('college.programs.edit', compact('event', 'program', 'vendors', 'independents', 'internals'));
    }

    public function update(Request $request, Event $event, Program $program): RedirectResponse
    {
        $this->ensureProgramScope($event, $program);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Training,Hackathon,Seminar'],
            'department' => ['required', 'string', 'max:255'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'mode' => ['required', 'in:On-Campus,Online,Hybrid'],
            'status' => ['nullable', 'in:Draft,Manager_Assigned,Registration_Open,In_Progress,Completed,Approved'],
            'manager_type' => ['required', 'in:Vendor,Independent'],
            'vendor_manager_id' => ['nullable', 'integer'],
            'independent_manager_id' => ['nullable', 'integer'],
            'internal_manager_id' => ['required', 'integer'],
        ]);

        $managerId = $this->resolveManagerId($validated);

        if (empty($managerId)) {
            return redirect()->back()->with('error', 'Please select who runs the program (Vendor or Independent Trainer).')->withInput();
        }

        if (!$this->managerBelongsToCollege($validated['manager_type'], $managerId, Auth::user()->college_id)) {
            return redirect()->back()->with('error', 'Selected executor does not belong to this college.')->withInput();
        }

        $internalManagerId = (int) $validated['internal_manager_id'];
        if (! InternalManager::where('id', $internalManagerId)->where('college_id', Auth::user()->college_id)->exists()) {
            return redirect()->back()->with('error', 'Selected internal manager does not belong to this college.')->withInput();
        }

        $executorChanged = ($program->manager_type !== $validated['manager_type'])
            || ($program->manager_id != $managerId);

        $status = $validated['status'] ?? $program->status;

        $program->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'department' => $validated['department'],
            'duration_days' => $validated['duration_days'],
            'mode' => $validated['mode'],
            'status' => $status,
            'manager_type' => $validated['manager_type'],
            'manager_id' => $managerId,
            'internal_manager_id' => $internalManagerId,
        ]);

        $generatedCredentials = [];
        if ($executorChanged) {
            ProgramManagerCredential::where('program_id', $program->id)
                ->where('college_id', Auth::user()->college_id)
                ->update(['status' => 'inactive']);

            $generatedCredentials = $this->createManagerCredential($program);
        }

        ActivityLog::create([
            'college_id' => Auth::user()->college_id,
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'action' => 'program.updated',
            'description' => "Program '{$program->name}' was updated",
        ]);

        if (!empty($generatedCredentials)) {
            return redirect()->route('college.events.programs.show', [$event, $program])
                ->with('generated_program_credentials', [$generatedCredentials])
                ->with('success', 'Program updated successfully. New manager credentials generated below.');
        }

        return redirect()->route('college.events.programs.index', $event)
            ->with('success', 'Program updated successfully.');
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
            'description' => "Program '{$programName}' was deleted",
        ]);

        return redirect()->route('college.events.programs.index', $event)
            ->with('success', 'Program deleted successfully.');
    }

    public function approveCompletion(Event $event, Program $program): RedirectResponse
    {
        $this->ensureProgramScope($event, $program);

        $request = ProgramCompletionRequest::where('program_id', $program->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$request) {
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
            'description' => "Program '{$program->name}' completion was approved",
        ]);

        return redirect()->back()->with('success', 'Program completion approved.');
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
            abort(403, 'Unauthorized access to this event.');
        }
    }

    private function ensureProgramScope(Event $event, Program $program): void
    {
        $this->ensureCollegeScope($event);

        if ($program->college_id !== Auth::user()->college_id || $program->event_id !== $event->id) {
            abort(403, 'Unauthorized access to this program.');
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
}
