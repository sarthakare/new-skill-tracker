<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
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
        ]);

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
            'language_id' => (int) $validated['language_id'],
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
