<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    public static function render(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = __DIR__ . '/../../views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: $view");
        }
        
        ob_start();
        require $viewPath;
        $content = ob_get_clean();
        
        require __DIR__ . '/../../views/layouts/main.php';
    }
}
