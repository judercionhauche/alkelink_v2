<?php
namespace App\Controllers;

use App\Models\InventoryModel;

class OperationsController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();
        $model = new InventoryModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model->addMovement([
                'medicine_id' => (int)($_POST['medicine_id'] ?? 0),
                'type' => trim($_POST['type'] ?? 'Receipt'),
                'quantity' => (int)($_POST['quantity'] ?? 0),
                'facility' => trim($_POST['facility'] ?? ''),
                'department' => trim($_POST['department'] ?? ''),
                'performed_by' => $_SESSION['user']['name'] ?? 'Demo User',
                'notes' => trim($_POST['notes'] ?? ''),
                'offline' => !empty($_POST['offline']),
                'device' => trim($_POST['device'] ?? 'Store Tablet'),
            ]);
            $_SESSION['flash_success'] = 'Operational transaction saved.';
            $this->redirect('operations');
            return;
        }
        $this->render('operations/index', [
            'items' => $model->all(),
            'movements' => array_slice(array_reverse($model->movements()), 0, 12),
            'departments' => \App\Models\DemoData::departments(),
            'facilities' => \App\Models\DemoData::facilities(),
        ], 'Stock Operations');
    }
}
