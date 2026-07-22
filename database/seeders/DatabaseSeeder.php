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
        // 2. CATEGORIES (lama + baru)
        // ============================================
        $categories = [
            // --- Kategori Lama ---
            ['name' => 'Elektronik',           'slug' => 'elektronik',           'icon' => 'smartphone'],
            ['name' => 'Pakaian',              'slug' => 'pakaian',              'icon' => 'shirt'],
            ['name' => 'Kendaraan',            'slug' => 'kendaraan',            'icon' => 'car'],
            ['name' => 'Furnitur',             'slug' => 'furnitur',             'icon' => 'sofa'],
            ['name' => 'Hobi & Mainan',        'slug' => 'hobi-mainan',          'icon' => 'gamepad-2'],
            ['name' => 'Buku',                 'slug' => 'buku',                 'icon' => 'book-open'],

            // // --- Kategori Baru ---
            // ['name' => 'Komputer & Aksesoris', 'slug' => 'komputer-aksesoris',   'icon' => 'laptop'],
            // ['name' => 'Olahraga & Outdoor',   'slug' => 'olahraga-outdoor',     'icon' => 'dumbbell'],
            // ['name' => 'Kesehatan & Kecantikan','slug' => 'kesehatan-kecantikan','icon' => 'heart-pulse'],
            // ['name' => 'Makanan & Minuman',    'slug' => 'makanan-minuman',      'icon' => 'utensils'],
            // ['name' => 'Properti',             'slug' => 'properti',             'icon' => 'building'],
            // ['name' => 'Jasa & Layanan',       'slug' => 'jasa-layanan',         'icon' => 'wrench'],
            // ['name' => 'Musik & Instrumen',    'slug' => 'musik-instrumen',      'icon' => 'guitar'],
            // ['name' => 'Perlengkapan Bayi',    'slug' => 'perlengkapan-bayi',    'icon' => 'baby'],
            // ['name' => 'Hewan Peliharaan',     'slug' => 'hewan-peliharaan',     'icon' => 'paw-print'],
            // ['name' => 'Fotografi',            'slug' => 'fotografi',            'icon' => 'camera'],

            // --- Catch-all ---
            ['name' => 'Lainnya',              'slug' => 'lainnya',              'icon' => 'ellipsis'],
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
        $products = [
            // --- Elektronik (slug: elektronik) ---
            [
                'category_slug' => 'elektronik',
                'title'         => 'iPhone 13 Pro 256GB ex iBox',
                'description'   => 'Mulus 98%, batre health 89%. Kelengkapan fullset original.',
                'price'         => 10500000,
                'condition'     => 'Like New',
                'location'      => 'Jakarta Selatan',
                'views'         => 120,
                'image_seed'    => 'iphone',
            ],
            [
                'category_slug' => 'elektronik',
                'title'         => 'Samsung Galaxy S23 Ultra 512GB',
                'description'   => 'Garansi resmi SEIN, mulus 99%. Bonus case & tempered glass.',
                'price'         => 14200000,
                'condition'     => 'Like New',
                'location'      => 'Jakarta Pusat',
                'views'         => 210,
                'image_seed'    => 'samsung',
            ],

            // --- Pakaian (slug: pakaian) ---
            [
                'category_slug' => 'pakaian',
                'title'         => 'Sepatu Nike Air Max - Size 42',
                'description'   => 'Baru dipakai 2 kali, kondisi masih sangat baik seperti baru.',
                'price'         => 850000,
                'condition'     => 'Sangat Baik',
                'location'      => 'Bandung',
                'views'         => 45,
                'image_seed'    => 'shoes',
            ],

            // // --- Komputer & Aksesoris (slug: komputer-aksesoris) ---
            // [
            //     'category_slug' => 'komputer-aksesoris',
            //     'title'         => 'Keyboard Mechanical Keychron K2',
            //     'description'   => 'Switch red, kondisi normal tidak ada double type.',
            //     'price'         => 1100000,
            //     'condition'     => 'Baik',
            //     'location'      => 'Surabaya',
            //     'views'         => 230,
            //     'image_seed'    => 'keyboard',
            // ],
            // [
            //     'category_slug' => 'komputer-aksesoris',
            //     'title'         => 'MacBook Air M1 2020 8/256GB',
            //     'description'   => 'Pemakaian pribadi untuk ngoding, battery cycle count 120. Sangat mulus.',
            //     'price'         => 11500000,
            //     'condition'     => 'Sangat Baik',
            //     'location'      => 'Jakarta Barat',
            //     'views'         => 450,
            //     'image_seed'    => 'macbook',
            // ],
            // [
            //     'category_slug' => 'komputer-aksesoris',
            //     'title'         => 'Monitor LG UltraWide 29" IPS',
            //     'description'   => 'Resolusi 2560x1080, cocok untuk multitasking. No dead pixel.',
            //     'price'         => 2800000,
            //     'condition'     => 'Sangat Baik',
            //     'location'      => 'Semarang',
            //     'views'         => 98,
            //     'image_seed'    => 'monitor',
            // ],

            // --- Hobi & Mainan (slug: hobi-mainan) ---
            [
                'category_slug' => 'hobi-mainan',
                'title'         => 'Sony PlayStation 5 Disc Edition',
                'description'   => 'Lengkap dengan 2 DualSense dan game Spiderman.',
                'price'         => 7800000,
                'condition'     => 'Like New',
                'location'      => 'Bekasi',
                'views'         => 312,
                'image_seed'    => 'ps5',
            ],

            // --- Kendaraan (slug: kendaraan) ---
            [
                'category_slug' => 'kendaraan',
                'title'         => 'Honda Vario 150 Tahun 2021',
                'description'   => 'Pajak hidup, surat lengkap. KM masih 15rb-an.',
                'price'         => 18500000,
                'condition'     => 'Sangat Baik',
                'location'      => 'Depok',
                'views'         => 890,
                'image_seed'    => 'motorcycle',
            ],

            // --- Furnitur (slug: furnitur) ---
            [
                'category_slug' => 'furnitur',
                'title'         => 'Sofa Minimalis 3 Seater',
                'description'   => 'Baru dibeli 3 bulan, dijual karena pindah rumah. Warna abu-abu.',
                'price'         => 1200000,
                'condition'     => 'Like New',
                'location'      => 'Tangerang',
                'views'         => 155,
                'image_seed'    => 'sofa',
            ],

            // --- Buku (slug: buku) ---
            [
                'category_slug' => 'buku',
                'title'         => 'Komik Naruto Lengkap Vol 1-72',
                'description'   => 'Kondisi 90%, halaman utuh semua tidak ada yang sobek.',
                'price'         => 1400000,
                'condition'     => 'Baik',
                'location'      => 'Bandung',
                'views'         => 620,
                'image_seed'    => 'books',
            ],

            // --- Olahraga & Outdoor (slug: olahraga-outdoor) ---
            // [
            //     'category_slug' => 'olahraga-outdoor',
            //     'title'         => 'Sepeda Gunung Polygon Xtrada 6',
            //     'description'   => 'Frame alloy, Shimano Deore 2x10 speed. Jarang dipakai.',
            //     'price'         => 4500000,
            //     'condition'     => 'Sangat Baik',
            //     'location'      => 'Malang',
            //     'views'         => 178,
            //     'image_seed'    => 'bicycle',
            // ],
            // [
            //     'category_slug' => 'olahraga-outdoor',
            //     'title'         => 'Tenda Camping Eiger Borneo 4P',
            //     'description'   => 'Kapasitas 4 orang, waterproof 3000mm. Baru dipakai 2x camping.',
            //     'price'         => 950000,
            //     'condition'     => 'Sangat Baik',
            //     'location'      => 'Yogyakarta',
            //     'views'         => 67,
            //     'image_seed'    => 'tent',
            // ],

            // --- Kesehatan & Kecantikan (slug: kesehatan-kecantikan) ---
            // [
            //     'category_slug' => 'kesehatan-kecantikan',
            //     'title'         => 'Skincare Set Somethinc Lengkap',
            //     'description'   => 'Baru beli, tidak cocok di kulit. Masih sealed 80%.',
            //     'price'         => 350000,
            //     'condition'     => 'Like New',
            //     'location'      => 'Jakarta Selatan',
            //     'views'         => 203,
            //     'image_seed'    => 'skincare',
            // ],

            // --- Makanan & Minuman (slug: makanan-minuman) ---
            // [
            //     'category_slug' => 'makanan-minuman',
            //     'title'         => 'Kopi Arabica Gayo 1kg Roasted Bean',
            //     'description'   => 'Fresh roast, medium roast level. Cocok untuk V60 & espresso.',
            //     'price'         => 185000,
            //     'condition'     => 'Baru',
            //     'location'      => 'Aceh',
            //     'views'         => 340,
            //     'image_seed'    => 'coffee',
            // ],

            // --- Properti (slug: properti) ---
            // [
            //     'category_slug' => 'properti',
            //     'title'         => 'Kost Putri Dekat UI Depok',
            //     'description'   => 'Kamar 3x4m, sudah termasuk listrik & WiFi. Dekat stasiun.',
            //     'price'         => 1200000,
            //     'condition'     => 'Baik',
            //     'location'      => 'Depok',
            //     'views'         => 520,
            //     'image_seed'    => 'kost',
            // ],

            // --- Jasa & Layanan (slug: jasa-layanan) ---
            // [
            //     'category_slug' => 'jasa-layanan',
            //     'title'         => 'Jasa Desain Logo & Branding',
            //     'description'   => 'Include 3 konsep, revisi unlimited, file AI/PNG/PDF. Pengerjaan 3-5 hari.',
            //     'price'         => 500000,
            //     'condition'     => 'Baru',
            //     'location'      => 'Remote / Online',
            //     'views'         => 89,
            //     'image_seed'    => 'design',
            // ],

            // --- Musik & Instrumen (slug: musik-instrumen) ---
            // [
            //     'category_slug' => 'musik-instrumen',
            //     'title'         => 'Gitar Akustik Yamaha F310',
            //     'description'   => 'Kondisi mulus, senar baru diganti. Cocok untuk pemula.',
            //     'price'         => 1350000,
            //     'condition'     => 'Sangat Baik',
            //     'location'      => 'Surabaya',
            //     'views'         => 145,
            //     'image_seed'    => 'guitar',
            // ],

            // --- Perlengkapan Bayi (slug: perlengkapan-bayi) ---
            // [
            //     'category_slug' => 'perlengkapan-bayi',
            //     'title'         => 'Stroller Baby Elle 3 in 1',
            //     'description'   => 'Bisa jadi car seat & bassinet. Kondisi 90%, roda masih lancar.',
            //     'price'         => 1800000,
            //     'condition'     => 'Sangat Baik',
            //     'location'      => 'Tangerang',
            //     'views'         => 76,
            //     'image_seed'    => 'stroller',
            // ],

            // --- Hewan Peliharaan (slug: hewan-peliharaan) ---
            [
                'category_slug' => 'lainnya',
                'title'         => 'Kandang Kucing 3 Tingkat + Aksesoris',
                'description'   => 'Ukuran 90x60x120cm, include hammock & tempat makan.',
                'price'         => 650000,
                'condition'     => 'Baik',
                'location'      => 'Bogor',
                'views'         => 112,
                'image_seed'    => 'catcage',
            ],

            // --- Fotografi (slug: fotografi) ---
            // [
            //     'category_slug' => 'fotografi',
            //     'title'         => 'Canon EOS M50 Mark II + Lensa Kit',
            //     'description'   => 'Shutter count 5rb-an. Include lensa 15-45mm, baterai, charger, tas.',
            //     'price'         => 8900000,
            //     'condition'     => 'Sangat Baik',
            //     'location'      => 'Jakarta Timur',
            //     'views'         => 267,
            //     'image_seed'    => 'camera',
            // ],
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

            ProductImage::firstOrCreate(
                ['product_id' => $product->id, 'is_primary' => true],
                [
                    'image_path' => "https://picsum.photos/seed/{$data['image_seed']}/400/400",
                ]
            );
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Categories: ' . Category::count());
        $this->command->info('Products:   ' . Product::count());
        $this->command->info('Users:      ' . User::count());
    }
}