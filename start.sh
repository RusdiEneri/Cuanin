#!/bin/sh

# Optimize cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan Migrasi + Seeder
php artisan migrate --force --seed

# Symlink storage
php artisan storage:link || true

# Jalankan Web Server (Wajib di baris paling akhir)
php artisan serve --host=0.0.0.0 --port=$PORT