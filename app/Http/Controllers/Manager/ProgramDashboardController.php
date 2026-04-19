<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramAttendance;
use App\Models\ProgramManagerCredential;
use App\Models\SyllabusAssignment;
use App\Models\SyllabusSubtopic;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ProgramDashboardController extends Controller
{
    public function index(Program $program): View
    {
        $credential = ProgramManagerCredential::query()
            ->where('id', session('program_manager_credential_id'))
            ->firstOrFail();

        $program->loadMissing([
            'event',
            'college',
            'departments',
            'oversightManager',
            'vendorManager',
            'independentManager',
        ]);

        $today = Carbon::today();

        $studentsQuery = $program->programStudentsQuery();

        $sessionsCount = $program->sessions()->count();
        $upcomingSessionsCount = $program->sessions()
            ->whereDate('session_date', '>=', $today)
            ->count();
        $pastSessionsCount = $program->sessions()
            ->whereDate('session_date', '<', $today)
            ->count();

        $nextSession = $program->sessions()
            ->whereDate('session_date', '>=', $today)
            ->orderBy('session_date')
            ->orderBy('start_time')
            ->first();

        $lastSession = $program->sessions()
            ->whereDate('session_date', '<', $today)
            ->orderByDesc('session_date')
            ->orderByDesc('start_time')
            ->first();

        $topicsCount = $program->syllabusTopics()->count();
        $topicsCompleteCount = $program->syllabusTopics()->where('is_complete', true)->count();

        $subtopicsCount = SyllabusSubtopic::query()
            ->whereHas('syllabusTopic', fn ($q) => $q->where('program_id', $program->id))
            ->count();
        $subtopicsCompleteCount = SyllabusSubtopic::query()
            ->whereHas('syllabusTopic', fn ($q) => $q->where('program_id', $program->id))
            ->where('is_complete', true)
            ->count();

        $assignmentsCount = SyllabusAssignment::query()
            ->whereHas('syllabusSubtopic.syllabusTopic', fn ($q) => $q->where('program_id', $program->id))
            ->count();

        $feedbackCount = $program->feedback()->count();

        $attendanceMarksCount = ProgramAttendance::query()
            ->whereHas('student', fn ($q) => $q->where('program_id', $program->id))
            ->count();

        $studentsWithRemarksCount = (clone $studentsQuery)
            ->whereNotNull('manager_remarks')
            ->where('manager_remarks', '!=', '')
            ->count();

        $completionByStatus = $program->completionRequests()
            ->selectRaw('status, count(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status');

        $stats = [
            'program_name' => $program->name,
            'event_name' => $program->event->name,
            'status' => $program->status,
            'status_label' => str_replace('_', ' ', $program->status),
            'students_count' => $studentsQuery->count(),
            'sessions_count' => $sessionsCount,
            'upcoming_sessions_count' => $upcomingSessionsCount,
            'past_sessions_count' => $pastSessionsCount,
            'pending_completion' => (int) ($completionByStatus['pending'] ?? 0),
            'approved_completion' => (int) ($completionByStatus['approved'] ?? 0),
            'rejected_completion' => (int) ($completionByStatus['rejected'] ?? 0),
            'manager_name' => $credential->managerLabel(),
            'manager_account_type' => $credential->manager_type ?? '—',
            'college_name' => $program->college?->name ?? '—',
            'departments_label' => $program->departmentsLabel(),
            'program_type' => $program->type ?? '—',
            'mode' => $program->mode ?? '—',
            'duration_days' => (int) ($program->duration_days ?? 0),
            'executor_label' => $program->executorLabel(),
            'oversight_manager_name' => $program->oversightManager?->name,
            'topics_count' => $topicsCount,
            'topics_complete_count' => $topicsCompleteCount,
            'subtopics_count' => $subtopicsCount,
            'subtopics_complete_count' => $subtopicsCompleteCount,
            'assignments_count' => $assignmentsCount,
            'feedback_count' => $feedbackCount,
            'attendance_marks_count' => $attendanceMarksCount,
            'students_with_remarks_count' => $studentsWithRemarksCount,
        ];

        $event = $program->event;
        $eventWindow = null;
        if ($event && ($event->start_date || $event->end_date)) {
            $eventWindow = [
                'start' => $event->start_date,
                'end' => $event->end_date,
                'status' => $event->status ?? null,
            ];
        }

        $statusBadgeClass = match ($program->status) {
            'Draft' => 'bg-slate-100 text-slate-700 ring-slate-200/80',
            'Manager_Assigned' => 'bg-violet-50 text-violet-800 ring-violet-200/80',
            'Registration_Open' => 'bg-sky-50 text-sky-800 ring-sky-200/80',
            'In_Progress' => 'bg-amber-50 text-amber-900 ring-amber-200/80',
            'Completed', 'Approved' => 'bg-emerald-50 text-emerald-800 ring-emerald-200/80',
            default => 'bg-slate-100 text-slate-700 ring-slate-200/80',
        };

        return view('manager.programs.dashboard', compact(
            'program',
            'credential',
            'stats',
            'nextSession',
            'lastSession',
            'eventWindow',
            'statusBadgeClass'
        ));
    }
}
