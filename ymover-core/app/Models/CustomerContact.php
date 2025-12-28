<?php

namespace App\Models;

use PDO;

class CustomerContact
{
    private PDO $db;

    public function __construct()
    {
        $this->db = \App\Core\Database::getInstance();
    }

    public function getByCustomerId(int $customerId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM customer_contacts WHERE customer_id = :customer_id ORDER BY is_primary DESC, id ASC");
        $stmt->execute(['customer_id' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO customer_contacts (customer_id, type, value, is_primary, label) 
                VALUES (:customer_id, :type, :value, :is_primary, :label)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'customer_id' => $data['customer_id'],
            'type' => $data['type'],
            'value' => $data['value'],
            'is_primary' => $data['is_primary'] ?? 0,
            'label' => $data['label'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE customer_contacts SET type = :type, value = :value, is_primary = :is_primary, label = :label WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'type' => $data['type'],
            'value' => $data['value'],
            'is_primary' => $data['is_primary'] ?? 0,
            'label' => $data['label'] ?? null,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM customer_contacts WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function deleteAllByCustomerId(int $customerId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM customer_contacts WHERE customer_id = :customer_id");
        return $stmt->execute(['customer_id' => $customerId]);
    }
}
