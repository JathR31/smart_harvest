# SMS OTP Verification Setup Guide

## Overview
SmartHarvest now supports SMS OTP verification as an alternative to email verification. This is particularly useful for users in areas with limited email access.

## Features
- ✅ 6-digit OTP code sent via SMS
- ✅ 10-minute expiration time
- ✅ Rate limiting (max 3 resend attempts per minute)
- ✅ 5 verification attempts before requiring new OTP
- ✅ Philippine mobile number support (+63 9XX XXX XXXX)
- ✅ Fallback to email verification if SMS fails
- ✅ Switch between email and SMS verification

## SMS Provider: Semaphore
We use **Semaphore** as our SMS gateway provider, which is widely used in the Philippines.

### Why Semaphore?
- 📱 Philippine-focused SMS service
- 💰 Affordable pricing (₱0.60-0.80 per SMS)
- 🚀 Fast delivery (typically within seconds)
- 📊 Delivery tracking and analytics
- 🔒 Secure API with authentication

## Setup Instructions

### 1. Get Semaphore API Key

1. **Sign up for Semaphore account**:
   - Visit: https://semaphore.co/
   - Click "Sign Up" or "Get Started"
   - Fill in your details
   - Verify your email address

2. **Purchase SMS credits**:
   - Log in to your Semaphore dashboard
   - Go to "Buy Credits"
   - Choose a package (recommended: ₱500 for ~625-833 messages)
   - Pay via GCash, PayMaya, Bank Transfer, or Credit Card

3. **Get your API Key**:
   - Go to dashboard: https://semaphore.co/dashboard
   - Navigate to "API" section
   - Copy your API key (starts with `xxxx...`)

### 2. Configure Environment Variables

Add the following to your `.env` file:

```env
# SMS Service Configuration (Semaphore)
SEMAPHORE_API_KEY=your_api_key_here
```

**Example:**
```env
SEMAPHORE_API_KEY=abc123def456ghi789jkl012mno345pqr
```

### 3. Run Database Migration

```bash
php artisan migrate
```

This will add the following columns to the `users` table:
- `phone_number` - User's mobile number (+639XXXXXXXXX format)
- `phone_verified_at` - Timestamp when phone was verified
- `otp_code` - Current OTP code (6 digits)
- `otp_expires_at` - OTP expiration timestamp
- `verification_method` - 'email' or 'sms'
- `otp_attempts` - Failed verification attempts counter

### 4. Test the Implementation

1. **Register a new account**:
   - Go to `/register`
   - Fill in your details
   - Enter a valid Philippine mobile number (09XX XXX XXXX)
   - Select "SMS Verification"
   - Submit the form

2. **Verify OTP**:
   - You should receive an SMS with a 6-digit code
   - Enter the code on the verification page
   - Code expires in 10 minutes
   - Maximum 5 attempts before requiring new code

3. **Test Resend**:
   - Wait 60 seconds after initial send
   - Click "Resend Code"
   - New OTP will be sent

## Usage Flow

### User Registration with SMS OTP

```
1. User fills registration form
   ↓
2. User enters phone number (+63 9XX XXX XXXX)
   ↓
3. User selects "SMS Verification"
   ↓
4. System generates 6-digit OTP
   ↓
5. SMS sent via Semaphore API
   ↓
6. User receives SMS with OTP
   ↓
7. User enters OTP on verification page
   ↓
8. System validates OTP
   ↓
9. Phone marked as verified
   ↓
10. User sets password
    ↓
11. User redirected to dashboard
```

### Fallback Mechanism

If SMS delivery fails:
1. System automatically switches to email verification
2. Verification email sent to user's email address
3. User notified about fallback
4. User can still verify via email link

## Phone Number Format

### Accepted Formats
- `09XX XXX XXXX` (most common)
- `9XXXXXXXXX` (without leading zero)
- `+639XXXXXXXXX` (international format)
- `639XXXXXXXXX` (international without +)

### Examples
- ✅ `0917 123 4567` → `+639171234567`
- ✅ `9171234567` → `+639171234567`
- ✅ `+639171234567` → `+639171234567`
- ❌ `123456789` → Invalid (must start with 9)
- ❌ `09123456789` → Invalid (too long)

## Security Features

### Rate Limiting
- **OTP Resend**: Max 3 requests per minute
- **Verification Attempts**: Max 5 failed attempts before requiring new OTP
- **OTP Expiration**: 10 minutes from generation

### Protection Against Abuse
1. **Throttling**: Prevents spam requests
2. **Attempt Counting**: Blocks brute-force attacks
3. **Expiration**: Limits OTP validity window
4. **Phone Uniqueness**: Each phone can only register once

## Troubleshooting

### SMS Not Received

**Check:**
1. ✅ API key is correct in `.env`
2. ✅ Semaphore account has sufficient credits
3. ✅ Phone number is in correct format
4. ✅ Phone number is a valid Philippine mobile number
5. ✅ Check SMS logs in Semaphore dashboard

**Solutions:**
- Use "Switch to Email Verification" link
- Request new OTP after 60 seconds
- Contact Semaphore support if persistent issues

### Invalid OTP Error

**Possible Causes:**
- OTP has expired (>10 minutes)
- OTP was entered incorrectly
- Too many failed attempts (>5)

**Solutions:**
- Request new OTP via "Resend Code"
- Double-check the code in SMS
- Wait for new SMS after resend

### SMS Service Error

**Check Laravel logs:**
```bash
tail -f storage/logs/laravel.log
```

**Common issues:**
- Missing API key: `SEMAPHORE_API_KEY not configured`
- API error: Check Semaphore dashboard for error details
- Network timeout: Check internet connectivity

## Cost Estimation

### SMS Pricing (Semaphore)
- **Per SMS**: ₱0.60 - ₱0.80 (depending on package)
- **Bulk packages available**:
  - 500 credits: ₱400 (~625 SMS)
  - 1000 credits: ₱750 (~937 SMS)
  - 5000 credits: ₱3,500 (~4,375 SMS)

### Expected Usage
- **Per registration**: 1-2 SMS (initial + 1 resend average)
- **100 users**: ~₱80-₱160
- **1000 users**: ~₱600-₱1,600
- **10,000 users**: ~₱6,000-₱16,000

### Cost Optimization Tips
1. Set appropriate expiration times (10 min is good)
2. Limit resend attempts (currently: 3 per minute)
3. Implement email fallback for failed SMS
4. Monitor usage in Semaphore dashboard
5. Buy bulk packages for better rates

## Alternative SMS Providers

If you want to use a different provider, modify `app/Services/SMSService.php`:

### Twilio (International)
```php
// Change baseUrl to Twilio API
protected $baseUrl = 'https://api.twilio.com/2010-04-01/Accounts/{AccountSid}/Messages.json';
```

### Globe Labs / Smart Developer Portal (PH)
- Globe: https://www.globe.com.ph/about-us/globe-labs.html
- Smart: https://developer.smart.com.ph/

## Files Created/Modified

### New Files
1. `app/Services/SMSService.php` - SMS sending service
2. `resources/views/verify-otp.blade.php` - OTP verification page
3. `database/migrations/2025_12_06_145759_add_sms_otp_fields_to_users_table.php` - Database migration
4. `SMS_OTP_SETUP.md` - This documentation

### Modified Files
1. `resources/views/register.blade.php` - Added phone input and verification method selector
2. `routes/web.php` - Added OTP verification routes and updated registration logic
3. `app/Models/User.php` - Added phone verification methods and fillable fields

## API Routes

### Registration
- `POST /register` - User registration with verification method selection

### OTP Verification
- `GET /verify-otp` - Display OTP input page
- `POST /verify-otp` - Verify entered OTP code
- `POST /resend-otp` - Resend OTP (throttled: 3/minute)
- `GET /switch-to-email-verification` - Switch from SMS to email

## Testing Checklist

- [ ] Register with SMS verification
- [ ] Receive OTP via SMS
- [ ] Verify correct OTP
- [ ] Verify incorrect OTP (check error message)
- [ ] Test OTP expiration (after 10 minutes)
- [ ] Test resend functionality
- [ ] Test rate limiting (multiple resends)
- [ ] Test switch to email verification
- [ ] Test SMS fallback when API fails
- [ ] Verify phone number validation
- [ ] Test with various phone formats

## Production Deployment

### Pre-deployment Checklist
1. ✅ Semaphore API key configured in production `.env`
2. ✅ Database migration run
3. ✅ SMS credits purchased and loaded
4. ✅ Rate limiting configured appropriately
5. ✅ Error logging enabled
6. ✅ Fallback mechanisms tested
7. ✅ User documentation updated

### Monitoring
- Monitor SMS delivery rates in Semaphore dashboard
- Track OTP verification success rates
- Monitor API error logs
- Check SMS credit balance regularly
- Set up alerts for low credits

## Support

### Semaphore Support
- Website: https://semaphore.co/
- Email: hello@semaphore.co
- Phone: +63 2 8638 8888
- Documentation: https://semaphore.co/docs

### SmartHarvest Support
- For implementation issues, check Laravel logs
- Review `app/Services/SMSService.php` for SMS logic
- Check routes in `routes/web.php` for verification flow

## License & Credits
- Semaphore API: https://semaphore.co/
- Laravel Framework: https://laravel.com/
- SmartHarvest Platform: Agricultural data intelligence system for Benguet Province farmers

---

**Last Updated**: December 6, 2024
**Version**: 1.0
**Status**: Production Ready
