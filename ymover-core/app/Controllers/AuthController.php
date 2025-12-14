<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\User;

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function showLoginForm(): void
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: /");
            exit;
        }
        
        // Render login view without the main layout (or a specific auth layout)
        // For now, we'll assume View::render supports a 'layout' option or we just render a standalone file
        // Since View::render wraps in main layout by default, we might need to adjust View class or just use main layout for now but hide sidebar
        // Let's assume we can pass a layout option to View::render or create a separate method.
        // Looking at View.php (from memory), it includes 'views/layouts/main.php'. 
        // We should probably create a 'views/layouts/auth.php' or just handle it in the view.
        // For simplicity, let's render 'auth/login' and handle layout inside it or modify View.php later if needed.
        // Actually, let's just use the main layout for now, it's fine.
        
        View::render('auth/login');
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email e password sono obbligatori.';
            header("Location: /login");
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Login Success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['tenant_id'] = $user['tenant_id'];
            $_SESSION['lang'] = $user['language'] ?? 'it';
            
            header("Location: /");
            exit;
        } else {
            // Login Failed
            $_SESSION['error'] = 'Credenziali non valide.';
            header("Location: /login");
            exit;
        }
    }

    public function logout(): void
    {
        session_destroy();
        header("Location: /login");
        exit;
    }
}
