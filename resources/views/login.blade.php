<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SmartHarvest - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/translation.js') }}"></script>
    <style>
        /* Custom light green background to match the image */
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }
        /* Custom card shadow for depth */
        .login-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body class="antialiased flex items-center justify-center min-h-screen" x-data="{ languageOpen: false }">

    <div class="login-card w-full max-w-sm p-8 bg-white rounded-2xl">
        <div class="text-center">
            <!-- SmartHarvest Seedling Logo -->
            <div class="inline-block p-3 mb-3 rounded-full bg-gradient-to-br from-green-50 to-green-100">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                </svg>
                <div class="text-3xl" style="margin-top: -8px;">🌱</div>
            </div>
            <h1 class="text-2xl font-semibold text-green-700 mb-1">SmartHarvest</h1>
            <p class="text-gray-500 mb-2 text-sm" data-translate data-translate-id="login-subtitle">Sign in to access your dashboard</p>
            <p class="text-xs text-gray-400 mb-6" data-translate data-translate-id="login-for-all">For both Farmers and Admin</p>
        </div>

        <!-- Language Selector -->
        <div class="mb-4">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
                        <span class="text-sm font-medium text-gray-700" data-translate data-translate-id="language">Language</span>
                    </div>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute top-full left-0 mt-2 w-full bg-white border rounded-lg shadow-lg z-10" style="display: none;">
                    <button onclick="SmartHarvestTranslation.changeLanguage('en')" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm">English</button>
                    <button onclick="SmartHarvestTranslation.changeLanguage('tl')" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm">Tagalog</button>
                    <button onclick="SmartHarvestTranslation.changeLanguage('ilo')" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm">Ilocano</button>
                    <button onclick="SmartHarvestTranslation.changeLanguage('pam')" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm">Kapampangan</button>
                    <button onclick="SmartHarvestTranslation.changeLanguage('ceb')" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm">Cebuano</button>
                    <button onclick="SmartHarvestTranslation.changeLanguage('kan')" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm">Kankanaey</button>
                    <button onclick="SmartHarvestTranslation.changeLanguage('ibl')" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm">Ibaloi</button>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf
            
            <!-- Email / Username Field -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2" data-translate data-translate-id="email-or-phone">Email or Phone Number:</label>
                <div class="relative">
                    <input id="email" type="text" name="email" required autofocus
                           class="w-full p-3 pl-10 border border-gray-200 rounded-xl bg-gray-50 focus:ring-green-500 focus:border-green-500 transition duration-150"
                           placeholder="farmer@example.com or +639XXXXXXXXX" value="{{ old('email') }}">
                    <!-- Icon for Username/Email -->
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <p class="text-xs text-gray-500 mt-1 pl-1" data-translate data-translate-id="email-phone-help">You can use your email or phone number to sign in</p>
            </div>

            <!-- Password Field -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2" data-translate data-translate-id="password">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="w-full p-3 pl-10 border border-gray-200 rounded-xl bg-gray-50 focus:ring-green-500 focus:border-green-500 transition duration-150"
                           placeholder="••••••••">
                    <!-- Icon for Password -->
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-6h12m-6-6V7a2 2 0 00-2-2H8a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2z"></path></svg>
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900" data-translate data-translate-id="remember-me">Remember me</label>
                </div>
                <a href="#" class="text-sm font-medium text-green-600 hover:text-green-500 transition duration-150" data-translate data-translate-id="forgot-password">Forgot password?</a>
            </div>

            <!-- Sign In Button -->
            <button type="submit" class="w-full py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition duration-300 shadow-md shadow-green-200" data-translate data-translate-id="sign-in">
                Sign In
            </button>
        </form>
        
        <!-- Back to Main Site -->
        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-green-600 transition duration-150" data-translate data-translate-id="back-to-main">
                &mdash; Back to main site
            </a>
        </div>


    </div>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            SmartHarvestTranslation.init();
        });
    </script>
</body>
</html>