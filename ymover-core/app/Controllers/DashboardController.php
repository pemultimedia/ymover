<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

class DashboardController
{
    public function index(): void
    {
        $db = \App\Core\Database::getInstance()->pdo;
        $tenantId = $_SESSION['tenant_id'];

        // 1. Request Stats
        $stmt = $db->prepare("SELECT status, COUNT(*) as count FROM requests WHERE tenant_id = :tenant_id GROUP BY status");
        $stmt->execute(['tenant_id' => $tenantId]);
        $requestStats = $stmt->fetchAll();

        // 2. Revenue Stats (Accepted Quotes)
        $stmt = $db->prepare("SELECT SUM(total_amount) as total FROM quotes WHERE tenant_id = :tenant_id AND status = 'accepted'");
        $stmt->execute(['tenant_id' => $tenantId]);
        $revenue = $stmt->fetch()['total'] ?? 0;

        // 3. Monthly Trend (Last 6 months)
        $stmt = $db->prepare("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                             FROM requests 
                             WHERE tenant_id = :tenant_id 
                             AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                             GROUP BY month ORDER BY month ASC");
        $stmt->execute(['tenant_id' => $tenantId]);
        $trend = $stmt->fetchAll();

        View::render('dashboard/index', [
            'requestStats' => $requestStats,
            'revenue' => $revenue,
            'trend' => $trend
        ]);
    }
}
