<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SmartHarvest - Verify OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }
        .verify-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        .otp-input {
            width: 3rem;
            height: 3.5rem;
            font-size: 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body class="antialiased flex items-center justify-center min-h-screen py-10">
    <div class="verify-card w-full max-w-md p-8 bg-white rounded-2xl">
        <!-- Logo/Header -->
        <div class="flex flex-col items-center mb-6">
            <div class="inline-block p-3 rounded-full bg-green-50 mb-4">
                <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Verify Your Mobile Number</h1>
            <p class="text-sm text-gray-600 mt-2 text-center">
                We've sent a 6-digit code to<br>
                <span class="font-semibold text-green-700">{{ $masked_phone }}</span>
            </p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- OTP Input Form -->
        <form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3 text-center">Enter 6-Digit Code</label>
                <div class="flex justify-center gap-2" id="otpInputs">
                    <input type="text" maxlength="1" class="otp-input border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition" data-index="0" autocomplete="off" inputmode="numeric">
                    <input type="text" maxlength="1" class="otp-input border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition" data-index="1" autocomplete="off" inputmode="numeric">
                    <input type="text" maxlength="1" class="otp-input border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition" data-index="2" autocomplete="off" inputmode="numeric">
                    <input type="text" maxlength="1" class="otp-input border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition" data-index="3" autocomplete="off" inputmode="numeric">
                    <input type="text" maxlength="1" class="otp-input border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition" data-index="4" autocomplete="off" inputmode="numeric">
                    <input type="text" maxlength="1" class="otp-input border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition" data-index="5" autocomplete="off" inputmode="numeric">
                </div>
                <input type="hidden" name="otp_code" id="otpCode">
            </div>

            <!-- Timer Display -->
            <div class="text-center mb-4">
                <p class="text-sm text-gray-600">
                    Code expires in: <span id="timer" class="font-semibold text-green-700">10:00</span>
                </p>
            </div>

            <!-- Verify Button -->
            <button type="submit" id="verifyBtn" class="w-full py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition duration-300 shadow-md shadow-green-200">
                Verify Code
            </button>
        </form>

        <!-- Resend OTP -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Didn't receive the code?
            </p>
            <form method="POST" action="{{ route('otp.resend') }}" id="resendForm" class="mt-2">
                @csrf
                <button type="submit" id="resendBtn" class="text-green-600 hover:text-green-700 font-medium text-sm disabled:text-gray-400 disabled:cursor-not-allowed" disabled>
                    Resend Code (<span id="resendTimer">60</span>s)
                </button>
            </form>
        </div>

        <!-- Back to Login -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-green-600 transition duration-150">
                ← Back to Login
            </a>
        </div>

        <!-- Use Email Instead -->
        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg text-center">
            <p class="text-sm text-blue-700">
                Having trouble with SMS?
                <a href="{{ route('otp.switch-to-email') }}" class="font-medium underline hover:text-blue-800">
                    Verify with Email instead
                </a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const otpCodeField = document.getElementById('otpCode');
            const otpForm = document.getElementById('otpForm');
            const timerDisplay = document.getElementById('timer');
            const resendBtn = document.getElementById('resendBtn');
            const resendTimerDisplay = document.getElementById('resendTimer');
            const resendForm = document.getElementById('resendForm');
            
            // OTP Expiry Timer (10 minutes = 600 seconds)
            let timeLeft = 600;
            const timerInterval = setInterval(function() {
                timeLeft--;
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    timerDisplay.textContent = 'Expired';
                    timerDisplay.classList.add('text-red-600');
                    otpInputs.forEach(input => input.disabled = true);
                    document.getElementById('verifyBtn').disabled = true;
                    document.getElementById('verifyBtn').classList.add('opacity-50', 'cursor-not-allowed');
                }
            }, 1000);
            
            // Resend Button Timer (60 seconds)
            let resendTimeLeft = 60;
            const resendInterval = setInterval(function() {
                resendTimeLeft--;
                resendTimerDisplay.textContent = resendTimeLeft;
                
                if (resendTimeLeft <= 0) {
                    clearInterval(resendInterval);
                    resendBtn.disabled = false;
                    resendBtn.textContent = 'Resend Code';
                }
            }, 1000);
            
            // OTP Input Handling
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    // Only allow numbers
                    const value = e.target.value.replace(/[^0-9]/g, '');
                    e.target.value = value;
                    
                    // Auto-focus next input
                    if (value && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                    
                    // Update hidden field with complete OTP
                    updateOTPCode();
                });
                
                input.addEventListener('keydown', function(e) {
                    // Handle backspace
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                    
                    // Handle arrow keys
                    if (e.key === 'ArrowLeft' && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                    if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                });
                
                // Handle paste
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
                    
                    if (pastedData.length === 6) {
                        pastedData.split('').forEach((digit, idx) => {
                            if (otpInputs[idx]) {
                                otpInputs[idx].value = digit;
                            }
                        });
                        updateOTPCode();
                        otpInputs[5].focus();
                    }
                });
            });
            
            function updateOTPCode() {
                const code = Array.from(otpInputs).map(input => input.value).join('');
                otpCodeField.value = code;
                
                // Auto-submit when all 6 digits are entered
                if (code.length === 6) {
                    setTimeout(() => {
                        otpForm.submit();
                    }, 500);
                }
            }
            
            // Focus first input on load
            otpInputs[0].focus();
            
            // Resend form handling
            resendForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Disable button and show loading
                resendBtn.disabled = true;
                resendBtn.textContent = 'Sending...';
                
                // Submit form
                fetch(resendForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alert('New OTP code sent successfully!');
                        
                        // Reset timer
                        timeLeft = 600;
                        resendTimeLeft = 60;
                        
                        // Clear inputs
                        otpInputs.forEach(input => {
                            input.value = '';
                            input.disabled = false;
                        });
                        otpInputs[0].focus();
                        
                        // Restart resend timer
                        setTimeout(() => {
                            resendBtn.textContent = 'Resend Code (60s)';
                            const newResendInterval = setInterval(function() {
                                resendTimeLeft--;
                                resendTimerDisplay.textContent = resendTimeLeft;
                                
                                if (resendTimeLeft <= 0) {
                                    clearInterval(newResendInterval);
                                    resendBtn.disabled = false;
                                    resendBtn.textContent = 'Resend Code';
                                }
                            }, 1000);
                        }, 100);
                    } else {
                        alert(data.message || 'Failed to resend OTP. Please try again.');
                        resendBtn.disabled = false;
                        resendBtn.textContent = 'Resend Code';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    resendBtn.disabled = false;
                    resendBtn.textContent = 'Resend Code';
                });
            });
        });
    </script>
</body>
</html>
