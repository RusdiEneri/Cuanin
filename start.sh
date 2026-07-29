#!/bin/sh

# Hapus penanda dev server Vite
rm -f public/hot

# Optimization Cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migrasi & Seeder
php artisan migrate --force --seed || true

# Symlink storage
php artisan storage:link || true

# Jalankan Nginx + PHP-FPM bawaan Nixpacks (Bukan artisan serve)
php-fpm -D && nginx -g 'daemon off;'