# Email Verification Quick Setup - SmartHarvest

## Current Status
✅ Email verification configured for **registration only**  
✅ Verification emails sent automatically when users register  
✅ Brevo (Sendinblue) SMTP configured as default  
❗ **Action Required**: Add your Brevo credentials to `.env`

---

## Quick Setup (5 Minutes)

### Step 1: Get Free Brevo Account
1. Visit: https://www.brevo.com/
2. Sign up (free - 300 emails/day)
3. Verify your email

### Step 2: Get SMTP Key
1. Login to Brevo
2. Go to: **Settings** → **SMTP & API**
3. Click **SMTP** tab
4. Click **Generate a new SMTP key**
5. Copy the key

### Step 3: Update `.env` File
Open: `c:\xampp\htdocs\dashboard\SmartHarvest\.env`

Update these two lines:
```dotenv
MAIL_USERNAME=your-brevo-email@example.com    # Your Brevo login email
MAIL_PASSWORD=your-generated-smtp-key         # The key you just copied
```

### Step 4: Clear Cache
```powershell
php artisan config:clear
```

### Step 5: Test Registration
1. Go to: http://localhost/dashboard/SmartHarvest/public/register
2. Register with **your real email address**
3. Check your inbox for verification email
4. Click "Verify Email Address" button
5. Set your password
6. Done! ✅

---

## Current .env Configuration

Location: `SmartHarvest\.env` (lines 50-60)

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your-brevo-email@example.com    ← UPDATE THIS
MAIL_PASSWORD=your-brevo-smtp-key             ← UPDATE THIS
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@smartharvest.local"
MAIL_FROM_NAME="SmartHarvest"
```

---

## Alternative: Use Gmail (Quick Test)

If you just want to test quickly with Gmail:

### Get Gmail App Password
1. Go to: https://myaccount.google.com/apppasswords
2. Generate app password (16 characters)

### Update `.env`
```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop    # Remove spaces: abcdefghijklmnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-gmail@gmail.com"
MAIL_FROM_NAME="SmartHarvest"
```

**Note**: Gmail limited to 500 emails/day, may go to spam.

---

## How It Works

### Registration Flow
```
1. User fills registration form (name, email, municipality)
   ↓
2. System creates account with temporary password
   ↓
3. System sends verification email automatically
   ↓
4. User clicks link in email
   ↓
5. Email verified → redirected to password setup
   ↓
6. User sets password → account fully activated
```

### Key Points
- ✅ Verification email sent **only during registration**
- ✅ Users **cannot login** until email verified
- ✅ Users set password **after** verification
- ✅ One-time verification per account
- ✅ Resend option available on verification notice page

---

## Verification Email Content

**Subject**: Verify Email Address

**Body**:
```
SmartHarvest

Verify Email Address

Please click the button below to verify your email address.

[Verify Email Address]

If you did not create an account, no further action is required.

Regards,
SmartHarvest
```

---

## Testing Checklist

- [ ] Updated `.env` with SMTP credentials
- [ ] Ran `php artisan config:clear`
- [ ] Registered test user with real email
- [ ] Received verification email in inbox
- [ ] Clicked verification link successfully
- [ ] Set password on password setup page
- [ ] Logged in with new credentials
- [ ] Accessed dashboard

---

## Troubleshooting

### Email not received?
1. Check spam/junk folder
2. Verify `.env` credentials are correct
3. Run `php artisan config:clear`
4. Check `storage/logs/laravel.log` for errors

### "Connection refused" error?
```powershell
# Test SMTP connection
Test-NetConnection smtp-relay.brevo.com -Port 587
```
If blocked by firewall, try port 465:
```dotenv
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

### Credentials invalid?
1. Regenerate SMTP key in Brevo dashboard
2. Update `MAIL_PASSWORD` in `.env`
3. Clear config cache

### Want to test without email?
Temporarily use log driver:
```dotenv
MAIL_MAILER=log
```
Check verification link in: `storage/logs/laravel.log`

---

## Production Tips

### Before Going Live:
1. ✅ Use real domain in `MAIL_FROM_ADDRESS`
2. ✅ Set correct `APP_URL` in `.env`
3. ✅ Enable HTTPS for verification links
4. ✅ Add SPF/DKIM records (from Brevo)
5. ✅ Monitor email delivery in Brevo dashboard

### Brevo Dashboard
Monitor sent emails at: https://app.brevo.com/email-campaigns/statistics

---

## Files Changed

1. **`.env`** - SMTP configuration updated
2. **`routes/farmer_api.php`** - Removed standalone email API endpoints
3. **`routes/web.php`** - Registration sends verification (unchanged)
4. **`bootstrap/app.php`** - Removed unnecessary CSRF exemptions

---

## Routes

### Registration Routes (Active)
- `GET /register` - Show registration form
- `POST /register` - Handle registration + send verification email

### Email Verification Routes (Active)
- `GET /email/verify` - Show verification notice
- `GET /email/verify/{id}/{hash}` - Handle verification link click
- `POST /email/resend` - Resend verification email (from notice page)

### Password Setup Routes (Active)
- `GET /setup-password` - Show password setup form
- `POST /setup-password` - Handle password submission

---

## Need Help?

📖 **Full Setup Guide**: `EMAIL_SERVICE_SETUP.md`  
📧 **Brevo Support**: https://help.brevo.com/  
📚 **Laravel Mail Docs**: https://laravel.com/docs/11.x/mail

---

## Summary

✅ **Email verification limited to registration only** (no standalone API)  
✅ **Brevo SMTP configured** (just add your credentials)  
✅ **Alternative options available** (Gmail, Mailgun)  
✅ **Automatic email sending on registration**  
✅ **Complete setup documentation provided**  

**Ready to use!** Just add your Brevo credentials to `.env` and test registration.
