<?php
namespace App\Models;

class DashboardModel extends BaseModel
{
    public function metrics(): array
    {
        $inventoryModel = new InventoryModel();
        $alertsModel = new AlertsModel();
        $procurementModel = new ProcurementModel();

        if (self::isDemoMode()) {
            $medicines = $inventoryModel->all();
            $alerts = $alertsModel->all();
            $insights = $this->sessionCollection('insights', fn() => DemoData::aiInsights());
            $facilities = DemoData::facilities();
            $forecast = (new ForecastModel())->all();
            $syncQueue = DemoData::syncQueue();
            $orders = $procurementModel->all();
        } else {
            $medicines = $inventoryModel->all();
            $alerts = $alertsModel->all();
            $insights = self::db()->query('SELECT * FROM ai_insights ORDER BY generated_at DESC')->fetchAll();
            $facilities = DemoData::facilities();
            $forecast = (new ForecastModel())->all();
            $syncQueue = DemoData::syncQueue();
            $orders = $procurementModel->all();
        }

        $total = count($medicines);
        $inStock = count(array_filter($medicines, fn($m) => $m['status'] === 'In Stock'));
        $lowStock = count(array_filter($medicines, fn($m) => $m['status'] === 'Low Stock'));
        $outOfStock = count(array_filter($medicines, fn($m) => $m['status'] === 'Out of Stock'));
        $nearExpiry = count(array_filter($medicines, fn($m) => strtotime($m['expiry_date']) <= strtotime('+60 days')));
        $criticalAlerts = count(array_filter($alerts, fn($a) => $a['severity'] === 'Critical' && !in_array($a['status'], ['Resolved','Dismissed'], true)));
        $healthScore = $total ? (int) round(($inStock / $total) * 100) : 0;
        $emergencyShield = max(35, 100 - (($criticalAlerts * 16) + ($outOfStock * 12)));
        $wastageRisk = min(100, 18 + ($nearExpiry * 9));
        $priorityScore = min(100, 35 + ($lowStock * 6) + ($criticalAlerts * 8));
        $procurementSpend = array_sum(array_map(fn($o)=>(float)($o['value'] ?? 0), $orders));

        $statusCounts = [];
        $categoryCounts = [];
        $monthlyTrend = ['Oct' => 74, 'Nov' => 71, 'Dec' => 76, 'Jan' => 69, 'Feb' => 64, 'Mar' => 61];
        $usageByDepartment = [];

        foreach ($medicines as $m) {
            $statusCounts[$m['status']] = ($statusCounts[$m['status']] ?? 0) + 1;
            $categoryCounts[$m['category']] = ($categoryCounts[$m['category']] ?? 0) + 1;
            $usageByDepartment[$m['department']] = ($usageByDepartment[$m['department']] ?? 0) + (int)($m['avg_monthly_usage'] ?? 0);
        }
        arsort($usageByDepartment);
        usort($alerts, fn($a,$b) => strcmp($b['created_at'],$a['created_at']));
        usort($insights, fn($a,$b) => strcmp($b['generated_at'],$a['generated_at']));

        $atRisk = array_values(array_filter($medicines, fn($m) => in_array($m['status'], ['Low Stock','Out of Stock'], true)));
        usort($atRisk, function($a, $b) {
            $ra = ($a['reorder_point'] > 0) ? ($a['stock'] / $a['reorder_point']) : 999;
            $rb = ($b['reorder_point'] > 0) ? ($b['stock'] / $b['reorder_point']) : 999;
            return $ra <=> $rb;
        });

        return compact('total','inStock','lowStock','outOfStock','nearExpiry','criticalAlerts','healthScore','emergencyShield','wastageRisk','priorityScore','procurementSpend','statusCounts','categoryCounts','alerts','insights','atRisk','monthlyTrend','usageByDepartment','facilities','forecast','syncQueue');
    }

    public function reports(): array
    {
        // Reports are demo/seed data only in this version.
        return DemoData::reports();
    }

    public function aiInsights(): array
    {
        if (self::isDemoMode()) {
            return $this->sessionCollection('insights', fn() => DemoData::aiInsights());
        }

        try {
            $stmt = self::db()->query('SELECT * FROM ai_insights ORDER BY generated_at DESC LIMIT 20');
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            // Fall back to demo insights if DB query fails.
            return $this->sessionCollection('insights', fn() => DemoData::aiInsights());
        }
    }
}
