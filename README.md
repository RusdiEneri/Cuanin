# Cuanin - Marketplace Barang Bekas

## Prasyarat
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL
- Git

## Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/RusdiEneri/Cuanin.git
cd Cuanin
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
copy .env.example .env
php artisan key:generate
```
### 4. Konfigurasi Database
Edit file .env:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_cuanin
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Buat Database
```sql
CREATE DATABASE db_cuanin;
```

### 6. Import Database (Jika ada backup)
```bash
mysql -u root -p db_cuanin < backup_database.sql
```

### 7. Jalankan Migration & Seeder
```bash
php artisan migrate:fresh --seed
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

<!-- ### 8. Setup Storage (PENTING untuk gambar) 
```bash
php artisan storage:link
``` -->

<!-- ### 6. Copy Folder Gambar (Jika ada backup)
Copy folder `storage/app/public/` dari backup ke project Anda. -->

### 9. Build Asset
```bash
npm run build
```

### 10. Jalankan Aplikasi
```bash
npm run dev:all
```

Akses: http://localhost:8000

### Login Default
- Email: rusdi@example.com
- Password: password

### Login Default
- Email: ilham@example.com
- Password: password

### Struktur Database
- users
- categories
- products
- product_images
- carts
- wishlists
- orders
- order_items
- negotiations
- reviews
- Fitur
- Multi-role (Pembeli & Penjual)
- Upload gambar produk (max 5 foto)
- Status produk (Aktif/Terjual/Diarsipkan)
- Wishlist & Keranjang
- Nego harga
- Review produk
