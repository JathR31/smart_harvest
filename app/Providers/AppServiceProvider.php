<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production') && env('FORCE_HTTPS', true)) {
            URL::forceScheme('https');
        }

        // Auto-run migrations in production on app startup
        if (app()->environment('production')) {
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            } catch (\Exception $e) {
                // Log the error but don't break the app
                \Illuminate\Support\Facades\Log::error('Migration error: ' . $e->getMessage());
            }
        }
    }
}
