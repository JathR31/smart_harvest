# Email Verification API Documentation

## Overview
API endpoints for sending and managing email verification for user accounts in SmartHarvest.

## Base URL
```
http://localhost/dashboard/SmartHarvest/public
```

---

## Endpoints

### 1. Send Verification Email (By Email Address)

Send email verification to any user account by email address.

**Endpoint:** `POST /api/email/send-verification`

**Rate Limit:** 6 requests per minute

**Authentication:** Not required

**Request Body:**
```json
{
  "email": "user@example.com"
}
```

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Verification email sent successfully",
  "email": "user@example.com",
  "sent_at": "2025-11-21T15:47:13+00:00"
}
```

**Error Response - Email Already Verified (400):**
```json
{
  "status": "error",
  "message": "Email is already verified",
  "email": "user@example.com",
  "verified_at": "2025-11-21T10:30:00.000000Z"
}
```

**Error Response - User Not Found (422):**
```json
{
  "message": "The email field must be a valid email address.",
  "errors": {
    "email": [
      "The selected email is invalid."
    ]
  }
}
```

**Error Response - Send Failed (500):**
```json
{
  "status": "error",
  "message": "Failed to send verification email",
  "error": "Connection timeout"
}
```

**cURL Example:**
```bash
curl -X POST http://localhost/dashboard/SmartHarvest/public/api/email/send-verification \
  -H "Content-Type: application/json" \
  -d '{"email": "farmer@example.com"}'
```

**PowerShell Example:**
```powershell
$body = @{
    email = "farmer@example.com"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost/dashboard/SmartHarvest/public/api/email/send-verification" `
  -Method Post `
  -Body $body `
  -ContentType "application/json"
```

---

### 2. Resend Verification Email (For Authenticated User)

Resend email verification for the currently authenticated user.

**Endpoint:** `POST /api/email/resend-verification`

**Rate Limit:** 6 requests per minute

**Authentication:** Required (Bearer token or session)

**Request Body:** None

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Verification email resent successfully",
  "email": "user@example.com",
  "sent_at": "2025-11-21T15:47:13+00:00"
}
```

**Error Response - Unauthorized (401):**
```json
{
  "status": "error",
  "message": "Unauthorized. Please login first."
}
```

**Error Response - Already Verified (400):**
```json
{
  "status": "error",
  "message": "Your email is already verified",
  "verified_at": "2025-11-21T10:30:00.000000Z"
}
```

**cURL Example:**
```bash
curl -X POST http://localhost/dashboard/SmartHarvest/public/api/email/resend-verification \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -b cookies.txt
```

**PowerShell Example (with session):**
```powershell
$session = New-Object Microsoft.PowerShell.Commands.WebRequestSession

Invoke-RestMethod -Uri "http://localhost/dashboard/SmartHarvest/public/api/email/resend-verification" `
  -Method Post `
  -WebSession $session
```

---

### 3. Check Email Verification Status

Check if an email address is verified and password has been set.

**Endpoint:** `GET /api/email/verification-status`

**Rate Limit:** 20 requests per minute

**Authentication:** Not required

**Query Parameters:**
- `email` (required): Email address to check

**Success Response (200):**
```json
{
  "status": "success",
  "email": "user@example.com",
  "is_verified": true,
  "verified_at": "2025-11-21T10:30:00.000000Z",
  "password_set": true,
  "password_set_at": "2025-11-21T10:35:00.000000Z"
}
```

**Response - Not Verified:**
```json
{
  "status": "success",
  "email": "user@example.com",
  "is_verified": false,
  "verified_at": null,
  "password_set": false,
  "password_set_at": null
}
```

**Error Response - Email Missing (400):**
```json
{
  "status": "error",
  "message": "Email parameter is required"
}
```

**Error Response - User Not Found (404):**
```json
{
  "status": "error",
  "message": "User not found"
}
```

**cURL Example:**
```bash
curl -X GET "http://localhost/dashboard/SmartHarvest/public/api/email/verification-status?email=farmer@example.com"
```

**PowerShell Example:**
```powershell
Invoke-RestMethod -Uri "http://localhost/dashboard/SmartHarvest/public/api/email/verification-status?email=farmer@example.com" `
  -Method Get
```

---

## Rate Limiting

All endpoints are rate-limited to prevent abuse:

| Endpoint | Rate Limit |
|----------|------------|
| `/api/email/send-verification` | 6 requests/minute |
| `/api/email/resend-verification` | 6 requests/minute |
| `/api/email/verification-status` | 20 requests/minute |

**Rate Limit Response (429):**
```json
{
  "message": "Too Many Attempts."
}
```

---

## Usage Examples

### Example 1: Send Verification to New User

```javascript
// JavaScript/Node.js
const response = await fetch('http://localhost/dashboard/SmartHarvest/public/api/email/send-verification', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    email: 'newfarmer@example.com'
  })
});

const data = await response.json();
console.log(data);
// Output: { status: 'success', message: 'Verification email sent successfully', ... }
```

### Example 2: Check Verification Status

```python
# Python
import requests

response = requests.get(
    'http://localhost/dashboard/SmartHarvest/public/api/email/verification-status',
    params={'email': 'farmer@example.com'}
)

data = response.json()
print(f"Email verified: {data['is_verified']}")
print(f"Password set: {data['password_set']}")
```

### Example 3: Resend Verification (Authenticated)

```php
// PHP
$ch = curl_init('http://localhost/dashboard/SmartHarvest/public/api/email/resend-verification');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-CSRF-TOKEN: ' . csrf_token()
]);

$response = curl_exec($ch);
$data = json_decode($response, true);

if ($data['status'] === 'success') {
    echo "Verification email resent to: " . $data['email'];
}
```

---

## Integration with Registration Flow

### Complete Registration Workflow with API

1. **User Registers** (Frontend)
   ```javascript
   POST /register
   Body: { full_name, email, municipality }
   ```

2. **System Sends Verification** (Automatic)
   - User created with temporary password
   - Verification email sent automatically

3. **Check Status** (Optional - Frontend polling)
   ```javascript
   GET /api/email/verification-status?email=user@example.com
   ```

4. **Resend if Needed** (Frontend button)
   ```javascript
   POST /api/email/send-verification
   Body: { email: "user@example.com" }
   ```

5. **User Clicks Link** (Email)
   ```
   GET /email/verify/{id}/{hash}
   → Redirects to /setup-password
   ```

6. **User Sets Password** (Frontend)
   ```javascript
   POST /setup-password
   Body: { password, password_confirmation }
   ```

7. **Verify Complete** (Check via API)
   ```javascript
   GET /api/email/verification-status?email=user@example.com
   Response: { is_verified: true, password_set: true }
   ```

---

## Testing with PowerShell

### Test Script
```powershell
# Variables
$baseUrl = "http://localhost/dashboard/SmartHarvest/public"
$testEmail = "testfarmer@example.com"

# 1. Send verification email
Write-Host "Sending verification email..." -ForegroundColor Yellow
$sendBody = @{ email = $testEmail } | ConvertTo-Json
$sendResult = Invoke-RestMethod -Uri "$baseUrl/api/email/send-verification" `
    -Method Post -Body $sendBody -ContentType "application/json"
Write-Host "Result: $($sendResult.status)" -ForegroundColor Green
Write-Host "Message: $($sendResult.message)" -ForegroundColor Green

# 2. Check verification status
Write-Host "`nChecking verification status..." -ForegroundColor Yellow
$statusResult = Invoke-RestMethod -Uri "$baseUrl/api/email/verification-status?email=$testEmail" `
    -Method Get
Write-Host "Email: $($statusResult.email)" -ForegroundColor Cyan
Write-Host "Verified: $($statusResult.is_verified)" -ForegroundColor Cyan
Write-Host "Password Set: $($statusResult.password_set)" -ForegroundColor Cyan

# 3. Check logs for verification email
Write-Host "`nChecking email logs..." -ForegroundColor Yellow
Get-Content "storage\logs\laravel.log" -Tail 50 | Select-String -Pattern "verify"
```

---

## Error Handling

### Common Error Codes

| Status Code | Meaning | Common Cause |
|-------------|---------|--------------|
| 200 | Success | Request completed successfully |
| 400 | Bad Request | Email already verified |
| 401 | Unauthorized | User not authenticated |
| 404 | Not Found | User email doesn't exist |
| 422 | Unprocessable Entity | Validation failed |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Internal Server Error | Email sending failed |

### Handling Errors in Frontend

```javascript
async function sendVerification(email) {
  try {
    const response = await fetch('/api/email/send-verification', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email })
    });
    
    const data = await response.json();
    
    if (!response.ok) {
      if (response.status === 400) {
        alert('Email is already verified!');
      } else if (response.status === 429) {
        alert('Too many requests. Please wait a minute.');
      } else {
        alert('Error: ' + data.message);
      }
      return;
    }
    
    alert('Verification email sent successfully!');
    
  } catch (error) {
    console.error('Network error:', error);
    alert('Failed to send verification email. Check your connection.');
  }
}
```

---

## Security Notes

1. **Rate Limiting**: All endpoints are throttled to prevent abuse
2. **Email Validation**: Only existing user emails can receive verification
3. **Duplicate Prevention**: Cannot send to already-verified emails
4. **Signed URLs**: Verification links use Laravel's signed URL feature
5. **No Sensitive Data**: API responses don't expose passwords or tokens

---

## Mail Configuration

Ensure your `.env` file is configured for email sending:

```dotenv
# For Testing (logs to file)
MAIL_MAILER=log

# For Production (SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@smartharvest.gov.ph"
MAIL_FROM_NAME="SmartHarvest"
```

---

## Support

For issues or questions:
- Check `storage/logs/laravel.log` for email sending logs
- Verify mail configuration in `.env`
- Ensure user exists in database before sending verification
- Check rate limits if receiving 429 errors

---

## Summary

✅ **3 API endpoints created**
- Send verification by email
- Resend verification for authenticated user  
- Check verification status

✅ **Features**
- Rate limiting (6-20 req/min)
- Error handling
- Status checking
- JSON responses

✅ **Use Cases**
- Admin dashboard to resend verifications
- User profile "Resend Email" button
- Registration flow automation
- Verification status monitoring
