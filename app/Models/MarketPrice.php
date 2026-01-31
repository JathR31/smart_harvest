<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'crop_name',
        'variety',
        'price_per_kg',
        'previous_price',
        'price_trend',
        'market_location',
        'demand_level',
        'price_date',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'price_per_kg' => 'decimal:2',
        'previous_price' => 'decimal:2',
        'price_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the creator of the price entry
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for active prices
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for latest prices
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('price_date', 'desc');
    }

    /**
     * Scope by crop
     */
    public function scopeForCrop($query, $cropName)
    {
        return $query->where('crop_name', $cropName);
    }

    /**
     * Get price change percentage
     */
    public function getPriceChangePercentAttribute()
    {
        if (!$this->previous_price || $this->previous_price == 0) {
            return 0;
        }
        return round((($this->price_per_kg - $this->previous_price) / $this->previous_price) * 100, 1);
    }

    /**
     * Get trend icon
     */
    public function getTrendIconAttribute()
    {
        return match($this->price_trend) {
            'up' => '↑',
            'down' => '↓',
            default => '→',
        };
    }

    /**
     * Get trend color class
     */
    public function getTrendColorAttribute()
    {
        return match($this->price_trend) {
            'up' => 'text-green-600',
            'down' => 'text-red-600',
            default => 'text-gray-600',
        };
    }

    /**
     * Get demand badge color
     */
    public function getDemandBadgeAttribute()
    {
        return match($this->demand_level) {
            'very_high' => 'bg-green-100 text-green-800',
            'high' => 'bg-blue-100 text-blue-800',
            'moderate' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get latest price for each crop
     */
    public static function getLatestPrices()
    {
        return self::active()
            ->select('crop_name', 'variety', 'price_per_kg', 'previous_price', 'price_trend', 'demand_level', 'price_date', 'market_location')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('market_prices')
                    ->where('is_active', true)
                    ->groupBy('crop_name');
            })
            ->orderBy('crop_name')
            ->get();
    }
}
