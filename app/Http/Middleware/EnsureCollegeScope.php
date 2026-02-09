<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCollegeScope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->isCollegeAdmin() || !$user->college_id) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. College Admin access required.'], 403);
            }

            return redirect()->route('login')->with('error', 'Unauthorized. College Admin access required.');
        }

        // Ensure the user's college is active
        if ($user->college && $user->college->status !== 'active') {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Your college account is inactive. Please contact the administrator.');
        }

        return $next($request);
    }
}
