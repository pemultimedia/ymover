<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Customer;
use App\Models\Request;

class CustomerController
{
    private Customer $customerModel;
    private Request $requestModel;

    public function __construct()
    {
        $this->customerModel = new Customer();
        $this->requestModel = new Request();
    }

    public function index(): void
    {
        $tenantId = $_SESSION['tenant_id'];
        $customers = $this->customerModel->getAllByTenant($tenantId);
        
        View::render('customers/index', ['customers' => $customers]);
    }

    public function create(): void
    {
        View::render('customers/create');
    }

    public function store(): void
    {
        $tenantId = $_SESSION['tenant_id'];
        $data = $_POST;

        if (empty($data['name'])) {
            $_SESSION['error'] = 'Il nome è obbligatorio.';
            header("Location: /customers/create");
            exit;
        }

        $customerId = $this->customerModel->create([
            'tenant_id' => $tenantId,
            'type' => $data['type'] ?? 'private',
            'name' => $data['name'],
            'tax_code' => $data['tax_code'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        header("Location: /customers/show?id=" . $customerId);
        exit;
    }

    public function show(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /customers");
            exit;
        }

        $customer = $this->customerModel->findById((int)$id);

        if (!$customer || $customer['tenant_id'] != $_SESSION['tenant_id']) {
            header("Location: /customers");
            exit;
        }

        // Fetch requests for this customer
        // We need a method in Request model for this
        $db = \App\Core\Database::getInstance()->pdo;
        $stmt = $db->prepare("SELECT * FROM requests WHERE customer_id = :customer_id ORDER BY created_at DESC");
        $stmt->execute(['customer_id' => $id]);
        $requests = $stmt->fetchAll();

        View::render('customers/show', [
            'customer' => $customer,
            'requests' => $requests
        ]);
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /customers");
            exit;
        }

        $customer = $this->customerModel->findById((int)$id);

        if (!$customer || $customer['tenant_id'] != $_SESSION['tenant_id']) {
            header("Location: /customers");
            exit;
        }

        View::render('customers/edit', ['customer' => $customer]);
    }

    public function update(): void
    {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: /customers");
            exit;
        }

        $tenantId = $_SESSION['tenant_id'];
        $data = $_POST;

        $customer = $this->customerModel->findById((int)$id);
        if (!$customer || $customer['tenant_id'] != $tenantId) {
            header("Location: /customers");
            exit;
        }

        $this->customerModel->update((int)$id, [
            'type' => $data['type'] ?? 'private',
            'name' => $data['name'],
            'tax_code' => $data['tax_code'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        header("Location: /customers/show?id=" . $id);
        exit;
    }
}
