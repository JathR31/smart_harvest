<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SmartHarvest - Verify Email</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom light green background to match the image */
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }
        /* Custom card shadow for depth */
        .verify-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body class="antialiased flex items-center justify-center min-h-screen">

    <div class="verify-card w-full max-w-md p-8 bg-white rounded-2xl">
        <div class="text-center">
            <!-- Email Icon -->
            <div class="inline-block p-3 mb-4 rounded-full bg-green-50">
                <svg class="w-12 h-12 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-semibold text-green-700 mb-2">Verify Your Email</h1>
            <p class="text-gray-600 mb-6 text-sm">
                Before getting started, please verify your email address by clicking the link we sent to:
            </p>
            <p class="text-green-700 font-medium mb-6">{{ Auth::user()->email }}</p>
        </div>

        @if (session('message'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                {{ session('message') }}
            </div>
        @endif

        <div class="space-y-4">
            <!-- Continue button (hidden by default, shown when verified) -->
            <div id="continue-section" style="display: none;">
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700 text-center">
                    <strong>✓ Email Verified!</strong>
                </div>
                <a href="/setup-password" 
                   class="block w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition duration-150 text-center">
                    Continue to Password Setup
                </a>
                <div class="pt-4 border-t border-gray-100 mt-4"></div>
            </div>

            <!-- Resend section (shown by default, hidden when verified) -->
            <div id="resend-section">
                <p class="text-sm text-gray-500 text-center">
                    Didn't receive the email?
                </p>

                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition duration-150 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Resend Verification Email
                    </button>
                </form>
            </div>

            <div class="pt-4 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}" onsubmit="sessionStorage.setItem('isLoggedOut','true');">
                    @csrf
                    <button type="submit" 
                            class="w-full py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition duration-150 focus:outline-none">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-6 text-center">
            <p class="text-xs text-gray-400">
                SmartHarvest Provincial Rice Monitoring System
            </p>
        </div>
    </div>

    <script>
        console.log('Verification page loaded');
        
        function showContinueButton() {
            console.log('Showing continue button - email verified!');
            // Hide resend section and show continue button
            document.getElementById('resend-section').style.display = 'none';
            document.getElementById('continue-section').style.display = 'block';
        }

        // Listen for verification events from other tabs using localStorage
        window.addEventListener('storage', function(e) {
            console.log('Storage event detected:', e.key, e.newValue);
            if (e.key === 'email_verified' && e.newValue === 'true') {
                showContinueButton();
            }
        });

        // Check verification status every 1 second by reloading the page
        // The server-side redirect will handle showing the password setup if verified
        let checkCount = 0;
        let checkInterval = setInterval(function() {
            checkCount++;
            console.log('Checking verification... attempt', checkCount);
            
            // Simple check: reload the page which will trigger server-side redirect if verified
            if (checkCount % 3 === 0) {  // Every 3 seconds
                console.log('Reloading page to check verification status');
                window.location.reload();
            }
        }, 1000);
        
        // Listen for localStorage changes from verification link click
        if (localStorage.getItem('email_verified') === 'true') {
            console.log('Found verification flag in localStorage');
            showContinueButton();
            localStorage.removeItem('email_verified');
        }
    </script>

</body>
</html>
