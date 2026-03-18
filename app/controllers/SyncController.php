<?php
namespace App\Controllers;

use App\Models\InventoryModel;

class SyncController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();
        $model = new InventoryModel();
        if (($_GET['action'] ?? '') === 'sync') {
            $model->syncQueue();
            $_SESSION['flash_success'] = 'Sync queue flushed in demo mode.';
            $this->redirect('sync-center');
            return;
        }
        $this->render('sync/index', [
            'queue' => $model->queue(),
            'devices' => \App\Models\DemoData::syncQueue(),
            'quality' => \App\Models\DemoData::dataQuality(),
        ], 'Sync Center');
    }
}
