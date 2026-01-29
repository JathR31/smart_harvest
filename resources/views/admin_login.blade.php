<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartHarvest - Admin Login</title>
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
            <!-- SmartHarvest Admin Logo with Seedling -->
            <div class="inline-block p-3 mb-3 rounded-full bg-gradient-to-br from-green-50 to-blue-50">
                <div class="flex items-center space-x-1">
                    <span class="text-2xl">🌱</span>
                    <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
            </div>
            <h1 class="text-2xl font-semibold text-green-700 mb-1">SmartHarvest Admin</h1>
            <p class="text-gray-500 mb-8 text-sm">Sign in to access the admin dashboard</p>
        </div>

        <!-- NOTE: In a real app, this form would post to a separate admin login controller/route -->

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.attempt') }}">
            @csrf
            
            <!-- Email Address Field -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <div class="relative">
                    <input id="email" type="email" name="email" required autofocus
                           class="w-full p-3 pl-10 border border-gray-200 rounded-xl bg-gray-50 focus:ring-green-500 focus:border-green-500 transition duration-150"
                           placeholder="admin@smartharvest.com">
                    <!-- Icon for Email -->
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 00-5 0V12m4-9H8a2 2 0 00-2 2v14a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2z"></path></svg>
                </div>
            </div>

            <!-- Password Field -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="w-full p-3 pl-10 border border-gray-200 rounded-xl bg-gray-50 focus:ring-green-500 focus:border-green-500 transition duration-150"
                           value="••••••••">
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

        <!-- Superadmin Login Link -->
        <div class="text-center mt-4">
            <a href="{{ route('superadmin.login') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition duration-150">
                🛡️ Super Admin Login
            </a>
        </div>

        <!-- Demo Credentials -->
        <div class="mt-6 pt-4 border-t border-gray-100 text-sm text-gray-500 text-left">
            <p class="font-semibold mb-1">Demo Credentials:</p>
            <p>Email: <span class="text-gray-700">admin@smartharvest.com</span></p>
            <p>Password: <span class="text-gray-700">any password</span></p>
        </div>
    </div>
</body>
</html>