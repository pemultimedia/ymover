<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\Tenant;

class AuthMiddleware
{
    public function handle(): void
    {
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Exclude Webhook and Login/Subscription routes
        if (strpos($uri, '/webhook') === 0 || 
            strpos($uri, '/login') === 0 || 
            strpos($uri, '/subscribe') === 0 ||
            strpos($uri, '/quotes/public') === 0 ||
            strpos($uri, '/assets') === 0) {
            return;
        }

        // Check Login
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['tenant_id'])) {
            header('Location: /login');
            exit;
        }

        // Check Subscription
        $tenantModel = new Tenant();
        $tenant = $tenantModel->findById($_SESSION['tenant_id']);

        if (!$tenant) {
            // Should not happen if session is valid
            session_destroy();
            header('Location: /login');
            exit;
        }

        $status = $tenant['subscription_status'];
        $trialEnds = $tenant['trial_ends_at'] ? new \DateTime($tenant['trial_ends_at']) : null;
        $now = new \DateTime();

        $isTrialActive = $status === 'trial' && $trialEnds && $trialEnds > $now;
        $isActive = $status === 'active';

        if (!$isActive && !$isTrialActive) {
            // Redirect to subscription page
            header('Location: /subscribe');
            exit;
        }
    }
}
