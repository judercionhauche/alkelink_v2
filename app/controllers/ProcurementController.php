<?php
namespace App\Controllers;

use App\Models\ProcurementModel;
use App\Models\DashboardModel;
use App\Models\InventoryModel;

class ProcurementController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();
        $model = new ProcurementModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'create-order') {
                $items = [];
                foreach (($_POST['item_medicine'] ?? []) as $idx => $medicine) {
                    if (!$medicine) continue;
                    $items[] = [
                        'medicine' => $medicine,
                        'qty' => (int)(($_POST['item_qty'][$idx] ?? 0)),
                        'unit_cost' => (float)(($_POST['item_cost'][$idx] ?? 0)),
                    ];
                }
                $model->createOrder([
                    'supplier' => trim($_POST['supplier'] ?? 'Unknown Supplier'),
                    'status' => 'Draft',
                    'order_date' => date('Y-m-d'),
                    'expected_delivery' => trim($_POST['expected_delivery'] ?? date('Y-m-d', strtotime('+7 days'))),
                    'value' => (float)($_POST['value'] ?? 0),
                    'ai_recommended' => !empty($_POST['ai_recommended']) ? 1 : 0,
                    'notes' => trim($_POST['notes'] ?? 'Manual order created from procurement workspace'),
                    'items' => $items,
                ]);
                $_SESSION['flash_success'] = 'Procurement order created.';
                $this->redirect('procurement');
                return;
            }

            if ($action === 'add-supplier') {
                $model->addSupplier([
                    'name' => trim($_POST['name'] ?? ''),
                    'lead_time_days' => (int)($_POST['lead_time_days'] ?? 0),
                    'reliability_score' => (int)($_POST['reliability_score'] ?? 0),
                    'contact' => trim($_POST['contact'] ?? ''),
                    'category' => trim($_POST['category'] ?? 'General'),
                    'status' => trim($_POST['status'] ?? 'Active'),
                ]);
                $_SESSION['flash_success'] = 'Supplier added.';
                $this->redirect('procurement');
                return;
            }
        }

        if (($_GET['action'] ?? '') === 'status' && !empty($_GET['id']) && !empty($_GET['value'])) {
            $model->updateStatus((int)$_GET['id'], trim($_GET['value']));
            $_SESSION['flash_success'] = 'Order status updated.';
            $this->redirect('procurement');
            return;
        }

        $insights = (new DashboardModel())->aiInsights();
        $medicines = (new InventoryModel())->all();
        $this->render('procurement/index', [
            'orders' => array_reverse($model->all()),
            'insights' => $insights,
            'suppliers' => $model->suppliers(),
            'medicines' => $medicines,
        ], 'Procurement');
    }
}
