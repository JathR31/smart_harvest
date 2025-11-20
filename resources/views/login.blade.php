<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartHarvest - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
<body class="antialiased flex items-center justify-center min-h-screen">

    <div class="login-card w-full max-w-sm p-8 bg-white rounded-2xl">
        <div class="text-center">
            <!-- SmartHarvest Logo/Icon Placeholder -->
            <div class="inline-block p-2 mb-3 rounded-full bg-green-50">
                <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4m16 0c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8z"/></svg>
            </div>
            <h1 class="text-2xl font-semibold text-green-700 mb-1">SmartHarvest</h1>
            <p class="text-gray-500 mb-2 text-sm">Sign in to access your dashboard</p>
            <p class="text-xs text-gray-400 mb-6">For both Farmers and Admin</p>
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
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email / Username</label>
                <div class="relative">
                    <input id="email" type="text" name="email" required autofocus
                           class="w-full p-3 pl-10 border border-gray-200 rounded-xl bg-gray-50 focus:ring-green-500 focus:border-green-500 transition duration-150"
                           placeholder="farmer@example.com" value="{{ old('email') }}">
                    <!-- Icon for Username/Email -->
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>

            <!-- Password Field -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
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
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>
                <a href="#" class="text-sm font-medium text-green-600 hover:text-green-500 transition duration-150">Forgot password?</a>
            </div>

            <!-- Sign In Button -->
            <button type="submit" class="w-full py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition duration-300 shadow-md shadow-green-200">
                Sign In
            </button>
        </form>
        
        <!-- Back to Main Site -->
        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-green-600 transition duration-150">
                &mdash; Back to main site
            </a>
        </div>

        <!-- Demo Credentials -->
        <div class="mt-6 pt-4 border-t border-gray-100 text-sm text-gray-500 text-left">
            <p class="font-semibold mb-1">Demo Credentials:</p>
            <p>Email: <span class="text-gray-700">farmer</span></p>
            <p>Password: <span class="text-gray-700">farmer</span></p>
        </div>
    </div>
</body>
</html>