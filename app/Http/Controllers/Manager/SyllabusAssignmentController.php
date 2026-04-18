<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\StoreSyllabusAssignmentRequest;
use App\Http\Requests\Manager\UpdateSyllabusAssignmentRequest;
use App\Models\Program;
use App\Models\ProgramManagerCredential;
use App\Models\SyllabusAssignment;
use App\Models\SyllabusSubtopic;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SyllabusAssignmentController extends Controller
{
    public function create(Program $program, SyllabusSubtopic $subtopic): View
    {
        $this->ensureSubtopicInProgram($program, $subtopic);

        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();
        $program->load(['event', 'college', 'vendorManager', 'independentManager']);
        $subtopic->load('syllabusTopic');

        return view('manager.programs.syllabus-assignment-create', compact('program', 'subtopic', 'credential'));
    }

    public function store(StoreSyllabusAssignmentRequest $request, Program $program, SyllabusSubtopic $subtopic): RedirectResponse
    {
        $this->ensureSubtopicInProgram($program, $subtopic);

        $data = $request->validated();
        $languageIds = array_values(array_unique(array_map(
            static fn ($id) => (int) $id,
            $data['languages_supported'] ?? []
        )));

        SyllabusAssignment::create([
            'syllabus_subtopic_id' => $subtopic->id,
            'title' => $data['title'],
            'description' => $data['description'],
            'difficulty' => $data['difficulty'],
            'starter_code' => $data['starter_code'] ?? null,
            'test_cases' => $data['test_cases'] ?? null,
            'expected_output' => $data['expected_output'] ?? null,
            'time_limit' => isset($data['time_limit']) ? (int) $data['time_limit'] : null,
            'languages_supported' => $languageIds,
        ]);

        return redirect()
            ->route('manager.program.syllabus.index', $program)
            ->withFragment('topic-'.$subtopic->syllabus_topic_id)
            ->with('success', 'Assignment created successfully.');
    }

    public function edit(Program $program, SyllabusAssignment $assignment): View
    {
        $this->ensureAssignmentInProgram($program, $assignment);

        $credential = ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->firstOrFail();
        $program->load(['event', 'college', 'vendorManager', 'independentManager']);
        $subtopic = $assignment->syllabusSubtopic;
        $subtopic->load('syllabusTopic');

        return view('manager.programs.syllabus-assignment-edit', compact('program', 'subtopic', 'assignment', 'credential'));
    }

    public function update(UpdateSyllabusAssignmentRequest $request, Program $program, SyllabusAssignment $assignment): RedirectResponse
    {
        $this->ensureAssignmentInProgram($program, $assignment);

        $data = $request->validated();
        $languageIds = array_values(array_unique(array_map(
            static fn ($id) => (int) $id,
            $data['languages_supported'] ?? []
        )));

        $assignment->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'difficulty' => $data['difficulty'],
            'starter_code' => $data['starter_code'] ?? null,
            'test_cases' => $data['test_cases'] ?? null,
            'expected_output' => $data['expected_output'] ?? null,
            'time_limit' => isset($data['time_limit']) ? (int) $data['time_limit'] : null,
            'languages_supported' => $languageIds,
        ]);

        $topicId = $assignment->syllabusSubtopic->syllabus_topic_id;

        return redirect()
            ->route('manager.program.syllabus.index', $program)
            ->withFragment('topic-'.$topicId)
            ->with('success', 'Assignment updated successfully.');
    }

    private function ensureAssignmentInProgram(Program $program, SyllabusAssignment $assignment): void
    {
        $assignment->loadMissing('syllabusSubtopic.syllabusTopic');
        if ($assignment->syllabusSubtopic->syllabusTopic->program_id !== $program->id) {
            abort(403);
        }
    }

    private function ensureSubtopicInProgram(Program $program, SyllabusSubtopic $subtopic): void
    {
        $subtopic->loadMissing('syllabusTopic');
        if ($subtopic->syllabusTopic->program_id !== $program->id) {
            abort(403);
        }
    }
}
