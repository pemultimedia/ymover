<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Request;
use App\Models\Customer;
use App\Models\InventoryVersion;
use App\Models\InventoryBlock;

class RequestController
{
    private Request $requestModel;
    private Customer $customerModel;

    public function __construct()
    {
        $this->requestModel = new Request();
        $this->customerModel = new Customer();
    }

    public function index(): void
    {
        $tenantId = $_SESSION['tenant_id'];
        $requests = $this->requestModel->getAllByTenant($tenantId);
        
        // Enrich requests with customer name (mock join logic for now, ideally in Model)
        foreach ($requests as &$req) {
            $customer = $this->customerModel->findById($req['customer_id']);
            $req['customer_name'] = $customer['name'] ?? 'Unknown';
        }
        
        View::render('requests/index', ['requests' => $requests]);
    }

    public function create(): void
    {
        View::render('requests/create');
    }

    public function store(): void
    {
        $tenantId = $_SESSION['tenant_id'];
        $data = $_POST;
        
        // 1. Create Customer if new (simplified logic)
        // In a real app we would check if customer_id is passed or if we need to create one
        // For this wizard, let's assume we create a new customer for every request if not selected
        
        $customerData = [
            'tenant_id' => $tenantId,
            'name' => $data['customer_name'],
            'type' => 'private', // Default
        ];
        
        $customerId = $this->customerModel->create($customerData);
        
        // 2. Create Request
        $requestData = [
            'tenant_id' => $tenantId,
            'customer_id' => $customerId,
            'status' => 'new',
            'source' => $data['source'] ?? 'manual',
            'internal_notes' => $data['internal_notes'] ?? null,
        ];
        
        $requestId = $this->requestModel->create($requestData);
        
        // 3. Create Initial Inventory Version & Block
        $versionModel = new InventoryVersion();
        $versionId = $versionModel->create([
            'request_id' => $requestId,
            'name' => 'Versione 1',
            'is_selected' => true
        ]);
        
        $blockModel = new InventoryBlock();
        $blockModel->create([
            'version_id' => $versionId,
            'name' => 'Generico'
        ]);
        
        header("Location: /requests/show?id=" . $requestId);
        exit;
    }

    public function show(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /requests");
            exit;
        }

        // Fetch Request
        // Ideally we should check tenant ownership here
        // $req = $this->requestModel->findById((int)$id); 
        // For now let's assume we have a findById in Request Model (we need to add it or use a generic one)
        // Let's add findById to Request Model quickly via direct DB call here or update Model
        
        // Quick fix: direct DB call for now to keep moving, but ideally update Model
        $db = \App\Core\Database::getInstance()->pdo;
        $stmt = $db->prepare("SELECT * FROM requests WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $request = $stmt->fetch();
        
        if (!$request || $request['tenant_id'] != $_SESSION['tenant_id']) {
             header("Location: /requests");
             exit;
        }
        
        $customer = $this->customerModel->findById($request['customer_id']);
        
        View::render('requests/show', [
            'request' => $request,
            'customer' => $customer
        ]);
    }
}
