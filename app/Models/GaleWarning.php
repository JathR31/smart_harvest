<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaleWarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'area',
        'description',
        'severity',
        'affected_municipalities',
        'warning_date',
        'valid_until',
    ];

    protected $casts = [
        'warning_date' => 'date',
        'valid_until' => 'datetime',
    ];

    /**
     * Get active gale warnings
     */
    public static function getActiveWarnings()
    {
        return self::where(function ($query) {
                $query->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            })
            ->where('warning_date', '>=', now()->subDays(3))
            ->orderBy('severity', 'desc')
            ->orderBy('warning_date', 'desc')
            ->get();
    }

    /**
     * Check if a municipality is affected
     */
    public static function isAffected($municipality)
    {
        return self::where('affected_municipalities', 'LIKE', "%$municipality%")
            ->where(function ($query) {
                $query->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            })
            ->exists();
    }
}
