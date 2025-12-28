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
        
        // Fetch Notes
        $db = \App\Core\Database::getInstance()->pdo;
        $stmt = $db->prepare("SELECT * FROM request_notes WHERE request_id = :request_id ORDER BY created_at ASC");
        $stmt->execute(['request_id' => $id]);
        $notes = $stmt->fetchAll();

        foreach ($notes as &$note) {
            $stmt = $db->prepare("SELECT * FROM attachments WHERE entity_type = 'request_note' AND entity_id = :entity_id");
            $stmt->execute(['entity_id' => $note['id']]);
            $note['attachments'] = $stmt->fetchAll();
        }

        if (empty($notes) && !empty($request['internal_notes'])) {
             // Migrate legacy internal note to notes if empty
             $notes[] = ['author_name' => 'Sistema', 'text' => $request['internal_notes'], 'created_at' => $request['created_at']];
        }

        // Fetch Files
        $stmt = $db->prepare("SELECT * FROM attachments WHERE entity_type = 'request' AND entity_id = :entity_id ORDER BY created_at DESC");
        $stmt->execute(['entity_id' => $id]);
        $files = $stmt->fetchAll();

        View::render('requests/show', [
            'request' => $request,
            'customer' => $customer,
            'stops' => $stops,
            'inventoryVersions' => $versions,
            'resources' => $resources,
            'notes' => $notes,
            'files' => $files
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

    public function addNote(): void
    {
        $requestId = $_POST['request_id'] ?? null;
        $text = $_POST['text'] ?? null;

        if (!$requestId) {
            http_response_code(400);
            return;
        }

        $db = \App\Core\Database::getInstance()->pdo;
        $stmt = $db->prepare("INSERT INTO request_notes (request_id, user_id, author_name, text) VALUES (:request_id, :user_id, :author_name, :text)");
        $stmt->execute([
            'request_id' => $requestId,
            'user_id' => $_SESSION['user_id'] ?? null,
            'author_name' => $_SESSION['user_name'] ?? 'Operatore',
            'text' => $text
        ]);

        $noteId = $db->lastInsertId();

        // Handle File Uploads for the note
        if (!empty($_FILES['files'])) {
            $files = $_FILES['files'];
            $uploadDir = __DIR__ . '/../../storage/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $filename = uniqid() . '_' . basename($files['name'][$i]);
                    $targetPath = $uploadDir . $filename;

                    if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
                        $stmt = $db->prepare("INSERT INTO attachments (tenant_id, entity_type, entity_id, filename, file_path, file_size, mime_type) VALUES (:tenant_id, 'request_note', :entity_id, :filename, :file_path, :file_size, :mime_type)");
                        $stmt->execute([
                            'tenant_id' => $_SESSION['tenant_id'],
                            'entity_id' => $noteId,
                            'filename' => $files['name'][$i],
                            'file_path' => 'uploads/' . $filename,
                            'file_size' => $files['size'][$i],
                            'mime_type' => $files['type'][$i]
                        ]);
                    }
                }
            }
        }

        http_response_code(200);
    }

    public function uploadFile(): void
    {
        $requestId = $_POST['request_id'] ?? null;
        
        if (!$requestId || empty($_FILES['file'])) {
            header("Location: /requests/show?id=" . $requestId);
            exit;
        }

        $file = $_FILES['file'];
        $uploadDir = __DIR__ . '/../../storage/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = uniqid() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $db = \App\Core\Database::getInstance()->pdo;
            $stmt = $db->prepare("INSERT INTO attachments (tenant_id, entity_type, entity_id, filename, file_path, file_size, mime_type) VALUES (:tenant_id, 'request', :entity_id, :filename, :file_path, :file_size, :mime_type)");
            $stmt->execute([
                'tenant_id' => $_SESSION['tenant_id'],
                'entity_id' => $requestId,
                'filename' => $file['name'],
                'file_path' => 'uploads/' . $filename,
                'file_size' => $file['size'],
                'mime_type' => $file['type']
            ]);
        }

        header("Location: /requests/show?id=" . $requestId);
        exit;
    }
}
