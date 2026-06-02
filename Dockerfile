# 1. Gamitin ang PHP 8.4
FROM php:8.4-fpm-alpine

# 2. I-install ang mga system dependencies + Node.js + gettext (para sa envsubst)
RUN apk add --no-cache \
    nginx supervisor curl libpng-dev libxml2-dev zip unzip git \
    oniguruma-dev libzip-dev nodejs npm gettext

# 3. I-install ang PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 4. I-copy ang Composer mula sa opisyal na image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. I-set ang working directory
WORKDIR /var/www

# 6. I-copy ang files mula sa iyong project
COPY . .

# 7. I-install ang PHP dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 8. I-install ang JS dependencies at i-build ang Vite/Tailwind assets
RUN npm install --ignore-scripts && npm run build

# 9. Setup ng permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 10. I-copy ang config files
COPY docker/nginx.conf /etc/nginx/nginx.conf.template
COPY docker/supervisor.conf /etc/supervisor/conf.d/supervisor.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80
CMD ["/start.sh"]