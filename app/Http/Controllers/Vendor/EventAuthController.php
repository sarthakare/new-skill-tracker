<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorEventCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class EventAuthController extends Controller
{
    public function showLoginForm()
    {
        // Check if already logged in as vendor
        if (session('vendor_event_credential_id')) {
            $credential = VendorEventCredential::with(['vendor', 'event'])
                ->where('id', session('vendor_event_credential_id'))
                ->where('status', 'active')
                ->first();

            if ($credential) {
                return redirect()->route('vendor.event.dashboard', $credential->event_id);
            }
        }

        return redirect()->route('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $credential = VendorEventCredential::with(['vendor', 'event', 'college'])
            ->where('username', $request->username)
            ->where('status', 'active')
            ->first();

        if (!$credential) {
            throw ValidationException::withMessages([
                'username' => ['Invalid credentials.'],
            ]);
        }

        // Check if event is active
        if ($credential->event->status !== 'Active') {
            throw ValidationException::withMessages([
                'username' => ['This year/semester/event is not currently active.'],
            ]);
        }

        // Check if college is active
        if ($credential->college->status !== 'active') {
            throw ValidationException::withMessages([
                'username' => ['The college for this year/semester/event is inactive.'],
            ]);
        }

        // Verify password
        if (!Hash::check($request->password, $credential->password)) {
            throw ValidationException::withMessages([
                'username' => ['Invalid credentials.'],
            ]);
        }

        // Store credential ID in session
        session([
            'vendor_event_credential_id' => $credential->id,
            'vendor_event_id' => $credential->event_id,
            'vendor_id' => $credential->vendor_id,
        ]);

        $request->session()->regenerate();

        return redirect()->route('vendor.event.dashboard', $credential->event_id);
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'vendor_event_credential_id',
            'vendor_event_id',
            'vendor_id',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
