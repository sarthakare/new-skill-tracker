<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/super-admin.php'));
            Route::middleware('web')
                ->group(base_path('routes/college.php'));
            Route::middleware('web')
                ->group(base_path('routes/vendor.php'));
            Route::middleware('web')
                ->group(base_path('routes/manager.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'super-admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
            'college-scope' => \App\Http\Middleware\EnsureCollegeScope::class,
            'vendor-event-access' => \App\Http\Middleware\EnsureVendorEventAccess::class,
            'program-manager-access' => \App\Http\Middleware\EnsureProgramManagerAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
