<section class="page-body">
  <div class="section-head mb-3">
    <div>
      <h2>Stock Operations Workspace</h2>
      <p class="text-muted mb-0">Receive deliveries, dispense medicines, and move stock between departments with offline-safe capture.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <a href="index.php?route=scanner" class="btn btn-outline-secondary"><i class="bi bi-upc-scan"></i> Open Scan Station</a>
      <a href="index.php?route=sync-center" class="btn btn-alk"><i class="bi bi-cloud-check"></i> Sync Center</a>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-lg-5">
      <div class="card alk-card h-100">
        <div class="card-body">
          <h5 class="mb-3">Create inventory transaction</h5>
          <form method="post" class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Workflow</label>
              <select name="type" class="form-select">
                <option>Receipt</option>
                <option>Dispensing</option>
                <option>Transfer Out</option>
                <option>Transfer In</option>
                <option>Adjustment</option>
                <option>Write-off</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Medicine</label>
              <select name="medicine_id" class="form-select">
                <?php foreach ($items as $item): ?><option value="<?= (int)$item['id'] ?>"><?= htmlspecialchars($item['name']) ?> · <?= htmlspecialchars($item['facility']) ?></option><?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Quantity</label>
              <input class="form-control" type="number" min="1" name="quantity" value="10">
            </div>
            <div class="col-md-4">
              <label class="form-label">Facility</label>
              <select name="facility" class="form-select"><?php foreach ($facilities as $f): ?><option><?= htmlspecialchars($f['name']) ?></option><?php endforeach; ?></select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Department</label>
              <select name="department" class="form-select"><?php foreach ($departments as $d): ?><option><?= htmlspecialchars($d) ?></option><?php endforeach; ?></select>
            </div>
            <div class="col-md-8">
              <label class="form-label">Notes</label>
              <input class="form-control" name="notes" placeholder="e.g. emergency issue, batch received, ward transfer">
            </div>
            <div class="col-md-4">
              <label class="form-label">Device</label>
              <input class="form-control" name="device" value="Store Tablet 01">
            </div>
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" name="offline" id="offlineSave">
                <label class="form-check-label" for="offlineSave">Queue locally if internet drops</label>
              </div>
              <button class="btn btn-alk"><i class="bi bi-save"></i> Save operation</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-7">
      <div class="card alk-card h-100">
        <div class="card-body">
          <div class="section-head mb-3">
            <h5 class="mb-0">Recent stock movements</h5>
            <span class="badge bg-light text-dark border">Live ledger</span>
          </div>
          <div class="table-responsive">
            <table class="table inventory-table align-middle">
              <thead><tr><th>Time</th><th>Medicine</th><th>Type</th><th>Qty</th><th>Facility</th><th>Performed by</th></tr></thead>
              <tbody>
                <?php foreach ($movements as $m): ?>
                  <tr>
                    <td><?= htmlspecialchars($m['created_at']) ?></td>
                    <td><strong><?= htmlspecialchars($m['medicine']) ?></strong><div class="small text-muted"><?= htmlspecialchars($m['department']) ?></div></td>
                    <td><span class="pill-status <?= in_array($m['type'], ['Receipt','Transfer In'], true) ? 'in-stock' : 'low-stock' ?>"><?= htmlspecialchars($m['type']) ?></span></td>
                    <td><?= (int)$m['quantity'] ?></td>
                    <td><?= htmlspecialchars($m['facility']) ?></td>
                    <td><?= htmlspecialchars($m['performed_by'] ?? '-') ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-md-4"><div class="mini-stat"><span>Receiving lane</span><strong>Barcode-first intake</strong><div class="text-muted small mt-2">Use camera or handheld scanner to register delivery before shelving.</div></div></div>
    <div class="col-md-4"><div class="mini-stat"><span>Dispensing lane</span><strong>Low-friction ward issues</strong><div class="text-muted small mt-2">Capture department, quantity, and reason in one quick workflow.</div></div></div>
    <div class="col-md-4"><div class="mini-stat"><span>Transfer lane</span><strong>Cross-unit rebalancing</strong><div class="text-muted small mt-2">Move near-expiry or at-risk stock to facilities with immediate demand.</div></div></div>
  </div>
</section>