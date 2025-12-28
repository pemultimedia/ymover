<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\StorageContract;
use App\Models\StorageMovement;
use App\Models\Warehouse;
use App\Models\Customer;

class StorageController
{
    private StorageContract $contractModel;
    private StorageMovement $movementModel;
    private Warehouse $warehouseModel;
    private Customer $customerModel;

    public function __construct()
    {
        $this->contractModel = new StorageContract();
        $this->movementModel = new StorageMovement();
        $this->warehouseModel = new Warehouse();
        $this->customerModel = new Customer();
    }

    public function index(): void
    {
        // List contracts
        $contracts = $this->contractModel->getAll();
        View::render('storage/index', ['contracts' => $contracts]);
    }

    public function createContract(): void
    {
        $warehouses = $this->warehouseModel->getAll();
        $customers = $this->customerModel->getAll(); // Assuming getAll exists in Customer model
        View::render('storage/create_contract', [
            'warehouses' => $warehouses,
            'customers' => $customers
        ]);
    }

    public function storeContract(): void
    {
        $data = [
            'customer_id' => (int)$_POST['customer_id'],
            'warehouse_id' => (int)$_POST['warehouse_id'],
            'start_date' => $_POST['start_date'],
            'billing_cycle' => $_POST['billing_cycle'],
            'payment_type' => $_POST['payment_type'],
            'price_per_period' => (float)$_POST['price_per_period'],
            'insurance_declared_value' => (float)($_POST['insurance_declared_value'] ?? 0),
            'insurance_cost' => (float)($_POST['insurance_cost'] ?? 0),
            'notes' => $_POST['notes'] ?? ''
        ];

        $this->contractModel->create($data);
        header("Location: /storage");
        exit;
    }

    public function showContract(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $contract = $this->contractModel->getById($id);
        
        if (!$contract) {
            header("Location: /storage");
            exit;
        }

        $movements = $this->movementModel->getByContractId($id);

        View::render('storage/show_contract', [
            'contract' => $contract,
            'movements' => $movements
        ]);
    }

    public function registerMovement(): void
    {
        $contractId = (int)$_POST['contract_id'];
        $type = $_POST['type']; // 'in' or 'out'
        $items = json_decode($_POST['items_json'], true); // Expecting JSON string from frontend
        
        // Calculate total volume
        $totalVolume = 0;
        foreach ($items as $item) {
            $totalVolume += ($item['vol'] * $item['qty']);
        }

        $movementId = $this->movementModel->create([
            'storage_contract_id' => $contractId,
            'type' => $type,
            'date' => date('Y-m-d H:i:s'),
            'operator_id' => $_SESSION['user_id'],
            'items_snapshot' => $items,
            'total_volume_m3' => $totalVolume,
            'notes' => $_POST['notes'] ?? ''
        ]);

        header("Location: /storage/show?id=" . $contractId);
        exit;
    }

    public function generateWaybill(): void
    {
        $movementId = (int)$_GET['movement_id'];
        // In a real app, fetch movement details, contract details, etc.
        // and generate PDF using TCPDF.
        
        // Example logic (placeholder):
        // $movement = $this->movementModel->getById($movementId);
        // $pdf = new \TCPDF();
        // ... build PDF ...
        // $pdf->Output('waybill.pdf', 'I');
        
        echo "PDF Generation Placeholder for Movement ID: " . $movementId;
        exit;
    }
}
