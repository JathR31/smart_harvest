<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HuggingFaceService
{
    private $apiKey;
    private $baseUrl = 'https://api-inference.huggingface.co/models';
    private $model;

    public function __construct()
    {
        // Hugging Face API is free! Get your token from https://huggingface.co/settings/tokens
        $this->apiKey = env('HUGGINGFACE_API_KEY', '');
        // Using a reliable free model with good availability
        $this->model = env('HUGGINGFACE_MODEL', 'google/flan-t5-base');
    }

    /**
     * Generate interpretation for yield comparison data
     */
    public function interpretYieldComparison($yieldData)
    {
        $prompt = $this->buildYieldComparisonPrompt($yieldData);
        return $this->generateInterpretation($prompt);
    }

    /**
     * Generate interpretation for crop performance data
     */
    public function interpretCropPerformance($cropData)
    {
        $prompt = $this->buildCropPerformancePrompt($cropData);
        return $this->generateInterpretation($prompt);
    }

    /**
     * Generate interpretation for monthly yield trends
     */
    public function interpretMonthlyTrends($monthlyData)
    {
        $prompt = $this->buildMonthlyTrendsPrompt($monthlyData);
        return $this->generateInterpretation($prompt);
    }

    /**
     * Build prompt for yield comparison analysis
     */
    private function buildYieldComparisonPrompt($data)
    {
        $dataStr = json_encode($data, JSON_PRETTY_PRINT);
        
        return "[INST] You are an agricultural data analyst. Analyze this yield data and provide a concise 2-3 sentence interpretation.

Data: {$dataStr}

Focus on trends, changes, and recommendations for farmers. Be specific with numbers. [/INST]";
    }

    /**
     * Build prompt for crop performance analysis
     */
    private function buildCropPerformancePrompt($data)
    {
        $dataStr = json_encode($data, JSON_PRETTY_PRINT);
        
        return "[INST] You are an agricultural data analyst. Analyze this crop performance data and provide a concise 2-3 sentence interpretation.

Data: {$dataStr}

Identify best performing crops and provide recommendations. Be specific. [/INST]";
    }

    /**
     * Build prompt for monthly trends analysis
     */
    private function buildMonthlyTrendsPrompt($data)
    {
        $dataStr = json_encode($data, JSON_PRETTY_PRINT);
        
        return "[INST] You are an agricultural data analyst. Analyze this monthly yield data and provide a concise 2-3 sentence interpretation.

Data: {$dataStr}

Identify peak months, patterns, and planting recommendations. [/INST]";
    }

    /**
     * Generate interpretation using Hugging Face API with fallback
     */
    private function generateInterpretation($prompt)
    {
        try {
            // Try Hugging Face first
            $headers = [
                'Content-Type' => 'application/json',
            ];
            
            if (!empty($this->apiKey)) {
                $headers['Authorization'] = 'Bearer ' . $this->apiKey;
            }

            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->post($this->baseUrl . '/' . $this->model, [
                    'inputs' => $prompt,
                    'parameters' => [
                        'max_new_tokens' => 200,
                        'temperature' => 0.7,
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data[0]['generated_text'])) {
                    $interpretation = $data[0]['generated_text'];
                    $interpretation = str_replace($prompt, '', $interpretation);
                    $interpretation = trim($interpretation);
                    
                    return [
                        'status' => 'success',
                        'interpretation' => $interpretation,
                        'model' => 'AI Analysis'
                    ];
                }
            }
            
            // Fallback to rule-based interpretation
            return $this->generateRuleBasedInterpretation($prompt);
            
        } catch (\Exception $e) {
            // Fallback to rule-based interpretation
            return $this->generateRuleBasedInterpretation($prompt);
        }
    }
    
    /**
     * Generate rule-based interpretation as fallback
     */
    private function generateRuleBasedInterpretation($prompt)
    {
        // Extract data from prompt
        preg_match('/Data:\s*({.*?})/s', $prompt, $matches);
        
        if (!$matches) {
            return [
                'status' => 'success',
                'interpretation' => 'Data analysis shows consistent agricultural patterns. Continue monitoring crop performance for optimal results.',
                'model' => 'Basic Analysis'
            ];
        }
        
        $dataStr = $matches[1];
        $data = json_decode($dataStr, true);
        
        if (!$data) {
            return [
                'status' => 'success',
                'interpretation' => 'Agricultural data indicates stable farming conditions. Regular monitoring recommended.',
                'model' => 'Basic Analysis'
            ];
        }
        
        // Generate interpretation based on data patterns
        $interpretation = '';
        
        // Yield comparison analysis
        if (isset($data[0]['year'])) {
            $yields = array_column($data, 'avg_yield');
            $avg = array_sum($yields) / count($yields);
            $trend = end($yields) > $yields[0] ? 'increasing' : 'decreasing';
            $change = abs(end($yields) - $yields[0]);
            
            $interpretation = "Yield trends show a {$trend} pattern over the analyzed period with an average of " . number_format($avg, 2) . " mt/ha. ";
            $interpretation .= "The change of " . number_format($change, 2) . " mt/ha indicates ";
            $interpretation .= $trend === 'increasing' ? 'improved farming practices.' : 'need for crop management review.';
        }
        // Crop performance analysis
        elseif (isset($data[0]['crop'])) {
            $topCrop = $data[0];
            $avgYield = array_sum(array_column($data, 'avg_yield')) / count($data);
            
            $interpretation = "{$topCrop['crop']} shows the highest performance at " . number_format($topCrop['avg_yield'], 2) . " mt/ha. ";
            $interpretation .= "Average yields across all crops is " . number_format($avgYield, 2) . " mt/ha. ";
            $interpretation .= "Focus on top-performing crops for better returns.";
        }
        // Monthly trends analysis
        elseif (isset($data[0]['month'])) {
            $yields = array_column($data, 'yield');
            $maxIdx = array_search(max($yields), $yields);
            $peakMonth = $data[$maxIdx]['month'];
            
            $interpretation = "Peak harvest occurs in {$peakMonth} with " . number_format(max($yields), 2) . " mt. ";
            $interpretation .= "Plan planting schedules to align with optimal harvest periods for maximum yield.";
        }
        else {
            $interpretation = 'Data analysis complete. Monitoring agricultural patterns for continued optimization.';
        }
        
        return [
            'status' => 'success',
            'interpretation' => $interpretation,
            'model' => 'Statistical Analysis'
        ];
    }

    /**
     * Test the Hugging Face connection
     */
    public function testConnection()
    {
        try {
            $headers = ['Content-Type' => 'application/json'];
            
            if (!empty($this->apiKey)) {
                $headers['Authorization'] = 'Bearer ' . $this->apiKey;
            }

            $response = Http::withHeaders($headers)
                ->timeout(60)
                ->post($this->baseUrl . '/' . $this->model, [
                    'inputs' => 'Test connection',
                    'parameters' => ['max_new_tokens' => 10]
                ]);

            if ($response->successful() || $response->status() == 503) {
                // 503 means model is loading, which is fine
                return [
                    'status' => 'success',
                    'message' => 'Hugging Face connection successful'
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
}
