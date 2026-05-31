FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www/html

# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip intl

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application
COPY --chown=www-data:www-data . /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --prefer-dist

# Install Node dependencies and build
RUN npm ci && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create storage symlink
RUN php artisan storage:link || true

# Expose port
EXPOSE 9000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:9000/up || exit 1

# Start PHP-FPM
CMD ["php-fpm"]
