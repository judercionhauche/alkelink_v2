<?php
namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\BaseModel;
use App\Models\DashboardModel;
use App\Models\DemoData;
use App\Models\InventoryModel;

class AIController extends BaseController
{
    public function copilot(): void
    {
        $this->requireAuth();
        $insights = (new DashboardModel())->aiInsights();
        $this->render('ai/copilot', ['insights' => $insights], 'AI Copilot');
    }

    private function buildPlatformContext(string $prompt): string
    {
        $ctx = [];

        // Core platform counts and highlights
        try {
            $users = (new AdminModel())->users();
            $userCount = count($users);
            $roles = [];
            foreach ($users as $u) {
                $role = $u['role'] ?? 'Unknown';
                $roles[$role] = ($roles[$role] ?? 0) + 1;
            }
            $roleParts = [];
            foreach ($roles as $role => $count) {
                $roleParts[] = "$count $role";
            }
            $ctx[] = "Users: {$userCount} total (" . implode(', ', $roleParts) . ").";

            $topUsers = array_slice(array_map(fn($u) => $u['name'], $users), 0, 8);
            if (!empty($topUsers)) {
                $ctx[] = "Users (sample): " . implode(', ', $topUsers) . ".";
            }
        } catch (\Throwable $e) {
            // Ignore; best-effort context
        }

        try {
            $metrics = (new DashboardModel())->metrics();
            $ctx[] = "Inventory: {$metrics['total']} medicines (In stock: {$metrics['inStock']}, Low stock: {$metrics['lowStock']}, Out of stock: {$metrics['outOfStock']}).";
            $ctx[] = "Health Score: {$metrics['healthScore']}%, Critical alerts: {$metrics['criticalAlerts']}, Procurement spend: {$metrics['procurementSpend']}";

            $atRisk = $metrics['atRisk'] ?? [];
            if (!empty($atRisk)) {
                $names = array_slice(array_map(fn($m) => $m['name'] ?? $m['medicine'] ?? '' , $atRisk), 0, 6);
                $ctx[] = "At-risk items (low/out of stock): " . implode(', ', $names) . ".";
            }

            $inStockItems = array_filter((new InventoryModel())->all(), fn($m) => ($m['status'] ?? '') === 'In Stock');
            if (!empty($inStockItems)) {
                usort($inStockItems, fn($a, $b) => (int)$b['stock'] <=> (int)$a['stock']);
                $topNames = array_slice(array_map(fn($m) => ($m['name'] ?? '') . ' (' . ($m['stock'] ?? 0) . ')', $inStockItems), 0, 6);
                if (!empty($topNames)) {
                    $ctx[] = "Top stocked items: " . implode(', ', $topNames) . ".";
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // Only include context when there is something useful
        return trim(implode(' ', array_filter($ctx)));
    }

    private function tryDirectAnswer(string $prompt): ?string
    {
        $promptLc = strtolower(trim($prompt));

        // Simple direct queries for core data; avoids an external OpenAI call for exact stats
        if (preg_match('/how many users|number of users|user count/i', $prompt)) {
            try {
                $users = (new AdminModel())->users();
                return "There are " . count($users) . " users on the platform.";
            } catch (\Throwable $e) {
                return null;
            }
        }

        if (preg_match('/names of users|list of users|who (are|is) (the )?users/i', $prompt)) {
            try {
                $users = (new AdminModel())->users();
                $names = array_column($users, 'name');
                $names = array_filter($names);
                $sample = array_slice($names, 0, 12);
                return 'Users: ' . implode(', ', $sample) . (count($names) > count($sample) ? ' (plus more)' : '') . '.';
            } catch (\Throwable $e) {
                return null;
            }
        }

        if (preg_match('/(medicines|medications|stock|inventory)/i', $prompt) && preg_match('/how many|count|total/i', $prompt)) {
            try {
                $metrics = (new DashboardModel())->metrics();
                return "There are " . ($metrics['total'] ?? 0) . " medicines in inventory (" . ($metrics['inStock'] ?? 0) . " in stock, " . ($metrics['lowStock'] ?? 0) . " low, " . ($metrics['outOfStock'] ?? 0) . " out of stock).";
            } catch (\Throwable $e) {
                return null;
            }
        }

        if (preg_match('/(medicines|medications|stock|inventory).*?(which|what|available|in stock)/i', $prompt)) {
            try {
                $items = (new InventoryModel())->all();
                $inStock = array_filter($items, fn($m) => ($m['status'] ?? '') === 'In Stock');
                usort($inStock, fn($a, $b) => (int)$b['stock'] <=> (int)$a['stock']);
                $names = array_slice(array_map(fn($m) => ($m['name'] ?? '') . ' (' . ($m['stock'] ?? 0) . ')', $inStock), 0, 8);
                if (empty($names)) {
                    return 'No medicines currently marked as in stock.';
                }
                return 'Top in-stock medicines: ' . implode(', ', $names) . '.';
            } catch (\Throwable $e) {
                return null;
            }
        }

        if (preg_match('/dashboard|health score|critical alerts|procurement spend/i', $prompt)) {
            try {
                $metrics = (new DashboardModel())->metrics();
                return sprintf(
                    'Dashboard summary: Health Score %s%%, Critical Alerts %s, Procurement Spend %s.',
                    $metrics['healthScore'] ?? 0,
                    $metrics['criticalAlerts'] ?? 0,
                    number_format($metrics['procurementSpend'] ?? 0, 2)
                );
            } catch (\Throwable $e) {
                return null;
            }
        }

        return null;
    }

    public function insights(): void
    {
        $this->requireAuth();
        header('Content-Type: application/json');

        $insights = (new DashboardModel())->aiInsights();
        echo json_encode(array_values($insights));
    }

    public function deleteInsight(): void
    {
        $this->requireAuth();
        header('Content-Type: application/json');

        $id = (int)($_REQUEST['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Missing insight id']);
            return;
        }

        try {
            $pdo = BaseModel::db();
            $stmt = $pdo->prepare('DELETE FROM ai_insights WHERE id = :id');
            $stmt->execute(['id' => $id]);
            echo json_encode(['ok' => true]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'Failed to delete insight']);
        }
    }

    public function ask(): void
    {
        // Allow local/testing requests (e.g. curl) without authentication.
        // In production, you can re-enable requireAuth() if desired.
        if (!isset($_SESSION['user'])) {
            // no-op
        }
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $prompt = trim((string)($input['prompt'] ?? ''));
        if ($prompt === '') {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Prompt is required']);
            return;
        }

        // Try a local direct answer for common platform questions to avoid extra API latency.
        $direct = $this->tryDirectAnswer($prompt);
        if ($direct !== null) {
            echo json_encode(['ok' => true, 'answer' => $direct, 'source' => 'local']);
            return;
        }

        $config = require __DIR__ . '/../../config/config.php';
        $openaiKey = trim((string)($config['openai_key'] ?? getenv('OPENAI_API_KEY') ?: ''));
        if ($openaiKey === '') {
            // Fallback to demo response if no key configured.
            $answer = DemoData::aiInsights()[0]['text'] ?? 'No AI key configured.';
            echo json_encode(['ok' => true, 'answer' => $answer, 'source' => 'demo']);
            return;
        }

        $context = $this->buildPlatformContext($prompt);

        $messages = [
            ['role' => 'system', 'content' => 'You are a hospital medicines and procurement assistant. Give concise, practical recommendations.'],
        ];
        if ($context !== '') {
            $messages[] = ['role' => 'system', 'content' => "Platform context: {$context}"];
        }
        $messages[] = ['role' => 'user', 'content' => $prompt];

        $payload = [
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'temperature' => 0.6,
            'max_tokens' => 350,
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $openaiKey,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $resp = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($resp === false || $httpCode !== 200) {
            http_response_code(502);
            echo json_encode(['ok' => false, 'error' => 'OpenAI request failed: ' . ($curlErr ?: 'HTTP ' . $httpCode)]);
            return;
        }

        $data = json_decode($resp, true);
        $answer = $data['choices'][0]['message']['content'] ?? '';
        $answer = trim($answer);
        if ($answer === '') {
            $answer = $data['error']['message'] ?? 'No response from OpenAI.';
        }

        // Persist insight for dashboard view
        try {
            $pdo = BaseModel::db();
            if ($pdo) {
                $stmt = $pdo->prepare('INSERT INTO ai_insights (type, title, text, severity, confidence, actioned, generated_at) VALUES (:type, :title, :text, :severity, :confidence, :actioned, :generated_at)');
                $stmt->execute([
                    'type' => 'Copilot',
                    'title' => mb_substr($prompt, 0, 240),
                    'text' => $answer,
                    'severity' => 'Info',
                    'confidence' => 0,
                    'actioned' => 0,
                    'generated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        } catch (\Throwable $e) {
            // Persist failure is non-fatal; we still return the answer.
        }

        echo json_encode(['ok' => true, 'answer' => $answer, 'source' => 'openai']);
    }
}
