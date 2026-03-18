<section class="page-body">
  <div class="section-head mb-3">
    <div>
      <h2>Mobile Quick Actions</h2>
      <p class="text-muted mb-0">Built for ward pharmacists, central store teams, and low-connectivity operations.</p>
    </div>
    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">Field-friendly workflow</span>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3"><a class="metric-card text-decoration-none d-block" href="index.php?route=scanner"><div class="metric-copy"><small>Scan & Receive</small><h3 class="text-alk">Camera</h3><div class="tiny text-muted">Barcode / QR</div></div><div class="metric-icon"><i class="bi bi-upc-scan"></i></div></a></div>
        <div class="col-sm-6 col-xl-3"><div class="metric-card"><div class="metric-copy"><small>Issue Stock</small><h3 class="text-warning">Fast</h3><div class="tiny text-muted">1 tap ward issue</div></div><div class="metric-icon"><i class="bi bi-box-arrow-right"></i></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="metric-card"><div class="metric-copy"><small>Transfers</small><h3 class="text-primary">Live</h3><div class="tiny text-muted">Between wards</div></div><div class="metric-icon"><i class="bi bi-arrow-left-right"></i></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="metric-card"><div class="metric-copy"><small>Offline Queue</small><h3 class="text-danger"><?= count($queue) ?></h3><div class="tiny text-muted">Pending sync events</div></div><div class="metric-icon"><i class="bi bi-cloud-arrow-up"></i></div></div></div>
      </div>

      <div class="card alk-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
              <h5 class="mb-1">Offline Sync Queue</h5>
              <p class="small text-muted mb-0">Local actions waiting for connection recovery.</p>
            </div>
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-repeat"></i> Retry all</button>
          </div>
          <div class="table-responsive">
            <table class="table align-middle inventory-table">
              <thead><tr><th>Reference</th><th>Type</th><th>Facility</th><th>Device</th><th>Status</th><th>Payload</th></tr></thead>
              <tbody>
              <?php foreach ($queue as $item): ?>
                <tr>
                  <td class="fw-semibold"><?= htmlspecialchars($item['reference']) ?></td>
                  <td><?= htmlspecialchars($item['type']) ?></td>
                  <td><?= htmlspecialchars($item['facility']) ?></td>
                  <td><?= htmlspecialchars($item['device']) ?></td>
                  <td><span class="pill-status <?= strtolower($item['status']) === 'pending' ? 'low-stock' : 'in-stock' ?>"><?= htmlspecialchars($item['status']) ?></span></td>
                  <td class="small text-muted"><?= htmlspecialchars($item['payload']) ?></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card alk-card mb-4">
        <div class="card-body">
          <h5>Critical Alerts for Mobile Teams</h5>
          <div class="d-grid gap-2">
            <?php foreach ($alerts as $alert): ?>
            <div class="p-3 rounded-3 border bg-light">
              <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                  <div class="fw-semibold small"><?= htmlspecialchars($alert['title']) ?></div>
                  <div class="tiny text-muted"><?= htmlspecialchars($alert['facility']) ?> · <?= htmlspecialchars($alert['department']) ?></div>
                </div>
                <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle"><?= htmlspecialchars($alert['severity']) ?></span>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="card alk-card">
        <div class="card-body">
          <h5>Design Goals</h5>
          <ul class="small text-muted mb-0 ps-3">
            <li>Readable on low-cost Android devices</li>
            <li>Fast actions for ward stock updates</li>
            <li>Visible reassurance when offline</li>
            <li>Simple sync conflict handling</li>
            <li>Ready for English / Portuguese expansion</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
