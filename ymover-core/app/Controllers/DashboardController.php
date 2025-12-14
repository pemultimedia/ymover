<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

class DashboardController
{
    public function index(): void
    {
        View::render('dashboard/index');
    }
}
