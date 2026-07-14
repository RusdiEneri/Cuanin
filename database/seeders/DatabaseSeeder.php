<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Budi Setiawan',
            'email' => 'budi@example.com',
            'password' => bcrypt('password'),
            'role' => 'penjual',
            'phone_number' => '081234567890',
            'address' => 'Jl. Sudirman No. 123, Jakarta Selatan',
        ]);

        $categories = [
            ['name' => 'Elektronik', 'slug' => 'elektronik', 'icon' => 'smartphone'],
            ['name' => 'Pakaian', 'slug' => 'pakaian', 'icon' => 'shirt'],
            ['name' => 'Kendaraan', 'slug' => 'kendaraan', 'icon' => 'car'],
            ['name' => 'Furnitur', 'slug' => 'furnitur', 'icon' => 'sofa'],
            ['name' => 'Hobi & Mainan', 'slug' => 'hobi-mainan', 'icon' => 'gamepad-2'],
            ['name' => 'Buku', 'slug' => 'buku', 'icon' => 'book-open'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::create($cat);
        }

        $product1 = \App\Models\Product::create([
            'user_id' => $user->id,
            'category_id' => 1,
            'title' => 'iPhone 13 Pro 256GB ex iBox',
            'slug' => 'iphone-13-pro-256gb-ex-ibox',
            'description' => 'Mulus 98%, batre health 89%. Kelengkapan fullset original.',
            'price' => 10500000,
            'condition' => 'Like New',
            'location' => 'Jakarta Selatan',
            'status' => 'active',
            'views' => 120,
        ]);

        \App\Models\ProductImage::create([
            'product_id' => $product1->id,
            'image_path' => 'dummy/iphone.jpg', // we will use fallback in view if file not exist
            'is_primary' => true,
        ]);

        $product2 = \App\Models\Product::create([
            'user_id' => $user->id,
            'category_id' => 2,
            'title' => 'Sepatu Nike Air Max - Size 42',
            'slug' => 'sepatu-nike-air-max-size-42',
            'description' => 'Baru dipakai 2 kali, kondisi masih sangat baik seperti baru.',
            'price' => 850000,
            'condition' => 'Sangat Baik',
            'location' => 'Bandung',
            'status' => 'active',
            'views' => 45,
        ]);
        
        \App\Models\ProductImage::create([
            'product_id' => $product2->id,
            'image_path' => 'dummy/shoes.jpg',
            'is_primary' => true,
        ]);
        
        $product3 = \App\Models\Product::create([
            'user_id' => $user->id,
            'category_id' => 1,
            'title' => 'Keyboard Mechanical Keychron K2',
            'slug' => 'keyboard-mechanical-keychron-k2',
            'description' => 'Switch red, kondisi normal tidak ada double type.',
            'price' => 1100000,
            'condition' => 'Baik',
            'location' => 'Surabaya',
            'status' => 'active',
            'views' => 230,
        ]);
        
        \App\Models\ProductImage::create([
            'product_id' => $product3->id,
            'image_path' => 'dummy/macbook.jpg', // Placeholder for keychron/macbook
            'is_primary' => true,
        ]);

        $product4 = \App\Models\Product::create([
            'user_id' => $user->id,
            'category_id' => 1,
            'title' => 'MacBook Air M1 2020 8/256GB',
            'slug' => 'macbook-air-m1-2020',
            'description' => 'Pemakaian pribadi untuk ngoding, battery cycle count 120. Sangat mulus.',
            'price' => 11500000,
            'condition' => 'Sangat Baik',
            'location' => 'Jakarta Barat',
            'status' => 'active',
            'views' => 450,
        ]);

        \App\Models\ProductImage::create([
            'product_id' => $product4->id,
            'image_path' => 'dummy/macbook.jpg',
            'is_primary' => true,
        ]);

        $product5 = \App\Models\Product::create([
            'user_id' => $user->id,
            'category_id' => 5, // Hobi
            'title' => 'Sony PlayStation 5 Disc Edition',
            'slug' => 'sony-playstation-5',
            'description' => 'Lengkap dengan 2 DualSense dan game Spiderman.',
            'price' => 7800000,
            'condition' => 'Like New',
            'location' => 'Bekasi',
            'status' => 'active',
            'views' => 312,
        ]);

        \App\Models\ProductImage::create([
            'product_id' => $product5->id,
            'image_path' => 'dummy/ps5.jpg',
            'is_primary' => true,
        ]);

        $product6 = \App\Models\Product::create([
            'user_id' => $user->id,
            'category_id' => 3, // Kendaraan
            'title' => 'Honda Vario 150 Tahun 2021',
            'slug' => 'honda-vario-150-2021',
            'description' => 'Pajak hidup, surat lengkap. KM masih 15rb-an.',
            'price' => 18500000,
            'condition' => 'Sangat Baik',
            'location' => 'Depok',
            'status' => 'active',
            'views' => 890,
        ]);

        \App\Models\ProductImage::create([
            'product_id' => $product6->id,
            'image_path' => 'dummy/vario.jpg',
            'is_primary' => true,
        ]);

        $product7 = \App\Models\Product::create([
            'user_id' => $user->id,
            'category_id' => 4, // Furnitur
            'title' => 'Sofa Minimalis 3 Seater',
            'slug' => 'sofa-minimalis-3-seater',
            'description' => 'Baru dibeli 3 bulan, dijual karena pindah rumah. Warna abu-abu.',
            'price' => 1200000,
            'condition' => 'Like New',
            'location' => 'Tangerang',
            'status' => 'active',
            'views' => 155,
        ]);

        \App\Models\ProductImage::create([
            'product_id' => $product7->id,
            'image_path' => 'dummy/sofa.jpg',
            'is_primary' => true,
        ]);

        $product8 = \App\Models\Product::create([
            'user_id' => $user->id,
            'category_id' => 6, // Buku
            'title' => 'Komik Naruto Lengkap Vol 1-72',
            'slug' => 'komik-naruto-lengkap',
            'description' => 'Kondisi 90%, halaman utuh semua tidak ada yang sobek.',
            'price' => 1400000,
            'condition' => 'Baik',
            'location' => 'Bandung',
            'status' => 'active',
            'views' => 620,
        ]);

        \App\Models\ProductImage::create([
            'product_id' => $product8->id,
            'image_path' => 'dummy/komik.jpg',
            'is_primary' => true,
        ]);
    }
}
