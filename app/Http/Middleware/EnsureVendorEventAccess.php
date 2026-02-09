<?php

namespace App\Http\Middleware;

use App\Models\VendorEventCredential;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVendorEventAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $credentialId = session('vendor_event_credential_id');
        
        if (!$credentialId) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Vendor event access required.'], 403);
            }
            
            return redirect()->route('login')
                ->with('error', 'Please login to access the event dashboard.');
        }

        $credential = VendorEventCredential::where('id', $credentialId)
            ->where('status', 'active')
            ->first();

        if (!$credential) {
            session()->forget([
                'vendor_event_credential_id',
                'vendor_event_id',
                'vendor_id',
            ]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid or inactive credentials.'], 403);
            }

            return redirect()->route('login')
                ->with('error', 'Your credentials are invalid or inactive.');
        }

        // Verify event access if event_id is in route
        if ($request->route('event')) {
            $event = $request->route('event');
            $eventId = is_object($event) ? $event->id : $event;
            
            if ($credential->event_id != $eventId) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Unauthorized access to this event.'], 403);
                }

                return redirect()->route('vendor.event.dashboard', $credential->event_id)
                    ->with('error', 'You do not have access to this event.');
            }
        }

        return $next($request);
    }
}
