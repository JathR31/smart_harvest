<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Code</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0fdf4; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%); padding: 30px; text-align: center;">
            <div style="background-color: rgba(255, 255, 255, 0.2); width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                </svg>
            </div>
            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 600;">Password Reset</h1>
            <p style="color: rgba(255, 255, 255, 0.9); margin: 10px 0 0 0; font-size: 14px;">SmartHarvest</p>
        </div>

        <!-- Content -->
        <div style="padding: 40px 30px;">
            <p style="color: #374151; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
                Hello{{ isset($name) ? ' ' . $name : '' }},
            </p>
            
            <p style="color: #374151; font-size: 16px; line-height: 1.6; margin: 0 0 25px 0;">
                You requested to reset your password. Use the following code to complete the process:
            </p>

            <!-- Code Box -->
            <div style="background-color: #f0fdf4; border: 2px dashed #22c55e; border-radius: 12px; padding: 25px; text-align: center; margin: 0 0 25px 0;">
                <p style="color: #16a34a; font-size: 14px; font-weight: 500; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 1px;">Your Reset Code</p>
                <div style="font-size: 36px; font-weight: 700; letter-spacing: 8px; color: #15803d; font-family: 'Courier New', monospace;">
                    {{ $code }}
                </div>
            </div>

            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; border-radius: 0 8px 8px 0; margin: 0 0 25px 0;">
                <p style="color: #92400e; font-size: 14px; margin: 0; font-weight: 500;">
                    ⚠️ This code will expire in 15 minutes.
                </p>
            </div>

            <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin: 0 0 10px 0;">
                If you didn't request a password reset, please ignore this email or contact support if you have concerns.
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f9fafb; padding: 20px 30px; text-align: center; border-top: 1px solid #e5e7eb;">
            <p style="color: #9ca3af; font-size: 12px; margin: 0 0 5px 0;">
                This is an automated message from SmartHarvest.
            </p>
            <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                © {{ date('Y') }} SmartHarvest. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
