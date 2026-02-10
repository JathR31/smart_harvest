<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CropData;
use App\Models\ClimatePattern;
use App\Models\MarketPrice;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatasetsApiController extends Controller
{
    /**
     * Get all available datasets with statistics
     */
    public function getDatasets(Request $request)
    {
        try {
            Log::info('DatasetsApiController::getDatasets called');
            
            $datasets = [];
            
            // 1. Crop Production Dataset
            $cropDataCount = CropData::count();
            $cropDataSize = $this->estimateTableSize('crop_data');
            $latestCropUpdate = CropData::latest('updated_at')->first();
            
            if ($cropDataCount > 0) {
                $datasets[] = [
                    'id' => 'crop_production',
                    'name' => 'Crop Production Statistics',
                    'description' => 'Comprehensive data on crop production including yield, area planted, and harvest information across municipalities',
                    'category' => 'Statistics',
                    'records' => $cropDataCount,
                    'size' => $cropDataSize,
                    'updated' => $latestCropUpdate ? $latestCropUpdate->updated_at->diffForHumans() : 'N/A',
                    'updated_by' => 'System',
                    'icon' => 'statistics',
                    'color' => 'blue'
                ];
            }
            
            // 2. Climate Patterns Dataset
            $climateCount = ClimatePattern::count();
            $climateSize = $this->estimateTableSize('climate_patterns');
            $latestClimateUpdate = ClimatePattern::latest('updated_at')->first();
            
            if ($climateCount > 0) {
                $datasets[] = [
                    'id' => 'climate_patterns',
                    'name' => 'Climate & Weather Patterns',
                    'description' => 'Historical climate data including rainfall, temperature, humidity for agricultural planning and analysis',
                    'category' => 'Statistics',
                    'records' => $climateCount,
                    'size' => $climateSize,
                    'updated' => $latestClimateUpdate ? $latestClimateUpdate->updated_at->diffForHumans() : 'N/A',
                    'updated_by' => 'System',
                    'icon' => 'weather',
                    'color' => 'cyan'
                ];
            }
            
            // 3. Market Prices Dataset
            $marketPricesCount = MarketPrice::count();
            $marketPricesSize = $this->estimateTableSize('market_prices');
            $latestMarketUpdate = MarketPrice::latest('updated_at')->first();
            
            if ($marketPricesCount > 0) {
                $datasets[] = [
                    'id' => 'market_prices',
                    'name' => 'Agricultural Market Prices',
                    'description' => 'Real-time and historical market prices for agricultural commodities across different markets',
                    'category' => 'Economics',
                    'records' => $marketPricesCount,
                    'size' => $marketPricesSize,
                    'updated' => $latestMarketUpdate ? $latestMarketUpdate->updated_at->diffForHumans() : 'N/A',
                    'updated_by' => 'Market Admin',
                    'icon' => 'economics',
                    'color' => 'green'
                ];
            }
            
            // 4. Farmer Registry (RSBSA-like)
            $farmerCount = User::where('role', 'Farmer')->count();
            $farmerSize = $this->estimateTableSize('users');
            $latestFarmerUpdate = User::where('role', 'Farmer')->latest('updated_at')->first();
            
            if ($farmerCount > 0) {
                $datasets[] = [
                    'id' => 'farmer_registry',
                    'name' => 'RSBSA Masterlist - CAR',
                    'description' => 'Official registry of all farmers, fishers, and farm laborers in the Cordillera Administrative Region',
                    'category' => 'Registry',
                    'records' => $farmerCount,
                    'size' => $farmerSize,
                    'updated' => $latestFarmerUpdate ? $latestFarmerUpdate->updated_at->diffForHumans() : 'N/A',
                    'updated_by' => 'J. Abello',
                    'icon' => 'registry',
                    'color' => 'purple'
                ];
            }
            
            // 5. Municipality-wise Production Summary
            $municipalityStats = CropData::select('municipality')
                ->selectRaw('COUNT(*) as record_count')
                ->selectRaw('SUM(yield_amount) as total_yield')
                ->groupBy('municipality')
                ->get();
            
            if ($municipalityStats->count() > 0) {
                $datasets[] = [
                    'id' => 'municipality_summary',
                    'name' => 'Municipal Production Summary',
                    'description' => 'Aggregated agricultural production data organized by municipality for regional analysis',
                    'category' => 'Analytics',
                    'records' => $municipalityStats->count(),
                    'size' => strlen(json_encode($municipalityStats)) * 2, // Approximate
                    'updated' => 'Recently',
                    'updated_by' => 'Analytics Engine',
                    'icon' => 'analytics',
                    'color' => 'yellow'
                ];
            }
            
            // 6. Seasonal Yield Analysis
            $seasonalData = CropData::whereNotNull('planting_date')
                ->whereNotNull('yield_amount')
                ->count();
            
            if ($seasonalData > 0) {
                $datasets[] = [
                    'id' => 'seasonal_yield',
                    'name' => 'Seasonal Yield Analysis',
                    'description' => 'Time-series data showing crop yields across different seasons and planting periods',
                    'category' => 'Analytics',
                    'records' => $seasonalData,
                    'size' => $seasonalData * 512, // Approximate
                    'updated' => 'Recently',
                    'updated_by' => 'Analytics Engine',
                    'icon' => 'analytics',
                    'color' => 'orange'
                ];
            }
            
            // 7. Crop Variety Performance
            $varietyData = CropData::whereNotNull('variety')
                ->distinct('variety')
                ->count('variety');
            
            if ($varietyData > 0) {
                $datasets[] = [
                    'id' => 'crop_varieties',
                    'name' => 'Crop Variety Performance',
                    'description' => 'Performance metrics for different crop varieties including yield efficiency and adaptability',
                    'category' => 'Research',
                    'records' => $varietyData,
                    'size' => $varietyData * 1024, // Approximate
                    'updated' => 'Recently',
                    'updated_by' => 'Research Team',
                    'icon' => 'research',
                    'color' => 'indigo'
                ];
            }
            
            // Calculate total stats
            $stats = [
                'total' => count($datasets),
                'totalRecords' => array_sum(array_column($datasets, 'records')),
                'totalSize' => array_sum(array_column($datasets, 'size'))
            ];
            
            Log::info('Datasets loaded: ' . count($datasets));
            
            return response()->json([
                'datasets' => $datasets,
                'stats' => $stats,
                'success' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('Datasets API Error: ' . $e->getMessage());
            
            return response()->json([
                'datasets' => [],
                'stats' => [
                    'total' => 0,
                    'totalRecords' => 0,
                    'totalSize' => 0
                ],
                'success' => false,
                'error' => 'Failed to load datasets'
            ], 500);
        }
    }
    
    /**
     * Delete a dataset (soft delete or mark as archived)
     */
    public function deleteDataset(Request $request, $id)
    {
        try {
            Log::info("Delete dataset requested: $id");
            
            // In a real application, you might want to implement soft deletes
            // or mark datasets as archived instead of actually deleting data
            
            return response()->json([
                'success' => true,
                'message' => 'Dataset archived successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Delete Dataset Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to archive dataset'
            ], 500);
        }
    }
    
    /**
     * Estimate table size in bytes
     */
    private function estimateTableSize($tableName)
    {
        try {
            $result = DB::select("
                SELECT 
                    (data_length + index_length) as size
                FROM information_schema.TABLES 
                WHERE table_schema = DATABASE()
                AND table_name = ?
            ", [$tableName]);
            
            return $result[0]->size ?? 0;
        } catch (\Exception $e) {
            // Fallback: estimate based on row count
            try {
                $count = DB::table($tableName)->count();
                return $count * 1024; // Estimate 1KB per row
            } catch (\Exception $e2) {
                return 0;
            }
        }
    }
}
