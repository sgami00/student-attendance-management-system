FROM php:8.4-fpm-alpine
RUN apk add --no-cache nginx supervisor curl libpng-dev libxml2-dev zip unzip git oniguruma-dev libzip-dev
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip
WORKDIR /var/www
COPY . .
RUN composer install --no-interaction --optimize-autoloader --no-dev
EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisor.conf"]