<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudent;
use App\Models\SyllabusAssignment;
use App\Models\SyllabusAssignmentSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Judge0RunController extends Controller
{
    private const MAX_SOURCE_CHARS = 100_000;

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'source_code' => ['required', 'string', 'max:'.self::MAX_SOURCE_CHARS],
            'language_id' => ['required', 'integer', 'min:1'],
            'stdin' => ['nullable', 'string', 'max:65536'],
            'assignment_id' => ['nullable', 'integer', 'exists:syllabus_assignments,id'],
        ]);

        $languageId = (int) $validated['language_id'];
        $assignmentId = isset($validated['assignment_id']) ? (int) $validated['assignment_id'] : null;
        if ($assignmentId !== null) {
            $assignment = SyllabusAssignment::query()
                ->with(['syllabusSubtopic.syllabusTopic'])
                ->find($assignmentId);
            if (! $assignment) {
                return response()->json([
                    'ok' => false,
                    'error' => 'Assignment not found.',
                ], 422);
            }
            $programId = $assignment->programId();
            if ($programId === null) {
                return response()->json([
                    'ok' => false,
                    'error' => 'Invalid assignment.',
                ], 422);
            }
            $enrolled = ProgramStudent::query()
                ->where('user_id', $request->user()->id)
                ->where('program_id', $programId)
                ->exists();
            if (! $enrolled) {
                return response()->json([
                    'ok' => false,
                    'error' => 'You are not enrolled in the subject/program for this assignment.',
                ], 403);
            }
            if (! $assignment->allowsJudge0LanguageId($languageId)) {
                return response()->json([
                    'ok' => false,
                    'error' => 'This language is not allowed for this assignment. Choose one of the languages your instructor enabled.',
                ], 422);
            }
            if (SyllabusAssignmentSubmission::query()
                ->where('user_id', $request->user()->id)
                ->where('syllabus_assignment_id', $assignmentId)
                ->exists()) {
                return response()->json([
                    'ok' => false,
                    'error' => 'This assignment is already submitted and cannot be changed.',
                ], 422);
            }
        }

        $baseUrl = rtrim(config('services.judge0.url'), '/');
        if ($baseUrl === '') {
            return response()->json([
                'ok' => false,
                'error' => 'Code runner is not configured. Set JUDGE0_URL in the environment.',
            ], 503);
        }

        $headers = array_filter([
            'X-Auth-Token' => config('services.judge0.token') ?: null,
            'X-Auth-User' => config('services.judge0.user') ?: null,
        ]);

        $payload = [
            'source_code' => base64_encode($validated['source_code']),
            'language_id' => $languageId,
            'stdin' => base64_encode($validated['stdin'] ?? ''),
        ];

        $query = http_build_query([
            'base64_encoded' => 'true',
            'wait' => 'true',
        ]);

        try {
            $response = Http::withHeaders($headers)
                ->timeout((int) config('services.judge0.timeout', 60))
                ->acceptJson()
                ->post("{$baseUrl}/submissions?{$query}", $payload);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'error' => 'Could not reach the code judge server.',
            ], 502);
        }

        if (! $response->successful()) {
            return response()->json([
                'ok' => false,
                'error' => $this->formatHttpError($response->status(), $response->body()),
            ], 502);
        }

        $data = $response->json();
        if (! is_array($data)) {
            return response()->json([
                'ok' => false,
                'error' => 'Unexpected response from the code judge.',
            ], 502);
        }

        return response()->json([
            'ok' => true,
            'status' => $data['status'] ?? null,
            'stdout' => $this->decodeB64Field($data['stdout'] ?? null),
            'stderr' => $this->decodeB64Field($data['stderr'] ?? null),
            'compile_output' => $this->decodeB64Field($data['compile_output'] ?? null),
            'time' => $data['time'] ?? null,
            'memory' => $data['memory'] ?? null,
            'message' => $data['message'] ?? null,
        ]);
    }

    private function decodeB64Field(mixed $value): ?string
    {
        if (! is_string($value) || $value === '') {
            return is_string($value) ? $value : null;
        }

        $decoded = base64_decode($value, true);
        if ($decoded === false) {
            return $value;
        }

        return $decoded;
    }

    private function formatHttpError(int $status, string $body): string
    {
        $snippet = Str::limit(trim(strip_tags($body)), 200);

        return $snippet !== ''
            ? "Judge returned HTTP {$status}: {$snippet}"
            : "Judge returned HTTP {$status}.";
    }
}
