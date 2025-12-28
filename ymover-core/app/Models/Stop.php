<?php

declare(strict_types=1);

namespace App\Models;

class Stop extends BaseModel
{
    public function create(array $data): int
    {
        $sql = "INSERT INTO stops (request_id, warehouse_id, address_full, city, zip_code, country, lat, lng, floor, elevator_status, distance_from_parking, notes) 
                VALUES (:request_id, :warehouse_id, :address_full, :city, :zip_code, :country, :lat, :lng, :floor, :elevator_status, :distance_from_parking, :notes)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'request_id' => $data['request_id'],
            'warehouse_id' => $data['warehouse_id'] ?? null,
            'address_full' => $data['address_full'],
            'city' => $data['city'] ?? null,
            'zip_code' => $data['zip_code'] ?? null,
            'country' => $data['country'] ?? 'IT',
            'lat' => $data['lat'] ?? null,
            'lng' => $data['lng'] ?? null,
            'floor' => $data['floor'] ?? 0,
            'elevator_status' => $data['elevator_status'] ?? 'unknown',
            'distance_from_parking' => $data['distance_from_parking'] ?? 0,
            'notes' => $data['notes'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function getByRequestId(int $requestId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM stops WHERE request_id = :request_id");
        $stmt->execute(['request_id' => $requestId]);
        return $stmt->fetchAll();
    }
}
