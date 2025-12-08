# SmartHarvest 🌾

> Data-driven agricultural intelligence platform for Benguet Province farmers

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)](https://www.php.net/)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker)](https://www.docker.com/)
[![Deployment](https://img.shields.io/badge/Render-Production%20Ready-46E3B7)](https://render.com)

## 🚀 Quick Deploy

**Production-ready in 10 minutes!**

```bash
# Follow the quick start guide
See: DEPLOY_QUICK_START.md

# Or comprehensive deployment guide
See: RENDER_DEPLOYMENT.md
```

| Status | Platform | Cost | Region | Setup Time |
|--------|----------|------|--------|------------|
| ✅ Ready | Render.com | $14-45/mo | Singapore | 10-30 min |

---

## 📋 Table of Contents

- [Features](#-features)
- [Quick Deploy](#-quick-deploy-to-production)
- [Local Development](#-local-development)
- [Architecture](#-architecture)
- [Documentation](#-documentation)
- [Tech Stack](#-tech-stack)

---

## ✨ Features

### Core Features
- 🌤️ **Real-time Weather Data** - OpenWeather API integration for Benguet Province
- 📊 **Yield Prediction** - Machine learning models trained on historical climate data
- 📅 **Planting Schedule** - Optimal planting recommendations based on crop type
- 👨‍🌾 **Farmer Dashboard** - Track plantings, view predictions, analyze trends
- 👔 **Admin Panel** - Manage users, crops, and system configuration

### Authentication & Verification
- ✉️ **Email Verification** - Secure email-based account verification
- 📱 **SMS OTP Verification** - Alternative verification via Semaphore API (Philippine SMS)
- 🔐 **Role-based Access Control** - Admin, Farmer, and Guest roles
- 🔑 **Password Reset** - Secure password recovery via email

### Data Management
- 📈 **CSV Import** - Bulk import crop/weather datasets
- 📊 **Analytics Dashboard** - Visualize yield trends and weather patterns
- 📉 **Historical Data** - Access to years of climate and crop performance data
- 🔄 **Data Synchronization** - Real-time updates from ML API

---

## 🚀 Quick Deploy to Production

### Prerequisites
- GitHub account
- Render.com account (free tier available)
- External service accounts:
  - OpenWeather API key (free tier)
  - Semaphore SMS account (for SMS OTP)
  - Email service (Gmail app password or SendGrid)

### Deployment Steps

**1. Commit files to GitHub**
```bash
git add .
git commit -m "Production deployment ready"
git push origin main
```

**2. Follow deployment guide**
```bash
# Quick Start (10 minutes)
DEPLOY_QUICK_START.md

# Detailed Guide (30 minutes, includes testing)
RENDER_DEPLOYMENT.md

# Checklist (comprehensive verification)
DEPLOYMENT_CHECKLIST.md
```

**3. Configure environment**
- Use `.env.production` as template
- Add all required API keys
- Generate APP_KEY: `php artisan key:generate --show`

**4. Deploy!**
- Render auto-deploys from GitHub
- First deployment: ~10 minutes
- Subsequent deployments: ~3-5 minutes

### Cost Breakdown

| Service | Plan | Cost | Purpose |
|---------|------|------|---------|
| Web Service | Starter | $7/mo | Application hosting |
| MySQL Database | Starter | $7/mo | Data storage |
| **Total** | - | **$14/mo** | Production deployment |

*Scales to $45/mo for higher traffic*

---

## 💻 Local Development

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 20+
- MySQL 8.0+
- Python 3.11+ (for ML API)

### Setup

```bash
# 1. Clone repository
git clone <your-repo-url>
cd SmartHarvest

# 2. Install dependencies
composer install
npm install

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# 4. Database setup
php artisan migrate
php artisan db:seed

# 5. Build frontend assets
npm run dev

# 6. Start development server
php artisan serve
```

### Docker Development

```bash
# Build image
docker build -t smartharvest:dev .

# Run container
docker run -p 80:80 smartharvest:dev

# Test locally (see DOCKER_TEST_GUIDE.md)
```

---

## 🏗️ Architecture

### System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     RENDER.COM PLATFORM                      │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌───────────────────────────────────────────────────────┐  │
│  │           Docker Container (Web Service)              │  │
│  │                                                         │  │
│  │  ┌─────────────────────────────────────────────────┐  │  │
│  │  │              Supervisor                          │  │  │
│  │  │  ┌──────────┐ ┌──────────┐ ┌──────────────┐    │  │  │
│  │  │  │ PHP-FPM  │ │  Nginx   │ │ Queue Worker │    │  │  │
│  │  │  │  (8.2)   │ │  (1.x)   │ │   (x2)       │    │  │  │
│  │  │  └──────────┘ └──────────┘ └──────────────┘    │  │  │
│  │  └─────────────────────────────────────────────────┘  │  │
│  │                                                         │  │
│  │  Laravel 11 Application                                │  │
│  │  ├── Controllers                                        │  │
│  │  ├── Models (Eloquent ORM)                             │  │
│  │  ├── Views (Blade Templates)                           │  │
│  │  ├── Routes (Web + API)                                │  │
│  │  └── Jobs (Queue Workers)                              │  │
│  └───────────────────────────────────────────────────────┘  │
│                           ↓                                  │
│  ┌───────────────────────────────────────────────────────┐  │
│  │         MySQL Database (Render Managed)               │  │
│  │  - Users, Roles, Permissions                          │  │
│  │  - Crops, Plantings, Yield Data                       │  │
│  │  - Weather History                                     │  │
│  │  - Sessions, Cache, Queue Jobs                        │  │
│  └───────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                           ↕
        ┌──────────────────────────────────────┐
        │      External Services               │
        │  ┌────────────────────────────────┐  │
        │  │  OpenWeather API               │  │
        │  │  - Real-time weather data      │  │
        │  │  - 7-day forecasts             │  │
        │  └────────────────────────────────┘  │
        │  ┌────────────────────────────────┐  │
        │  │  Semaphore SMS API             │  │
        │  │  - OTP verification            │  │
        │  │  - Philippine mobile numbers   │  │
        │  └────────────────────────────────┘  │
        │  ┌────────────────────────────────┐  │
        │  │  ML Prediction API (Python)    │  │
        │  │  - Yield predictions           │  │
        │  │  - Climate analysis            │  │
        │  └────────────────────────────────┘  │
        │  ┌────────────────────────────────┐  │
        │  │  Email Service                 │  │
        │  │  - Gmail / SendGrid / Mailtrap │  │
        │  └────────────────────────────────┘  │
        └──────────────────────────────────────┘
```

### Request Flow

```
User Browser
    ↓
HTTPS (Render SSL)
    ↓
Nginx (Port 80)
    ↓
PHP-FPM (FastCGI)
    ↓
Laravel Router
    ↓
Controller → Model → Database
    ↓
View (Blade) → Response
    ↓
User Browser
```

---

## 📚 Documentation

### Deployment Guides
- **[DEPLOY_QUICK_START.md](DEPLOY_QUICK_START.md)** - 10-minute deployment guide
- **[RENDER_DEPLOYMENT.md](RENDER_DEPLOYMENT.md)** - Comprehensive deployment guide
- **[DOCKER_TEST_GUIDE.md](DOCKER_TEST_GUIDE.md)** - Test Docker locally before deployment
- **[DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)** - Complete verification checklist
- **[DEPLOYMENT_SUMMARY.md](DEPLOYMENT_SUMMARY.md)** - Deployment overview & architecture
- **[HOSTING_OPTIONS.md](HOSTING_OPTIONS.md)** - Platform comparison (Render vs alternatives)

### Feature Documentation
- **[SMS_OTP_SETUP.md](SMS_OTP_SETUP.md)** - SMS verification implementation
- **[SMS_OTP_IMPLEMENTATION_SUMMARY.md](SMS_OTP_IMPLEMENTATION_SUMMARY.md)** - SMS OTP overview
- **[SMS_OTP_QUICK_START.md](SMS_OTP_QUICK_START.md)** - Quick SMS configuration
- **[EMAIL_VERIFICATION_GUIDE.md](EMAIL_VERIFICATION_GUIDE.md)** - Email verification setup
- **[EMAIL_SERVICE_SETUP.md](EMAIL_SERVICE_SETUP.md)** - Email service configuration
- **[ML_DASHBOARD_INTEGRATION.md](ML_DASHBOARD_INTEGRATION.md)** - ML API integration
- **[PLANTING_SCHEDULE_COMPLETE.md](PLANTING_SCHEDULE_COMPLETE.md)** - Planting schedule feature
- **[ROLES_PERMISSIONS_IMPLEMENTATION.md](ROLES_PERMISSIONS_IMPLEMENTATION.md)** - RBAC setup

### API Documentation
- **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)** - Complete API reference
- **[EMAIL_VERIFICATION_API.md](EMAIL_VERIFICATION_API.md)** - Email verification endpoints
- **[ML_API_SETUP.md](ML_API_SETUP.md)** - Machine learning API setup

---

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 11
- **Language**: PHP 8.2
- **Database**: MySQL 8.0
- **ORM**: Eloquent
- **Queue**: Database driver (production-ready)
- **Cache**: Database driver
- **Session**: Database driver

### Frontend
- **Templating**: Blade
- **CSS Framework**: Tailwind CSS
- **Build Tool**: Vite
- **JavaScript**: Vanilla JS / Alpine.js

### Infrastructure
- **Containerization**: Docker
- **Web Server**: Nginx 1.x
- **Process Manager**: Supervisor
- **Platform**: Render.com
- **CI/CD**: GitHub Actions
- **Region**: Singapore (optimal for Philippines)

### External Services
- **Weather**: OpenWeather API
- **SMS**: Semaphore SMS API (Philippines)
- **Email**: Gmail / SendGrid / Mailtrap
- **ML API**: Python Flask (yield predictions)

### Development Tools
- **Package Manager**: Composer (PHP), npm (Node.js)
- **Testing**: Pest PHP
- **Code Quality**: PHP Stan
- **Version Control**: Git

---

## 🔐 Security

- HTTPS enforced (Render automatic SSL)
- CSRF protection (Laravel built-in)
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade escaping)
- Rate limiting on API endpoints
- Email/SMS verification for new accounts
- Password hashing (bcrypt)
- Environment variable encryption

---

## 📊 Performance

- **Nginx static file caching** (1 year for assets)
- **Laravel config/route/view caching**
- **Opcache enabled** (PHP bytecode caching)
- **Database query optimization** (Eloquent eager loading)
- **Queue workers** (2 instances for background jobs)
- **CDN-ready** (static assets served efficiently)

---

## 🧪 Testing

### Run Tests
```bash
# PHP tests
php artisan test

# Or with Pest
./vendor/bin/pest
```

### Docker Tests
```bash
# Build and test
docker build -t smartharvest:test .
docker run --rm smartharvest:test php artisan test
```

---

## 📝 Environment Variables

### Required Variables
```env
APP_NAME=SmartHarvest
APP_ENV=production
APP_KEY=base64:... # Generate with: php artisan key:generate
APP_DEBUG=false
APP_URL=https://your-app.onrender.com

DB_CONNECTION=mysql
DB_HOST=your-render-database.oregon-postgres.render.com
DB_PORT=3306
DB_DATABASE=smartharvest
DB_USERNAME=smartharvest
DB_PASSWORD=your-database-password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@smartharvest.com
MAIL_FROM_NAME=SmartHarvest

SEMAPHORE_API_KEY=your-semaphore-api-key
OPENWEATHER_API_KEY=your-openweather-api-key
ML_API_URL=https://your-ml-api.onrender.com
```

See `.env.production` for complete template with comments.

---

## 📞 Support

### Documentation
- All deployment guides in repository root (*.md files)
- Comprehensive troubleshooting in RENDER_DEPLOYMENT.md
- Checklist for verification: DEPLOYMENT_CHECKLIST.md

### Common Issues
- **Database connection fails**: Check DB_* variables match Render database
- **Migrations fail**: Ensure database is created and credentials are correct
- **SMS not sending**: Verify SEMAPHORE_API_KEY and phone format (+63...)
- **Email not sending**: Check MAIL_* config and app password
- **500 error**: Check logs in Render dashboard → Logs tab

### Logs
```bash
# View logs in Render dashboard
Dashboard → Your Service → Logs

# Or via CLI
render logs smartharvest
```

---

## 🚦 Project Status

| Feature | Status | Documentation |
|---------|--------|---------------|
| User Authentication | ✅ Complete | Built-in Laravel |
| Email Verification | ✅ Complete | EMAIL_VERIFICATION_GUIDE.md |
| SMS OTP Verification | ✅ Complete | SMS_OTP_SETUP.md |
| Weather Data | ✅ Complete | ML_DASHBOARD_INTEGRATION.md |
| Yield Prediction | ✅ Complete | ML_API_SETUP.md |
| Planting Schedule | ✅ Complete | PLANTING_SCHEDULE_COMPLETE.md |
| Admin Dashboard | ✅ Complete | ROLES_PERMISSIONS_IMPLEMENTATION.md |
| Docker Deployment | ✅ Complete | DOCKER_TEST_GUIDE.md |
| Render Hosting | ✅ Complete | RENDER_DEPLOYMENT.md |
| CI/CD Pipeline | ✅ Complete | .github/workflows/docker-test.yml |

**Overall Status**: 🎉 **Production Ready**

---

## 📄 License

This project is proprietary software developed for Benguet Province agricultural use.

---

## 👥 Credits

- **Framework**: [Laravel](https://laravel.com)
- **Weather API**: [OpenWeather](https://openweathermap.org)
- **SMS Gateway**: [Semaphore](https://semaphore.co)
- **Hosting**: [Render](https://render.com)

---

## 🎯 Next Steps

1. **Deploy to Production**
   - Follow DEPLOY_QUICK_START.md
   - Use DEPLOYMENT_CHECKLIST.md for verification

2. **Configure External Services**
   - OpenWeather API key
   - Semaphore SMS account
   - Email service (Gmail/SendGrid)

3. **Test Everything**
   - User registration (email + SMS)
   - Weather data display
   - Yield predictions
   - Planting schedules

4. **Monitor & Optimize**
   - Check Render logs regularly
   - Monitor response times
   - Scale resources if needed

---

**Need help?** Check the comprehensive documentation in the repository root! 📚
