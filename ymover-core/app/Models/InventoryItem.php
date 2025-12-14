<?php

declare(strict_types=1);

namespace App\Models;

class InventoryItem extends BaseModel
{
    public function create(array $data): int
    {
        $sql = "INSERT INTO inventory_items (block_id, description, quantity, width, height, depth, volume_m3, weight_kg) 
                VALUES (:block_id, :description, :quantity, :width, :height, :depth, :volume_m3, :weight_kg)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'block_id' => $data['block_id'],
            'description' => $data['description'],
            'quantity' => $data['quantity'] ?? 1,
            'width' => $data['width'] ?? 0,
            'height' => $data['height'] ?? 0,
            'depth' => $data['depth'] ?? 0,
            'volume_m3' => $data['volume_m3'] ?? 0.000,
            'weight_kg' => $data['weight_kg'] ?? 0,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM inventory_items WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getByBlockId(int $blockId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM inventory_items WHERE block_id = :block_id ORDER BY id ASC");
        $stmt->execute(['block_id' => $blockId]);
        return $stmt->fetchAll();
    }
}
