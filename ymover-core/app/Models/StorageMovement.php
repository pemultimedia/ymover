<?php

declare(strict_types=1);

namespace App\Models;

class StorageMovement extends BaseModel
{
    public function getByContractId(int $contractId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM storage_movements 
            WHERE storage_contract_id = :contract_id 
            ORDER BY date DESC
        ");
        $stmt->execute(['contract_id' => $contractId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO storage_movements (
                    storage_contract_id, type, date, operator_id, 
                    items_snapshot, total_volume_m3, notes
                ) VALUES (
                    :storage_contract_id, :type, :date, :operator_id, 
                    :items_snapshot, :total_volume_m3, :notes
                )";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'storage_contract_id' => $data['storage_contract_id'],
            'type' => $data['type'],
            'date' => $data['date'],
            'operator_id' => $data['operator_id'] ?? null,
            'items_snapshot' => json_encode($data['items_snapshot']),
            'total_volume_m3' => $data['total_volume_m3'],
            'notes' => $data['notes'] ?? null
        ]);
        
        return (int)$this->db->lastInsertId();
    }
}
