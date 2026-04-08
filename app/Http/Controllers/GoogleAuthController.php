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
            return redirect('/login')->withErrors(['error' => 'Failed to authenticate with Google']);
        }

        // Check if user exists by Google ID
        $user = User::where('google_id', $googleUser->id)->first();

        // If user doesn't exist by Google ID, check by email
        if (!$user) {
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Link existing user to Google OAuth
                $user->update([
                    'google_id' => $googleUser->id,
                    'google_avatar' => $googleUser->avatar,
                    'auth_method' => 'google',
                ]);
            } else {
                // Create new user from Google data
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'google_avatar' => $googleUser->avatar,
                    'role' => 'Farmer',
                    'status' => 'active',
                    'auth_method' => 'google',
                    'email_verified_at' => now(), // Google email is pre-verified
                    'password' => bcrypt(\Illuminate\Support\Str::random(32)), // Random password for security
                ]);
            }
        } else {
            // Update existing user's Google data
            $user->update([
                'google_avatar' => $googleUser->avatar,
                'auth_method' => 'google',
            ]);
        }

        // Login user
        Auth::login($user, remember: true);

        // Update last login
        $user->update(['last_login' => now()]);

        // Redirect based on user role
        if ($user->is_superadmin || $user->role === 'Admin' || $user->role === 'DA Admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('dashboard');
        }
    }
}
