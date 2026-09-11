#!/bin/sh
set -e

cd /var/www/html

# Cache config, routes, views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start PHP-FPM in background
php-fpm -D

# Start nginx in foreground
nginx -g "daemon off;"
