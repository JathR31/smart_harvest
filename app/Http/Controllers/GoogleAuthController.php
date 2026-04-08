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
            
            // Try to find user by email (safest - column always exists)
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Link existing user to Google OAuth (if columns exist)
                try {
                    $updateData = [];
                    
                    // Check if OAuth columns exist in schema
                    if (\Schema::hasColumn('users', 'google_id')) {
                        $updateData['google_id'] = $googleUser->id;
                        $updateData['google_avatar'] = $googleUser->avatar;
                        $updateData['auth_method'] = 'google';
                    }
                    
                    if (!empty($updateData)) {
                        $user->update($updateData);
                    }
                } catch (\Exception $updateError) {
                    \Log::warning('Failed to update user: ' . $updateError->getMessage());
                }
            } else {
                // Create new user from Google data
                $userData = [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => bcrypt(\Illuminate\Support\Str::random(32)),
                ];
                
                // Add standard fields only if they exist
                if (\Schema::hasColumn('users', 'role')) {
                    $userData['role'] = 'Farmer';
                }
                if (\Schema::hasColumn('users', 'status')) {
                    $userData['status'] = 'active';
                }
                if (\Schema::hasColumn('users', 'location')) {
                    $userData['location'] = 'La Trinidad';
                }
                if (\Schema::hasColumn('users', 'email_verified_at')) {
                    $userData['email_verified_at'] = now();
                }
                if (\Schema::hasColumn('users', 'password_set_at')) {
                    $userData['password_set_at'] = now();
                }
                
                // Add OAuth fields only if they exist
                if (\Schema::hasColumn('users', 'google_id')) {
                    $userData['google_id'] = $googleUser->id;
                    $userData['google_avatar'] = $googleUser->avatar;
                    $userData['auth_method'] = 'google';
                }

                $user = User::create($userData);
            }

            // Login user
            Auth::login($user, remember: true);

            // Try to update last login
            try {
                if (\Schema::hasColumn('users', 'last_login')) {
                    $user->update(['last_login' => now()]);
                }
            } catch (\Exception $e) {
                // Silently fail
            }

            // Redirect based on user role
            $role = $user->role ?? 'Farmer';
            if (isset($user->is_superadmin) && $user->is_superadmin) {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'Admin' || $role === 'DA Admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        } catch (Exception $e) {
            \Log::error('Google OAuth callback error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            return redirect('/login')->withErrors(['error' => 'Authentication error']);
        }
    }
}
