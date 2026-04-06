# SMS Integration Documentation

## Overview
SmartHarvest now supports SMS functionality for:
1. **Account Verification** - Users can choose SMS OTP verification instead of email
2. **Admin Announcements** - Admin officers can send SMS announcements to farmers

## Features Implemented

### 1. SMS Verification (User Registration)
- Users can select SMS or Email verification during registration
- 6-digit OTP code sent via SMS
- 10-minute expiration time
- 5 verification attempts before requiring new OTP
- Option to switch to email verification
- Supports Philippine mobile numbers (+639XXXXXXXXX format)

### 2. Admin SMS Announcements
- Send announcements to all farmers, by municipality, or selected farmers
- 160-character SMS limit
- Live recipient count preview
- Delivery tracking and reporting
- SMS balance monitoring
- Detailed delivery reports

## SMS Provider

**SMS API Philippines**
- Dashboard: https://smsapi.ph/dashboard
- Current API Key: `sk-0df679fa423fe9b837ad7df1`
- Project: SmartHarvest
- User ID: dc44a97557255f98d825
- Email: carboneljahnielrei@gmail.com
- Phone: +639560878368
- Created: 2026-02-13

## Environment Configuration

Add to `.env` file:
```env
# SMS API Philippines (Current Provider)
SMS_API_PHILIPPINES_KEY=sk-0df679fa423fe9b837ad7df1
SMS_SIMULATION_MODE=false
```

## Files Created/Modified

### New Files Created:
1. `app/Services/SMSApiPhilippinesService.php` - Main SMS service
2. `app/Http/Controllers/SMSVerificationController.php` - Handles SMS verification
3. `app/Http/Controllers/SMSAnnouncementController.php` - Handles admin announcements
4. `app/Models/SMSAnnouncement.php` - Announcement model
5. `app/Http/Middleware/AdminMiddleware.php` - Admin access middleware
6. `database/migrations/2026_02_14_create_sms_announcements_table.php` - Announcements table
7. `resources/views/auth/verify-sms.blade.php` - SMS verification page
8. `resources/views/admin/sms-announcements.blade.php` - Announcements list
9. `resources/views/admin/sms-announcements-create.blade.php` - Create announcement

### Modified Files:
1. `routes/web.php` - Added SMS routes
2. `bootstrap/app.php` - Registered admin middleware
3. `.env` - Added SMS API key configuration

## Routes Added

### SMS Verification Routes:
```php
GET  /sms/verify                  - Show SMS verification page
POST /api/sms/send-otp           - Send OTP code
POST /api/sms/verify-otp         - Verify OTP code
POST /api/sms/resend-otp         - Resend OTP code
POST /api/sms/switch-to-email    - Switch to email verification
```

### Admin Announcement Routes:
```php
GET  /admin/sms                  - List announcements
GET  /admin/sms/create           - Create announcement form
POST /admin/sms/send             - Send announcement
POST /admin/sms/preview          - Preview recipients
GET  /admin/sms/{id}             - View announcement details
GET  /admin/sms/balance          - Check SMS balance
```

## Installation Steps

1. **Run Migration:**
   ```bash
   php artisan migrate
   ```

2. **Environment Setup:**
   - Ensure `.env` file has `SMS_API_PHILIPPINES_KEY` configured
   - Verify MySQL service is running

3. **Test SMS Service:**
   ```bash
   php artisan tinker
   ```
   ```php
   $service = new \App\Services\SMSApiPhilippinesService();
   $result = $service->sendOTP('09123456789', '123456');
   var_dump($result);
   ```

## Usage

### For Users (Registration)
1. Navigate to registration page
2. Fill in details including mobile number
3. Choose "SMS Verification" method
4. Submit registration
5. Enter OTP code received via SMS
6. Account verified and set password

### For Admin Officers (Announcements)
1. Login to admin dashboard
2. Navigate to "SMS Announcements" (route: `/admin/sms`)
3. Click "New Announcement"
4. Enter message (max 160 characters)
5. Select recipients:
   - All Farmers
   - By Municipality
   - Selected Farmers
6. Preview recipient count
7. Send announcement
8. View delivery report

## API Methods

### SMSApiPhilippinesService

```php
// Send OTP
sendOTP($phoneNumber, $otpCode)

// Send announcement to multiple recipients
sendAnnouncement($phoneNumbers, $message, $senderName = 'SmartHarvest')

// Send custom message
sendMessage($phoneNumber, $message, $senderName = 'SmartHarvest')

// Check SMS balance
checkBalance()
```

## Database Schema

### sms_announcements table
```sql
- id (bigint)
- sender_id (foreign key to users)
- message (text)
- recipient_type (enum: 'all', 'selected', 'municipality')
- recipient_filter (string, nullable)
- total_recipients (integer)
- sent_count (integer)
- failed_count (integer)
- status (enum: 'pending', 'sent', 'partial', 'failed')
- sent_at (timestamp)
- details (json)
- created_at, updated_at
```

## Phone Number Format

The system accepts Philippine mobile numbers in various formats and normalizes them:
- `09XXXXXXXXX` → `+639XXXXXXXXX`
- `639XXXXXXXXX` → `+639XXXXXXXXX`
- `9XXXXXXXXX` → `+639XXXXXXXXX`
- `+639XXXXXXXXX` → `+639XXXXXXXXX` (no change)

## Error Handling

The SMS service includes comprehensive error handling:
- Invalid phone numbers
- Expired OTP codes
- Rate limiting (max 3 SMS per 10 minutes)
- Failed SMS delivery
- Service unavailability

## Security Features

1. **OTP Expiration** - 10 minutes
2. **Attempt Limiting** - 5 verification attempts
3. **Rate Limiting** - 3 SMS requests per 10 minutes per phone
4. **Phone Masking** - Displays `+639XX XXX X234` format
5. **Admin Only** - Announcements restricted to admin roles

## Testing

### Test SMS Verification:
1. Register with phone number
2. Choose SMS verification
3. Verify OTP code received
4. Test switch to email option
5. Test OTP expiration and resend

### Test Admin Announcements:
1. Login as admin
2. Create announcement
3. Test different recipient types
4. Verify delivery reports
5. Check SMS balance

## Troubleshooting

### SMS Not Sending:
- Check `SMS_API_PHILIPPINES_KEY` in `.env`
- Verify API key is active on dashboard
- Check SMS balance/credits
- Review Laravel logs: `storage/logs/laravel.log`

### Invalid Phone Number:
- Ensure Philippine format (09XXXXXXXXX)
- Must start with 9
- Must be 10 digits (after +63)

### Admin Access Denied:
- Ensure user role is 'Admin', 'DA Admin', or 'is_superadmin'
- Check admin middleware is registered
- Verify user is authenticated

## Next Steps

1. Top up SMS credits on https://smsapi.ph/dashboard
2. Test in production environment
3. Monitor SMS delivery rates
4. Configure SMS templates for different languages
5. Add SMS notification preferences for users

## Support

For SMS API issues:
- Dashboard: https://smsapi.ph/dashboard
- Documentation: https://smsapi.ph/docs

For SmartHarvest issues:
- Check application logs
- Review error messages
- Contact system administrator
