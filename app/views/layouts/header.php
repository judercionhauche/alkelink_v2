<?php
$user = $_SESSION['user'] ?? null;
$route = $_GET['route'] ?? 'dashboard';
$role = $user['role'] ?? '';
$lang = get_locale();

$alertsModel = new \App\Models\AlertsModel();
$unresolvedAlertCount = count(array_filter($alertsModel->all(), fn($a) => !in_array($a['status'], ['Resolved','Dismissed'], true)));

$nav = [
    'dashboard' => ['label' => t('nav.dashboard'), 'icon' => 'bi-grid'],
    'inventory' => ['label' => t('nav.inventory'), 'icon' => 'bi-box-seam'],
    'operations' => ['label' => t('nav.operations'), 'icon' => 'bi-arrow-left-right'],
    'alerts' => ['label' => t('nav.alerts'), 'icon' => 'bi-bell', 'count' => $unresolvedAlertCount],
    'procurement' => ['label' => t('nav.procurement'), 'icon' => 'bi-cart3'],
    'forecast' => ['label' => t('nav.forecast'), 'icon' => 'bi-activity'],
    'ai-copilot' => ['label' => t('nav.ai_copilot'), 'icon' => 'bi-cpu'],
    'scanner' => ['label' => t('nav.scanner'), 'icon' => 'bi-upc-scan'],
    'sync-center' => ['label' => t('nav.sync_center'), 'icon' => 'bi-cloud-arrow-up'],
    'facilities' => ['label' => t('nav.facilities'), 'icon' => 'bi-hospital'],
    'reports' => ['label' => t('nav.reports'), 'icon' => 'bi-graph-up-arrow'],
    'mobile' => ['label' => t('nav.mobile'), 'icon' => 'bi-phone'],
];
if (in_array($role, ['Super Admin','Hospital Administrator','Auditor'], true)) {
    $nav['audit'] = ['label' => t('nav.audit'), 'icon' => 'bi-journal-text'];
}
if (in_array($role, ['Super Admin','Hospital Administrator'], true)) {
    $nav['roles'] = ['label' => t('nav.roles'), 'icon' => 'bi-shield-lock'];
    $nav['users'] = ['label' => t('nav.users'), 'icon' => 'bi-people'];
    $nav['settings'] = ['label' => t('nav.settings'), 'icon' => 'bi-gear'];
}
?>
<!doctype html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle) ?> · <?= htmlspecialchars(t('app.name')) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="public/assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php if ($user): ?>
<div class="app-shell">
  <aside class="sidebar-panel">
    <div class="brand-wrap">
      <img src="https://static.wixstatic.com/media/ca7ee7_f89f82431563445e97f92b1e3f373805~mv2.png/v1/crop/x_0,y_166,w_1920,h_714/fill/w_358,h_130,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/ca7ee7_f89f82431563445e97f92b1e3f373805~mv2.png" alt="AlkeLink">
      <div class="hospital-meta"><i class="bi bi-hospital"></i> <?= htmlspecialchars(t('layout.hospital')) ?></div>
      <div class="mission-tag mt-3"><?= htmlspecialchars(t('layout.mission')) ?></div>
    </div>
    <nav class="nav flex-column gap-2 mt-4 side-scroll pe-1">
      <?php foreach ($nav as $key => $item): ?>
        <a class="nav-link alk-nav-link <?= $route === $key ? 'active' : '' ?>" href="index.php?route=<?= $key ?>">
          <i class="bi <?= $item['icon'] ?>"></i>
          <span><?= htmlspecialchars($item['label']) ?></span>
          <?php if (isset($item['count'])): ?>
            <?php $count = (int)$item['count']; ?>
            <span id="alertCountBadge" class="badge rounded-pill <?= $count > 0 ? 'bg-danger text-white' : 'bg-secondary text-white-75' ?> ms-2" style="font-size:0.75rem; line-height:1; padding:0.25rem 0.45rem;"><?= $count ?></span>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
    </nav>
    <div class="sidebar-footer mt-auto">
      <div class="sync-box mb-3">
        <div class="d-flex align-items-center justify-content-between gap-2">
          <div class="d-flex align-items-center gap-2"><i class="bi bi-wifi text-success"></i> <strong><?= htmlspecialchars(t('layout.online')) ?></strong></div>
          <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle"><?= htmlspecialchars(t('layout.offline_safe')) ?></span>
        </div>
        <small class="text-secondary-emphasis d-block mt-1"><i class="bi bi-arrow-repeat"></i> <?= htmlspecialchars(t('layout.synced', ['%time%' => '11:05', '%reliability%' => '97.8%'])) ?></small>
      </div>
      <div class="user-box d-flex align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
          <div class="user-avatar"><?= strtoupper(substr($user['name'],0,1)) ?></div>
          <div>
            <div class="fw-semibold text-white small"><?= htmlspecialchars($user['name']) ?></div>
            <div class="text-white-50 tiny"><?= htmlspecialchars($user['email']) ?></div>
          </div>
        </div>
        <a href="index.php?route=logout" class="text-white-50"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>
  <main class="content-panel">
    <div class="topbar">
      <span class="page-pill"><i class="bi bi-layout-sidebar"></i> <?= htmlspecialchars($nav[$route]['label'] ?? 'AlkeLink') ?></span>
      <div class="topbar-actions d-flex gap-2 align-items-center flex-wrap justify-content-end">
        <span class="badge text-bg-light border"><?= htmlspecialchars(t('layout.role')) ?>: <?= htmlspecialchars($user['role']) ?></span>
        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle"><?= htmlspecialchars(t('layout.demo_notice')) ?></span>
        <?php
          $langOptions = get_supported_locales();
          $currentParams = $_GET;
          unset($currentParams['lang']);
        ?>
        <div class="dropdown">
          <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?= htmlspecialchars($langOptions[$lang] ?? strtoupper($lang)) ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <?php foreach ($langOptions as $code => $label): ?>
              <li><a class="dropdown-item<?= $code === $lang ? ' active' : '' ?>" href="index.php?<?= htmlspecialchars(http_build_query(array_merge($currentParams, ['lang' => $code]))) ?>"><?= htmlspecialchars($label) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
    <?php if (!empty($_SESSION['flash_success'])): ?><div class="px-4 pt-3"><div class="alert alert-success mb-0"><?= htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div></div><?php endif; ?>
    <?php if (!empty($_SESSION['db_error'])): ?><div class="px-4 pt-3"><div class="alert alert-warning mb-0"><strong>Database connection warning:</strong> <?= htmlspecialchars($_SESSION['db_error']); unset($_SESSION['db_error']); ?></div></div><?php endif; ?>
<?php endif; ?>
