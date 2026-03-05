<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherForecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'region',
        'weather_condition',
        'wind_condition',
        'temp_high_range',
        'temp_low_range',
        'humidity_range',
        'rainfall_range',
        'synopsis',
        'fwfa_number',
        'forecast_date',
        'valid_from',
        'valid_until',
    ];

    protected $casts = [
        'forecast_date' => 'date',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    /**
     * Get the latest forecast for a specific region
     */
    public static function getLatestForRegion($region)
    {
        return self::where('region', $region)
            ->where('forecast_date', '>=', now()->subDays(2))
            ->orderBy('forecast_date', 'desc')
            ->first();
    }

    /**
     * Get all current forecasts (within validity period)
     */
    public static function getCurrentForecasts()
    {
        return self::where('valid_until', '>=', now())
            ->orderBy('region')
            ->get();
    }
}
