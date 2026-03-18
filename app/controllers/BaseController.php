<?php
namespace App\Controllers;

class BaseController
{
    protected function render(string $view, array $data = [], string $title = 'AlkeLink'): void
    {
        extract($data);
        $pageTitle = $title;
        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    protected function redirect(string $route): void
    {
        header('Location: index.php?route=' . $route);
        exit;
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION['user'])) {
            $this->redirect('login');
        }
    }

    protected function requireRole(array $roles): void
    {
        $this->requireAuth();
        $userRole = $_SESSION['user']['role'] ?? '';
        if (!in_array($userRole, $roles, true)) {
            http_response_code(403);
            $this->render('admin/forbidden', ['roles' => $roles], 'Access denied');
            exit;
        }
    }
}
