<?php
namespace App\Controllers;

use App\Models\InventoryModel;

class InventoryController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();
        $model = new InventoryModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'movement') {
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
                $_SESSION['flash_success'] = 'Stock movement logged successfully.';
                $this->redirect('inventory');
                return;
            }
            if (in_array($action, ['add-medicine', 'edit-medicine'], true)) {
                $id = $model->saveMedicine($_POST);
                $_SESSION['flash_success'] = $action === 'add-medicine' ? 'Medicine added successfully.' : 'Medicine updated successfully.';
                $this->redirect('inventory&detail=' . $id);
                return;
            }
        }

        if (($_GET['action'] ?? '') === 'delete' && !empty($_GET['id'])) {
            $model->deleteMedicine((int)$_GET['id']);
            $_SESSION['flash_success'] = 'Medicine removed from demo catalog.';
            $this->redirect('inventory');
            return;
        }

        if (($_GET['action'] ?? '') === 'sync-queue') {
            $model->syncQueue();
            $_SESSION['flash_success'] = 'Offline queue synced in demo mode.';
            $this->redirect('inventory');
            return;
        }

        $items = $model->all();
        $search = trim($_GET['search'] ?? '');
        $category = trim($_GET['category'] ?? 'All');
        $status = trim($_GET['status'] ?? 'All');
        $essential = trim($_GET['essential'] ?? 'All Medicines');
        $filtered = array_values(array_filter($items, function ($item) use ($search, $category, $status, $essential) {
            $matchSearch = $search === '' || stripos($item['name'], $search) !== false || stripos($item['brand_name'], $search) !== false || stripos($item['sku'], $search) !== false;
            $matchCategory = $category === 'All' || $item['category'] === $category;
            $matchStatus = $status === 'All' || $item['status'] === $status;
            $matchEssential = $essential === 'All Medicines' || ($essential === 'Essential Only' ? !empty($item['is_essential']) : empty($item['is_essential']));
            return $matchSearch && $matchCategory && $matchStatus && $matchEssential;
        }));

        $detailId = (int)($_GET['detail'] ?? 0);
        $editId = (int)($_GET['edit'] ?? 0);
        $selected = $detailId ? $model->find($detailId) : null;
        $editMedicine = $editId ? $model->find($editId) : null;

        $this->render('inventory/index', [
            'items' => $filtered,
            'allItems' => $items,
            'movements' => array_slice(array_reverse($model->movements()), 0, 6),
            'queue' => $model->queue(),
            'selectedMedicine' => $selected,
            'selectedBatches' => $selected ? $model->batchesForMedicine($selected['id']) : [],
            'selectedHistory' => $selected ? $model->historyForMedicine($selected['id']) : [],
            'editMedicine' => $editMedicine,
            'search' => $search,
            'category' => $category,
            'status' => $status,
            'essential' => $essential,
        ], 'Inventory');
    }
}
