<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SmartHarvest - Setup 2FA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
        }
        .setup-card {
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }
        .otp-input {
            letter-spacing: 0.5em;
            font-family: monospace;
        }
        .qr-container svg {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body class="antialiased flex items-center justify-center min-h-screen p-4">

    <div class="setup-card w-full max-w-lg p-8 bg-white rounded-2xl">
        <div class="text-center">
            <!-- Setup Icon -->
            <div class="inline-block p-4 mb-4 rounded-full bg-gradient-to-br from-green-100 to-blue-100">
                <div class="flex items-center space-x-2">
                    <span class="text-3xl">📱</span>
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Set Up Two-Factor Authentication</h1>
            <p class="text-gray-500 mb-6 text-sm">Secure your account with an authenticator app</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600 text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Instructions -->
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
            <h3 class="font-semibold text-blue-800 mb-2 text-sm">📋 Setup Instructions:</h3>
            <ol class="text-xs text-blue-700 space-y-1 list-decimal list-inside">
                <li>Download an authenticator app (Google Authenticator, Microsoft Authenticator, Authy, etc.)</li>
                <li>Scan the QR code below with your authenticator app</li>
                <li>Enter the 6-digit code from the app to verify setup</li>
            </ol>
        </div>

        <!-- QR Code -->
        <div class="mb-6 text-center">
            <div class="inline-block p-4 bg-white border-2 border-gray-200 rounded-xl qr-container">
                {!! $qrCode !!}
            </div>
            <p class="text-xs text-gray-500 mt-2">Scan this QR code with your authenticator app</p>
        </div>

        <!-- Manual Entry -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <p class="text-xs text-gray-600 mb-2 text-center">Can't scan? Enter this code manually:</p>
            <div class="flex items-center justify-center space-x-2">
                <code class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-mono text-sm tracking-wider select-all">
                    {{ $secret }}
                </code>
                <button type="button" onclick="copySecret()" class="p-2 text-gray-500 hover:text-indigo-600 transition" title="Copy to clipboard">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </button>
            </div>
            <p class="text-xs text-gray-500 mt-2 text-center">Account: {{ $user->email }}</p>
        </div>

        <!-- Verify Form -->
        <form method="POST" action="{{ route('superadmin.2fa.enable') }}" id="verifyForm">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2 text-center">
                    Enter the 6-digit code from your authenticator app
                </label>
                <div class="flex justify-center">
                    <input type="text" name="otp" required autofocus
                           maxlength="6" 
                           pattern="[0-9]{6}"
                           inputmode="numeric"
                           class="otp-input w-48 p-4 text-2xl text-center border border-gray-200 rounded-xl bg-gray-50 focus:ring-green-500 focus:border-green-500 transition duration-150"
                           placeholder="000000">
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition duration-300 shadow-lg shadow-green-200">
                ✓ Verify & Enable 2FA
            </button>
        </form>

        <!-- Back Link -->
        <div class="text-center mt-6 pt-4 border-t border-gray-100">
            <a href="{{ route('superadmin.login') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition duration-150">
                ← Cancel and go back
            </a>
        </div>

        <!-- Security Notice -->
        <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-100">
            <div class="flex items-start space-x-2">
                <span class="text-yellow-500 mt-0.5">⚠️</span>
                <div>
                    <p class="text-xs text-yellow-800 font-medium">Important</p>
                    <p class="text-xs text-yellow-700 mt-1">Save this secret key in a secure location. If you lose access to your authenticator app, you'll need this key to recover your account.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-format OTP input
        document.addEventListener('DOMContentLoaded', function() {
            const otpInput = document.querySelector('input[name="otp"]');
            if (otpInput) {
                otpInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        });

        // Copy secret to clipboard
        function copySecret() {
            const secret = '{{ $secret }}';
            navigator.clipboard.writeText(secret).then(function() {
                alert('Secret key copied to clipboard!');
            }).catch(function() {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = secret;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('Secret key copied to clipboard!');
            });
        }
    </script>
</body>
</html>
