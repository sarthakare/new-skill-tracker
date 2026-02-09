<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramAttendance;
use App\Models\ProgramManagerCredential;
use App\Models\ProgramSession;
use App\Models\ProgramStudent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramAttendanceController extends Controller
{
    public function edit(Program $program, ProgramSession $session): View
    {
        if ($session->program_id !== $program->id) {
            abort(403, 'Unauthorized access to this session.');
        }

        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();
        $students = ProgramStudent::where('program_id', $program->id)->orderBy('student_name')->get();
        $attendance = ProgramAttendance::where('program_session_id', $session->id)->get()->keyBy('program_student_id');

        return view('manager.programs.attendance', compact('program', 'session', 'students', 'attendance', 'credential'));
    }

    public function store(Request $request, Program $program, ProgramSession $session): RedirectResponse
    {
        if ($session->program_id !== $program->id) {
            abort(403, 'Unauthorized access to this session.');
        }

        $validated = $request->validate([
            'attendance' => ['array'],
            'method' => ['required', 'in:QR,Manual'],
        ]);

        $studentIds = ProgramStudent::where('program_id', $program->id)->pluck('id')->toArray();
        $presentIds = $validated['attendance'] ?? [];

        foreach ($studentIds as $studentId) {
            ProgramAttendance::updateOrCreate(
                [
                    'program_session_id' => $session->id,
                    'program_student_id' => $studentId,
                ],
                [
                    'status' => in_array($studentId, $presentIds) ? 'present' : 'absent',
                    'method' => $validated['method'],
                ]
            );
        }

        $session->update(['status' => 'completed']);

        return redirect()->route('manager.program.sessions.index', $program)
            ->with('success', 'Attendance saved successfully.');
    }

    public function report(Program $program, ProgramSession $session): View
    {
        if ($session->program_id !== $program->id) {
            abort(403, 'Unauthorized access to this session.');
        }

        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();
        $students = ProgramStudent::where('program_id', $program->id)->orderBy('student_name')->get();
        $attendance = ProgramAttendance::where('program_session_id', $session->id)->get()->keyBy('program_student_id');

        $presentCount = $attendance->where('status', 'present')->count();
        $absentCount = $students->count() - $presentCount;

        return view('manager.programs.attendance-report', compact('program', 'session', 'students', 'attendance', 'credential', 'presentCount', 'absentCount'));
    }
}
