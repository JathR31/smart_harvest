<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CropData extends Model
{
    protected $table = 'crop_data';

    protected $fillable = [
        'user_id',
        'crop_type',
        'variety',
        'municipality',
        'area_planted',
        'yield_amount',
        'planting_date',
        'harvest_date',
        'status',
        'temperature',
        'rainfall',
        'humidity',
        'notes',
        'validation_status'
    ];

    protected $casts = [
        'planting_date' => 'date',
        'harvest_date' => 'date',
        'area_planted' => 'decimal:2',
        'yield_amount' => 'decimal:2',
        'temperature' => 'decimal:2',
        'rainfall' => 'decimal:2',
        'humidity' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
