<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule PAGASA weather data update daily at 7:30 AM
// (PAGASA updates at 7:00 AM, we fetch at 7:30 AM to ensure data is available)
Schedule::command('weather:update-pagasa')
    ->dailyAt('07:30')
    ->timezone('Asia/Manila')
    ->name('Update PAGASA Weather Data')
    ->onSuccess(function () {
        \Illuminate\Support\Facades\Log::info('PAGASA weather data scheduled update completed successfully');
    })
    ->onFailure(function () {
        \Illuminate\Support\Facades\Log::error('PAGASA weather data scheduled update failed');
    });

