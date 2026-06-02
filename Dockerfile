FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    nginx supervisor curl libpng-dev libxml2-dev zip unzip git \
    oniguruma-dev libzip-dev nodejs npm gettext

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-dev

RUN npm install --ignore-scripts && npm run build

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf.template
COPY docker/supervisor.conf /etc/supervisor/conf.d/supervisor.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80
CMD ["/start.sh"]