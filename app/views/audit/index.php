<section class="page-body">
  <div class="section-head mb-4">
    <div>
      <h2>Audit Trail & Operational Intelligence</h2>
      <p class="text-muted mb-0">High-trust review space for stock events, procurement decisions, AI actions, and offline scanner exceptions.</p>
    </div>
    <span class="badge bg-light text-dark border">Restricted access</span>
  </div>
  <div class="card alk-card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 inventory-table">
          <thead><tr><th>Time</th><th>Actor</th><th>Role</th><th>Action</th><th>Facility</th><th>Status</th></tr></thead>
          <tbody>
          <?php foreach ($logs as $log): ?>
            <tr>
              <td><?= htmlspecialchars($log['time']) ?></td>
              <td><?= htmlspecialchars($log['actor']) ?></td>
              <td><?= htmlspecialchars($log['role']) ?></td>
              <td><?= htmlspecialchars($log['action']) ?></td>
              <td><?= htmlspecialchars($log['facility']) ?></td>
              <td><span class="badge text-bg-light border"><?= htmlspecialchars($log['status']) ?></span></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
