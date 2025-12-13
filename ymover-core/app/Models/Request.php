<?php

declare(strict_types=1);

namespace App\Models;

class Request extends BaseModel
{
    public function getAllByTenant(int $tenantId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM requests WHERE tenant_id = :tenant_id ORDER BY created_at DESC");
        $stmt->execute(['tenant_id' => $tenantId]);
        return $stmt->fetchAll();
    }
    
    public function create(array $data): int
    {
        $sql = "INSERT INTO requests (tenant_id, customer_id, status, source, internal_notes) VALUES (:tenant_id, :customer_id, :status, :source, :internal_notes)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tenant_id' => $data['tenant_id'],
            'customer_id' => $data['customer_id'],
            'status' => $data['status'] ?? 'new',
            'source' => $data['source'] ?? 'manual',
            'internal_notes' => $data['internal_notes'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }
}
