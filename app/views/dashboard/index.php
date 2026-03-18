<?php
$today = date('l, d F Y');
$statusLabels = json_encode(array_keys($data['statusCounts']));
$statusValues = json_encode(array_values($data['statusCounts']));
$categoryLabels = json_encode(array_keys($data['categoryCounts']));
$categoryValues = json_encode(array_values($data['categoryCounts']));
$trendLabels = json_encode(array_keys($data['monthlyTrend']));
$trendValues = json_encode(array_values($data['monthlyTrend']));
$deptLabels = json_encode(array_keys($data['usageByDepartment']));
$deptValues = json_encode(array_values($data['usageByDepartment']));
$briefing = $data['insights'][0] ?? null;
$recentAlerts = array_slice($data['alerts'], 0, 5);
?>
<section class="page-body compact-page">
  <div class="hero-banner compact-hero">
    <div>
      <div class="hero-date"><?= htmlspecialchars($today) ?></div>
      <h1><?= htmlspecialchars(t('layout.hospital')) ?></h1>
      <p><?= htmlspecialchars(t('dashboard.welcome', ['%s' => $_SESSION['user']['name']])) ?></p>
    </div>
    <div class="hero-score">
      <span>Health Score</span>
      <strong><?= (int) $data['healthScore'] ?>%</strong>
      <small>Emergency Shield <?= (int)$data['emergencyShield'] ?>%</small>
    </div>
  </div>

  <?php if ($briefing): ?>
  <div class="insight-banner compact-insight">
    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
      <div>
        <div class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle"><?= htmlspecialchars(t('dashboard.section.ai_daily')) ?></div>
        <h5><?= htmlspecialchars($briefing['title']) ?></h5>
      </div>
      <div class="small text-muted">Confidence <?= (int)$briefing['confidence'] ?>%</div>
    </div>
    <p class="mb-0"><?= htmlspecialchars($briefing['text']) ?></p>
  </div>
  <?php endif; ?>

  <div class="row g-3 mb-3">
    <?php
    $cards = [
      [t('dashboard.card.total_medicines'), $data['total'], 'bi-box-seam', 'text-alk', t('dashboard.card.live_catalog')],
      [t('dashboard.card.low_stock'), $data['lowStock'], 'bi-graph-down-arrow', 'text-warning', t('dashboard.card.need_attention')],
      [t('dashboard.card.out_of_stock'), $data['outOfStock'], 'bi-exclamation-triangle', 'text-danger', t('dashboard.card.urgent_reorder')],
      [t('dashboard.card.near_expiry'), $data['nearExpiry'], 'bi-clock-history', 'text-purple', t('dashboard.card.redistribute')],
      [t('dashboard.card.critical_alerts'), $data['criticalAlerts'], 'bi-shield-exclamation', 'text-danger', t('dashboard.card.unresolved')],
      [t('dashboard.card.procurement_priority'), $data['priorityScore'] . '%', 'bi-lightning-charge', 'text-alk', t('dashboard.card.ai_weighted')],
    ];
    foreach ($cards as [$label,$value,$icon,$class,$sub]): ?>
      <div class="col-6 col-xl-2">
        <div class="metric-card compact-card">
          <div class="metric-copy">
            <small><?= htmlspecialchars($label) ?></small>
            <h3 class="<?= $class ?>"><?= htmlspecialchars((string)$value) ?></h3>
            <div class="tiny text-muted mt-1"><?= htmlspecialchars($sub) ?></div>
          </div>
          <div class="metric-icon"><i class="bi <?= $icon ?>"></i></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-lg-6">
      <div class="card alk-card"><div class="card-body"><h5><?= htmlspecialchars(t('dashboard.section.stock_status')) ?></h5><div class="chart-box chart-box-sm"><canvas id="statusChart"></canvas></div></div></div>
    </div>
    <div class="col-lg-6">
      <div class="card alk-card"><div class="card-body"><h5><?= htmlspecialchars(t('dashboard.section.category_breakdown')) ?></h5><div class="chart-box chart-box-sm"><canvas id="categoryChart"></canvas></div></div></div>
    </div>
  </div>

  <div class="row g-3 mb-3 dashboard-mid-row">
    <div class="col-xl-4">
      <div class="card alk-card fixed-card">
        <div class="card-body">
          <div class="section-head mb-3"><h2 class="h5 mb-0"><?= htmlspecialchars(t('dashboard.section.operational_risk')) ?></h2><span class="badge rounded-pill bg-light text-dark border"><?= htmlspecialchars(t('dashboard.section.mission_critical')) ?></span></div>
          <div class="mb-3">
            <div class="d-flex justify-content-between small mb-1"><span><?= htmlspecialchars(t('dashboard.label.emergency_stock_shield')) ?></span><strong><?= (int)$data['emergencyShield'] ?>%</strong></div>
            <div class="progress alk-progress"><div class="progress-bar bg-success" style="width: <?= (int)$data['emergencyShield'] ?>%"></div></div>
          </div>
          <div class="mb-3">
            <div class="d-flex justify-content-between small mb-1"><span><?= htmlspecialchars(t('dashboard.label.wastage_risk')) ?></span><strong><?= (int)$data['wastageRisk'] ?>%</strong></div>
            <div class="progress alk-progress"><div class="progress-bar bg-warning" style="width: <?= (int)$data['wastageRisk'] ?>%"></div></div>
          </div>
          <div class="mb-3">
            <div class="d-flex justify-content-between small mb-1"><span><?= htmlspecialchars(t('dashboard.label.procurement_priority')) ?></span><strong><?= (int)$data['priorityScore'] ?>%</strong></div>
            <div class="progress alk-progress"><div class="progress-bar" style="width: <?= (int)$data['priorityScore'] ?>%"></div></div>
          </div>
          <div class="stack-panel mt-3 internal-scroll-panel">
            <div class="small text-uppercase text-muted mb-2 fw-semibold">AI says act now on</div>
            <ul class="mb-0 ps-3">
              <?php foreach (array_slice($data['forecast'],0,6) as $row): ?>
              <li class="mb-2"><strong><?= htmlspecialchars($row['medicine']) ?></strong> · <?= (int)$row['days_to_stockout'] ?> days to threshold · reorder <?= (int)$row['recommended_qty'] ?> units</li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="card alk-card fixed-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3"><h5><?= htmlspecialchars(t('dashboard.section.ai_insights')) ?></h5><span class="badge bg-success-subtle text-success">Live</span></div>
          <div class="internal-scroll-panel">
          <?php foreach (array_slice($data['insights'],0,8) as $insight): ?>
            <div class="feed-item">
              <div class="d-flex justify-content-between gap-2">
                <strong><?= htmlspecialchars($insight['title']) ?></strong>
                <span class="small text-muted"><?= htmlspecialchars(date('d M', strtotime($insight['generated_at']))) ?></span>
              </div>
              <div class="text-muted small mb-2"><?= htmlspecialchars($insight['text']) ?></div>
              <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="pill-severity <?= strtolower(str_replace(' ','-',$insight['severity'])) ?>"><?= htmlspecialchars($insight['severity']) ?></span>
                <span class="small">Confidence <?= (int) $insight['confidence'] ?>%</span>
              </div>
            </div>
          <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="card alk-card fixed-card">
        <div class="card-body">
            <h5 class="mb-3"><?= htmlspecialchars(t('dashboard.section.recent_alerts')) ?></h5>
          <div class="internal-scroll-panel">
          <?php foreach ($recentAlerts as $alert): ?>
            <div class="feed-item border-start border-4 <?= strtolower($alert['severity']) === 'critical' ? 'border-danger' : (strtolower($alert['severity']) === 'high' ? 'border-warning' : 'border-info') ?>">
              <div class="fw-semibold"><?= htmlspecialchars($alert['title']) ?></div>
              <div class="small text-muted"><?= htmlspecialchars($alert['type']) ?> · <?= htmlspecialchars(date('d M Y, H:i', strtotime($alert['created_at']))) ?></div>
            </div>
          <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-lg-7">
      <div class="card alk-card"><div class="card-body"><h5><?= htmlspecialchars(t('dashboard.section.hospital_health_trend')) ?></h5><div class="chart-box chart-box-sm"><canvas id="trendChart"></canvas></div></div></div>
    </div>
    <div class="col-lg-5">
      <div class="card alk-card"><div class="card-body"><h5><?= htmlspecialchars(t('dashboard.section.department_usage')) ?></h5><div class="chart-box chart-box-sm"><canvas id="departmentChart"></canvas></div></div></div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-lg-6">
      <div class="card alk-card fixed-card-sm">
        <div class="card-body">
          <div class="section-head mb-3"><h2 class="h5 mb-0"><?= htmlspecialchars(t('dashboard.section.facility_visibility')) ?></h2><a href="index.php?route=facilities" class="btn btn-sm btn-alk-soft"><?= htmlspecialchars(t('dashboard.label.open_facilities')) ?></a></div>
          <div class="internal-scroll-panel short-scroll">
          <?php foreach ($data['facilities'] as $facility): ?>
            <div class="facility-tile mb-3">
              <div class="d-flex justify-content-between gap-3 align-items-start">
                <div>
                  <div class="fw-bold"><?= htmlspecialchars($facility['name']) ?></div>
                  <div class="small text-muted"><?= htmlspecialchars($facility['type']) ?> · <?= (int)$facility['departments'] ?> departments</div>
                </div>
                <span class="pill-severity <?= strtolower($facility['risk']) === 'critical' ? 'critical' : (strtolower($facility['risk']) === 'high' ? 'high' : (strtolower($facility['risk']) === 'moderate' ? 'medium' : 'low')) ?>"><?= htmlspecialchars($facility['risk']) ?> risk</span>
              </div>
              <div class="d-flex justify-content-between small mt-2"><span>Health score</span><strong><?= (int)$facility['health_score'] ?>%</strong></div>
              <div class="progress alk-progress mt-1"><div class="progress-bar" style="width: <?= (int)$facility['health_score'] ?>%"></div></div>
            </div>
          <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card alk-card fixed-card-sm">
        <div class="card-body">
          <div class="section-head mb-3"><h2 class="h5 mb-0"><?= htmlspecialchars(t('dashboard.section.top_at_risk')) ?></h2><a href="index.php?route=inventory" class="btn btn-sm btn-alk-soft"><?= htmlspecialchars(t('dashboard.label.open_inventory')) ?></a></div>
          <div class="internal-scroll-panel short-scroll">
          <?php foreach (array_slice($data['atRisk'],0,8) as $item): $pct = min(100, $item['reorder_point'] ? round(($item['stock'] / $item['reorder_point']) * 100) : 0); ?>
            <div class="risk-row">
              <div>
                <strong><?= htmlspecialchars($item['name']) ?></strong>
                <div class="small text-muted"><?= htmlspecialchars($item['department']) ?> · <?= htmlspecialchars($item['facility']) ?></div>
              </div>
              <div class="text-end">
                <span class="badge text-bg-light border"><?= htmlspecialchars($item['status']) ?></span>
                <div class="small text-muted"><?= (int)$item['stock'] ?> units</div>
              </div>
            </div>
            <div class="progress alk-progress mb-3"><div class="progress-bar" style="width: <?= $pct ?>%"></div></div>
          <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
window.dashboardData = {
  statusLabels: <?= $statusLabels ?>,
  statusValues: <?= $statusValues ?>,
  categoryLabels: <?= $categoryLabels ?>,
  categoryValues: <?= $categoryValues ?>,
  trendLabels: <?= $trendLabels ?>,
  trendValues: <?= $trendValues ?>,
  deptLabels: <?= $deptLabels ?>,
  deptValues: <?= $deptValues ?>
};
</script>
