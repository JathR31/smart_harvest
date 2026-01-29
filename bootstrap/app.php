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
                ->group(base_path('routes/farmer_api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'admin/api/import',
            'admin/api/datasets/*',
            'api/planting/*',
            'api/translate',
            'api/translate/batch',
            'api/translate/detect',
            'api/translate/languages',
        ]);
        
        // Register middleware aliases
        $middleware->alias([
            'prevent.back' => \App\Http\Middleware\PreventBackHistory::class,
        ]);
        
        // Apply prevent back middleware to web routes
        $middleware->web(append: [
            \App\Http\Middleware\PreventBackHistory::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
