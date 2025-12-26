<?php

declare(strict_types=1);

namespace App\Models;

class Quote extends BaseModel
{
    public function create(array $data): int
    {
        $sql = "INSERT INTO quotes (tenant_id, request_id, inventory_version_id, quote_number, date_issued, valid_until, amount_total, status) 
                VALUES (:tenant_id, :request_id, :inventory_version_id, :quote_number, :date_issued, :valid_until, :amount_total, :status)";
        $stmt = $this->db->prepare($sql);
        
        $quoteNumber = 'PREV-' . date('Y') . '-' . str_pad((string)rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        $stmt->execute([
            'tenant_id' => $data['tenant_id'],
            'request_id' => $data['request_id'],
            'inventory_version_id' => $data['inventory_version_id'] ?? null,
            'quote_number' => $quoteNumber,
            'date_issued' => date('Y-m-d'),
            'valid_until' => $data['valid_until'] ?? $data['expiration_date'] ?? null,
            'amount_total' => $data['amount_total'] ?? $data['total_amount'] ?? 0.00,
            'status' => $data['status'] ?? 'draft',
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
