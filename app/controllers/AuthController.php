<?php
namespace App\Controllers;

use App\Models\AuthModel;

class AuthController extends BaseController
{
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $model = new AuthModel();
            $user = $model->attempt($email, $password);
            if ($user) {
                $_SESSION['user'] = $user;
                $this->redirect('dashboard');
                return;
            }
            $this->render('auth/login', ['error' => t('login.error_invalid')], t('login.title'));
            return;
        }
        $this->render('auth/login', [], t('login.title'));

    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->redirect('login');
    }
}
