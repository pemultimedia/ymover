<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Request;

class RequestController
{
    public function index(): void
    {
        // Mock Tenant ID for now
        $tenantId = 1; 
        
        $requestModel = new Request();
        // In a real scenario we would catch exceptions if DB is not ready
        try {
            $requests = $requestModel->getAllByTenant($tenantId);
        } catch (\Exception $e) {
            $requests = [];
        }
        
        View::render('requests/index', ['requests' => $requests]);
    }

    public function create(): void
    {
        View::render('requests/create');
    }

    public function store(): void
    {
        $data = $_POST;
        $data['tenant_id'] = 1; // Mock
        // We need a customer. For now let's assume one exists or we fail.
        $data['customer_id'] = 1; 
        
        $requestModel = new Request();
        $requestModel->create($data);
        
        header("Location: /requests");
        exit;
    }
}
