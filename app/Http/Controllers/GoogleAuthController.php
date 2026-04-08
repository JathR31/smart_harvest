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
            $user = null;
            
            // Try to find user by email (safer, column always exists)
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Link existing user to Google OAuth (if columns exist)
                try {
                    $updateData = [];
                    
                    // Only add columns if they exist
                    try {
                        \DB::select("SELECT google_id FROM users LIMIT 1");
                        $updateData['google_id'] = $googleUser->id;
                        $updateData['google_avatar'] = $googleUser->avatar;
                        $updateData['auth_method'] = 'google';
                    } catch (\Exception $e) {
                        // Columns don't exist yet, skip
                    }
                    
                    if (!empty($updateData)) {
                        $user->update($updateData);
                    }
                } catch (\Exception $updateError) {
                    \Log::warning('Failed to update user with Google data: ' . $updateError->getMessage());
                }
            } else {
                // Create new user from Google data
                $userData = [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'role' => 'Farmer',
                    'status' => 'active',
                    'location' => 'La Trinidad',
                    'email_verified_at' => now(),
                    'password_set_at' => now(),
                    'password' => bcrypt(\Illuminate\Support\Str::random(32)),
                ];
                
                // Only add OAuth fields in fillable if columns exist
                try {
                    \DB::select("SELECT google_id FROM users WHERE 1=0");
                    $userData['google_id'] = $googleUser->id;
                    $userData['google_avatar'] = $googleUser->avatar;
                    $userData['auth_method'] = 'google';
                } catch (\Exception $e) {
                    // OAuth columns don't exist yet - that's okay, migration will add them
                    \Log::info('OAuth columns not yet in database: ' . $e->getMessage());
                }

                $user = User::create($userData);
            }

            // Login user
            Auth::login($user, remember: true);

            // Try to update last login
            try {
                $user->update(['last_login' => now()]);
            } catch (\Exception $e) {
                // last_login might not exist yet
            }

            // Redirect based on user role
            if (isset($user->is_superadmin) && $user->is_superadmin) {
                return redirect()->route('admin.dashboard');
            } elseif (isset($user->role) && ($user->role === 'Admin' || $user->role === 'DA Admin')) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        } catch (Exception $e) {
            \Log::error('Google OAuth callback error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            return redirect('/login')->withErrors(['error' => 'Authentication error. Try again later.']);
        }
    }
}
