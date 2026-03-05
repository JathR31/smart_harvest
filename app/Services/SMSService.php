<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * SMSService - Wrapper service that delegates to SMSApiPhilippinesService
 * 
 * This service maintains backward compatibility while using SMS API Philippines
 * as the actual SMS provider.
 */
class SMSService
{
    protected $smsApiService;
    
    public function __construct()
    {
        $this->smsApiService = new SMSApiPhilippinesService();
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
        return $this->smsApiService->sendOTP($phoneNumber, $otpCode);
    }
    
    /**
     * Send announcement to multiple recipients via SMS
     * 
     * @param array $phoneNumbers - Array of phone numbers
     * @param string $message - Announcement message
     * @param string $priority - Priority level (normal, high, urgent) - converted to sender name
     * @return array - ['success' => bool, 'message' => string, 'sent' => int, 'failed' => int]
     */
    public function sendAnnouncement($phoneNumbers, $message, $priority = 'normal')
    {
        // SMS API Philippines uses sender name instead of priority
        // We'll use SmartHarvest as the sender name
        $senderName = 'SmartHarvest';
        
        return $this->smsApiService->sendAnnouncement($phoneNumbers, $message, $senderName);
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
        return $this->smsApiService->sendMessage($phoneNumber, $message, $senderName);
    }
    
    /**
     * Check SMS balance/credits
     * 
     * @return array - ['success' => bool, 'balance' => float|null, 'message' => string]
     */
    public function checkBalance()
    {
        return $this->smsApiService->checkBalance();
    }
    
    /**
     * Validate Philippine mobile number format
     * 
     * @param string $phoneNumber
     * @return bool
     */
    public function isValidPhoneNumber($phoneNumber)
    {
        return $this->smsApiService->isValidPhoneNumber($phoneNumber);
    }
    
    /**
     * Check SMS service health/configuration
     * 
     * @return array
     */
    public function checkHealth()
    {
        $apiKey = env('SMS_API_PHILIPPINES_KEY');
        
        if (!$apiKey) {
            return [
                'status' => 'error',
                'message' => 'SMS_API_PHILIPPINES_KEY not configured'
            ];
        }
        
        return [
            'status' => 'ok',
            'message' => 'SMS service configured',
            'provider' => 'SMS API Philippines'
        ];
    }
}
