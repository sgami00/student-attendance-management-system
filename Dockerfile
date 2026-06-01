# 1. Gamitin ang PHP 8.4
FROM php:8.4-fpm-alpine

# 2. I-install ang mga system dependencies
RUN apk add --no-cache \
    nginx supervisor curl libpng-dev libxml2-dev zip unzip git oniguruma-dev libzip-dev

# 3. I-install ang PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 4. I-copy ang Composer mula sa opisyal na image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. I-set ang working directory
WORKDIR /var/www

# 6. I-copy ang files mula sa iyong project
COPY . .

# 7. I-install ang dependencies
# Siguraduhin na ang composer.json ay nasa root folder mo
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 8. Setup ng permissions at configuration
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisor.conf"]