<?php

declare(strict_types=1);

namespace App\Models;

class Resource extends BaseModel
{
    public function create(array $data): int
    {
        $sql = "INSERT INTO resources (tenant_id, name, type, specs, cost_per_hour, cost_per_km, is_active) 
                VALUES (:tenant_id, :name, :type, :specs, :cost_per_hour, :cost_per_km, :is_active)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tenant_id' => $data['tenant_id'],
            'name' => $data['name'],
            'type' => $data['type'],
            'specs' => $data['specs'] ?? null,
            'cost_per_hour' => $data['cost_per_hour'] ?? 0.00,
            'cost_per_km' => $data['cost_per_km'] ?? 0.00,
            'is_active' => $data['is_active'] ?? true,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function getAllByTenant(int $tenantId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM resources WHERE tenant_id = :tenant_id ORDER BY name ASC");
        $stmt->execute(['tenant_id' => $tenantId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM resources WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE resources SET 
                name = :name, 
                type = :type, 
                specs = :specs, 
                cost_per_hour = :cost_per_hour, 
                cost_per_km = :cost_per_km, 
                is_active = :is_active 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'type' => $data['type'],
            'specs' => $data['specs'] ?? null,
            'cost_per_hour' => $data['cost_per_hour'] ?? 0.00,
            'cost_per_km' => $data['cost_per_km'] ?? 0.00,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM resources WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
