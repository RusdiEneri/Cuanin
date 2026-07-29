#!/bin/sh

# Hapus penanda dev server Vite jika terbawa dari lokal
rm -f public/hot

# Clear & Cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan migrasi & seeder
php artisan migrate --force --seed || true

# Symlink storage
php artisan storage:link || true

# Jalankan server bawaan Laravel dengan port terisolasi
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"