# SmartHarvest Render Deployment - Quick Start

## 🚀 Deploy in 10 Minutes

### 1. Create Render Account
👉 https://render.com/ (Free to start)

### 2. Create MySQL Database
1. Dashboard → **New +** → **MySQL**
2. Name: `smartharvest-db`
3. Region: **Singapore**
4. Plan: **Starter** ($7/mo)
5. Click **Create Database**
6. 📋 Copy connection details

### 3. Create Web Service
1. Dashboard → **New +** → **Web Service**
2. Connect GitHub repo: `JathR31/smart_harvest`
3. Settings:
   - Name: `smartharvest`
   - Region: **Singapore**
   - Branch: `main`
   - Runtime: **Docker**
4. Click **Create Web Service**

### 4. Add Environment Variables
Go to **Environment** tab and add:

**Essential Variables:**
```env
APP_KEY=base64:GENERATE_THIS_LOCALLY
APP_URL=https://smartharvest.onrender.com
DB_HOST=dpg-xxxxx.render.com
DB_DATABASE=smartharvest
DB_USERNAME=smartharvest_user
DB_PASSWORD=FROM_DATABASE_DASHBOARD
MAIL_HOST=smtp.gmail.com
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
SEMAPHORE_API_KEY=your-semaphore-key
```

💡 **Generate APP_KEY locally:**
```bash
php artisan key:generate --show
```

### 5. Deploy! 🎉
- Render automatically builds and deploys
- Wait 5-10 minutes for first deployment
- Visit: `https://smartharvest.onrender.com`

---

## ✅ Verify Deployment

- [ ] Homepage loads
- [ ] Registration works
- [ ] Email verification sends
- [ ] SMS OTP works
- [ ] Login successful
- [ ] Dashboard displays data

---

## 💰 Cost
- **Free Tier**: $0 (spins down after 15 min inactivity)
- **Starter**: $14/mo (web + database, always on)
- **Recommended**: $45/mo (better performance)

---

## 📚 Full Guide
See `RENDER_DEPLOYMENT.md` for detailed instructions.

---

## 🆘 Issues?
- Check Render logs (Dashboard → Logs)
- Verify all environment variables set
- Check database connection details
- Run `php artisan config:clear` in shell
