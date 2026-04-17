<?php

use App\Http\Controllers\Auth\UnifiedAuthController;
use App\Http\Controllers\Student\AuthController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\Judge0RunController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Student routes
|--------------------------------------------------------------------------
*/

Route::get('/student', function () {
    if (Auth::check() && Auth::user()->isStudent()) {
        return redirect()->route('student.dashboard');
    }

    return redirect()->route('student.login');
});

Route::prefix('student')->name('student.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register/departments', [AuthController::class, 'departmentsForCollege'])->name('register.departments');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::middleware(['auth', 'student-scope'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/code-run', Judge0RunController::class)->name('code-run');
        Route::post('/logout', [UnifiedAuthController::class, 'logout'])->name('logout');
    });
});
