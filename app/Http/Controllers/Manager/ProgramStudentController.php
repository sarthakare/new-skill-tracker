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
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProgramStudentController extends Controller
{
    public function index(Program $program): View
    {
        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();

        $collegeId = $program->college_id;

        $assignedUserIds = ProgramStudent::where('program_id', $program->id)
            ->whereNotNull('user_id')
            ->pluck('user_id');

        $registeredStudents = User::query()
            ->where('college_id', $collegeId)
            ->where('role', 'STUDENT')
            ->whereNotIn('id', $assignedUserIds)
            ->with('department')
            ->orderBy('name')
            ->get();

        $departments = Department::where('college_id', $collegeId)->orderBy('name')->get();

        $students = ProgramStudent::where('program_id', $program->id)
            ->with(['user.department', 'collegeDepartment'])
            ->latest()
            ->get();

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
                    ->with('error', 'That student is already in this program.');
            }

            $user = User::with('department')->findOrFail($validated['user_id']);

            $departmentId = $user->department_id;
            $departmentName = $user->department?->name ?? '';

            ProgramStudent::create([
                'college_id' => $collegeId,
                'program_id' => $program->id,
                'user_id' => $user->id,
                'student_name' => $user->name,
                'student_identifier' => null,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'department_id' => $departmentId,
                'department' => $departmentName ?: '—',
                'status' => 'registered',
            ]);
        } else {
            $validated = $request->validate([
                'student_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'mobile' => ['required', 'string', 'max:32', 'regex:/^[\d\s\+\-\(\)]+$/'],
                'department_id' => [
                    'required',
                    'integer',
                    Rule::exists('departments', 'id')->where(fn ($q) => $q->where('college_id', $collegeId)),
                ],
                'student_identifier' => ['nullable', 'string', 'max:255'],
            ]);

            ProgramStudent::create([
                'college_id' => $collegeId,
                'program_id' => $program->id,
                'user_id' => null,
                'student_name' => $validated['student_name'],
                'student_identifier' => $validated['student_identifier'] ?? null,
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'department_id' => (int) $validated['department_id'],
                'department' => Department::whereKey($validated['department_id'])->value('name') ?? '',
                'status' => 'registered',
            ]);
        }

        return redirect()->route('manager.program.students.index', $program)
            ->with('success', 'Student added successfully.');
    }

    public function edit(Program $program, ProgramStudent $student): View
    {
        if ($student->program_id !== $program->id) {
            abort(403, 'Unauthorized access to this student.');
        }

        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();

        $departments = Department::where('college_id', $program->college_id)->orderBy('name')->get();
        $student->load(['user', 'collegeDepartment']);

        return view('manager.programs.students-edit', compact('program', 'student', 'credential', 'departments'));
    }

    public function update(Request $request, Program $program, ProgramStudent $student): RedirectResponse
    {
        if ($student->program_id !== $program->id) {
            abort(403, 'Unauthorized access to this student.');
        }

        $collegeId = $program->college_id;

        $validated = $request->validate([
            'student_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'mobile' => ['required', 'string', 'max:32', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'department_id' => [
                'required',
                'integer',
                Rule::exists('departments', 'id')->where(fn ($q) => $q->where('college_id', $collegeId)),
            ],
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
        if ($student->program_id !== $program->id) {
            abort(403, 'Unauthorized access to this student.');
        }

        $student->delete();

        return redirect()->route('manager.program.students.index', $program)
            ->with('success', 'Student removed successfully.');
    }

    public function remarks(Program $program): View
    {
        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();

        $students = ProgramStudent::where('program_id', $program->id)
            ->with(['user.department', 'collegeDepartment'])
            ->latest()
            ->get();

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

        foreach ($remarks as $studentId => $text) {
            $studentId = (int) $studentId;
            $text = trim((string) ($text ?? ''));

            ProgramStudent::where('program_id', $program->id)
                ->where('id', $studentId)
                ->update([
                    'manager_remarks' => $text === '' ? null : $text,
                ]);
        }

        return redirect()->route('manager.program.remarks.index', $program)
            ->with('success', 'Remarks saved successfully.');
    }
}
