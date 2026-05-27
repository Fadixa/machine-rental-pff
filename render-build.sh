#!/usr/bin/env bash
set -o errexit

composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

if [ -f "package.json" ]; then
    npm install
    npm run build
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force
php artisan storage:link