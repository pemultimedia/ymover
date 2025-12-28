<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\MarketplaceAd;

class MarketplaceController
{
    private MarketplaceAd $adModel;

    public function __construct()
    {
        $this->adModel = new MarketplaceAd();
        
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
    }

    public function index(): void
    {
        $ads = $this->adModel->getAll();
        View::render('marketplace/index', ['ads' => $ads]);
    }

    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $ad = $this->adModel->getById($id);

        if (!$ad) {
            header("Location: /marketplace");
            exit;
        }

        View::render('marketplace/show', ['ad' => $ad]);
    }

    public function create(): void
    {
        View::render('marketplace/create');
    }

    public function store(): void
    {
        $data = [
            'tenant_id' => $_SESSION['tenant_id'],
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'type' => $_POST['type'] ?? 'vehicle',
            'price_fixed' => (float)($_POST['price'] ?? 0),
            'city' => $_POST['city'] ?? '',
            'available_from' => $_POST['available_from'] ?? date('Y-m-d H:i:s'),
            'available_to' => $_POST['available_to'] ?? date('Y-m-d H:i:s', strtotime('+30 days')),
            'lat' => 0.0, // Default for now
            'lng' => 0.0  // Default for now
        ];

        $this->adModel->create($data);
        header("Location: /marketplace");
        exit;
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $ad = $this->adModel->getById($id);

        if (!$ad || !$this->canEdit($ad)) {
            header("Location: /marketplace");
            exit;
        }

        View::render('marketplace/edit', ['ad' => $ad]);
    }

    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $ad = $this->adModel->getById($id);

        if (!$ad || !$this->canEdit($ad)) {
            header("Location: /marketplace");
            exit;
        }

        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'type' => $_POST['type'] ?? 'vehicle',
            'price_fixed' => (float)($_POST['price'] ?? 0),
            'city' => $_POST['city'] ?? '',
            'available_from' => $_POST['available_from'] ?? date('Y-m-d H:i:s'),
            'available_to' => $_POST['available_to'] ?? date('Y-m-d H:i:s', strtotime('+30 days'))
        ];

        $this->adModel->update($id, $data);
        header("Location: /marketplace/show?id=" . $id);
        exit;
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $ad = $this->adModel->getById($id);

        if ($ad && $this->canEdit($ad)) {
            $this->adModel->delete($id);
        }

        header("Location: /marketplace");
        exit;
    }

    private function canEdit(array $ad): bool
    {
        // Allow edit if user belongs to the same tenant (company)
        return isset($_SESSION['tenant_id']) && $ad['tenant_id'] == $_SESSION['tenant_id'];
    }
}
