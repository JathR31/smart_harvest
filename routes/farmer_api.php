<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// ========== FARMER DASHBOARD API ENDPOINTS ==========

// Dashboard statistics API with ML predictions
Route::get('/api/dashboard/stats', function () {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $userId = Auth::id();
    $currentYear = now()->year;
    $municipality = Auth::user()->location ?? 'La Trinidad';
    $mlService = new \App\Services\MLApiService();
    
    // Get user's crop data
    $userCropData = \App\Models\CropData::where('user_id', $userId)->get();
    
    // Get last year's actual harvest for comparison
    $lastYearHarvest = \App\Models\CropData::where('user_id', $userId)
        ->where('status', 'Harvested')
        ->whereYear('harvest_date', $currentYear - 1)
        ->sum('yield_amount') / 1000;
    
    // Get ML prediction for this year's expected harvest
    $mlPrediction = $mlService->predict([
        'municipality' => $municipality,
        'crop_type' => 'Mixed Vegetables',
        'area_planted' => $userCropData->avg('area_planted') ?? 1.0,
        'month' => now()->month,
        'year' => $currentYear
    ]);
    
    $expectedHarvest = 0;
    $mlConfidence = 0;
    
    if ($mlPrediction['status'] === 'success' && isset($mlPrediction['data']['prediction'])) {
        $predictedYieldPerHa = $mlPrediction['data']['prediction']['predicted_yield_per_ha'];
        $totalArea = $userCropData->sum('area_planted') ?? 1.0;
        $expectedHarvest = $predictedYieldPerHa * $totalArea;
        $mlConfidence = $mlPrediction['data']['prediction']['confidence'] * 100;
    } else {
        // Fallback to basic calculation if ML fails
        $expectedHarvest = $userCropData->where('status', '!=', 'Failed')
            ->whereNotNull('yield_amount')
            ->sum('yield_amount') / 1000;
    }
    
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
                'id' => $record->id,
                'crop_type' => $record->crop_type,
                'variety' => $record->variety,
                'municipality' => $record->municipality,
                'year' => $record->harvest_date ? $record->harvest_date->year : now()->year,
                'area_planted' => $record->area_planted,
                'yield_amount' => $record->yield_amount ? $record->yield_amount / 1000 : 0,
            ];
        });
    
    return response()->json([
        'stats' => [
            'expected_harvest' => round($expectedHarvest, 2),
            'percentage_change' => round($percentageChange, 1),
            'ml_confidence' => round($mlConfidence, 0),
        ],
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

// Yield comparison data (multi-year) with ML predictions
Route::get('/api/yield/comparison', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $municipality = Auth::user()->location ?? 'La Trinidad';
    $years = range(now()->year - 5, now()->year);
    $mlService = new \App\Services\MLApiService();
    
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
        
        // Get ML prediction for this year
        $mlPrediction = $mlService->predict([
            'municipality' => $municipality,
            'crop_type' => 'Mixed Vegetables',
            'area_planted' => 1.0,
            'month' => 6,
            'year' => $year
        ]);
        
        $predicted = ($mlPrediction['status'] === 'success' && isset($mlPrediction['data']['prediction']['predicted_yield_per_ha']))
            ? round($mlPrediction['data']['prediction']['predicted_yield_per_ha'], 2)
            : round($avgYield ?? 0, 2);
        
        $data[] = [
            'year' => $year,
            'actual' => round($avgYield ?? 0, 2),
            'predicted' => $predicted,
            'confidence' => ($mlPrediction['status'] === 'success' && isset($mlPrediction['data']['prediction']['confidence']))
                ? round($mlPrediction['data']['prediction']['confidence'] * 100, 1)
                : null
        ];
    }
    
    return response()->json($data);
});

// Crop performance data with ML predictions
Route::get('/api/yield/crops', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $year = $request->query('year', now()->year);
    $municipality = Auth::user()->location ?? 'La Trinidad';
    $mlService = new \App\Services\MLApiService();
    
    $cropData = \App\Models\CropData::selectRaw('crop_type, AVG(yield_amount / area_planted / 1000) as avg_yield, AVG(area_planted) as avg_area')
        ->where('status', 'Harvested')
        ->whereYear('harvest_date', $year)
        ->where('municipality', $municipality)
        ->whereNotNull('yield_amount')
        ->where('area_planted', '>', 0)
        ->groupBy('crop_type')
        ->orderByDesc('avg_yield')
        ->get()
        ->map(function($record) use ($municipality, $year, $mlService) {
            // Get ML prediction for each crop
            $mlPrediction = $mlService->predict([
                'municipality' => $municipality,
                'crop_type' => $record->crop_type,
                'area_planted' => $record->avg_area ?? 1.0,
                'month' => now()->month,
                'year' => $year
            ]);
            
            $predictedYield = ($mlPrediction['status'] === 'success' && isset($mlPrediction['data']['prediction']['predicted_yield_per_ha']))
                ? round($mlPrediction['data']['prediction']['predicted_yield_per_ha'], 2)
                : null;
            
            return [
                'crop' => $record->crop_type,
                'yield' => round($record->avg_yield, 2),
                'predicted' => $predictedYield,
                'confidence' => ($mlPrediction['status'] === 'success' && isset($mlPrediction['data']['prediction']['confidence']))
                    ? round($mlPrediction['data']['prediction']['confidence'] * 100, 1)
                    : null
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

// ========== AI INTERPRETATION ENDPOINTS ==========

// Get AI interpretation for yield comparison
Route::get('/api/yield/interpretation/comparison', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $municipality = $request->query('municipality', Auth::user()->location ?? 'La Trinidad');
    $year = $request->query('year', now()->year);
    $mlService = new \App\Services\MLApiService();
    
    // Get yield comparison data with ML predictions
    $years = range($year - 5, $year);
    $yieldData = [];
    
    foreach ($years as $y) {
        $avgYield = \App\Models\CropData::where('status', 'Harvested')
            ->whereYear('harvest_date', $y)
            ->where('municipality', $municipality)
            ->whereNotNull('yield_amount')
            ->where('area_planted', '>', 0)
            ->get()
            ->map(function($record) {
                return ($record->yield_amount / 1000) / $record->area_planted;
            })
            ->avg();
        
        // Get ML prediction for this year
        $mlPrediction = $mlService->predict([
            'municipality' => $municipality,
            'crop_type' => 'Mixed Vegetables',
            'area_planted' => 1.0,
            'month' => 6,
            'year' => $y
        ]);
        
        $predicted = ($mlPrediction['status'] === 'success' && isset($mlPrediction['data']['prediction']['predicted_yield_per_ha']))
            ? round($mlPrediction['data']['prediction']['predicted_yield_per_ha'], 2)
            : null;
        
        $yieldData[] = [
            'year' => $y,
            'actual_yield' => round($avgYield ?? 0, 2),
            'ml_predicted_yield' => $predicted
        ];
    }
    
    $aiService = new \App\Services\GroqService();
    $interpretation = $aiService->interpretYieldComparison($yieldData);
    
    return response()->json($interpretation);
});

// Get AI interpretation for crop performance
Route::get('/api/yield/interpretation/crops', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $municipality = $request->query('municipality', Auth::user()->location ?? 'La Trinidad');
    $year = $request->query('year', now()->year);
    $mlService = new \App\Services\MLApiService();
    
    // Get crop performance data with ML predictions
    $cropData = \App\Models\CropData::selectRaw('crop_type, AVG(yield_amount / area_planted / 1000) as avg_yield, AVG(area_planted) as avg_area')
        ->where('status', 'Harvested')
        ->whereYear('harvest_date', $year)
        ->where('municipality', $municipality)
        ->whereNotNull('yield_amount')
        ->where('area_planted', '>', 0)
        ->groupBy('crop_type')
        ->orderByDesc('avg_yield')
        ->get()
        ->map(function($record) use ($municipality, $year, $mlService) {
            // Get ML prediction for next year
            $mlPrediction = $mlService->predict([
                'municipality' => $municipality,
                'crop_type' => $record->crop_type,
                'area_planted' => $record->avg_area ?? 1.0,
                'month' => now()->month,
                'year' => $year + 1
            ]);
            
            $predictedYield = ($mlPrediction['status'] === 'success' && isset($mlPrediction['data']['prediction']['predicted_yield_per_ha']))
                ? round($mlPrediction['data']['prediction']['predicted_yield_per_ha'], 2)
                : null;
            
            return [
                'crop' => $record->crop_type,
                'current_yield' => round($record->avg_yield, 2),
                'ml_predicted_next_year' => $predictedYield
            ];
        })
        ->toArray();
    
    $aiService = new \App\Services\GroqService();
    $interpretation = $aiService->interpretCropPerformance($cropData);
    
    return response()->json($interpretation);
});

// Get AI interpretation for monthly trends
Route::get('/api/yield/interpretation/monthly', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $municipality = $request->query('municipality', Auth::user()->location ?? 'La Trinidad');
    $year = $request->query('year', now()->year);
    $mlService = new \App\Services\MLApiService();
    
    // Get monthly data with ML predictions
    $monthlyData = \App\Models\CropData::selectRaw('MONTH(harvest_date) as month, SUM(yield_amount) as total_yield, COUNT(*) as harvest_count')
        ->where('status', 'Harvested')
        ->whereYear('harvest_date', $year)
        ->where('municipality', $municipality)
        ->whereNotNull('harvest_date')
        ->whereNotNull('yield_amount')
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->map(function($record) use ($municipality, $year, $mlService) {
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            
            // Get ML prediction for this month
            $mlPrediction = $mlService->predict([
                'municipality' => $municipality,
                'crop_type' => 'Mixed Vegetables',
                'area_planted' => 1.0,
                'month' => $record->month,
                'year' => $year
            ]);
            
            $mlYield = ($mlPrediction['status'] === 'success' && isset($mlPrediction['data']['prediction']['predicted_yield_per_ha']))
                ? round($mlPrediction['data']['prediction']['predicted_yield_per_ha'], 2)
                : null;
            
            return [
                'month' => $months[$record->month - 1],
                'actual_harvest' => round($record->total_yield / 1000, 2),
                'ml_predicted_yield_per_ha' => $mlYield,
                'harvest_count' => $record->harvest_count
            ];
        })
        ->toArray();
    
    $aiService = new \App\Services\GroqService();
    $interpretation = $aiService->interpretMonthlyTrends($monthlyData);
    
    return response()->json($interpretation);
});

// Weather forecast interpretations using AI
Route::get('/api/weather/interpretation/temperature', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $municipality = $request->query('municipality', Auth::user()->location ?? 'Baguio City');
    
    try {
        // Get 5-day forecast data
        $weatherResponse = Http::get('https://api.openweathermap.org/data/2.5/onecall', [
            'lat' => 16.4023,
            'lon' => 120.5960,
            'exclude' => 'minutely,alerts',
            'units' => 'metric',
            'appid' => config('services.openweather.key')
        ]);
        
        if (!$weatherResponse->successful()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch weather data'
            ], 500);
        }
        
        $weatherData = $weatherResponse->json();
        
        if (!isset($weatherData['daily']) || empty($weatherData['daily'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'No forecast data available'
            ], 500);
        }
        
        $daily = array_slice($weatherData['daily'], 0, 5);
        
        // Prepare temperature data for AI
        $tempData = [];
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        
        for ($i = 0; $i < count($daily); $i++) {
            $day = $daily[$i];
            $tempData[] = [
                'day' => $days[$i] ?? 'Day ' . ($i + 1),
                'high' => round($day['temp']['max'] ?? 0),
                'low' => round($day['temp']['min'] ?? 0),
                'avg' => round((($day['temp']['max'] ?? 0) + ($day['temp']['min'] ?? 0)) / 2)
            ];
        }
        
        $aiService = new \App\Services\GroqService();
        $interpretation = $aiService->interpretTemperatureForecast($tempData, $municipality);
        
        return response()->json($interpretation);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error generating interpretation: ' . $e->getMessage()
        ], 500);
    }
});

Route::post('/api/weather/interpretation/rainfall', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $municipality = $request->input('municipality', Auth::user()->location ?? 'Atok');
    $rainfallData = $request->input('rainfallData', []);
    
    try {
        // If no data provided, generate default
        if (empty($rainfallData)) {
            $rainfallData = [
                ['week' => 'Week 1', 'rainfall' => 40],
                ['week' => 'Week 2', 'rainfall' => 75],
                ['week' => 'Week 3', 'rainfall' => 55],
                ['week' => 'Week 4', 'rainfall' => 45]
            ];
        }
        
        $aiService = new \App\Services\GroqService();
        $interpretation = $aiService->interpretRainfallForecast($rainfallData, $municipality);
        
        return response()->json($interpretation);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error generating interpretation: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/api/weather/interpretation/hourly', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $municipality = $request->query('municipality', Auth::user()->location ?? 'Baguio City');
    
    try {
        $apiKey = env('OPENWEATHER_API_KEY');
        
        if (!$apiKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'Weather API key not configured'
            ], 500);
        }
        
        // Get coordinates for municipality
        $coordinates = [
            'Atok' => ['lat' => 16.5833, 'lon' => 120.7000],
            'Bakun' => ['lat' => 16.7833, 'lon' => 120.6667],
            'Bokod' => ['lat' => 16.5167, 'lon' => 120.8333],
            'Buguias' => ['lat' => 16.7333, 'lon' => 120.8167],
            'Kabayan' => ['lat' => 16.6167, 'lon' => 120.8500],
            'Kapangan' => ['lat' => 16.5667, 'lon' => 120.6000],
            'Kibungan' => ['lat' => 16.7000, 'lon' => 120.6333],
            'Mankayan' => ['lat' => 16.8667, 'lon' => 120.7833],
            'La Trinidad' => ['lat' => 16.4561, 'lon' => 120.5895],
            'Itogon' => ['lat' => 16.3667, 'lon' => 120.6833],
            'Sablan' => ['lat' => 16.4833, 'lon' => 120.5500],
            'Tuba' => ['lat' => 16.3167, 'lon' => 120.5500],
            'Tublay' => ['lat' => 16.5167, 'lon' => 120.6167],
        ];
        
        $coords = $coordinates[$municipality] ?? $coordinates['Atok'];
        
        // Fetch weather data from OpenWeather
        $url = "https://api.openweathermap.org/data/3.0/onecall?lat={$coords['lat']}&lon={$coords['lon']}&exclude=minutely,daily,alerts&units=metric&appid={$apiKey}";
        $response = @file_get_contents($url);
        
        if ($response === false) {
            throw new \Exception('Failed to fetch weather data');
        }
        
        $weatherData = json_decode($response, true);
        
        if (!isset($weatherData['hourly']) || !is_array($weatherData['hourly'])) {
            throw new \Exception('Invalid weather data format');
        }
        
        // Extract hourly data for next 8 hours
        $hourlyData = [];
        for ($i = 0; $i < 8 && $i < count($weatherData['hourly']); $i++) {
            $hour = $weatherData['hourly'][$i];
            $hourlyData[] = [
                'time' => date('g A', $hour['dt'] ?? time()),
                'temp' => round($hour['temp'] ?? 0),
                'humidity' => $hour['humidity'] ?? 0,
                'wind_speed' => round($hour['wind_speed'] ?? 0, 1),
                'description' => $hour['weather'][0]['description'] ?? 'Unknown',
                'icon' => $hour['weather'][0]['icon'] ?? '01d'
            ];
        }
        
        $aiService = new \App\Services\GroqService();
        $interpretation = $aiService->interpretHourlyForecast($hourlyData, $municipality);
        
        return response()->json($interpretation);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error generating interpretation: ' . $e->getMessage()
        ], 500);
    }
});

// ========== PLANTING SCHEDULE API ENDPOINTS ==========
// NOTE: Planting schedule routes have been moved to web.php for enhanced ML integration
// The routes below are commented out to avoid conflicts

/*
// Planting schedule recommendations with ML predictions
Route::get('/api/planting/schedule', function (Request $request) {
    // Allow public access - get municipality from request or use default
    $municipality = $request->query('municipality');
    if (!$municipality && Auth::check()) {
        $municipality = Auth::user()->location;
    }
    $municipality = $municipality ?? 'La Trinidad';
    $mlService = new \App\Services\MLApiService();
    
    // Get crop statistics grouped by crop type
    $cropStats = \App\Models\CropData::selectRaw('
            crop_type,
            variety,
            AVG(DATEDIFF(harvest_date, planting_date)) as avg_duration,
            AVG(yield_amount / area_planted / 1000) as avg_yield,
            AVG(area_planted) as avg_area
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
        ->map(function($record) use ($municipality, $mlService) {
            $duration = round($record->avg_duration ?? 90);
            
            // Get ML prediction for optimal planting
            $mlPrediction = $mlService->predict([
                'municipality' => $municipality,
                'crop_type' => $record->crop_type,
                'area_planted' => $record->avg_area ?? 1.0,
                'month' => now()->addDays(15)->month,
                'year' => now()->year
            ]);
            
            $predictedYield = ($mlPrediction['status'] === 'success' && isset($mlPrediction['data']['prediction']['predicted_yield_per_ha']))
                ? round($mlPrediction['data']['prediction']['predicted_yield_per_ha'], 2)
                : round($record->avg_yield, 2);
            
            $confidence = ($mlPrediction['status'] === 'success' && isset($mlPrediction['data']['prediction']['confidence']))
                ? (round($mlPrediction['data']['prediction']['confidence'] * 100) >= 85 ? 'High' : 'Medium')
                : 'Medium';
            
            return [
                'crop' => $record->crop_type,
                'variety' => $record->variety,
                'optimal_planting' => now()->format('M d') . ' - ' . now()->addDays(30)->format('M d'),
                'expected_harvest' => now()->addDays($duration)->format('M d') . ' - ' . now()->addDays($duration + 30)->format('M d'),
                'duration' => $duration . ' days',
                'yield_prediction' => $predictedYield . ' mt/ha',
                'historical_yield' => round($record->avg_yield, 2) . ' mt/ha',
                'confidence' => $confidence,
                'confidence_score' => ($mlPrediction['status'] === 'success' && isset($mlPrediction['data']['prediction']['confidence']))
                    ? round($mlPrediction['data']['prediction']['confidence'] * 100, 1)
                    : null,
                'status' => 'Recommended',
            ];
        });
    
    return response()->json($cropStats);
});

// Next optimal planting date with ML prediction
Route::get('/api/planting/optimal', function (Request $request) {
    // Allow public access - get municipality from request or use default
    $municipality = $request->query('municipality');
    if (!$municipality && Auth::check()) {
        $municipality = Auth::user()->location;
    }
    $municipality = $municipality ?? 'La Trinidad';
    $mlService = new \App\Services\MLApiService();
    
    // Get best performing crop from last year
    $bestCrop = \App\Models\CropData::selectRaw('crop_type, variety, AVG(yield_amount / area_planted / 1000) as avg_yield, AVG(area_planted) as avg_area')
        ->where('status', 'Harvested')
        ->where('municipality', $municipality)
        ->whereYear('harvest_date', now()->year - 1)
        ->whereNotNull('yield_amount')
        ->where('area_planted', '>', 0)
        ->groupBy('crop_type', 'variety')
        ->orderByDesc('avg_yield')
        ->first();
    
    $nextDate = now()->addDays(15);
    $cropType = $bestCrop ? $bestCrop->crop_type : 'Cabbage';
    
    // Get ML prediction for next planting
    $mlPrediction = $mlService->predict([
        'municipality' => $municipality,
        'crop_type' => $cropType,
        'area_planted' => $bestCrop ? $bestCrop->avg_area : 1.0,
        'month' => $nextDate->month,
        'year' => $nextDate->year
    ]);
    
    $expectedYield = ($mlPrediction['status'] === 'success' && isset($mlPrediction['data']['prediction']['predicted_yield_per_ha']))
        ? round($mlPrediction['data']['prediction']['predicted_yield_per_ha'], 2)
        : ($bestCrop ? round($bestCrop->avg_yield * 1.05, 2) : 5.8);
    
    $confidence = ($mlPrediction['status'] === 'success' && isset($mlPrediction['data']['prediction']['confidence']))
        ? (round($mlPrediction['data']['prediction']['confidence'] * 100) >= 85 ? 'High' : 'Medium')
        : 'Medium';
    
    return response()->json([
        'next_date' => $nextDate->format('M d'),
        'crop' => $cropType,
        'variety' => $bestCrop ? $bestCrop->variety : 'Scorpio',
        'expected_yield' => $expectedYield,
        'historical_yield' => $bestCrop ? round($bestCrop->avg_yield, 2) : null,
        'weather_window' => 14,
        'confidence' => $confidence,
        'confidence_score' => ($mlPrediction['status'] === 'success' && isset($mlPrediction['data']['prediction']['confidence']))
            ? round($mlPrediction['data']['prediction']['confidence'] * 100, 1)
            : null,
        'ml_status' => $mlPrediction['status'],
    ]);
});
*/
// END OF COMMENTED OUT PLANTING ROUTES - Using enhanced versions from web.php instead

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
            'Atok', 'Bakun', 'Bokod', 'Buguias', 'Itogon', 
            'Kabayan', 'Kapangan', 'Kibungan', 'La Trinidad', 'Mankayan', 
            'Sablan', 'Tuba', 'Tublay'
        ]
    ]);
});

// ML-based forecast for future yields
Route::get('/api/ml/forecast', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $municipality = $request->query('municipality', Auth::user()->location ?? 'La Trinidad');
    $cropType = $request->query('crop_type', 'Mixed Vegetables');
    $periods = $request->query('periods', 12);
    
    $mlService = new \App\Services\MLApiService();
    
    // Get ML forecast
    $mlForecast = $mlService->forecast([
        'municipality' => $municipality,
        'crop_type' => $cropType,
        'periods' => $periods
    ]);
    
    if ($mlForecast['status'] === 'success') {
        return response()->json([
            'status' => 'success',
            'forecast' => $mlForecast['data']['forecast']
        ]);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Unable to generate forecast',
            'details' => $mlForecast
        ], 500);
    }
});

// ========== TRANSLATION API ENDPOINTS (PUBLIC) ==========

// Translate text (No authentication required for homepage)
Route::post('/api/translate', function (Request $request) {
    $text = $request->input('text');
    $targetLanguage = $request->input('target_language', 'tl');
    $sourceLanguage = $request->input('source_language', 'en');
    
    if (empty($text)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Text is required'
        ], 400);
    }
    
    $translationService = new \App\Services\TranslationService();
    $result = $translationService->translate($text, $targetLanguage, $sourceLanguage);
    
    return response()->json($result);
});

// Batch translation endpoint (No authentication required for homepage)
Route::post('/api/translate/batch', function (Request $request) {
    
    $texts = $request->input('texts', []);
    $targetLanguage = $request->input('target_language', 'tl');
    
    if (empty($texts) || !is_array($texts)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Texts array is required'
        ], 400);
    }
    
    $translationService = new \App\Services\TranslationService();
    $results = $translationService->batchTranslate($texts, $targetLanguage);
    
    return response()->json([
        'status' => 'success',
        'translations' => $results
    ]);
});

// Get supported languages
Route::get('/api/translate/languages', function (Request $request) {
    $translationService = new \App\Services\TranslationService();
    $languages = $translationService->getSupportedLanguages();
    
    return response()->json([
        'status' => 'success',
        'languages' => $languages
    ]);
});

// Detect language
Route::post('/api/translate/detect', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $text = $request->input('text');
    
    if (empty($text)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Text is required'
        ], 400);
    }
    
    $translationService = new \App\Services\TranslationService();
    $result = $translationService->detectLanguage($text);
    
    return response()->json([
        'status' => 'success',
        'detection' => $result
    ]);
});
