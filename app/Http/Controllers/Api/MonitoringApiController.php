<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CropData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class MonitoringApiController extends Controller
{
    /**
     * Municipality coordinates for OpenWeatherMap API
     */
    private $municipalityCoords = [
        'Atok'         => ['lat' => 16.5970, 'lon' => 120.7090],
        'Bakun'        => ['lat' => 16.7880, 'lon' => 120.6590],
        'Baguio'       => ['lat' => 16.4023, 'lon' => 120.5960],
        'Bokod'        => ['lat' => 16.4860, 'lon' => 120.8230],
        'Buguias'      => ['lat' => 16.7310, 'lon' => 120.8360],
        'Itogon'       => ['lat' => 16.3690, 'lon' => 120.6540],
        'Kabayan'      => ['lat' => 16.6190, 'lon' => 120.8370],
        'Kapangan'     => ['lat' => 16.5692, 'lon' => 120.5920],
        'Kibungan'     => ['lat' => 16.6850, 'lon' => 120.6540],
        'La Trinidad'  => ['lat' => 16.4561, 'lon' => 120.5870],
        'Mankayan'     => ['lat' => 16.8590, 'lon' => 120.7770],
        'Sablan'       => ['lat' => 16.4880, 'lon' => 120.5060],
        'Tuba'         => ['lat' => 16.3600, 'lon' => 120.5650],
        'Tublay'       => ['lat' => 16.5110, 'lon' => 120.6200],
    ];

    /**
     * Fetch current weather for a single municipality from OpenWeatherMap
     * Results are cached for 10 minutes to avoid rate limits
     */
    private function fetchWeather(string $municipality): ?array
    {
        $apiKey = env('OPENWEATHER_API_KEY');
        if (!$apiKey) return null;

        $coords = $this->municipalityCoords[$municipality] ?? null;
        if (!$coords) return null;

        $cacheKey = "owm_weather_{$municipality}";
        return Cache::remember($cacheKey, 600, function () use ($coords, $apiKey, $municipality) {
            try {
                $response = Http::timeout(10)->get('https://api.openweathermap.org/data/2.5/weather', [
                    'lat'   => $coords['lat'],
                    'lon'   => $coords['lon'],
                    'appid' => $apiKey,
                    'units' => 'metric',
                ]);

                if (!$response->successful()) {
                    Log::warning("OpenWeather API error for {$municipality}: " . $response->status());
                    return null;
                }

                $data = $response->json();
                return [
                    'temp'        => round($data['main']['temp'] ?? 0, 1),
                    'feels_like'  => round($data['main']['feels_like'] ?? 0, 1),
                    'temp_min'    => round($data['main']['temp_min'] ?? 0, 1),
                    'temp_max'    => round($data['main']['temp_max'] ?? 0, 1),
                    'humidity'    => $data['main']['humidity'] ?? 0,
                    'pressure'    => $data['main']['pressure'] ?? 0,
                    'wind_speed'  => $data['wind']['speed'] ?? 0,
                    'wind_deg'    => $data['wind']['deg'] ?? 0,
                    'clouds'      => $data['clouds']['all'] ?? 0,
                    'rain_1h'     => $data['rain']['1h'] ?? ($data['rain']['3h'] ?? 0),
                    'visibility'  => ($data['visibility'] ?? 10000) / 1000,
                    'description' => $data['weather'][0]['description'] ?? 'N/A',
                    'icon'        => $data['weather'][0]['icon'] ?? '01d',
                    'main'        => $data['weather'][0]['main'] ?? 'Clear',
                ];
            } catch (\Exception $e) {
                Log::error("Weather fetch error for {$municipality}: " . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Fetch 5-day/3-hour forecast for a municipality from OpenWeatherMap
     * Cached for 15 minutes
     */
    private function fetchForecast(string $municipality): ?array
    {
        $apiKey = env('OPENWEATHER_API_KEY');
        if (!$apiKey) return null;

        $coords = $this->municipalityCoords[$municipality] ?? null;
        if (!$coords) return null;

        $cacheKey = "owm_forecast_{$municipality}";
        return Cache::remember($cacheKey, 900, function () use ($coords, $apiKey, $municipality) {
            try {
                $response = Http::timeout(15)->get('https://api.openweathermap.org/data/2.5/forecast', [
                    'lat'   => $coords['lat'],
                    'lon'   => $coords['lon'],
                    'appid' => $apiKey,
                    'units' => 'metric',
                ]);

                if (!$response->successful()) {
                    Log::warning("OpenWeather Forecast API error for {$municipality}: " . $response->status());
                    return null;
                }

                return $response->json();
            } catch (\Exception $e) {
                Log::error("Forecast fetch error for {$municipality}: " . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get Climate Hazard Alerts based on LIVE weather from OpenWeatherMap
     * Falls back to demo data if API key not configured
     */
    public function getAlerts(Request $request)
    {
        try {
            $municipality = $request->query('municipality');
            $alerts = [];

            $checkList = ($municipality && $municipality !== 'all')
                ? [$municipality]
                : array_keys($this->municipalityCoords);

            foreach ($checkList as $muni) {
                $weather = $this->fetchWeather($muni);
                if (!$weather) continue;

                // Heavy rain alert
                if ($weather['rain_1h'] > 5) {
                    $alerts[] = [
                        'id'          => 'rain_' . $muni,
                        'type'        => 'heavy_rainfall',
                        'title'       => 'Heavy Rainfall Alert — ' . $muni,
                        'time'        => 'Now, ' . Carbon::now()->format('g:i A'),
                        'description' => "Current rainfall of {$weather['rain_1h']}mm/h recorded in {$muni}. Risk of flooding in low-lying farm areas. Advise farmers to secure harvested crops and check drainage.",
                        'locations'   => [$muni],
                        'severity'    => $weather['rain_1h'] > 15 ? 'high' : 'medium',
                        'riskLabel'   => $weather['rain_1h'] > 15 ? 'High Risk' : 'Medium Risk',
                    ];
                }

                // Strong wind alert
                if ($weather['wind_speed'] > 8) {
                    $alerts[] = [
                        'id'          => 'wind_' . $muni,
                        'type'        => 'tropical_depression',
                        'title'       => 'Strong Wind Advisory — ' . $muni,
                        'time'        => 'Now, ' . Carbon::now()->format('g:i A'),
                        'description' => "Wind speed at {$weather['wind_speed']} m/s in {$muni}. Secure greenhouses and tall crop supports. Delay pesticide spraying.",
                        'locations'   => [$muni],
                        'severity'    => $weather['wind_speed'] > 12 ? 'high' : 'medium',
                        'riskLabel'   => $weather['wind_speed'] > 12 ? 'High Risk' : 'Medium Risk',
                    ];
                }

                // Low visibility / fog
                if ($weather['visibility'] < 2) {
                    $alerts[] = [
                        'id'          => 'fog_' . $muni,
                        'type'        => 'heavy_rainfall',
                        'title'       => 'Dense Fog Advisory — ' . $muni,
                        'time'        => 'Now, ' . Carbon::now()->format('g:i A'),
                        'description' => "Visibility at {$weather['visibility']}km in {$muni}. Exercise caution on highland roads. Delay field work if visibility is impaired.",
                        'locations'   => [$muni],
                        'severity'    => 'low',
                        'riskLabel'   => 'Low Risk',
                    ];
                }
            }

            // If no real alerts found, return demo alerts (fallback when API key not set)
            if (empty($alerts)) {
                $alerts = $this->getDemoAlerts();
            }

            return response()->json([
                'alerts'       => array_values($alerts),
                'source'       => empty($alerts) ? 'Demo Data' : 'OpenWeatherMap (Live)',
                'last_updated' => Carbon::now()->toIso8601String(),
            ]);

        } catch (\Exception $e) {
            Log::error('Climate Alerts Error: ' . $e->getMessage());
            return response()->json([
                'alerts'       => $this->getDemoAlerts(),
                'source'       => 'Demo Data (Error Recovery)',
                'last_updated' => Carbon::now()->toIso8601String(),
            ]);
        }
    }

    /**
     * Get demo/fallback alerts for when API is not available
     */
    private function getDemoAlerts(): array
    {
        return [
            [
                'id'          => 'demo_alert_1',
                'type'        => 'heavy_rainfall',
                'title'       => 'Moderate Rainfall Alert — La Trinidad',
                'time'        => 'Now, ' . Carbon::now()->format('g:i A'),
                'description' => 'Expected moderate rainfall in La Trinidad. Monitor drainage systems and ensure proper field water management.',
                'locations'   => ['La Trinidad'],
                'severity'    => 'medium',
                'riskLabel'   => 'Medium Risk',
            ],
            [
                'id'          => 'demo_alert_2',
                'type'        => 'advisory',
                'title'       => 'Vegetable Monitoring Advisory — Benguet',
                'time'        => 'Today, 2:00 PM',
                'description' => 'Suitable conditions for vegetable cultivation. Optimal temperature and humidity for cabbage and lettuce growth.',
                'locations'   => ['Atok', 'Bokod', 'Kapangan'],
                'severity'    => 'low',
                'riskLabel'   => 'Favorable',
            ],
        ];
    }
                        'locations'   => [$muni],
                        'severity'    => $weather['wind_speed'] > 14 ? 'high' : 'medium',
                        'riskLabel'   => $weather['wind_speed'] > 14 ? 'High Risk' : 'Medium Risk',
                    ];
                }

                // Extreme temperature alert
                if ($weather['temp'] > 30) {
                    $alerts[] = [
                        'id'          => 'heat_' . $muni,
                        'type'        => 'drought',
                        'title'       => 'High Temperature Warning — ' . $muni,
                        'time'        => 'Today, ' . Carbon::now()->format('g:i A'),
                        'description' => "Temperature at {$weather['temp']}°C in {$muni} — above normal for Cordillera highlands. Increase irrigation, apply mulching to retain soil moisture.",
                        'locations'   => [$muni],
                        'severity'    => $weather['temp'] > 34 ? 'high' : 'medium',
                        'riskLabel'   => $weather['temp'] > 34 ? 'High Risk' : 'Medium Risk',
                    ];
                }

                // Very high humidity - fungal risk
                if ($weather['humidity'] > 90) {
                    $alerts[] = [
                        'id'          => 'humid_' . $muni,
                        'type'        => 'heavy_rainfall',
                        'title'       => 'High Humidity / Fungal Risk — ' . $muni,
                        'time'        => 'Today, ' . Carbon::now()->format('g:i A'),
                        'description' => "Humidity at {$weather['humidity']}% in {$muni}. Conditions favor fungal diseases (blight, mildew). Monitor vegetable crops and apply preventive fungicide.",
                        'locations'   => [$muni],
                        'severity'    => 'low',
                        'riskLabel'   => 'Low Risk',
                    ];
                }

                // Low visibility / fog
                if ($weather['visibility'] < 2) {
                    $alerts[] = [
                        'id'          => 'fog_' . $muni,
                        'type'        => 'heavy_rainfall',
                        'title'       => 'Dense Fog Advisory — ' . $muni,
                        'time'        => 'Now, ' . Carbon::now()->format('g:i A'),
                        'description' => "Visibility at {$weather['visibility']}km in {$muni}. Exercise caution on highland roads. Delay field work if visibility is impaired.",
                        'locations'   => [$muni],
                        'severity'    => 'low',
                        'riskLabel'   => 'Low Risk',
                    ];
                }
            }

            return response()->json([
                'alerts'       => array_values($alerts),
                'source'       => 'OpenWeatherMap (Live)',
                'last_updated' => Carbon::now()->toIso8601String(),
            ]);

        } catch (\Exception $e) {
            Log::error('Climate Alerts Error: ' . $e->getMessage());
            return response()->json([
                'alerts' => [],
                'error'  => 'Failed to load climate alerts: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get 7-Day Rainfall Forecast using OpenWeatherMap 5-day forecast API
     */
    public function getRainfallForecast(Request $request)
    {
        try {
            $municipality = $request->query('municipality', 'La Trinidad');

            $forecastData = $this->fetchForecast($municipality);
            $forecast = [];

            if ($forecastData && isset($forecastData['list'])) {
                // Group 3-hour intervals by day
                $daily = [];
                $today = Carbon::now()->format('Y-m-d');

                foreach ($forecastData['list'] as $item) {
                    $date    = date('Y-m-d', $item['dt']);
                    $dayName = date('l', $item['dt']);

                    if (!isset($daily[$date])) {
                        $daily[$date] = [
                            'day'      => $dayName,
                            'rainfall' => 0,
                            'pop'      => 0,
                            'count'    => 0,
                        ];
                    }

                    $rain = $item['rain']['3h'] ?? 0;
                    $daily[$date]['rainfall'] += $rain;
                    $daily[$date]['pop'] = max($daily[$date]['pop'], ($item['pop'] ?? 0) * 100);
                    $daily[$date]['count']++;
                }

                foreach ($daily as $date => $dayData) {
                    $forecast[] = [
                        'day'        => $dayData['day'],
                        'rainfall'   => round($dayData['rainfall'], 1),
                        'percentage' => round($dayData['pop']) . '%',
                    ];
                }

                // Limit to 7 days max
                $forecast = array_slice($forecast, 0, 7);
            }

            // If no forecast data, return demo data
            if (empty($forecast)) {
                $forecast = $this->getDemoRainfallForecast();
            }

            return response()->json([
                'forecast'     => $forecast,
                'municipality' => $municipality,
                'source'       => count($forecast) > 0 ? (empty($forecastData) ? 'Demo Forecast' : 'OpenWeatherMap (Live)') : 'No data',
                'last_updated' => Carbon::now()->toIso8601String(),
            ]);

        } catch (\Exception $e) {
            Log::error('Rainfall Forecast Error: ' . $e->getMessage());
            return response()->json([
                'forecast'     => $this->getDemoRainfallForecast(),
                'municipality' => $request->query('municipality', 'La Trinidad'),
                'source'       => 'Demo Forecast (Error Recovery)',
                'last_updated' => Carbon::now()->toIso8601String(),
            ]);
        }
    }

    /**
     * Get demo/fallback rainfall forecast
     */
    private function getDemoRainfallForecast(): array
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $forecast = [];
        
        for ($i = 0; $i < 7; $i++) {
            $forecast[] = [
                'day'        => $days[$i % 7],
                'rainfall'   => rand(2, 8),
                'percentage' => (rand(30, 80)) . '%',
            ];
        }
        
        return $forecast;
    }

    /**
     * Get Provincial Climate Status for all municipalities using LIVE OpenWeatherMap data
     */
    public function getMunicipalityStatus(Request $request)
    {
        try {
            $municipalities = [];
            $hasRealData = false;

            foreach ($this->municipalityCoords as $muni => $coords) {
                $weather = $this->fetchWeather($muni);

                if ($weather) {
                    $hasRealData = true;
                    $rainfall    = round($weather['rain_1h'], 1);
                    $temperature = round($weather['temp'], 1);
                    $humidity    = round($weather['humidity']);
                    $status      = $this->determineClimateStatus($weather);
                } else {
                    // Fallback with demo data
                    $rainfall    = rand(1, 5);
                    $temperature = rand(18, 24);
                    $humidity    = rand(65, 80);
                    $status      = 'Favorable'; // Default favorable status for Cordillera
                }

                $municipalities[] = [
                    'name'        => $muni,
                    'status'      => $status,
                    'rainfall'    => $rainfall,
                    'temperature' => $temperature,
                    'humidity'    => $humidity,
                    'description' => $weather['description'] ?? 'Partly Cloudy',
                    'wind_speed'  => $weather['wind_speed'] ?? rand(2, 4),
                    'icon'        => $weather['icon'] ?? '02d',
                ];
            }

            // Sort by status priority (Watch first, then Favorable, then Normal)
            usort($municipalities, function ($a, $b) {
                $priority = ['Watch' => 0, 'Favorable' => 1, 'Normal' => 2];
                return ($priority[$a['status']] ?? 3) <=> ($priority[$b['status']] ?? 3);
            });

            return response()->json([
                'municipalities' => $municipalities,
                'source'         => $hasRealData ? 'OpenWeatherMap (Live)' : 'Demo Data',
                'last_updated'   => Carbon::now()->toIso8601String(),
            ]);

        } catch (\Exception $e) {
            Log::error('Municipality Status Error: ' . $e->getMessage());
            // Return demo data on error
            return response()->json([
                'municipalities' => $this->getDemoMunicipalityStatus(),
                'source'         => 'Demo Data (Error Recovery)',
                'last_updated'   => Carbon::now()->toIso8601String(),
            ]);
        }
    }

    /**
     * Get demo municipality status data
     */
    private function getDemoMunicipalityStatus(): array
    {
        $statuses = ['Favorable', 'Normal', 'Watch'];
        $municipalities = [];

        foreach ($this->municipalityCoords as $muni => $coords) {
            $municipalities[] = [
                'name'        => $muni,
                'status'      => $statuses[array_rand($statuses)],
                'rainfall'    => rand(1, 8),
                'temperature' => rand(18, 24),
                'humidity'    => rand(65, 85),
                'description' => ['Partly Cloudy', 'Clear', 'Overcast'][array_rand(['Partly Cloudy', 'Clear', 'Overcast'])],
                'wind_speed'  => rand(2, 5),
                'icon'        => ['01d', '02d', '03d'][array_rand(['01d', '02d', '03d'])],
            ];
        }

        usort($municipalities, function ($a, $b) {
            $priority = ['Watch' => 0, 'Favorable' => 1, 'Normal' => 2];
            return ($priority[$a['status']] ?? 3) <=> ($priority[$b['status']] ?? 3);
        });

        return $municipalities;
    }

    /**
     * Determine climate status based on real-time weather data
     *
     * Watch:     Extreme rain (>5mm/h), strong wind (>8 m/s), temp >30°C, humidity >90%, visibility <2km
     * Favorable: Temp 15-24°C, humidity 55-80%, low wind, light or no rain — ideal growing
     * Normal:    Everything else
     */
    private function determineClimateStatus(array $weather): string
    {
        $rain    = $weather['rain_1h'] ?? 0;
        $temp    = $weather['temp'] ?? 20;
        $hum     = $weather['humidity'] ?? 70;
        $wind    = $weather['wind_speed'] ?? 0;
        $vis     = $weather['visibility'] ?? 10;

        // Watch: any extreme condition
        if ($rain > 5 || $wind > 8 || $temp > 30 || $hum > 90 || $vis < 2) {
            return 'Watch';
        }

        // Favorable: ideal Cordillera agriculture conditions
        if ($temp >= 15 && $temp <= 24 && $hum >= 55 && $hum <= 80 && $wind <= 5 && $rain <= 2) {
            return 'Favorable';
        }

        return 'Normal';
    }
}
