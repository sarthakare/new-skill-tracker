<?php

use App\Http\Controllers\Vendor\EventAuthController;
use App\Http\Controllers\Vendor\EventDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Vendor Event Routes
|--------------------------------------------------------------------------
|
| Routes for vendors to access their assigned events using event-specific credentials.
|
*/

// Protected routes (require vendor event authentication)
Route::prefix('vendor/event')->name('vendor.event.')->middleware(['web', 'vendor-event-access'])->group(function () {
    // Dashboard
    Route::get('/{event}/dashboard', [EventDashboardController::class, 'index'])->name('dashboard');
});
