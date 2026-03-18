<section class="page-body compact-page">
  <div class="section-head mb-3">
    <div>
      <h2>Procurement</h2>
      <p class="text-muted mb-0"><?= count($orders) ?> orders</p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#supplierModal"><i class="bi bi-building"></i> Add Supplier</button>
      <button class="btn btn-alk" data-bs-toggle="modal" data-bs-target="#orderModal"><i class="bi bi-plus-lg"></i> New Order</button>
    </div>
  </div>
  <div class="row g-3">
    <div class="col-xl-8">
      <?php foreach ($orders as $order): ?>
      <div class="card alk-card mb-3 proc-card"><div class="card-body proc-card-body"><div class="proc-card-flex">
          <div class="proc-card-main">
            <div class="d-flex align-items-center gap-2 flex-wrap mb-1"><strong><?= htmlspecialchars($order['order_number']) ?></strong><span class="badge text-bg-light border"><?= htmlspecialchars($order['status']) ?></span><?php if(!empty($order['ai_recommended'])): ?><span class="badge bg-success-subtle text-success border border-success-subtle">AI Recommended</span><?php endif; ?></div>
            <div class="small text-muted"><?= htmlspecialchars($order['supplier']) ?> · Ordered <?= htmlspecialchars($order['order_date']) ?> · Expected <?= htmlspecialchars($order['expected_delivery']) ?></div>
            <div class="small text-muted"><?= htmlspecialchars($order['notes']) ?></div>
          </div>
          <div class="proc-card-meta">
            <div class="proc-value">MZN <?= number_format((float)$order['value'],0) ?></div>
            <div class="proc-card-actions">
              <?php foreach (['Draft','Pending Approval','Approved','Ordered','Received'] as $st): if($st!==$order['status']): ?><a class="btn btn-sm btn-outline-secondary" href="index.php?route=procurement&action=status&id=<?= (int)$order['id'] ?>&value=<?= urlencode($st) ?>"><?= $st ?></a><?php endif; endforeach; ?>
            </div>
          </div>
        </div></div></div>
      <?php endforeach; ?>
    </div>
    <div class="col-xl-4">
      <div class="card alk-card fixed-card"><div class="card-body"><div class="d-flex justify-content-between align-items-center mb-3"><h5>AI Supplier Recommendations</h5><span class="badge bg-danger-subtle text-danger border border-danger-subtle">Critical</span></div><div class="internal-scroll-panel"><?php foreach(array_slice($insights,0,5) as $ins): ?><div class="feed-item"><div class="fw-semibold"><?= htmlspecialchars($ins['title']) ?></div><div class="small text-muted mb-2"><?= htmlspecialchars($ins['text']) ?></div><div class="small">Recommended qty: <strong><?= rand(120,500) ?></strong></div><div class="progress alk-progress mt-2"><div class="progress-bar" style="width: <?= (int)$ins['confidence'] ?>%"></div></div></div><?php endforeach; ?></div></div></div>
    </div>
  </div>
</section>

<div class="modal fade" id="supplierModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><form method="post"><input type="hidden" name="action" value="add-supplier"><div class="modal-header"><h5 class="modal-title">Add Supplier</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="row g-3"><div class="col-md-6"><label class="form-label">Supplier name</label><input class="form-control" name="name" required></div><div class="col-md-6"><label class="form-label">Contact (email)</label><input class="form-control" name="contact"></div><div class="col-md-4"><label class="form-label">Lead time (days)</label><input class="form-control" type="number" min="0" name="lead_time_days" value="7"></div><div class="col-md-4"><label class="form-label">Reliability (%)</label><input class="form-control" type="number" min="0" max="100" name="reliability_score" value="80"></div><div class="col-md-4"><label class="form-label">Status</label><select class="form-select" name="status"><option>Active</option><option>Preferred</option><option>Watch</option></select></div><div class="col-md-6"><label class="form-label">Category</label><input class="form-control" name="category" value="General Medicines"></div></div></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-alk">Save Supplier</button></div></form></div></div></div>

<div class="modal fade" id="orderModal" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content"><form method="post" id="orderForm"><input type="hidden" name="action" value="create-order"><input type="hidden" name="value" id="orderTotalInput" value="0"><div class="modal-header"><h5 class="modal-title">Create Procurement Order</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="row g-3 mb-3"><div class="col-md-6"><label class="form-label">Supplier *</label><select class="form-select" name="supplier"><?php foreach($suppliers as $sup): ?><option><?= htmlspecialchars($sup['name']) ?></option><?php endforeach; ?></select></div><div class="col-md-6"><label class="form-label">Expected Delivery</label><input class="form-control" type="date" name="expected_delivery"></div></div><div class="d-flex justify-content-between align-items-center mb-2"><h6 class="mb-0">Order Items</h6><button type="button" class="btn btn-alk-soft btn-sm" id="addOrderItem"><i class="bi bi-plus-lg"></i> Add Item</button></div><div class="order-items-box" id="orderItemsBox"><div class="order-item-row row g-2 mb-2"><div class="col-md-5"><select class="form-select item-medicine" name="item_medicine[]"><option value="">Select medicine</option><?php foreach($medicines as $med): ?><option value="<?= htmlspecialchars($med['name']) ?>" data-cost="<?= (float)$med['unit_cost'] ?>"><?= htmlspecialchars($med['name']) ?></option><?php endforeach; ?></select></div><div class="col-md-2"><input class="form-control item-qty" type="number" min="1" name="item_qty[]" value="1"></div><div class="col-md-2"><input class="form-control item-cost" type="number" min="0" name="item_cost[]" value="0"></div><div class="col-md-2 d-flex align-items-center"><strong class="item-line-total">MZN 0</strong></div><div class="col-md-1 d-flex align-items-center"><button type="button" class="btn btn-link text-danger remove-order-item"><i class="bi bi-trash"></i></button></div></div></div><div class="text-end fw-bold mb-3">Total: <span id="orderTotalText">MZN 0</span></div><div><label class="form-label">Notes</label><textarea class="form-control" name="notes" placeholder="Special instructions..."></textarea></div></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-alk">Create Order</button></div></form></div></div></div>

