<?php
namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\AlertsModel;

class MobileController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();
        $this->render('mobile/index', [
            'queue' => (new InventoryModel())->queue(),
            'alerts' => array_slice((new AlertsModel())->all(), 0, 3),
        ], 'Mobile Quick Actions');
    }
}
