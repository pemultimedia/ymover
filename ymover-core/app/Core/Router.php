<?php

declare(strict_types=1);

namespace App\Core;

use Bramus\Router\Router as BramusRouter;

class Router
{
    private BramusRouter $router;

    public function __construct()
    {
        $this->router = new BramusRouter();
    }

    public function loadRoutes(): void
    {
        $this->router->setNamespace('App\Controllers');

        // Middleware Hook
        $this->router->before('GET|POST', '/.*', function() {
            $middleware = new AuthMiddleware();
            $middleware->handle();
        });

        $this->router->get('/', function() {
            echo "YMover Core is running.";
        });

        // Request Routes
        $this->router->mount('/requests', function () {
            $this->router->get('/', 'RequestController@index');
            $this->router->get('/create', 'RequestController@create');
            $this->router->post('/store', 'RequestController@store');
        });

        // Subscription Routes
        $this->router->mount('/subscribe', function () {
            $this->router->get('/', 'SubscriptionController@index');
            $this->router->post('/checkout', 'SubscriptionController@checkout');
            $this->router->get('/portal', 'SubscriptionController@portal');
        });

        // Webhook (No Middleware check needed as it's excluded in Middleware logic, but good to be explicit if router supported it)
        $this->router->post('/webhook/stripe', 'SubscriptionController@webhook');

        // 404
        $this->router->set404(function () {
            header('HTTP/1.1 404 Not Found');
            echo '404 - Page not found';
        });
    }

    public function run(): void
    {
        $this->router->run();
    }
}
