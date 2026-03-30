<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ProgramAttendance;
use App\Models\ProgramStudent;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $user->load(['college', 'department']);

        $enrollments = ProgramStudent::query()
            ->where('user_id', $user->id)
            ->with([
                'program.event',
                'collegeDepartment',
            ])
            ->orderByDesc('updated_at')
            ->get();

        $attendanceByEnrollment = collect();
        if ($enrollments->isNotEmpty()) {
            $attendanceByEnrollment = ProgramAttendance::query()
                ->whereIn('program_student_id', $enrollments->pluck('id'))
                ->where('status', 'present')
                ->with('session')
                ->get()
                ->groupBy('program_student_id');
        }

        return view('student.dashboard', [
            'user' => $user,
            'college' => $user->college,
            'enrollments' => $enrollments,
            'attendanceByEnrollment' => $attendanceByEnrollment,
        ]);
    }
}
