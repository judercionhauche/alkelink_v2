<?php
namespace App\Models;

use PDO;

class ProcurementModel extends BaseModel
{
    public function all(): array
    {
        if (self::isDemoMode()) {
            return $this->sessionCollection('orders', fn() => DemoData::procurementOrders());
        }

        $pdo = self::db();
        $stmt = $pdo->query('SELECT * FROM procurement_orders ORDER BY order_date DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function suppliers(): array
    {
        if (self::isDemoMode()) {
            return $this->sessionCollection('suppliers', fn() => DemoData::suppliers());
        }

        $pdo = self::db();
        $stmt = $pdo->query('SELECT * FROM suppliers ORDER BY name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createOrder(array $data): void
    {
        if (self::isDemoMode()) {
            $orders = $this->all();
            $data['id'] = $this->nextId($orders);
            $data['order_number'] = 'PO-' . date('Ymd') . '-' . rand(100, 999);
            $data['items'] = $data['items'] ?? [];
            $orders[] = $data;
            $this->setSessionCollection('orders', $orders);
            return;
        }

        $pdo = self::db();
        $stmt = $pdo->prepare('INSERT INTO procurement_orders (order_number, supplier, status, order_date, expected_delivery, value, ai_recommended, notes, created_at) VALUES (:order_number, :supplier, :status, :order_date, :expected_delivery, :value, :ai_recommended, :notes, :created_at)');
        $stmt->execute([
            'order_number' => 'PO-' . date('Ymd') . '-' . rand(100, 999),
            'supplier' => trim($data['supplier'] ?? ''),
            'status' => trim($data['status'] ?? 'Draft'),
            'order_date' => $data['order_date'] ?? date('Y-m-d'),
            'expected_delivery' => $data['expected_delivery'] ?: null,
            'value' => (float)($data['value'] ?? 0),
            'ai_recommended' => !empty($data['ai_recommended']) ? 1 : 0,
            'notes' => trim($data['notes'] ?? ''),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function updateStatus(int $id, string $status): void
    {
        if (self::isDemoMode()) {
            $orders = $this->all();
            foreach ($orders as &$order) {
                if ((int)$order['id'] === $id) {
                    $order['status'] = $status;
                    break;
                }
            }
            unset($order);
            $this->setSessionCollection('orders', $orders);
            return;
        }

        $pdo = self::db();
        $stmt = $pdo->prepare('UPDATE procurement_orders SET status = :status WHERE id = :id');
        $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function addSupplier(array $data): void
    {
        if (self::isDemoMode()) {
            $suppliers = $this->suppliers();
            $data['id'] = $this->nextId($suppliers);
            $suppliers[] = $data;
            $this->setSessionCollection('suppliers', $suppliers);
            return;
        }

        $pdo = self::db();
        $stmt = $pdo->prepare('INSERT INTO suppliers (name, lead_time_days, reliability_score, contact, category, status) VALUES (:name, :lead_time_days, :reliability_score, :contact, :category, :status)');
        $stmt->execute([
            'name' => trim($data['name'] ?? ''),
            'lead_time_days' => (int)($data['lead_time_days'] ?? 0),
            'reliability_score' => (int)($data['reliability_score'] ?? 0),
            'contact' => trim($data['contact'] ?? ''),
            'category' => trim($data['category'] ?? ''),
            'status' => trim($data['status'] ?? ''),
        ]);
    }
}
