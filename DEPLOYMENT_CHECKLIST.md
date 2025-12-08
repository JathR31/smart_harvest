# Render Deployment Checklist

## Pre-Deployment Setup ✅

### 1. Repository Preparation
- [ ] All deployment files committed to GitHub
  - [ ] `Dockerfile`
  - [ ] `docker-entrypoint.sh`
  - [ ] `.dockerignore`
  - [ ] `render.yaml`
  - [ ] `docker/nginx.conf`
  - [ ] `docker/supervisord.conf`
- [ ] Code pushed to `main` branch
- [ ] No sensitive data in repository (check .gitignore)
- [ ] Repository is public or Render has access

### 2. Render Account Setup
- [ ] Render account created (https://render.com)
- [ ] Credit card added (for paid plans) or free tier selected
- [ ] Region selected: **Singapore** (closest to Philippines)

### 3. Database Setup
- [ ] MySQL database created on Render
  - [ ] Name: `smartharvest-db`
  - [ ] Database: `smartharvest`
  - [ ] User: `smartharvest_user`
  - [ ] Region: Singapore
  - [ ] Plan: Starter ($7/mo) or Free
- [ ] Database connection details saved:
  - [ ] Host: `dpg-xxxxx.render.com`
  - [ ] Port: `3306`
  - [ ] Database name: `smartharvest`
  - [ ] Username: `smartharvest_user`
  - [ ] Password: (from Render dashboard)

### 4. External Services
- [ ] **Semaphore SMS**
  - [ ] Account created (https://semaphore.co)
  - [ ] Credits purchased (₱500+ recommended)
  - [ ] API key obtained
- [ ] **Email Service** (choose one)
  - [ ] Gmail app password generated
  - [ ] OR SendGrid API key obtained
  - [ ] OR Mailtrap credentials for testing
- [ ] **OpenWeather API**
  - [ ] API key available (default: `735d56dd7a0f98a8ac7638cbd8911242`)

---

## Render Configuration ⚙️

### 5. Create Web Service
- [ ] New Web Service created on Render
- [ ] GitHub repository connected
- [ ] Settings configured:
  - [ ] Name: `smartharvest` or `smartharvest-web`
  - [ ] Region: **Singapore**
  - [ ] Branch: `main`
  - [ ] Runtime: **Docker**
  - [ ] Plan: Starter ($7/mo) or Free

### 6. Environment Variables Set
Copy from `.env.production` and configure:

#### Application
- [ ] `APP_NAME=SmartHarvest`
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY=base64:...` (generated with `php artisan key:generate --show`)
- [ ] `APP_URL=https://your-app.onrender.com`

#### Logging
- [ ] `LOG_CHANNEL=stack`
- [ ] `LOG_LEVEL=error`

#### Database (from Render database)
- [ ] `DB_CONNECTION=mysql`
- [ ] `DB_HOST=dpg-xxxxx.render.com`
- [ ] `DB_PORT=3306`
- [ ] `DB_DATABASE=smartharvest`
- [ ] `DB_USERNAME=smartharvest_user`
- [ ] `DB_PASSWORD=...` (from Render)

#### Session & Cache
- [ ] `SESSION_DRIVER=database`
- [ ] `QUEUE_CONNECTION=database`
- [ ] `CACHE_STORE=database`

#### Mail
- [ ] `MAIL_MAILER=smtp`
- [ ] `MAIL_HOST=...`
- [ ] `MAIL_PORT=587`
- [ ] `MAIL_USERNAME=...`
- [ ] `MAIL_PASSWORD=...`
- [ ] `MAIL_ENCRYPTION=tls`
- [ ] `MAIL_FROM_ADDRESS=...`
- [ ] `MAIL_FROM_NAME=SmartHarvest`

#### SMS (Semaphore)
- [ ] `SEMAPHORE_API_KEY=...`

#### Weather API
- [ ] `OPENWEATHER_API_KEY=735d56dd7a0f98a8ac7638cbd8911242`

#### ML API (optional)
- [ ] `ML_API_URL=http://localhost:5000` or external URL

---

## Deployment 🚀

### 7. Initial Deployment
- [ ] Click "Create Web Service" or "Manual Deploy"
- [ ] Monitor build logs in Render dashboard
- [ ] Wait for build to complete (5-10 minutes first time)
- [ ] Check for build errors
- [ ] Verify deployment status: **Live**

### 8. Post-Deployment Verification

#### Application Health
- [ ] Application URL accessible (https://your-app.onrender.com)
- [ ] Homepage loads without errors
- [ ] No 500/502 errors
- [ ] Static assets (CSS/JS/images) load correctly
- [ ] Console shows no JavaScript errors

#### Database & Migrations
- [ ] Migrations ran successfully (check logs)
- [ ] Database tables created
- [ ] Can connect to database (via shell or external client)
- [ ] Seeded data present (if applicable)

#### Authentication System
- [ ] Registration page loads
- [ ] Can create new account
- [ ] Email verification works
  - [ ] Verification email received
  - [ ] Email link works
  - [ ] Redirects to password setup
- [ ] SMS OTP verification works (if tested)
  - [ ] OTP SMS received
  - [ ] Code verification successful
  - [ ] Redirects to password setup
- [ ] Password setup works
- [ ] Login successful
- [ ] Session persists across requests

#### Core Features
- [ ] Dashboard loads after login
- [ ] Weather data displays
  - [ ] Temperature shows
  - [ ] Humidity shows
  - [ ] Wind speed shows
  - [ ] Rainfall shows
- [ ] Municipality selector works
- [ ] Forecast page accessible
  - [ ] Hourly forecast displays
  - [ ] 5-day forecast displays
  - [ ] Charts render correctly
- [ ] Yield analysis page works
  - [ ] Data loads
  - [ ] Charts display
  - [ ] Statistics show
- [ ] Planting schedule accessible
  - [ ] Recommendations display
  - [ ] Data accurate

#### Admin Panel (if applicable)
- [ ] Admin login works
- [ ] Admin dashboard loads
- [ ] User management accessible
- [ ] Data import works
- [ ] Datasets page loads
- [ ] Monitoring page accessible

---

## Performance & Monitoring 📊

### 9. Performance Checks
- [ ] Page load time < 3 seconds
- [ ] API responses < 1 second
- [ ] Images optimized and loading fast
- [ ] No memory issues in logs
- [ ] Queue workers running (check supervisor)

### 10. Monitoring Setup
- [ ] Render health checks passing
- [ ] Log monitoring configured
- [ ] Error notifications enabled (Render dashboard)
- [ ] Database performance acceptable
- [ ] Storage usage within limits

### 11. Security Verification
- [ ] HTTPS enabled (automatic with Render)
- [ ] SSL certificate valid
- [ ] `APP_DEBUG=false` confirmed
- [ ] No sensitive data exposed in logs
- [ ] CSRF protection working
- [ ] Rate limiting active
- [ ] Strong database password used

---

## Optional Enhancements 🎨

### 12. Custom Domain (Optional)
- [ ] Domain purchased/owned
- [ ] DNS records updated:
  - [ ] CNAME record: `@` → `your-app.onrender.com`
  - [ ] OR A record with Render IP
- [ ] Custom domain added in Render
- [ ] SSL certificate provisioned
- [ ] Domain accessible

### 13. Scaling (Optional)
- [ ] Instance count increased (if needed)
- [ ] Auto-scaling configured (paid plans)
- [ ] Database plan upgraded (if needed)
- [ ] CDN configured (for static assets)

### 14. Backups & Recovery
- [ ] Database backup enabled (paid plans)
- [ ] Backup schedule configured
- [ ] Recovery process tested
- [ ] Code repository backed up

---

## Testing Scenarios ✅

### 15. User Flow Testing
- [ ] **New Farmer Registration (Email)**
  1. Navigate to /register
  2. Fill form with email
  3. Select "Email Verification"
  4. Submit form
  5. Check email for verification link
  6. Click link
  7. Set password
  8. Login
  9. Access dashboard
  
- [ ] **New Farmer Registration (SMS)**
  1. Navigate to /register
  2. Fill form with Philippine mobile number
  3. Select "SMS Verification"
  4. Submit form
  5. Check phone for OTP SMS
  6. Enter 6-digit code
  7. Code verified
  8. Set password
  9. Login
  10. Access dashboard

- [ ] **Weather Feature**
  1. Login
  2. View dashboard
  3. Check weather cards display
  4. Change municipality
  5. Verify data updates
  6. Visit forecast page
  7. Check hourly/daily forecasts

- [ ] **Yield Analysis**
  1. Login
  2. Navigate to yield analysis
  3. Verify charts load
  4. Check statistics display
  5. Change municipality/filters
  6. Verify data updates

- [ ] **Planting Schedule**
  1. Login
  2. Navigate to planting schedule
  3. View recommendations
  4. Check crop information
  5. Verify dates are accurate

### 16. Error Handling
- [ ] 404 page works
- [ ] 500 errors logged properly
- [ ] Form validation works
- [ ] Database connection errors handled
- [ ] API timeout errors handled gracefully

---

## Troubleshooting 🛠️

### Common Issues Checklist

#### Build Fails
- [ ] Check Dockerfile syntax
- [ ] Verify all COPY paths exist
- [ ] Check composer.json valid
- [ ] Verify package.json valid
- [ ] Review build logs in Render

#### Application Not Starting
- [ ] Check supervisord logs
- [ ] Verify nginx running
- [ ] Check PHP-FPM running
- [ ] Review entrypoint script logs

#### Database Connection Errors
- [ ] Verify DB_HOST correct
- [ ] Check DB_PORT (usually 3306)
- [ ] Confirm DB_DATABASE name
- [ ] Validate DB_USERNAME
- [ ] Test DB_PASSWORD
- [ ] Check database is running on Render

#### Email Not Sending
- [ ] Verify MAIL_* variables set
- [ ] Check mail service credentials
- [ ] Test with Mailtrap first
- [ ] Review Laravel logs for errors
- [ ] Check spam folder

#### SMS Not Sending
- [ ] Verify SEMAPHORE_API_KEY set
- [ ] Check Semaphore credits balance
- [ ] Verify phone format (+639XXXXXXXXX)
- [ ] Review SMS logs in Semaphore dashboard
- [ ] Check Laravel logs

#### Static Assets Not Loading
- [ ] Run `npm run build` included in Dockerfile
- [ ] Verify public/build exists
- [ ] Check nginx serving static files
- [ ] Review nginx error logs

---

## Post-Launch Monitoring 👀

### Daily Checks (First Week)
- [ ] Application uptime (Render dashboard)
- [ ] Error logs reviewed
- [ ] Database size monitored
- [ ] SMS credit balance checked
- [ ] New user registrations working
- [ ] Weather data updating

### Weekly Checks
- [ ] Performance metrics reviewed
- [ ] Storage usage checked
- [ ] Backup verification (if enabled)
- [ ] Security updates applied
- [ ] User feedback addressed

### Monthly Checks
- [ ] Cost analysis (Render bill)
- [ ] Database optimization
- [ ] Cache clearing
- [ ] Log rotation
- [ ] Feature usage analytics

---

## Success Criteria ✨

### Deployment is successful when:
- ✅ Application accessible via HTTPS
- ✅ Registration with email works
- ✅ Registration with SMS works
- ✅ Email verification delivers
- ✅ SMS OTP delivers
- ✅ Login successful
- ✅ Dashboard displays data
- ✅ Weather feature functional
- ✅ Yield analysis works
- ✅ Planting schedule displays
- ✅ Admin panel accessible (if applicable)
- ✅ No critical errors in logs
- ✅ Performance acceptable (<3s page load)
- ✅ Database connected and responsive
- ✅ Queue workers processing jobs

---

## Rollback Plan 🔄

If deployment fails:
1. Check logs in Render dashboard
2. Revert to previous deployment (Render → Rollback)
3. Fix issues locally
4. Test Docker build locally
5. Push fix to GitHub
6. Retry deployment

---

## Support Resources 📚

- **Render Docs**: https://render.com/docs
- **Laravel Docs**: https://laravel.com/docs
- **SmartHarvest Guides**:
  - `RENDER_DEPLOYMENT.md` - Full deployment guide
  - `DEPLOY_QUICK_START.md` - Quick start
  - `DOCKER_TEST_GUIDE.md` - Local testing
  - `SMS_OTP_SETUP.md` - SMS configuration

---

## Final Sign-Off ✍️

**Deployed By**: _______________  
**Date**: _______________  
**Deployment URL**: _______________  
**Database**: _______________  
**Status**: _______________  

**Notes**:
_________________________________
_________________________________
_________________________________

---

**Checklist Complete!** 🎉  
Your SmartHarvest application is now live on Render!
