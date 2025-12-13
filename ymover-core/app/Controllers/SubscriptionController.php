<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Services\StripeService;
use App\Models\Tenant;
use App\Core\Database;

class SubscriptionController
{
    private StripeService $stripeService;
    private Tenant $tenantModel;

    public function __construct()
    {
        $this->stripeService = new StripeService();
        $this->tenantModel = new Tenant();
    }

    public function index(): void
    {
        // Check if user is logged in (should be handled by middleware but good to have)
        if (!isset($_SESSION['tenant_id'])) {
            header('Location: /login');
            exit;
        }

        $tenant = $this->tenantModel->findById($_SESSION['tenant_id']);
        
        View::render('subscription/index', ['tenant' => $tenant]);
    }

    public function checkout(): void
    {
        if (!isset($_SESSION['tenant_id'])) {
            header('Location: /login');
            exit;
        }

        $tenant = $this->tenantModel->findById($_SESSION['tenant_id']);
        
        // Ensure customer exists
        if (empty($tenant['stripe_customer_id'])) {
            // We need email, let's assume we can get it from the user or tenant
            // For now, let's mock or use what we have
            $tenant['email'] = 'billing@' . str_replace(' ', '', strtolower($tenant['company_name'])) . '.com'; 
            $customerId = $this->stripeService->createCustomer($tenant);
            
            // Update DB
            $db = Database::getInstance()->pdo;
            $stmt = $db->prepare("UPDATE tenants SET stripe_customer_id = :cid WHERE id = :id");
            $stmt->execute(['cid' => $customerId, 'id' => $tenant['id']]);
            
            $tenant['stripe_customer_id'] = $customerId;
        }

        try {
            $checkoutUrl = $this->stripeService->createCheckoutSession($tenant);
            header("Location: " . $checkoutUrl);
            exit;
        } catch (\Exception $e) {
            echo "Error creating checkout session: " . $e->getMessage();
        }
    }

    public function portal(): void
    {
        if (!isset($_SESSION['tenant_id'])) {
            header('Location: /login');
            exit;
        }

        $tenant = $this->tenantModel->findById($_SESSION['tenant_id']);

        try {
            $portalUrl = $this->stripeService->createPortalSession($tenant);
            header("Location: " . $portalUrl);
            exit;
        } catch (\Exception $e) {
            echo "Error creating portal session: " . $e->getMessage();
        }
    }

    public function webhook(): void
    {
        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        $event = $this->stripeService->handleWebhook($payload, $sigHeader);

        // Handle the event
        $db = Database::getInstance()->pdo;

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $customerId = $session->customer;
                
                // Activate tenant
                $stmt = $db->prepare("UPDATE tenants SET subscription_status = 'active' WHERE stripe_customer_id = :cid");
                $stmt->execute(['cid' => $customerId]);
                break;
                
            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
                $customerId = $invoice->customer;
                
                // Extend subscription
                $stmt = $db->prepare("UPDATE tenants SET subscription_status = 'active', subscription_ends_at = DATE_ADD(NOW(), INTERVAL 1 MONTH) WHERE stripe_customer_id = :cid");
                $stmt->execute(['cid' => $customerId]);
                break;
                
            case 'invoice.payment_failed':
                $invoice = $event->data->object;
                $customerId = $invoice->customer;
                
                $stmt = $db->prepare("UPDATE tenants SET subscription_status = 'past_due' WHERE stripe_customer_id = :cid");
                $stmt->execute(['cid' => $customerId]);
                break;
                
            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                $customerId = $subscription->customer;
                
                $stmt = $db->prepare("UPDATE tenants SET subscription_status = 'cancelled' WHERE stripe_customer_id = :cid");
                $stmt->execute(['cid' => $customerId]);
                break;
        }

        http_response_code(200);
    }
}
