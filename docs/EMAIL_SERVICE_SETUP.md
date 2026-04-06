# Email Service Setup Guide - SmartHarvest

## Overview
This guide will help you configure real email sending for SmartHarvest's email verification system during user registration.

---

## Email Services Comparison

| Service | Free Tier | Pros | Cons | Best For |
|---------|-----------|------|------|----------|
| **Brevo (Sendinblue)** | 300 emails/day | Easy setup, reliable, good deliverability | Daily limit | Small projects, testing |
| **Mailgun** | 5,000 emails/month (3 months trial) | Professional, detailed analytics | Requires credit card | Production apps |
| **Gmail SMTP** | 500 emails/day | Free, familiar | Less professional, may flag as spam | Development only |

**Recommended: Brevo (Sendinblue)** - Best balance of features and ease of use.

---

## Option 1: Brevo (Sendinblue) Setup (RECOMMENDED)

### Step 1: Create Brevo Account
1. Go to: https://www.brevo.com/
2. Click "Sign up free"
3. Enter your email and create password
4. Verify your email address

### Step 2: Get SMTP Credentials
1. Login to Brevo dashboard
2. Go to **Settings** → **SMTP & API**
3. Click **SMTP** tab
4. You'll see:
   - **SMTP Server**: `smtp-relay.brevo.com`
   - **Port**: `587`
   - **Login**: Your Brevo account email
   - **Master Password**: Click "Generate a new SMTP key"

### Step 3: Update SmartHarvest .env
```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your-brevo-email@example.com
MAIL_PASSWORD=your-generated-smtp-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@smartharvest.local"
MAIL_FROM_NAME="SmartHarvest"
```

### Step 4: Clear Config Cache
```powershell
php artisan config:clear
```

### Step 5: Test Email Sending
Register a new user and check if verification email arrives.

**Brevo Dashboard**: Monitor sent emails at https://app.brevo.com/email-campaigns/statistics

---

## Option 2: Mailgun Setup

### Step 1: Create Mailgun Account
1. Go to: https://www.mailgun.com/
2. Sign up for free trial (requires credit card)
3. Verify your email

### Step 2: Add Sending Domain
1. Go to **Sending** → **Domains**
2. Click **Add New Domain**
3. Use: `mg.yourdomain.com` (if you own a domain)
   - Or use sandbox domain for testing: `sandbox[xxx].mailgun.org`

### Step 3: Get API Credentials
1. Select your domain
2. Copy:
   - **SMTP hostname**: `smtp.mailgun.org`
   - **Port**: `587`
   - **Default SMTP Login**: `postmaster@your-domain.mailgun.org`
   - **Default password**: Click "Reset password"

### Step 4: Install Mailgun Package
```powershell
composer require symfony/mailgun-mailer symfony/http-client
```

### Step 5: Update SmartHarvest .env
```dotenv
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-api-key
MAILGUN_ENDPOINT=api.mailgun.net
MAIL_FROM_ADDRESS="noreply@smartharvest.local"
MAIL_FROM_NAME="SmartHarvest"
```

### Step 6: Update config/services.php
Add Mailgun configuration:
```php
'mailgun' => [
    'domain' => env('MAILGUN_DOMAIN'),
    'secret' => env('MAILGUN_SECRET'),
    'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
],
```

### Step 7: Clear Cache & Test
```powershell
php artisan config:clear
# Register new user to test
```

---

## Option 3: Gmail SMTP Setup (Development Only)

### Step 1: Enable 2-Step Verification
1. Go to: https://myaccount.google.com/security
2. Click **2-Step Verification**
3. Follow steps to enable

### Step 2: Generate App Password
1. Go to: https://myaccount.google.com/apppasswords
2. Select **App**: Mail
3. Select **Device**: Windows Computer (or Other)
4. Click **Generate**
5. Copy the 16-character password (e.g., `abcd efgh ijkl mnop`)

### Step 3: Update SmartHarvest .env
```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=abcdefghijklmnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-gmail@gmail.com"
MAIL_FROM_NAME="SmartHarvest"
```

### Step 4: Clear Cache & Test
```powershell
php artisan config:clear
```

**Note**: Gmail has a 500 emails/day limit and may flag your emails as spam.

---

## Testing Email Configuration

### Method 1: Register Test User
1. Go to: `http://localhost/dashboard/SmartHarvest/public/register`
2. Fill registration form with your real email
3. Submit form
4. Check your inbox for verification email
5. Check spam folder if not in inbox

### Method 2: Use Tinker
```powershell
php artisan tinker
```

```php
// Get a test user
$user = \App\Models\User::where('email_verified_at', null)->first();

// Send verification email
$user->sendEmailVerificationNotification();

// Output: Should return without errors
exit
```

Check your email inbox.

### Method 3: Check Logs
```powershell
# Monitor log in real-time
Get-Content storage\logs\laravel.log -Wait -Tail 50
```

Register a user and watch for email-related logs.

---

## Troubleshooting

### Issue: "Connection could not be established with host smtp-relay.brevo.com"

**Possible Causes**:
1. Firewall blocking port 587
2. Wrong SMTP credentials
3. Internet connection issue

**Solutions**:
```powershell
# Test connectivity to SMTP server
Test-NetConnection smtp-relay.brevo.com -Port 587

# If blocked, try port 465 with SSL
# Update .env:
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

### Issue: "Expected response code 250 but got code 535"

**Cause**: Invalid SMTP credentials

**Solution**:
1. Regenerate SMTP key in Brevo dashboard
2. Update `MAIL_PASSWORD` in `.env`
3. Clear config: `php artisan config:clear`

### Issue: Emails going to spam

**Solutions**:
1. **Add SPF Record** (if using custom domain):
   ```
   v=spf1 include:spf.brevo.com ~all
   ```

2. **Add DKIM Record**: Get from Brevo → Settings → Senders

3. **Verify Sender Domain**: Use your actual domain instead of `smartharvest.local`

4. **Update FROM address**:
   ```dotenv
   MAIL_FROM_ADDRESS="noreply@yourdomain.com"
   ```

### Issue: "Swift_TransportException: Failed to authenticate"

**Solution**:
```powershell
# Check .env values
php artisan tinker
echo config('mail.username');
echo config('mail.password');
exit

# If showing old values, clear all caches
php artisan config:clear
php artisan cache:clear
```

### Issue: Verification email not sent during registration

**Check**:
1. User model implements `MustVerifyEmail` ✓
2. Registration route calls `sendEmailVerificationNotification()` ✓
3. Mail configuration correct in `.env`
4. Config cache cleared

**Debug**:
```php
// In routes/web.php registration route, add:
try {
    $user->sendEmailVerificationNotification();
    \Log::info('Verification email sent to: ' . $user->email);
} catch (\Exception $e) {
    \Log::error('Failed to send verification: ' . $e->getMessage());
}
```

---

## Email Verification Flow

### Complete User Journey

1. **User Registers**
   - Fills form: Name, Email, Municipality
   - Submits registration
   - Account created with temporary password

2. **System Sends Email**
   - Verification email sent automatically via configured service
   - Contains unique signed verification link
   - Link format: `/email/verify/{user_id}/{hash}`

3. **User Receives Email**
   - Subject: "Verify Email Address"
   - Body: "Please click the button below to verify your email address"
   - Button: "Verify Email Address"

4. **User Clicks Verification Link**
   - Redirected to SmartHarvest
   - Email marked as verified
   - Redirected to password setup page

5. **User Sets Password**
   - Creates secure password
   - Account fully activated
   - Redirected to dashboard

### Email Template

The default Laravel email verification notification looks like:

```
SmartHarvest

Verify Email Address

Please click the button below to verify your email address.

[Verify Email Address]

If you did not create an account, no further action is required.

Regards,
SmartHarvest
```

To customize, create: `resources/views/vendor/notifications/email.blade.php`

---

## Production Recommendations

### 1. Use Real Domain
Update `MAIL_FROM_ADDRESS` to your actual domain:
```dotenv
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
```

### 2. Set Proper APP_URL
```dotenv
APP_URL=https://yourdomain.com
```
This ensures verification links point to correct domain.

### 3. Enable HTTPS
Verification links use APP_URL. Use HTTPS in production.

### 4. Monitor Email Delivery
- **Brevo**: Dashboard → Statistics
- **Mailgun**: Dashboard → Sending → Logs
- **Gmail**: Sent folder

### 5. Set Up SPF/DKIM Records
Improves email deliverability and prevents spam filtering.

### 6. Add Error Notifications
```php
// In registration route
try {
    $user->sendEmailVerificationNotification();
} catch (\Exception $e) {
    \Log::error('Verification email failed', [
        'user_id' => $user->id,
        'email' => $user->email,
        'error' => $e->getMessage()
    ]);
    
    return back()->with('warning', 
        'Account created but verification email failed. Please contact support.'
    );
}
```

---

## Quick Start Checklist

- [ ] Choose email service (Brevo recommended)
- [ ] Create account and get SMTP credentials
- [ ] Update `.env` with SMTP settings
- [ ] Run `php artisan config:clear`
- [ ] Test by registering with your real email
- [ ] Check inbox (and spam) for verification email
- [ ] Click verification link
- [ ] Set password
- [ ] Verify you can login

---

## Current Configuration

**File**: `.env`

The configuration has been pre-set for Brevo with placeholder values:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your-brevo-email@example.com
MAIL_PASSWORD=your-brevo-smtp-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@smartharvest.local"
MAIL_FROM_NAME="SmartHarvest"
```

**Action Required**: Replace `MAIL_USERNAME` and `MAIL_PASSWORD` with your actual Brevo credentials.

---

## Testing Without Real Email (Development)

If you want to test without setting up SMTP:

```dotenv
MAIL_MAILER=log
```

Emails will be written to `storage/logs/laravel.log` and you can copy the verification URL from there.

---

## Support Resources

- **Brevo Documentation**: https://developers.brevo.com/docs
- **Mailgun Documentation**: https://documentation.mailgun.com/
- **Laravel Mail Documentation**: https://laravel.com/docs/11.x/mail
- **Gmail SMTP**: https://support.google.com/mail/answer/7126229

---

## Summary

✅ **Email verification only works during registration**  
✅ **Brevo (Sendinblue) configured as default** (update credentials)  
✅ **Alternative options documented** (Mailgun, Gmail)  
✅ **Troubleshooting guide included**  
✅ **Production best practices provided**  

**Next Step**: Get your Brevo SMTP credentials and update `.env` file, then test registration!
