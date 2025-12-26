<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

class MarketplaceController
{
    public function index(): void
    {
        // In a real app, we would fetch listings from a global table or an external API.
        // For now, we'll show a placeholder view.
        View::render('marketplace/index');
    }
}
