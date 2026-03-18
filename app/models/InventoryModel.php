<?php
namespace App\Models;

use PDO;

class InventoryModel extends BaseModel
{
    public function all(): array
    {
        if (self::isDemoMode()) {
            return $this->sessionCollection('medicines', fn() => DemoData::medicines());
        }

        $pdo = self::db();
        $stmt = $pdo->query('SELECT * FROM medicines ORDER BY name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function movements(): array
    {
        if (self::isDemoMode()) {
            return $this->sessionCollection('stock_movements', fn() => DemoData::stockMovements());
        }

        $pdo = self::db();
        $stmt = $pdo->query('SELECT * FROM stock_movements ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queue(): array
    {
        if (self::isDemoMode()) {
            return $this->sessionCollection('queue_events', fn() => DemoData::queueEvents());
        }

        $pdo = self::db();
        try {
            $stmt = $pdo->query('SELECT * FROM queue_events ORDER BY created_at DESC');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // If the queue_events table does not exist (or other DB issue), fall back to empty.
            return [];
        }
    }

    public function find(int $id): ?array
    {
        if (self::isDemoMode()) {
            foreach ($this->all() as $item) {
                if ((int)$item['id'] === $id) {
                    return $item;
                }
            }
            return null;
        }

        $pdo = self::db();
        $stmt = $pdo->prepare('SELECT * FROM medicines WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        return $item ?: null;
    }

    public function saveMedicine(array $payload): int
    {
        $id = (int)($payload['id'] ?? 0);
        $record = [
            'name' => trim($payload['name'] ?? ''),
            'brand_name' => trim($payload['brand_name'] ?? ''),
            'category' => trim($payload['category'] ?? 'Other'),
            'dosage_form' => trim($payload['dosage_form'] ?? 'Unit'),
            'strength' => trim($payload['strength'] ?? ''),
            'sku' => trim($payload['sku'] ?? ''),
            'stock' => (int)($payload['stock'] ?? 0),
            'reorder_point' => (int)($payload['reorder_point'] ?? 0),
            'reorder_qty' => (int)($payload['reorder_qty'] ?? 0),
            'avg_monthly_usage' => (int)($payload['avg_monthly_usage'] ?? 0),
            'unit_cost' => (float)($payload['unit_cost'] ?? 0),
            'status' => trim($payload['status'] ?? 'In Stock'),
            'is_critical' => !empty($payload['is_critical']) ? 1 : 0,
            'is_essential' => !empty($payload['is_essential']) ? 1 : 0,
            'facility' => trim($payload['facility'] ?? 'Maputo Central'),
            'department' => trim($payload['department'] ?? 'Pharmacy'),
            'expiry_date' => trim($payload['expiry_date'] ?? date('Y-m-d', strtotime('+180 days'))),
            'storage' => trim($payload['storage'] ?? 'Room Temperature'),
            'notes' => trim($payload['notes'] ?? ''),
        ];

        $record['status'] = $record['stock'] <= 0 ? 'Out of Stock' : ($record['stock'] < max(1, $record['reorder_point']) ? 'Low Stock' : ($record['stock'] > max(1, $record['reorder_point'] * 2) ? 'Overstocked' : 'In Stock'));

        if (self::isDemoMode()) {
            $items = $this->all();
            $record['id'] = $id ?: $this->nextId($items);

            $updated = false;
            foreach ($items as &$item) {
                if ((int)$item['id'] === $record['id']) {
                    $item = array_merge($item, $record);
                    $updated = true;
                    break;
                }
            }
            unset($item);
            if (!$updated) {
                $items[] = $record;
            }
            $this->setSessionCollection('medicines', $items);
            return $record['id'];
        }

        $pdo = self::db();
        if ($id > 0) {
            $record['id'] = $id;
            $stmt = $pdo->prepare('UPDATE medicines SET name=:name, brand_name=:brand_name, category=:category, dosage_form=:dosage_form, strength=:strength, sku=:sku, stock=:stock, reorder_point=:reorder_point, reorder_qty=:reorder_qty, avg_monthly_usage=:avg_monthly_usage, unit_cost=:unit_cost, status=:status, is_critical=:is_critical, is_essential=:is_essential, facility=:facility, department=:department, expiry_date=:expiry_date, storage=:storage, notes=:notes WHERE id = :id');
            $stmt->execute($record);
            return $id;
        }

        $fields = array_keys($record);
        $placeholders = array_map(fn($f) => ':' . $f, $fields);
        $sql = 'INSERT INTO medicines (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $placeholders) . ')';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($record);
        return (int)$pdo->lastInsertId();
    }

    public function deleteMedicine(int $id): void
    {
        if (self::isDemoMode()) {
            $items = array_values(array_filter($this->all(), fn($item) => (int)$item['id'] !== $id));
            $this->setSessionCollection('medicines', $items);
            return;
        }

        $pdo = self::db();
        $stmt = $pdo->prepare('DELETE FROM medicines WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function addMovement(array $payload): void
    {
        if (!$payload['medicine_id'] || !$payload['quantity']) {
            return;
        }

        if (self::isDemoMode()) {
            $items = $this->all();
            $movements = $this->movements();
            foreach ($items as &$item) {
                if ((int)$item['id'] === (int)$payload['medicine_id']) {
                    $delta = (int)$payload['quantity'];
                    if (in_array($payload['type'], ['Dispensing', 'Transfer Out', 'Write-off', 'Adjustment Out'], true)) {
                        $delta *= -1;
                    }
                    $item['stock'] = max(0, (int)$item['stock'] + $delta);
                    $item['status'] = $item['stock'] <= 0 ? 'Out of Stock' : ($item['stock'] < (int)$item['reorder_point'] ? 'Low Stock' : 'In Stock');
                    $payload['medicine'] = $item['name'];
                    $payload['facility'] = $payload['facility'] ?: $item['facility'];
                    $payload['department'] = $payload['department'] ?: $item['department'];
                    break;
                }
            }
            unset($item);
            $payload['id'] = $this->nextId($movements);
            $payload['created_at'] = date('Y-m-d H:i:s');
            $movements[] = $payload;
            $queue = $this->queue();
            if (!empty($payload['offline'])) {
                $queue[] = [
                    'id' => $this->nextId($queue),
                    'type' => $payload['type'],
                    'reference' => strtoupper(substr($payload['type'], 0, 3)) . '-' . str_pad((string)rand(1, 999), 3, '0', STR_PAD_LEFT),
                    'device' => $payload['device'] ?? 'Store Tablet',
                    'facility' => $payload['facility'],
                    'payload' => $payload['medicine'] . ' movement queued locally',
                    'status' => 'Pending',
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $this->setSessionCollection('queue_events', $queue);
            }
            $this->setSessionCollection('medicines', $items);
            $this->setSessionCollection('stock_movements', $movements);
            return;
        }

        $pdo = self::db();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('SELECT * FROM medicines WHERE id = :id FOR UPDATE');
            $stmt->execute(['id' => $payload['medicine_id']]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$item) {
                $pdo->rollBack();
                return;
            }

            $delta = (int)$payload['quantity'];
            if (in_array($payload['type'], ['Dispensing', 'Transfer Out', 'Write-off', 'Adjustment Out'], true)) {
                $delta *= -1;
            }
            $newStock = max(0, (int)$item['stock'] + $delta);
            $newStatus = $newStock <= 0 ? 'Out of Stock' : ($newStock < (int)$item['reorder_point'] ? 'Low Stock' : 'In Stock');

            $updateStmt = $pdo->prepare('UPDATE medicines SET stock = :stock, status = :status WHERE id = :id');
            $updateStmt->execute(['stock' => $newStock, 'status' => $newStatus, 'id' => $item['id']]);

            $movement = [
                'medicine_id' => $item['id'],
                'medicine' => $item['name'],
                'type' => trim($payload['type'] ?? 'Receipt'),
                'quantity' => (int)$payload['quantity'],
                'facility' => trim($payload['facility'] ?: $item['facility']),
                'department' => trim($payload['department'] ?: $item['department']),
                'performed_by' => trim($payload['performed_by'] ?? ($_SESSION['user']['name'] ?? 'System')),
                'notes' => trim($payload['notes'] ?? ''),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $mvStmt = $pdo->prepare('INSERT INTO stock_movements (medicine_id, medicine, type, quantity, facility, department, performed_by, notes, created_at) VALUES (:medicine_id, :medicine, :type, :quantity, :facility, :department, :performed_by, :notes, :created_at)');
            $mvStmt->execute($movement);

            if (!empty($payload['offline'])) {
                $queueStmt = $pdo->prepare('INSERT INTO queue_events (type, reference, device, facility, payload, status, created_at) VALUES (:type, :reference, :device, :facility, :payload, :status, :created_at)');
                $queueStmt->execute([
                    'type' => $movement['type'],
                    'reference' => strtoupper(substr($movement['type'], 0, 3)) . '-' . str_pad((string)rand(1, 999), 3, '0', STR_PAD_LEFT),
                    'device' => trim($payload['device'] ?? 'Store Tablet'),
                    'facility' => $movement['facility'],
                    'payload' => $movement['medicine'] . ' movement queued locally',
                    'status' => 'Pending',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
        }
    }

    public function syncQueue(): void
    {
        if (self::isDemoMode()) {
            $queue = $this->queue();
            foreach ($queue as &$item) {
                $item['status'] = 'Synced';
            }
            unset($item);
            $this->setSessionCollection('queue_events', $queue);
            return;
        }

        $pdo = self::db();
        $stmt = $pdo->prepare('UPDATE queue_events SET status = :status WHERE status != :status');
        $stmt->execute(['status' => 'Synced']);
    }

    public function batchesForMedicine(int $medicineId): array
    {
        $item = $this->find($medicineId);
        if (!$item) return [];
        return [
            ['batch_no' => 'BAT-' . str_pad((string)$medicineId, 3, '0', STR_PAD_LEFT) . '-A', 'qty' => max(0, (int)floor($item['stock'] * 0.55)), 'expiry' => $item['expiry_date'], 'status' => strtotime($item['expiry_date']) < strtotime('+45 days') ? 'Near Expiry' : 'Active'],
            ['batch_no' => 'BAT-' . str_pad((string)$medicineId, 3, '0', STR_PAD_LEFT) . '-B', 'qty' => max(0, (int)ceil($item['stock'] * 0.45)), 'expiry' => date('Y-m-d', strtotime($item['expiry_date'] . ' + 60 days')), 'status' => 'Active'],
        ];
    }

    public function historyForMedicine(int $medicineId): array
    {
        if (self::isDemoMode()) {
            return array_values(array_filter(array_reverse($this->movements()), fn($m) => (int)($m['medicine_id'] ?? 0) === $medicineId));
        }

        $pdo = self::db();
        $stmt = $pdo->prepare('SELECT * FROM stock_movements WHERE medicine_id = :medicine_id ORDER BY created_at DESC');
        $stmt->execute(['medicine_id' => $medicineId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
