<?php

use App\Http\Controllers\College\DashboardController;
use App\Http\Controllers\College\DepartmentController;
use App\Http\Controllers\College\EventController;
use App\Http\Controllers\College\EventUserController;
use App\Http\Controllers\College\IndependentTrainerController;
use App\Http\Controllers\College\InternalManagerController;
use App\Http\Controllers\College\ProgramController;
use App\Http\Controllers\College\StudentController;
use App\Http\Controllers\College\VendorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| College Admin Routes
|--------------------------------------------------------------------------
|
| All routes for College Admin functionality are defined here.
| All routes are prefixed with /college and protected by auth and college-scope middleware.
|
*/

// Public routes (login) - redirect to unified login
Route::prefix('college')->name('college.')->group(function () {
    Route::get('/login', function () {
        return redirect()->route('login');
    })->name('login');
    Route::post('/login', function () {
        return redirect()->route('login');
    })->name('login.post');
});

// Redirect /college to login if not authenticated
Route::get('/college', function () {
    if (Auth::check() && Auth::user()->isCollegeAdmin()) {
        return redirect()->route('college.dashboard');
    }

    return redirect()->route('login');
});

// Protected routes (require authentication and College Admin role)
Route::prefix('college')->name('college.')->middleware(['auth', 'college-scope'])->group(function () {
    // Logout - use unified logout
    Route::post('/logout', [\App\Http\Controllers\Auth\UnifiedAuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/completions', [DashboardController::class, 'completionRequests'])->name('dashboard.completions');

    // Students (accounts with student role for this college)
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');

    // Departments (names students choose at registration)
    Route::resource('departments', DepartmentController::class)->except(['show']);

    // Events
    Route::resource('events', EventController::class);
    Route::post('/events/{event}/toggle-status', [EventController::class, 'toggleStatus'])
        ->name('events.toggle-status');
    Route::get('/events/{event}/vendor-credentials', [EventController::class, 'vendorCredentials'])
        ->name('events.vendor-credentials');

    // Programs (under events)
    Route::resource('events.programs', ProgramController::class);
    Route::post('/events/{event}/programs/{program}/approve-completion', [ProgramController::class, 'approveCompletion'])
        ->name('programs.approve-completion');

    // Event Users (Removed - vendors handle user assignment now)
    Route::get('/events/{event}/users', [EventUserController::class, 'index'])
        ->name('events.users.index');
    Route::post('/events/{event}/users', [EventUserController::class, 'store'])
        ->name('events.users.store');
    Route::delete('/events/{event}/users/{eventUser}', [EventUserController::class, 'destroy'])
        ->name('events.users.destroy');

    // Vendors
    Route::resource('vendors', VendorController::class);
    Route::post('/vendors/{vendor}/assign-event', [VendorController::class, 'assignToEvent'])
        ->name('vendors.assign-event');
    Route::delete('/vendors/{vendor}/events/{event}', [VendorController::class, 'removeFromEvent'])
        ->name('vendors.remove-from-event');

    // Independent Trainers
    Route::resource('independent-trainers', IndependentTrainerController::class)->except(['show']);

    // Internal Managers
    Route::resource('internal-managers', InternalManagerController::class)->except(['show']);
});
