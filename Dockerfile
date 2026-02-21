FROM php:8.1-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    openssl-dev \
    freetype-dev \
    jpeg-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    nginx \
    nodejs \
    npm \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . /var/www

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install NPM dependencies and build assets
RUN npm install && npm run build

# Remove node modules after build to keep image small
RUN rm -rf node_modules

# Set proper permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy Nginx configuration
COPY docker/nginx/railway.conf /etc/nginx/http.d/default.conf

# Setup startup script
RUN chmod +x /var/www/docker/startup.sh

# Railway typically uses 8080 or the PORT env var
EXPOSE 8080

# Start services via script
CMD ["/var/www/docker/startup.sh"]
