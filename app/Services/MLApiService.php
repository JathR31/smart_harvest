<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MLApiService
{
    protected $baseUrl;
    protected $timeout;
    protected $retryTimes;

    /**
     * Logging should never break API responses.
     */
    private function safeLogError(string $message): void
    {
        try {
            Log::error($message);
        } catch (\Throwable $ignored) {
            // Ignore logging failures (e.g. unwritable log file in production)
        }
    }

    public function __construct()
    {
        $rawBaseUrl = rtrim((string) config('ml.api_url'), '/');
        if (!preg_match('/^https?:\/\//i', $rawBaseUrl)) {
            $rawBaseUrl = 'https://' . $rawBaseUrl;
        }

        $this->baseUrl = $rawBaseUrl;
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
                ->retry($this->retryTimes, 100, throw: false)
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
            $this->safeLogError('ML API Connection Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Cannot connect to ML API service',
                'error' => $e->getMessage(),
                'api_url' => $this->baseUrl,
            ];
        } catch (\Throwable $e) {
            $this->safeLogError('ML API Health Check Error: ' . $e->getMessage());
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
                ->retry($this->retryTimes, 100, throw: false)
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
        } catch (\Throwable $e) {
            $this->safeLogError('ML API Prediction Error: ' . $e->getMessage());
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
                ->retry($this->retryTimes, 100, throw: false)
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
        } catch (\Throwable $e) {
            $this->safeLogError('ML API Forecast Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error making forecast request',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get top performing crops
     */
    public function getTopCrops(array $params = [])
    {
        try {
            $response = Http::timeout($this->timeout)
                ->retry($this->retryTimes, 100, throw: false)
                ->post($this->baseUrl . config('ml.endpoints.top_crops'), $params);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $response->json(),
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Top crops request failed',
                'status_code' => $response->status(),
            ];
        } catch (\Throwable $e) {
            $this->safeLogError('ML API Top Crops Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error getting top crops',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get forecast for a specific crop and municipality
     */
    public function getForecast(array $params)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->retry($this->retryTimes, 100, throw: false)
                ->post($this->baseUrl . config('ml.endpoints.forecast'), $params);

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
            ];
        } catch (\Throwable $e) {
            $this->safeLogError('ML API Forecast Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error getting forecast',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get dataset statistics
     */
    public function getDatasetStats()
    {
        try {
            $response = Http::timeout($this->timeout)
                ->retry($this->retryTimes, 100, throw: false)
                ->get($this->baseUrl . config('ml.endpoints.dataset_stats'));

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $response->json(),
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Dataset stats request failed',
                'status_code' => $response->status(),
            ];
        } catch (\Throwable $e) {
            $this->safeLogError('ML API Dataset Stats Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error getting dataset stats',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get model information
     */
    public function getModelInfo()
    {
        try {
            $response = Http::timeout($this->timeout)
                ->retry($this->retryTimes, 100, throw: false)
                ->get($this->baseUrl . config('ml.endpoints.model_info'));

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $response->json(),
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Model info request failed',
                'status_code' => $response->status(),
            ];
        } catch (\Throwable $e) {
            $this->safeLogError('ML API Model Info Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error getting model info',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Batch predict multiple records
     */
    public function batchPredict(array $records)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->retry($this->retryTimes, 100, throw: false)
                ->post($this->baseUrl . config('ml.endpoints.batch_predict'), ['records' => $records]);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $response->json(),
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Batch prediction failed',
                'status_code' => $response->status(),
            ];
        } catch (\Throwable $e) {
            $this->safeLogError('ML API Batch Predict Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error making batch prediction',
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
