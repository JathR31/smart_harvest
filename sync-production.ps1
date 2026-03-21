# SmartHarvest Data Sync Script for Windows
# Syncs local database changes to production (Laravel Cloud)
# Usage: .\sync-production.ps1

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "SmartHarvest Data Sync Tool (Laravel Cloud)" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# Function to export local database
function Export-LocalDatabase {
    $timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
    $backupFile = "backup_local_$timestamp.sql"
    
    Write-Host "📤 Exporting local database..." -ForegroundColor Yellow
    
    # Using XAMPP MySQL
    $mysqlPath = "C:\xampp\mysql\bin\mysqldump.exe"
    
    if (Test-Path $mysqlPath) {
        & $mysqlPath -u root smartharvest | Out-File -FilePath $backupFile -Encoding UTF8
        Write-Host "✅ Backup saved: $backupFile" -ForegroundColor Green
        return $backupFile
    } else {
        Write-Host "❌ MySQL not found at expected location" -ForegroundColor Red
        return $null
    }
}

# Function to sync to production
function Sync-ToProduction {
    Write-Host ""
    Write-Host "📤 Syncing LOCAL → PRODUCTION (Laravel Cloud)" -ForegroundColor Yellow
    Write-Host ""
    
    # Step 1: Export local database
    $backup = Export-LocalDatabase
    
    if ($null -eq $backup) {
        Write-Host "❌ Backup failed. Aborting sync." -ForegroundColor Red
        return
    }
    
    # Step 2: Git operations
    Write-Host ""
    Write-Host "📝 Committing to git..." -ForegroundColor Yellow
    
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    
    Write-Host "   Stage 1: Add changes..."
    git add -A
    
    Write-Host "   Stage 2: Commit changes..."
    git commit -m "Data sync: local changes migrated to production at $timestamp"
    
    Write-Host "   Stage 3: Push to GitHub..."
    git push origin main
    
    # Step 3: Instructions
    Write-Host ""
    Write-Host "✅ Git push complete!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Next steps - LARAVEL CLOUD WILL AUTOMATICALLY DEPLOY:" -ForegroundColor Cyan
    Write-Host "  1. Detect your push on GitHub"
    Write-Host "  2. Build and deploy your application"
    Write-Host "  3. Run migrations: php artisan migrate --force"
    Write-Host "  4. Run seeder: php artisan db:seed --class=MigrateProductionDataSeeder --force"
    Write-Host ""
    Write-Host "Monitor deployment at:" -ForegroundColor Yellow
    Write-Host "  📊 Laravel Cloud Dashboard → Deployments"
    Write-Host "  🔗 https://cloud.laravel.com"
    Write-Host "  📌 Or check GitHub Actions: https://github.com/JathR31/smart_harvest/actions"
    Write-Host ""
    Write-Host "⏱️  Deployment usually completes in 5-10 minutes" -ForegroundColor Cyan
}

# Function to sync FROM production
function Sync-FromProduction {
    Write-Host ""
    Write-Host "📥 Syncing PRODUCTION → LOCAL (from Laravel Cloud)" -ForegroundColor Yellow
    Write-Host ""
    
    # Get production credentials
    Write-Host "Enter your Laravel Cloud database credentials:" -ForegroundColor Cyan
    Write-Host "  (Find these in Laravel Cloud Dashboard → Database)" -ForegroundColor Gray
    Write-Host ""
    
    $dbHost = Read-Host "  DB Host"
    $dbUser = Read-Host "  DB User"
    $dbName = Read-Host "  DB Name"
    
    $securePass = Read-Host "  DB Password" -AsSecureString
    $dbPass = [System.Net.NetworkCredential]::new('', $securePass).Password
    
    $timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
    $backupFile = "backup_prod_$timestamp.sql"
    
    # Export from production
    Write-Host ""
    Write-Host "📥 Exporting production database..." -ForegroundColor Yellow
    
    $mysqlPath = "C:\xampp\mysql\bin\mysqldump.exe"
    
    if (Test-Path $mysqlPath) {
        & $mysqlPath -h $dbHost -u $dbUser -p"$dbPass" $dbName | Out-File -FilePath $backupFile -Encoding UTF8
        Write-Host "✅ Backup saved: $backupFile" -ForegroundColor Green
        
        # Import to local
        Write-Host ""
        Write-Host "📥 Importing to local database..." -ForegroundColor Yellow
        
        $mysqlClientPath = "C:\xampp\mysql\bin\mysql.exe"
        Get-Content $backupFile | & $mysqlClientPath -u root smartharvest
        
        Write-Host "✅ Production data imported to local!" -ForegroundColor Green
        Write-Host ""
        Write-Host "💡 Your local database now matches production" -ForegroundColor Cyan
    } else {
        Write-Host "❌ MySQL not found at expected location" -ForegroundColor Red
    }
}

# Main menu
Write-Host "Select sync direction:" -ForegroundColor Cyan
Write-Host "  1. LOCAL → PRODUCTION (push local changes live)" -ForegroundColor Yellow
Write-Host "  2. PRODUCTION → LOCAL (pull production data locally)" -ForegroundColor Yellow
Write-Host ""

$choice = Read-Host "Enter choice (1 or 2)"

switch ($choice) {
    "1" { Sync-ToProduction }
    "2" { Sync-FromProduction }
    default { 
        Write-Host "❌ Invalid choice" -ForegroundColor Red
        exit 1
    }
}

Write-Host ""
Write-Host "Done!" -ForegroundColor Green
