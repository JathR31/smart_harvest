<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

class SuperadminAuthController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Show the superadmin login form.
     */
    public function showLoginForm(Request $request)
    {
        // Clear previous session data if starting fresh
        if (!$request->session()->has('superadmin_credentials_verified')) {
            $request->session()->forget(['superadmin_user_id']);
        }

        $showTotpForm = $request->session()->has('superadmin_credentials_verified');

        return view('superadmin_login', compact('showTotpForm'));
    }

    /**
     * Verify superadmin credentials (Step 1).
     */
    public function verifyCredentials(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        // Find user by email
        $user = User::where('email', $email)->first();

        if (!$user) {
            Log::warning('Superadmin login attempt with invalid email', [
                'email' => $email,
                'ip' => $request->ip(),
            ]);
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        // Verify password
        if (!Hash::check($password, $user->password)) {
            Log::warning('Superadmin login attempt with invalid password', [
                'email' => $email,
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);
            return back()->withErrors(['password' => 'Invalid credentials.'])->withInput();
        }

        // Check if user is a superadmin
        if (!$user->is_superadmin && $user->admin_type !== 'superadmin') {
            Log::warning('Non-superadmin user attempted superadmin login', [
                'email' => $email,
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);
            return back()->withErrors(['email' => 'You do not have superadmin privileges.'])->withInput();
        }

        // Check if 2FA is set up
        if (!$user->google2fa_enabled || !$user->google2fa_secret) {
            // 2FA not set up, redirect to setup page
            $request->session()->put('superadmin_user_id', $user->id);
            $request->session()->put('superadmin_needs_2fa_setup', true);
            
            Log::info('Superadmin needs 2FA setup', [
                'user_id' => $user->id,
                'email' => $email,
                'ip' => $request->ip(),
            ]);

            return redirect()->route('superadmin.2fa.setup');
        }

        // Store in session for TOTP verification
        $request->session()->put('superadmin_credentials_verified', true);
        $request->session()->put('superadmin_user_id', $user->id);

        Log::info('Superadmin credentials verified, proceeding to 2FA', [
            'user_id' => $user->id,
            'email' => $email,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('superadmin.login');
    }

    /**
     * Show 2FA setup page.
     */
    public function show2FASetup(Request $request)
    {
        if (!$request->session()->has('superadmin_user_id')) {
            return redirect()->route('superadmin.login')->withErrors(['error' => 'Please verify your credentials first.']);
        }

        $userId = $request->session()->get('superadmin_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('superadmin.login')->withErrors(['error' => 'User not found.']);
        }

        // Generate a new secret if not already stored in session
        $secret = $request->session()->get('superadmin_2fa_temp_secret');
        if (!$secret) {
            $secret = $this->google2fa->generateSecretKey();
            $request->session()->put('superadmin_2fa_temp_secret', $secret);
        }

        // Generate QR code URL
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            'SmartHarvest',
            $user->email,
            $secret
        );

        // Generate inline QR code using bacon/bacon-qr-code
        $qrCode = $this->generateQRCode($qrCodeUrl);

        return view('superadmin_2fa_setup', compact('secret', 'qrCode', 'user'));
    }

    /**
     * Generate QR code as SVG.
     */
    private function generateQRCode($url)
    {
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new \BaconQrCode\Writer($renderer);
        
        return $writer->writeString($url);
    }

    /**
     * Enable 2FA after verification.
     */
    public function enable2FA(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        if (!$request->session()->has('superadmin_user_id') || !$request->session()->has('superadmin_2fa_temp_secret')) {
            return redirect()->route('superadmin.login')->withErrors(['error' => 'Session expired. Please try again.']);
        }

        $userId = $request->session()->get('superadmin_user_id');
        $secret = $request->session()->get('superadmin_2fa_temp_secret');
        $otp = $request->input('otp');

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('superadmin.login')->withErrors(['error' => 'User not found.']);
        }

        // Verify the OTP
        $valid = $this->google2fa->verifyKey($secret, $otp);

        if (!$valid) {
            Log::warning('Invalid OTP during 2FA setup', [
                'user_id' => $userId,
                'ip' => $request->ip(),
            ]);
            return back()->withErrors(['otp' => 'Invalid verification code. Please try again.']);
        }

        // Save the secret and enable 2FA
        $user->google2fa_secret = Crypt::encrypt($secret);
        $user->google2fa_enabled = true;
        $user->save();

        // Clear session data
        $request->session()->forget(['superadmin_2fa_temp_secret', 'superadmin_needs_2fa_setup']);
        $request->session()->put('superadmin_credentials_verified', true);

        Log::info('2FA enabled for superadmin', [
            'user_id' => $userId,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('superadmin.login')->with('success', '2FA has been enabled! Please enter your verification code to continue.');
    }

    /**
     * Verify TOTP code (Step 2).
     */
    public function verifyTotp(Request $request)
    {
        // Ensure credentials were verified
        if (!$request->session()->has('superadmin_credentials_verified')) {
            return redirect()->route('superadmin.login')->withErrors(['error' => 'Please verify your credentials first.']);
        }

        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userId = $request->session()->get('superadmin_user_id');
        $user = User::find($userId);

        if (!$user) {
            return $this->failSecurityCheck($request, 'User not found. Please try again.');
        }

        // Decrypt the secret
        try {
            $secret = Crypt::decrypt($user->google2fa_secret);
        } catch (\Exception $e) {
            Log::error('Failed to decrypt 2FA secret', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
            return $this->failSecurityCheck($request, 'Security verification error. Please contact administrator.');
        }

        // Verify the OTP
        $otp = $request->input('otp');
        $valid = $this->google2fa->verifyKey($secret, $otp);

        if (!$valid) {
            Log::warning('Superadmin 2FA verification failed', [
                'user_id' => $userId,
                'ip' => $request->ip(),
            ]);
            return back()->withErrors(['otp' => 'Invalid verification code. Please try again.']);
        }

        // All verified! Log in the user
        $request->session()->forget(['superadmin_credentials_verified', 'superadmin_user_id']);

        // Log in the user
        Auth::login($user);
        $request->session()->regenerate();

        // Update last login
        $user->last_login = now();
        $user->save();

        Log::info('Superadmin login successful via 2FA', [
            'user_id' => $user->id,
            'username' => $user->username,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Welcome, Super Administrator!');
    }

    /**
     * Handle failed security check.
     */
    private function failSecurityCheck(Request $request, string $message)
    {
        // Clear all security session data
        $request->session()->forget(['superadmin_credentials_verified', 'superadmin_user_id', 'superadmin_2fa_temp_secret']);

        return redirect()->route('superadmin.login')->withErrors(['error' => $message]);
    }

    /**
     * Logout superadmin.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        Log::info('Superadmin logged out', [
            'user_id' => $user->id ?? null,
            'ip' => $request->ip(),
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('superadmin.login')->with('success', 'You have been logged out successfully.');
    }
}
