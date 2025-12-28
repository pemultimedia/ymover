<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddStorageModule extends AbstractMigration
{
    public function up(): void
    {
        $sql = <<<'SQL'
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Anagrafica Magazzini
CREATE TABLE `warehouses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL, -- Es. "Deposito Nord"
  `code` VARCHAR(50) DEFAULT NULL, -- Es. "Box A12"
  `address` VARCHAR(255) NOT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `lat` DECIMAL(10, 8) DEFAULT NULL, -- Coordinate per routing
  `lng` DECIMAL(11, 8) DEFAULT NULL,
  `total_capacity_m3` DECIMAL(10, 2) DEFAULT 0.00,
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_warehouses_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Modifica agli Stop (Per linkare un deposito come tappa)
ALTER TABLE `stops` ADD COLUMN `warehouse_id` BIGINT UNSIGNED DEFAULT NULL AFTER `request_id`;
ALTER TABLE `stops` ADD CONSTRAINT `fk_stops_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL;

-- 3. Contratti di Deposito (Aspetto Economico)
CREATE TABLE `storage_contracts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `customer_id` BIGINT UNSIGNED NOT NULL,
  `warehouse_id` BIGINT UNSIGNED NOT NULL,
  `request_id` BIGINT UNSIGNED DEFAULT NULL, -- Richiesta di origine (se c'è)
  
  `start_date` DATE NOT NULL,
  `end_date_expected` DATE DEFAULT NULL,
  `end_date_actual` DATE DEFAULT NULL,
  
  -- Billing
  `billing_cycle` ENUM('daily', 'weekly', 'monthly') DEFAULT 'monthly',
  `payment_type` ENUM('prepaid', 'postpaid') DEFAULT 'prepaid',
  `price_per_period` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  
  -- Assicurazione
  `insurance_declared_value` DECIMAL(10, 2) DEFAULT 0.00,
  `insurance_cost` DECIMAL(10, 2) DEFAULT 0.00,
  
  `status` ENUM('active', 'closed', 'payment_issue') DEFAULT 'active',
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_storage_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_storage_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_storage_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Movimenti di Magazzino (Log Operativo & Bolle)
CREATE TABLE `storage_movements` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `storage_contract_id` BIGINT UNSIGNED NOT NULL,
  `type` ENUM('in', 'out') NOT NULL,
  `date` DATETIME NOT NULL,
  `operator_id` BIGINT UNSIGNED DEFAULT NULL, -- Chi ha fatto il carico/scarico
  
  -- Snapshot degli oggetti movimentati (JSON Array di items)
  -- Es: [{"desc": "Divano", "vol": 2.5, "qty": 1}, ...]
  `items_snapshot` JSON NOT NULL, 
  `total_volume_m3` DECIMAL(10, 2) NOT NULL,
  
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_movements_contract` FOREIGN KEY (`storage_contract_id`) REFERENCES `storage_contracts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
SQL;
        $this->execute($sql);
    }
}
