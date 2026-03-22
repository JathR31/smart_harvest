# Sync Local Data → Laravel Cloud (Step-by-Step Guide)

## 🎯 Overview

This guide will help you migrate all your local XAMPP data to your Laravel Cloud production database.

---

## 📋 Prerequisites

✅ LocalXAMPP MySQL running  
✅ Laravel Cloud credentials (you already have these)  
✅ Git repository set up  

---

## Step 1: Find Your Public IP Address

Your local machine needs to be whitelisted on Laravel Cloud to connect remotely.

### Get Your IP:

**Method A: Using PowerShell**

```powershell
$ipaddr = (Invoke-WebRequest -UseBasicParsing -Uri "https://ipinfo.io/json" | ConvertFrom-Json).ip
Write-Host "Your public IP: $ipaddr"
```

**Method B: Visit a website**
- Go to: https://www.whatismyipaddress.com
- Copy the IPv4 address

**Example output:**
```
Your public IP: 203.0.113.42
```

**Write it down:** `_________________` (you'll need this in Step 2)

---

## Step 2: Add IP to Laravel Cloud Whitelist

In **Laravel Cloud Dashboard:**

1. Go to: https://cloud.laravel.com
2. Click your **smart_harvest** project
3. Go to **Database** section
4. Look for **IP Whitelist** or **Network Access** or **Firewall Rules**
5. Click **Add IP** or similar button
6. Enter your IP from Step 1
   - Example: `203.0.113.42/32`
   - Or use: `0.0.0.0/0` (allows all IPs, temporary for testing)
7. Click **Save** or **Apply**

⏱️ **Wait 2-3 minutes** for changes to take effect

---

## Step 3: Test Connection

Now test if your local machine can connect to Laravel Cloud database:

### Using PowerShell:

```powershell
C:\xampp\mysql\bin\mysql.exe `
  -h db-a1587856-038a-4a04-ad0c-2a84753adb9b.ap-southeast-1.public.db.laravel.cloud `
  -u uneugpobxruqa95g `
  -p"cQrOnGXpNT7arHKVy8mW" `
  -e "SELECT 1 AS test_connection;"
```

**Expected output:**
```
test_connection
1
```

❌ **If it fails:**
- Check IP whitelist was added correctly
- Wait another minute (propagation delay)
- Try again
- If still fails, use `0.0.0.0/0` temporarily

✅ **If it works:** Continue to Step 4!

---

## Step 4: Check What Data You Have Locally

Let's see what data exists in your local XAMPP database:

```powershell
cd c:\xampp\htdocs\smart_harvest

# Check how many users you have
C:\xampp\mysql\bin\mysql.exe -u root smartharvest -e "SELECT COUNT(*) AS user_count FROM users;"

# Check crops
C:\xampp\mysql\bin\mysql.exe -u root smartharvest -e "SELECT COUNT(*) AS crop_count FROM crops;"

# Check market prices
C:\xampp\mysql\bin\mysql.exe -u root smartharvest -e "SELECT COUNT(*) AS price_count FROM market_prices;"
```

**Example output:**
```
user_count
5

crop_count
12

price_count
42
```

If all are 0, you have no data to migrate. Skip to Step 6.

---

## Step 5: Export Local Database & Import to Cloud

### 5A: Export Your Local Database

```powershell
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
C:\xampp\mysql\bin\mysqldump.exe -u root smartharvest | Out-File -FilePath "backup_local_$timestamp.sql" -Encoding UTF8
```

**This creates a file like:** `backup_local_20260322_143025.sql`

### 5B: Import to Laravel Cloud

```powershell
# Use the same timestamp filename from above
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$backupFile = "backup_local_$timestamp.sql"

# Read the backup and import to cloud
Get-Content $backupFile | C:\xampp\mysql\bin\mysql.exe `
  -h db-a1587856-038a-4a04-ad0c-2a84753adb9b.ap-southeast-1.public.db.laravel.cloud `
  -u uneugpobxruqa95g `
  -p"cQrOnGXpNT7arHKVy8mW" `
  smartharvest
```

**Expected:** No errors shown = successful import ✅

### 5C: Verify Import

```powershell
C:\xampp\mysql\bin\mysql.exe `
  -h db-a1587856-038a-4a04-ad0c-2a84753adb9b.ap-southeast-1.public.db.laravel.cloud `
  -u uneugpobxruqa95g `
  -p"cQrOnGXpNT7arHKVy8mW" `
  smartharvest `
  -e "SELECT COUNT(*) AS user_count FROM users;"
```

**Should show your local user count now in Cloud!** ✅

---

## Step 6: Push Code to Trigger Seeder

The seeder will ensure any new data is properly synced:

```powershell
cd c:\xampp\htdocs\smart_harvest

# Stage all changes
git add .

# Commit
git commit -m "Data sync: local data migrated to Laravel Cloud production database"

# Push to GitHub
git push origin main
```

**Expected output:**
```
To https://github.com/JathR31/smart_harvest.git
   db044c4..xxxxx main -> main
```

---

## Step 7: Monitor Deployment

Laravel Cloud will automatically deploy when it detects your push.

### Check Deployment Status:

1. Go to: https://cloud.laravel.com
2. Click **smart_harvest** project
3. Go to **Deployments** tab
4. Watch for green checkmark ✅

**Timeline:**
- 0-1 min: Deployment starts
- 1-3 min: Code builds
- 3-5 min: Database migrations run
- 5-10 min: Seeder runs & finishes
- Total: ~10 minutes

### Check Deployment Logs:

If you see any errors:
1. Click the deployment
2. Scroll down to **Logs**
3. Look for error messages

---

## Step 8: Verify on Live Site

Once deployment is complete:

1. Visit your live Laravel Cloud site
2. Try to login with a user from your local database
3. Check that crops/data appear correctly
4. Verify everything works

✅ **Success!** Your local data is now live in production!

---

## 🆘 Troubleshooting

### ❌ Error: "Access denied for user"

**Solution:**
- Wait 3-5 minutes after adding IP to whitelist
- Restart your MySQL connection
- Try again

### ❌ Error: "Can't connect to MySQL server"

**Solution:**
- Check IP whitelist is active
- Verify IP address is correct
- Try using `0.0.0.0/0` temporarily

### ❌ Error: "Table doesn't exist"

**Solution:**
- Make sure Laravel Cloud migrations ran first
- Check deployment logs
- Run migrations manually on cloud console

### ❌ Import says "connection timeout"

**Solution:**
- Your IP might not be whitelisted yet
- Wait 5 minutes after adding to whitelist
- Try smaller exports first

---

## 📝 Quick Command Reference

**Find your IP:**
```powershell
(Invoke-WebRequest -UseBasicParsing -Uri "https://ipinfo.io/json" | ConvertFrom-Json).ip
```

**Test Cloud connection:**
```powershell
C:\xampp\mysql\bin\mysql.exe -h db-a1587856-038a-4a04-ad0c-2a84753adb9b.ap-southeast-1.public.db.laravel.cloud -u uneugpobxruqa95g -p"cQrOnGXpNT7arHKVy8mW" -e "SELECT 1;"
```

**Export local database:**
```powershell
C:\xampp\mysql\bin\mysqldump.exe -u root smartharvest > local_backup.sql
```

**Import to Cloud:**
```powershell
Get-Content local_backup.sql | C:\xampp\mysql\bin\mysql.exe -h db-a1587856-038a-4a04-ad0c-2a84753adb9b.ap-southeast-1.public.db.laravel.cloud -u uneugpobxruqa95g -p"cQrOnGXpNT7arHKVy8mW" smartharvest
```

**Commit and push:**
```powershell
git add . && git commit -m "Data sync" && git push origin main
```

---

## ✅ Checklist

Before each step, mark it complete:

- [ ] Step 1: Found public IP
- [ ] Step 2: Added IP to Laravel Cloud whitelist
- [ ] Step 3: Tested connection successfully
- [ ] Step 4: Checked local data exists
- [ ] Step 5A: Exported local database
- [ ] Step 5B: Imported to Cloud
- [ ] Step 5C: Verified data in Cloud
- [ ] Step 6: Pushed code to GitHub
- [ ] Step 7: Monitored deployment (completed)
- [ ] Step 8: Verified on live site

---

## 🎉 Done!

Your local data is now synced to Laravel Cloud production! Users on your live site can see all the data you had locally.

**Next time you make local changes:**
1. Update your local database
2. Export: `mysqldump -u root smartharvest > backup.sql`
3. Import to Cloud
4. Push code: `git push origin main`

---

**Need help?** Check the troubleshooting section above or review each step carefully.

**Last Updated:** March 22, 2026
