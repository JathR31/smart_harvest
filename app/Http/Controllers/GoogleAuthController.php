<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google for authentication
     */
    public function redirect()
    {
        // Check if Google OAuth is properly configured
        if (!env('GOOGLE_CLIENT_ID') || !env('GOOGLE_CLIENT_SECRET') || !env('GOOGLE_REDIRECT_URI')) {
            return redirect('/login')->withErrors(['error' => 'Google OAuth is not configured. Please use email registration instead.']);
        }
        
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            \Log::error('Google OAuth error: ' . $e->getMessage());
            return redirect('/login')->withErrors(['error' => 'Failed to authenticate with Google']);
        }

        try {
            // Try to find user, but handle case where columns don't exist yet
            $user = null;
            
            try {
                // Check if user exists by Google ID
                $user = User::where('google_id', $googleUser->id)->first();
            } catch (\Exception $queryError) {
                \Log::warning('Google ID query failed: ' . $queryError->getMessage());
            }

            // If user doesn't exist by Google ID, check by email
            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // Link existing user to Google OAuth
                    try {
                        $user->update([
                            'google_id' => $googleUser->id,
                            'google_avatar' => $googleUser->avatar,
                            'auth_method' => 'google',
                        ]);
                    } catch (\Exception $updateError) {
                        \Log::error('Failed to update user with Google data: ' . $updateError->getMessage());
                        // Continue anyway - user will just be logged in
                    }
                } else {
                    // Create new user from Google data
                    $userData = [
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'role' => 'Farmer',
                        'status' => 'active',
                        'location' => 'La Trinidad', // Default location
                        'email_verified_at' => now(), // Google email is pre-verified
                        'password_set_at' => now(), // Password set via Google OAuth
                        'password' => bcrypt(\Illuminate\Support\Str::random(32)), // Random password for security
                    ];
                    
                    // Try to add OAuth fields
                    try {
                        $userData['google_id'] = $googleUser->id;
                        $userData['google_avatar'] = $googleUser->avatar;
                        $userData['auth_method'] = 'google';
                    } catch (\Exception $e) {
                        // OAuth fields might not exist yet
                        \Log::warning('Could not add OAuth fields: ' . $e->getMessage());
                    }

                    $user = User::create($userData);
                }
            } else {
                // Update existing user's Google data
                try {
                    $user->update([
                        'google_avatar' => $googleUser->avatar,
                        'auth_method' => 'google',
                    ]);
                } catch (\Exception $updateError) {
                    \Log::warning('Failed to update Google avatar: ' . $updateError->getMessage());
                }
            }

            // Login user
            Auth::login($user, remember: true);

            // Update last login
            try {
                $user->update(['last_login' => now()]);
            } catch (\Exception $e) {
                \Log::warning('Could not update last login: ' . $e->getMessage());
            }

            // Redirect based on user role
            if ($user->is_superadmin || $user->role === 'Admin' || $user->role === 'DA Admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        } catch (Exception $e) {
            \Log::error('Google OAuth callback error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            return redirect('/login')->withErrors(['error' => 'An error occurred during authentication']);
        }
    }
}
