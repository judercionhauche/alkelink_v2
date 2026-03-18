<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

// Internationalization / locale management
require_once __DIR__ . '/../app/i18n.php';

// Allow switching language via ?lang=xx (stored in session).
if (!empty($_GET['lang'])) {
    set_locale($_GET['lang']);
    $params = $_GET;
    unset($params['lang']);
    $qs = http_build_query($params);
    header('Location: index.php' . ($qs ? "?{$qs}" : ''));
    exit;
}

require_once __DIR__ . '/../app/models/BaseModel.php';
require_once __DIR__ . '/../app/models/DemoData.php';
require_once __DIR__ . '/../app/models/AuthModel.php';
require_once __DIR__ . '/../app/models/DashboardModel.php';
require_once __DIR__ . '/../app/models/InventoryModel.php';
require_once __DIR__ . '/../app/models/AlertsModel.php';
require_once __DIR__ . '/../app/models/ProcurementModel.php';
require_once __DIR__ . '/../app/models/AdminModel.php';
require_once __DIR__ . '/../app/models/FacilitiesModel.php';
require_once __DIR__ . '/../app/models/AuditModel.php';
require_once __DIR__ . '/../app/models/SettingsModel.php';
require_once __DIR__ . '/../app/models/ForecastModel.php';
require_once __DIR__ . '/../app/controllers/BaseController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/InventoryController.php';
require_once __DIR__ . '/../app/controllers/AlertsController.php';
require_once __DIR__ . '/../app/controllers/ProcurementController.php';
require_once __DIR__ . '/../app/controllers/AIController.php';
require_once __DIR__ . '/../app/controllers/ScannerController.php';
require_once __DIR__ . '/../app/controllers/ReportsController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/FacilitiesController.php';
require_once __DIR__ . '/../app/controllers/AuditController.php';
require_once __DIR__ . '/../app/controllers/SettingsController.php';
require_once __DIR__ . '/../app/controllers/ForecastController.php';
require_once __DIR__ . '/../app/controllers/MobileController.php';
require_once __DIR__ . '/../app/controllers/OperationsController.php';
require_once __DIR__ . '/../app/controllers/SyncController.php';
require_once __DIR__ . '/../app/controllers/UsersController.php';

$route = $_GET['route'] ?? 'dashboard';
$map = [
    'login' => [App\Controllers\AuthController::class, 'login'],
    'logout' => [App\Controllers\AuthController::class, 'logout'],
    'dashboard' => [App\Controllers\DashboardController::class, 'index'],
    'inventory' => [App\Controllers\InventoryController::class, 'index'],
    'operations' => [App\Controllers\OperationsController::class, 'index'],
    'alerts' => [App\Controllers\AlertsController::class, 'index'],
    'procurement' => [App\Controllers\ProcurementController::class, 'index'],
    'ai-copilot' => [App\Controllers\AIController::class, 'copilot'],
    'ai-ask' => [App\Controllers\AIController::class, 'ask'],
    'ai-insights' => [App\Controllers\AIController::class, 'insights'],
    'ai-insights-delete' => [App\Controllers\AIController::class, 'deleteInsight'],
    'scanner' => [App\Controllers\ScannerController::class, 'index'],
    'sync-center' => [App\Controllers\SyncController::class, 'index'],
    'reports' => [App\Controllers\ReportsController::class, 'index'],
    'forecast' => [App\Controllers\ForecastController::class, 'index'],
    'mobile' => [App\Controllers\MobileController::class, 'index'],
    'roles' => [App\Controllers\AdminController::class, 'roles'],
    'users' => [App\Controllers\UsersController::class, 'index'],
    'facilities' => [App\Controllers\FacilitiesController::class, 'index'],
    'audit' => [App\Controllers\AuditController::class, 'index'],
    'settings' => [App\Controllers\SettingsController::class, 'index'],
];

if (!isset($map[$route])) {
    http_response_code(404);
    echo 'Route not found';
    exit;
}
[$controllerClass, $method] = $map[$route];
$controller = new $controllerClass();
$controller->$method();
