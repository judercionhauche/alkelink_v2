<?php
namespace App\Controllers;

use App\Models\DashboardModel;

class ReportsController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();

        if (($_GET['export'] ?? '') === 'csv') {
            $rows = (new DashboardModel())->reports();
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="alkelink_reports.csv"');
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Report', 'Summary']);
            foreach ($rows as $row) {
                fputcsv($out, [$row['title'], $row['summary']]);
            }
            fclose($out);
            exit;
        }

        $this->render('reports/index', ['reports' => (new DashboardModel())->reports()], 'Reports');
    }
}
