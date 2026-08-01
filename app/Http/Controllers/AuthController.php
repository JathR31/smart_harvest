<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        
        return view('admin_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'login_mode' => 'nullable|in:email,rsbsa',
            'password' => 'nullable|string',
        ]);

        $remember = $request->boolean('remember');
        $loginMode = $request->input('login_mode', 'email');
        $loginField = trim((string) $request->input('email'));
        [$fieldType, $normalizedLoginField, $loginDigitsOnly] = $this->normalizeLoginIdentifier($loginField);

        if ($loginMode === 'rsbsa' || $fieldType === 'rsbsa_number') {
            if ($fieldType !== 'rsbsa_number') {
                return back()->withErrors(['email' => 'Please enter a valid RSBSA number for RSBSA login.'])->withInput();
            }

            $rsbsaLookupValues = User::rsbsaLookupValues($loginField);

            $user = User::where('role', 'Farmer')
                ->where(function ($query) use ($rsbsaLookupValues, $loginDigitsOnly) {
                    foreach ($rsbsaLookupValues as $candidate) {
                        $query->orWhere('rsbsa_number', $candidate);
                    }

                    if ($loginDigitsOnly !== '') {
                        $query->orWhereRaw("REPLACE(REPLACE(rsbsa_number, '-', ''), ' ', '') = ?", [$loginDigitsOnly]);
                    }
                })
                ->first();

            if (!$user) {
                return back()->withErrors(['email' => 'RSBSA login failed. Please check your RSBSA number.'])->withInput();
            }

            return $this->completeLogin($request, $user, $remember);
        }

        $user = $this->findLoginAccount($normalizedLoginField);
        if (!$user) {
            return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->withInput($request->only('email'));
        }

        if ($this->requiresPasswordForAccount($user)) {
            if (!$request->filled('password')) {
                return back()->withErrors([
                    'password' => 'Password is required for DA Admin accounts.',
                ])->withInput($request->only('email'));
            }

            if (!Hash::check($request->input('password'), $user->password)) {
                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ])->withInput($request->only('email'));
            }
        }

        return $this->completeLogin($request, $user, $remember);
    }

    public function resolveLoginType(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'login_mode' => 'nullable|in:email,rsbsa',
        ]);

        $loginMode = $request->input('login_mode', 'email');
        $loginField = trim((string) $request->input('identifier'));
        [$fieldType, $normalizedLoginField, $loginDigitsOnly] = $this->normalizeLoginIdentifier($loginField);

        if ($loginMode === 'rsbsa' || $fieldType === 'rsbsa_number') {
            return response()->json([
                'found' => true,
                'login_type' => 'rsbsa',
                'requires_password' => false,
                'role' => 'Farmer',
                'normalized_identifier' => $normalizedLoginField,
            ]);
        }

        $user = $this->findLoginAccount($normalizedLoginField);

        if (!$user) {
            return response()->json([
                'found' => false,
                'login_type' => $fieldType,
                'requires_password' => false,
            ]);
        }

        return response()->json([
            'found' => true,
            'login_type' => $fieldType,
            'requires_password' => $this->requiresPasswordForAccount($user),
            'role' => $user->role,
            'normalized_identifier' => $normalizedLoginField,
            'login_digits_only' => $loginDigitsOnly,
        ]);
    }

    protected function completeLogin(Request $request, User $user, bool $remember = false)
    {
        Auth::login($user, $remember);
        $request->session()->regenerate();

        // Superadmin starts from normal login, then continues to the dedicated 2FA flow.
        if ($user->is_superadmin || $user->admin_type === 'superadmin') {
            Auth::logout();
            $request->session()->put('superadmin_credentials_verified', true);
            $request->session()->put('superadmin_user_id', $user->id);

            if (!$user->google2fa_enabled || !$user->google2fa_secret) {
                $request->session()->put('superadmin_needs_2fa_setup', true);
                return redirect()->route('superadmin.2fa.setup');
            }

            return redirect()->route('superadmin.login');
        }

        $user->update(['last_login' => now()]);

        if ($this->isAdminAccount($user)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    protected function findLoginAccount(string $loginField): ?User
    {
        return User::where('email', $loginField)
            ->orWhere('phone_number', $loginField)
            ->first();
    }

    protected function normalizeLoginIdentifier(string $loginField): array
    {
        $loginField = trim($loginField);
        $loginDigitsOnly = preg_replace('/\D+/', '', $loginField);
        $fieldType = 'email';

        if (preg_match('/^(\+63|0)?9[0-9]{9}$/', $loginField)) {
            $fieldType = 'phone_number';
            $loginField = preg_replace('/^(\+63|0)?/', '+63', $loginField);
        } elseif (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
            $fieldType = 'email';
        } elseif ($loginDigitsOnly !== '' && strlen($loginDigitsOnly) === 13) {
            $fieldType = 'rsbsa_number';
            $loginField = User::normalizeRsbsaNumber($loginField);
        }

        return [$fieldType, $loginField, $loginDigitsOnly];
    }

    protected function requiresPasswordForAccount(User $user): bool
    {
        return $user->role === 'DA Admin' || strtolower((string) $user->admin_type) === 'da_admin';
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginIdentifier = trim($request->input('email'));
        $user = User::where('email', $loginIdentifier)
            ->orWhere('username', $loginIdentifier)
            ->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput($request->only('email'));
        }

        if (!$this->isAdminAccount($user)) {
            return back()->withErrors([
                'email' => 'This account does not have admin access.',
            ])->withInput($request->only('email'));
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectBasedOnRole();
    }

    public function showSignupForm()
    {
        return view('signup');
    }

    public function signup(Request $request)
    {
        $allowedMunicipalities = [
            'Atok', 'Bakun', 'Bokod', 'Buguias', 'Itogon',
            'Kabayan', 'Kapangan', 'Kibungan', 'La Trinidad', 'Mankayan',
            'Sablan', 'Tuba', 'Tublay',
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'municipality' => ['required', 'string', Rule::in($allowedMunicipalities)],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Farmer',
            'municipality' => $request->municipality,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    protected function redirectBasedOnRole()
    {
        $user = Auth::user();

        if ($user->is_superadmin || $user->admin_type === 'superadmin') {
            return redirect()->route('superadmin.login');
        }
        
        if ($this->isAdminAccount($user)) {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('dashboard');
    }

    protected function isAdminAccount(User $user): bool
    {
        return $user->role === 'Admin' || $user->role === 'DA Admin';
    }
}
