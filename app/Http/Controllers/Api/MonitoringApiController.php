<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClimatePattern;

class MonitoringApiController extends Controller
{
    public function getAlerts(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $climateAlerts = [];
        
        // Check for heavy rainfall alerts
        $heavyRainfall = ClimatePattern::where('rainfall', '>', 200)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($heavyRainfall) {
            $climateAlerts[] = [
                'id' => 'hr_' . $heavyRainfall->id,
                'type' => 'heavy_rainfall',
                'title' => 'Heavy Rainfall Warning',
                'time' => 'Today, 6:00 AM',
                'description' => 'Heavy rainfall expected in the region. Flooding risk in low-lying agricultural areas.',
                'locations' => [$heavyRainfall->municipality],
                'severity' => 'high',
                'riskLabel' => 'High Risk'
            ];
        }
        
        // Check for drought conditions
        $lowRainfall = ClimatePattern::where('rainfall', '<', 50)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($lowRainfall) {
            $climateAlerts[] = [
                'id' => 'drought_' . $lowRainfall->id,
                'type' => 'drought',
                'title' => 'Drought Risk Alert',
                'time' => 'Yesterday, 2:00 PM',
                'description' => 'Below normal rainfall recorded in region for the past 30 days. Monitor crop water needs.',
                'locations' => [$lowRainfall->municipality],
                'severity' => 'medium',
                'riskLabel' => 'Medium Risk'
            ];
        }
        
        // Add tropical depression alert
        $highTempVariance = ClimatePattern::whereRaw('(max_temperature - min_temperature) > 15')
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($highTempVariance) {
            array_unshift($climateAlerts, [
                'id' => 'td_001',
                'type' => 'tropical_depression',
                'title' => 'Tropical Depression entering PAR',
                'time' => 'Today, 8:00 AM',
                'description' => 'TD expected to affect Northern Luzon within 48 hours. Moderate to heavy rainfall expected.',
                'locations' => ['Baguio', 'Benguet', 'Ifugao'],
                'severity' => 'high',
                'riskLabel' => 'Medium Risk'
            ]);
        }

        return response()->json([
            'alerts' => array_slice($climateAlerts, 0, 3)
        ]);
    }

    public function getRainfallForecast(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $recentData = ClimatePattern::orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(7)
            ->get();

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $forecast = [];

        foreach ($days as $index => $day) {
            $rainfall = $recentData->get($index)?->rainfall ?? rand(5, 45);
            $forecast[] = [
                'day' => $day,
                'rainfall' => round($rainfall, 1),
                'percentage' => rand(10, 90) . '%'
            ];
        }

        return response()->json(['forecast' => $forecast]);
    }

    public function getMunicipalityStatus(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $municipalities = ClimatePattern::select('municipality')
            ->selectRaw('AVG(rainfall) as avg_rainfall')
            ->selectRaw('AVG(avg_temperature) as avg_temp')
            ->selectRaw('AVG(humidity) as avg_humidity')
            ->groupBy('municipality')
            ->orderBy('municipality')
            ->get();

        $statuses = [];
        foreach ($municipalities as $muni) {
            $status = 'Normal';
            
            if ($muni->avg_rainfall > 200 || $muni->avg_temp > 30) {
                $status = 'Watch';
            } elseif ($muni->avg_rainfall > 100 && $muni->avg_rainfall < 150 && $muni->avg_humidity > 60) {
                $status = 'Favorable';
            }

            $statuses[] = [
                'name' => $muni->municipality,
                'status' => $status
            ];
        }

        if (count($statuses) === 0) {
            $statuses = [
                ['name' => 'Atok', 'status' => 'Normal'],
                ['name' => 'Bakun', 'status' => 'Watch'],
                ['name' => 'Baguio', 'status' => 'Favorable'],
                ['name' => 'Itogon', 'status' => 'Normal'],
                ['name' => 'La Trinidad', 'status' => 'Normal'],
            ];
        }

        return response()->json(['municipalities' => $statuses]);
    }
}
