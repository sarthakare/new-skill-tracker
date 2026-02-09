<?php

namespace App\Http\Middleware;

use App\Models\ProgramManagerCredential;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProgramManagerAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $credentialId = session('program_manager_credential_id');

        if (!$credentialId) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Program manager access required.'], 403);
            }

            return redirect()->route('login')
                ->with('error', 'Please login to access the program dashboard.');
        }

        $credential = ProgramManagerCredential::with(['program', 'college'])
            ->where('id', $credentialId)
            ->where('status', 'active')
            ->first();

        if (!$credential) {
            session()->forget([
                'program_manager_credential_id',
                'program_id',
                'program_manager_type',
                'program_manager_id',
            ]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid or inactive credentials.'], 403);
            }

            return redirect()->route('login')
                ->with('error', 'Your credentials are invalid or inactive.');
        }

        if ($request->route('program')) {
            $program = $request->route('program');
            $programId = is_object($program) ? $program->id : $program;

            if ($credential->program_id != $programId) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Unauthorized access to this program.'], 403);
                }

                return redirect()->route('manager.program.dashboard', $credential->program_id)
                    ->with('error', 'You do not have access to this program.');
            }
        }

        return $next($request);
    }
}
