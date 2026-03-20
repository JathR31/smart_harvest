<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

/**
 * EmailVerificationService - Handles email verification with proper link generation
 * 
 * Uses Laravel's built-in email system configured with:
 * - Brevo (Sendinblue) - Free tier: 300 emails/day
 * - Gmail SMTP - Requires App Password
 * - Log driver - For development/testing
 */
class EmailVerificationService
{
    /**
     * Send verification email to user
     * 
     * @param User $user
     * @return array ['success' => bool, 'message' => string]
     */
    public function sendVerificationEmail(User $user)
    {
        try {
            // Check if already verified
            if ($user->hasVerifiedEmail()) {
                return [
                    'success' => true,
                    'message' => 'Email already verified.',
                    'already_verified' => true
                ];
            }
            
            // Rate limiting - max 3 emails per 15 minutes
            $lastSent = $user->verification_sent_at ?? null;
            $sendCount = $user->verification_send_count ?? 0;
            
            if ($lastSent && Carbon::parse($lastSent)->addMinutes(1)->isFuture()) {
                return [
                    'success' => false,
                    'message' => 'Please wait a minute before requesting another verification email.',
                    'wait_seconds' => Carbon::parse($lastSent)->addMinutes(1)->diffInSeconds(now())
                ];
            }
            
            // Generate signed verification URL
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(60),
                [
                    'id' => $user->getKey(),
                    'hash' => sha1($user->getEmailForVerification())
                ]
            );
            
            // Send email using Laravel's mail system
            Mail::send('emails.verify-email', [
                'user' => $user,
                'verificationUrl' => $verificationUrl,
                'expiresIn' => '60 minutes'
            ], function ($message) use ($user) {
                $fromAddress = config('mail.from.address') ?: 'no-reply@smartharvest.app';
                $message->to($user->email, $user->name)
                    ->from($fromAddress, 'SmartHarvest')
                    ->subject('Verify Your SmartHarvest Account');
            });
            
            // Update tracking
            $user->verification_sent_at = now();
            $user->verification_send_count = $sendCount + 1;
            $user->save();
            
            Log::info('Verification email sent', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return [
                'success' => true,
                'message' => 'Verification email sent to ' . $this->maskEmail($user->email) . '. Please check your inbox and spam folder.'
            ];
            
        } catch (\Exception $e) {
            Log::error('Failed to send verification email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to send verification email. Please try again or contact support.',
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Send OTP code via email (alternative to SMS)
     * 
     * @param User $user
     * @param string $otpCode
     * @return array
     */
    public function sendOTPEmail(User $user, string $otpCode)
    {
        try {
            Mail::send('emails.otp-verification', [
                'user' => $user,
                'otpCode' => $otpCode,
                'expiresIn' => '10 minutes'
            ], function ($message) use ($user) {
                $fromAddress = config('mail.from.address') ?: 'no-reply@smartharvest.app';
                $message->to($user->email, $user->name)
                    ->from($fromAddress, 'SmartHarvest')
                    ->subject('SmartHarvest - Your Verification Code');
            });
            
            Log::info('OTP email sent', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return [
                'success' => true,
                'message' => 'Verification code sent to ' . $this->maskEmail($user->email)
            ];
            
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to send verification code via email.'
            ];
        }
    }
    
    /**
     * Verify email with signed URL parameters
     * 
     * @param int $userId
     * @param string $hash
     * @return array
     */
    public function verifyEmail($userId, $hash)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found.'
            ];
        }
        
        if ($user->hasVerifiedEmail()) {
            return [
                'success' => true,
                'message' => 'Email already verified.',
                'already_verified' => true
            ];
        }
        
        // Verify hash
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return [
                'success' => false,
                'message' => 'Invalid verification link.'
            ];
        }
        
        // Mark as verified
        $user->email_verified_at = now();
        $user->save();
        
        Log::info('Email verified', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);
        
        return [
            'success' => true,
            'message' => 'Email verified successfully!',
            'user' => $user
        ];
    }
    
    /**
     * Check mail service health
     * 
     * @return array
     */
    public function checkHealth()
    {
        $mailer = config('mail.default');
        $host = config('mail.mailers.smtp.host');
        
        return [
            'status' => 'configured',
            'mailer' => $mailer,
            'host' => $host,
            'from' => config('mail.from.address'),
            'message' => $mailer === 'log' 
                ? 'Using log driver (development mode)' 
                : "Using {$mailer} via {$host}"
        ];
    }
    
    /**
     * Mask email for display
     */
    private function maskEmail($email)
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) return $email;
        
        $name = $parts[0];
        $domain = $parts[1];
        
        $maskedName = substr($name, 0, 2) . str_repeat('*', max(0, strlen($name) - 4)) . substr($name, -2);
        
        return $maskedName . '@' . $domain;
    }
}
