<section class="page-body">
  <div class="section-head mb-3">
    <div>
      <h2>Predictive Forecasting</h2>
      <p class="text-muted mb-0">Scenario planning, stockout windows, and explainable AI for Mozambican facilities.</p>
    </div>
    <div class="d-flex gap-2">
      <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">Explainable AI</span>
      <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Seasonal malaria watch</span>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-lg-8">
      <div class="card alk-card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
              <h5 class="mb-1">Stockout Prediction Queue</h5>
              <p class="text-muted small mb-0">Items most likely to create clinical disruption if no action is taken.</p>
            </div>
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i> Export Brief</button>
          </div>
          <div class="table-responsive">
            <table class="table align-middle inventory-table">
              <thead>
                <tr>
                  <th>Medicine</th>
                  <th>Facility</th>
                  <th>Days to stockout</th>
                  <th>Confidence</th>
                  <th>Recommended reorder</th>
                  <th>Reason</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($forecast as $row): ?>
                <tr>
                  <td>
                    <div class="fw-semibold"><?= htmlspecialchars($row['medicine']) ?></div>
                    <div class="small text-muted"><?= htmlspecialchars($row['category'] ?? '') ?></div>
                  </td>
                  <td>
                    <div><?= htmlspecialchars($row['facility'] ?? '') ?></div>
                    <div class="small text-muted"><?= htmlspecialchars($row['department'] ?? '') ?></div>
                  </td>
                  <td><span class="pill-status <?= (int)$row['days_to_stockout'] <= 7 ? 'out-of-stock' : 'low-stock' ?>"><?= (int)$row['days_to_stockout'] ?> days</span></td>
                  <td>
                    <div class="small fw-semibold"><?= (int)$row['confidence'] ?>%</div>
                    <div class="progress mt-1" style="height:8px"><div class="progress-bar bg-success" style="width: <?= (int)$row['confidence'] ?>%"></div></div>
                  </td>
                  <td>
                    <div class="fw-semibold"><?= (int)$row['recommended_qty'] ?> units</div>
                    <div class="small text-muted">Lead time <?= (int)($row['lead_time_days'] ?? 0) ?> days</div>
                  </td>
                  <td class="small text-muted"><?= htmlspecialchars($row['reason'] ?? '') ?></td>
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
          <h5>Scenario Planner</h5>
          <p class="small text-muted">Compare safety buffers against spend and projected shortages.</p>
          <div class="list-group list-group-flush">
            <?php foreach ($scenario as $s): ?>
            <div class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent border-secondary-subtle">
              <div>
                <div class="fw-semibold"><?= htmlspecialchars($s['label']) ?></div>
                <div class="small text-muted">Buffer x<?= number_format($s['buffer'], 2) ?></div>
              </div>
              <div class="text-end">
                <div class="fw-semibold">MZN <?= number_format($s['spend']) ?></div>
                <div class="small text-danger"><?= (int)$s['stockout_count'] ?> stockout risks</div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="card alk-card">
        <div class="card-body">
          <h5>AI Explanation</h5>
          <div class="p-3 rounded-3 bg-light border small text-secondary-emphasis">
            Forecasts are based on baseline consumption, recent spike detection, supplier lead time, disease seasonality, and facility-level stock protection rules. Confidence drops when sync reliability is weak or historical movement data is incomplete.
          </div>
          <div class="mt-3 d-grid gap-2">
            <button class="btn btn-alk"><i class="bi bi-magic"></i> Generate procurement brief</button>
            <button class="btn btn-outline-secondary"><i class="bi bi-arrow-repeat"></i> Re-run scenario</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
