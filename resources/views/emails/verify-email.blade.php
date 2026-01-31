<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - SmartHarvest</title>
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
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            color: white !important;
            text-decoration: none;
            padding: 14px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
        }
        .button:hover {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
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
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #888;
            font-size: 12px;
        }
        .link-fallback {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            word-break: break-all;
            font-size: 12px;
            color: #666;
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
                Thank you for registering with SmartHarvest. To complete your registration and access all features, please verify your email address by clicking the button below:
            </p>
            
            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>
            </div>
            
            <div class="expire-notice">
                ⏰ This verification link will expire in {{ $expiresIn ?? '60 minutes' }}.
            </div>
            
            <p class="message">
                After verification, you'll be able to set your password and start using SmartHarvest to optimize your planting schedules and maximize your yields.
            </p>
            
            <div class="link-fallback">
                <strong>Can't click the button?</strong> Copy and paste this link into your browser:<br>
                {{ $verificationUrl }}
            </div>
        </div>
        
        <div class="footer">
            <p>If you didn't create an account with SmartHarvest, you can safely ignore this email.</p>
            <p>&copy; {{ date('Y') }} SmartHarvest - Cordillera Agricultural Insights</p>
        </div>
    </div>
</body>
</html>
