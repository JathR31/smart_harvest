<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    private $apiKey;
    private $baseUrl = 'https://api.groq.com/openai/v1';
    private $model;

    public function __construct()
    {
        // Groq is FREE and FAST! Get your key from https://console.groq.com/keys
        $this->apiKey = env('GROQ_API_KEY', '');
        $this->model = env('GROQ_MODEL', 'llama-3.1-8b-instant');
    }

    /**
     * Generate interpretation for yield comparison data
     */
    public function interpretYieldComparison($yieldData)
    {
        return [
            'status' => 'success',
            'interpretation' => $this->analyzeYieldData($yieldData),
            'model' => 'Statistical Analysis'
        ];
    }

    /**
     * Generate interpretation for crop performance data
     */
    public function interpretCropPerformance($cropData)
    {
        return [
            'status' => 'success',
            'interpretation' => $this->analyzeCropData($cropData),
            'model' => 'Statistical Analysis'
        ];
    }

    /**
     * Generate interpretation for monthly yield trends
     */
    public function interpretMonthlyTrends($monthlyData)
    {
        return [
            'status' => 'success',
            'interpretation' => $this->analyzeMonthlyData($monthlyData),
            'model' => 'Statistical Analysis'
        ];
    }

    /**
     * Analyze yield comparison data directly
     */
    private function analyzeYieldData($data)
    {
        if (empty($data) || !is_array($data)) {
            return "• No data available for selected period";
        }

        $first = $data[0];
        $last = end($data);
        $firstYield = floatval($first['actual_yield'] ?? 0);
        $lastYield = floatval($last['actual_yield'] ?? 0);
        $firstYear = $first['year'] ?? '';
        $lastYear = $last['year'] ?? '';

        $percentChange = $firstYield > 0 ? round((($lastYield - $firstYield) / $firstYield) * 100, 1) : 0;
        $absChange = abs($percentChange);
        $trend = $percentChange > 0 ? 'increased' : ($percentChange < 0 ? 'decreased' : 'remained stable');

        return "• Actual yields {$trend} by {$absChange}% from {$firstYield} MT/ha ({$firstYear}) to {$lastYield} MT/ha ({$lastYear})";
    }

    /**
     * Analyze crop performance data directly
     */
    private function analyzeCropData($data)
    {
        if (empty($data) || !is_array($data)) {
            return "• No crop data available for this selection";
        }

        $topCrop = $data[0];
        $cropName = $topCrop['crop'] ?? '';
        $currentYield = floatval($topCrop['current_yield'] ?? 0);
        $predictedYield = floatval($topCrop['ml_predicted_next_year'] ?? 0);
        
        $growthPercent = $currentYield > 0 ? round((($predictedYield - $currentYield) / $currentYield) * 100, 1) : 0;

        return "• {$cropName} shows highest yield at {$currentYield} MT/ha, ML predicts {$predictedYield} MT/ha next year ({$growthPercent}% growth)";
    }

    /**
     * Analyze monthly harvest data directly
     */
    private function analyzeMonthlyData($data)
    {
        if (empty($data) || !is_array($data)) {
            return "• No monthly harvest data available";
        }

        // Find peak and lowest months
        $maxHarvest = 0;
        $minHarvest = PHP_INT_MAX;
        $peakMonth = '';
        $lowMonth = '';

        foreach ($data as $record) {
            $harvest = floatval($record['actual_harvest'] ?? 0);
            $month = $record['month'] ?? '';
            
            if ($harvest > $maxHarvest) {
                $maxHarvest = $harvest;
                $peakMonth = $month;
            }
            if ($harvest < $minHarvest && $harvest > 0) {
                $minHarvest = $harvest;
                $lowMonth = $month;
            }
        }

        return "• Higher yields predicted during cool season (Oct-Mar) in Benguet region";
    }

    /**
     * Build prompt for yield comparison analysis
     */
    private function buildYieldComparisonPrompt($data)
    {
        $dataStr = json_encode($data, JSON_PRETTY_PRINT);
        
        return "Look at this yield data:
{$dataStr}

Generate EXACTLY 3 bullet points analyzing the graph 'Historical vs ML Predictions':

• First bullet: Calculate percentage change from first actual_yield to last actual_yield. State if increasing/decreasing.
• Second bullet: Compare ml_predicted_yield to actual_yield - state if predictions are accurate.
• Third bullet: State the ml_predicted_yield value for the most recent year.

Each bullet ONE sentence, max 15 words, using EXACT numbers from data above.";
    }

    /**
     * Build prompt for crop performance analysis
     */
    private function buildCropPerformancePrompt($data)
    {
        $dataStr = json_encode($data, JSON_PRETTY_PRINT);
        
        return "Look at this crop data:
{$dataStr}

Generate EXACTLY 3 bullet points analyzing 'ML Predicted Crop Performance':

• First bullet: Name the crop with highest current_yield with exact number.
• Second bullet: State the ml_predicted_next_year for that top crop.
• Third bullet: If there are 2+ crops in the data, compare top vs second crop current_yield. If only 1 crop, say 'Only one crop in data.'

Each bullet ONE sentence, max 15 words, using EXACT numbers from data.";
    }

    /**
     * Build prompt for monthly trends analysis
     */
    private function buildMonthlyTrendsPrompt($data)
    {
        $dataStr = json_encode($data, JSON_PRETTY_PRINT);
        
        return "Look at this monthly data:
{$dataStr}

Generate EXACTLY 3 bullet points analyzing 'ML Seasonal Predictions':

• First bullet: State month with highest actual_harvest and exact value.
• Second bullet: State month with lowest actual_harvest and exact value.
• Third bullet: Say 'Higher yields occur during cool season (Oct-Mar) in Benguet.'

Each bullet ONE sentence, max 15 words, using EXACT numbers from data.";
    }

    /**
     * Generate interpretation using Groq API with fallback
     */
    private function generateInterpretation($prompt)
    {
        // Check if API key is available
        if (empty($this->apiKey)) {
            Log::info('Groq API key not set, using fallback');
            return $this->generateRuleBasedInterpretation($prompt);
        }

        try {
            Log::info('Making Groq API call...');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(15)->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are analyzing data for farmers. Use simple words. Generate ONLY 3 bullet points with NO introductory text. Each bullet should be one clear sentence under 15 words. Start each line with • and nothing else before the first bullet point.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.5,
                'max_tokens' => 100,
            ]);

            Log::info('Groq API response status: ' . $response->status());

            if ($response->successful()) {
                $data = $response->json();
                $interpretation = $data['choices'][0]['message']['content'] ?? '';
                
                Log::info('Groq AI response received', ['length' => strlen($interpretation)]);
                
                return [
                    'status' => 'success',
                    'interpretation' => trim($interpretation),
                    'model' => 'Groq AI (Llama 3.1)'
                ];
            } else {
                Log::warning('Groq API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return $this->generateRuleBasedInterpretation($prompt);
            }
            
        } catch (\Exception $e) {
            Log::warning('Groq API exception: ' . $e->getMessage());
            return $this->generateRuleBasedInterpretation($prompt);
        }
    }
    
    /**
     * Generate farmer-friendly interpretation as fallback
     */
    private function generateRuleBasedInterpretation($prompt)
    {
        // Extract data from prompt - try multiple patterns
        preg_match('/Data:\s*(\[.*?\])/s', $prompt, $matches);
        if (!$matches) {
            preg_match('/\[(.*?)\]/s', $prompt, $matches);
        }
        
        if (!$matches) {
            return [
                'status' => 'success',
                'interpretation' => "• No data available for selected period.\n• Try selecting a different year or municipality.\n• System ready to analyze when data is available.",
                'model' => 'Smart Analysis'
            ];
        }
        
        $dataStr = $matches[0];
        $data = json_decode($dataStr, true);
        
        if (!$data || !is_array($data) || empty($data)) {
            return [
                'status' => 'success',
                'interpretation' => "• No harvest data found for this selection.\n• Try a different year or municipality with recorded harvests.\n• System is ready to analyze available data.",
                'model' => 'Smart Analysis'
            ];
        }
        
        $interpretation = '';
        
        // Yield comparison analysis - professional and accurate
        if (isset($data[0]['year']) && isset($data[0]['avg_yield'])) {
            $yields = array_column($data, 'avg_yield');
            $years = array_column($data, 'year');
            $first = $yields[0];
            $last = end($yields);
            $firstYear = $years[0];
            $lastYear = end($years);
            
            if ($last > $first) {
                $increase = round((($last - $first) / $first) * 100, 1);
                $interpretation = "Yields went up {$increase}%. Keep your current methods.";
            } elseif ($last < $first) {
                $decrease = round((($first - $last) / $first) * 100, 1);
                $interpretation = "Yields dropped {$decrease}%. Check soil and seeds.";
            } else {
                $interpretation = "Yields steady. Try crop rotation.";
            }
        }
        // Crop performance analysis - professional and accurate
        elseif (isset($data[0]['crop']) && isset($data[0]['avg_yield'])) {
            $topCrop = $data[0];
            $topYield = number_format($topCrop['avg_yield'], 2);
            $cropName = $topCrop['crop'];
            $secondCrop = isset($data[1]) ? $data[1]['crop'] : null;
            $secondYield = isset($data[1]) ? number_format($data[1]['avg_yield'], 2) : null;
            
            if ($secondCrop) {
                $interpretation = "Plant {$cropName} ({$topYield} MT/ha) or {$secondCrop} ({$secondYield} MT/ha).";
            } else {
                $interpretation = "Plant {$cropName} - best yield at {$topYield} MT/ha.";
            }
        }
        // Monthly trends analysis - professional and accurate
        elseif (isset($data[0]['month']) && isset($data[0]['yield'])) {
            $yields = array_column($data, 'yield');
            $months = array_column($data, 'month');
            $maxIdx = array_search(max($yields), $yields);
            $peakMonth = $months[$maxIdx];
            $peakYield = number_format(max($yields), 2);
            
            // Calculate planting month (3-4 months before peak)
            $monthMap = ['Jan'=>1, 'Feb'=>2, 'Mar'=>3, 'Apr'=>4, 'May'=>5, 'Jun'=>6, 'Jul'=>7, 'Aug'=>8, 'Sep'=>9, 'Oct'=>10, 'Nov'=>11, 'Dec'=>12];
            $peakMonthNum = $monthMap[$peakMonth] ?? 1;
            $plantMonthNum = $peakMonthNum - 3;
            if ($plantMonthNum <= 0) $plantMonthNum += 12;
            $plantMonth = array_search($plantMonthNum, $monthMap) ?: 'January';
            
            $interpretation = "Best harvest in {$peakMonth}. Plant in {$plantMonth}.";
        }
        // Rainfall forecast analysis
        elseif (isset($data[0]['week']) && isset($data[0]['rainfall'])) {
            $rainfalls = array_column($data, 'rainfall');
            $weeks = array_column($data, 'week');
            $maxRainfall = max($rainfalls);
            $minRainfall = min($rainfalls);
            $totalRainfall = array_sum($rainfalls);
            $maxWeek = $weeks[array_search($maxRainfall, $rainfalls)];
            $minWeek = $weeks[array_search($minRainfall, $rainfalls)];
            
            $adequacy = $totalRainfall > 200 ? 'adequate' : 'moderate';
            
            $interpretation = "• {$maxWeek} shows highest rainfall at {$maxRainfall}mm, ideal for crop growth\n• Total monthly rainfall of {$totalRainfall}mm is {$adequacy} for highland vegetables\n• Plan irrigation for {$minWeek} with lower rainfall at {$minRainfall}mm";
        }
        else {
            $interpretation = 'Insufficient data for comprehensive analysis. Continue monitoring crop performance and maintain cultivation records.';
        }
        
        return [
            'status' => 'success',
            'interpretation' => $interpretation,
            'model' => 'Smart Analysis'
        ];
    }

    /**
     * Test the Groq connection
     */
    public function testConnection()
    {
        if (empty($this->apiKey)) {
            return [
                'status' => 'error',
                'message' => 'API key not configured'
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->timeout(5)->get($this->baseUrl . '/models');

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'message' => 'Groq AI connected successfully'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Connection failed: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate interpretation for temperature forecast
     */
    public function interpretTemperatureForecast($tempData, $municipality)
    {
        $prompt = $this->buildTemperatureForecastPrompt($tempData, $municipality);
        $result = $this->generateInterpretation($prompt);
        
        // If generateInterpretation returns a string, wrap it properly
        if (is_string($result)) {
            return [
                'status' => 'success',
                'interpretation' => $result,
                'model' => 'AI Analysis'
            ];
        }
        
        return $result;
    }

    /**
     * Generate interpretation for rainfall forecast
     */
    public function interpretRainfallForecast($rainfallData, $municipality)
    {
        $prompt = $this->buildRainfallForecastPrompt($rainfallData, $municipality);
        $result = $this->generateInterpretation($prompt);
        
        // If generateInterpretation returns a string, wrap it properly
        if (is_string($result)) {
            return [
                'status' => 'success',
                'interpretation' => $result,
                'model' => 'AI Analysis'
            ];
        }
        
        return $result;
    }

    /**
     * Build prompt for temperature forecast analysis
     */
    private function buildTemperatureForecastPrompt($data, $municipality)
    {
        $dataStr = json_encode($data, JSON_PRETTY_PRINT);
        
        return "Analyze this 5-day temperature forecast for {$municipality}:
{$dataStr}

Generate EXACTLY 3 bullet points about temperature patterns for farmers:

• First bullet: Identify the day with highest temperature and lowest temperature with exact values.
• Second bullet: Calculate and state the average temperature range across the 5 days.
• Third bullet: Provide one specific farming recommendation based on the temperature pattern.

Each bullet ONE sentence, max 20 words, using EXACT numbers from data. Focus on practical farming implications.";
    }

    /**
     * Build prompt for rainfall forecast analysis
     */
    private function buildRainfallForecastPrompt($data, $municipality)
    {
        $dataStr = json_encode($data, JSON_PRETTY_PRINT);
        
        return "Analyze this 4-week rainfall forecast for {$municipality}:
{$dataStr}

Generate EXACTLY 3 bullet points about rainfall patterns for farmers:

• First bullet: Identify the week with highest rainfall and exact amount in mm.
• Second bullet: Calculate total expected rainfall for the month and state if it's adequate for crops.
• Third bullet: Provide one specific farming recommendation based on rainfall pattern (e.g., irrigation, planting timing).

Each bullet ONE sentence, max 20 words, using EXACT numbers from data. Focus on practical farming implications.";
    }

    /**
     * Generate interpretation for hourly forecast
     */
    public function interpretHourlyForecast($hourlyData, $municipality)
    {
        $prompt = $this->buildHourlyForecastPrompt($hourlyData, $municipality);
        $result = $this->generateInterpretation($prompt);
        
        // If generateInterpretation returns a string, wrap it properly
        if (is_string($result)) {
            return [
                'status' => 'success',
                'interpretation' => $result,
                'model' => 'AI Analysis'
            ];
        }
        
        return $result;
    }

    /**
     * Build prompt for hourly forecast analysis
     */
    private function buildHourlyForecastPrompt($data, $municipality)
    {
        $dataStr = json_encode($data, JSON_PRETTY_PRINT);
        
        return "Analyze this 8-hour weather forecast for {$municipality}:
{$dataStr}

Generate EXACTLY 3 bullet points about hourly conditions for farmers:

• First bullet: Identify temperature range and peak hours with exact values.
• Second bullet: Note any weather changes (humidity, wind, conditions) that affect farm work.
• Third bullet: Recommend best hours for outdoor farming activities based on conditions.

Each bullet ONE sentence, max 20 words, using EXACT data. Focus on practical farm work timing.";
    }
}
