<?php

declare(strict_types=1);

namespace App\Models;

class User extends BaseModel
{
    public function findByEmail(string $email, int $tenantId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email AND tenant_id = :tenant_id");
        $stmt->execute(['email' => $email, 'tenant_id' => $tenantId]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO users (tenant_id, name, email, password, role) VALUES (:tenant_id, :name, :email, :password, :role)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tenant_id' => $data['tenant_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_ARGON2ID),
            'role' => $data['role'] ?? 'operative'
        ]);
        return (int)$this->db->lastInsertId();
    }
}
