# SMS/OTP Verification Setup Guide

## Overview
SmartHarvest uses **Semaphore SMS API** for OTP (One-Time Password) verification during farmer registration. This allows farmers without email access to verify their accounts via SMS.

## Current Status
✅ SMS Service implemented (`app/Services/SMSService.php`)  
✅ OTP verification routes configured  
✅ Registration flow supports SMS option  
⚠️ **Needs Semaphore API Key to work**

---

## Quick Setup (5 Minutes)

### Step 1: Get Semaphore API Key

1. **Sign up at Semaphore**
   - Visit: https://semaphore.co/
   - Click "Sign Up" (Free trial available)
   - Complete registration

2. **Get Your API Key**
   - Login to dashboard: https://app.semaphore.co/
   - Navigate to **API** section
   - Copy your API Key

3. **Check Credits**
   - Free trial: 100 SMS credits
   - Paid: ₱0.50 - ₱1.00 per SMS
   - Verify you have credits available

### Step 2: Configure SmartHarvest

1. **Open `.env` file** in your project root

2. **Add your API key:**
   ```env
   SEMAPHORE_API_KEY=your_api_key_here
   ```

3. **Save the file**

### Step 3: Test SMS Functionality

Run the test script:
```bash
php test_sms.php
```

**Expected Output:**
```
SMS/OTP Service Test
✓ API Key configured
Enter test phone number: 09171234567
Generated OTP: 123456
Sending SMS...

RESULT:
Success: YES ✓
Message: OTP code sent successfully
✓ SMS sent successfully!
```

---

## How It Works

### Registration Flow (SMS Option)

1. **User registers** and selects "SMS Verification"
2. **System generates** 6-digit OTP code
3. **SMS sent** to user's phone number via Semaphore
4. **User receives** SMS with OTP
5. **User enters** OTP on verification page
6. **System validates** OTP and activates account
7. **User sets password** and can login

### Security Features

✅ **10-minute expiration** - OTP expires after 10 minutes  
✅ **Rate limiting** - Max 3 resend attempts per minute  
✅ **Attempt tracking** - Max 5 verification attempts  
✅ **Auto-cleanup** - Expired OTPs cleared automatically  
✅ **Fallback to email** - If SMS fails, switches to email verification

---

## File Structure

```
SmartHarvest/
├── app/Services/SMSService.php          # Core SMS service
├── routes/web.php                       # OTP routes (lines 2936-3050)
├── resources/views/verify-otp.blade.php # OTP verification page
├── database/migrations/
│   └── *_add_sms_otp_fields_to_users_table.php
├── test_sms.php                         # Test script
└── .env                                 # Configuration (add API key here)
```

---

## API Configuration

### Semaphore SMS Service

**Service:** `App\Services\SMSService`

**Key Methods:**
- `sendOTP($phoneNumber, $otpCode)` - Send OTP via SMS
- `normalizePhoneNumber($phone)` - Convert to Philippine format
- `maskPhoneNumber($phone)` - Hide digits for security

**Supported Formats:**
- `09XXXXXXXXX` (Standard Philippine)
- `9XXXXXXXXX` (Without leading 0)
- `+639XXXXXXXXX` (International)
- `639XXXXXXXXX` (Without +)

**SMS Message Template:**
```
SmartHarvest Verification

Your OTP code is: 123456

This code will expire in 10 minutes. 
Do not share this code with anyone.
```

---

## Routes

### OTP Verification Routes

```php
// Display OTP verification form
GET /verify-otp
Route: otp.verify.show
Middleware: auth

// Submit OTP code
POST /verify-otp
Route: otp.verify
Middleware: auth

// Resend OTP
POST /resend-otp
Route: otp.resend
Middleware: auth, throttle:3,1

// Switch to email verification
GET /switch-to-email-verification
Middleware: auth
```

---

## Database Schema

### Users Table - OTP Fields

```sql
otp_code VARCHAR(6) NULL            -- 6-digit OTP
otp_expires_at TIMESTAMP NULL       -- Expiration time
otp_attempts INT DEFAULT 0          -- Failed attempts
phone_verified_at TIMESTAMP NULL    -- Verification timestamp
verification_method VARCHAR(20)     -- 'sms' or 'email'
```

---

## Testing

### Manual Testing Steps

1. **Start Server:**
   ```bash
   php artisan serve
   ```

2. **Register New Account:**
   - Go to: http://localhost:8000/register
   - Fill in details
   - Enter your phone number (09XXXXXXXXX)
   - Select "SMS Verification"
   - Submit

3. **Check Your Phone:**
   - You should receive SMS with 6-digit code
   - Code expires in 10 minutes

4. **Enter OTP:**
   - Redirected to /verify-otp
   - Enter the 6-digit code
   - Submit

5. **Set Password:**
   - After successful verification
   - Set your password
   - Login and access dashboard

### Test with Script

```bash
php test_sms.php
```

Enter your phone number when prompted. Check your phone for the OTP.

---

## Troubleshooting

### Issue: "SMS service not configured"
**Solution:** Add `SEMAPHORE_API_KEY` to `.env` file

### Issue: "Invalid phone number format"
**Solution:** Use Philippine mobile format (09XXXXXXXXX)

### Issue: "Failed to send SMS"
**Possible Causes:**
- Invalid API key
- No SMS credits remaining
- Invalid phone number
- Network connectivity issue

**Check:**
```bash
# View logs
tail -f storage/logs/laravel.log

# Test API key
php test_sms.php
```

### Issue: OTP not received
**Check:**
1. Phone number is correct
2. Semaphore credits available
3. Network signal on phone
4. Check spam/blocked messages
5. View logs: `storage/logs/laravel.log`

### Issue: OTP expired
**Solution:** Click "Resend Code" button (max 3 times per minute)

---

## Cost Estimation

### Semaphore Pricing (Philippines)

| Package | Credits | Price | Per SMS |
|---------|---------|-------|---------|
| Free Trial | 100 | Free | ₱0.00 |
| Starter | 1,000 | ₱500 | ₱0.50 |
| Basic | 5,000 | ₱2,500 | ₱0.50 |
| Pro | 10,000+ | Custom | ₱0.40-0.50 |

**For 1000 farmers:**
- Each farmer receives 1-2 OTPs on average
- Total SMS: ~1,500
- Cost: ₱750 - ₱1,000

---

## Alternative SMS Providers

If Semaphore doesn't work, you can switch to:

### 1. Twilio
- Global coverage
- ₱0.80 per SMS
- More expensive but more reliable
- Update `SMSService.php` to use Twilio API

### 2. M360 (Philippines)
- Local provider
- Similar pricing to Semaphore
- Good for Philippine-only deployment

### 3. Free Alternatives (Not Recommended for Production)
- Textbelt (Limited free tier)
- Vonage (Complex setup)

---

## Production Checklist

Before deploying to production:

- [ ] Semaphore API key configured
- [ ] SMS credits purchased
- [ ] Test SMS sending with real phone
- [ ] Test OTP verification flow
- [ ] Test resend OTP functionality
- [ ] Test OTP expiration (wait 10 min)
- [ ] Test failed attempts (5 wrong codes)
- [ ] Test fallback to email if SMS fails
- [ ] Monitor SMS delivery rates
- [ ] Set up credit alerts in Semaphore dashboard

---

## Support

### Semaphore Support
- Dashboard: https://app.semaphore.co/
- Documentation: https://semaphore.co/docs
- Email: support@semaphore.co

### SmartHarvest SMS Issues
- Check logs: `storage/logs/laravel.log`
- Review `app/Services/SMSService.php`
- Test with: `php test_sms.php`

---

## Quick Reference

### Add API Key
```env
SEMAPHORE_API_KEY=your_api_key_here
```

### Test SMS
```bash
php test_sms.php
```

### View Logs
```bash
tail -f storage/logs/laravel.log
```

### Check OTP in Database
```sql
SELECT name, phone_number, otp_code, otp_expires_at 
FROM users 
WHERE verification_method = 'sms';
```

---

**Status:** Ready to use once API key is configured  
**Last Updated:** January 14, 2026
