<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoilMoistureData extends Model
{
    use HasFactory;

    protected $fillable = [
        'municipality',
        'province',
        'condition',
        'observation_date',
    ];

    protected $casts = [
        'observation_date' => 'date',
    ];

    /**
     * Get the latest soil moisture data for a municipality
     */
    public static function getLatestForMunicipality($municipality)
    {
        return self::where('municipality', 'LIKE', "%$municipality%")
            ->orderBy('observation_date', 'desc')
            ->first();
    }

    /**
     * Get all current soil moisture data
     */
    public static function getCurrentData()
    {
        return self::where('observation_date', '>=', now()->subDays(3))
            ->orderBy('observation_date', 'desc')
            ->orderBy('municipality')
            ->get();
    }

    /**
     * Get soil moisture by condition
     */
    public static function getByCondition($condition)
    {
        return self::where('condition', $condition)
            ->where('observation_date', '>=', now()->subDays(3))
            ->orderBy('municipality')
            ->get();
    }
}
