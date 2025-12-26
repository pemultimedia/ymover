<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Resource;

class ResourceController
{
    private Resource $resourceModel;

    public function __construct()
    {
        $this->resourceModel = new Resource();
    }

    public function index(): void
    {
        $resources = $this->resourceModel->getAllByTenant($_SESSION['tenant_id']);
        View::render('resources/index', ['resources' => $resources]);
    }

    public function create(): void
    {
        View::render('resources/create');
    }

    public function store(): void
    {
        $data = $_POST;
        $data['tenant_id'] = $_SESSION['tenant_id'];
        $data['specs'] = json_encode($data['specs'] ?? []);
        
        $this->resourceModel->create($data);
        
        header("Location: /resources");
        exit;
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $resource = $this->resourceModel->findById($id);
        
        if (!$resource) {
            header("Location: /resources");
            exit;
        }

        $resource['specs'] = json_decode($resource['specs'] ?? '{}', true);

        View::render('resources/edit', ['resource' => $resource]);
    }

    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $data = $_POST;
        $data['specs'] = json_encode($data['specs'] ?? []);
        
        $this->resourceModel->update($id, $data);
        
        header("Location: /resources");
        exit;
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $this->resourceModel->delete($id);
        
        header("Location: /resources");
        exit;
    }
}
