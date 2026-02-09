<?php

use App\Http\Controllers\SuperAdmin\AuthController;
use App\Http\Controllers\SuperAdmin\CollegeAdminController;
use App\Http\Controllers\SuperAdmin\CollegeController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
|
| All routes for Super Admin functionality are defined here.
| All routes are prefixed with /super-admin and protected by auth and super-admin middleware.
|
*/

// Public routes (login) - redirect to unified login
Route::prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/login', function () {
        return redirect()->route('login');
    })->name('login');
    Route::post('/login', function () {
        return redirect()->route('login');
    })->name('login.post');
});

// Redirect /super-admin to login if not authenticated
Route::get('/super-admin', function () {
    if (Auth::check() && Auth::user()->isSuperAdmin()) {
        return redirect()->route('super-admin.dashboard');
    }
    return redirect()->route('login');
});

// Protected routes (require authentication and Super Admin role)
Route::prefix('super-admin')->name('super-admin.')->middleware(['auth', 'super-admin'])->group(function () {
    // Logout - use unified logout
    Route::post('/logout', [\App\Http\Controllers\Auth\UnifiedAuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Colleges
    Route::resource('colleges', CollegeController::class);
    Route::post('/colleges/{college}/toggle-status', [CollegeController::class, 'toggleStatus'])
        ->name('colleges.toggle-status');

    // College Admins (view only - admins are created when creating a college)
    Route::get('/college-admins', [CollegeAdminController::class, 'index'])->name('college-admins.index');
    Route::get('/college-admins/{college_admin}', [CollegeAdminController::class, 'show'])->name('college-admins.show');
});
