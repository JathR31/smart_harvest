#!/bin/bash
set -e

echo "Starting SmartHarvest deployment..."

# Cache configuration first (doesn't require database)
echo "Caching configuration..."
php artisan config:cache 2>/dev/null || true
# Note: Route caching can cause issues, disabled for stability
# Routes are optimized by PHP opcoding naturally
php artisan view:cache 2>/dev/null || true

# Set proper permissions (only if needed)
echo "Verifying permissions..."
if [ -d "/var/www/html/storage" ]; then
    chmod -R 775 /var/www/html/storage 2>/dev/null || true
fi
if [ -d "/var/www/html/bootstrap/cache" ]; then
    chmod -R 775 /var/www/html/bootstrap/cache 2>/dev/null || true
fi

echo "Starting services..."

# Run database migrations and setup in background (don't block startup)
(
  # Wait for database to be ready
  echo "Waiting for database connection..."
  until php artisan db:show 2>/dev/null || php artisan migrate:status 2>/dev/null; do
    echo "Database not ready, waiting 2 seconds..."
    sleep 2
  done
  echo "Database connection successful!"

  # Run migrations
  echo "Running database migrations..."
  php artisan migrate --force --no-interaction 2>/dev/null || true

  # Create storage link
  echo "Creating storage symlink..."
  php artisan storage:link --force 2>/dev/null || true
  
  echo "Database setup complete!"
) &

echo "Deployment setup complete!"

# Start supervisor
exec "$@"
