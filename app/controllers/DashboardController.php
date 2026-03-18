<?php
namespace App\Controllers;

use App\Models\DashboardModel;

class DashboardController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();
        $model = new DashboardModel();
        $this->render('dashboard/index', ['data' => $model->metrics()], 'Dashboard');
    }
}
