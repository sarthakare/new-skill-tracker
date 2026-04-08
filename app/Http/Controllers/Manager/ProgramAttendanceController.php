<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramAttendance;
use App\Models\ProgramManagerCredential;
use App\Models\ProgramSession;
use App\Models\ProgramStudent;
use App\Models\SyllabusTopic;
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
        $students = $this->programStudentsOrdered($program);
        $attendance = ProgramAttendance::where('program_session_id', $session->id)->get()->keyBy('program_student_id');
        $topics = SyllabusTopic::where('program_id', $program->id)->with('subtopics')->orderBy('sort_order')->get();
        $taughtTopicIds = $session->taughtSyllabus()->pluck('syllabus_topics.id')->toArray();

        return view('manager.programs.attendance', compact('program', 'session', 'students', 'attendance', 'credential', 'topics', 'taughtTopicIds'));
    }

    public function store(Request $request, Program $program, ProgramSession $session): RedirectResponse
    {
        if ($session->program_id !== $program->id) {
            abort(403, 'Unauthorized access to this session.');
        }

        $validated = $request->validate([
            'attendance' => ['array'],
            'syllabus_topics' => ['nullable', 'array'],
            'syllabus_topics.*' => ['integer', 'exists:syllabus_topics,id'],
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
                    'method' => 'Manual',
                ]
            );
        }

        $session->update(['status' => 'completed']);

        $topicIds = $validated['syllabus_topics'] ?? [];
        $validTopicIds = SyllabusTopic::where('program_id', $program->id)->whereIn('id', $topicIds)->pluck('id')->toArray();
        $session->taughtSyllabus()->sync($validTopicIds);

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
        $program->load(['event', 'college', 'vendorManager', 'independentManager']);
        $students = $this->programStudentsOrdered($program);
        $attendance = ProgramAttendance::where('program_session_id', $session->id)->get()->keyBy('program_student_id');

        $presentCount = $attendance->where('status', 'present')->count();
        $absentCount = $students->count() - $presentCount;

        return view('manager.programs.attendance-report', compact('program', 'session', 'students', 'attendance', 'credential', 'presentCount', 'absentCount'));
    }

    public function dailyReport(Program $program, ProgramSession $session): View
    {
        if ($session->program_id !== $program->id) {
            abort(403, 'Unauthorized access to this session.');
        }

        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();
        $program->load(['event', 'college', 'vendorManager', 'independentManager']);
        $attendance = ProgramAttendance::where('program_session_id', $session->id)->get()->keyBy('program_student_id');
        $taughtTopics = $session->taughtSyllabus()->with('subtopics')->orderBy('sort_order')->get();

        $presentCount = $attendance->where('status', 'present')->count();
        $totalCount = ProgramStudent::where('program_id', $program->id)->count();

        return view('manager.programs.daily-report', compact('program', 'session', 'credential', 'taughtTopics', 'presentCount', 'totalCount'));
    }

    public function completionReport(Program $program): View
    {
        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();
        $program->load(['syllabusTopics.subtopics', 'students', 'sessions', 'completionRequests', 'event', 'college', 'vendorManager', 'independentManager']);
        $topics = $program->syllabusTopics;
        $totalTopics = $topics->count();
        $completedTopics = $topics->where('is_complete', true)->count();
        $totalSubtopics = $topics->sum(fn ($t) => $t->subtopics->count());
        $completedSubtopics = $topics->sum(fn ($t) => $t->subtopics->where('is_complete', true)->count());
        $latestCompletion = $program->completionRequests->sortByDesc('created_at')->first();

        return view('manager.programs.completion-report', compact('program', 'credential', 'topics', 'totalTopics', 'completedTopics', 'totalSubtopics', 'completedSubtopics', 'latestCompletion'));
    }

    /**
     * @return \Illuminate\Support\Collection<int, ProgramStudent>
     */
    private function programStudentsOrdered(Program $program)
    {
        return ProgramStudent::sortByRollThenName(
            ProgramStudent::where('program_id', $program->id)
                ->with(['collegeDepartment', 'user'])
                ->get()
        );
    }
}
