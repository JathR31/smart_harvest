<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MLApiService
{
    protected $baseUrl;
    protected $timeout;
    protected $retryTimes;

    public function __construct()
    {
        $this->baseUrl = config('ml.api_url');
        $this->timeout = config('ml.timeout', 30);
        $this->retryTimes = config('ml.retry_times', 2);
    }

    /**
     * Check if the ML API is reachable and healthy
     */
    public function checkHealth()
    {
        try {
            $response = Http::timeout($this->timeout)
                ->retry($this->retryTimes, 100)
                ->get($this->baseUrl . config('ml.endpoints.health'));

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'message' => 'ML API is healthy and reachable',
                    'data' => $response->json(),
                    'response_time' => $response->handlerStats()['total_time'] ?? null,
                ];
            }

            return [
                'status' => 'error',
                'message' => 'ML API returned non-successful status code',
                'status_code' => $response->status(),
                'body' => $response->body(),
            ];
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('ML API Connection Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Cannot connect to ML API service',
                'error' => $e->getMessage(),
                'api_url' => $this->baseUrl,
            ];
        } catch (\Exception $e) {
            Log::error('ML API Health Check Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error checking ML API health',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Make a prediction request to the ML API
     */
    public function predict(array $data)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->retry($this->retryTimes, 100)
                ->post($this->baseUrl . config('ml.endpoints.predict'), $data);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $response->json(),
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Prediction request failed',
                'status_code' => $response->status(),
                'body' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('ML API Prediction Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error making prediction request',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Make a forecast request to the ML API
     */
    public function forecast(array $data)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->retry($this->retryTimes, 100)
                ->post($this->baseUrl . config('ml.endpoints.forecast'), $data);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $response->json(),
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Forecast request failed',
                'status_code' => $response->status(),
                'body' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('ML API Forecast Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error making forecast request',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get the ML API base URL
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
}
