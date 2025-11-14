<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClimatePattern extends Model
{
    protected $fillable = [
        'municipality',
        'year',
        'month',
        'avg_temperature',
        'min_temperature',
        'max_temperature',
        'rainfall',
        'humidity',
        'wind_speed',
        'weather_condition',
    ];

    protected $casts = [
        'avg_temperature' => 'decimal:2',
        'min_temperature' => 'decimal:2',
        'max_temperature' => 'decimal:2',
        'rainfall' => 'decimal:2',
        'humidity' => 'decimal:2',
        'wind_speed' => 'decimal:2',
    ];
}
