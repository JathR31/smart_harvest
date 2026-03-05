<?php

namespace App\Http\Controllers;

use App\Services\PagasaWeatherService;
use App\Models\WeatherForecast;
use App\Models\SoilMoistureData;
use App\Models\FarmingAdvisory;
use App\Models\EnsoAlert;
use App\Models\GaleWarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    protected $pagasaService;

    public function __construct(PagasaWeatherService $pagasaService)
    {
        $this->pagasaService = $pagasaService;
    }

    /**
     * Display the weather dashboard
     */
    public function index()
    {
        $data = $this->pagasaService->getDashboardSummary();
        
        return view('weather.dashboard', $data);
    }

    /**
     * API: Get all weather data
     */
    public function getWeatherData(Request $request)
    {
        try {
            $municipality = $request->input('municipality');

            if ($municipality) {
                $data = $this->pagasaService->getWeatherForMunicipality($municipality);
                return response()->json([
                    'success' => true,
                    'data' => $data,
                ]);
            }

            $data = $this->pagasaService->getDashboardSummary();
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching weather data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch weather data',
            ], 500);
        }
    }

    /**
     * API: Get weather forecasts
     */
    public function getForecasts(Request $request)
    {
        try {
            $region = $request->input('region');

            if ($region) {
                $forecasts = WeatherForecast::getLatestForRegion($region);
            } else {
                $forecasts = WeatherForecast::getCurrentForecasts();
            }

            return response()->json([
                'success' => true,
                'data' => $forecasts,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch forecasts',
            ], 500);
        }
    }

    /**
     * API: Get soil moisture data
     */
    public function getSoilMoisture(Request $request)
    {
        try {
            $municipality = $request->input('municipality');
            $condition = $request->input('condition');

            if ($municipality) {
                $data = SoilMoistureData::getLatestForMunicipality($municipality);
            } elseif ($condition) {
                $data = SoilMoistureData::getByCondition($condition);
            } else {
                $data = SoilMoistureData::getCurrentData();
            }

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch soil moisture data',
            ], 500);
        }
    }

    /**
     * API: Get farming advisories
     */
    public function getAdvisories(Request $request)
    {
        try {
            $severity = $request->input('severity');

            if ($severity) {
                $advisories = FarmingAdvisory::getBySeverity($severity);
            } else {
                $advisories = FarmingAdvisory::getActiveAdvisories();
            }

            return response()->json([
                'success' => true,
                'data' => $advisories,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch advisories',
            ], 500);
        }
    }

    /**
     * API: Get ENSO status
     */
    public function getEnsoStatus()
    {
        try {
            $status = EnsoAlert::getCurrentStatus();

            return response()->json([
                'success' => true,
                'data' => $status,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch ENSO status',
            ], 500);
        }
    }

    /**
     * API: Get gale warnings
     */
    public function getGaleWarnings(Request $request)
    {
        try {
            $municipality = $request->input('municipality');

            if ($municipality) {
                $isAffected = GaleWarning::isAffected($municipality);
                $warnings = $isAffected ? GaleWarning::getActiveWarnings() : [];
            } else {
                $warnings = GaleWarning::getActiveWarnings();
            }

            return response()->json([
                'success' => true,
                'data' => $warnings,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch gale warnings',
            ], 500);
        }
    }

    /**
     * Manually trigger weather data update (admin only)
     */
    public function updateWeatherData(Request $request)
    {
        try {
            // Check if user is admin or superadmin
            if (!in_array($request->user()->role, ['admin', 'superadmin', 'da_admin'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            $result = $this->pagasaService->updateWeatherData();

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Weather data updated successfully',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to update weather data',
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error updating weather data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update weather data',
            ], 500);
        }
    }

    /**
     * Get weather widget data for farmer dashboard
     */
    public function getWeatherWidget(Request $request)
    {
        try {
            $user = $request->user();
            $municipality = $user->municipality ?? null;

            $data = [
                'soil_moisture' => null,
                'advisories' => FarmingAdvisory::getActiveAdvisories()->take(3),
                'enso' => EnsoAlert::getCurrentStatus(),
                'gale_warning' => false,
            ];

            if ($municipality) {
                $data['soil_moisture'] = SoilMoistureData::getLatestForMunicipality($municipality);
                $data['gale_warning'] = GaleWarning::isAffected($municipality);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch weather widget data',
            ], 500);
        }
    }
}
