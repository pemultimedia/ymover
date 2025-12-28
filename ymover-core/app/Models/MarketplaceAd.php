<?php

declare(strict_types=1);

namespace App\Models;

class MarketplaceAd extends BaseModel
{
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT m.*, u.name as user_name 
            FROM marketplace_ads m 
            LEFT JOIN users u ON m.user_id = u.id 
            ORDER BY m.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT m.*, u.name as user_name 
            FROM marketplace_ads m 
            LEFT JOIN users u ON m.user_id = u.id 
            WHERE m.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO marketplace_ads (tenant_id, user_id, title, description, type, price, location, image_url) 
                VALUES (:tenant_id, :user_id, :title, :description, :type, :price, :location, :image_url)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tenant_id' => $data['tenant_id'],
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'type' => $data['type'],
            'price' => $data['price'],
            'location' => $data['location'],
            'image_url' => $data['image_url'] ?? null
        ]);
        
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE marketplace_ads 
                SET title = :title, 
                    description = :description, 
                    type = :type, 
                    price = :price, 
                    location = :location, 
                    image_url = :image_url 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'description' => $data['description'],
            'type' => $data['type'],
            'price' => $data['price'],
            'location' => $data['location'],
            'image_url' => $data['image_url'] ?? null
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM marketplace_ads WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
