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
            
            return response()->json([
                'error' => 'ML API returned an error',
                'ml_api_connected' => false
            ], 500);
            
        } catch (\Exception $e) {
            \Log::error('ML Yield Analysis error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch yield analysis',
                'ml_api_connected' => false
            ], 500);
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
