<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ProgramAttendance;
use App\Models\ProgramStudent;
use App\Models\ProgramStudentAssignmentRemark;
use App\Models\SyllabusAssignment;
use App\Models\SyllabusAssignmentSubmission;
use App\Models\SyllabusTopic;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
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

        $programIds = $enrollments->pluck('program_id')->unique()->filter()->values();

        $submissionsByProgramId = collect();
        if ($programIds->isNotEmpty()) {
            $assignmentIds = SyllabusAssignment::query()
                ->whereHas('syllabusSubtopic.syllabusTopic', fn ($q) => $q->whereIn('program_id', $programIds))
                ->pluck('id');
            if ($assignmentIds->isNotEmpty()) {
                $submissionsByProgramId = SyllabusAssignmentSubmission::query()
                    ->where('user_id', $user->id)
                    ->whereIn('syllabus_assignment_id', $assignmentIds)
                    ->with(['syllabusAssignment.syllabusSubtopic.syllabusTopic'])
                    ->orderByDesc('created_at')
                    ->get()
                    ->groupBy(fn (SyllabusAssignmentSubmission $s) => $s->syllabusAssignment->programId());
            }
        }

        $assignmentRemarksByEnrollmentId = collect();
        if ($enrollments->isNotEmpty()) {
            $assignmentRemarksByEnrollmentId = ProgramStudentAssignmentRemark::query()
                ->whereIn('program_student_id', $enrollments->pluck('id'))
                ->with(['syllabusAssignment'])
                ->get()
                ->groupBy('program_student_id')
                ->map(fn ($rows) => $rows->keyBy('syllabus_assignment_id'));
        }

        $syllabusTopicsByProgram = $programIds->isEmpty()
            ? collect()
            : SyllabusTopic::query()
                ->whereIn('program_id', $programIds)
                ->with(['subtopics.assignments'])
                ->orderBy('sort_order')
                ->get()
                ->groupBy('program_id');

        $activeAssignment = null;
        $activeAssignmentSubmitted = false;
        $activeAssignmentSubmission = null;
        $codeRunnerLanguages = config('judge0.languages', []);
        if ($request->filled('assignment')) {
            $assignment = SyllabusAssignment::query()
                ->whereKey($request->integer('assignment'))
                ->with(['syllabusSubtopic.syllabusTopic'])
                ->first();
            if ($assignment) {
                $pid = $assignment->programId();
                if ($pid !== null && $enrollments->pluck('program_id')->contains($pid)) {
                    $activeAssignment = $assignment;
                    $codeRunnerLanguages = $assignment->allowedJudge0Languages();
                    $activeAssignmentSubmission = SyllabusAssignmentSubmission::query()
                        ->where('user_id', $user->id)
                        ->where('syllabus_assignment_id', $assignment->id)
                        ->first();
                    $activeAssignmentSubmitted = $activeAssignmentSubmission !== null;
                }
            }
        }

        return view('student.dashboard', [
            'user' => $user,
            'college' => $user->college,
            'enrollments' => $enrollments,
            'attendanceByEnrollment' => $attendanceByEnrollment,
            'syllabusTopicsByProgram' => $syllabusTopicsByProgram,
            'activeAssignment' => $activeAssignment,
            'activeAssignmentSubmitted' => $activeAssignmentSubmitted,
            'activeAssignmentSubmission' => $activeAssignmentSubmission,
            'codeRunnerLanguages' => $codeRunnerLanguages,
            'submissionsByProgramId' => $submissionsByProgramId,
            'assignmentRemarksByEnrollmentId' => $assignmentRemarksByEnrollmentId,
        ]);
    }
}
