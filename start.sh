#!/bin/sh
# Hapus penanda dev server Vite kalau kebawa dari lokal
rm -f public/hot

# Cache Laravel (gagal pun tidak menggagalkan start)
php artisan config:cache || true
php artisan route:cache  || true
php artisan view:cache   || true

# Migrasi + seed awal (seeder kamu pakai firstOrCreate, jadi aman diulang)
php artisan migrate --force --seed || true

# Symlink storage (biar gambar bisa diakses)
php artisan storage:link || true

# Jalankan server Laravel di port Railway
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"