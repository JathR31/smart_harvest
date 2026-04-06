# SmartHarvest Render Deployment Guide

## 🚀 Overview

This guide will help you deploy SmartHarvest to Render.com using Docker containers. Render provides automatic deployments from GitHub with built-in database hosting.

---

## 📋 Prerequisites

1. **GitHub Repository** - Your code should be on GitHub
2. **Render Account** - Sign up at https://render.com/
3. **Environment Variables Ready** - Semaphore API key, Mail credentials, etc.

---

## 🔧 Deployment Steps

### Step 1: Prepare Repository

Ensure these files are in your repository:
- ✅ `Dockerfile` - Docker configuration
- ✅ `docker-entrypoint.sh` - Startup script
- ✅ `.dockerignore` - Files to exclude
- ✅ `render.yaml` - Render blueprint (optional but recommended)
- ✅ `docker/nginx.conf` - Nginx web server config
- ✅ `docker/supervisord.conf` - Process manager config

### Step 2: Push to GitHub

```bash
git add .
git commit -m "Add Render deployment configuration"
git push origin main
```

### Step 3: Create Database on Render

1. Go to https://dashboard.render.com/
2. Click **"New +"** → **"PostgreSQL"** or **"MySQL"**
3. Configure database:
   - **Name**: `smartharvest-db`
   - **Database**: `smartharvest`
   - **User**: `smartharvest_user`
   - **Region**: Singapore (closest to Philippines)
   - **Plan**: Starter ($7/month) or Free
4. Click **"Create Database"**
5. Save connection details (host, port, database, user, password)

### Step 4: Create Web Service

1. Click **"New +"** → **"Web Service"**
2. Connect your GitHub repository
3. Configure service:
   - **Name**: `smartharvest-web`
   - **Region**: Singapore
   - **Branch**: `main`
   - **Runtime**: Docker
   - **Docker Command**: Leave default (uses Dockerfile CMD)
4. Click **"Create Web Service"**

### Step 5: Configure Environment Variables

In your Render dashboard, go to **Environment** tab and add:

#### Required Variables
```env
APP_NAME=SmartHarvest
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:GENERATE_THIS_WITH_php_artisan_key:generate
APP_URL=https://your-app.onrender.com

# Database (from Render database)
DB_CONNECTION=mysql
DB_HOST=your-database-host.render.com
DB_PORT=3306
DB_DATABASE=smartharvest
DB_USERNAME=smartharvest_user
DB_PASSWORD=your-database-password

# Session & Cache
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

# Mail Configuration (using Gmail, Mailtrap, or SendGrid)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME=SmartHarvest

# SMS Service (Semaphore)
SEMAPHORE_API_KEY=your-semaphore-api-key

# Weather API
OPENWEATHER_API_KEY=735d56dd7a0f98a8ac7638cbd8911242

# ML API (if hosted separately)
ML_API_URL=http://your-ml-api-url:5000
```

#### Generate APP_KEY
Run locally:
```bash
php artisan key:generate --show
```
Copy the output and paste into `APP_KEY` environment variable.

### Step 6: Deploy

1. Render will automatically build and deploy
2. Monitor build logs in the dashboard
3. First deployment takes ~5-10 minutes
4. Once complete, your app will be live at `https://your-app.onrender.com`

---

## 🔄 Automatic Deployments

Render automatically deploys when you push to GitHub:

```bash
git add .
git commit -m "Update feature"
git push origin main
# Render automatically rebuilds and deploys!
```

---

## 🗃️ Database Management

### Run Migrations
Migrations run automatically on deployment via `docker-entrypoint.sh`.

### Manual Migration (if needed)
In Render dashboard:
1. Go to **Shell** tab
2. Run:
```bash
php artisan migrate --force
```

### Seed Database
```bash
php artisan db:seed --force
```

### Access Database
Use Render's database connection details with any MySQL client:
- Host: `dpg-xxxxx-xxx.render.com`
- Port: `3306`
- Database: `smartharvest`
- User: `smartharvest_user`
- Password: `(from Render dashboard)`

---

## 📊 Performance Optimization

### Enable Caching (Already configured)
The deployment automatically runs:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Queue Workers
The Dockerfile includes 2 queue workers via Supervisor for background jobs (email sending, data processing).

### Static Asset Caching
Nginx is configured to cache static assets (images, CSS, JS) for 1 year.

---

## 🔍 Monitoring & Logs

### View Application Logs
In Render dashboard:
1. Go to **Logs** tab
2. Real-time log streaming
3. Filter by severity

### View Laravel Logs
Access shell and run:
```bash
tail -f storage/logs/laravel.log
```

### Health Check
Render automatically monitors your app at `/` endpoint.

---

## 💰 Pricing Estimate

### Render Plans

**Free Tier** (Development):
- Web Service: Free (spins down after inactivity)
- Database: Free PostgreSQL (90 days retention)
- **Total**: $0/month

**Starter** (Production):
- Web Service: $7/month (always on)
- Database: $7/month (MySQL or PostgreSQL)
- **Total**: $14/month

**Standard** (Recommended for production):
- Web Service: $25/month (better performance)
- Database: $20/month (better performance + backups)
- **Total**: $45/month

---

## 🛠️ Troubleshooting

### Build Failures

**Check Dockerfile syntax:**
```bash
docker build -t smartharvest-test .
```

**Common issues:**
- Missing dependencies in Dockerfile
- Invalid nginx.conf syntax
- Permission issues (use `chmod -R 755`)

### Database Connection Errors

**Verify environment variables:**
- ✅ DB_HOST correct
- ✅ DB_PORT correct (usually 3306)
- ✅ DB_DATABASE matches Render database name
- ✅ DB_USERNAME and DB_PASSWORD correct

### Application Not Loading

**Check logs:**
1. Render Dashboard → Logs tab
2. Look for errors in Laravel logs
3. Check nginx error logs

**Common fixes:**
- Clear cache: `php artisan cache:clear`
- Rebuild config: `php artisan config:cache`
- Check APP_KEY is set

### SMS/Email Not Working

**Verify environment variables:**
- ✅ SEMAPHORE_API_KEY set correctly
- ✅ MAIL_* variables configured
- ✅ Check Semaphore credits balance

---

## 🔒 Security Best Practices

### Environment Variables
- ✅ Never commit `.env` to Git
- ✅ Use Render's environment variable manager
- ✅ Rotate API keys regularly

### Database Security
- ✅ Use strong passwords
- ✅ Enable SSL connections (Render default)
- ✅ Restrict IP access if needed

### Application Security
- ✅ Set `APP_DEBUG=false` in production
- ✅ Keep Laravel and dependencies updated
- ✅ Enable CSRF protection (default in Laravel)
- ✅ Use HTTPS (automatic with Render)

---

## 🚀 Advanced Configuration

### Custom Domain

1. In Render dashboard, go to **Settings**
2. Click **Custom Domain**
3. Add your domain: `smartharvest.com`
4. Update DNS records:
   ```
   Type: CNAME
   Name: @
   Value: your-app.onrender.com
   ```
5. SSL certificate automatically provisioned

### Horizontal Scaling

For high traffic:
1. Go to **Settings** → **Instance Count**
2. Increase to 2-5 instances
3. Render load balances automatically

### Background Jobs

Queue workers are already configured in `supervisord.conf`:
- 2 workers by default
- Auto-restart on failure
- Max 1 hour per job

---

## 📈 Deployment Checklist

Before deploying to production:

- [ ] Database created on Render
- [ ] All environment variables set
- [ ] APP_KEY generated and set
- [ ] Mail service configured (Gmail/SendGrid)
- [ ] Semaphore API key added
- [ ] Repository pushed to GitHub
- [ ] Web service created and connected
- [ ] Custom domain configured (optional)
- [ ] SSL certificate active (automatic)
- [ ] Test registration with email verification
- [ ] Test SMS OTP verification
- [ ] Test weather API integration
- [ ] Test ML predictions
- [ ] Monitor logs for errors
- [ ] Set up error alerting (Render notifications)

---

## 🆘 Support Resources

### Render Documentation
- Main docs: https://render.com/docs
- Docker: https://render.com/docs/docker
- Environment variables: https://render.com/docs/environment-variables
- Databases: https://render.com/docs/databases

### SmartHarvest Specific
- **Laravel docs**: https://laravel.com/docs
- **Deployment issues**: Check `storage/logs/laravel.log`
- **SMS issues**: See `SMS_OTP_SETUP.md`
- **ML integration**: See `ML_API_SETUP.md`

---

## 🎯 Quick Commands Reference

### Local Development
```bash
# Run locally with Docker
docker build -t smartharvest .
docker run -p 8000:80 smartharvest

# Run migrations
php artisan migrate

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### On Render (via Shell)
```bash
# Run migrations
php artisan migrate --force

# Clear cache
php artisan cache:clear

# View logs
tail -f storage/logs/laravel.log

# Check queue workers
ps aux | grep queue:work
```

---

## ✅ Post-Deployment Verification

After deployment, verify:

1. **Homepage loads**: Visit `https://your-app.onrender.com`
2. **Registration works**: Create test account
3. **Email verification**: Check inbox for verification email
4. **SMS OTP**: Test with Philippine mobile number
5. **Login**: Sign in with verified account
6. **Dashboard**: Check weather data loads
7. **ML predictions**: Verify yield analysis works
8. **Admin panel**: Test admin login (if applicable)

---

## 🔄 Update Process

Updating your deployed app:

```bash
# 1. Make changes locally
# 2. Test changes
# 3. Commit and push
git add .
git commit -m "Update description"
git push origin main

# 4. Render automatically deploys
# 5. Monitor deployment in Render dashboard
# 6. Verify changes on live site
```

---

**Deployment Status**: ✅ Ready for Render  
**Estimated Setup Time**: 20-30 minutes  
**Recommended Plan**: Starter ($14/month) or Standard ($45/month)  
**Support**: Check Render dashboard logs for any deployment issues
