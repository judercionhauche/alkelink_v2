<?php
namespace App\Controllers;

use App\Models\AdminModel;

class UsersController extends BaseController
{
    public function index(): void
    {
        $this->requireRole(['Super Admin', 'Hospital Administrator']);
        $model = new AdminModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'add-user';
            if ($action === 'add-user') {
                $model->addUser([
                    'name' => trim($_POST['name'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'role' => trim($_POST['role'] ?? 'Clinician'),
                    'facility' => trim($_POST['facility'] ?? 'Maputo Central'),
                    'status' => trim($_POST['status'] ?? 'Active'),
                    'password' => trim($_POST['password'] ?? 'password123'),
                ]);
                $_SESSION['flash_success'] = 'User added successfully.';
                $this->redirect('users');
                return;
            }
            if ($action === 'edit-user') {
                $model->updateUser((int)$_POST['id'], [
                    'name' => trim($_POST['name'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'role' => trim($_POST['role'] ?? 'Clinician'),
                    'facility' => trim($_POST['facility'] ?? 'Maputo Central'),
                    'status' => trim($_POST['status'] ?? 'Active'),
                ]);
                $_SESSION['flash_success'] = 'User updated.';
                $this->redirect('users');
                return;
            }
            if ($action === 'reset-password') {
                $id = (int)$_POST['id'];
                $model->updateUser($id, ['password' => trim($_POST['password'] ?? 'password123')]);
                $_SESSION['flash_success'] = 'Password reset updated for demo session.';
                $this->redirect('users');
                return;
            }
        }

        if (!empty($_GET['action']) && !empty($_GET['id'])) {
            $id = (int)$_GET['id'];
            $action = $_GET['action'];
            if ($action === 'deactivate') $model->updateUser($id, ['status' => 'Inactive']);
            if ($action === 'activate') $model->updateUser($id, ['status' => 'Active']);
            if ($action === 'delete') $model->deleteUser($id);
            $_SESSION['flash_success'] = 'User action completed.';
            $this->redirect('users');
            return;
        }

        $this->render('admin/users', [
            'usersList' => $model->users(),
            'rolesList' => $model->roles(),
            'facilities' => \App\Models\DemoData::facilities(),
        ], 'Users');
    }
}
