<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramManagerCredential;
use App\Models\ProgramStudent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramStudentController extends Controller
{
    public function index(Program $program): View
    {
        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();
        $students = ProgramStudent::where('program_id', $program->id)
            ->latest()
            ->get();

        return view('manager.programs.students', compact('program', 'students', 'credential'));
    }

    public function store(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'student_name' => ['required', 'string', 'max:255'],
            'student_identifier' => ['nullable', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
        ]);

        ProgramStudent::create([
            'college_id' => $program->college_id,
            'program_id' => $program->id,
            'student_name' => $validated['student_name'],
            'student_identifier' => $validated['student_identifier'] ?? null,
            'department' => $validated['department'],
            'status' => 'registered',
        ]);

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

        return view('manager.programs.students-edit', compact('program', 'student', 'credential'));
    }

    public function update(Request $request, Program $program, ProgramStudent $student): RedirectResponse
    {
        if ($student->program_id !== $program->id) {
            abort(403, 'Unauthorized access to this student.');
        }

        $validated = $request->validate([
            'student_name' => ['required', 'string', 'max:255'],
            'student_identifier' => ['nullable', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
        ]);

        $student->update([
            'student_name' => $validated['student_name'],
            'student_identifier' => $validated['student_identifier'] ?? null,
            'department' => $validated['department'],
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
}
