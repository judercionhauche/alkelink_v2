<section class="page-body">
  <div class="section-head mb-3">
    <div>
      <h2>Reports & Analytics</h2>
      <p class="text-muted mb-0">Executive-ready views for stockouts, expiry, procurement, offline reliability, and forecast confidence.</p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary">Export PDF</button>
      <button class="btn btn-outline-secondary">Export Excel</button>
    </div>
  </div>
  <div class="row g-3 mb-4">
    <?php foreach ($reports as $report): ?>
      <div class="col-md-6 col-xl-4">
        <div class="card alk-card h-100">
          <div class="card-body">
            <h5><?= htmlspecialchars($report['title']) ?></h5>
            <p class="text-muted mb-3"><?= htmlspecialchars($report['summary']) ?></p>
            <button class="btn btn-alk-soft">Open Report</button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="card alk-card">
    <div class="card-body">
      <h5 class="mb-3">Recommended Executive Pack</h5>
      <div class="row g-3">
        <div class="col-md-4"><div class="mini-stat h-100"><span>Board slide 01</span><strong>Stock resilience index</strong><div class="text-muted small mt-2">Pair health score with emergency shield and sync reliability.</div></div></div>
        <div class="col-md-4"><div class="mini-stat h-100"><span>Board slide 02</span><strong>Critical medicine watchlist</strong><div class="text-muted small mt-2">Show Ceftriaxone, Oxytocin, Magnesium Sulfate, Ketamine.</div></div></div>
        <div class="col-md-4"><div class="mini-stat h-100"><span>Board slide 03</span><strong>Procurement opportunity</strong><div class="text-muted small mt-2">Use AI-led order bundling and supplier lead-time discipline.</div></div></div>
      </div>
    </div>
  </div>
</section>
