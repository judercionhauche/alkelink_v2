<?php
namespace App\Models;

use PDO;
use PDOException;

class BaseModel
{
    protected static ?PDO $pdo = null;
    protected static bool $demoMode = false;

    public static function db(): ?PDO
    {
        if (self::$pdo instanceof PDO || self::$demoMode) {
            return self::$pdo;
        }

        $config = require __DIR__ . '/../../config/config.php';
        $db = $config['db'];

        try {
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $db['host'], $db['port'], $db['name'], $db['charset']);
            self::$pdo = new PDO($dsn, $db['user'], $db['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return self::$pdo;
        } catch (PDOException $e) {
            // Fall back to demo mode if DB is not available.
            self::$demoMode = true;
            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION['db_error'] = $e->getMessage();
            }
            return null;
        }
    }

    public static function isDemoMode(): bool
    {
        self::db();
        return self::$demoMode;
    }

    protected function sessionCollection(string $key, callable $seed): array
    {
        if (!isset($_SESSION['demo_store'])) {
            $_SESSION['demo_store'] = [];
        }
        if (!array_key_exists($key, $_SESSION['demo_store'])) {
            $_SESSION['demo_store'][$key] = $seed();
        }
        return $_SESSION['demo_store'][$key];
    }

    protected function setSessionCollection(string $key, array $data): void
    {
        if (!isset($_SESSION['demo_store'])) {
            $_SESSION['demo_store'] = [];
        }
        $_SESSION['demo_store'][$key] = array_values($data);
    }

    protected function nextId(array $rows): int
    {
        $max = 0;
        foreach ($rows as $row) {
            $id = (int)($row['id'] ?? 0);
            if ($id > $max) {
                $max = $id;
            }
        }
        return $max + 1;
    }
}
