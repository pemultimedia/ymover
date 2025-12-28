<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateCustomerContactsAndAddNotes extends AbstractMigration
{
    public function up(): void
    {
        // 1. Update customer_contacts type enum
        $this->execute("ALTER TABLE `customer_contacts` MODIFY COLUMN `type` ENUM('email','phone','mobile','whatsapp','residence_address','billing_address','tax_code','vat_number','sdi_code') NOT NULL");

        // 2. Create request_notes table
        $table = $this->table('request_notes');
        $table->addColumn('request_id', 'integer', ['signed' => false])
              ->addColumn('user_id', 'integer', ['signed' => false, 'null' => true]) // Nullable for system notes
              ->addColumn('author_name', 'string', ['limit' => 100]) // Snapshot of author name or "System"
              ->addColumn('text', 'text')
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addForeignKey('request_id', 'requests', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->addForeignKey('user_id', 'users', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
              ->create();
    }
}
