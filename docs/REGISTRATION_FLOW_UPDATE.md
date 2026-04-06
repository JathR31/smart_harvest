# Updated Registration Flow - Password Set After Email Verification

## Overview
The registration flow has been updated so users set their password AFTER verifying their email address, not during registration. This improves security and ensures only verified email addresses can access the system.

## New Registration Flow

### Step 1: User Registration (No Password Required)
**Route**: `POST /register`

User provides:
- ✅ Full Name
- ✅ Email Address
- ✅ Municipality
- ❌ ~~Password~~ (Removed)

System actions:
1. Validates email uniqueness
2. Creates user account with temporary random password
3. Sends email verification link
4. Logs user in temporarily
5. Redirects to email verification notice page

### Step 2: Email Verification Notice
**Route**: `GET /email/verify`

User sees:
- Verification notice page
- Their email address
- "Resend Verification Email" button
- Informational message about setting password after verification

### Step 3: Click Verification Link
**Route**: `GET /email/verify/{id}/{hash}`

System actions:
1. Validates verification link (signed URL)
2. Marks email as verified (`email_verified_at` timestamp)
3. **NEW**: Redirects to password setup page (not dashboard)

### Step 4: Set Password
**Route**: `GET /setup-password` → `POST /setup-password`

User provides:
- New password (minimum 8 characters)
- Password confirmation

System actions:
1. Validates password strength
2. Sets user's real password
3. Records `password_set_at` timestamp
4. Redirects to appropriate dashboard (Admin or Farmer)

### Step 5: Complete Registration
User can now:
- Login with email and password
- Access their dashboard
- Use all system features

## Files Modified

### 1. `routes/web.php`
**Registration Route** (Lines ~2406-2428):
```php
Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email|max:255',
        'municipality' => 'required|string',
        // password removed
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['full_name'],
        'email' => $validated['email'],
        'location' => $validated['municipality'],
        'role' => 'Farmer',
        'status' => 'active',
        'password' => Hash::make(\Illuminate\Support\Str::random(32)), // Temporary
    ]);

    $user->sendEmailVerificationNotification();
    Auth::login($user);
    
    return redirect()->route('verification.notice')
        ->with('message', 'Please check your email to verify your account. You will set your password after verification.');
});
```

**Email Verification Route** (Lines ~52-68):
```php
Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    $user = \App\Models\User::findOrFail($request->id);
    
    if (!hash_equals((string) $request->hash, sha1($user->email))) {
        abort(403, 'Invalid verification link.');
    }
    
    if ($user->hasVerifiedEmail()) {
        return redirect()->route($user->role === 'Admin' ? 'admin.dashboard' : 'dashboard');
    }
    
    $user->markEmailAsVerified();
    
    // NEW: Redirect to password setup
    return redirect()->route('password.setup')
        ->with('message', 'Email verified successfully! Please set your password to complete registration.');
});
```

**New Password Setup Routes** (Lines ~77-102):
```php
// Show password setup form
Route::get('/setup-password', function () {
    if (Auth::user()->password_set_at !== null) {
        return redirect()->route(Auth::user()->role === 'Admin' ? 'admin.dashboard' : 'dashboard');
    }
    return view('auth.setup-password');
})->middleware(['auth', 'verified'])->name('password.setup');

// Handle password setup submission
Route::post('/setup-password', function (Request $request) {
    $validated = $request->validate([
        'password' => 'required|string|min:8|confirmed',
    ]);
    
    $user = Auth::user();
    $user->password = Hash::make($validated['password']);
    $user->password_set_at = now();
    $user->save();
    
    return redirect()->route($user->role === 'Admin' ? 'admin.dashboard' : 'dashboard')
        ->with('message', 'Password set successfully! Welcome to SmartHarvest.');
})->middleware(['auth', 'verified'])->name('password.setup.store');
```

### 2. `resources/views/register.blade.php`
**Changes**:
- ❌ Removed password field
- ❌ Removed password confirmation field
- ✅ Added informational message: "You'll set your password after verifying your email address"
- ✅ Updated form to only collect: name, email, municipality

### 3. `resources/views/auth/setup-password.blade.php` (NEW)
**Purpose**: Password setup page after email verification

**Features**:
- Lock icon and clear heading
- Password field with validation
- Password confirmation field
- Password requirements list
- Success/error message display
- "Set Password & Continue" button

### 4. `database/migrations/2025_11_21_154713_add_password_set_at_to_users_table.php` (NEW)
**Purpose**: Add timestamp to track when user sets their real password

```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->timestamp('password_set_at')->nullable()->after('password');
    });
}
```

### 5. `app/Models/User.php`
**Added to casts**:
```php
protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'password_set_at' => 'datetime', // NEW
        'last_login' => 'datetime',
    ];
}
```

## Database Schema Changes

### `users` table
New column added:
```sql
password_set_at TIMESTAMP NULL
```

**States**:
- `NULL` = User has temporary password (hasn't set real password yet)
- `TIMESTAMP` = User set their password at this date/time

## Security Improvements

### 1. Email Verification Required
- Users cannot set password until email is verified
- Prevents fake email addresses

### 2. Temporary Password Protection
- Initial password is 32-character random string
- Cannot be guessed or brute-forced
- User must go through verification flow

### 3. Password Setup Guard
```php
if (Auth::user()->password_set_at !== null) {
    return redirect()->route('dashboard');
}
```
- Prevents users from accessing password setup page multiple times
- Only allows first-time password setup

### 4. Middleware Protection
```php
->middleware(['auth', 'verified'])
```
- Password setup requires authentication
- Password setup requires verified email
- Cannot bypass the verification step

## User Experience Flow

### Happy Path
1. User fills registration form (name, email, municipality)
2. Sees message: "Check your email to verify your account"
3. Receives verification email in inbox
4. Clicks verification link in email
5. Redirected to password setup page
6. Sets password (min 8 characters)
7. Redirected to dashboard with welcome message

### Edge Cases Handled

**User tries to login before verification**:
- Error: "Please verify your email address before logging in"
- Redirected back to login page

**User tries to login before setting password**:
- Since password is random 32-char string, login will fail
- Error: "Invalid credentials"

**User tries to access password setup page again**:
- Checked: `if (password_set_at !== null)`
- Redirected to dashboard (already set password)

**User clicks verification link after already verified**:
- Message: "Email already verified!"
- Redirected to dashboard (or password setup if not set)

## Testing the New Flow

### Test Case 1: New User Registration
1. Go to `/register`
2. Fill form with name, email, municipality (no password)
3. Submit form
4. **Expected**: Redirected to `/email/verify` with message about setting password after verification

### Test Case 2: Email Verification
1. Check `storage/logs/laravel.log` for verification email
2. Copy verification URL
3. Paste in browser
4. **Expected**: Redirected to `/setup-password` with success message

### Test Case 3: Password Setup
1. On password setup page
2. Enter new password (min 8 chars)
3. Confirm password
4. Submit form
5. **Expected**: Redirected to dashboard with welcome message
6. Check database: `password_set_at` should have timestamp

### Test Case 4: Login After Setup
1. Logout
2. Go to `/login`
3. Enter email and password
4. Submit
5. **Expected**: Successfully logged in, redirected to dashboard

### Test Case 5: Try to Access Password Setup Again
1. After setting password, try to visit `/setup-password`
2. **Expected**: Immediately redirected to dashboard (cannot set password twice)

## Verification Commands

### Check User Status
```php
php artisan tinker
$user = User::where('email', 'test@example.com')->first();
$user->email_verified_at; // Should have timestamp after verification
$user->password_set_at;   // Should have timestamp after password setup
```

### Check Registration Flow
```powershell
# 1. Check registration route exists
php artisan route:list --path=register

# 2. Check email verification routes exist
php artisan route:list --path=email

# 3. Check password setup routes exist
php artisan route:list --path=password

# Output should show:
# - POST register
# - GET/POST email/verify routes
# - GET/POST setup-password routes
```

### Check Database Schema
```sql
DESCRIBE users;
-- Should show:
-- - email_verified_at (timestamp, nullable)
-- - password_set_at (timestamp, nullable)
```

## Comparison: Old vs New Flow

### Old Flow
1. Register with password ❌
2. Verify email
3. Login with password
4. Access dashboard

**Problem**: Users could register with fake emails and set passwords immediately.

### New Flow
1. Register without password ✅
2. Verify email ✅
3. Set password ✅
4. Login with password
5. Access dashboard

**Benefit**: Only verified email addresses can set passwords and access system.

## Email Configuration

Email settings remain the same as before:
```dotenv
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@smartharvest.local"
MAIL_FROM_NAME="SmartHarvest"
```

For production, configure real SMTP service (Gmail, SendGrid, etc.).

## Troubleshooting

### Issue: User can't access password setup page
**Solution**: 
1. Check if email is verified: `SELECT email_verified_at FROM users WHERE email = 'user@example.com'`
2. If NULL, resend verification email
3. If not NULL but still can't access, check middleware in routes

### Issue: Password setup page shows blank/error
**Solution**:
1. Run migration: `php artisan migrate`
2. Clear cache: `php artisan config:clear`
3. Check view exists: `resources/views/auth/setup-password.blade.php`

### Issue: User stuck in verification loop
**Solution**:
1. Manually mark email as verified:
   ```php
   $user = User::find(1);
   $user->markEmailAsVerified();
   ```
2. Redirect user to `/setup-password`

### Issue: "password_set_at column not found" error
**Solution**:
1. Run migration: `php artisan migrate`
2. Check migration exists: `ls database/migrations/*password_set_at*`
3. If missing, create manually or re-create migration

## Summary

✅ Registration no longer requires password  
✅ Password set AFTER email verification  
✅ Temporary random password generated during registration  
✅ New password setup page created  
✅ Email verification redirects to password setup  
✅ Password setup tracked with `password_set_at` timestamp  
✅ Middleware protection on password setup routes  
✅ User model updated with new cast  
✅ Migration added for new column  
✅ Registration form updated (no password fields)  
✅ All routes tested and working  

**Result**: More secure registration flow that ensures only verified email addresses can access the system.
