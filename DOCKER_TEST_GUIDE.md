# SmartHarvest Dockerfile Test Guide

## Test Locally Before Deploying

### 1. Build Docker Image
```bash
docker build -t smartharvest:test .
```

**Expected output:**
- ✅ All build steps complete successfully
- ✅ No errors in composer install
- ✅ No errors in npm build
- ✅ Image created successfully

### 2. Create .env for Testing
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Run Container Locally
```bash
docker run -d -p 8080:80 \
  --name smartharvest-test \
  -e APP_KEY="base64:your-key-here" \
  -e DB_CONNECTION=sqlite \
  -e DB_DATABASE=/var/www/html/database/database.sqlite \
  smartharvest:test
```

### 4. Test the Application
Open browser: `http://localhost:8080`

**Verify:**
- [ ] Homepage loads correctly
- [ ] Static assets (CSS/JS) load
- [ ] No 500 errors in logs
- [ ] Registration page accessible

### 5. Check Container Logs
```bash
# View all logs
docker logs smartharvest-test

# Follow logs in real-time
docker logs -f smartharvest-test

# Check specific service
docker exec smartharvest-test supervisorctl status
```

### 6. Access Container Shell
```bash
docker exec -it smartharvest-test bash

# Inside container:
php artisan --version
php artisan route:list
ls -la storage/logs/
exit
```

### 7. Stop and Remove Test Container
```bash
docker stop smartharvest-test
docker rm smartharvest-test
```

---

## Common Issues & Fixes

### Build Fails at Composer Install
**Error:** "composer: command not found"
**Fix:** Ensure Composer copy line is before composer install

### Build Fails at npm
**Error:** "npm: command not found"
**Fix:** Verify Node.js installation step in Dockerfile

### Container Starts but 502 Error
**Error:** "502 Bad Gateway"
**Fix:** 
- Check PHP-FPM is running: `docker exec smartharvest-test supervisorctl status php-fpm`
- Check nginx config: `docker exec smartharvest-test nginx -t`

### Permission Errors
**Error:** "storage/logs/laravel.log: Permission denied"
**Fix:** Verify chown commands in Dockerfile

### Database Connection Errors
**Error:** "SQLSTATE[HY000] [2002] Connection refused"
**Fix:** 
- Use SQLite for local testing
- Or link to external database with proper credentials

---

## Docker Commands Cheat Sheet

```bash
# Build
docker build -t smartharvest:test .

# Run with environment variables
docker run -d -p 8080:80 \
  -e APP_KEY="your-key" \
  -e DB_CONNECTION=sqlite \
  --name smartharvest-test \
  smartharvest:test

# View logs
docker logs smartharvest-test
docker logs -f smartharvest-test  # Follow

# Execute commands
docker exec smartharvest-test php artisan migrate
docker exec -it smartharvest-test bash

# Check processes
docker exec smartharvest-test supervisorctl status

# Stop/Start/Restart
docker stop smartharvest-test
docker start smartharvest-test
docker restart smartharvest-test

# Remove
docker rm smartharvest-test
docker rmi smartharvest:test

# Clean up all
docker system prune -a
```

---

## Production Checklist

Before pushing to Render:

- [ ] Dockerfile builds successfully locally
- [ ] Application runs in container
- [ ] All environment variables documented
- [ ] Database migrations work
- [ ] Static assets load correctly
- [ ] Logs are accessible
- [ ] No hardcoded credentials
- [ ] .dockerignore configured
- [ ] nginx.conf tested
- [ ] supervisord.conf tested
- [ ] Queue workers configured
- [ ] File permissions correct

---

## Next Steps

Once local testing passes:
1. Commit and push to GitHub
2. Follow `DEPLOY_QUICK_START.md`
3. Monitor Render deployment logs
4. Verify production deployment
