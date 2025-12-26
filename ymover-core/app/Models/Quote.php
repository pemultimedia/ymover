<?php

declare(strict_types=1);

namespace App\Models;

class Quote extends BaseModel
{
    public function create(array $data): int
    {
        $sql = "INSERT INTO quotes (tenant_id, request_id, inventory_version_id, total_amount, status, expiration_date, internal_notes) 
                VALUES (:tenant_id, :request_id, :inventory_version_id, :total_amount, :status, :expiration_date, :internal_notes)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tenant_id' => $data['tenant_id'],
            'request_id' => $data['request_id'],
            'inventory_version_id' => $data['inventory_version_id'] ?? null,
            'total_amount' => $data['total_amount'] ?? 0.00,
            'status' => $data['status'] ?? 'draft',
            'expiration_date' => $data['expiration_date'] ?? null,
            'internal_notes' => $data['internal_notes'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM quotes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function getByRequestId(int $requestId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM quotes WHERE request_id = :request_id ORDER BY created_at DESC");
        $stmt->execute(['request_id' => $requestId]);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE quotes SET status = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function markAsPaid(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE quotes SET status = 'accepted' WHERE id = :id");
        // In a real app, we'd have a 'paid' status or a payments table.
        // For now, we'll just ensure it's accepted and maybe add a note.
        return $stmt->execute(['id' => $id]);
    }
}
