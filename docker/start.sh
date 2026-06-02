#!/bin/sh
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf
php artisan migrate --force
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisor.conf