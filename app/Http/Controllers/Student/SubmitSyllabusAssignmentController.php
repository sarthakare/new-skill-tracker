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
    public function __invoke(Request $request, SyllabusAssignment $assignment): JsonResponse
    {
        $user = $request->user();
        if (! $user->isStudent()) {
            return response()->json(['ok' => false, 'error' => 'Unauthorized.'], 403);
        }

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

        if (SyllabusAssignmentSubmission::query()
            ->where('user_id', $user->id)
            ->where('syllabus_assignment_id', $assignment->id)
            ->exists()) {
            return response()->json(['ok' => false, 'error' => 'This assignment is already submitted.'], 422);
        }

        SyllabusAssignmentSubmission::query()->create([
            'user_id' => $user->id,
            'syllabus_assignment_id' => $assignment->id,
        ]);

        return response()->json(['ok' => true, 'message' => 'Assignment submitted successfully.']);
    }
}
