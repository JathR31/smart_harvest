<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Notifications\CustomVerifyEmail;
use App\Models\User;

class EmailTestController extends Controller
{
    /**
     * Test email sending
     */
    public function testEmail()
    {
        $testEmail = request('email', 'test@example.com');
        
        try {
            // Test basic mail
            Mail::raw('This is a test email from SmartHarvest.', function ($message) use ($testEmail) {
                $message->to($testEmail)
                        ->subject('SmartHarvest Test Email');
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Test email sent to ' . $testEmail,
                'mail_config' => [
                    'driver' => config('mail.mailer'),
                    'from_address' => config('mail.from.address'),
                    'from_name' => config('mail.from.name'),
                    'host' => config('mail.host'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send email',
                'error' => $e->getMessage(),
                'mail_config' => [
                    'driver' => config('mail.mailer'),
                    'from_address' => config('mail.from.address'),
                    'from_name' => config('mail.from.name'),
                    'host' => config('mail.host'),
                ]
            ], 500);
        }
    }

    /**
     * Test verification email
     */
    public function testVerificationEmail()
    {
        $email = request('email', 'test@example.com');
        
        try {
            // Create a temporary test user
            $testUser = new User();
            $testUser->email = $email;
            $testUser->name = 'Test User';
            
            // Send verification email
            $testUser->notify(new CustomVerifyEmail());

            return response()->json([
                'status' => 'success',
                'message' => 'Verification email sent to ' . $email,
                'note' => 'Check your email (including spam folder) for the verification link'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send verification email',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
