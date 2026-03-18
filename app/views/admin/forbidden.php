<section class="page-body">
  <div class="card alk-card">
    <div class="card-body py-5 text-center">
      <h2>Access denied</h2>
      <p class="text-muted">This page is limited to: <?= htmlspecialchars(implode(', ', $roles)) ?>.</p>
      <a href="index.php?route=dashboard" class="btn btn-alk">Back to dashboard</a>
    </div>
  </div>
</section>
