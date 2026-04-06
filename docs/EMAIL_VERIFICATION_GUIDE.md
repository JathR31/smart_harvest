# Email Verification System - SmartHarvest

## Overview
Email verification has been successfully implemented in the SmartHarvest login system. Users must verify their email addresses before they can access the application.

## How It Works

### 1. User Registration
- When a new farmer registers via `/register`, they are immediately logged in
- A verification email is sent to their email address
- They are redirected to the email verification notice page

### 2. Login Flow
- Users enter their credentials on the login page
- If credentials are valid but email is NOT verified:
  - User is logged out immediately
  - Error message displayed: "Please verify your email address before logging in"
- If credentials are valid AND email is verified:
  - User is logged in successfully
  - Redirected to their dashboard (Admin or Farmer)

### 3. Email Verification Process
- User clicks the verification link in their email
- Link format: `/email/verify/{user_id}/{hash}`
- System validates:
  - User ID matches
  - Hash matches the user's email (prevents tampering)
  - Link is signed (prevents unauthorized access)
- Once verified, `email_verified_at` timestamp is set
- User is redirected to their appropriate dashboard

## Routes Added

```php
// Show verification notice page (auth required)
GET /email/verify → verification.notice

// Handle verification link click (auth + signed link required)
GET /email/verify/{id}/{hash} → verification.verify

// Resend verification email (auth + rate limited: 6 attempts per minute)
POST /email/resend → verification.resend
```

## Files Modified

### 1. `app/Models/User.php`
- Implemented `Illuminate\Contracts\Auth\MustVerifyEmail` interface
- Enables: `hasVerifiedEmail()`, `markEmailAsVerified()`, `sendEmailVerificationNotification()`

### 2. `routes/web.php`
- **Login route**: Added email verification check after successful authentication
- **Registration route**: Sends verification email and redirects to notice page
- **New routes**: verification.notice, verification.verify, verification.resend

### 3. `resources/views/auth/verify-email.blade.php` (NEW)
- Beautiful UI matching SmartHarvest design
- Shows user's email address
- "Resend Verification Email" button
- Logout option
- Success/error message display

### 4. `.env`
- Updated `MAIL_FROM_ADDRESS` to `noreply@smartharvest.local`
- Mail driver set to `log` (emails written to `storage/logs/laravel.log`)

## Email Configuration

### Current Setup (Development)
```dotenv
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@smartharvest.local"
MAIL_FROM_NAME="SmartHarvest"
```

Verification emails are written to: `storage/logs/laravel.log`

### Production Setup (Future)
For production, update `.env` to use real SMTP service:

**Option 1: Gmail SMTP**
```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@smartharvest.gov.ph"
MAIL_FROM_NAME="SmartHarvest"
```

**Option 2: Mailtrap (Testing)**
```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_FROM_ADDRESS="noreply@smartharvest.local"
MAIL_FROM_NAME="SmartHarvest"
```

**Option 3: SendGrid/Mailgun (Production)**
```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@smartharvest.gov.ph"
MAIL_FROM_NAME="SmartHarvest"
```

## Testing the System

### 1. Register a New User
1. Go to `/register`
2. Fill in the registration form
3. Submit the form
4. You'll be redirected to `/email/verify` (verification notice page)

### 2. Check the Verification Email
Since `MAIL_MAILER=log`, check the log file:
```powershell
Get-Content storage\logs\laravel.log -Tail 100
```

Look for the verification link in the log output.

### 3. Test Login Before Verification
1. Log out if logged in
2. Go to `/login`
3. Enter the unverified user's credentials
4. Click "Sign In"
5. You should see: "Please verify your email address before logging in"

### 4. Verify Email
1. Copy the verification URL from the log file
2. Paste it in your browser
3. You should be redirected to the dashboard
4. The user's `email_verified_at` field is now set in the database

### 5. Test Login After Verification
1. Log out
2. Go to `/login`
3. Enter the same credentials
4. Click "Sign In"
5. You should successfully log in and reach your dashboard

## Database Schema

The `users` table includes:
```sql
email_verified_at TIMESTAMP NULL
```

- `NULL` = Email not verified
- `TIMESTAMP` = Email verified at this date/time

## Security Features

1. **Signed URLs**: Verification links use Laravel's signed URL feature to prevent tampering
2. **Hash Validation**: Email hash is verified to ensure link is for the correct user
3. **Rate Limiting**: Resend feature limited to 6 attempts per minute per user
4. **Auth Guard**: Verification routes require authentication
5. **Automatic Logout**: Unverified users are logged out immediately after login attempt

## User Experience

### For New Users
1. Register → Receive email → Click link → Access dashboard

### For Returning Users
- Verified users: Normal login flow
- Unverified users: Shown error message with instructions

### Resend Email
- Users can request a new verification email from the verification notice page
- Throttled to prevent abuse

## Admin Considerations

### Manually Verifying Users
If needed, admin can manually verify a user via Tinker:
```php
php artisan tinker
$user = User::where('email', 'user@example.com')->first();
$user->markEmailAsVerified();
```

### Checking Verification Status
```php
$user = User::find(1);
$user->hasVerifiedEmail(); // true or false
$user->email_verified_at; // timestamp or null
```

## Troubleshooting

### Issue: "Please verify your email" error but email is verified
**Solution**: Check database:
```sql
SELECT id, email, email_verified_at FROM users WHERE email = 'user@example.com';
```
If `email_verified_at` is NULL, manually verify the user.

### Issue: Verification email not sent
**Solution**: 
1. Check `.env` has correct `MAIL_MAILER` setting
2. Check `storage/logs/laravel.log` for email content
3. Verify `MAIL_FROM_ADDRESS` is set
4. Clear config cache: `php artisan config:clear`

### Issue: Verification link doesn't work
**Solution**:
1. Ensure `APP_URL` in `.env` is correct
2. Check if link is signed correctly
3. Verify user ID and hash match

### Issue: Can't resend verification email
**Solution**: You may be rate limited. Wait 1 minute and try again.

## Future Enhancements

- [ ] Custom email template with SmartHarvest branding
- [ ] Email verification expiration (e.g., 24 hours)
- [ ] Admin panel to view/manage unverified users
- [ ] Reminder emails for unverified accounts after X days
- [ ] SMS verification as alternative to email

## Summary

✅ Email verification fully implemented  
✅ User model implements MustVerifyEmail  
✅ Login route checks verification status  
✅ Registration sends verification email  
✅ Verification routes created (notice, verify, resend)  
✅ Beautiful verification UI created  
✅ Mail configuration ready for testing  
✅ Security measures in place (signed URLs, rate limiting)  
✅ Database schema supports verification  

Users must now verify their email addresses before accessing the SmartHarvest application.
