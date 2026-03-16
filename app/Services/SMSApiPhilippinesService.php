<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * SMSApiPhilippinesService - SMS service using SMS API Philippines
 * 
 * API Documentation: https://smsapi.ph/docs
 * Dashboard: https://smsapi.ph/dashboard
 * 
 * This service sends real SMS messages through the SMS API Philippines provider.
 * It includes SSL fallback, retry logic, and comprehensive error handling.
 */
class SMSApiPhilippinesService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.smsapi.ph/v1';
    protected $fallbackUrl = 'http://api.smsapi.ph/v1';
    protected $simulationMode;
    
    public function __construct()
    {
        $this->apiKey = config('services.sms_api_philippines.key', env('SMS_API_PHILIPPINES_KEY'));
        
        // Handle 'false' string from .env properly
        $simMode = config('services.sms_api_philippines.simulation_mode', env('SMS_SIMULATION_MODE', false));
        $this->simulationMode = filter_var($simMode, FILTER_VALIDATE_BOOLEAN);
    }
    
    /**
     * Send OTP code via SMS
     * 
     * @param string $phoneNumber - Phone number in format +639XXXXXXXXX or 09XXXXXXXXX
     * @param string $otpCode - 6-digit OTP code
     * @return array - ['success' => bool, 'message' => string]
     */
    public function sendOTP($phoneNumber, $otpCode)
    {
        // Simulation mode - bypass actual API call
        if ($this->simulationMode) {
            Log::info('[SIMULATION] SMS OTP would be sent', [
                'phone' => $this->maskPhoneNumber($phoneNumber),
                'otp' => $otpCode
            ]);
            
            return [
                'success' => true,
                'message' => '[SIMULATION] OTP code: ' . $otpCode . ' — sent to ' . $this->maskPhoneNumber($phoneNumber),
                'simulation' => true,
                'otp_code' => $otpCode
            ];
        }
        
        if (!$this->apiKey) {
            Log::error('SMS Service: SMS_API_PHILIPPINES_KEY not configured');
            return [
                'success' => false,
                'message' => 'SMS service not configured. Please contact administrator.'
            ];
        }
        
        // Normalize phone number to Philippine format
        $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);
        
        if (!$normalizedPhone) {
            return [
                'success' => false,
                'message' => 'Invalid phone number format. Please use Philippine mobile number (09XX XXX XXXX).'
            ];
        }
        
        // Rate limiting - max 3 SMS per phone per 10 minutes
        $cacheKey = 'sms_limit_' . md5($normalizedPhone);
        $attempts = Cache::get($cacheKey, 0);
        
        if ($attempts >= 3) {
            return [
                'success' => false,
                'message' => 'Too many SMS requests. Please wait 10 minutes before trying again.'
            ];
        }
        
        // Compose SMS message
        $message = "SmartHarvest Verification\n\nYour OTP code is: {$otpCode}\n\nThis code will expire in 10 minutes. Do not share this code with anyone.";
        
        $result = $this->sendSMS($normalizedPhone, $message, 'SmartHarvest');
        
        if ($result['success']) {
            // Increment rate limit counter
            Cache::put($cacheKey, $attempts + 1, now()->addMinutes(10));
            
            return [
                'success' => true,
                'message' => 'OTP code sent successfully to ' . $this->maskPhoneNumber($normalizedPhone),
                'data' => $result['data'] ?? null
            ];
        }
        
        return $result;
    }
    
    /**
     * Send announcement message to multiple recipients
     * 
     * @param array $phoneNumbers - Array of phone numbers
     * @param string $message - Announcement message
     * @param string $senderName - Optional sender name (default: SmartHarvest)
     * @return array - ['success' => bool, 'sent' => int, 'failed' => int, 'details' => array]
     */
    public function sendAnnouncement($phoneNumbers, $message, $senderName = 'SmartHarvest')
    {
        // Simulation mode - skip actual API calls
        if ($this->simulationMode) {
            $validCount = 0;
            $details = [];
            foreach ($phoneNumbers as $phone) {
                if ($this->isValidPhoneNumber($phone)) {
                    $validCount++;
                    $details[] = [
                        'phone' => $this->maskPhoneNumber($phone),
                        'status' => 'simulated',
                    ];
                    Log::info('[SIMULATION] Announcement SMS would be sent', [
                        'phone' => $this->maskPhoneNumber($phone),
                        'message' => substr($message, 0, 50) . '...'
                    ]);
                }
            }
            
            return [
                'success' => true,
                'sent' => $validCount,
                'failed' => count($phoneNumbers) - $validCount,
                'message' => sprintf('[SIMULATION] Would send to %d recipient(s), %d invalid.', $validCount, count($phoneNumbers) - $validCount),
                'simulation' => true,
                'details' => $details
            ];
        }
        
        if (!$this->apiKey) {
            return [
                'success' => false,
                'message' => 'SMS service not configured.',
                'sent' => 0,
                'failed' => count($phoneNumbers),
                'details' => []
            ];
        }
        
        $results = [
            'success' => true,
            'sent' => 0,
            'failed' => 0,
            'details' => []
        ];
        
        foreach ($phoneNumbers as $phoneNumber) {
            $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);
            
            if (!$normalizedPhone) {
                $results['failed']++;
                $results['details'][] = [
                    'phone' => $phoneNumber,
                    'status' => 'failed',
                    'error' => 'Invalid phone number format'
                ];
                continue;
            }
            
            $sendResult = $this->sendSMS($normalizedPhone, $message, $senderName);
            
            if ($sendResult['success']) {
                $results['sent']++;
                $results['details'][] = [
                    'phone' => $this->maskPhoneNumber($normalizedPhone),
                    'status' => 'sent',
                    'response' => $sendResult['data'] ?? null
                ];
                
                Log::info('Announcement SMS sent', [
                    'phone' => $this->maskPhoneNumber($normalizedPhone),
                    'sender' => $senderName
                ]);
            } else {
                $results['failed']++;
                $results['details'][] = [
                    'phone' => $this->maskPhoneNumber($normalizedPhone),
                    'status' => 'failed',
                    'error' => $sendResult['message'] ?? 'Unknown error'
                ];
                
                Log::error('Failed to send announcement SMS', [
                    'phone' => $this->maskPhoneNumber($normalizedPhone),
                    'error' => $sendResult['message'] ?? 'Unknown error'
                ]);
            }
            
            // Small delay to avoid rate limiting
            usleep(200000); // 0.2 second delay
        }
        
        if ($results['failed'] > 0 && $results['sent'] === 0) {
            $results['success'] = false;
        }
        
        $results['message'] = sprintf(
            'Successfully sent to %d recipient(s), %d failed.',
            $results['sent'],
            $results['failed']
        );
        
        return $results;
    }
    
    /**
     * Send custom SMS message
     * 
     * @param string $phoneNumber - Phone number
     * @param string $message - Message to send
     * @param string $senderName - Sender name (default: SmartHarvest)
     * @return array - ['success' => bool, 'message' => string]
     */
    public function sendMessage($phoneNumber, $message, $senderName = 'SmartHarvest')
    {
        // Simulation mode
        if ($this->simulationMode) {
            Log::info('[SIMULATION] SMS would be sent', [
                'phone' => $this->maskPhoneNumber($phoneNumber),
                'message' => substr($message, 0, 50) . '...',
                'sender' => $senderName
            ]);
            
            return [
                'success' => true,
                'message' => '[SIMULATION] SMS sent successfully to ' . $this->maskPhoneNumber($phoneNumber),
                'simulation' => true
            ];
        }
        
        if (!$this->apiKey) {
            return [
                'success' => false,
                'message' => 'SMS service not configured.'
            ];
        }
        
        $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);
        
        if (!$normalizedPhone) {
            return [
                'success' => false,
                'message' => 'Invalid phone number format.'
            ];
        }
        
        return $this->sendSMS($normalizedPhone, $message, $senderName);
    }
    
    /**
     * Core SMS sending method with retry and SSL fallback
     * 
     * @param string $phoneNumber - Normalized phone number (+639XXXXXXXXX)
     * @param string $message - SMS message content
     * @param string $senderName - Sender display name
     * @return array - ['success' => bool, 'message' => string, 'data' => array|null]
     */
    protected function sendSMS($phoneNumber, $message, $senderName = 'SmartHarvest')
    {
        $payload = [
            'recipient' => $phoneNumber,
            'message' => $message,
            'sender_name' => $senderName
        ];
        
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'SmartHarvest/1.0'
        ];
        
        // Try multiple URL patterns with SSL fallback
        $urlsToTry = [
            $this->baseUrl . '/send',
            $this->fallbackUrl . '/send',
            'https://smsapi.ph/api/v1/send',
        ];
        
        $lastError = null;
        
        foreach ($urlsToTry as $url) {
            $result = $this->attemptSend($url, $payload, $headers);
            
            if ($result['success']) {
                return $result;
            }
            
            $lastError = $result;
            
            // Only continue to next URL if it was a connection/SSL/response error
            $errorType = $result['error_type'] ?? 'unknown';
            if (!in_array($errorType, ['ssl', 'connect', 'timeout', 'invalid_response'])) {
                // API responded with valid JSON error — don't try alternate URLs
                return $result;
            }
        }
        
        // All attempts failed
        Log::error('SMS API: All send attempts failed', [
            'phone' => $this->maskPhoneNumber($phoneNumber),
            'last_error' => $lastError['message'] ?? 'Unknown'
        ]);
        
        return [
            'success' => false,
            'message' => 'Failed to send SMS. The SMS service may be temporarily unavailable. Please try again later or use email verification.',
            'error_type' => 'all_attempts_failed'
        ];
    }
    
    /**
     * Attempt to send SMS to a specific URL
     * 
     * @param string $url - Full API endpoint URL
     * @param array $payload - Request body
     * @param array $headers - Request headers
     * @return array
     */
    protected function attemptSend($url, $payload, $headers)
    {
        try {
            $response = Http::withoutVerifying()
                ->withHeaders($headers)
                ->timeout(20)
                ->connectTimeout(10)
                ->retry(2, 1000)
                ->post($url, $payload);
            
            // Check content type — API must return JSON, not HTML
            $contentType = $response->header('Content-Type') ?? '';
            $body = $response->body();
            
            // If response is HTML or a redirect page, this is not a valid API response
            if (str_contains($contentType, 'text/html') || str_starts_with(trim($body), '<html') || str_starts_with(trim($body), '<!DOCTYPE')) {
                Log::warning('SMS API returned HTML instead of JSON', [
                    'url' => $url,
                    'status' => $response->status(),
                    'content_type' => $contentType,
                    'body_preview' => substr($body, 0, 200)
                ]);
                
                return [
                    'success' => false,
                    'message' => 'SMS API endpoint returned an invalid response. The service may be down or the URL may be incorrect.',
                    'error_type' => 'invalid_response'
                ];
            }
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Verify we got actual JSON data back
                if ($data === null && !empty($body)) {
                    Log::warning('SMS API response was not valid JSON', [
                        'url' => $url,
                        'body_preview' => substr($body, 0, 200)
                    ]);
                    
                    return [
                        'success' => false,
                        'message' => 'SMS API returned an unexpected response format.',
                        'error_type' => 'invalid_response'
                    ];
                }
                
                Log::info('SMS sent successfully', [
                    'url' => $url,
                    'phone' => $this->maskPhoneNumber($payload['recipient']),
                    'response_status' => $response->status()
                ]);
                
                return [
                    'success' => true,
                    'message' => 'SMS sent successfully to ' . $this->maskPhoneNumber($payload['recipient']),
                    'data' => $data
                ];
            }
            
            // API responded but with error status
            $errorJson = $response->json() ?? [];
            
            Log::error('SMS API error response', [
                'url' => $url,
                'status' => $response->status(),
                'body' => substr($body, 0, 500)
            ]);
            
            return [
                'success' => false,
                'message' => $errorJson['message'] ?? $errorJson['error'] ?? 'SMS API returned error (HTTP ' . $response->status() . ')',
                'error_type' => 'api_error',
                'status' => $response->status()
            ];
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $errorMsg = $e->getMessage();
            $errorType = 'connect';
            
            $lowerMsg = strtolower($errorMsg);
            if (str_contains($lowerMsg, 'ssl') || str_contains($lowerMsg, 'certificate') || str_contains($lowerMsg, 'tls')) {
                $errorType = 'ssl';
            } elseif (str_contains($lowerMsg, 'timeout') || str_contains($lowerMsg, 'timed out')) {
                $errorType = 'timeout';
            }
            
            Log::warning('SMS API connection error', [
                'url' => $url,
                'error' => substr($errorMsg, 0, 200),
                'type' => $errorType
            ]);
            
            return [
                'success' => false,
                'message' => 'Could not connect to SMS service. Please try again.',
                'error_type' => $errorType
            ];
            
        } catch (\Exception $e) {
            Log::error('SMS Service Exception', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'SMS service temporarily unavailable.',
                'error_type' => 'exception'
            ];
        }
    }
    
    /**
     * Check SMS balance/credits
     * 
     * @return array - ['success' => bool, 'balance' => float|null, 'message' => string]
     */
    public function checkBalance()
    {
        if ($this->simulationMode) {
            return [
                'success' => true,
                'balance' => 999.00,
                'message' => '[SIMULATION] Balance check — simulated credits.',
                'simulation' => true
            ];
        }
        
        if (!$this->apiKey) {
            return [
                'success' => false,
                'balance' => null,
                'message' => 'SMS service not configured. Add SMS_API_PHILIPPINES_KEY to your .env file.'
            ];
        }
        
        // Try multiple URL patterns
        $urls = [
            $this->baseUrl . '/balance',
            $this->fallbackUrl . '/balance',
            'https://smsapi.ph/api/v1/balance',
        ];
        
        foreach ($urls as $url) {
            try {
                $response = Http::withoutVerifying()
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Accept' => 'application/json',
                        'User-Agent' => 'SmartHarvest/1.0'
                    ])
                    ->timeout(15)
                    ->connectTimeout(8)
                    ->get($url);
                
                // Skip HTML responses (bounce pages, etc.)
                $contentType = $response->header('Content-Type') ?? '';
                $body = $response->body();
                if (str_contains($contentType, 'text/html') || str_starts_with(trim($body), '<html') || str_starts_with(trim($body), '<!DOCTYPE')) {
                    Log::debug('SMS balance endpoint returned HTML, skipping: ' . $url);
                    continue;
                }
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if ($data === null) {
                        continue; // Not valid JSON
                    }
                    
                    return [
                        'success' => true,
                        'balance' => $data['balance'] ?? $data['credits'] ?? null,
                        'message' => 'Balance retrieved successfully.',
                        'data' => $data
                    ];
                }
            } catch (\Exception $e) {
                Log::debug('SMS balance check failed for URL: ' . $url, ['error' => $e->getMessage()]);
                continue;
            }
        }
        
        return [
            'success' => false,
            'balance' => null,
            'message' => 'Could not retrieve SMS balance. Service may be temporarily unavailable.'
        ];
    }
    
    /**
     * Test the API connection
     * 
     * @return array - ['connected' => bool, 'message' => string]
     */
    public function testConnection()
    {
        if ($this->simulationMode) {
            return [
                'connected' => true,
                'message' => 'Running in simulation mode. SMS will be logged but not actually sent.',
                'mode' => 'simulation',
                'api_key_configured' => !empty($this->apiKey)
            ];
        }
        
        if (!$this->apiKey) {
            return [
                'connected' => false,
                'message' => 'API key not configured. Set SMS_API_PHILIPPINES_KEY in .env',
                'mode' => 'unconfigured'
            ];
        }
        
        $balance = $this->checkBalance();
        
        return [
            'connected' => $balance['success'],
            'message' => $balance['success'] 
                ? 'Connected to SMS API Philippines successfully.' 
                : 'Could not connect to SMS API. Error: ' . $balance['message'],
            'mode' => 'live',
            'api_key_configured' => true,
            'balance' => $balance['balance'] ?? null
        ];
    }
    
    /**
     * Get service status for display
     * 
     * @return array
     */
    public function getStatus()
    {
        return [
            'provider' => 'SMS API Philippines',
            'api_key_set' => !empty($this->apiKey),
            'api_key_preview' => $this->apiKey ? substr($this->apiKey, 0, 8) . '...' : 'Not set',
            'simulation_mode' => $this->simulationMode,
            'base_url' => $this->baseUrl,
        ];
    }
    
    /**
     * Normalize Philippine phone number to +639XXXXXXXXX format
     * 
     * @param string $phoneNumber
     * @return string|false
     */
    public function normalizePhoneNumber($phoneNumber)
    {
        if (!$phoneNumber) return false;
        
        // Remove all non-numeric characters except leading +
        $cleaned = preg_replace('/[^0-9+]/', '', trim($phoneNumber));
        // Strip + sign for uniform handling
        $cleaned = ltrim($cleaned, '+');
        
        // Handle different formats
        if (preg_match('/^09\d{9}$/', $cleaned)) {
            // 09XXXXXXXXX -> +639XXXXXXXXX
            return '+63' . substr($cleaned, 1);
        } elseif (preg_match('/^639\d{9}$/', $cleaned)) {
            // 639XXXXXXXXX -> +639XXXXXXXXX
            return '+' . $cleaned;
        } elseif (preg_match('/^9\d{9}$/', $cleaned)) {
            // 9XXXXXXXXX -> +639XXXXXXXXX
            return '+63' . $cleaned;
        }
        
        // Invalid format
        return false;
    }
    
    /**
     * Mask phone number for display (e.g., +639XX XXX X234)
     * 
     * @param string $phoneNumber
     * @return string
     */
    public function maskPhoneNumber($phoneNumber)
    {
        if (!$phoneNumber || strlen($phoneNumber) < 4) {
            return 'XXX XXX XXXX';
        }
        
        $lastFour = substr($phoneNumber, -4);
        return '+639XX XXX ' . substr($lastFour, -3);
    }
    
    /**
     * Validate Philippine mobile number format
     * 
     * @param string $phoneNumber
     * @return bool
     */
    public function isValidPhoneNumber($phoneNumber)
    {
        return $this->normalizePhoneNumber($phoneNumber) !== false;
    }
}
