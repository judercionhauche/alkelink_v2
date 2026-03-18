<section class="page-body">
  <div class="section-head mb-4">
    <div>
      <h2>Platform Settings</h2>
      <p class="text-muted mb-0">Control localization, offline resilience, and AI governance defaults for AlkeLink deployments.</p>
    </div>
    <span class="badge bg-light text-dark border">Admin only</span>
  </div>
  <div class="row g-4">
    <?php foreach ($settings as $group): ?>
      <div class="col-lg-4">
        <div class="card alk-card h-100"><div class="card-body">
          <h5><?= htmlspecialchars($group['group']) ?></h5>
          <ul class="mt-3 mb-0 ps-3">
            <?php foreach ($group['items'] as $item): ?>
            <li class="mb-2"><?= htmlspecialchars($item) ?></li>
            <?php endforeach; ?>
          </ul>
        </div></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
