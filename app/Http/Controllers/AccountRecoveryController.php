<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AccountRecoveryController extends Controller
{
    /**
     * Reset a user's password for testing purposes
     */
    public function resetUserPassword(Request $request)
    {
        $email = $request->input('email');
        $newPassword = $request->input('password', 'password123'); // Default test password
        
        if (!$email) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email is required',
                'example' => '/api/reset-password?email=user@example.com&password=newpassword123'
            ], 400);
        }

        try {
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found with email: ' . $email,
                ], 404);
            }

            // Reset password
            $user->password = Hash::make($newPassword);
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset successfully',
                'email' => $email,
                'new_password' => $newPassword,
                'note' => 'Now try logging in with this password'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to reset password',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * List all users in database
     */
    public function listUsers()
    {
        try {
            $users = User::select('id', 'email', 'name', 'role', 'created_at')->get();

            return response()->json([
                'status' => 'success',
                'total_users' => $users->count(),
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to list users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a test user
     */
    public function createTestUser(Request $request)
    {
        $email = $request->input('email', 'test@example.com');
        $password = $request->input('password', 'password123');
        $name = $request->input('name', 'Test User');

        try {
            // Check if user exists
            $exists = User::where('email', $email)->exists();
            if ($exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User already exists'
                ], 409);
            }

            // Create user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'farmer',
                'email_verified_at' => now(), // Pre-verified for testing
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Test user created',
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'password_for_login' => $password
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
