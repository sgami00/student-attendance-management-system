#!/bin/sh

envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

php artisan config:cache
php artisan view:cache
php artisan migrate --force

# Gumawa ng admin account (once lang, ignored kung nandoon na)
php artisan tinker --execute="
\App\Models\User::firstOrCreate(
    ['email' => 'teacher@school.edu'],
    ['name' => 'Admin', 'password' => bcrypt('password123')]
);
"

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisor.conf