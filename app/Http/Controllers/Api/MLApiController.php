<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MLApiService;

class MLApiController extends Controller
{
    protected $mlService;

    public function __construct()
    {
        $this->mlService = new MLApiService();
    }

    public function getYieldAnalysis(Request $request)
    {
        try {
            $municipality = $request->query('municipality', 'La Trinidad');
            $year = $request->query('year', date('Y'));
            
            $mlMunicipality = strtoupper(str_replace(' ', '', $municipality));
            
            $result = $this->mlService->getYieldAnalysis([
                'MUNICIPALITY' => $mlMunicipality,
                'YEAR' => $year
            ]);
            
            if ($result['status'] === 'success') {
                return response()->json($result['data']);
            }
            
            // Fallback if ML API is unavailable
            \Log::warning('ML API unavailable, returning default yield data');
            return response()->json([
                'average_yield' => 12500,
                'yield_trend' => 'stable',
                'expected_yield' => 13000,
                'ml_api_connected' => false,
                'status' => 'using_fallback_data'
            ], 200);
            
        } catch (\Exception $e) {
            \Log::error('ML Yield Analysis error: ' . $e->getMessage());
            // Return fallback data instead of error
            return response()->json([
                'average_yield' => 12500,
                'yield_trend' => 'stable',
                'expected_yield' => 13000,
                'ml_api_connected' => false,
                'status' => 'using_fallback_data'
            ], 200);
        }
    }

    public function getOptimalPlanting(Request $request)
    {
        try {
            $municipality = $request->query('municipality', 'La Trinidad');
            $mlMunicipality = strtoupper(str_replace(' ', '', $municipality));
            
            $result = $this->mlService->getOptimalPlanting([
                'MUNICIPALITY' => $mlMunicipality
            ]);
            
            if ($result['status'] === 'success' && isset($result['data'])) {
                return response()->json($result['data']);
            }
            
            return response()->json([
                'crop' => 'Cabbage',
                'variety' => 'Scorpio',
                'next_date' => 'December 2024 - January 2025',
                'expected_yield' => 0,
                'confidence' => 'Medium',
                'ml_status' => 'offline'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Optimal Planting error: ' . $e->getMessage());
            return response()->json([
                'crop' => 'Cabbage',
                'variety' => 'Scorpio',
                'next_date' => 'N/A',
                'expected_yield' => 0,
                'confidence' => 'Low',
                'ml_status' => 'error'
            ]);
        }
    }
}
