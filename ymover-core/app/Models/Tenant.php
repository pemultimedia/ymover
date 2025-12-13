<?php

declare(strict_types=1);

namespace App\Models;

class Tenant extends BaseModel
{
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM tenants WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }
}
