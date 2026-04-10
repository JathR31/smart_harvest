<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SmartHarvest - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center min-h-screen py-10">
    <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-lg">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-block p-2 rounded-full bg-green-100 mb-3">
                <span class="text-3xl">🌱</span>
            </div>
            <h1 class="text-2xl font-bold text-green-700 mb-2">SmartHarvest</h1>
            <p class="text-gray-600 text-sm">Create your account today</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-300 rounded-lg">
                <ul class="text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Google OAuth Button -->
        <a href="{{ route('auth.google') }}" class="w-full mb-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg flex items-center justify-center gap-2 transition">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="white" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="white" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="white" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="white" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Sign up with Google
        </a>

        <div class="relative mb-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">or</span>
            </div>
        </div>

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register.attempt') }}" class="space-y-4">
            @csrf

            <!-- Full Name -->
            <div>
                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                <input type="text" id="full_name" name="full_name" required autofocus
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       placeholder="Juan Dela Cruz" value="{{ old('full_name') }}">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       placeholder="your@email.com" value="{{ old('email') }}">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       placeholder="Minimum 8 characters">
                <p class="text-xs text-gray-500 mt-1">At least 8 characters</p>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       placeholder="Confirm password">
            </div>

            <!-- Municipality -->
            <div>
                <label for="municipality" class="block text-sm font-medium text-gray-700 mb-1">Municipality / City *</label>
                <select id="municipality" name="municipality" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Select municipality</option>
                    <option value="Atok">Atok</option>
                    <option value="Baguio City">Baguio City</option>
                    <option value="Bakun">Bakun</option>
                    <option value="Bokod">Bokod</option>
                    <option value="Buguias">Buguias</option>
                    <option value="Itogon">Itogon</option>
                    <option value="Kabayan">Kabayan</option>
                    <option value="Kapangan">Kapangan</option>
                    <option value="Kibungan">Kibungan</option>
                    <option value="La Trinidad">La Trinidad</option>
                    <option value="Mankayan">Mankayan</option>
                    <option value="Sablan">Sablan</option>
                    <option value="Tuba">Tuba</option>
                    <option value="Tublay">Tublay</option>
                </select>
            </div>

            <!-- Phone (Optional) -->
            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number (Optional)</label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 bg-gray-100 text-gray-600 rounded-l-lg border border-gray-300 border-r-0">+63</span>
                    <input type="text" id="phone_number" name="phone_number"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="9XX XXX XXXX" maxlength="10" pattern="[9][0-9]{9}">
                </div>
            </div>

            <!-- Terms -->
            <div class="flex items-center">
                <input type="checkbox" id="terms" name="terms" required class="h-4 w-4 text-green-600 rounded">
                <label for="terms" class="ml-2 text-sm text-gray-700">
                    I agree to the <a href="{{ route('terms-of-service') }}" target="_blank" class="text-green-600 hover:underline">Terms of Service</a> and <a href="{{ route('privacy-policy') }}" target="_blank" class="text-green-600 hover:underline">Privacy Policy</a>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                Create Account
            </button>
        </form>

        <!-- Login Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-green-600 hover:underline font-medium">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>