<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmingAdvisory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'applicable_regions',
        'severity',
        'advisory_date',
        'valid_until',
    ];

    protected $casts = [
        'advisory_date' => 'date',
        'valid_until' => 'datetime',
    ];

    /**
     * Get current active advisories
     */
    public static function getActiveAdvisories()
    {
        return self::where(function ($query) {
                $query->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            })
            ->where('advisory_date', '>=', now()->subDays(7))
            ->orderBy('severity', 'desc')
            ->orderBy('advisory_date', 'desc')
            ->get();
    }

    /**
     * Get advisories by severity
     */
    public static function getBySeverity($severity)
    {
        return self::where('severity', $severity)
            ->where('advisory_date', '>=', now()->subDays(7))
            ->orderBy('advisory_date', 'desc')
            ->get();
    }
}
