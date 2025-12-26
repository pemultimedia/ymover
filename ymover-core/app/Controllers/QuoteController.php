<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Quote;
use App\Models\Request;
use App\Models\InventoryVersion;

class QuoteController
{
    private Quote $quoteModel;
    private Request $requestModel;
    private InventoryVersion $versionModel;

    public function __construct()
    {
        $this->quoteModel = new Quote();
        $this->requestModel = new Request();
        $this->versionModel = new InventoryVersion();
    }

    public function create(): void
    {
        $requestId = (int)($_GET['request_id'] ?? 0);
        if (!$requestId) {
            header("Location: /requests");
            exit;
        }

        $request = $this->requestModel->findById($requestId, $_SESSION['tenant_id']);
        $versions = $this->versionModel->getByRequestId($requestId);

        View::render('quotes/create', [
            'request' => $request,
            'versions' => $versions
        ]);
    }

    public function store(): void
    {
        $data = $_POST;
        $data['tenant_id'] = $_SESSION['tenant_id'];
        $data['status'] = 'draft';
        
        $id = $this->quoteModel->create($data);
        
        header("Location: /quotes/show?id=" . $id);
        exit;
    }

    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $quote = $this->quoteModel->findById($id, $_SESSION['tenant_id']);
        
        if (!$quote) {
            header("Location: /requests");
            exit;
        }

        $request = $this->requestModel->findById((int)$quote['request_id'], $_SESSION['tenant_id']);

        View::render('quotes/show', [
            'quote' => $quote,
            'request' => $request
        ]);
    }

    public function publicView(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $quote = $this->quoteModel->findById($id);
        
        if (!$quote) {
            echo "Preventivo non trovato.";
            exit;
        }

        $request = $this->requestModel->findById((int)$quote['request_id']);

        // Render a clean view without sidebar
        extract(['quote' => $quote, 'request' => $request]);
        require __DIR__ . '/../../views/quotes/public_view.php';
    }

    public function accept(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $quote = $this->quoteModel->findById($id, $_SESSION['tenant_id']);
        
        if ($quote) {
            $this->quoteModel->updateStatus($id, 'accepted');
            
            // Update request status to confirmed
            $db = \App\Core\Database::getInstance()->pdo;
            $stmt = $db->prepare("UPDATE requests SET status = 'confirmed' WHERE id = :request_id");
            $stmt->execute(['request_id' => $quote['request_id']]);
        }

        header("Location: /quotes/public?id=" . $id . "&accepted=1");
        exit;
    }

    public function pay(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $quote = $this->quoteModel->findById($id, $_SESSION['tenant_id']);
        
        if ($quote) {
            $this->quoteModel->markAsPaid($id);
        }
        
        header("Location: /quotes/show?id=" . $id . "&paid=1");
        exit;
    }
}
