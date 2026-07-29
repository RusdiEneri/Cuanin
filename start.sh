#!/bin/sh

# Optimize cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan Migrasi + Seeder
php artisan migrate --force --seed

# Symlink storage
php artisan storage:link || true