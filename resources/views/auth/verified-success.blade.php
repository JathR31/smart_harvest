<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verified - SmartHarvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 via-green-100 to-emerald-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white shadow-2xl rounded-2xl p-8 text-center">
            <div class="inline-block p-4 mb-4 rounded-full bg-green-100">
                <svg class="w-20 h-20 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-800 mb-3">Verified!</h1>
            <p class="text-gray-600 mb-6 text-lg">{{ $message }}</p>
            
            @if($can_close)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-800">
                        <strong>You can now close this window</strong> and return to your registration tab.
                    </p>
                </div>
            @endif
            
            <button onclick="window.close()" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                Close This Window
            </button>
            
            <p class="text-xs text-gray-500 mt-6">
                SmartHarvest Provincial Rice Monitoring System
            </p>
        </div>
    </div>
    
    <script>
        // Signal to other tabs that verification is complete
        localStorage.setItem('email_verified', 'true');
        
        // Try to close the window automatically after 2 seconds
        setTimeout(function() {
            window.close();
        }, 2000);
        
        // If window doesn't close, redirect to password setup
        setTimeout(function() {
            window.location.href = '/setup-password';
        }, 3000);
    </script>
</body>
</html>
