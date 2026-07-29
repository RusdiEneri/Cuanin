#!/bin/sh

# Cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan migrasi & seeder (ditambah || true agar error seeder tidak mematikan server)
php artisan migrate --force --seed || true

# Symlink storage
php artisan storage:link || true

# Wajib di paling bawah: Jalankan server
# php artisan serve --host=0.0.0.0 
npm run build
npm run dev