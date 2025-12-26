<?php

declare(strict_types=1);

namespace App\Models;

class CalendarEvent extends BaseModel
{
    public function create(array $data): int
    {
        $sql = "INSERT INTO calendar_events (tenant_id, resource_id, request_id, title, start_datetime, end_datetime, type) 
                VALUES (:tenant_id, :resource_id, :request_id, :title, :start_datetime, :end_datetime, :type)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tenant_id' => $data['tenant_id'],
            'resource_id' => $data['resource_id'] ?? null,
            'request_id' => $data['request_id'] ?? null,
            'title' => $data['title'],
            'start_datetime' => $data['start_datetime'],
            'end_datetime' => $data['end_datetime'],
            'type' => $data['type'] ?? 'job',
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function getByTenant(int $tenantId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM calendar_events WHERE tenant_id = :tenant_id ORDER BY start_datetime ASC");
        $stmt->execute(['tenant_id' => $tenantId]);
        return $stmt->fetchAll();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM calendar_events WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
