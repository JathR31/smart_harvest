<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnsoAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'description',
        'recommendations',
        'alert_date',
        'updated_date',
    ];

    protected $casts = [
        'alert_date' => 'date',
        'updated_date' => 'date',
    ];

    /**
     * Get the current ENSO status
     */
    public static function getCurrentStatus()
    {
        return self::orderBy('alert_date', 'desc')->first();
    }

    /**
     * Get ENSO history
     */
    public static function getHistory($months = 12)
    {
        return self::where('alert_date', '>=', now()->subMonths($months))
            ->orderBy('alert_date', 'desc')
            ->get();
    }
}
