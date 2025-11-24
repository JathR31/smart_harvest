<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClimatePattern;
use App\Models\CropData;
use App\Services\MLApiService;

class DashboardApiController extends Controller
{
    protected $mlService;

    public function __construct()
    {
        $this->mlService = new MLApiService();
    }

    public function getStats(Request $request)
    {
        try {
            $municipality = $request->query('municipality', 'La Trinidad');
            
            // Normalize municipality name
            $mlMunicipality = strtoupper(str_replace(' ', '', $municipality));
            $dbMunicipality = strtoupper(str_replace(' ', '', $municipality));
            
            // Get ML predictions
            $topCropsResult = $this->mlService->getTopCrops(['MUNICIPALITY' => $mlMunicipality]);
            
            $expected_harvest = 0;
            $percentage_change = 0;
            $ml_confidence = 0;
            $mlConnected = false;
            
            if ($topCropsResult['status'] === 'success' && isset($topCropsResult['data']['predicted_top5'])) {
                $topCrops = $topCropsResult['data']['predicted_top5']['crops'] ?? [];
                $mlConnected = true;
                
                foreach ($topCrops as $crop) {
                    foreach ($crop['forecasts'] ?? [] as $forecast) {
                        if ($forecast['year'] == 2025) {
                            $expected_harvest += floatval($forecast['production']);
                        }
                    }
                }
                
                if (isset($topCropsResult['data']['historical_top5']['crops']) && $expected_harvest > 0) {
                    $historical_total = 0;
                    foreach ($topCropsResult['data']['historical_top5']['crops'] as $crop) {
                        $historical_total += floatval($crop['average_production'] ?? 0);
                    }
                    
                    if ($historical_total > 0) {
                        $percentage_change = (($expected_harvest - $historical_total) / $historical_total) * 100;
                    }
                }
                
                if ($expected_harvest > 0) {
                    $ml_confidence = 85;
                }
            }
            
            // Fallback to database if ML API fails
            if ($expected_harvest == 0) {
                $mlConnected = false;
                
                $currentYearData = CropData::whereRaw('UPPER(municipality) = ?', [$dbMunicipality])
                    ->whereYear('planting_date', '>=', 2025)
                    ->sum('yield_amount');
                
                $previousYearData = CropData::whereRaw('UPPER(municipality) = ?', [$dbMunicipality])
                    ->whereYear('planting_date', '=', 2024)
                    ->sum('yield_amount');
                
                $expected_harvest = floatval($currentYearData);
                
                if ($expected_harvest == 0 && $previousYearData > 0) {
                    $expected_harvest = floatval($previousYearData);
                }
                
                if ($expected_harvest == 0) {
                    $avgYield = CropData::whereRaw('UPPER(municipality) = ?', [$dbMunicipality])
                        ->avg('yield_amount') ?? 0;
                    $expected_harvest = floatval($avgYield) * 10;
                }
                
                if ($previousYearData > 0 && $expected_harvest > 0) {
                    $percentage_change = (($expected_harvest - $previousYearData) / $previousYearData) * 100;
                }
                
                $ml_confidence = 0;
            }
            
            // Get recent harvests
            $recentHarvests = CropData::whereRaw('UPPER(municipality) = ?', [$dbMunicipality])
                ->orderBy('planting_date', 'desc')
                ->limit(5)
                ->get()
                ->map(function($harvest) {
                    return [
                        'id' => $harvest->id,
                        'crop_type' => $harvest->crop_type,
                        'variety' => $harvest->variety ?? 'N/A',
                        'municipality' => $harvest->municipality,
                        'year' => date('Y', strtotime($harvest->planting_date)),
                        'area_planted' => floatval($harvest->area_planted),
                        'yield_amount' => floatval($harvest->yield_amount)
                    ];
                });

            return response()->json([
                'stats' => [
                    'expected_harvest' => number_format($expected_harvest, 1),
                    'percentage_change' => round($percentage_change, 1),
                    'ml_confidence' => $ml_confidence,
                    'ml_api_connected' => $mlConnected
                ],
                'recent_harvests' => $recentHarvests
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Dashboard stats error: ' . $e->getMessage());
            return response()->json([
                'stats' => [
                    'expected_harvest' => '0',
                    'percentage_change' => 0,
                    'ml_confidence' => 0,
                    'ml_api_connected' => false
                ],
                'recent_harvests' => []
            ]);
        }
    }

    public function getCurrentClimate(Request $request)
    {
        try {
            $municipality = $request->query('municipality', 'La Trinidad');
            
            $latestClimate = ClimatePattern::where('municipality', $municipality)
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->first();
            
            $historicalAvg = ClimatePattern::where('municipality', $municipality)
                ->selectRaw('AVG(rainfall) as avg_rainfall, AVG(avg_temperature) as avg_temp, AVG(humidity) as avg_humidity')
                ->first();
            
            return response()->json([
                'current' => $latestClimate ? [
                    'rainfall' => round($latestClimate->rainfall, 1),
                    'avg_temperature' => round($latestClimate->avg_temperature, 1),
                    'humidity' => round($latestClimate->humidity, 1),
                    'weather_condition' => $this->getWeatherCondition($latestClimate)
                ] : null,
                'historical_avg' => $historicalAvg ? [
                    'avg_rainfall' => round($historicalAvg->avg_rainfall, 1),
                    'avg_temp' => round($historicalAvg->avg_temp, 1),
                    'avg_humidity' => round($historicalAvg->avg_humidity, 1)
                ] : null
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Climate data error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch climate data'], 500);
        }
    }

    private function getWeatherCondition($climate)
    {
        if ($climate->rainfall > 10) {
            return 'Rainy';
        } elseif ($climate->avg_temperature > 28) {
            return 'Hot';
        } elseif ($climate->avg_temperature < 18) {
            return 'Cool';
        }
        return 'Fair';
    }
}
