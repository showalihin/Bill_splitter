#!/bin/bash

# Start PHP-FPM in the background
php-fpm -D

# Run migrations (force runs them in production without asking)
php artisan migrate --force

# Cache configuration and routes for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Nginx in the foreground
nginx -g "daemon off;"
