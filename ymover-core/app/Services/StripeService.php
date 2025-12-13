<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Checkout\Session;
use Stripe\BillingPortal\Session as PortalSession;
use Stripe\Webhook;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
    }

    public function createCustomer(array $tenant): string
    {
        if (!empty($tenant['stripe_customer_id'])) {
            return $tenant['stripe_customer_id'];
        }

        $customer = Customer::create([
            'email' => $tenant['email'] ?? null, // Assuming we have email in tenant or we pass it
            'name' => $tenant['company_name'],
            'metadata' => [
                'tenant_id' => $tenant['id']
            ]
        ]);

        // Update tenant in DB with stripe_customer_id (Logic should be in Controller or Model, but here we just return ID)
        return $customer->id;
    }

    public function createCheckoutSession(array $tenant): string
    {
        $customerId = $tenant['stripe_customer_id'];
        if (empty($customerId)) {
            throw new \RuntimeException("Tenant has no Stripe Customer ID");
        }

        // Calculate trial end
        // If trial_ends_at is in the future, we use the remaining days
        // If it's null, we give 7 days
        // If it's past, no trial
        
        $trialPeriodDays = 7;
        $now = new \DateTime();
        
        // Logic for trial period adjustment can be complex, for now we stick to simple 7 days for new subs
        // or 0 if already used.
        // Simplified: Always 7 days trial for now as per requirements
        
        $session = Session::create([
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $_ENV['STRIPE_PRICE_ID'],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'subscription_data' => [
                'trial_period_days' => $trialPeriodDays,
            ],
            'success_url' => $_ENV['APP_URL'] . '/dashboard?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['APP_URL'] . '/subscribe',
        ]);

        return $session->url;
    }

    public function createPortalSession(array $tenant): string
    {
        $customerId = $tenant['stripe_customer_id'];
        if (empty($customerId)) {
            throw new \RuntimeException("Tenant has no Stripe Customer ID");
        }

        $session = PortalSession::create([
            'customer' => $customerId,
            'return_url' => $_ENV['APP_URL'] . '/dashboard',
        ]);

        return $session->url;
    }

    public function handleWebhook(string $payload, string $sigHeader)
    {
        $endpointSecret = $_ENV['STRIPE_WEBHOOK_SECRET'];

        try {
            $event = Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        return $event;
    }
}
