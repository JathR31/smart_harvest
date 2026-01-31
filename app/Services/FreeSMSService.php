<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * FreeSMSService - Multi-provider SMS service using free/freemium APIs
 * 
 * Providers supported (in order of priority):
 * 1. Semaphore (Philippines) - Best for PH numbers, pay-per-use but affordable
 * 2. TextBelt - 1 free SMS/day for testing, then paid
 * 3. Twilio (Trial) - Free trial credits available
 * 4. Simulation Mode - For development/testing without real SMS
 */
class FreeSMSService
{
    protected $providers = [];
    protected $simulationMode = false;
    
    public function __construct()
    {
        // Check if we should use simulation mode (development)
        $this->simulationMode = env('SMS_SIMULATION_MODE', false) || env('APP_ENV') === 'local';
        
        // Initialize available providers
        $this->initProviders();
    }
    
    /**
     * Initialize SMS providers based on available API keys
     */
    private function initProviders()
    {
        // Semaphore (Best for Philippines)
        if (env('SEMAPHORE_API_KEY')) {
            $this->providers['semaphore'] = [
                'name' => 'Semaphore',
                'url' => 'https://api.semaphore.co/api/v4/messages',
                'api_key' => env('SEMAPHORE_API_KEY'),
                'priority' => 1
            ];
        }
        
        // Twilio (International, free trial available)
        if (env('TWILIO_SID') && env('TWILIO_TOKEN') && env('TWILIO_PHONE')) {
            $this->providers['twilio'] = [
                'name' => 'Twilio',
                'sid' => env('TWILIO_SID'),
                'token' => env('TWILIO_TOKEN'),
                'from' => env('TWILIO_PHONE'),
                'priority' => 2
            ];
        }
        
        // TextBelt (1 free SMS/day, then paid)
        // No API key needed for 1 free SMS per day
        $this->providers['textbelt'] = [
            'name' => 'TextBelt',
            'url' => 'https://textbelt.com/text',
            'api_key' => env('TEXTBELT_API_KEY', 'textbelt'), // 'textbelt' = 1 free/day
            'priority' => 3
        ];
    }
    
    /**
     * Send OTP code via SMS
     * 
     * @param string $phoneNumber - Phone number in format +639XXXXXXXXX or 09XXXXXXXXX
     * @param string $otpCode - 6-digit OTP code
     * @return array - ['success' => bool, 'message' => string, 'provider' => string]
     */
    public function sendOTP($phoneNumber, $otpCode)
    {
        // Normalize phone number
        $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);
        
        if (!$normalizedPhone) {
            return [
                'success' => false,
                'message' => 'Invalid phone number format. Please use Philippine mobile number (09XX XXX XXXX).',
                'provider' => 'none'
            ];
        }
        
        // Simulation mode for development
        if ($this->simulationMode) {
            return $this->simulateSMS($normalizedPhone, $otpCode);
        }
        
        // Rate limiting - max 3 SMS per phone per 10 minutes
        $cacheKey = 'sms_limit_' . md5($normalizedPhone);
        $attempts = Cache::get($cacheKey, 0);
        
        if ($attempts >= 3) {
            return [
                'success' => false,
                'message' => 'Too many SMS requests. Please wait a few minutes before trying again.',
                'provider' => 'rate_limit'
            ];
        }
        
        // Compose message
        $message = "SmartHarvest Verification\n\nYour OTP code is: {$otpCode}\n\nThis code expires in 10 minutes. Do not share this code.";
        
        // Try each provider in order of priority
        foreach ($this->getSortedProviders() as $providerKey => $provider) {
            $result = $this->sendWithProvider($providerKey, $normalizedPhone, $message);
            
            if ($result['success']) {
                // Increment rate limit counter
                Cache::put($cacheKey, $attempts + 1, now()->addMinutes(10));
                
                Log::info('SMS sent successfully', [
                    'provider' => $provider['name'],
                    'phone' => $this->maskPhoneNumber($normalizedPhone)
                ]);
                
                return [
                    'success' => true,
                    'message' => 'OTP code sent successfully to ' . $this->maskPhoneNumber($normalizedPhone),
                    'provider' => $provider['name']
                ];
            }
            
            Log::warning("SMS provider {$provider['name']} failed, trying next", ['error' => $result['message']]);
        }
        
        // All providers failed
        return [
            'success' => false,
            'message' => 'Unable to send SMS. Please try email verification instead.',
            'provider' => 'all_failed'
        ];
    }
    
    /**
     * Send SMS using a specific provider
     */
    private function sendWithProvider($providerKey, $phone, $message)
    {
        switch ($providerKey) {
            case 'semaphore':
                return $this->sendWithSemaphore($phone, $message);
            case 'twilio':
                return $this->sendWithTwilio($phone, $message);
            case 'textbelt':
                return $this->sendWithTextBelt($phone, $message);
            default:
                return ['success' => false, 'message' => 'Unknown provider'];
        }
    }
    
    /**
     * Send SMS via Semaphore (Philippines)
     */
    private function sendWithSemaphore($phone, $message)
    {
        try {
            $response = Http::timeout(15)->post($this->providers['semaphore']['url'], [
                'apikey' => $this->providers['semaphore']['api_key'],
                'number' => $phone,
                'message' => $message,
                'sendername' => 'SmartHarvest'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data[0]['message_id'])) {
                    return ['success' => true, 'message' => 'Sent via Semaphore'];
                }
                
                return ['success' => false, 'message' => $data[0]['message'] ?? 'Unknown error'];
            }
            
            return ['success' => false, 'message' => 'Semaphore API error: ' . $response->status()];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Semaphore exception: ' . $e->getMessage()];
        }
    }
    
    /**
     * Send SMS via Twilio
     */
    private function sendWithTwilio($phone, $message)
    {
        try {
            $sid = $this->providers['twilio']['sid'];
            $token = $this->providers['twilio']['token'];
            $from = $this->providers['twilio']['from'];
            
            // Format phone to E.164
            $toPhone = '+' . ltrim($phone, '+');
            
            $response = Http::withBasicAuth($sid, $token)
                ->timeout(15)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                    'To' => $toPhone,
                    'From' => $from,
                    'Body' => $message
                ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['sid'])) {
                    return ['success' => true, 'message' => 'Sent via Twilio'];
                }
            }
            
            $error = $response->json()['message'] ?? 'Unknown error';
            return ['success' => false, 'message' => 'Twilio error: ' . $error];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Twilio exception: ' . $e->getMessage()];
        }
    }
    
    /**
     * Send SMS via TextBelt (1 free SMS per day)
     */
    private function sendWithTextBelt($phone, $message)
    {
        try {
            // Format phone for TextBelt (needs country code)
            $toPhone = '+' . ltrim($phone, '+');
            
            $response = Http::timeout(15)->post('https://textbelt.com/text', [
                'phone' => $toPhone,
                'message' => $message,
                'key' => $this->providers['textbelt']['api_key']
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['success'] ?? false) {
                    return ['success' => true, 'message' => 'Sent via TextBelt'];
                }
                
                return ['success' => false, 'message' => $data['error'] ?? 'TextBelt quota exceeded'];
            }
            
            return ['success' => false, 'message' => 'TextBelt API error'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'TextBelt exception: ' . $e->getMessage()];
        }
    }
    
    /**
     * Simulate SMS sending for development
     */
    private function simulateSMS($phone, $otpCode)
    {
        Log::info('=== SIMULATED SMS (Development Mode) ===', [
            'to' => $phone,
            'otp_code' => $otpCode,
            'message' => "Your SmartHarvest OTP code is: {$otpCode}"
        ]);
        
        // Store in session for easy testing
        session(['simulated_otp' => $otpCode]);
        
        return [
            'success' => true,
            'message' => 'OTP code sent to ' . $this->maskPhoneNumber($phone) . ' (Simulation Mode - Check logs for OTP)',
            'provider' => 'Simulation',
            'debug_otp' => $otpCode // Only in simulation mode
        ];
    }
    
    /**
     * Get providers sorted by priority
     */
    private function getSortedProviders()
    {
        $providers = $this->providers;
        uasort($providers, function($a, $b) {
            return ($a['priority'] ?? 99) - ($b['priority'] ?? 99);
        });
        return $providers;
    }
    
    /**
     * Normalize phone number to international format
     */
    private function normalizePhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters except leading +
        $cleaned = preg_replace('/[^0-9+]/', '', $phoneNumber);
        $cleaned = ltrim($cleaned, '+');
        
        // Handle Philippine formats
        if (preg_match('/^0?9\d{9}$/', $cleaned)) {
            // 09XXXXXXXXX or 9XXXXXXXXX format
            return '63' . ltrim($cleaned, '0');
        } elseif (preg_match('/^639\d{9}$/', $cleaned)) {
            // 639XXXXXXXXX format
            return $cleaned;
        }
        
        return null;
    }
    
    /**
     * Mask phone number for display
     */
    private function maskPhoneNumber($phoneNumber)
    {
        if (!$phoneNumber) return 'your phone';
        
        $lastFour = substr($phoneNumber, -4);
        return '+63 9XX XXX ' . $lastFour;
    }
    
    /**
     * Check if phone number is valid
     */
    public function isValidPhoneNumber($phoneNumber)
    {
        return $this->normalizePhoneNumber($phoneNumber) !== null;
    }
    
    /**
     * Get available providers status
     */
    public function getProvidersStatus()
    {
        $status = [];
        
        if ($this->simulationMode) {
            $status['simulation'] = [
                'status' => 'active',
                'message' => 'Simulation mode enabled (development)',
                'priority' => 0
            ];
        }
        
        foreach ($this->providers as $key => $provider) {
            $status[$key] = [
                'name' => $provider['name'],
                'status' => 'configured',
                'priority' => $provider['priority']
            ];
        }
        
        return $status;
    }
}
