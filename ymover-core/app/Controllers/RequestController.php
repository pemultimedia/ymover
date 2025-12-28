<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Request;
use App\Models\Customer;
use App\Models\InventoryVersion;
use App\Models\InventoryBlock;
use App\Models\Stop;


class RequestController
{
    private Request $requestModel;
    private Customer $customerModel;
    private Stop $stopModel;


    public function __construct()
    {
        $this->requestModel = new Request();
        $this->customerModel = new Customer();
        $this->stopModel = new Stop();
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
        $customers = $this->customerModel->getAllByTenant($_SESSION['tenant_id']);
        View::render('requests/create', ['customers' => $customers]);
    }

    public function store(): void
    {
        $tenantId = $_SESSION['tenant_id'];
        $data = $_POST;
        
        // 1. Get or Create Customer
        $customerId = $data['customer_id'] ?? null;
        
        if (empty($customerId) && !empty($data['customer_name'])) {
            $customerData = [
                'tenant_id' => $tenantId,
                'name' => $data['customer_name'],
                'type' => 'private',
            ];
            $customerId = $this->customerModel->create($customerData);
        }

        if (empty($customerId)) {
            $_SESSION['error'] = 'Devi selezionare o creare un cliente.';
            header("Location: /requests/create");
            exit;
        }
        
        // 2. Create Request
        $requestData = [
            'tenant_id' => $tenantId,
            'customer_id' => $customerId,
            'status' => 'new',
            'source' => $data['source'] ?? 'manual',
            'internal_notes' => $data['internal_notes'] ?? null,
        ];
        
        $requestId = $this->requestModel->create($requestData);

        // 3. Create Stops if provided
        if (!empty($data['origin_address'])) {
            $this->stopModel->create([
                'request_id' => $requestId,
                'address_full' => $data['origin_address']
            ]);
        }
        if (!empty($data['destination_address'])) {
            $this->stopModel->create([
                'request_id' => $requestId,
                'address_full' => $data['destination_address']
            ]);
        }

        
        // 4. Create Initial Inventory Version & Block
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
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            header("Location: /requests");
            exit;
        }

        // Fetch Request
        $request = $this->requestModel->findById($id, $_SESSION['tenant_id']);
        
        if (!$request) {
             header("Location: /requests");
             exit;
        }
        
        $customer = $this->customerModel->findById($request['customer_id']);
        
        // Fetch Stops (Ordered)
        $stops = $this->stopModel->getByRequestId($id);
        
        // Fetch Inventory (Hierarchical)
        $versionModel = new InventoryVersion();
        $blockModel = new InventoryBlock();
        $itemModel = new \App\Models\InventoryItem(); // Assuming this model exists or will be created
        
        $versions = $versionModel->getByRequestId($id);
        foreach ($versions as &$version) {
            $version['blocks'] = $blockModel->getByVersionId($version['id']);
            $version['total_volume'] = 0;
            foreach ($version['blocks'] as &$block) {
                $block['items'] = $itemModel->getByBlockId($block['id']);
                $block['volume'] = 0;
                foreach ($block['items'] as $item) {
                    $itemVol = ($item['width'] * $item['height'] * $item['depth'] * $item['quantity']) / 1000000; // cm to m3
                    $block['volume'] += $itemVol;
                }
                $version['total_volume'] += $block['volume'];
            }
        }
        
        // Fetch Resources
        $resourceModel = new \App\Models\Resource();
        $resources = $resourceModel->getAllByTenant($_SESSION['tenant_id']);
        
        // Mock Notes (until Note model is ready)
        $notes = [
            ['author' => 'Sistema', 'text' => 'Richiesta creata.', 'created_at' => $request['created_at']],
        ];

        View::render('requests/show', [
            'request' => $request,
            'customer' => $customer,
            'stops' => $stops,
            'inventoryVersions' => $versions,
            'resources' => $resources,
            'notes' => $notes
        ]);
    }

    public function updateStatus(): void
    {
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$id || !$status) {
            header("Location: /requests");
            exit;
        }

        $db = \App\Core\Database::getInstance()->pdo;
        $stmt = $db->prepare("UPDATE requests SET status = :status WHERE id = :id AND tenant_id = :tenant_id");
        $stmt->execute([
            'status' => $status,
            'id' => $id,
            'tenant_id' => $_SESSION['tenant_id']
        ]);

        header("Location: /requests/show?id=" . $id);
        exit;
    }

    public function addStop(): void
    {
        $requestId = $_POST['request_id'] ?? null;
        if (!$requestId) {
            header("Location: /requests");
            exit;
        }

        $warehouseId = !empty($_POST['warehouse_id']) ? (int)$_POST['warehouse_id'] : null;
        $addressFull = $_POST['address_full'] ?? '';
        $city = $_POST['city'] ?? null;
        $lat = null;
        $lng = null;

        if ($warehouseId) {
            $warehouseModel = new \App\Models\Warehouse();
            $warehouse = $warehouseModel->getById($warehouseId);
            if ($warehouse) {
                $addressFull = $warehouse['address']; // Use warehouse address
                $city = $warehouse['city'];
                $lat = $warehouse['lat'];
                $lng = $warehouse['lng'];
            }
        }

        $this->stopModel->create([
            'request_id' => $requestId,
            'warehouse_id' => $warehouseId,
            'address_full' => $addressFull,
            'city' => $city,
            'lat' => $lat,
            'lng' => $lng,
            'floor' => $_POST['floor'] ?? 0,
            'elevator_status' => $_POST['elevator_status'] ?? 'unknown',
            'notes' => $_POST['notes'] ?? null,
        ]);

        header("Location: /requests/show?id=" . $requestId);
        exit;
    }


    public function removeStop(): void
    {
        $id = $_GET['id'] ?? null;
        $requestId = $_GET['request_id'] ?? null;

        if (!$id || !$requestId) {
            header("Location: /requests");
            exit;
        }

        $db = \App\Core\Database::getInstance()->pdo;
        $stmt = $db->prepare("DELETE FROM stops WHERE id = :id AND request_id = :request_id");
        $stmt->execute(['id' => (int)$id, 'request_id' => (int)$requestId]);

        header("Location: /requests/show?id=" . $requestId);
        exit;
    }

}
