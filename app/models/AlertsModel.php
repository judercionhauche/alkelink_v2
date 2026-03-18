<?php
namespace App\Models;

use PDO;

class AlertsModel extends BaseModel
{
    public function all(): array
    {
        if (self::isDemoMode()) {
            return $this->sessionCollection('alerts', fn() => DemoData::alerts());
        }

        $pdo = self::db();
        $stmt = $pdo->query('SELECT * FROM alerts ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $id, string $status): void
    {
        if (self::isDemoMode()) {
            $alerts = $this->all();
            foreach ($alerts as &$alert) {
                if ((int)$alert['id'] === $id) {
                    $alert['status'] = $status;
                    break;
                }
            }
            unset($alert);
            $this->setSessionCollection('alerts', $alerts);
            return;
        }

        $pdo = self::db();
        $stmt = $pdo->prepare('UPDATE alerts SET status = :status WHERE id = :id');
        $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function create(array $data): void
    {
        if (self::isDemoMode()) {
            $alerts = $this->all();
            $alerts[] = [
                'id' => $this->nextId($alerts),
                'title' => trim($data['title'] ?? 'New Alert'),
                'severity' => trim($data['severity'] ?? 'Medium'),
                'type' => trim($data['type'] ?? 'Low Stock'),
                'status' => 'New',
                'medicine' => trim($data['medicine'] ?? ''),
                'department' => trim($data['department'] ?? 'Pharmacy'),
                'facility' => trim($data['facility'] ?? 'Maputo Central'),
                'recommended_action' => trim($data['recommended_action'] ?? 'Review and respond.'),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->setSessionCollection('alerts', $alerts);
            return;
        }

        $pdo = self::db();
        $stmt = $pdo->prepare('INSERT INTO alerts (title, severity, type, status, medicine, department, facility, recommended_action, created_at) VALUES (:title, :severity, :type, :status, :medicine, :department, :facility, :recommended_action, :created_at)');
        $stmt->execute([
            'title' => trim($data['title'] ?? 'New Alert'),
            'severity' => trim($data['severity'] ?? 'Medium'),
            'type' => trim($data['type'] ?? 'Low Stock'),
            'status' => 'New',
            'medicine' => trim($data['medicine'] ?? ''),
            'department' => trim($data['department'] ?? 'Pharmacy'),
            'facility' => trim($data['facility'] ?? 'Maputo Central'),
            'recommended_action' => trim($data['recommended_action'] ?? 'Review and respond.'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
