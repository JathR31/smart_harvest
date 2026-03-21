# SmartHarvest Data Synchronization Guide

## 🎯 Overview

This guide shows you how to synchronize data between your **local development environment (XAMPP)** and **production (Laravel Cloud/Render)**.

---

## 📋 Table of Contents

1. [Initial Setup](#initial-setup)
2. [Sync Local → Production](#sync-local--production)
3. [Sync Production → Local](#sync-production--local)
4. [Troubleshooting](#troubleshooting)

---

## Initial Setup

### Step 1: Enable the Migration Seeder

The seeder `MigrateProductionDataSeeder.php` is already created. It will:
- ✅ Migrate all users from local to production
- ✅ Migrate all crops data
- ✅ Migrate all market prices
- ✅ Migrate all announcements
- ✅ Skip duplicates (so it's safe to run multiple times)

### Step 2: Push Initial Code

```bash
cd c:\xampp\htdocs\smart_harvest
git add .
git commit -m "Initial setup: add data migration seeder"
git push origin main
```

---

## 🔄 Sync Local → Production

**Use this when:** You made changes to local data and want to push them live to production users.

### Quick Method (Recommended)

**Option A: Using PowerShell Script (Windows)**

```powershell
cd c:\xampp\htdocs\smart_harvest
.\sync-production.ps1
```

Then select option `1` (LOCAL → PRODUCTION)

The script will:
1. ✅ Backup your local database to `backup_local_YYYYMMDD_HHMMSS.sql`
2. ✅ Commit all changes to git
3. ✅ Push to GitHub
4. ✅ Your CI/CD pipeline automatically runs the migration seeder on production

### Manual Method

If the script doesn't work, do this manually:

```bash
# Step 1: Backup local database first!
C:\xampp\mysql\bin\mysqldump.exe -u root smartharvest > backup_local.sql

# Step 2: Commit and push
git add .
git commit -m "Data sync: migrate local changes to production"
git push origin main

# Step 3: Wait for deployment
# Check: https://dashboard.render.com → smartharvest → Logs
```

### What Happens in Production?

When your code is pushed:

1. **GitHub Actions** automatically triggers
2. **Render CI/CD pipeline:**
   - Pulls latest code
   - Runs: `php artisan migrate --force`
   - Runs: `php artisan db:seed --class=MigrateProductionDataSeeder --force`
3. **Result:** All new/updated data appears in production

⏱️ **Timeline:** 5-10 minutes for full deployment

---

## 🔄 Sync Production → Local

**Use this when:** Users registered/added data on production and you want it in local for testing.

### Quick Method (Recommended)

**Option A: Using PowerShell Script (Windows)**

```powershell
cd c:\xampp\htdocs\smart_harvest
.\sync-production.ps1
```

Then select option `2` (PRODUCTION → LOCAL)

The script will:
1. Ask for production database credentials
2. Download production database
3. Import to local XAMPP database
4. You can now test/develop with real production data

### Get Production Credentials

From **Render Dashboard:**

1. Go: https://dashboard.render.com
2. Click **smartharvest** → **Info**
3. Under "Connections" find:
   ```
   DB_HOST=dpg-xxxxxxxx.render.com
   DB_USER=smartharvest_user
   DB_PASSWORD=xxxxxxxxxxxxx
   DB_NAME=smartharvest
   ```

### Manual Method

```powershell
# You'll need these from Render Dashboard:
$dbHost = "dpg-xxxxxxxx.render.com"
$dbUser = "smartharvest_user"
$dbPass = "your_password_here"
$dbName = "smartharvest"

# Backup original local database first!
C:\xampp\mysql\bin\mysqldump.exe -u root smartharvest > backup_local_before_sync.sql

# Export from production
C:\xampp\mysql\bin\mysqldump.exe -h $dbHost -u $dbUser -p"$dbPass" $dbName > prod_data.sql

# Import to local
C:\xampp\mysql\bin\mysql.exe -u root smartharvest < prod_data.sql

# Done! Your local now matches production
```

---

## 📊 Workflow Examples

### Example 1: Adding New Users Locally → Production

```
1. Register new users in local XAMPP
2. Run: .\sync-production.ps1 → Choose option 1
3. Users appear in production after 5-10 min
4. They can login on live site
```

### Example 2: Testing with Real Production Data

```
1. Run: .\sync-production.ps1 → Choose option 2
2. Enter production credentials
3. All production users/crops now in your local
4. You can develop new features safely on test data
5. Changes stay local (won't affect production)
```

### Example 3: Bug Fix in Production Data

```
1. Sync production → local (get the problematic data)
2. Fix the data locally (edit through UI or directly)
3. Sync local → production (push fix live)
4. Users see corrected data
```

---

## 🆘 Troubleshooting

### Problem: MySQL command not found

**Solution:** Add XAMPP MySQL to PATH:

```powershell
$env:PATH = "C:\xampp\mysql\bin;$env:PATH"
mysqldump -u root smartharvest > backup.sql
```

### Problem: Access denied for MySQL

**Solution:** Check credentials:

```powershell
# Test connection
C:\xampp\mysql\bin\mysql.exe -u root -e "SELECT 1"

# If password protected:
C:\xampp\mysql\bin\mysql.exe -u root -p smartharvest
# Then enter password
```

### Problem: Render deployment failed

**Check logs:**
1. Go: https://dashboard.render.com
2. Click **smartharvest** service
3. Scroll to **Logs** tab
4. Look for error messages

**Common issues:**
- Missing environment variables (check `.env` on Render)
- Database connection timeout (check credentials)
- Migration errors (look at migration files)

### Problem: Seeder says "Table not found"

**Solution:** Ensure migrations ran first:

```bash
# Login to Render shell
# Then run:
php artisan migrate --force
php artisan db:seed --class=MigrateProductionDataSeeder --force
```

---

## 🔒 Safety Tips

1. **Always backup before syncing:**
   ```powershell
   mysqldump -u root smartharvest > backup_before_sync.sql
   ```

2. **Test locally first:**
   - Make changes locally
   - Test thoroughly
   - THEN sync to production

3. **Never sync development test data to production:**
   - Create separate seeder if testing
   - Clean up before syncing to live users

4. **Keep git history clean:**
   - Meaningful commit messages
   - One logical change per commit
   - Makes rollbacks easier if needed

---

## 📝 Seeder Details

**Location:** `database/seeders/MigrateProductionDataSeeder.php`

**What it migrates:**
- ✅ **Users** - All user accounts (with hashed passwords)
- ✅ **Crops** - All crop monitoring data
- ✅ **Market Prices** - All price history
- ✅ **Announcements** - All admin announcements

**Smart features:**
- Checks for duplicates before inserting
- Skips data that already exists
- Preserves timestamps
- Safe to run multiple times
- Maintains data integrity

**To manually run seeder:**
```bash
php artisan db:seed --class=MigrateProductionDataSeeder
```

---

## 📞 Need Help?

If sync fails:

1. **Check git status:**
   ```bash
   git status
   git log --oneline -5
   ```

2. **Check Render logs:**
   https://dashboard.render.com → smartharvest → Logs

3. **Test database connection:**
   ```bash
   php artisan tinker
   >>> DB::connection()->getPdo()
   // Should not error
   ```

4. **Check migrations:**
   ```bash
   php artisan migrate:status
   ```

---

## ✅ Checklist

Before going live:

- [ ] Tested locally first
- [ ] Backed up production database
- [ ] Backed up local database
- [ ] Ran git push successfully
- [ ] Verified Render deployment finished
- [ ] Tested live site functionality
- [ ] Users can login
- [ ] Data displays correctly
- [ ] No errors in Render logs

---

**Last Updated:** March 21, 2026  
**SmartHarvest Version:** 1.0
