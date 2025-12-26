<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

class EmailController
{
    public function index(): void
    {
        $db = \App\Core\Database::getInstance()->pdo;
        $tenantId = $_SESSION['tenant_id'];

        $stmt = $db->prepare("SELECT * FROM email_messages WHERE tenant_id = :tenant_id ORDER BY received_at DESC LIMIT 50");
        $stmt->execute(['tenant_id' => $tenantId]);
        $emails = $stmt->fetchAll();

        View::render('emails/index', ['emails' => $emails]);
    }
}
