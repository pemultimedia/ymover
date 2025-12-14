<?php

declare(strict_types=1);

namespace App\Models;

class InventoryVersion extends BaseModel
{
    public function create(array $data): int
    {
        $sql = "INSERT INTO inventory_versions (request_id, name, is_selected) VALUES (:request_id, :name, :is_selected)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'request_id' => $data['request_id'],
            'name' => $data['name'] ?? 'Versione 1',
            'is_selected' => $data['is_selected'] ?? false,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function getByRequestId(int $requestId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM inventory_versions WHERE request_id = :request_id ORDER BY created_at ASC");
        $stmt->execute(['request_id' => $requestId]);
        return $stmt->fetchAll();
    }
}
