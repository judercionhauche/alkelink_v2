<?php
$categories = ['All'];
$statuses = ['All'];
foreach ($allItems as $it) { $categories[$it['category']] = $it['category']; $statuses[$it['status']] = $it['status']; }
?>
<section class="page-body compact-page">
  <div class="section-head mb-3">
    <div>
      <h2>Medicine Inventory</h2>
      <p class="text-muted mb-0"><?= count($items) ?> of <?= count($allItems) ?> medicines</p>
    </div>
    <button class="btn btn-alk" data-bs-toggle="modal" data-bs-target="#addMedicineModal"><i class="bi bi-plus-lg"></i> Add Medicine</button>
  </div>

  <form class="row g-3 mb-3" method="get" action="index.php">
    <input type="hidden" name="route" value="inventory">
    <div class="col-lg-5"><input class="form-control" name="search" placeholder="Search medicines..." value="<?= htmlspecialchars($search) ?>"></div>
    <div class="col-lg-2"><select class="form-select" name="category"><?php foreach ($categories as $opt): ?><option <?= $category===$opt?'selected':''; ?>><?= htmlspecialchars($opt) ?></option><?php endforeach; ?></select></div>
    <div class="col-lg-2"><select class="form-select" name="status"><?php foreach ($statuses as $opt): ?><option <?= $status===$opt?'selected':''; ?>><?= htmlspecialchars($opt) ?></option><?php endforeach; ?></select></div>
    <div class="col-lg-2"><select class="form-select" name="essential"><?php foreach (['All Medicines','Essential Only','Non-Essential'] as $opt): ?><option <?= $essential===$opt?'selected':''; ?>><?= htmlspecialchars($opt) ?></option><?php endforeach; ?></select></div>
    <div class="col-lg-1"><button class="btn btn-alk-soft w-100">Go</button></div>
  </form>

  <div class="card alk-card compact-table-card">
    <div class="card-body p-0">
      <div class="table-responsive inventory-table-wrap">
        <table class="table inventory-table align-middle mb-0">
          <thead><tr><th>Medicine</th><th>Category</th><th>Form / Strength</th><th>Stock</th><th>Reorder Pt</th><th>Days Left</th><th>Status</th><th>Unit Cost</th></tr></thead>
          <tbody>
          <?php foreach ($items as $item):
            $days = $item['avg_monthly_usage'] > 0 ? floor(($item['stock'] / $item['avg_monthly_usage']) * 30) : null;
            $pct = min(100, $item['reorder_point'] ? round(($item['stock'] / $item['reorder_point']) * 100) : 0);
          ?>
            <tr>
              <td><a class="inventory-row-link" href="index.php?route=inventory&detail=<?= (int)$item['id'] ?>"><strong><?= htmlspecialchars($item['name']) ?></strong><div class="small text-muted"><?= htmlspecialchars($item['brand_name']) ?></div></a></td>
              <td><span class="pill-status in-stock"><?= htmlspecialchars($item['category']) ?></span></td>
              <td><?= htmlspecialchars($item['dosage_form']) ?> · <?= htmlspecialchars($item['strength']) ?></td>
              <td><?= (int)$item['stock'] ?></td>
              <td><?= (int)$item['reorder_point'] ?></td>
              <td><div class="days-inline"><div class="progress alk-progress"><div class="progress-bar" style="width: <?= max(8, min(100,$pct)) ?>%"></div></div><span><?= $days !== null ? (int)$days . 'd' : '—' ?></span></div></td>
              <td><span class="pill-status <?= strtolower(str_replace(' ','-',$item['status'])) ?>"><?= htmlspecialchars($item['status']) ?></span></td>
              <td>MZN <?= number_format((float)$item['unit_cost'], 0) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <?php if ($selectedMedicine): $stockPct = min(100, $selectedMedicine['reorder_point'] ? round(($selectedMedicine['stock']/$selectedMedicine['reorder_point'])*100):0); $daysLeft = $selectedMedicine['avg_monthly_usage']>0?floor(($selectedMedicine['stock']/$selectedMedicine['avg_monthly_usage'])*30):null; ?>
  <div class="drawer-backdrop"></div>
  <aside class="drawer-panel">
    <div class="drawer-head">
      <div>
        <h3><?= htmlspecialchars($selectedMedicine['name']) ?></h3>
        <div class="text-muted"><?= htmlspecialchars($selectedMedicine['brand_name']) ?> · <?= htmlspecialchars($selectedMedicine['strength']) ?> · <?= htmlspecialchars($selectedMedicine['dosage_form']) ?></div>
        <div class="d-flex gap-2 mt-2 flex-wrap">
          <span class="pill-status <?= strtolower(str_replace(' ','-',$selectedMedicine['status'])) ?>"><?= htmlspecialchars($selectedMedicine['status']) ?></span>
          <span class="pill-status in-stock"><?= htmlspecialchars($selectedMedicine['category']) ?></span>
          <?php if (!empty($selectedMedicine['is_essential'])): ?><span class="pill-status in-stock">Essential</span><?php endif; ?>
        </div>
      </div>
      <div class="d-flex gap-2">
        <a class="btn btn-light" href="index.php?route=inventory&edit=<?= (int)$selectedMedicine['id'] ?>&detail=<?= (int)$selectedMedicine['id'] ?>"><i class="bi bi-pencil-square"></i></a>
        <a class="btn btn-light text-danger" href="index.php?route=inventory&action=delete&id=<?= (int)$selectedMedicine['id'] ?>" onclick="return confirm('Delete this medicine?')"><i class="bi bi-trash"></i></a>
        <a class="btn btn-light" href="index.php?route=inventory"><i class="bi bi-x-lg"></i></a>
      </div>
    </div>
    <div class="drawer-tabs"><a href="#overview" class="drawer-tab active">Overview</a><a href="#batches" class="drawer-tab">Batches</a><a href="#history" class="drawer-tab">Stock History</a></div>
    <div class="drawer-body" id="overview">
      <div class="row g-3 mb-3">
        <div class="col-6"><div class="mini-stat"><span>Current Stock</span><strong><?= (int)$selectedMedicine['stock'] ?> units</strong></div></div>
        <div class="col-6"><div class="mini-stat"><span>Reorder Point</span><strong><?= (int)$selectedMedicine['reorder_point'] ?> units</strong></div></div>
        <div class="col-6"><div class="mini-stat"><span>Reorder Qty</span><strong><?= (int)$selectedMedicine['reorder_qty'] ?> units</strong></div></div>
        <div class="col-6"><div class="mini-stat"><span>Avg Monthly Usage</span><strong><?= (int)$selectedMedicine['avg_monthly_usage'] ?> units</strong></div></div>
        <div class="col-6"><div class="mini-stat"><span>Unit Cost</span><strong>MZN <?= number_format((float)$selectedMedicine['unit_cost'],0) ?></strong></div></div>
        <div class="col-6"><div class="mini-stat"><span>SKU</span><strong><?= htmlspecialchars($selectedMedicine['sku']) ?></strong></div></div>
        <div class="col-6"><div class="mini-stat"><span>Storage</span><strong><?= htmlspecialchars($selectedMedicine['storage'] ?? 'Room Temperature') ?></strong></div></div>
        <div class="col-6"><div class="mini-stat"><span>Days of Stock</span><strong><?= $daysLeft !== null ? (int)$daysLeft . ' days' : '—' ?></strong></div></div>
      </div>
      <div class="small text-muted mb-1">Stock vs Reorder Point <span class="float-end fw-semibold text-dark"><?= $stockPct ?>%</span></div>
      <div class="progress alk-progress mb-3"><div class="progress-bar" style="width: <?= $stockPct ?>%"></div></div>
      <div class="mini-stat mb-3"><span>Notes</span><strong style="font-size:1rem;font-weight:600;"><?= htmlspecialchars($selectedMedicine['notes'] ?? 'High-priority inventory item for operational continuity.') ?></strong></div>
      <button class="btn btn-alk w-100" data-bs-toggle="modal" data-bs-target="#movementModal"><i class="bi bi-arrow-left-right"></i> Log Stock Movement</button>
    </div>
    <div class="drawer-body mt-3" id="batches">
      <?php foreach ($selectedBatches as $batch): ?>
        <div class="feed-item"><div class="d-flex justify-content-between"><strong><?= htmlspecialchars($batch['batch_no']) ?></strong><span class="pill-status <?= strtolower(str_replace(' ','-',$batch['status'])) ?>"><?= htmlspecialchars($batch['status']) ?></span></div><div class="small text-muted">Qty <?= (int)$batch['qty'] ?> · Expiry <?= htmlspecialchars($batch['expiry']) ?></div></div>
      <?php endforeach; ?>
    </div>
    <div class="drawer-body mt-3" id="history">
      <div class="internal-scroll-panel short-scroll">
      <?php foreach ($selectedHistory as $move): ?>
        <div class="feed-item"><div class="d-flex justify-content-between"><strong><?= htmlspecialchars($move['type']) ?></strong><span class="small text-muted"><?= htmlspecialchars(date('d M H:i', strtotime($move['created_at']))) ?></span></div><div class="small text-muted"><?= htmlspecialchars($move['department']) ?> · <?= (int)$move['quantity'] ?> units</div></div>
      <?php endforeach; ?>
      </div>
    </div>
  </aside>
  <?php endif; ?>

  <div class="modal fade" id="movementModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"><form method="post"><input type="hidden" name="action" value="movement"><input type="hidden" name="medicine_id" value="<?= (int)($selectedMedicine['id'] ?? 0) ?>"><div class="modal-header"><h5 class="modal-title">Log Stock Movement</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body row g-3"><div class="col-md-4"><label class="form-label">Movement Type</label><select class="form-select" name="type"><?php foreach (['Receipt','Dispensing','Transfer Out','Transfer In','Adjustment','Write-off','Return'] as $t): ?><option><?= $t ?></option><?php endforeach; ?></select></div><div class="col-md-4"><label class="form-label">Quantity</label><input class="form-control" type="number" name="quantity" min="1" required></div><div class="col-md-4"><label class="form-label">Facility</label><input class="form-control" name="facility" value="<?= htmlspecialchars($selectedMedicine['facility'] ?? 'Maputo Central') ?>"></div><div class="col-md-6"><label class="form-label">Department</label><input class="form-control" name="department" value="<?= htmlspecialchars($selectedMedicine['department'] ?? 'Pharmacy') ?>"></div><div class="col-md-6"><label class="form-label">Device</label><input class="form-control" name="device" value="Store Tablet 01"></div><div class="col-12"><label class="form-label">Notes</label><textarea class="form-control" name="notes"></textarea></div><div class="col-12"><div class="form-check"><input class="form-check-input" type="checkbox" name="offline" value="1" id="offlineFlag"><label class="form-check-label" for="offlineFlag">Queue this action for offline-safe sync</label></div></div></div><div class="modal-footer"><button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancel</button><button class="btn btn-alk">Save movement</button></div></form></div></div></div>

  <div class="modal fade" id="addMedicineModal" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content"><form method="post"><input type="hidden" name="action" value="add-medicine"><div class="modal-header"><h5 class="modal-title">Add Medicine</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body row g-3"><?php include __DIR__ . '/partials/medicine_form_fields.php'; ?></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-alk">Add Medicine</button></div></form></div></div></div>

  <?php if ($editMedicine): ?>
  <div class="modal fade show" id="editMedicineModal" tabindex="-1" style="display:block;background:rgba(0,0,0,.55)"><div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content"><form method="post"><input type="hidden" name="action" value="edit-medicine"><input type="hidden" name="id" value="<?= (int)$editMedicine['id'] ?>"><div class="modal-header"><h5 class="modal-title">Edit Medicine</h5><a href="index.php?route=inventory&detail=<?= (int)$editMedicine['id'] ?>" class="btn-close"></a></div><div class="modal-body row g-3"><?php $formMedicine = $editMedicine; include __DIR__ . '/partials/medicine_form_fields.php'; ?></div><div class="modal-footer"><a href="index.php?route=inventory&detail=<?= (int)$editMedicine['id'] ?>" class="btn btn-outline-secondary">Cancel</a><button class="btn btn-alk">Update Medicine</button></div></form></div></div></div>
  <?php endif; ?>
</section>
