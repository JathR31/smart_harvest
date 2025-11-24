<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService
{
    private $apiKey;
    private $baseUrl = 'https://openrouter.ai/api/v1';
    private $model;

    public function __construct()
    {
        $this->apiKey = env('OPENROUTER_API_KEY');
        // You can configure this in .env: OPENROUTER_MODEL
        // Free models: google/gemini-2.0-flash-exp:free, meta-llama/llama-3.2-3b-instruct:free
        // Paid models: anthropic/claude-3.5-sonnet, openai/gpt-4
        $this->model = env('OPENROUTER_MODEL', 'google/gemini-2.0-flash-exp:free');
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
        
        return "You are an agricultural data analyst for SmartHarvest system. Analyze the following multi-year yield data and provide a concise, actionable interpretation in 2-3 sentences.

Yield Comparison Data:
{$dataStr}

Focus on:
1. Overall yield trends (increasing/decreasing/stable)
2. Year-over-year changes and their significance
3. ML prediction accuracy if available
4. Practical recommendations for farmers

Provide your response in a clear, farmer-friendly language. Be specific with numbers and percentages when relevant.";
    }

    /**
     * Build prompt for crop performance analysis
     */
    private function buildCropPerformancePrompt($data)
    {
        $dataStr = json_encode($data, JSON_PRETTY_PRINT);
        
        return "You are an agricultural data analyst for SmartHarvest system. Analyze the following crop performance data and provide a concise interpretation in 2-3 sentences.

Crop Performance Data:
{$dataStr}

Focus on:
1. Which crops are performing best and why
2. Comparison between actual and predicted yields
3. Recommendations for crop selection based on performance
4. Any concerning patterns or opportunities

Provide your response in a clear, farmer-friendly language. Be specific about crop names and yield numbers.";
    }

    /**
     * Build prompt for monthly trends analysis
     */
    private function buildMonthlyTrendsPrompt($data)
    {
        $dataStr = json_encode($data, JSON_PRETTY_PRINT);
        
        return "You are an agricultural data analyst for SmartHarvest system. Analyze the following monthly yield trend data and provide a concise interpretation in 2-3 sentences.

Monthly Yield Data:
{$dataStr}

Focus on:
1. Peak harvest months and seasonal patterns
2. Low-yield months and potential causes
3. Recommendations for optimizing planting schedules
4. Climate or weather influences if apparent

Provide your response in a clear, farmer-friendly language. Mention specific months and patterns.";
    }

    /**
     * Generate interpretation using OpenRouter API
     */
    private function generateInterpretation($prompt)
    {
        try {
            if (empty($this->apiKey)) {
                Log::warning('OpenRouter API key not configured');
                return [
                    'status' => 'error',
                    'message' => 'AI interpretation service not configured. Please add OPENROUTER_API_KEY to your .env file.'
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'HTTP-Referer' => env('APP_URL', 'http://localhost'),
                'X-Title' => 'SmartHarvest Analysis'
            ])->timeout(30)->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 200,
                'temperature' => 0.7
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $interpretation = $data['choices'][0]['message']['content'] ?? '';
                
                return [
                    'status' => 'success',
                    'interpretation' => trim($interpretation),
                    'model' => $this->model
                ];
            } else {
                Log::error('OpenRouter API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                // Provide helpful messages for common errors
                if ($response->status() == 402) {
                    return [
                        'status' => 'error',
                        'message' => 'This AI model requires credits. Please add credits at https://openrouter.ai/credits, switch to a free model, or add your own API key.'
                    ];
                }
                
                if ($response->status() == 429) {
                    return [
                        'status' => 'error',
                        'message' => 'AI service is temporarily rate-limited. Please try again in a moment or switch to a paid model with higher limits.'
                    ];
                }
                
                return [
                    'status' => 'error',
                    'message' => 'Failed to generate interpretation. API returned status ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('OpenRouter service exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error connecting to AI service: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Test the OpenRouter connection
     */
    public function testConnection()
    {
        try {
            if (empty($this->apiKey)) {
                return [
                    'status' => 'error',
                    'message' => 'API key not configured'
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->timeout(10)->get($this->baseUrl . '/models');

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'message' => 'OpenRouter connection successful'
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
