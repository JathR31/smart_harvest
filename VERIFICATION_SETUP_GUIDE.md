# SMS & Email Verification Setup Guide

This guide covers how to set up and use the SMS and Email verification system in SmartHarvest.

## Quick Start (Development Mode)

For development and testing, **simulation mode** is enabled by default:

```env
SMS_SIMULATION_MODE=true
```

In simulation mode:
- ✅ No real SMS messages are sent
- ✅ OTP codes are logged to `storage/logs/laravel.log`
- ✅ You can test the entire verification flow
- ✅ No API keys required

To view simulated OTPs, check your Laravel logs:
```bash
tail -f storage/logs/laravel.log | grep "SIMULATION OTP"
```

---

## Email Verification (Free - Using Brevo)

Email verification is already configured using **Brevo (Sendinblue)** SMTP:

| Feature | Details |
|---------|---------|
| Provider | Brevo (formerly Sendinblue) |
| Free Tier | 300 emails/day |
| Sign Up | https://www.brevo.com/ |
| Cost | Free up to 300 emails/day |

### Current Configuration

Your `.env` is already configured:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_smtp_key
MAIL_PASSWORD=your_brevo_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your@email.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## SMS Verification Options (Free & Paid)

The system supports multiple SMS providers with automatic fallback:

### 1. Simulation Mode (Free - Development)

**Best for:** Local development and testing

```env
SMS_SIMULATION_MODE=true
```

### 2. Semaphore (Recommended for Philippines)

**Best for:** Production use in the Philippines

| Feature | Details |
|---------|---------|
| Sign Up | https://semaphore.co/ |
| Pricing | ~₱0.50/SMS |
| Coverage | Philippines only |

```env
SMS_SIMULATION_MODE=false
SEMAPHORE_API_KEY=your_api_key_here
```

### 3. Twilio (International)

**Best for:** International SMS delivery

| Feature | Details |
|---------|---------|
| Sign Up | https://console.twilio.com/ |
| Free Trial | $15.50 in credits |
| Pricing | ~$0.0075/SMS |

```env
SMS_SIMULATION_MODE=false
TWILIO_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_PHONE_NUMBER=+1234567890
```

### 4. TextBelt (Free Tier Available)

**Best for:** Testing with limited free SMS

| Feature | Details |
|---------|---------|
| Website | https://textbelt.com/ |
| Free Tier | 1 SMS/day |
| Pricing | $0.05/SMS after |

```env
SMS_SIMULATION_MODE=false
TEXTBELT_API_KEY=textbelt  # Use 'textbelt' for free tier
```

---

## Provider Priority & Fallback

The system tries providers in this order:

1. **Semaphore** - If API key is configured
2. **Twilio** - If credentials are configured
3. **TextBelt** - If API key is configured
4. **Simulation** - If `SMS_SIMULATION_MODE=true`
5. **Email Fallback** - If all SMS providers fail

---

## Configuration Summary

### Minimal Setup (Development)

```env
# Development - just use simulation mode
SMS_SIMULATION_MODE=true
```

### Production Setup (Philippines)

```env
# Production with Semaphore
SMS_SIMULATION_MODE=false
SEMAPHORE_API_KEY=your_actual_api_key
```

### Production Setup (International)

```env
# Production with Twilio
SMS_SIMULATION_MODE=false
TWILIO_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_PHONE_NUMBER=+1234567890
```

---

## Rate Limiting

The system implements rate limiting to prevent abuse:

| Limit | Value |
|-------|-------|
| SMS per phone number | 3 per 10 minutes |
| Email per address | 1 per minute |
| OTP verification attempts | 5 per code |

---

## Testing the System

### Test Registration Flow

1. Go to `/register`
2. Fill in the form
3. Choose "SMS Verification" or "Email Verification"
4. Submit the form
5. Check logs for OTP (simulation mode) or your phone/email

### Check SMS Health

You can check if SMS services are working:

```php
$smsService = new \App\Services\FreeSMSService();
$health = $smsService->checkHealth();
print_r($health);
```

### Check Email Health

```php
$emailService = new \App\Services\EmailVerificationService();
$health = $emailService->checkHealth();
print_r($health);
```

---

## Troubleshooting

### SMS Not Sending

1. Check if simulation mode is enabled: `SMS_SIMULATION_MODE=true`
2. Verify API keys are correct in `.env`
3. Check Laravel logs: `storage/logs/laravel.log`
4. Ensure phone number format is correct: `+639XXXXXXXXX`

### Email Not Sending

1. Check mail configuration in `.env`
2. Verify Brevo credentials are correct
3. Check spam folder
4. Review Laravel logs for errors

### OTP Expired

- OTP codes expire after 10 minutes
- User can request a new code using "Resend OTP"
- Maximum 5 verification attempts per code

---

## Services Overview

### FreeSMSService

Location: `app/Services/FreeSMSService.php`

Methods:
- `sendOTP($phoneNumber, $otpCode)` - Send OTP via SMS
- `sendWithSemaphore()` - Send via Semaphore
- `sendWithTwilio()` - Send via Twilio  
- `sendWithTextBelt()` - Send via TextBelt
- `simulateSMS()` - Log OTP without sending
- `checkHealth()` - Check service availability

### EmailVerificationService

Location: `app/Services/EmailVerificationService.php`

Methods:
- `sendVerificationEmail($user)` - Send verification link
- `sendOTPEmail($user, $otp)` - Send OTP code via email
- `verifyEmail($userId, $hash)` - Verify email signature
- `checkHealth()` - Check email service status

---

## Free Tier Comparison

| Provider | Free Tier | Best For |
|----------|-----------|----------|
| Simulation | Unlimited | Development |
| TextBelt | 1 SMS/day | Testing |
| Twilio | $15.50 credits | Trial period |
| Semaphore | Pay-per-use | Philippines production |
| Brevo Email | 300/day | Email verification |

---

## Need Help?

- **Semaphore Issues:** https://semaphore.co/support
- **Twilio Issues:** https://support.twilio.com/
- **TextBelt Issues:** https://textbelt.com/
- **Brevo Issues:** https://help.brevo.com/
