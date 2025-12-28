<?php

declare(strict_types=1);

namespace App\Models;

class InventoryItem extends BaseModel
{
    public function create(array $data): int
    {
        $sql = "INSERT INTO inventory_items (block_id, name, width, height, depth, weight, quantity, is_disassembly, is_packing, notes) 
                VALUES (:block_id, :name, :width, :height, :depth, :weight, :quantity, :is_disassembly, :is_packing, :notes)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'block_id' => $data['block_id'],
            'name' => $data['name'],
            'width' => $data['width'] ?? 0,
            'height' => $data['height'] ?? 0,
            'depth' => $data['depth'] ?? 0,
            'weight' => $data['weight'] ?? 0,
            'quantity' => $data['quantity'] ?? 1,
            'is_disassembly' => $data['is_disassembly'] ?? 0,
            'is_packing' => $data['is_packing'] ?? 0,
            'notes' => $data['notes'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function getByBlockId(int $blockId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM inventory_items WHERE block_id = :block_id");
        $stmt->execute(['block_id' => $blockId]);
        return $stmt->fetchAll();
    }
}
