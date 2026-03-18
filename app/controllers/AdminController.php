<?php
namespace App\Controllers;

use App\Models\AdminModel;

class AdminController extends BaseController
{
    public function roles(): void
    {
        $this->requireRole(['Super Admin', 'Hospital Administrator']);
        $model = new AdminModel();
        $this->render('admin/roles', ['rolesList' => $model->roles()], 'Roles & Access');
    }
}
