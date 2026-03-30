<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Check if user is logged in
    if (Auth::check()) {
        if (Auth::user()->isSuperAdmin()) {
            return redirect()->route('super-admin.dashboard');
        }
        if (Auth::user()->isCollegeAdmin()) {
            return redirect()->route('college.dashboard');
        }
        if (Auth::user()->isStudent()) {
            return redirect()->route('student.dashboard');
        }
    }

    // Check if program manager is logged in
    if (session('program_manager_credential_id')) {
        $credential = \App\Models\ProgramManagerCredential::where('id', session('program_manager_credential_id'))
            ->where('status', 'active')
            ->first();

        if ($credential) {
            return redirect()->route('manager.program.dashboard', $credential->program_id);
        }
    }

    // Check if vendor is logged in
    if (session('vendor_event_credential_id')) {
        $credential = \App\Models\VendorEventCredential::where('id', session('vendor_event_credential_id'))
            ->where('status', 'active')
            ->first();

        if ($credential) {
            return redirect()->route('vendor.event.dashboard', $credential->event_id);
        }
    }

    return redirect()->route('login');
});

// Unified login route
Route::get('/login', [\App\Http\Controllers\Auth\UnifiedAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\UnifiedAuthController::class, 'login'])->name('login.post');
Route::post('/logout', [\App\Http\Controllers\Auth\UnifiedAuthController::class, 'logout'])->name('logout');

// Include Super Admin routes
require __DIR__.'/super-admin.php';
