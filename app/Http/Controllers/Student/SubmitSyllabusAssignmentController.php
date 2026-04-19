<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudent;
use App\Models\SyllabusAssignment;
use App\Models\SyllabusAssignmentSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubmitSyllabusAssignmentController extends Controller
{
    private const MAX_SOURCE_CHARS = 100_000;

    public function __invoke(Request $request, SyllabusAssignment $assignment): JsonResponse
    {
        $user = $request->user();
        if (! $user->isStudent()) {
            return response()->json(['ok' => false, 'error' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'source_code' => ['required', 'string', 'max:'.self::MAX_SOURCE_CHARS],
            'language_id' => ['required', 'integer', 'min:1'],
        ]);

        $assignment->loadMissing('syllabusSubtopic.syllabusTopic');
        $programId = $assignment->programId();
        if ($programId === null) {
            return response()->json(['ok' => false, 'error' => 'Invalid assignment.'], 404);
        }

        $enrolled = ProgramStudent::query()
            ->where('user_id', $user->id)
            ->where('program_id', $programId)
            ->exists();

        if (! $enrolled) {
            return response()->json(['ok' => false, 'error' => 'You are not enrolled in this program.'], 403);
        }

        $languageId = (int) $validated['language_id'];
        if (! $assignment->allowsJudge0LanguageId($languageId)) {
            return response()->json([
                'ok' => false,
                'error' => 'This language is not allowed for this assignment.',
            ], 422);
        }

        if (SyllabusAssignmentSubmission::query()
            ->where('user_id', $user->id)
            ->where('syllabus_assignment_id', $assignment->id)
            ->exists()) {
            return response()->json(['ok' => false, 'error' => 'This assignment is already submitted.'], 422);
        }

        $submission = SyllabusAssignmentSubmission::query()->create([
            'user_id' => $user->id,
            'syllabus_assignment_id' => $assignment->id,
            'source_code' => $validated['source_code'],
            'judge0_language_id' => $languageId,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Assignment submitted successfully.',
            'submitted_at_display' => $submission->created_at?->timezone(config('app.timezone'))->format('M j, Y g:i A'),
        ]);
    }
}
