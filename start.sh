#!/bin/sh

# Hapus file 'hot' agar Laravel Wajib memakai hasil build Vite di public/build
rm -f public/hot

# Cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan migrasi & seeder
php artisan migrate --force --seed || true

# Symlink storage
php artisan storage:link || true

# Jalankan server (Wajib pakai --port=$PORT agar terhubung ke Railway)
php artisan serve --host=0.0.0.0