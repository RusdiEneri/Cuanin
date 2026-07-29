<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Sesuaikan daftar value dengan yang benar-benar kamu pakai di seeder & blade
        DB::statement("
            ALTER TABLE products
            MODIFY COLUMN `condition`
            ENUM('Barang Baru', 'Like New', 'Sangat Baik', 'Baik', 'Cukup')
            NOT NULL DEFAULT 'Baik'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE products
            MODIFY COLUMN `condition`
            ENUM('Barang Baru', 'Bekas')
            NOT NULL DEFAULT 'Bekas'
        ");
    }
};
