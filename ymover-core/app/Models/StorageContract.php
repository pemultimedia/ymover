<?php

declare(strict_types=1);

namespace App\Models;

class StorageContract extends BaseModel
{
    public function getAll(): array
    {
        $stmt = $this->db->prepare("
            SELECT sc.*, c.name as customer_name, w.name as warehouse_name 
            FROM storage_contracts sc
            JOIN customers c ON sc.customer_id = c.id
            JOIN warehouses w ON sc.warehouse_id = w.id
            WHERE sc.tenant_id = :tenant_id 
            ORDER BY sc.created_at DESC
        ");
        $stmt->execute(['tenant_id' => $_SESSION['tenant_id']]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT sc.*, c.name as customer_name, w.name as warehouse_name 
            FROM storage_contracts sc
            JOIN customers c ON sc.customer_id = c.id
            JOIN warehouses w ON sc.warehouse_id = w.id
            WHERE sc.id = :id AND sc.tenant_id = :tenant_id
        ");
        $stmt->execute([
            'id' => $id,
            'tenant_id' => $_SESSION['tenant_id']
        ]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO storage_contracts (
                    tenant_id, customer_id, warehouse_id, request_id, 
                    start_date, end_date_expected, billing_cycle, payment_type, 
                    price_per_period, insurance_declared_value, insurance_cost, 
                    status, notes
                ) VALUES (
                    :tenant_id, :customer_id, :warehouse_id, :request_id, 
                    :start_date, :end_date_expected, :billing_cycle, :payment_type, 
                    :price_per_period, :insurance_declared_value, :insurance_cost, 
                    :status, :notes
                )";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tenant_id' => $_SESSION['tenant_id'],
            'customer_id' => $data['customer_id'],
            'warehouse_id' => $data['warehouse_id'],
            'request_id' => $data['request_id'] ?? null,
            'start_date' => $data['start_date'],
            'end_date_expected' => $data['end_date_expected'] ?? null,
            'billing_cycle' => $data['billing_cycle'] ?? 'monthly',
            'payment_type' => $data['payment_type'] ?? 'prepaid',
            'price_per_period' => $data['price_per_period'] ?? 0.00,
            'insurance_declared_value' => $data['insurance_declared_value'] ?? 0.00,
            'insurance_cost' => $data['insurance_cost'] ?? 0.00,
            'status' => $data['status'] ?? 'active',
            'notes' => $data['notes'] ?? null
        ]);
        
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE storage_contracts 
                SET end_date_expected = :end_date_expected,
                    end_date_actual = :end_date_actual,
                    billing_cycle = :billing_cycle,
                    payment_type = :payment_type,
                    price_per_period = :price_per_period,
                    insurance_declared_value = :insurance_declared_value,
                    insurance_cost = :insurance_cost,
                    status = :status,
                    notes = :notes
                WHERE id = :id AND tenant_id = :tenant_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'tenant_id' => $_SESSION['tenant_id'],
            'end_date_expected' => $data['end_date_expected'] ?? null,
            'end_date_actual' => $data['end_date_actual'] ?? null,
            'billing_cycle' => $data['billing_cycle'],
            'payment_type' => $data['payment_type'],
            'price_per_period' => $data['price_per_period'],
            'insurance_declared_value' => $data['insurance_declared_value'],
            'insurance_cost' => $data['insurance_cost'],
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null
        ]);
    }
}
