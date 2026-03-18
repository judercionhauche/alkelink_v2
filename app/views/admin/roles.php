<?php $adminModel = new \App\Models\AdminModel(); $permissions = $adminModel->permissions(); ?>
<section class="page-body">
  <div class="section-head mb-3">
    <div>
      <h2>Roles & Access Control</h2>
      <p class="text-muted mb-0">Enterprise permission architecture for Mozambican hospital operations.</p>
    </div>
    <a href="index.php?route=users" class="btn btn-alk-soft">Open users</a>
  </div>
  <div class="row g-4 mb-4">
    <?php foreach ($rolesList as $role): ?>
      <div class="col-md-6 col-xl-3"><div class="card alk-card h-100"><div class="card-body"><h5><?= htmlspecialchars($role['name']) ?></h5><p class="text-muted mb-0"><?= htmlspecialchars($role['description']) ?></p></div></div></div>
    <?php endforeach; ?>
  </div>
  <div class="card alk-card">
    <div class="card-body">
      <h5 class="mb-3">Permission matrix</h5>
      <div class="table-responsive"><table class="table permission-table align-middle"><thead><tr><th>Capability</th><th>Super Admin</th><th>Hospital Admin</th><th>Pharmacist</th><th>Procurement</th><th>Store</th><th>Clinician</th><th>Auditor</th></tr></thead><tbody><?php foreach ($permissions as $row): ?><tr><td><strong><?= htmlspecialchars($row['capability']) ?></strong></td><td><?= htmlspecialchars($row['Super Admin']) ?></td><td><?= htmlspecialchars($row['Hospital Administrator']) ?></td><td><?= htmlspecialchars($row['Pharmacist']) ?></td><td><?= htmlspecialchars($row['Procurement Officer']) ?></td><td><?= htmlspecialchars($row['Store Manager']) ?></td><td><?= htmlspecialchars($row['Clinician']) ?></td><td><?= htmlspecialchars($row['Auditor']) ?></td></tr><?php endforeach; ?></tbody></table></div>
    </div>
  </div>
</section>