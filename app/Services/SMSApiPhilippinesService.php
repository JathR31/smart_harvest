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
 */
class SMSApiPhilippinesService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.smsapi.ph/v1';
    protected $simulationMode;
    
    public function __construct()
    {
        $this->apiKey = env('SMS_API_PHILIPPINES_KEY');
        $this->simulationMode = env('SMS_SIMULATION_MODE', false);
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
        // Simulation mode - bypass actual API  call
        if ($this->simulationMode) {
            Log::info('[SIMULATION] SMS OTP would be sent', [
                'phone' => $phoneNumber,
                'otp' => $otpCode
            ]);
            
            return [
                'success' => true,
                'message' => '[SIMULATION MODE] OTP code sent successfully to ' . $this->maskPhoneNumber($phoneNumber),
                'simulation' => true
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
        
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(15)->post($this->baseUrl . '/send', [
                'recipient' => $normalizedPhone,
                'message' => $message,
                'sender_name' => 'SmartHarvest'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Increment rate limit counter
                Cache::put($cacheKey, $attempts + 1, now()->addMinutes(10));
                
                Log::info('SMS sent successfully via SMS API Philippines', [
                    'phone' => $normalizedPhone,
                    'response' => $data
                ]);
                
                return [
                    'success' => true,
                    'message' => 'OTP code sent successfully to ' . $this->maskPhoneNumber($normalizedPhone),
                    'data' => $data
                ];
            }
            
            Log::error('SMS API Philippines request failed', [
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
            foreach ($phoneNumbers as $phone) {
                if ($this->isValidPhoneNumber($phone)) {
                    $validCount++;
                    Log::info('[SIMULATION] Announcement SMS would be sent', [
                        'phone' => $phone,
                        'message' => substr($message, 0, 50) . '...'
                    ]);
                }
            }
            
            return [
                'success' => true,
                'sent' => $validCount,
                'failed' => count($phoneNumbers) - $validCount,
                'message' => sprintf('[SIMULATION MODE] Would send to %d recipient(s), %d invalid.', $validCount, count($phoneNumbers) - $validCount),
                'simulation' => true
            ];
        }
        
        if (!$this->apiKey) {
            return [
                'success' => false,
                'message' => 'SMS service not configured.',
                'sent' => 0,
                'failed' => count($phoneNumbers)
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
            
            try {
                $response = Http::withoutVerifying()->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->timeout(15)->post($this->baseUrl . '/send', [
                    'recipient' => $normalizedPhone,
                    'message' => $message,
                    'sender_name' => $senderName
                ]);
                
                if ($response->successful()) {
                    $results['sent']++;
                    $results['details'][] = [
                        'phone' => $this->maskPhoneNumber($normalizedPhone),
                        'status' => 'sent',
                        'response' => $response->json()
                    ];
                    
                    Log::info('Announcement SMS sent', [
                        'phone' => $normalizedPhone,
                        'sender' => $senderName
                    ]);
                } else {
                    $results['failed']++;
                    $results['details'][] = [
                        'phone' => $this->maskPhoneNumber($normalizedPhone),
                        'status' => 'failed',
                        'error' => $response->body()
                    ];
                    
                    Log::error('Failed to send announcement SMS', [
                        'phone' => $normalizedPhone,
                        'error' => $response->body()
                    ]);
                }
                
                // Small delay to avoid rate limiting
                usleep(100000); // 0.1 second delay
                
            } catch (\Exception $e) {
                $results['failed']++;
                $results['details'][] = [
                    'phone' => $this->maskPhoneNumber($normalizedPhone),
                    'status' => 'failed',
                    'error' => $e->getMessage()
                ];
                
                Log::error('SMS Service Exception for announcement', [
                    'phone' => $normalizedPhone,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        if ($results['failed'] > 0) {
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
                'phone' => $phoneNumber,
                'message' => substr($message, 0, 50) . '...',
                'sender' => $senderName
            ]);
            
            return [
                'success' => true,
                'message' => '[SIMULATION MODE] SMS sent successfully to ' . $this->maskPhoneNumber($phoneNumber),
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
        
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(15)->post($this->baseUrl . '/send', [
                'recipient' => $normalizedPhone,
                'message' => $message,
                'sender_name' => $senderName
            ]);
            
            if ($response->successful()) {
                Log::info('SMS sent successfully', [
                    'phone' => $normalizedPhone,
                    'sender' => $senderName
                ]);
                
                return [
                    'success' => true,
                    'message' => 'SMS sent successfully to ' . $this->maskPhoneNumber($normalizedPhone),
                    'data' => $response->json()
                ];
            }
            
            Log::error('Failed to send SMS', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to send SMS. Please try again.'
            ];
            
        } catch (\Exception $e) {
            Log::error('SMS Service Exception: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'SMS service temporarily unavailable.'
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
        if (!$this->apiKey) {
            return [
                'success' => false,
                'balance' => null,
                'message' => 'SMS service not configured.'
            ];
        }
        
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(10)->get($this->baseUrl . '/balance');
            
            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'balance' => $data['balance'] ?? null,
                    'message' => 'Balance retrieved successfully.',
                    'data' => $data
                ];
            }
            
            return [
                'success' => false,
                'balance' => null,
                'message' => 'Failed to retrieve balance.'
            ];
            
        } catch (\Exception $e) {
            Log::error('Failed to check SMS balance: ' . $e->getMessage());
            
            return [
                'success' => false,
                'balance' => null,
                'message' => 'Service temporarily unavailable.'
            ];
        }
    }
    
    /**
     * Normalize Philippine phone number to +639XXXXXXXXX format
     * 
     * @param string $phoneNumber
     * @return string|false
     */
    protected function normalizePhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        
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
    protected function maskPhoneNumber($phoneNumber)
    {
        if (strlen($phoneNumber) >= 4) {
            $lastFour = substr($phoneNumber, -4);
            return '+639XX XXX X' . substr($lastFour, -3);
        }
        
        return 'XXX XXX XXXX';
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
