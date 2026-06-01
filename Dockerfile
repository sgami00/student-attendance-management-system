# 1. Gamitin ang opisyal na PHP Alpine image (magaan at mabilis i-deploy)
FROM php:8.2-fpm-alpine

# 2. I-install ang mga kailangang system dependencies at PHP extensions para sa Laravel
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    libzip-dev

RUN docker-php-ext-install pdo_mysql mbstring exifr pcntl bcmath gd zip

# 3. Kuhanin ang pinakabagong bersyon ng Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. I-set ang working directory sa loob ng container
WORKDIR /var/www

# 5. Kopyahin ang lahat ng files ng iyong project papunta sa container
COPY . /var/www

# 6. I-install ang mga PHP dependencies (Pang-production setup)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 7. Ayusin ang file permissions para sa storage at cache ng Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 8. Kopyahin ang mga custom configuration files para sa Nginx at Supervisor
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# 9. Buksan ang Port 80 para sa web traffic
EXPOSE 80

# 10. Patakbuhin ang Supervisor para sabay na umandar ang Nginx at PHP-FPM
CMD ["/usr/bin/supervisorc", "-c", "/etc/supervisor/conf.d/supervisor.conf"]