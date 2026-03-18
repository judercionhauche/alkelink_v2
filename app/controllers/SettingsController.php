<?php
namespace App\Controllers;

use App\Models\SettingsModel;

class SettingsController extends BaseController
{
    public function index(): void
    {
        $this->requireRole(['Super Admin','Hospital Administrator']);
        $model = new SettingsModel();
        $this->render('settings/index', $model->all(), 'Settings');
    }
}
