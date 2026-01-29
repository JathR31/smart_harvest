<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SmartHarvest - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/translation.js') }}"></script>
    <style>
        /* Custom light green background to match the image */
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }
        /* Custom card shadow for depth */
        .register-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        .input-icon {
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>
<body class="antialiased flex items-center justify-center py-10 min-h-screen" x-data="{ open: false }">

    <div class="register-card w-full max-w-xl p-8 bg-white rounded-2xl">
        <div class="flex justify-between items-start mb-6">
            <!-- Logo/Title -->
            <div class="flex flex-col items-start">
                <div class="flex items-center space-x-2">
                    <div class="inline-block p-2 rounded-full bg-gradient-to-br from-green-50 to-green-100">
                        <span class="text-2xl">🌱</span>
                    </div>
                    <span class="text-xl font-semibold text-green-700">SmartHarvest</span>
                </div>
                <h1 class="mt-4 text-base font-medium text-green-700" data-translate data-translate-id="welcome-smartharvest">Welcome SmartHarvest</h1>
                <p class="text-sm font-light text-green-500 flex items-center mt-1">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span data-translate data-translate-id="tagline">Grow Smarter, Harvest Better</span>
                </p>
            </div>

            <!-- Language Dropdown -->
            <div class="relative">
                <button @click="open = !open" class="flex items-center px-3 py-1 border rounded bg-white hover:bg-gray-50 transition duration-150 text-gray-700 text-sm">
                    <span data-translate data-translate-id="language">Language</span>
                    <svg class="ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 py-1 w-32 bg-white rounded-md shadow-xl text-gray-800 text-sm z-10">
                    <button onclick="SmartHarvestTranslation.changeLanguage('en')" class="block w-full text-left px-3 py-1.5 hover:bg-gray-100">English</button>
                    <button onclick="SmartHarvestTranslation.changeLanguage('tl')" class="block w-full text-left px-3 py-1.5 hover:bg-gray-100">Tagalog</button>
                    <button onclick="SmartHarvestTranslation.changeLanguage('ilo')" class="block w-full text-left px-3 py-1.5 hover:bg-gray-100">Ilocano</button>
                    <button onclick="SmartHarvestTranslation.changeLanguage('pam')" class="block w-full text-left px-3 py-1.5 hover:bg-gray-100">Kapampangan</button>
                    <button onclick="SmartHarvestTranslation.changeLanguage('ceb')" class="block w-full text-left px-3 py-1.5 hover:bg-gray-100">Cebuano</button>
                    <button onclick="SmartHarvestTranslation.changeLanguage('kan')" class="block w-full text-left px-3 py-1.5 hover:bg-gray-100">Kankanaey</button>
                    <button onclick="SmartHarvestTranslation.changeLanguage('ibl')" class="block w-full text-left px-3 py-1.5 hover:bg-gray-100">Ibaloi</button>
                </div>
            </div>
        </div>

        <p class="text-gray-600 mb-4 text-center sm:text-left" data-translate data-translate-id="register-intro">
            Join thousands of farmers in the Cordillera region using data-driven insights to optimize their planting schedules and maximize yields.
        </p>
        <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-700">
            <strong data-translate data-translate-id="note">Note:</strong> <span data-translate data-translate-id="password-note">You'll set your password after verifying your email address.</span>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.attempt') }}">
            @csrf
            
            <!-- Input Fields Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                
                <!-- Full Name -->
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2" data-translate data-translate-id="full-name">Full Name</label>
                    <div class="relative">
                        <input id="full_name" type="text" name="full_name" required autofocus
                               class="w-full p-3 pl-10 border border-gray-200 rounded-xl bg-gray-50 focus:ring-green-500 focus:border-green-500 transition duration-150"
                               placeholder="Juan Dela Cruz" value="{{ old('full_name') }}">
                        <svg class="absolute input-icon w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2" id="email_label">
                        Email <span class="text-xs text-gray-500" id="email_optional_text">(Required for email verification)</span>
                    </label>
                    <div class="relative">
                        <input id="email" type="email" name="email" required
                               class="w-full p-3 pl-10 border border-gray-200 rounded-xl bg-gray-50 focus:ring-green-500 focus:border-green-500 transition duration-150"
                               placeholder="farmer@example.com" value="{{ old('email') }}">
                        <svg class="absolute input-icon w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 00-5 0V12m4-9H8a2 2 0 00-2 2v14a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2z"></path></svg>
                    </div>
                    <div class="mt-2" id="no_email_checkbox" style="display: none;">
                        <label class="flex items-center text-sm text-gray-600">
                            <input type="checkbox" id="no_email" class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500 mr-2">
                            I don't have an email account
                        </label>
                    </div>
                </div>

                <!-- Phone Number (Optional) -->
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Mobile Number <span class="text-xs text-gray-500">(Optional for SMS verification)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute input-icon flex items-center text-gray-600 text-sm font-medium">+63</div>
                        <input id="phone_number" type="text" name="phone_number" 
                               class="w-full p-3 pl-14 border border-gray-200 rounded-xl bg-gray-50 focus:ring-green-500 focus:border-green-500 transition duration-150"
                               placeholder="9XX XXX XXXX" maxlength="10" pattern="[9][0-9]{9}" value="{{ old('phone_number') }}">
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 pl-1">Philippine mobile number starting with 9</p>
                </div>

                <!-- Municipality / City -->
                <div class="md:col-span-2">
                    <label for="municipality" class="block text-sm font-medium text-gray-700 mb-2" data-translate data-translate-id="municipality">Municipality / City</label>
                    <div class="relative">
                        <select id="municipality" name="municipality" required
                                class="w-full p-3 pl-10 border border-gray-200 rounded-xl bg-gray-50 appearance-none focus:ring-green-500 focus:border-green-500 transition duration-150 text-gray-700">
                            <option value="" disabled selected data-translate data-translate-id="select-municipality">Select your municipality</option>
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
                        <svg class="absolute input-icon w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243m-1.06-1.06L2.9 10.93a1.997 1.997 0 01-.007-2.834L7.14 3.793a1.997 1.997 0 012.828.007L13.414 7.9a1.998 1.998 0 010 2.828l-4.243 4.243z"></path></svg>
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 pl-1">Select your farm location in the Benguet Province</p>
                </div>

            </div>
            
            <!-- Verification Method -->
            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-3" data-translate data-translate-id="verification-method">Preferred Verification Method</label>
                <div class="space-y-2">
                    <label class="flex items-center p-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-150">
                        <input type="radio" name="verification_method" value="email" checked class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                        <div class="ml-3 flex-1">
                            <span class="text-sm font-medium text-gray-900" data-translate data-translate-id="email-verification">Email Verification</span>
                            <p class="text-xs text-gray-500" data-translate data-translate-id="email-verification-desc">We'll send a verification link to your email</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </label>
                    
                    <label class="flex items-center p-3 bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-150">
                        <input type="radio" name="verification_method" value="sms" id="sms_radio" class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                        <div class="ml-3 flex-1">
                            <span class="text-sm font-medium text-gray-900" data-translate data-translate-id="sms-verification">SMS Verification</span>
                            <p class="text-xs text-gray-500" data-translate data-translate-id="sms-verification-desc">We'll send an OTP code to your mobile number</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </label>
                </div>
                <p class="text-xs text-gray-500 mt-2">💡 Choose SMS if you have limited email access</p>
            </div>
            
            <!-- Terms Checkbox -->
            <div class="mt-6">
                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        <span data-translate data-translate-id="agree-to">I agree to SmartHarvest's</span> 
                        <a href="#" class="text-green-600 hover:text-green-500 font-medium" data-translate data-translate-id="terms-of-service">Terms of Service</a> 
                        <span data-translate data-translate-id="and">and</span> 
                        <a href="#" class="text-green-600 hover:text-green-500 font-medium" data-translate data-translate-id="privacy-policy">Privacy Policy</a>
                    </label>
                </div>
            </div>

            <!-- Create Account Button -->
            <button type="submit" class="w-full mt-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition duration-300 shadow-md shadow-green-200" data-translate data-translate-id="create-account">
                Create Account
            </button>
        </form>

        <!-- Login / Back to Main Site -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-500 mb-2">
                <span data-translate data-translate-id="already-have-account">Already have an account?</span> 
                <a href="{{ route('login') }}" class="text-green-600 hover:text-green-500 font-medium" data-translate data-translate-id="login">Login</a>
            </p>
            <a href="{{ url('/') }}" class="text-xs text-gray-400 hover:text-green-500 transition duration-150" data-translate data-translate-id="back-to-main">
                &mdash; Back to main site
            </a>
        </div>
        
        <!-- What You'll Get Section -->
        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">What you'll get:</h3>
            <div class="flex justify-around items-start space-x-4">
                @php
                    $benefits = [
                        ['icon' => 'plant', 'title' => 'Optimized Planting Schedules'],
                        ['icon' => 'chart', 'title' => 'Yield Analysis & Insights'],
                        ['icon' => 'sun', 'title' => 'Real-time Weather Data'],
                    ];
                @endphp
                @foreach ($benefits as $benefit)
                    <div class="flex flex-col items-center w-1/3">
                        <div class="w-10 h-10 mb-2 flex items-center justify-center rounded-full bg-yellow-50 text-yellow-500">
                             <!-- Icon Placeholder -->
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                        </div>
                        <p class="text-xs font-medium text-gray-700">{{ $benefit['title'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Phone number and SMS verification validation
        document.addEventListener('DOMContentLoaded', function() {
            const smsRadio = document.getElementById('sms_radio');
            const emailRadio = document.querySelector('input[name="verification_method"][value="email"]');
            const phoneInput = document.getElementById('phone_number');
            const emailInput = document.getElementById('email');
            const noEmailCheckbox = document.getElementById('no_email');
            const noEmailCheckboxContainer = document.getElementById('no_email_checkbox');
            const emailLabel = document.getElementById('email_label');
            const emailOptionalText = document.getElementById('email_optional_text');
            const form = document.querySelector('form');
            
            // Make phone required when SMS is selected
            smsRadio.addEventListener('change', function() {
                if (this.checked) {
                    phoneInput.required = true;
                    phoneInput.parentElement.parentElement.querySelector('label').innerHTML = 
                        'Mobile Number <span class="text-red-500">*</span>';
                    
                    // Make email optional and show 'no email' checkbox
                    emailInput.required = false;
                    emailOptionalText.textContent = '(Optional - check below if you don\'t have email)';
                    noEmailCheckboxContainer.style.display = 'block';
                }
            });
            
            // Remove required when email is selected
            emailRadio.addEventListener('change', function() {
                if (this.checked) {
                    phoneInput.required = false;
                    phoneInput.parentElement.parentElement.querySelector('label').innerHTML = 
                        'Mobile Number <span class="text-xs text-gray-500">(Optional for SMS verification)</span>';
                    
                    // Make email required again and hide 'no email' checkbox
                    emailInput.required = true;
                    emailOptionalText.textContent = '(Required for email verification)';
                    noEmailCheckboxContainer.style.display = 'none';
                    noEmailCheckbox.checked = false;
                    emailInput.disabled = false;
                    emailInput.value = '';
                }
            });
            
            // Handle 'no email' checkbox
            noEmailCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    emailInput.value = 'N/A';
                    emailInput.disabled = true;
                    emailInput.required = false;
                } else {
                    emailInput.value = '';
                    emailInput.disabled = false;
                    emailInput.required = false;
                }
            });
            
            // Validate phone number format
            phoneInput.addEventListener('input', function(e) {
                // Remove non-numeric characters
                let value = e.target.value.replace(/\D/g, '');
                
                // Ensure it starts with 9
                if (value.length > 0 && value[0] !== '9') {
                    value = '9' + value.substring(1);
                }
                
                // Limit to 10 digits
                value = value.substring(0, 10);
                e.target.value = value;
            });
            
            // Form submission validation
            form.addEventListener('submit', function(e) {
                const verificationMethod = document.querySelector('input[name="verification_method"]:checked').value;
                const phoneValue = phoneInput.value;
                
                if (verificationMethod === 'sms') {
                    if (!phoneValue || phoneValue.length !== 10 || phoneValue[0] !== '9') {
                        e.preventDefault();
                        alert('Please enter a valid Philippine mobile number (9XX XXX XXXX) for SMS verification.');
                        phoneInput.focus();
                        return false;
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            SmartHarvestTranslation.init();
        });
    </script>
</body>
</html>