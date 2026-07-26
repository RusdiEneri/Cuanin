<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ============================================
        // 1. USERS
        // ============================================
        $admin = User::firstOrCreate(
            ['email' => 'admin@cuanin.com'],
            [
                'name'         => 'Administrator Cuanin',
                'password'     => bcrypt('password'),
                'role'         => 'admin',
                'phone_number' => '081200000001',
                'address'      => 'Kantor Pusat Cuanin',
            ]
        );

        $penjual = User::firstOrCreate(
            ['email' => 'rusdi@example.com'],
            [
                'name'         => 'Rusdi Saputra',
                'password'     => bcrypt('password'),
                'role'         => 'penjual',
                'phone_number' => '081233649676',
                'address'      => 'Jl. Kartini No.69, Gresik, Jawa Timur',
            ]
        );

        $pembeli = User::firstOrCreate(
            ['email' => 'ilham@example.com'],
            [
                'name'         => 'Ilham Prasetyo',
                'password'     => bcrypt('password'),
                'role'         => 'pembeli',
                'phone_number' => '089876543210',
                'address'      => 'Jl. Gatot Subroto No. 45, Jakarta Pusat',
            ]
        );

        // ============================================
        // 2. CATEGORIES
        // ============================================
        $categories = [
            ['name' => 'Elektronik',    'slug' => 'elektronik',    'icon' => 'smartphone'],
            ['name' => 'Pakaian',       'slug' => 'pakaian',       'icon' => 'shirt'],
            ['name' => 'Kendaraan',     'slug' => 'kendaraan',     'icon' => 'car'],
            ['name' => 'Furnitur',      'slug' => 'furnitur',      'icon' => 'sofa'],
            ['name' => 'Hobi & Mainan', 'slug' => 'hobi-mainan',   'icon' => 'gamepad-2'],
            ['name' => 'Buku',          'slug' => 'buku',          'icon' => 'book-open'],
            ['name' => 'Lainnya',       'slug' => 'lainnya',       'icon' => 'ellipsis'],
        ];

        $categoryMap = [];
        foreach ($categories as $cat) {
            $category = Category::firstOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name'], 'icon' => $cat['icon']]
            );
            $categoryMap[$cat['slug']] = $category->id;
        }

        // ============================================
        // 3. PRODUCTS + IMAGES
        // ============================================
        // ⚠️  Ganti nilai 'image_path' di bawah dengan URL gambar kamu sendiri.
        //     Contoh: 'https://cdn.tokopedia.net/img/...'
        //            'https://down-id.img.susercontent.com/...'
        //            atau path lokal: '/images/products/iphone.jpg'
        // ============================================
        $products = [
            [
                'category_slug' => 'elektronik',
                'title'         => 'iPhone 13 Pro 256GB ex iBox',
                'description'   => 'Mulus 98%, batre health 89%. Kelengkapan fullset original.',
                'price'         => 10500000,
                'condition'     => 'Like New',
                'location'      => 'Jakarta Selatan',
                'views'         => 120,
                'image_path'    => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTC9nuK_SCqlw6DKwus0ciYntpwfnnWx-fjyqlUv6FeavgEqAqWLo3GY8jo&s=10', // ← isi URL gambar di sini
            ],
            [
                'category_slug' => 'elektronik',
                'title'         => 'Samsung Galaxy S23 Ultra 512GB',
                'description'   => 'Garansi resmi SEIN, mulus 99%. Bonus case & tempered glass.',
                'price'         => 14200000,
                'condition'     => 'Like New',
                'location'      => 'Jakarta Pusat',
                'views'         => 210,
                'image_path'    => 'https://images.samsung.com/is/image/samsung/p6pim/id/2302/gallery/id-galaxy-s23-s918-sm-s918bzgqxid-thumb-534862772', // ← isi URL gambar di sini
            ],
            [
                'category_slug' => 'pakaian',
                'title'         => 'Sepatu Nike Air Max - Size 42',
                'description'   => 'Baru dipakai 2 kali, kondisi masih sangat baik seperti baru.',
                'price'         => 850000,
                'condition'     => 'Sangat Baik',
                'location'      => 'Bandung',
                'views'         => 45,
                'image_path'    => 'https://image.807garage.com/content/uploads/2025/7/air-max-plus-triple-black-gs-women-2.jpg', // ← isi URL gambar di sini
            ],
            [
                'category_slug' => 'hobi-mainan',
                'title'         => 'Sony PlayStation 5 Disc Edition',
                'description'   => 'Lengkap dengan 2 DualSense dan game Spiderman.',
                'price'         => 7800000,
                'condition'     => 'Like New',
                'location'      => 'Bekasi',
                'views'         => 312,
                'image_path'    => 'https://myhartono.com/images/detailed/380/ASIA-00479.jpg', // ← isi URL gambar di sini
            ],
            [
                'category_slug' => 'kendaraan',
                'title'         => 'Honda Vario 150 Tahun 2021',
                'description'   => 'Pajak hidup, surat lengkap. KM masih 15rb-an.',
                'price'         => 18500000,
                'condition'     => 'Sangat Baik',
                'location'      => 'Depok',
                'views'         => 890,
                'image_path'    => 'https://www.hondacengkareng.com/wp-content/uploads/2020/06/Vario-150-eSP-CBS-ISS-Exclusive-Matte-Brown.jpg', // ← isi URL gambar di sini
            ],
            [
                'category_slug' => 'furnitur',
                'title'         => 'Sofa Minimalis 3 Seater',
                'description'   => 'Baru dibeli 3 bulan, dijual karena pindah rumah. Warna abu-abu.',
                'price'         => 1200000,
                'condition'     => 'Like New',
                'location'      => 'Tangerang',
                'views'         => 155,
                'image_path'    => 'https://www.soho.id/173-superlarge_default/sofa-sofa-melinda-3-seater-cream.jpg', // ← isi URL gambar di sini
            ],
            [
                'category_slug' => 'buku',
                'title'         => 'Komik Naruto Lengkap Vol 1-72',
                'description'   => 'Kondisi 90%, halaman utuh semua tidak ada yang sobek.',
                'price'         => 1400000,
                'condition'     => 'Baik',
                'location'      => 'Bandung',
                'views'         => 620,
                'image_path'    => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRjEt3bh8xbbayhmObQXI9Wb0YPIb14hhvP0is7H-YPuw&s=10', // ← isi URL gambar di sini
            ],
            [
                'category_slug' => 'lainnya',
                'title'         => 'Kandang Kucing 3 Tingkat + Aksesoris',
                'description'   => 'Ukuran 90x60x120cm, include hammock & tempat makan.',
                'price'         => 650000,
                'condition'     => 'Baik',
                'location'      => 'Bogor',
                'views'         => 112,
                'image_path'    => 'https://down-id.img.susercontent.com/file/47200ad32980d5c124dab759fb4c101c', // ← isi URL gambar di sini
            ],
        ];

        foreach ($products as $data) {
            $categoryId = $categoryMap[$data['category_slug']];
            $slug       = Str::slug($data['title']);

            $product = Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'user_id'     => $penjual->id,
                    'category_id' => $categoryId,
                    'title'       => $data['title'],
                    'description' => $data['description'],
                    'price'       => $data['price'],
                    'condition'   => $data['condition'],
                    'location'    => $data['location'],
                    'status'      => 'active',
                    'views'       => $data['views'],
                ]
            );

            // Hanya buat record gambar jika image_path tidak kosong
            if (!empty($data['image_path'])) {
                ProductImage::firstOrCreate(
                    ['product_id' => $product->id, 'is_primary' => true],
                    [
                        'image_path' => $data['image_path'],
                    ]
                );
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Categories: ' . Category::count());
        $this->command->info('Products:   ' . Product::count());
        $this->command->info('Users:      ' . User::count());
    }
}