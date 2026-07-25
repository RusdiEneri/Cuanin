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
        // Alter enum to add 'draft' status
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('active', 'sold', 'archived', 'draft') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert: remove 'draft' from enum
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('active', 'sold', 'archived') DEFAULT 'active'");
    }
};
