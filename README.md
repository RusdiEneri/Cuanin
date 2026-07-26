
---

<div align="center">
    <p>
        <img align="center" alt="count" src="./public/logo.png">
    </p>
</div>

---

# Cuanin - Marketplace Barang Bekas
Cuanin adalah marketplace untuk jual beli barang bekas (preloved). Pengguna dapat menjelajah produk, memasukkan ke keranjang, mengajukan nego harga, dan melakukan pemesanan langsung melalui WhatsApp penjual.

## Prasyarat
- PHP >= 8.2 [Link Download](https://www.php.net/downloads.php)
- Composer [Link Download](https://getcomposer.org/download/)
- Node.js & NPM [Link Download](https://nodejs.org/en/download)
- MySQL Xampp [Link Download Xampp](https://www.apachefriends.org/download.html) / Laragon [Link Download Laragon](https://laragon.org/download)
- Git [Link Download](https://git-scm.com/install/windows)

---

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
Windows (CMD):
```bash
copy .env.example .env
```
Linux / macOS:
```bash
cp .env.example .env
```
Lalu generate key aplikasi:
```bash
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env` sesuai pengaturan MySQL kamu:
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

### 6. Setup Isi Database — ⚠️ PILIH SALAH SATU

> **Jangan jalankan keduanya.** `migrate:fresh` akan **menghapus** seluruh tabel (termasuk data hasil import backup).

**Opsi A — Migration & Seeder (setup dari nol / data contoh):**
```bash
php artisan migrate:fresh --seed
```

**Opsi B — Import Backup (memulihkan data yang sudah ada):**
```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS db_cuanin;"
mysql -u root -p db_cuanin < backup_database.sql
```

### 7. Buat Symbolic Link Storage
Wajib agar gambar produk & avatar yang diunggah bisa ditampilkan:
```bash
php artisan storage:link
```

### 8. Bersihkan Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### 9. Build Asset
```bash
npm run build
```

### 10. Jalankan Aplikasi

**Cara 1 — Satu perintah (recommended).**
```bash
npm run dev
```

**Cara 2 — Dua terminal terpisah.**
```bash
# Terminal 1 (Vite)
npm run vite

# Terminal 2 (Laravel)
npm run php
```

Akses aplikasi di: **http://localhost:8000**
> Karena memakai `--host=0.0.0.0`, aplikasi juga bisa dibuka dari perangkat lain (mis. HP) di jaringan yang sama melalui `http://IP-LOKAL-PC:8000`.

---

## Backup & Restore Database

**Backup** (export MySQL lokal ke `backup_database.sql`):
```bash
mysqldump -u root -p db_cuanin > backup_database.sql
```

**Restore / Import** (memulihkan `backup_database.sql` ke MySQL lokal):
```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS db_cuanin;"
mysql -u root -p db_cuanin < backup_database.sql
```
> Catatan: untuk restore gunakan `mysql`, **bukan** `mysqldump`. `mysqldump` hanya untuk export.

---

## Panduan untuk Collaborators

```bash
# 1. Ambil update terbaru dari branch staging
git pull origin staging

# 2. Buat branch fitur baru (ganti nama-kamu dengan namamu)
git switch -c add/nama-kamu

# 3. Push branch fitur ke remote
git push origin add/nama-kamu
```

---

## Akses & Login Default

| Role    | Email              | Password   |
|---------|--------------------|------------|
| Penjual | rusdi@example.com  | `password` |
| Pembeli | ilham@example.com  | `password` |

---

## Struktur Database
- `users`
- `categories`
- `products`
- `product_images`
- `carts`
- `wishlists`
- `negotiations`
<!-- - `reviews` -->

---

## Fitur
- Multi-role (Pembeli & Penjual)
- Upload gambar produk (maksimal 5 foto)
- Status produk (Aktif / Terjual / Diarsipkan)
- Wishlist & Keranjang belanja (dengan pilihan per produk & hapus semua)
- Nego harga antara pembeli dan penjual
- Pemesanan langsung diarahkan via WhatsApp penjual
- Review produk