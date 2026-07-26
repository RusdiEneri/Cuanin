<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah sementara tipe data ke VARCHAR untuk menghindari strict mode Enum
        DB::statement('ALTER TABLE products MODIFY `condition` VARCHAR(50) NOT NULL');
        
        // Pemetaan data lama ke opsi baru
        DB::table('products')->where('condition', 'Barang Baru')->update(['condition' => 'BNOB']);
        DB::table('products')->whereIn('condition', ['Sangat Baik'])->update(['condition' => 'Like New']);
        DB::table('products')->whereIn('condition', ['Baik', 'Cukup'])->update(['condition' => 'Normal']);
        
        // Kembalikan ke tipe data ENUM dengan opsi baru
        DB::statement("ALTER TABLE products MODIFY `condition` ENUM('BNOB', 'Like New', 'Normal', 'Rusak Ringan', 'Rusak Parah') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE products MODIFY `condition` VARCHAR(50) NOT NULL');
        
        DB::table('products')->where('condition', 'BNOB')->update(['condition' => 'Barang Baru']);
        // Like New tetap
        // Normal kembali ke Baik secara default (karena mapping Cukup tidak bisa di reverse 1:1)
        DB::table('products')->where('condition', 'Normal')->update(['condition' => 'Baik']);
        
        DB::statement("ALTER TABLE products MODIFY `condition` ENUM('Barang Baru', 'Like New', 'Sangat Baik', 'Baik', 'Cukup', 'Rusak Ringan') NOT NULL");
    }
};
