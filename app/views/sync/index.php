<section class="page-body">
  <div class="section-head mb-3">
    <div>
      <h2>Offline Sync Center</h2>
      <p class="text-muted mb-0">Monitor pending uploads, device health, and local-save confidence across facilities.</p>
    </div>
    <a href="index.php?route=sync-center&action=sync" class="btn btn-alk"><i class="bi bi-cloud-upload"></i> Force sync demo queue</a>
  </div>
  <div class="row g-4 mb-4">
    <div class="col-lg-7">
      <div class="card alk-card h-100"><div class="card-body">
        <h5 class="mb-3">Pending queue events</h5>
        <div class="table-responsive"><table class="table inventory-table align-middle">
          <thead><tr><th>Reference</th><th>Type</th><th>Device</th><th>Facility</th><th>Status</th><th>Created</th></tr></thead>
          <tbody><?php foreach ($queue as $q): ?><tr><td><strong><?= htmlspecialchars($q['reference']) ?></strong><div class="small text-muted"><?= htmlspecialchars($q['payload']) ?></div></td><td><?= htmlspecialchars($q['type']) ?></td><td><?= htmlspecialchars($q['device']) ?></td><td><?= htmlspecialchars($q['facility']) ?></td><td><span class="pill-status <?= strtolower($q['status']) === 'synced' ? 'in-stock' : 'low-stock' ?>"><?= htmlspecialchars($q['status']) ?></span></td><td><?= htmlspecialchars($q['created_at']) ?></td></tr><?php endforeach; ?></tbody>
        </table></div>
      </div></div>
    </div>
    <div class="col-lg-5">
      <div class="card alk-card h-100"><div class="card-body">
        <h5 class="mb-3">Device reliability</h5>
        <?php foreach ($devices as $d): ?>
          <div class="sync-row d-flex justify-content-between gap-3 mb-3">
            <div><div class="fw-semibold"><?= htmlspecialchars($d['device']) ?></div><div class="small text-muted"><?= htmlspecialchars($d['facility']) ?> · Last sync <?= htmlspecialchars($d['last_sync']) ?></div></div>
            <div class="text-end"><div class="fw-bold <?= $d['pending'] > 0 ? 'text-warning' : 'text-alk' ?>"><?= (int)$d['pending'] ?> pending</div><div class="small text-muted"><?= htmlspecialchars($d['status']) ?></div></div>
          </div>
        <?php endforeach; ?>
      </div></div>
    </div>
  </div>
  <div class="row g-4">
    <?php foreach ($quality as $q): ?><div class="col-md-6 col-xl-3"><div class="mini-stat h-100"><span><?= htmlspecialchars($q['label']) ?></span><strong><?= htmlspecialchars($q['value']) ?></strong><div class="text-muted small mt-2"><?= htmlspecialchars($q['note']) ?></div></div></div><?php endforeach; ?>
  </div>
</section>