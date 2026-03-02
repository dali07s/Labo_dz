#!/bin/sh
set -e

# Ensure necessary Laravel directories exist
mkdir -p storage/framework/sessions \
         storage/framework/views \
         storage/framework/cache \
         storage/logs \
         bootstrap/cache

# Run migrations if RUN_MIGRATIONS is set
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

# Ensure storage and bootstrap/cache are writable
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Optimize Laravel (Optional but recommended for production)
if [ "$APP_ENV" = "production" ]; then
    echo "Optimizing Laravel for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Start Apache in foreground
echo "Starting Apache..."
exec apache2-foreground
