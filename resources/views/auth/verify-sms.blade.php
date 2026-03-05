<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Phone Number - SmartHarvest</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-green-700 mb-2">🌾 SmartHarvest</h1>
            <p class="text-gray-600">Phone Number Verification</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Verify Your Phone</h2>
                <p class="text-gray-600 mt-2">We've sent a 6-digit code to</p>
                <p class="text-green-600 font-semibold">{{ $maskedPhone }}</p>
            </div>

            <!-- Alert Messages -->
            <div id="alert-container" class="mb-6"></div>

            <!-- OTP Input Form -->
            <form id="otp-form" class="space-y-6">
                @csrf
                
                <!-- OTP Input -->
                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-700 mb-2">
                        Enter OTP Code
                    </label>
                    <input 
                        type="text" 
                        id="otp" 
                        name="otp" 
                        maxlength="6" 
                        pattern="[0-9]{6}"
                        class="w-full px-4 py-3 text-center text-2xl tracking-widest border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="000000"
                        required
                        autofocus
                    >
                    <p class="mt-2 text-sm text-gray-500">Code expires in <span id="timer" class="font-semibold text-green-600">10:00</span></p>
                </div>

                <!-- Verify Button -->
                <button 
                    type="submit" 
                    id="verify-btn"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center"
                >
                    <span id="verify-text">Verify Phone Number</span>
                    <span id="verify-loading" class="hidden">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Options</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <!-- Resend OTP -->
                <button 
                    type="button" 
                    id="resend-btn"
                    class="w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded-lg border-2 border-gray-300 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span id="resend-text">Resend OTP Code</span>
                    <span id="resend-countdown" class="hidden"></span>
                </button>

                <!-- Switch to Email -->
                <button 
                    type="button" 
                    id="switch-email-btn"
                    class="w-full bg-white hover:bg-gray-50 text-blue-600 font-medium py-2 px-4 rounded-lg border-2 border-blue-300 transition duration-200"
                >
                    Switch to Email Verification
                </button>
            </div>
        </div>

        <!-- Auto-request OTP on load -->
        <div id="initial-send" class="mt-4 text-center">
            <p class="text-sm text-gray-600">
                <span class="inline-flex items-center">
                    <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending OTP code...
                </span>
            </p>
        </div>
    </div>

    <script>
        let countdownInterval = null;
        let resendTimeout = null;
        let resendAllowedAt = null;

        // Auto-send OTP on page load
        window.addEventListener('DOMContentLoaded', function() {
            sendOTP(true);
        });

        // OTP Form Submission
        document.getElementById('otp-form').addEventListener('submit', function(e) {
            e.preventDefault();
            verifyOTP();
        });

        // Resend OTP
        document.getElementById('resend-btn').addEventListener('click', function() {
            sendOTP();
        });

        // Switch to Email
        document.getElementById('switch-email-btn').addEventListener('click', function() {
            if (confirm('Switch to email verification? Your current OTP will be cancelled.')) {
                switchToEmail();
            }
        });

        // Auto-format OTP input (numbers only)
        document.getElementById('otp').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });

        // Send OTP
        function sendOTP(isInitial = false) {
            const alertContainer = document.getElementById('alert-container');
            const initialSend = document.getElementById('initial-send');
            
            if (!isInitial) {
                showLoading('resend-btn', 'resend-text');
            }

            fetch('/api/sms/send-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
            })
            .then(response => response.json())
            .then(data => {
                if (isInitial) {
                    initialSend.style.display = 'none';
                }

                if (data.success) {
                    showAlert('success', data.message);
                    startCountdown(data.expiresAt);
                    startResendTimer();
                } else {
                    showAlert('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Failed to send OTP. Please try again.');
            })
            .finally(() => {
                if (!isInitial) {
                    hideLoading('resend-btn', 'resend-text');
                }
            });
        }

        // Verify OTP
        function verifyOTP() {
            const otp = document.getElementById('otp').value;
            
            if (otp.length !== 6) {
                showAlert('error', 'Please enter a 6-digit OTP code.');
                return;
            }

            showLoading('verify-btn', 'verify-text');

            fetch('/api/sms/verify-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ otp: otp })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    setTimeout(() => {
                        window.location.href = data.redirectUrl;
                    }, 1500);
                } else {
                    showAlert('error', data.message);
                    if (data.expired || data.tooManyAttempts) {
                        document.getElementById('otp').value = '';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Verification failed. Please try again.');
            })
            .finally(() => {
                hideLoading('verify-btn', 'verify-text');
            });
        }

        // Switch to Email
        function switchToEmail() {
            fetch('/api/sms/switch-to-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirectUrl;
                } else {
                    showAlert('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Failed to switch verification method.');
            });
        }

        // Start countdown timer
        function startCountdown(expiresAt) {
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }

            const timerElement = document.getElementById('timer');
            const expiryTime = new Date(expiresAt);

            countdownInterval = setInterval(() => {
                const now = new Date();
                const diff = expiryTime - now;

                if (diff <= 0) {
                    clearInterval(countdownInterval);
                    timerElement.textContent = '0:00';
                    timerElement.classList.add('text-red-600');
                    showAlert('warning', 'OTP code has expired. Please request a new one.');
                    return;
                }

                const minutes = Math.floor(diff / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);
                timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                if (diff <= 60000) {
                    timerElement.classList.add('text-red-600');
                }
            }, 1000);
        }

        // Start resend timer (60 seconds cooldown)
        function startResendTimer() {
            const resendBtn = document.getElementById('resend-btn');
            const resendText = document.getElementById('resend-text');
            const resendCountdown = document.getElementById('resend-countdown');
            
            resendBtn.disabled = true;
            resendText.classList.add('hidden');
            resendCountdown.classList.remove('hidden');
            
            let secondsLeft = 60;
            resendAllowedAt = Date.now() + 60000;

            resendTimeout = setInterval(() => {
                secondsLeft--;
                resendCountdown.textContent = `Resend available in ${secondsLeft}s`;

                if (secondsLeft <= 0) {
                    clearInterval(resendTimeout);
                    resendBtn.disabled = false;
                    resendText.classList.remove('hidden');
                    resendCountdown.classList.add('hidden');
                }
            }, 1000);
        }

        // Show alert
        function showAlert(type, message) {
            const alertContainer = document.getElementById('alert-container');
            const colors = {
                success: 'bg-green-100 border-green-400 text-green-700',
                error: 'bg-red-100 border-red-400 text-red-700',
                warning: 'bg-yellow-100 border-yellow-400 text-yellow-700'
            };

            alertContainer.innerHTML = `
                <div class="border-l-4 p-4 ${colors[type]} rounded">
                    <p class="font-medium">${message}</p>
                </div>
            `;

            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        // Show loading state
        function showLoading(btnId, textId) {
            const btn = document.getElementById(btnId);
            const text = document.getElementById(textId);
            const loading = btn.querySelector('[id$="-loading"]');
            
            btn.disabled = true;
            text.classList.add('hidden');
            if (loading) loading.classList.remove('hidden');
        }

        // Hide loading state
        function hideLoading(btnId, textId) {
            const btn = document.getElementById(btnId);
            const text = document.getElementById(textId);
            const loading = btn.querySelector('[id$="-loading"]');
            
            btn.disabled = false;
            text.classList.remove('hidden');
            if (loading) loading.classList.add('hidden');
        }
    </script>
</body>
</html>
