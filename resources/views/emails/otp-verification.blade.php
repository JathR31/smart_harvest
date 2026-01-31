<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Verification Code - SmartHarvest</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .title {
            color: #047857;
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .subtitle {
            color: #666;
            font-size: 14px;
        }
        .content {
            margin: 30px 0;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        .message {
            color: #555;
            margin: 20px 0;
        }
        .otp-container {
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            display: inline-block;
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            color: white;
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            padding: 20px 40px;
            border-radius: 12px;
            font-family: 'Courier New', monospace;
        }
        .otp-label {
            display: block;
            margin-top: 15px;
            color: #666;
            font-size: 14px;
        }
        .expire-notice {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            font-size: 14px;
            color: #92400e;
        }
        .security-notice {
            background: #fef2f2;
            border: 1px solid #ef4444;
            border-radius: 8px;
            padding: 12px;
            margin-top: 20px;
            font-size: 13px;
            color: #991b1b;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #888;
            font-size: 12px;
        }
        .steps {
            background: #f0fdf4;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .steps h3 {
            color: #047857;
            margin: 0 0 15px 0;
            font-size: 16px;
        }
        .steps ol {
            margin: 0;
            padding-left: 20px;
            color: #555;
        }
        .steps li {
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🌱</div>
            <h1 class="title">SmartHarvest</h1>
            <p class="subtitle">Grow Smarter, Harvest Better</p>
        </div>
        
        <div class="content">
            <p class="greeting">Hello {{ $user->name ?? 'Farmer' }}!</p>
            
            <p class="message">
                Use the verification code below to complete your {{ $purpose ?? 'registration' }}:
            </p>
            
            <div class="otp-container">
                <span class="otp-code">{{ $otp }}</span>
                <span class="otp-label">Your One-Time Verification Code</span>
            </div>
            
            <div class="expire-notice">
                ⏰ This code will expire in {{ $expiresIn ?? '10 minutes' }}.
            </div>
            
            <div class="steps">
                <h3>📋 How to verify:</h3>
                <ol>
                    <li>Go back to the SmartHarvest verification page</li>
                    <li>Enter the 6-digit code shown above</li>
                    <li>Click "Verify" to complete your registration</li>
                </ol>
            </div>
            
            <div class="security-notice">
                🔒 <strong>Security Notice:</strong> Never share this code with anyone. SmartHarvest staff will never ask for your verification code.
            </div>
        </div>
        
        <div class="footer">
            <p>If you didn't request this code, please ignore this email or contact support if you're concerned.</p>
            <p>&copy; {{ date('Y') }} SmartHarvest - Cordillera Agricultural Insights</p>
        </div>
    </div>
</body>
</html>
