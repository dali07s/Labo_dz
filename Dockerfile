# Stage 1: Build assets
FROM node:18-alpine as node-assets
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: PHP Application
FROM webdevops/php-nginx:8.1-alpine

# Set environment variables
ENV WEB_DOCUMENT_ROOT=/var/www/public
ENV APP_ENV=production

# Install system dependencies
RUN apk add --no-cache \
    libzip-dev \
    zip \
    unzip \
    git

# Copy existing application directory contents
COPY . /var/www

# Copy built assets from Stage 1
COPY --from=node-assets /app/public/build /var/www/public/build

# Set working directory
WORKDIR /var/www

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy Nginx configuration
COPY docker/nginx.conf /opt/docker/etc/nginx/vhost.conf

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set proper permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port (Railway will override this with its internal port mapping)
EXPOSE 80

# Use the entrypoint script
ENTRYPOINT ["entrypoint.sh"]

# Start Nginx and PHP-FPM
CMD ["supervisord"]
