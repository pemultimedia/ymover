<?php

declare(strict_types=1);

namespace App\Models;

class Warehouse extends BaseModel
{
    public function getAll(): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM warehouses 
            WHERE tenant_id = :tenant_id 
            ORDER BY name ASC
        ");
        $stmt->execute(['tenant_id' => $_SESSION['tenant_id']]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM warehouses 
            WHERE id = :id AND tenant_id = :tenant_id
        ");
        $stmt->execute([
            'id' => $id,
            'tenant_id' => $_SESSION['tenant_id']
        ]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO warehouses (tenant_id, name, code, address, city, lat, lng, total_capacity_m3, is_active) 
                VALUES (:tenant_id, :name, :code, :address, :city, :lat, :lng, :total_capacity_m3, :is_active)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tenant_id' => $_SESSION['tenant_id'],
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'address' => $data['address'],
            'city' => $data['city'] ?? null,
            'lat' => $data['lat'] ?? null,
            'lng' => $data['lng'] ?? null,
            'total_capacity_m3' => $data['total_capacity_m3'] ?? 0.00,
            'is_active' => $data['is_active'] ?? 1
        ]);
        
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE warehouses 
                SET name = :name, 
                    code = :code, 
                    address = :address, 
                    city = :city,
                    lat = :lat,
                    lng = :lng,
                    total_capacity_m3 = :total_capacity_m3,
                    is_active = :is_active
                WHERE id = :id AND tenant_id = :tenant_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'tenant_id' => $_SESSION['tenant_id'],
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'address' => $data['address'],
            'city' => $data['city'] ?? null,
            'lat' => $data['lat'] ?? null,
            'lng' => $data['lng'] ?? null,
            'total_capacity_m3' => $data['total_capacity_m3'] ?? 0.00,
            'is_active' => $data['is_active'] ?? 1
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM warehouses WHERE id = :id AND tenant_id = :tenant_id");
        return $stmt->execute([
            'id' => $id,
            'tenant_id' => $_SESSION['tenant_id']
        ]);
    }
}
