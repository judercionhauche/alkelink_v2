<?php
namespace App\Controllers;

use App\Models\AlertsModel;

class AlertsController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();
        $model = new AlertsModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create-alert') {
            $model->create([
                'title' => trim($_POST['title'] ?? ''),
                'severity' => trim($_POST['severity'] ?? 'Medium'),
                'type' => trim($_POST['type'] ?? 'Low Stock'),
                'medicine' => trim($_POST['medicine'] ?? ''),
                'department' => trim($_POST['department'] ?? 'Pharmacy'),
                'facility' => trim($_POST['facility'] ?? 'Maputo Central'),
                'recommended_action' => trim($_POST['recommended_action'] ?? ''),
            ]);
            $_SESSION['flash_success'] = 'Alert created.';
            $this->redirect('alerts');
            return;
        }

        if (($_GET['action'] ?? '') === 'status' && !empty($_GET['id']) && !empty($_GET['value'])) {
            $model->updateStatus((int)$_GET['id'], trim($_GET['value']));
            $_SESSION['flash_success'] = 'Alert status updated.';
            $this->redirect('alerts');
            return;
        }

        $alerts = $model->all();
        $severity = $_GET['severity'] ?? 'All Severities';
        $type = $_GET['type'] ?? 'All Types';
        $state = $_GET['state'] ?? 'All Statuses';
        $alerts = array_values(array_filter($alerts, function ($a) use ($severity, $type, $state) {
            return ($severity === 'All Severities' || $a['severity'] === $severity)
                && ($type === 'All Types' || $a['type'] === $type)
                && ($state === 'All Statuses' || $a['status'] === $state);
        }));
        $this->render('alerts/index', ['alerts' => $alerts, 'severity' => $severity, 'type' => $type, 'state' => $state], 'Alerts');
    }
}
