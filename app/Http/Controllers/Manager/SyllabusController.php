<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramManagerCredential;
use App\Models\SyllabusSubtopic;
use App\Models\SyllabusTopic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SyllabusController extends Controller
{
    public function index(Program $program): View
    {
        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();
        $program->load(['event', 'college', 'vendorManager', 'independentManager']);
        $topics = SyllabusTopic::where('program_id', $program->id)
            ->with(['subtopics.assignments'])
            ->orderBy('sort_order')
            ->get();

        return view('manager.programs.syllabus', compact('program', 'topics', 'credential'));
    }

    public function storeTopic(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $maxOrder = SyllabusTopic::where('program_id', $program->id)->max('sort_order') ?? 0;
        SyllabusTopic::create([
            'program_id' => $program->id,
            'title' => $validated['title'],
            'sort_order' => $maxOrder + 1,
        ]);

        return redirect()->route('manager.program.syllabus.index', $program)
            ->with('success', 'Topic added successfully.');
    }

    public function storeSubtopic(Request $request, Program $program, SyllabusTopic $topic): RedirectResponse
    {
        if ($topic->program_id !== $program->id) {
            abort(403);
        }
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $maxOrder = SyllabusSubtopic::where('syllabus_topic_id', $topic->id)->max('sort_order') ?? 0;
        SyllabusSubtopic::create([
            'syllabus_topic_id' => $topic->id,
            'title' => $validated['title'],
            'sort_order' => $maxOrder + 1,
        ]);

        return redirect()->route('manager.program.syllabus.index', $program)->withFragment('topic-'.$topic->id);
    }

    public function updateSubtopicSchedule(Request $request, Program $program, SyllabusSubtopic $subtopic): RedirectResponse
    {
        if ($subtopic->syllabusTopic->program_id !== $program->id) {
            abort(403);
        }
        $request->merge([
            'scheduled_date' => $request->filled('scheduled_date') ? $request->input('scheduled_date') : null,
            'scheduled_time' => $request->filled('scheduled_time') ? $request->input('scheduled_time') : null,
        ]);
        $validated = $request->validate([
            'scheduled_date' => ['nullable', 'date'],
            'scheduled_time' => ['nullable', 'date_format:H:i'],
        ]);

        $subtopic->update([
            'scheduled_date' => $validated['scheduled_date'],
            'scheduled_time' => $validated['scheduled_time'],
        ]);

        return redirect()->route('manager.program.syllabus.index', $program)->withFragment('topic-'.$subtopic->syllabusTopic->id);
    }

    public function toggleTopicComplete(Program $program, SyllabusTopic $topic): RedirectResponse
    {
        if ($topic->program_id !== $program->id) {
            abort(403);
        }
        $isComplete = ! $topic->is_complete;
        $topic->update(['is_complete' => $isComplete]);
        if ($isComplete) {
            $topic->subtopics()->update(['is_complete' => true]);
        }

        return redirect()->route('manager.program.syllabus.index', $program);
    }

    public function updateTopicSchedule(Request $request, Program $program, SyllabusTopic $topic): RedirectResponse
    {
        if ($topic->program_id !== $program->id) {
            abort(403);
        }
        $request->merge([
            'scheduled_date' => $request->filled('scheduled_date') ? $request->input('scheduled_date') : null,
            'scheduled_time' => $request->filled('scheduled_time') ? $request->input('scheduled_time') : null,
        ]);
        $validated = $request->validate([
            'scheduled_date' => ['nullable', 'date'],
            'scheduled_time' => ['nullable', 'date_format:H:i'],
        ]);

        $topic->update([
            'scheduled_date' => $validated['scheduled_date'],
            'scheduled_time' => $validated['scheduled_time'],
        ]);

        return redirect()->route('manager.program.syllabus.index', $program)->withFragment('topic-'.$topic->id);
    }

    public function updateTopic(Request $request, Program $program, SyllabusTopic $topic): RedirectResponse
    {
        if ($topic->program_id !== $program->id) {
            abort(403);
        }
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);
        $topic->update(['title' => $validated['title']]);

        return redirect()->route('manager.program.syllabus.index', $program)->withFragment('topic-'.$topic->id);
    }

    public function destroyTopic(Program $program, SyllabusTopic $topic): RedirectResponse
    {
        if ($topic->program_id !== $program->id) {
            abort(403);
        }
        $topic->delete();

        return redirect()->route('manager.program.syllabus.index', $program);
    }

    public function updateSubtopic(Request $request, Program $program, SyllabusSubtopic $subtopic): RedirectResponse
    {
        if ($subtopic->syllabusTopic->program_id !== $program->id) {
            abort(403);
        }
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);
        $subtopic->update(['title' => $validated['title']]);

        return redirect()->route('manager.program.syllabus.index', $program)->withFragment('topic-'.$subtopic->syllabusTopic->id);
    }

    public function destroySubtopic(Program $program, SyllabusSubtopic $subtopic): RedirectResponse
    {
        if ($subtopic->syllabusTopic->program_id !== $program->id) {
            abort(403);
        }
        $topicId = $subtopic->syllabusTopic->id;
        $subtopic->delete();

        return redirect()->route('manager.program.syllabus.index', $program)->withFragment('topic-'.$topicId);
    }

    public function toggleSubtopicComplete(Program $program, SyllabusSubtopic $subtopic): RedirectResponse
    {
        if ($subtopic->syllabusTopic->program_id !== $program->id) {
            abort(403);
        }
        $subtopic->update(['is_complete' => ! $subtopic->is_complete]);
        $message = $subtopic->is_complete ? 'Subtopic marked as complete.' : 'Subtopic marked as incomplete.';

        return redirect()->route('manager.program.syllabus.index', $program)
            ->with('success', $message);
    }
}
