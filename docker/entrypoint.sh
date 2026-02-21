#!/bin/sh

# Exit on error
set -e

echo "Running entrypoint script..."

# Ensure we are in the working directory
cd /var/www

# Clear and cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

# Execute the CMD
echo "Starting application..."
exec "$@"
