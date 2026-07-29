<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah condition dari ENUM -> VARCHAR supaya tidak lagi "data truncated"
        // untuk nilai apa pun (BNOB, BNIB, Like New, Sangat Baik, dll.)
        DB::statement("ALTER TABLE products MODIFY COLUMN `condition` VARCHAR(50) NOT NULL DEFAULT 'Bekas'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE products MODIFY COLUMN `condition` ENUM('Barang Baru','Bekas') NOT NULL DEFAULT 'Bekas'");
    }
};