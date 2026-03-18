<section class="page-body">
  <div class="section-head mb-4">
    <div>
      <h2>Facilities & Department Visibility</h2>
      <p class="text-muted mb-0">Cross-site operational visibility, sync confidence, and risk posture across Mozambique-facing demo facilities.</p>
    </div>
    <span class="badge bg-light text-dark border">Multi-facility demo</span>
  </div>
  <div class="row g-4 mb-4">
    <?php foreach ($facilities as $facility): ?>
    <div class="col-lg-6">
      <div class="card alk-card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start gap-3">
            <div>
              <h5 class="mb-1"><?= htmlspecialchars($facility['name']) ?></h5>
              <div class="text-muted small"><?= htmlspecialchars($facility['type']) ?> · <?= (int)$facility['departments'] ?> departments</div>
            </div>
            <span class="pill-severity <?= strtolower($facility['risk']) === 'critical' ? 'critical' : (strtolower($facility['risk']) === 'high' ? 'high' : (strtolower($facility['risk']) === 'moderate' ? 'medium' : 'low')) ?>"><?= htmlspecialchars($facility['risk']) ?></span>
          </div>
          <div class="row g-3 mt-1">
            <div class="col-4"><div class="mini-stat"><span>Health</span><strong><?= (int)$facility['health_score'] ?>%</strong></div></div>
            <div class="col-4"><div class="mini-stat"><span>Critical alerts</span><strong><?= (int)$facility['critical_alerts'] ?></strong></div></div>
            <div class="col-4"><div class="mini-stat"><span>Sync</span><strong><?= htmlspecialchars($facility['sync']) ?></strong></div></div>
          </div>
          <div class="progress alk-progress mt-3"><div class="progress-bar" style="width: <?= (int)$facility['health_score'] ?>%"></div></div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card alk-card h-100"><div class="card-body"><h5>Sync Reliability Queue</h5>
        <?php foreach ($syncQueue as $row): ?>
          <div class="sync-row d-flex justify-content-between align-items-center gap-3 mb-3">
            <div><div class="fw-semibold"><?= htmlspecialchars($row['device']) ?></div><div class="small text-muted"><?= htmlspecialchars($row['facility']) ?></div></div>
            <div class="text-end"><div class="fw-bold"><?= (int)$row['pending'] ?> pending</div><div class="small text-muted"><?= htmlspecialchars($row['status']) ?></div></div>
          </div>
        <?php endforeach; ?>
      </div></div>
    </div>
    <div class="col-lg-6">
      <div class="card alk-card h-100"><div class="card-body"><h5>Forecast Watchlist</h5>
        <?php foreach ($forecast as $f): ?>
          <div class="feed-item mb-2">
            <div class="d-flex justify-content-between"><strong><?= htmlspecialchars($f['medicine']) ?></strong><span><?= (int)$f['confidence'] ?>%</span></div>
            <div class="small text-muted"><?= (int)$f['days_to_stockout'] ?> days to threshold · recommended qty <?= (int)$f['recommended_qty'] ?></div>
          </div>
        <?php endforeach; ?>
      </div></div>
    </div>
  </div>
</section>
