<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.semaphore.co/api/v4/messages';
    
    public function __construct()
    {
        $this->apiKey = env('SEMAPHORE_API_KEY');
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
        if (!$this->apiKey) {
            Log::error('SMS Service: SEMAPHORE_API_KEY not configured');
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
        
        // Compose SMS message
        $message = "SmartHarvest Verification\n\nYour OTP code is: {$otpCode}\n\nThis code will expire in 10 minutes. Do not share this code with anyone.";
        
        try {
            $response = Http::timeout(10)->post($this->baseUrl, [
                'apikey' => $this->apiKey,
                'number' => $normalizedPhone,
                'message' => $message,
                'sendername' => 'SmartHarvest'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Semaphore API returns array with message_id on success
                if (isset($data[0]['message_id'])) {
                    Log::info('SMS sent successfully', [
                        'phone' => $normalizedPhone,
                        'message_id' => $data[0]['message_id']
                    ]);
                    
                    return [
                        'success' => true,
                        'message' => 'OTP code sent successfully to ' . $this->maskPhoneNumber($normalizedPhone),
                        'message_id' => $data[0]['message_id']
                    ];
                }
                
                // Handle API error responses
                $errorMessage = $data[0]['message'] ?? 'Unknown error from SMS provider';
                Log::error('SMS API error', ['response' => $data]);
                
                return [
                    'success' => false,
                    'message' => 'Failed to send SMS: ' . $errorMessage
                ];
            }
            
            Log::error('SMS API request failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to send SMS. Please try again or use email verification.'
            ];
            
        } catch (\Exception $e) {
            Log::error('SMS Service Exception: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'SMS service temporarily unavailable. Please try email verification.'
            ];
        }
    }
    
    /**
     * Normalize phone number to Philippine format (+639XXXXXXXXX)
     * Accepts: 09XXXXXXXXX, 9XXXXXXXXX, +639XXXXXXXXX, 639XXXXXXXXX
     * 
     * @param string $phoneNumber
     * @return string|null - Returns normalized number or null if invalid
     */
    private function normalizePhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters except leading +
        $cleaned = preg_replace('/[^0-9+]/', '', $phoneNumber);
        
        // Remove + if present
        $cleaned = ltrim($cleaned, '+');
        
        // Handle different formats
        if (preg_match('/^0?9\d{9}$/', $cleaned)) {
            // 09XXXXXXXXX or 9XXXXXXXXX format
            return '63' . ltrim($cleaned, '0');
        } elseif (preg_match('/^639\d{9}$/', $cleaned)) {
            // 639XXXXXXXXX format (already correct)
            return $cleaned;
        }
        
        // Invalid format
        return null;
    }
    
    /**
     * Mask phone number for display (e.g., +639XX XXX X123)
     * 
     * @param string $phoneNumber
     * @return string
     */
    private function maskPhoneNumber($phoneNumber)
    {
        $normalized = $this->normalizePhoneNumber($phoneNumber);
        
        if (!$normalized) {
            return 'your phone';
        }
        
        // Format: +639XX XXX X123
        $countryCode = substr($normalized, 0, 2);
        $prefix = substr($normalized, 2, 1);
        $lastThree = substr($normalized, -3);
        
        return "+{$countryCode}{$prefix}XX XXX X{$lastThree}";
    }
    
    /**
     * Validate Philippine mobile number format
     * 
     * @param string $phoneNumber
     * @return bool
     */
    public function isValidPhoneNumber($phoneNumber)
    {
        return $this->normalizePhoneNumber($phoneNumber) !== null;
    }
    
    /**
     * Check SMS service health/configuration
     * 
     * @return array
     */
    public function checkHealth()
    {
        if (!$this->apiKey) {
            return [
                'status' => 'error',
                'message' => 'SEMAPHORE_API_KEY not configured'
            ];
        }
        
        return [
            'status' => 'ok',
            'message' => 'SMS service configured',
            'provider' => 'Semaphore'
        ];
    }
}
