<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\User;

class UserController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index(): void
    {
        $users = $this->userModel->getAllByTenant($_SESSION['tenant_id']);
        View::render('users/index', ['users' => $users]);
    }

    public function create(): void
    {
        View::render('users/create');
    }

    public function store(): void
    {
        $data = $_POST;
        $data['tenant_id'] = $_SESSION['tenant_id'];
        
        $this->userModel->create($data);
        
        header("Location: /users");
        exit;
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            header("Location: /users");
            exit;
        }

        View::render('users/edit', ['user' => $user]);
    }

    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $data = $_POST;
        
        $this->userModel->update($id, $data);
        
        header("Location: /users");
        exit;
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $this->userModel->delete($id);
        
        header("Location: /users");
        exit;
    }
}
