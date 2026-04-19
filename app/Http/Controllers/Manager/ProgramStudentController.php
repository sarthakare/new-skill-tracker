<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Program;
use App\Models\ProgramManagerCredential;
use App\Models\ProgramStudent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProgramStudentController extends Controller
{
    public function index(Program $program): View
    {
        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();

        $program->load('departments');
        $collegeId = $program->college_id;
        $scopedDeptIds = $program->scopedDepartmentIds();

        $assignedUserIds = ProgramStudent::where('program_id', $program->id)
            ->whereNotNull('user_id')
            ->pluck('user_id');

        $registeredStudentsQuery = User::query()
            ->where('college_id', $collegeId)
            ->where('role', 'STUDENT')
            ->whereNotIn('id', $assignedUserIds)
            ->with('department');

        if ($scopedDeptIds->isNotEmpty()) {
            $registeredStudentsQuery->whereIn('department_id', $scopedDeptIds);
        }

        $registeredStudents = $registeredStudentsQuery->get()
            ->sort(function (User $a, User $b) {
                $ea = ! filled($a->roll_number);
                $eb = ! filled($b->roll_number);
                if ($ea !== $eb) {
                    return $ea ? 1 : -1;
                }
                if (! $ea) {
                    $c = strnatcasecmp((string) $a->roll_number, (string) $b->roll_number);
                    if ($c !== 0) {
                        return $c;
                    }
                }

                return strnatcasecmp((string) $a->name, (string) $b->name);
            })
            ->values();

        $departments = $scopedDeptIds->isNotEmpty()
            ? Department::where('college_id', $collegeId)->whereIn('id', $scopedDeptIds)->orderBy('name')->get()
            : Department::where('college_id', $collegeId)->orderBy('name')->get();

        $students = ProgramStudent::sortByRollThenName(
            $program->programStudentsQuery()
                ->with(['user.department', 'collegeDepartment'])
                ->latest()
                ->get()
        );

        return view('manager.programs.students', compact(
            'program',
            'students',
            'credential',
            'registeredStudents',
            'departments'
        ));
    }

    public function store(Request $request, Program $program): RedirectResponse
    {
        $collegeId = $program->college_id;

        $mode = $request->validate([
            'mode' => ['required', Rule::in(['user', 'manual'])],
        ])['mode'];

        if ($mode === 'user') {
            $validated = $request->validate([
                'user_id' => [
                    'required',
                    'integer',
                    Rule::exists('users', 'id')->where(fn ($q) => $q->where('college_id', $collegeId)->where('role', 'STUDENT')),
                ],
            ]);

            if (ProgramStudent::where('program_id', $program->id)->where('user_id', $validated['user_id'])->exists()) {
                return redirect()->route('manager.program.students.index', $program)
                    ->with('error', 'That student is already in this subject/program.');
            }

            $user = User::with('department')->findOrFail($validated['user_id']);

            $program->loadMissing('departments');
            $scopedIds = $program->scopedDepartmentIds();
            if ($scopedIds->isNotEmpty()) {
                if (! $user->department_id || ! $scopedIds->contains((int) $user->department_id)) {
                    return redirect()->route('manager.program.students.index', $program)
                        ->with('error', 'This student is not in a department assigned to this subject/program.');
                }
            }

            $departmentId = $user->department_id;
            $departmentName = $user->department?->name ?? '';

            ProgramStudent::create([
                'college_id' => $collegeId,
                'program_id' => $program->id,
                'user_id' => $user->id,
                'student_name' => $user->name,
                'student_identifier' => filled($user->roll_number) ? (string) $user->roll_number : null,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'department_id' => $departmentId,
                'department' => $departmentName ?: '—',
                'status' => 'registered',
            ]);
        } else {
            $mobileRaw = trim((string) $request->input('mobile', ''));
            $request->merge([
                'roll_number' => trim((string) $request->input('roll_number', '')),
                'mobile' => $mobileRaw === '' ? null : $mobileRaw,
            ]);

            $program->loadMissing('departments');
            $scopedIds = $program->scopedDepartmentIds();
            $departmentRules = [
                'required',
                'integer',
                $scopedIds->isNotEmpty()
                    ? Rule::in($scopedIds->all())
                    : Rule::exists('departments', 'id')->where(fn ($q) => $q->where('college_id', $collegeId)),
            ];

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
                'roll_number' => [
                    'required',
                    'string',
                    'max:64',
                    Rule::unique('users', 'roll_number')->where(
                        fn ($query) => $query->where('college_id', $collegeId)->where('role', 'STUDENT')
                    ),
                ],
                'mobile' => ['nullable', 'string', 'max:32', 'regex:/^[\d\s\+\-\(\)]+$/'],
                'department_id' => $departmentRules,
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $departmentName = (string) (Department::whereKey($validated['department_id'])->value('name') ?? '');
            $mobileNormalized = filled($validated['mobile'] ?? null)
                ? preg_replace('/\s+/', ' ', trim((string) $validated['mobile']))
                : null;

            DB::transaction(function () use (
                $collegeId,
                $program,
                $validated,
                $departmentName,
                $mobileNormalized
            ) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                    'role' => 'STUDENT',
                    'college_id' => $collegeId,
                    'department_id' => (int) $validated['department_id'],
                    'roll_number' => $validated['roll_number'],
                    'mobile' => $mobileNormalized,
                ]);

                ProgramStudent::create([
                    'college_id' => $collegeId,
                    'program_id' => $program->id,
                    'user_id' => $user->id,
                    'student_name' => $user->name,
                    'student_identifier' => $validated['roll_number'],
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'department_id' => (int) $validated['department_id'],
                    'department' => $departmentName !== '' ? $departmentName : '—',
                    'status' => 'registered',
                ]);
            });

            return redirect()->route('manager.program.students.index', $program)
                ->with('success', 'Student account created and added to this subject/program. Share their email and the password you set so they can sign in.');
        }

        return redirect()->route('manager.program.students.index', $program)
            ->with('success', 'Student added successfully.');
    }

    public function edit(Program $program, ProgramStudent $student): View
    {
        $this->ensureProgramStudentInScope($program, $student);

        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();

        $program->load('departments');
        $scopedIds = $program->scopedDepartmentIds();
        $departments = $scopedIds->isNotEmpty()
            ? Department::where('college_id', $program->college_id)->whereIn('id', $scopedIds)->orderBy('name')->get()
            : Department::where('college_id', $program->college_id)->orderBy('name')->get();
        $student->load(['user', 'collegeDepartment']);

        return view('manager.programs.students-edit', compact('program', 'student', 'credential', 'departments'));
    }

    public function update(Request $request, Program $program, ProgramStudent $student): RedirectResponse
    {
        $this->ensureProgramStudentInScope($program, $student);

        $collegeId = $program->college_id;

        $program->loadMissing('departments');
        $scopedIds = $program->scopedDepartmentIds();
        $departmentRules = [
            'required',
            'integer',
            $scopedIds->isNotEmpty()
                ? Rule::in($scopedIds->all())
                : Rule::exists('departments', 'id')->where(fn ($q) => $q->where('college_id', $collegeId)),
        ];

        $validated = $request->validate([
            'student_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'mobile' => ['required', 'string', 'max:32', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'department_id' => $departmentRules,
            'student_identifier' => ['nullable', 'string', 'max:255'],
            'manager_remarks' => ['nullable', 'string', 'max:10000'],
        ]);

        $remarks = $validated['manager_remarks'] ?? null;
        $student->update([
            'student_name' => $validated['student_name'],
            'student_identifier' => $validated['student_identifier'] ?? null,
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'department_id' => (int) $validated['department_id'],
            'manager_remarks' => ($remarks === null || $remarks === '') ? null : $remarks,
        ]);

        return redirect()->route('manager.program.students.index', $program)
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Program $program, ProgramStudent $student): RedirectResponse
    {
        $this->ensureProgramStudentInScope($program, $student);

        $student->delete();

        return redirect()->route('manager.program.students.index', $program)
            ->with('success', 'Student removed successfully.');
    }

    public function remarks(Program $program): View
    {
        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();

        $program->loadMissing('departments');
        $students = ProgramStudent::sortByRollThenName(
            $program->programStudentsQuery()
                ->with(['user.department', 'collegeDepartment'])
                ->latest()
                ->get()
        );

        return view('manager.programs.remarks', compact(
            'program',
            'students',
            'credential'
        ));
    }

    public function updateRemarks(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'remarks' => ['nullable', 'array'],
            'remarks.*' => ['nullable', 'string', 'max:10000'],
        ]);

        $remarks = $validated['remarks'] ?? [];
        if (empty($remarks)) {
            return redirect()->route('manager.program.remarks.index', $program)
                ->with('error', 'No remarks were submitted.');
        }

        $program->loadMissing('departments');
        $allowedIds = $program->programStudentsQuery()->pluck('id')->flip();
        $updated = 0;

        foreach ($remarks as $studentId => $text) {
            $studentId = (int) $studentId;
            if (! isset($allowedIds[$studentId])) {
                continue;
            }
            $text = trim((string) ($text ?? ''));

            ProgramStudent::where('program_id', $program->id)
                ->where('id', $studentId)
                ->update([
                    'manager_remarks' => $text === '' ? null : $text,
                ]);
            $updated++;
        }

        if ($updated === 0) {
            return redirect()->route('manager.program.remarks.index', $program)
                ->with('error', 'No remarks were saved.');
        }

        return redirect()->route('manager.program.remarks.index', $program)
            ->with('success', 'Remarks saved successfully.');
    }

    private function ensureProgramStudentInScope(Program $program, ProgramStudent $student): void
    {
        if ($student->program_id !== $program->id) {
            abort(403, 'Unauthorized access to this student.');
        }

        $program->loadMissing('departments');
        $ids = $program->scopedDepartmentIds();
        if ($ids->isEmpty()) {
            return;
        }

        if ($student->department_id === null || ! $ids->contains((int) $student->department_id)) {
            abort(404);
        }
    }
}
