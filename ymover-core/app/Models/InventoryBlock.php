<?php

declare(strict_types=1);

namespace App\Models;

class InventoryBlock extends BaseModel
{
    public function create(array $data): int
    {
        $sql = "INSERT INTO inventory_blocks (version_id, name) VALUES (:version_id, :name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'version_id' => $data['version_id'],
            'name' => $data['name'] ?? 'Generico',
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function getByVersionId(int $versionId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM inventory_blocks WHERE version_id = :version_id ORDER BY id ASC");
        $stmt->execute(['version_id' => $versionId]);
        return $stmt->fetchAll();
    }
}
