#!/bin/bash

# SmartHarvest Data Sync Script
# Syncs local database changes to production (Laravel Cloud)
# Usage: ./sync-production.sh or php sync-production.php

echo "=========================================="
echo "SmartHarvest Data Sync Tool"
echo "=========================================="
echo ""

# Get environment
read -p "Sync direction (local->prod or prod->local)? Default is local->prod: " direction
direction=${direction:-local->prod}

if [ "$direction" = "local->prod" ]; then
    echo ""
    echo "📤 Syncing LOCAL data → PRODUCTION"
    echo ""
    echo "Steps:"
    echo "1. Exporting local database..."
    mysqldump -u root -p smartharvest > backup_local_$(date +%Y%m%d_%H%M%S).sql
    
    echo "2. Committing changes to git..."
    git add .
    git commit -m "Data sync: migrate local changes to production"
    
    echo "3. Pushing to GitHub..."
    git push origin main
    
    echo "4. Deploying to production..."
    echo "   ⚠️  Your CI/CD pipeline (GitHub Actions) will automatically:"
    echo "   - Pull latest code"
    echo "   - Run migrations: php artisan migrate --force"
    echo "   - Run seeder: php artisan db:seed --class=MigrateProductionDataSeeder --force"
    echo ""
    echo "   Check your deployment logs at:"
    echo "   - Render Dashboard → smartharvest → Logs"
    echo "   - Or GitHub Actions → smart_harvest repo → Actions tab"
    echo ""
    
elif [ "$direction" = "prod->local" ]; then
    echo ""
    echo "📥 Syncing PRODUCTION data → LOCAL"
    echo ""
    echo "Steps:"
    echo "1. Get production database credentials"
    read -p "Enter Render DB Host (dpg-xxx.render.com): " db_host
    read -p "Enter Render DB User: " db_user
    read -sp "Enter Render DB Password: " db_pass
    echo ""
    read -p "Enter Render DB Name: " db_name
    
    echo "2. Exporting production database..."
    mysqldump -h $db_host -u $db_user -p"$db_pass" $db_name > backup_prod_$(date +%Y%m%d_%H%M%S).sql
    
    echo "3. Importing to local database..."
    mysql -u root smartharvest < backup_prod_$(date +%Y%m%d_%H%M%S).sql
    
    echo "✅ Production data imported to local!"
    echo ""
else
    echo "❌ Invalid option. Use 'local->prod' or 'prod->local'"
    exit 1
fi

echo ""
echo "✅ Sync complete!"
echo ""
