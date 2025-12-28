<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Warehouse;

class WarehouseController
{
    private Warehouse $warehouseModel;

    public function __construct()
    {
        $this->warehouseModel = new Warehouse();
    }

    public function index(): void
    {
        $warehouses = $this->warehouseModel->getAll();
        View::render('warehouses/index', ['warehouses' => $warehouses]);
    }

    public function create(): void
    {
        View::render('warehouses/create');
    }

    public function store(): void
    {
        // In a real app, use GeoService to get Lat/Lng from address
        // For now, we'll just take what's posted or default to 0
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'code' => $_POST['code'] ?? '',
            'address' => $_POST['address'] ?? '',
            'city' => $_POST['city'] ?? '',
            'lat' => (float)($_POST['lat'] ?? 0.0),
            'lng' => (float)($_POST['lng'] ?? 0.0),
            'total_capacity_m3' => (float)($_POST['total_capacity_m3'] ?? 0.0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        $this->warehouseModel->create($data);
        header("Location: /warehouses");
        exit;
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $warehouse = $this->warehouseModel->getById($id);

        if (!$warehouse) {
            header("Location: /warehouses");
            exit;
        }

        View::render('warehouses/edit', ['warehouse' => $warehouse]);
    }

    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $warehouse = $this->warehouseModel->getById($id);

        if (!$warehouse) {
            header("Location: /warehouses");
            exit;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'code' => $_POST['code'] ?? '',
            'address' => $_POST['address'] ?? '',
            'city' => $_POST['city'] ?? '',
            'lat' => (float)($_POST['lat'] ?? 0.0),
            'lng' => (float)($_POST['lng'] ?? 0.0),
            'total_capacity_m3' => (float)($_POST['total_capacity_m3'] ?? 0.0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        $this->warehouseModel->update($id, $data);
        header("Location: /warehouses");
        exit;
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $this->warehouseModel->delete($id);
        header("Location: /warehouses");
        exit;
    }
}
