ALTER TABLE products MODIFY COLUMN `condition` ENUM('BNOB','Like New','Normal','Bagus','Rusak Ringan','Rusak Parah') NOT NULL;
UPDATE products SET `condition` = 'Bagus' WHERE `condition` = 'Normal';
ALTER TABLE products MODIFY COLUMN `condition` ENUM('BNOB','Like New','Bagus','Rusak Ringan','Rusak Parah') NOT NULL;
