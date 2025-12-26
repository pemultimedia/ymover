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

        $this->router->get('/', 'DashboardController@index');
        
        // Auth Routes
        $this->router->get('/login', 'AuthController@showLoginForm');
        $this->router->post('/login', 'AuthController@login');
        $this->router->get('/logout', 'AuthController@logout');

        // User/Team Routes
        $this->router->mount('/users', function () {
            $this->router->get('/', 'UserController@index');
            $this->router->get('/create', 'UserController@create');
            $this->router->post('/store', 'UserController@store');
            $this->router->get('/edit', 'UserController@edit');
            $this->router->post('/update', 'UserController@update');
            $this->router->get('/delete', 'UserController@delete');
        });

        // Customer Routes
        $this->router->mount('/customers', function () {
            $this->router->get('/', 'CustomerController@index');
            $this->router->get('/create', 'CustomerController@create');
            $this->router->post('/store', 'CustomerController@store');
            $this->router->get('/show', 'CustomerController@show');
            $this->router->get('/edit', 'CustomerController@edit');
            $this->router->post('/update', 'CustomerController@update');
        });

        // Request Routes
        $this->router->mount('/requests', function () {
            $this->router->get('/', 'RequestController@index');
            $this->router->get('/create', 'RequestController@create');
            $this->router->post('/store', 'RequestController@store');
            $this->router->get('/show', 'RequestController@show');
            $this->router->post('/update-status', 'RequestController@updateStatus');
            $this->router->post('/add-stop', 'RequestController@addStop');
            $this->router->get('/remove-stop', 'RequestController@removeStop');
        });

        // Quote Routes
        $this->router->mount('/quotes', function () {
            $this->router->get('/create', 'QuoteController@create');
            $this->router->post('/store', 'QuoteController@store');
            $this->router->get('/show', 'QuoteController@show');
            $this->router->get('/public', 'QuoteController@publicView');
            $this->router->post('/accept', 'QuoteController@accept');
        });

        // Resource Routes
        $this->router->mount('/resources', function () {
            $this->router->get('/', 'ResourceController@index');
            $this->router->get('/create', 'ResourceController@create');
            $this->router->post('/store', 'ResourceController@store');
            $this->router->get('/edit', 'ResourceController@edit');
            $this->router->post('/update', 'ResourceController@update');
            $this->router->get('/delete', 'ResourceController@delete');
        });

        // Calendar Routes
        $this->router->mount('/calendar', function () {
            $this->router->get('/', 'CalendarController@index');
            $this->router->get('/events', 'CalendarController@getEvents');
            $this->router->post('/store', 'CalendarController@store');
            $this->router->get('/delete', 'CalendarController@delete');
        });

        // API Routes
        $this->router->mount('/api', function () {
            $this->router->mount('/inventory', function () {
                $this->router->get('/', 'Api\InventoryController@getInventory');
                $this->router->post('/version/create', 'Api\InventoryController@createVersion');
                $this->router->post('/block/create', 'Api\InventoryController@createBlock');
                $this->router->post('/item/add', 'Api\InventoryController@addItem');
                $this->router->post('/item/update', 'Api\InventoryController@updateItem');
                $this->router->post('/item/move', 'Api\InventoryController@moveItem');
                $this->router->post('/item/remove', 'Api\InventoryController@removeItem');
            });
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
