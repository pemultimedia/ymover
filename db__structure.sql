-- ========================================================
-- YMover Database Schema
-- Target: MariaDB 11.x / MySQL 8.0+
-- Charset: utf8mb4 (Supporto completo Multilingua/Emoji)
-- ========================================================

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- 1. SAAS CORE & CONFIGURAZIONE
-- --------------------------------------------------------

-- Tabella Aziende (Tenants)
CREATE TABLE `tenants` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_name` VARCHAR(255) NOT NULL,
  `vat_number` VARCHAR(50) DEFAULT NULL, -- Partita IVA
  `address` VARCHAR(255) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `country` CHAR(2) DEFAULT 'IT',
  
  -- Branding
  `logo_path` VARCHAR(255) DEFAULT NULL,
  `primary_color` VARCHAR(7) DEFAULT '#0d6efd', -- Per personalizzazione PDF/UI
  
  -- Abbonamento & Stripe
  `stripe_customer_id` VARCHAR(255) DEFAULT NULL,
  `subscription_status` ENUM('trial', 'active', 'past_due', 'cancelled') DEFAULT 'trial',
  `trial_ends_at` DATETIME DEFAULT NULL,
  `subscription_ends_at` DATETIME DEFAULT NULL,
  
  -- Configurazioni Tecniche (JSON per flessibilità)
  -- Contiene: { "imap_host": "...", "imap_user": "...", "smtp_host": "...", "google_maps_key": "..." }
  `tech_config` JSON DEFAULT NULL,
  
  -- Impostazioni di Business (JSON)
  -- Contiene: { "default_quote_notes": "...", "currency": "EUR", "unit_system": "metric" }
  `business_settings` JSON DEFAULT NULL,
  
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabella Utenti (Operatori)
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NOT NULL, -- Hash Argon2id
  `role` ENUM('admin', 'manager', 'operative', 'driver') DEFAULT 'operative',
  `phone` VARCHAR(50) DEFAULT NULL,
  `is_active` BOOLEAN DEFAULT TRUE,
  `last_login_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email_per_tenant` (`tenant_id`, `email`),
  CONSTRAINT `fk_users_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. CRM & CLIENTI
-- --------------------------------------------------------

-- Tabella Clienti
CREATE TABLE `customers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `type` ENUM('private', 'company') DEFAULT 'private',
  `name` VARCHAR(255) NOT NULL, -- Nome completo o Ragione Sociale
  `tax_code` VARCHAR(50) DEFAULT NULL, -- Codice Fiscale
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_customer_tenant` (`tenant_id`),
  CONSTRAINT `fk_customers_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contatti Multipli (Email/Telefono)
CREATE TABLE `customer_contacts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` BIGINT UNSIGNED NOT NULL,
  `type` ENUM('email', 'phone', 'mobile', 'whatsapp') NOT NULL,
  `value` VARCHAR(255) NOT NULL,
  `is_primary` BOOLEAN DEFAULT FALSE,
  `label` VARCHAR(50) DEFAULT NULL, -- Es. "Ufficio", "Casa", "Segretaria"
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_contacts_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. RICHIESTE & LOGISTICA (Il Core)
-- --------------------------------------------------------

-- Tabella Richieste (Pratiche)
CREATE TABLE `requests` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `customer_id` BIGINT UNSIGNED NOT NULL,
  `status` ENUM('new', 'contacted', 'survey_done', 'quoted', 'confirmed', 'completed', 'cancelled', 'archived') DEFAULT 'new',
  `source` VARCHAR(50) DEFAULT 'manual', -- 'web', 'manual', 'marketplace'
  `follow_up_date` DATETIME DEFAULT NULL, -- "Da ricontattare il"
  `internal_notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_requests_tenant_status` (`tenant_id`, `status`),
  CONSTRAINT `fk_requests_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_requests_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Versioni dell'Inventario (Tabbed Interface)
CREATE TABLE `inventory_versions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `request_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL DEFAULT 'Versione 1', -- Es. "Solo Mobili", "Tutto Incluso"
  `is_selected` BOOLEAN DEFAULT FALSE, -- La versione scelta per il contratto
  `total_volume_m3` DECIMAL(10, 2) DEFAULT 0.00,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_inv_versions_request` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Indirizzi / Stop Logistici (Georeferenziati)
CREATE TABLE `stops` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `request_id` BIGINT UNSIGNED NOT NULL,
  `address_full` VARCHAR(255) NOT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `zip_code` VARCHAR(20) DEFAULT NULL,
  `country` CHAR(2) DEFAULT 'IT',
  
  -- Geodata
  `lat` DECIMAL(10, 8) DEFAULT NULL,
  `lng` DECIMAL(11, 8) DEFAULT NULL,
  
  -- Dati Operativi
  `floor` INT DEFAULT 0,
  `elevator_status` ENUM('yes', 'no', 'external_needed', 'unknown') DEFAULT 'unknown',
  `distance_from_parking` INT DEFAULT 0, -- Metri
  `notes` TEXT DEFAULT NULL, -- "Citofonare Rossi"
  
  -- Referente in loco (per Foglio di Viaggio)
  `contact_name` VARCHAR(100) DEFAULT NULL,
  `contact_phone` VARCHAR(50) DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_stops_request` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blocchi di Inventario (Collegano Origine -> Destinazione)
-- Risolve il problema: "Mobili da Milano a Roma" vs "Lavatrice da Genova a Roma"
CREATE TABLE `inventory_blocks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `version_id` BIGINT UNSIGNED NOT NULL,
  `origin_stop_id` BIGINT UNSIGNED NULL, -- NULL = Deposito o Indefinito
  `destination_stop_id` BIGINT UNSIGNED NULL, -- NULL = Deposito o Indefinito
  `name` VARCHAR(100) DEFAULT 'Generico', -- Es. "Salotto", "Cucina"
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_blocks_version` FOREIGN KEY (`version_id`) REFERENCES `inventory_versions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_blocks_origin` FOREIGN KEY (`origin_stop_id`) REFERENCES `stops` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_blocks_dest` FOREIGN KEY (`destination_stop_id`) REFERENCES `stops` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Singoli Oggetti (Mobili)
CREATE TABLE `inventory_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `block_id` BIGINT UNSIGNED NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `quantity` INT DEFAULT 1,
  `width` INT DEFAULT 0, -- cm
  `height` INT DEFAULT 0, -- cm
  `depth` INT DEFAULT 0, -- cm
  `volume_m3` DECIMAL(10, 3) DEFAULT 0.000, -- Calcolato o forzato
  `weight_kg` INT DEFAULT 0,
  `is_disassembly_needed` BOOLEAN DEFAULT FALSE,
  `is_assembly_needed` BOOLEAN DEFAULT FALSE,
  `is_packing_needed` BOOLEAN DEFAULT FALSE,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_items_block` FOREIGN KEY (`block_id`) REFERENCES `inventory_blocks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. PREVENTIVI & CONTRATTI
-- --------------------------------------------------------

CREATE TABLE `quotes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `request_id` BIGINT UNSIGNED NOT NULL,
  `inventory_version_id` BIGINT UNSIGNED NOT NULL,
  `quote_number` VARCHAR(50) NOT NULL, -- Es. PREV-2025-001
  `date_issued` DATE NOT NULL,
  `valid_until` DATE DEFAULT NULL,
  
  -- Importi
  `amount_net` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `vat_rate` DECIMAL(5, 2) DEFAULT 22.00,
  `amount_total` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  
  -- Dettagli Servizi (JSON per split preventivo)
  -- Es: [{"service": "Trasporto", "price": 1000}, {"service": "Imballaggio", "price": 200}]
  `services_breakdown` JSON DEFAULT NULL,
  
  -- Condizioni Pagamento (Snapshot al momento dell'emissione)
  `payment_terms` TEXT DEFAULT NULL, 
  `deposit_percentage` DECIMAL(5, 2) DEFAULT 0.00,
  
  `status` ENUM('draft', 'sent', 'accepted', 'rejected') DEFAULT 'draft',
  `pdf_path` VARCHAR(255) DEFAULT NULL,
  
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_quotes_request` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. OPERATIVITÀ & RISORSE
-- --------------------------------------------------------

CREATE TABLE `resources` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL, -- Es. "Furgone Iveco", "Mario Rossi"
  `type` ENUM('vehicle', 'employee', 'equipment') NOT NULL,
  
  -- Specifiche (JSON)
  -- Veicoli: { "plate": "AB123CD", "volume_capacity": 20, "license_required": "B" }
  -- Operai: { "skills": ["driver_c", "packer", "carpenter"] }
  `specs` JSON DEFAULT NULL,
  
  `cost_per_hour` DECIMAL(10, 2) DEFAULT 0.00,
  `cost_per_km` DECIMAL(10, 2) DEFAULT 0.00,
  `is_active` BOOLEAN DEFAULT TRUE,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_resources_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `calendar_events` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `resource_id` BIGINT UNSIGNED DEFAULT NULL, -- NULL se è un evento generico
  `request_id` BIGINT UNSIGNED DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `start_datetime` DATETIME NOT NULL,
  `end_datetime` DATETIME NOT NULL,
  `type` ENUM('job', 'inspection', 'unavailable', 'maintenance') DEFAULT 'job',
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_events_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_events_resource` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_events_request` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. MARKETPLACE & B2B
-- --------------------------------------------------------

CREATE TABLE `marketplace_listings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL, -- Chi offre la risorsa
  `title` VARCHAR(255) NOT NULL, -- Es. "Elevatore 25m disponibile a Mantova"
  `type` ENUM('vehicle', 'equipment', 'manpower') NOT NULL,
  
  -- Disponibilità
  `available_from` DATETIME NOT NULL,
  `available_to` DATETIME NOT NULL,
  
  -- Georeferenziazione Annuncio
  `city` VARCHAR(100) NOT NULL,
  `lat` DECIMAL(10, 8) NOT NULL,
  `lng` DECIMAL(11, 8) NOT NULL,
  
  -- Costi
  `price_per_hour` DECIMAL(10, 2) DEFAULT NULL,
  `price_fixed` DECIMAL(10, 2) DEFAULT NULL,
  
  `description` TEXT DEFAULT NULL,
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_geo` (`lat`, `lng`), -- Indice per ricerca spaziale veloce
  CONSTRAINT `fk_market_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Wallet per acquisto Lead (Token)
CREATE TABLE `tenant_wallet` (
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `balance` INT DEFAULT 0, -- Numero Token
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tenant_id`),
  CONSTRAINT `fk_wallet_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 7. COMUNICAZIONI & EMAIL
-- --------------------------------------------------------

CREATE TABLE `email_messages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `customer_id` BIGINT UNSIGNED DEFAULT NULL, -- Collegato se trovato
  `request_id` BIGINT UNSIGNED DEFAULT NULL, -- Collegato se dedotto
  `direction` ENUM('inbound', 'outbound') NOT NULL,
  `sender` VARCHAR(255) NOT NULL,
  `recipient` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(255) DEFAULT NULL,
  `body_text` LONGTEXT DEFAULT NULL,
  `body_html` LONGTEXT DEFAULT NULL,
  `message_uid` VARCHAR(255) DEFAULT NULL, -- UID IMAP per evitare duplicati
  `has_attachments` BOOLEAN DEFAULT FALSE,
  `received_at` DATETIME NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email_tenant` (`tenant_id`),
  CONSTRAINT `fk_email_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `attachments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `entity_type` VARCHAR(50) NOT NULL, -- 'email', 'request', 'quote'
  `entity_id` BIGINT UNSIGNED NOT NULL,
  `filename` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL, -- Path relativo nello storage
  `file_size` INT UNSIGNED NOT NULL, -- Bytes
  `mime_type` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_attach_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;