# SMS OTP Quick Start Guide

## 🚀 Get Started in 5 Minutes

### Step 1: Run Database Migration
```bash
php artisan migrate
```

### Step 2: Get Semaphore API Key
1. Go to https://semaphore.co/
2. Sign up for free account
3. Purchase ₱500 credits (~625 SMS)
4. Copy API key from dashboard

### Step 3: Configure Environment
Add to `.env`:
```env
SEMAPHORE_API_KEY=your_actual_api_key_here
```

### Step 4: Test It Out
1. Visit: http://localhost:8000/register
2. Fill form with phone number: `0917 123 4567`
3. Select "SMS Verification"
4. Check phone for OTP
5. Enter code and verify

**That's it!** 🎉

---

## 📁 Key Files to Know

### Backend
- `app/Services/SMSService.php` - SMS sending logic
- `routes/web.php` - OTP verification routes (lines ~2820-2950)
- `app/Models/User.php` - User verification methods

### Frontend
- `resources/views/register.blade.php` - Registration form
- `resources/views/verify-otp.blade.php` - OTP verification page

### Documentation
- `SMS_OTP_SETUP.md` - Complete setup guide
- `SMS_OTP_IMPLEMENTATION_SUMMARY.md` - What was implemented

---

## 🧪 Quick Test Checklist

- [ ] Register with SMS verification
- [ ] Receive OTP via SMS
- [ ] Enter correct OTP → Success
- [ ] Enter wrong OTP → Error message
- [ ] Click "Resend Code" → New OTP
- [ ] Wait 10 min → OTP expires
- [ ] Click "Switch to Email" → Email verification

---

## 💡 Common Issues

### SMS Not Sending?
**Check:**
1. SEMAPHORE_API_KEY in .env
2. Credits in Semaphore account
3. Phone format: must start with 9
4. Laravel logs: `storage/logs/laravel.log`

### Database Error?
```bash
php artisan migrate:fresh --seed
```

### Need to Reset OTP?
```sql
UPDATE users SET otp_code=NULL, otp_expires_at=NULL, otp_attempts=0 WHERE email='user@example.com';
```

---

## 📞 Support

- Semaphore Support: hello@semaphore.co
- Documentation: `SMS_OTP_SETUP.md`
- Implementation Details: `SMS_OTP_IMPLEMENTATION_SUMMARY.md`

---

**Ready to go!** For detailed information, see `SMS_OTP_SETUP.md`
