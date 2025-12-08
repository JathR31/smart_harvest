# SmartHarvest - FREE Tier Deployment Guide

## 🎉 Deploy SmartHarvest for $0/month

### Free Tier Overview

| Service | Cost | Limitations | Good For |
|---------|------|-------------|----------|
| Render Web (Free) | $0 | Spins down after 15min inactivity, 750hrs/mo | Testing, demos, portfolio |
| PostgreSQL (Free) | $0 | 256MB, 90-day expiry (renewable) | Development, testing |
| **Total** | **$0** | Cold start: 30-60s after inactivity | Low-traffic apps |

### ⚠️ Free Tier Limitations
- **Cold Starts**: First request after 15min takes 30-60 seconds
- **Database Expiry**: Free PostgreSQL expires after 90 days (you can create a new one)
- **Build Time**: 500 minutes/month (usually enough)
- **Bandwidth**: 100GB/month outbound
- **Not Suitable For**: Production apps with consistent traffic

### ✅ Perfect For
- Development and testing
- Student projects and portfolios
- Demo applications
- Low-traffic personal projects
- Proof of concepts

---

## 🚀 Step-by-Step Deployment

### Prerequisites
- GitHub account with SmartHarvest repository
- Render.com account (free)
- Email service (Gmail app password - free)
- Semaphore SMS account (pay-as-you-go)
- OpenWeather API key (free tier)

---

### Step 1: Prepare Your Repository (2 minutes)

```bash
# Make sure all files are committed
cd SmartHarvest
git add .
git commit -m "Prepare for free tier deployment"
git push origin main
```

---

### Step 2: Create Free PostgreSQL Database (3 minutes)

1. **Go to Render Dashboard**: https://dashboard.render.com/

2. **Create Database**:
   - Click **"New +"** → Select **"PostgreSQL"**

3. **Configure**:
   ```
   Name: smartharvest-db
   Database: smartharvest
   User: smartharvest
   Region: Singapore (closest to Philippines)
   PostgreSQL Version: 16 (latest)
   Plan: Free (256MB, expires in 90 days)
   ```

4. **Create Database** → Wait 1-2 minutes

5. **Copy Connection Info**:
   - Go to database dashboard
   - Find **"Internal Database URL"**:
   ```
   postgresql://smartharvest:PASS@dpg-xxxxx-singapore.render.com/smartharvest_xxxx
   ```
   - Also copy individual fields:
     - **Hostname**: `dpg-xxxxx-singapore.render.com`
     - **Port**: `5432`
     - **Database**: `smartharvest_xxxx`
     - **Username**: `smartharvest_xxxx_user`
     - **Password**: `[copy this]`

📋 **Save these details** - you'll need them in Step 4!

---

### Step 3: Create Free Web Service (3 minutes)

1. **Dashboard** → **"New +"** → **"Web Service"**

2. **Connect Repository**:
   - Click **"Connect a repository"**
   - **Authorize GitHub** if first time
   - Select: `JathR31/smart_harvest`
   - Click **"Connect"**

3. **Configure Service**:
   ```
   Name: smartharvest
   Region: Singapore
   Branch: main
   Runtime: Docker (auto-detected from Dockerfile)
   Instance Type: Free
   ```

4. **Click "Create Web Service"** (Don't deploy yet!)

---

### Step 4: Add Environment Variables (5 minutes)

In your web service dashboard, go to **"Environment"** tab.

#### Required Variables

**Application Settings:**
```env
APP_NAME=SmartHarvest
APP_ENV=production
APP_DEBUG=false
APP_URL=https://smartharvest.onrender.com
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
```

**Database Settings (from Step 2):**
```env
DB_CONNECTION=pgsql
DB_HOST=dpg-xxxxx-singapore.render.com
DB_PORT=5432
DB_DATABASE=smartharvest_xxxx
DB_USERNAME=smartharvest_xxxx_user
DB_PASSWORD=YOUR_DATABASE_PASSWORD
```

**Session/Cache/Queue (use database):**
```env
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

**Email (Gmail - Free):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your.email@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@smartharvest.com
MAIL_FROM_NAME=SmartHarvest
```

**SMS (Semaphore - Pay-as-you-go):**
```env
SEMAPHORE_API_KEY=your_semaphore_api_key
```

**Weather API (OpenWeather - Free tier):**
```env
OPENWEATHER_API_KEY=your_openweather_api_key
```

**ML API (if deployed separately):**
```env
ML_API_URL=https://your-ml-api.onrender.com
```

#### Generate APP_KEY

Run this locally to generate your APP_KEY:
```bash
cd SmartHarvest
php artisan key:generate --show
```

Copy the output (looks like `base64:xxxxx...`) and paste it in Render.

---

### Step 5: Deploy! (10 minutes)

1. **Click "Manual Deploy"** → **"Deploy latest commit"**

2. **Watch Build Logs**:
   - Go to **"Logs"** tab
   - Watch Docker build process
   - First build takes 8-10 minutes

3. **Wait for Success**:
   ```
   ==> Build successful 🎉
   ==> Deploying...
   ==> Your service is live 🎉
   ```

4. **Check your URL**:
   ```
   https://smartharvest.onrender.com
   ```

---

### Step 6: Verify Deployment (3 minutes)

#### Test Basic Functionality

1. **Visit Homepage**: https://smartharvest.onrender.com
   - ✅ Page loads (may take 30-60s first time)
   - ✅ No errors displayed

2. **Test Registration**:
   - Go to `/register`
   - Create test account with email verification
   - Check if verification email arrives

3. **Test SMS OTP** (if configured):
   - Register with phone number
   - Select SMS verification
   - Check if OTP SMS arrives

4. **Test Dashboard**:
   - Login with test account
   - Check weather data loads
   - Verify planting schedule displays

5. **Check Logs**:
   - Render Dashboard → Your Service → **"Logs"** tab
   - Look for any errors (PHP errors, database connection issues)

---

## 🔧 Post-Deployment Configuration

### 1. Setup Email (Gmail App Password)

Since you're on free tier, use Gmail for emails:

1. **Enable 2-Factor Authentication**:
   - Go to Google Account → Security
   - Turn on 2-Step Verification

2. **Generate App Password**:
   - Google Account → Security → App passwords
   - Select: Mail, Other (Custom name)
   - Name it: "SmartHarvest Render"
   - Copy the 16-character password

3. **Update Render Environment**:
   ```env
   MAIL_USERNAME=your.email@gmail.com
   MAIL_PASSWORD=abcd efgh ijkl mnop
   ```

### 2. Setup SMS (Semaphore)

1. **Create Account**: https://semaphore.co/
2. **Verify Identity** (Philippine mobile number required)
3. **Buy Credits**: ₱500 = ~625 SMS
4. **Get API Key**: Dashboard → API → Copy key
5. **Add to Render**: `SEMAPHORE_API_KEY=your_key_here`

### 3. Setup Weather API (OpenWeather)

1. **Create Account**: https://openweathermap.org/
2. **Get Free API Key**: Dashboard → API keys
3. **Add to Render**: `OPENWEATHER_API_KEY=your_key_here`
4. **Note**: Free tier = 1000 calls/day (enough for testing)

---

## ⚡ Managing Free Tier

### Preventing Sleep/Cold Starts

**Option 1: UptimeRobot (Recommended - Free)**
```
Service: UptimeRobot.com
Setup:
1. Create free account
2. Add monitor:
   - Type: HTTP(S)
   - URL: https://smartharvest.onrender.com
   - Interval: 5 minutes
3. Keeps app awake during daytime
```

**Option 2: Cron Job (Free)**
```bash
# Add to your local crontab or use cron-job.org
*/14 * * * * curl https://smartharvest.onrender.com
```

**Option 3: Accept Cold Starts**
- Just let it sleep after 15min inactivity
- First request takes 30-60s (one-time delay)
- Good for demos, portfolios, low-traffic apps

### Database Renewal (Every 90 Days)

When your free PostgreSQL expires:

1. **Export Data**:
   ```bash
   # From Render dashboard or CLI
   pg_dump -h HOST -U USER -d DATABASE > backup.sql
   ```

2. **Create New Database**:
   - Render Dashboard → New PostgreSQL (Free)
   - Copy new credentials

3. **Update Environment Variables**:
   - Update `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

4. **Restore Data** (if needed):
   ```bash
   psql -h NEW_HOST -U NEW_USER -d NEW_DATABASE < backup.sql
   ```

5. **Redeploy**: Manual Deploy → Deploy latest commit

---

## 🐛 Troubleshooting

### Issue 1: "Application Error" on First Load

**Cause**: Build failed or migrations didn't run

**Fix**:
1. Check Logs tab in Render dashboard
2. Look for errors in build process
3. Common issues:
   - Missing environment variables
   - Database connection failed
   - APP_KEY not set

### Issue 2: Cold Start Takes Too Long

**Cause**: Free tier spins down after 15min

**Solutions**:
- Use UptimeRobot to ping every 5min
- Accept 30-60s delay on first request
- Upgrade to paid plan ($7/mo) for always-on

### Issue 3: Database Connection Failed

**Cause**: Wrong credentials or database not ready

**Fix**:
1. Verify DB_* variables match database dashboard
2. Use **Internal Database URL** (not external)
3. Ensure `DB_CONNECTION=pgsql` (not mysql)
4. Check database is active (not suspended)

### Issue 4: Emails Not Sending

**Cause**: Gmail blocking or wrong app password

**Fix**:
1. Verify 2FA is enabled on Google account
2. Generate new app password
3. Use 16-character password without spaces
4. Check `MAIL_HOST=smtp.gmail.com` and `MAIL_PORT=587`

### Issue 5: "No Such File or Directory" Error

**Cause**: Storage link not created

**Fix**:
1. Check docker-entrypoint.sh runs:
   ```bash
   php artisan storage:link
   ```
2. Redeploy if necessary

### Issue 6: 502 Bad Gateway

**Cause**: Service crashed or not responding

**Fix**:
1. Check Logs for PHP errors
2. Verify supervisor is running all services
3. Manual Deploy → Clear cache & deploy

---

## 📊 Free Tier Limits Reference

| Resource | Free Tier | Notes |
|----------|-----------|-------|
| **Web Service** |
| Compute | 512 MB RAM | Shared CPU |
| Hours | 750 hrs/month | ~31 days with sleep |
| Sleep After | 15 minutes | Cold start: 30-60s |
| Build Minutes | 500 min/month | Usually sufficient |
| **Database** |
| Storage | 256 MB | ~50k records |
| Expires | 90 days | Renewable (create new) |
| Connections | 10 concurrent | Usually enough |
| Backups | Manual only | No auto-backup |
| **Network** |
| Bandwidth | 100 GB/month | Outbound only |
| SSL/HTTPS | ✅ Free | Automatic |
| Custom Domain | ✅ Free | Can add your own |

---

## 💰 When to Upgrade?

Consider upgrading if you experience:

- ❌ **Frequent cold starts** annoying users
- ❌ **Database filling up** (>200MB)
- ❌ **Need 24/7 uptime** for production
- ❌ **More than 50-100 daily users**
- ❌ **Database expiring** every 90 days

**Paid Plans:**
- Web Service: $7/month (always-on, more resources)
- PostgreSQL: $7/month (permanent, 1GB storage, auto-backups)
- **Total: $14/month** for production-ready setup

---

## ✅ Free Tier Deployment Checklist

### Pre-Deployment
- [ ] Code pushed to GitHub (`main` branch)
- [ ] Dockerfile tested locally (optional)
- [ ] All documentation files committed
- [ ] `.env.production` reviewed

### Render Setup
- [ ] Render account created
- [ ] Free PostgreSQL database created
- [ ] Database credentials copied
- [ ] Web service created and connected to GitHub
- [ ] Environment variables added (20+ variables)
- [ ] APP_KEY generated and added

### External Services
- [ ] Gmail app password generated
- [ ] Semaphore SMS account created (optional)
- [ ] OpenWeather API key obtained
- [ ] ML API deployed separately (optional)

### Deployment
- [ ] Manual deploy triggered
- [ ] Build completed successfully (8-10 min)
- [ ] Service shows "Live" status
- [ ] URL accessible: https://smartharvest.onrender.com

### Verification
- [ ] Homepage loads without errors
- [ ] User registration works (email verification)
- [ ] Login functionality works
- [ ] Dashboard displays correctly
- [ ] Weather data loads
- [ ] No errors in Logs tab

### Post-Deployment
- [ ] UptimeRobot monitor added (optional)
- [ ] Test email sending works
- [ ] Test SMS OTP works (if configured)
- [ ] Database contains expected data
- [ ] Set calendar reminder for 90-day database renewal

---

## 🎯 Next Steps After Deployment

1. **Test Thoroughly**
   - Create multiple test accounts
   - Test all features (weather, yield analysis, planting schedules)
   - Verify SMS and email notifications

2. **Monitor Performance**
   - Check Render logs daily for first week
   - Watch for errors or slow queries
   - Monitor database storage usage

3. **Plan for Growth**
   - If app gets traction, budget for paid tier
   - Consider database migration strategy
   - Plan scaling approach

4. **Optimize for Free Tier**
   - Implement caching where possible
   - Optimize database queries
   - Use UptimeRobot to reduce cold starts
   - Compress images and static assets

5. **Document Your Setup**
   - Save all credentials securely
   - Set reminder for 90-day database renewal
   - Keep backup of important data

---

## 🆘 Need Help?

### Documentation
- **Full Deployment Guide**: `RENDER_DEPLOYMENT.md`
- **Docker Testing**: `DOCKER_TEST_GUIDE.md`
- **Complete Checklist**: `DEPLOYMENT_CHECKLIST.md`
- **Paid Tier Guide**: `DEPLOY_QUICK_START.md`

### Render Support
- Documentation: https://render.com/docs
- Community: https://community.render.com/
- Status: https://status.render.com/

### Common Links
- Render Dashboard: https://dashboard.render.com/
- Semaphore: https://semaphore.co/
- OpenWeather: https://openweathermap.org/
- UptimeRobot: https://uptimerobot.com/

---

**🎉 Congratulations! Your SmartHarvest app is now running on Render's free tier!**

**URL**: https://smartharvest.onrender.com  
**Cost**: $0/month  
**Status**: Perfect for testing, demos, and low-traffic use cases
