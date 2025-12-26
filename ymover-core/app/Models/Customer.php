<?php

declare(strict_types=1);

namespace App\Models;

class Customer extends BaseModel
{
    public function create(array $data): int
    {
        $sql = "INSERT INTO customers (tenant_id, type, name, tax_code, notes) VALUES (:tenant_id, :type, :name, :tax_code, :notes)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tenant_id' => $data['tenant_id'],
            'type' => $data['type'] ?? 'private',
            'name' => $data['name'],
            'tax_code' => $data['tax_code'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function getAllByTenant(int $tenantId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE tenant_id = :tenant_id ORDER BY name ASC");
        $stmt->execute(['tenant_id' => $tenantId]);
        return $stmt->fetchAll();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE customers SET type = :type, name = :name, tax_code = :tax_code, notes = :notes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'type' => $data['type'],
            'name' => $data['name'],
            'tax_code' => $data['tax_code'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function search(string $query, int $tenantId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE tenant_id = :tenant_id AND name LIKE :query LIMIT 10");
        $stmt->execute(['tenant_id' => $tenantId, 'query' => "%$query%"]);
        return $stmt->fetchAll();
    }
}
