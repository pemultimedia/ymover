SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `entity_type` varchar(50) NOT NULL,
  `entity_id` bigint(20) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` int(10) UNSIGNED NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `calendar_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `resource_id` bigint(20) UNSIGNED DEFAULT NULL,
  `request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `type` enum('job','inspection','unavailable','maintenance') DEFAULT 'job'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('private','company') DEFAULT 'private',
  `name` varchar(255) NOT NULL,
  `tax_code` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `customer_contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('email','phone','mobile','whatsapp','residence_address','billing_address','tax_code','vat_number','sdi_code') NOT NULL,
  `value` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `label` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `email_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `direction` enum('inbound','outbound') NOT NULL,
  `sender` varchar(255) NOT NULL,
  `recipient` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body_text` longtext DEFAULT NULL,
  `body_html` longtext DEFAULT NULL,
  `message_uid` varchar(255) DEFAULT NULL,
  `has_attachments` tinyint(1) DEFAULT 0,
  `received_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `inventory_blocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version_id` bigint(20) UNSIGNED NOT NULL,
  `origin_stop_id` bigint(20) UNSIGNED DEFAULT NULL,
  `destination_stop_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(100) DEFAULT 'Generico'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `inventory_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `block_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `width` int(11) DEFAULT 0,
  `height` int(11) DEFAULT 0,
  `depth` int(11) DEFAULT 0,
  `volume_m3` decimal(10,3) DEFAULT 0.000,
  `weight_kg` int(11) DEFAULT 0,
  `is_disassembly_needed` tinyint(1) DEFAULT 0,
  `is_assembly_needed` tinyint(1) DEFAULT 0,
  `is_packing_needed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `inventory_versions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT 'Versione 1',
  `is_selected` tinyint(1) DEFAULT 0,
  `total_volume_m3` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `marketplace_listings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` enum('vehicle','equipment','manpower') NOT NULL,
  `available_from` datetime NOT NULL,
  `available_to` datetime NOT NULL,
  `city` varchar(100) NOT NULL,
  `lat` decimal(10,8) NOT NULL,
  `lng` decimal(11,8) NOT NULL,
  `price_per_hour` decimal(10,2) DEFAULT NULL,
  `price_fixed` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `quotes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_id` bigint(20) UNSIGNED NOT NULL,
  `inventory_version_id` bigint(20) UNSIGNED NOT NULL,
  `quote_number` varchar(50) NOT NULL,
  `date_issued` date NOT NULL,
  `valid_until` date DEFAULT NULL,
  `amount_net` decimal(10,2) NOT NULL DEFAULT 0.00,
  `vat_rate` decimal(5,2) DEFAULT 22.00,
  `amount_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `services_breakdown` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`services_breakdown`)),
  `payment_terms` text DEFAULT NULL,
  `deposit_percentage` decimal(5,2) DEFAULT 0.00,
  `status` enum('draft','sent','accepted','rejected') DEFAULT 'draft',
  `pdf_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('new','contacted','survey_done','quoted','confirmed','completed','cancelled','archived') DEFAULT 'new',
  `source` varchar(50) DEFAULT 'manual',
  `follow_up_date` datetime DEFAULT NULL,
  `internal_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `resources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('vehicle','employee','equipment') NOT NULL,
  `specs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specs`)),
  `cost_per_hour` decimal(10,2) DEFAULT 0.00,
  `cost_per_km` decimal(10,2) DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `stops` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `address_full` varchar(255) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `country` char(2) DEFAULT 'IT',
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `coordinates` point DEFAULT NULL,
  `floor` int(11) DEFAULT 0,
  `elevator_status` enum('yes','no','external_needed','unknown') DEFAULT 'unknown',
  `distance_from_parking` int(11) DEFAULT 0,
  `notes` text DEFAULT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `storage_contracts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date_expected` date DEFAULT NULL,
  `end_date_actual` date DEFAULT NULL,
  `billing_cycle` enum('daily','weekly','monthly') DEFAULT 'monthly',
  `payment_type` enum('prepaid','postpaid') DEFAULT 'prepaid',
  `price_per_period` decimal(10,2) NOT NULL DEFAULT 0.00,
  `insurance_declared_value` decimal(10,2) DEFAULT 0.00,
  `insurance_cost` decimal(10,2) DEFAULT 0.00,
  `status` enum('active','closed','payment_issue') DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `storage_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `storage_contract_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('in','out') NOT NULL,
  `date` datetime NOT NULL,
  `operator_id` bigint(20) UNSIGNED DEFAULT NULL,
  `items_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items_snapshot`)),
  `total_volume_m3` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tenants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `vat_number` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` char(2) DEFAULT 'IT',
  `logo_path` varchar(255) DEFAULT NULL,
  `primary_color` varchar(7) DEFAULT '#0d6efd',
  `stripe_customer_id` varchar(255) DEFAULT NULL,
  `subscription_status` enum('trial','active','past_due','cancelled') DEFAULT 'trial',
  `trial_ends_at` datetime DEFAULT NULL,
  `subscription_ends_at` datetime DEFAULT NULL,
  `tech_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tech_config`)),
  `business_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`business_settings`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tenant_wallet` (
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `balance` int(11) DEFAULT 0,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manager','operative','driver') DEFAULT 'operative',
  `phone` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `total_capacity_m3` decimal(10,2) DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_attach_tenant` (`tenant_id`);

ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_events_tenant` (`tenant_id`),
  ADD KEY `fk_events_resource` (`resource_id`),
  ADD KEY `fk_events_request` (`request_id`);

ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_customer_tenant` (`tenant_id`);

ALTER TABLE `customer_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contacts_customer` (`customer_id`);

ALTER TABLE `email_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_tenant` (`tenant_id`);

ALTER TABLE `inventory_blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_blocks_version` (`version_id`),
  ADD KEY `fk_blocks_origin` (`origin_stop_id`),
  ADD KEY `fk_blocks_dest` (`destination_stop_id`);

ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_items_block` (`block_id`);

ALTER TABLE `inventory_versions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inv_versions_request` (`request_id`);

ALTER TABLE `marketplace_listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_geo` (`lat`,`lng`),
  ADD KEY `fk_market_tenant` (`tenant_id`);

ALTER TABLE `phinxlog`
  ADD PRIMARY KEY (`version`);

ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_quotes_request` (`request_id`);

ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_requests_tenant_status` (`tenant_id`,`status`),
  ADD KEY `fk_requests_customer` (`customer_id`);

ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_resources_tenant` (`tenant_id`);

ALTER TABLE `stops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stops_request` (`request_id`),
  ADD KEY `fk_stops_warehouse` (`warehouse_id`);

ALTER TABLE `storage_contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_storage_tenant` (`tenant_id`),
  ADD KEY `fk_storage_customer` (`customer_id`),
  ADD KEY `fk_storage_warehouse` (`warehouse_id`);

ALTER TABLE `storage_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_movements_contract` (`storage_contract_id`);

ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tenant_wallet`
  ADD PRIMARY KEY (`tenant_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email_per_tenant` (`tenant_id`,`email`);

ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_warehouses_tenant` (`tenant_id`);


ALTER TABLE `attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `calendar_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `customer_contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `email_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `inventory_blocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `inventory_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `inventory_versions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `marketplace_listings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `quotes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `resources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `stops`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `storage_contracts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `storage_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `tenants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `attachments`
  ADD CONSTRAINT `fk_attach_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

ALTER TABLE `calendar_events`
  ADD CONSTRAINT `fk_events_request` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_events_resource` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_events_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

ALTER TABLE `customers`
  ADD CONSTRAINT `fk_customers_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

ALTER TABLE `customer_contacts`
  ADD CONSTRAINT `fk_contacts_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

ALTER TABLE `email_messages`
  ADD CONSTRAINT `fk_email_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

ALTER TABLE `inventory_blocks`
  ADD CONSTRAINT `fk_blocks_dest` FOREIGN KEY (`destination_stop_id`) REFERENCES `stops` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_blocks_origin` FOREIGN KEY (`origin_stop_id`) REFERENCES `stops` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_blocks_version` FOREIGN KEY (`version_id`) REFERENCES `inventory_versions` (`id`) ON DELETE CASCADE;

ALTER TABLE `inventory_items`
  ADD CONSTRAINT `fk_items_block` FOREIGN KEY (`block_id`) REFERENCES `inventory_blocks` (`id`) ON DELETE CASCADE;

ALTER TABLE `inventory_versions`
  ADD CONSTRAINT `fk_inv_versions_request` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE;

ALTER TABLE `marketplace_listings`
  ADD CONSTRAINT `fk_market_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

ALTER TABLE `quotes`
  ADD CONSTRAINT `fk_quotes_request` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE;

ALTER TABLE `requests`
  ADD CONSTRAINT `fk_requests_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_requests_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

ALTER TABLE `resources`
  ADD CONSTRAINT `fk_resources_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

ALTER TABLE `stops`
  ADD CONSTRAINT `fk_stops_request` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_stops_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL;

ALTER TABLE `storage_contracts`
  ADD CONSTRAINT `fk_storage_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_storage_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_storage_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

ALTER TABLE `storage_movements`
  ADD CONSTRAINT `fk_movements_contract` FOREIGN KEY (`storage_contract_id`) REFERENCES `storage_contracts` (`id`) ON DELETE CASCADE;

ALTER TABLE `tenant_wallet`
  ADD CONSTRAINT `fk_wallet_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

ALTER TABLE `warehouses`
  ADD CONSTRAINT `fk_warehouses_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
