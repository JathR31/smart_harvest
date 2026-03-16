<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * SMSService - Wrapper service that delegates to SMSApiPhilippinesService
 * 
 * This service maintains backward compatibility while using SMS API Philippines
 * as the actual SMS provider. Used by MessageController and other parts of the app.
 */
class SMSService
{
    protected $smsApiService;
    
    public function __construct()
    {
        $this->smsApiService = app(SMSApiPhilippinesService::class);
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
     * @param string $priority - Priority level (normal, high, urgent) - used as sender name
     * @return array - ['success' => bool, 'message' => string, 'sent' => int, 'failed' => int]
     */
    public function sendAnnouncement($phoneNumbers, $message, $priority = 'normal')
    {
        return $this->smsApiService->sendAnnouncement($phoneNumbers, $message, 'SmartHarvest');
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
        return $this->smsApiService->testConnection();
    }
}
