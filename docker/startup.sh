#!/bin/sh

# Set working directory
cd /var/www

# Run migrations if database is available
# (Usually handle via Railway's post-deploy or here)
# php artisan migrate --force

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
nginx -g "daemon off;"
