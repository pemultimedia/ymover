<?php

declare(strict_types=1);

namespace App\Models;

class MarketplaceAd extends BaseModel
{
    public function getAll(): array
    {
        // user_id is not in the table, so we join tenants to get some info if needed, 
        // or just return listings. For now, we'll just return listings.
        // If we need user info, we might need to rely on tenant info.
        $stmt = $this->db->query("
            SELECT m.*, t.company_name as tenant_name 
            FROM marketplace_listings m 
            LEFT JOIN tenants t ON m.tenant_id = t.id 
            WHERE m.is_active = 1
            ORDER BY m.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT m.*, t.company_name as tenant_name 
            FROM marketplace_listings m 
            LEFT JOIN tenants t ON m.tenant_id = t.id 
            WHERE m.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO marketplace_listings (tenant_id, title, description, type, price_fixed, city, lat, lng, available_from, available_to, is_active) 
                VALUES (:tenant_id, :title, :description, :type, :price_fixed, :city, :lat, :lng, :available_from, :available_to, 1)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tenant_id' => $data['tenant_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'type' => $data['type'],
            'price_fixed' => $data['price_fixed'],
            'city' => $data['city'],
            'lat' => $data['lat'] ?? 0.0,
            'lng' => $data['lng'] ?? 0.0,
            'available_from' => $data['available_from'],
            'available_to' => $data['available_to']
        ]);
        
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE marketplace_listings 
                SET title = :title, 
                    description = :description, 
                    type = :type, 
                    price_fixed = :price_fixed, 
                    city = :city,
                    available_from = :available_from,
                    available_to = :available_to
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'description' => $data['description'],
            'type' => $data['type'],
            'price_fixed' => $data['price_fixed'],
            'city' => $data['city'],
            'available_from' => $data['available_from'],
            'available_to' => $data['available_to']
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM marketplace_listings WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
