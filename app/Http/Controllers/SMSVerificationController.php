<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SMSApiPhilippinesService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SMSVerificationController extends Controller
{
    protected $smsService;
    
    public function __construct(SMSApiPhilippinesService $smsService)
    {
        $this->smsService = $smsService;
    }
    
    /**
     * Show SMS verification page
     */
    public function show()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        if ($user->hasVerifiedPhone()) {
            return redirect()->route('home');
        }
        
        return view('auth.verify-sms', [
            'phone' => $user->phone_number ?? $user->phone,
            'maskedPhone' => $this->maskPhoneNumber($user->phone_number ?? $user->phone)
        ]);
    }
    
    /**
     * Send OTP code via SMS
     */
    public function sendOTP(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }
        
        // Check if already verified
        if ($user->hasVerifiedPhone()) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number already verified.'
            ]);
        }
        
        // Check if phone number exists
        $phoneNumber = $user->phone_number ?? $user->phone;
        
        if (!$phoneNumber) {
            return response()->json([
                'success' => false,
                'message' => 'No phone number found. Please update your profile.'
            ]);
        }
        
        // Check rate limiting for OTP generation
        if ($user->otp_expires_at && now()->lt($user->otp_expires_at)) {
            $remainingTime = now()->diffInSeconds($user->otp_expires_at);
            
            if ($remainingTime > 540) { // More than 9 minutes (out of 10)
                return response()->json([
                    'success' => false,
                    'message' => 'Please wait before requesting a new OTP code.',
                    'remainingTime' => $remainingTime
                ]);
            }
        }
        
        // Generate 6-digit OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Save OTP to user record
        $user->otp_code = $otpCode;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->otp_attempts = 0;
        $user->verification_method = 'sms';
        $user->save();
        
        // Send OTP via SMS
        $result = $this->smsService->sendOTP($phoneNumber, $otpCode);
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'expiresAt' => $user->otp_expires_at->toIso8601String()
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => $result['message']
        ]);
    }
    
    /**
     * Verify OTP code
     */
    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|size:6'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid 6-digit OTP code.',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }
        
        // Check if already verified
        if ($user->hasVerifiedPhone()) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number already verified.'
            ]);
        }
        
        // Check if OTP exists
        if (!$user->otp_code) {
            return response()->json([
                'success' => false,
                'message' => 'No OTP code found. Please request a new code.'
            ]);
        }
        
        // Check if OTP expired
        if (now()->gt($user->otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP code has expired. Please request a new code.',
                'expired' => true
            ]);
        }
        
        // Check attempts limit
        if ($user->otp_attempts >= 5) {
            // Clear OTP after too many attempts
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->otp_attempts = 0;
            $user->save();
            
            return response()->json([
                'success' => false,
                'message' => 'Too many failed attempts. Please request a new OTP code.',
                'tooManyAttempts' => true
            ]);
        }
        
        // Verify OTP
        if ($request->otp === $user->otp_code) {
            // Success - mark phone as verified
            $user->phone_verified_at = now();
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->otp_attempts = 0;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Phone number verified successfully!',
                'redirectUrl' => $this->getRedirectUrl($user)
            ]);
        }
        
        // Incorrect OTP - increment attempts
        $user->otp_attempts += 1;
        $user->save();
        
        $remainingAttempts = 5 - $user->otp_attempts;
        
        return response()->json([
            'success' => false,
            'message' => "Incorrect OTP code. {$remainingAttempts} attempt(s) remaining.",
            'remainingAttempts' => $remainingAttempts
        ]);
    }
    
    /**
     * Resend OTP code
     */
    public function resendOTP(Request $request)
    {
        return $this->sendOTP($request);
    }
    
    /**
     * Switch to email verification
     */
    public function switchToEmail(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }
        
        // Clear SMS OTP data
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->otp_attempts = 0;
        $user->verification_method = 'email';
        $user->save();
        
        // Send email verification
        $user->sendEmailVerificationNotification();
        
        return response()->json([
            'success' => true,
            'message' => 'Switched to email verification. Check your email for verification link.',
            'redirectUrl' => route('verification.notice')
        ]);
    }
    
    /**
     * Get redirect URL based on user role
     */
    protected function getRedirectUrl($user)
    {
        if ($user->role === 'Admin' || $user->role === 'DA Admin') {
            return route('admin.dashboard');
        }
        
        return route('farmer.dashboard');
    }
    
    /**
     * Mask phone number for display
     */
    protected function maskPhoneNumber($phoneNumber)
    {
        if (!$phoneNumber) {
            return 'XXX XXX XXXX';
        }
        
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        if (strlen($cleaned) >= 4) {
            $lastFour = substr($cleaned, -4);
            return '+639XX XXX ' . $lastFour;
        }
        
        return 'XXX XXX XXXX';
    }
}
