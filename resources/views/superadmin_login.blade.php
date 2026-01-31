<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SmartHarvest - Super Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
        }
        .login-card {
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }
        .security-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .otp-input {
            letter-spacing: 0.5em;
            font-family: monospace;
        }
    </style>
</head>
<body class="antialiased flex items-center justify-center min-h-screen p-4">

    <div class="login-card w-full max-w-md p-8 bg-white rounded-2xl">
        <div class="text-center">
            <!-- Super Admin Logo -->
            <div class="inline-block p-4 mb-4 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100">
                <div class="flex items-center space-x-2 security-badge">
                    <span class="text-3xl">🛡️</span>
                    <svg class="w-8 h-8 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-indigo-800 mb-1">Super Admin Access</h1>
            <p class="text-gray-500 mb-6 text-sm">High-security login with 2FA authentication</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600 text-center">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600 text-center">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-600 text-center">
                {{ session('success') }}
            </div>
        @endif

        @if (session('info'))
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-600 text-center">
                {{ session('info') }}
            </div>
        @endif

        <!-- Step 1: Credentials -->
        @if (!$showTotpForm)
        <form method="POST" action="{{ route('superadmin.login.verify-credentials') }}" id="credentialsForm">
            @csrf
            
            <div class="mb-4 p-3 bg-indigo-50 rounded-lg border border-indigo-100">
                <p class="text-xs text-indigo-700 font-medium">🔐 Step 1 of 2: Enter your credentials</p>
            </div>

            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <div class="relative">
                    <input id="email" type="email" name="email" required autofocus
                           class="w-full p-3 pl-10 border border-gray-200 rounded-xl bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                           placeholder="Enter your email"
                           value="{{ old('email') }}">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            <!-- Password Field -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required
                           class="w-full p-3 pl-10 border border-gray-200 rounded-xl bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                           placeholder="Enter your password">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition duration-300 shadow-lg shadow-indigo-200">
                Verify Credentials →
            </button>
        </form>

        @else
        <!-- Step 2: TOTP Verification -->
        <form method="POST" action="{{ route('superadmin.login.verify-totp') }}" id="totpForm">
            @csrf
            
            <div class="mb-4 p-3 bg-green-50 rounded-lg border border-green-100">
                <p class="text-xs text-green-700 font-medium">✓ Credentials verified</p>
            </div>
            
            <div class="mb-4 p-3 bg-indigo-50 rounded-lg border border-indigo-100">
                <p class="text-xs text-indigo-700 font-medium">🔐 Step 2 of 2: Enter authenticator code</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2 text-center">
                    Enter the 6-digit code from your authenticator app
                </label>
                <div class="flex justify-center">
                    <input type="text" name="otp" required autofocus
                           maxlength="6" 
                           pattern="[0-9]{6}"
                           inputmode="numeric"
                           class="otp-input w-48 p-4 text-2xl text-center border border-gray-200 rounded-xl bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                           placeholder="000000">
                </div>
                <p class="text-xs text-gray-500 mt-2 text-center">
                    Open Google Authenticator, Microsoft Authenticator, or similar app
                </p>
            </div>

            <button type="submit" class="w-full py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition duration-300 shadow-lg shadow-green-200">
                🔓 Verify & Login
            </button>

            <div class="mt-4 text-center space-y-2">
                <a href="{{ route('superadmin.login') }}" 
                   onclick="event.preventDefault(); document.getElementById('reset-form').submit();"
                   class="text-sm text-gray-500 hover:text-indigo-600 transition duration-150 block">
                    ← Start over
                </a>
                <form id="reset-form" action="{{ route('superadmin.login') }}" method="GET" style="display: none;">
                    @csrf
                </form>
                
                <!-- Reset 2FA Option -->
                <div class="pt-2 border-t border-gray-100">
                    <p class="text-xs text-gray-400 mb-2">Lost access to authenticator app?</p>
                    <button type="button" onclick="document.getElementById('reset-2fa-form').submit();" 
                            class="text-xs text-red-500 hover:text-red-700 transition duration-150">
                        🔄 Reset Two-Factor Authentication
                    </button>
                    <form id="reset-2fa-form" action="{{ route('superadmin.2fa.reset') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </form>
        @endif

        <!-- Back to Main Site -->
        <div class="text-center mt-6 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.login') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition duration-150">
                ← Regular Admin Login
            </a>
            <span class="text-gray-300 mx-2">|</span>
            <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition duration-150">
                Back to main site
            </a>
        </div>

        <!-- Security Notice -->
        <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-100">
            <div class="flex items-start space-x-2">
                <span class="text-yellow-500 mt-0.5">⚠️</span>
                <div>
                    <p class="text-xs text-yellow-800 font-medium">Security Notice</p>
                    <p class="text-xs text-yellow-700 mt-1">This is a restricted area protected by 2-Factor Authentication. All login attempts are logged and monitored.</p>
                </div>
            </div>
        </div>

        <!-- First-time setup notice -->
        @if (!$showTotpForm)
        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
            <div class="flex items-start space-x-2">
                <span class="text-blue-500 mt-0.5">ℹ️</span>
                <div>
                    <p class="text-xs text-blue-800 font-medium">First-time Login?</p>
                    <p class="text-xs text-blue-700 mt-1">After verifying your credentials, you'll be prompted to set up your authenticator app if you haven't already.</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script>
        // Auto-submit when 6 digits are entered
        document.addEventListener('DOMContentLoaded', function() {
            const otpInput = document.querySelector('input[name="otp"]');
            if (otpInput) {
                otpInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length === 6) {
                        // Optional: auto-submit
                        // document.getElementById('totpForm').submit();
                    }
                });
            }
        });
    </script>
</body>
</html>
