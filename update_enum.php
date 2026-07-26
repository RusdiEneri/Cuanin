<?php

DB::statement("ALTER TABLE products MODIFY COLUMN `condition` ENUM('BNOB','Like New','Normal','Bagus','Rusak Ringan','Rusak Parah') NOT NULL");
DB::table('products')->where('condition', 'Normal')->update(['condition' => 'Bagus']);
DB::statement("ALTER TABLE products MODIFY COLUMN `condition` ENUM('BNOB','Like New','Bagus','Rusak Ringan','Rusak Parah') NOT NULL");
