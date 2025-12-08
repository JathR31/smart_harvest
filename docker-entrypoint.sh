#!/bin/bash
set -e

echo "Starting SmartHarvest deployment..."

# Wait for database to be ready
echo "Waiting for database connection..."
php artisan wait-for-db

# Run migrations
echo "Running database migrations..."
php artisan migrate --force --no-interaction

# Create storage link
echo "Creating storage symlink..."
php artisan storage:link --force

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

echo "Deployment setup complete!"

# Start supervisor
exec "$@"
