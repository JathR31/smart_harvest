<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// ========== FARMER DASHBOARD API ENDPOINTS ==========

// Dashboard statistics API
Route::get('/api/dashboard/stats', function () {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $userId = Auth::id();
    $currentYear = now()->year;
    
    // Get user's crop data
    $userCropData = \App\Models\CropData::where('user_id', $userId)->get();
    
    // Year expected harvest (sum of all projected yields)
    $expectedHarvest = $userCropData->where('status', '!=', 'Failed')
        ->whereNotNull('yield_amount')
        ->sum('yield_amount') / 1000; // Convert kg to MT
    
    // Get last year's data for comparison
    $lastYearHarvest = \App\Models\CropData::where('user_id', $userId)
        ->where('status', 'Harvested')
        ->whereYear('harvest_date', $currentYear - 1)
        ->sum('yield_amount') / 1000;
    
    $percentageChange = $lastYearHarvest > 0 
        ? (($expectedHarvest - $lastYearHarvest) / $lastYearHarvest) * 100 
        : 0;
    
    // Recent harvest data (last 5 records)
    $recentHarvests = \App\Models\CropData::where('user_id', $userId)
        ->where('status', 'Harvested')
        ->orderBy('harvest_date', 'desc')
        ->take(5)
        ->get()
        ->map(function($record) {
            return [
                'crop' => $record->crop_type,
                'variety' => $record->variety,
                'municipality' => $record->municipality,
                'year' => $record->harvest_date ? $record->harvest_date->year : now()->year,
                'area_ha' => $record->area_planted,
                'production_mt' => $record->yield_amount ? $record->yield_amount / 1000 : 0,
                'yield_per_ha' => $record->area_planted > 0 && $record->yield_amount 
                    ? ($record->yield_amount / 1000) / $record->area_planted 
                    : 0,
            ];
        });
    
    return response()->json([
        'expected_harvest' => round($expectedHarvest, 2),
        'percentage_change' => round($percentageChange, 1),
        'recent_harvests' => $recentHarvests,
    ]);
});

// ========== YIELD ANALYSIS API ENDPOINTS ==========

// Yield analysis statistics
Route::get('/api/yield/stats', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $year = $request->query('year', now()->year);
    $municipality = Auth::user()->location ?? 'La Trinidad';
    
    // Average yield for selected year
    $avgYield = \App\Models\CropData::where('status', 'Harvested')
        ->whereYear('harvest_date', $year)
        ->where('municipality', $municipality)
        ->whereNotNull('yield_amount')
        ->whereNotNull('area_planted')
        ->where('area_planted', '>', 0)
        ->get()
        ->map(function($record) {
            return ($record->yield_amount / 1000) / $record->area_planted;
        })
        ->avg();
    
    // Best performing crop
    $bestCrop = \App\Models\CropData::selectRaw('crop_type, AVG(yield_amount / area_planted / 1000) as avg_yield')
        ->where('status', 'Harvested')
        ->whereYear('harvest_date', $year)
        ->where('municipality', $municipality)
        ->whereNotNull('yield_amount')
        ->where('area_planted', '>', 0)
        ->groupBy('crop_type')
        ->orderByDesc('avg_yield')
        ->first();
    
    // Total production
    $totalProduction = \App\Models\CropData::where('status', 'Harvested')
        ->whereYear('harvest_date', $year)
        ->where('municipality', $municipality)
        ->sum('yield_amount') / 1000;
    
    // Year-over-year comparison
    $lastYearAvg = \App\Models\CropData::where('status', 'Harvested')
        ->whereYear('harvest_date', $year - 1)
        ->where('municipality', $municipality)
        ->whereNotNull('yield_amount')
        ->where('area_planted', '>', 0)
        ->get()
        ->map(function($record) {
            return ($record->yield_amount / 1000) / $record->area_planted;
        })
        ->avg();
    
    $yieldChange = $lastYearAvg > 0 
        ? (($avgYield - $lastYearAvg) / $lastYearAvg) * 100 
        : 0;
    
    return response()->json([
        'avg_yield' => round($avgYield ?? 0, 2),
        'best_crop' => $bestCrop ? $bestCrop->crop_type : 'N/A',
        'best_crop_yield' => $bestCrop ? round($bestCrop->avg_yield, 2) : 0,
        'total_production' => round($totalProduction, 2),
        'yield_change' => round($yieldChange, 1),
        'prediction_accuracy' => 95.8, // Static for now
    ]);
});

// Yield comparison data (multi-year)
Route::get('/api/yield/comparison', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $municipality = Auth::user()->location ?? 'La Trinidad';
    $years = range(now()->year - 5, now()->year);
    
    $data = [];
    foreach ($years as $year) {
        $avgYield = \App\Models\CropData::where('status', 'Harvested')
            ->whereYear('harvest_date', $year)
            ->where('municipality', $municipality)
            ->whereNotNull('yield_amount')
            ->where('area_planted', '>', 0)
            ->get()
            ->map(function($record) {
                return ($record->yield_amount / 1000) / $record->area_planted;
            })
            ->avg();
        
        $data[] = [
            'year' => $year,
            'actual' => round($avgYield ?? 0, 2),
            'predicted' => round(($avgYield ?? 0) * (1 + (rand(-5, 5) / 100)), 2), // Simulated prediction
        ];
    }
    
    return response()->json($data);
});

// Crop performance data
Route::get('/api/yield/crops', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $year = $request->query('year', now()->year);
    $municipality = Auth::user()->location ?? 'La Trinidad';
    
    $cropData = \App\Models\CropData::selectRaw('crop_type, AVG(yield_amount / area_planted / 1000) as avg_yield')
        ->where('status', 'Harvested')
        ->whereYear('harvest_date', $year)
        ->where('municipality', $municipality)
        ->whereNotNull('yield_amount')
        ->where('area_planted', '>', 0)
        ->groupBy('crop_type')
        ->orderByDesc('avg_yield')
        ->get()
        ->map(function($record) {
            return [
                'crop' => $record->crop_type,
                'yield' => round($record->avg_yield, 2),
            ];
        });
    
    return response()->json($cropData);
});

// Monthly yield trend
Route::get('/api/yield/monthly', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $year = $request->query('year', now()->year);
    $municipality = Auth::user()->location ?? 'La Trinidad';
    
    $monthlyData = \App\Models\CropData::selectRaw('MONTH(harvest_date) as month, SUM(yield_amount) as total_yield')
        ->where('status', 'Harvested')
        ->whereYear('harvest_date', $year)
        ->where('municipality', $municipality)
        ->whereNotNull('harvest_date')
        ->whereNotNull('yield_amount')
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->map(function($record) {
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return [
                'month' => $months[$record->month - 1],
                'yield' => round($record->total_yield / 1000, 2),
            ];
        });
    
    return response()->json($monthlyData);
});

// ========== PLANTING SCHEDULE API ENDPOINTS ==========

// Planting schedule recommendations
Route::get('/api/planting/schedule', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $municipality = Auth::user()->location ?? 'La Trinidad';
    
    // Get crop statistics grouped by crop type
    $cropStats = \App\Models\CropData::selectRaw('
            crop_type,
            variety,
            AVG(DATEDIFF(harvest_date, planting_date)) as avg_duration,
            AVG(yield_amount / area_planted / 1000) as avg_yield
        ')
        ->where('status', 'Harvested')
        ->where('municipality', $municipality)
        ->whereNotNull('harvest_date')
        ->whereNotNull('planting_date')
        ->whereNotNull('yield_amount')
        ->where('area_planted', '>', 0)
        ->groupBy('crop_type', 'variety')
        ->orderByDesc('avg_yield')
        ->take(10)
        ->get()
        ->map(function($record) {
            $duration = round($record->avg_duration ?? 90);
            
            return [
                'crop' => $record->crop_type,
                'variety' => $record->variety,
                'optimal_planting' => now()->format('M d') . ' - ' . now()->addDays(30)->format('M d'),
                'expected_harvest' => now()->addDays($duration)->format('M d') . ' - ' . now()->addDays($duration + 30)->format('M d'),
                'duration' => $duration . ' days',
                'yield_prediction' => round($record->avg_yield, 2) . ' mt/ha',
                'confidence' => 'High',
                'status' => 'Recommended',
            ];
        });
    
    return response()->json($cropStats);
});

// Next optimal planting date
Route::get('/api/planting/optimal', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $municipality = Auth::user()->location ?? 'La Trinidad';
    
    // Get best performing crop from last year
    $bestCrop = \App\Models\CropData::selectRaw('crop_type, variety, AVG(yield_amount / area_planted / 1000) as avg_yield')
        ->where('status', 'Harvested')
        ->where('municipality', $municipality)
        ->whereYear('harvest_date', now()->year - 1)
        ->whereNotNull('yield_amount')
        ->where('area_planted', '>', 0)
        ->groupBy('crop_type', 'variety')
        ->orderByDesc('avg_yield')
        ->first();
    
    $nextDate = now()->addDays(15);
    $expectedYield = $bestCrop ? round($bestCrop->avg_yield * 1.05, 2) : 5.8; // 5% improvement projection
    
    return response()->json([
        'next_date' => $nextDate->format('M d'),
        'crop' => $bestCrop ? $bestCrop->crop_type : 'Cabbage',
        'variety' => $bestCrop ? $bestCrop->variety : 'Scorpio',
        'expected_yield' => $expectedYield,
        'weather_window' => 14,
        'confidence' => 'High',
    ]);
});

// Climate data for municipality
Route::get('/api/climate/current', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $municipality = $request->query('municipality', Auth::user()->location ?? 'La Trinidad');
    $currentMonth = now()->month;
    $currentYear = now()->year;
    
    // Get current month climate data
    $currentClimate = \App\Models\ClimatePattern::where('municipality', $municipality)
        ->where('year', $currentYear)
        ->where('month', $currentMonth)
        ->first();
    
    // Get historical average for this month
    $historicalAvg = \App\Models\ClimatePattern::where('municipality', $municipality)
        ->where('month', $currentMonth)
        ->whereYear('year', '<', $currentYear)
        ->selectRaw('AVG(avg_temperature) as avg_temp, AVG(rainfall) as avg_rain, AVG(humidity) as avg_hum')
        ->first();
    
    return response()->json([
        'current' => $currentClimate,
        'historical_average' => $historicalAvg,
    ]);
});

// Get municipalities list
Route::get('/api/municipalities', function () {
    return response()->json([
        'municipalities' => [
            'Atok', 'Baguio City', 'Bakun', 'Bokod', 'Buguias', 'Itogon', 
            'Kabayan', 'Kapangan', 'Kibungan', 'La Trinidad', 'Mankayan', 
            'Sablan', 'Tuba', 'Tublay'
        ]
    ]);
});
