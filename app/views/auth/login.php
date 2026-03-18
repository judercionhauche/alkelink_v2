<div class="login-screen">
  <div class="login-card shadow-lg">
    <div class="text-center mb-4">
      <img class="login-logo" src="https://static.wixstatic.com/media/ca7ee7_f89f82431563445e97f92b1e3f373805~mv2.png/v1/crop/x_0,y_166,w_1920,h_714/fill/w_358,h_130,al_c,q_85,usm_0.66_1.00_0.01,enc_avif,quality_auto/ca7ee7_f89f82431563445e97f92b1e3f373805~mv2.png" alt="AlkeLink">
      <h2 class="mt-3 mb-1 fw-bold"><?= htmlspecialchars(t('login.title')) ?></h2>
      <p class="text-secondary"><?= htmlspecialchars(t('login.subtitle')) ?></p>
    </div>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['db_error'])): ?>
      <div class="alert alert-warning">Database connection warning: <?= htmlspecialchars($_SESSION['db_error']) ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label"><?= htmlspecialchars(t('login.email')) ?></label>
        <input type="email" name="email" class="form-control" value="admin@alkelink.org" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><?= htmlspecialchars(t('login.password')) ?></label>
        <input type="password" name="password" class="form-control" value="password123" required>
      </div>
      <button class="btn btn-alk w-100"><?= htmlspecialchars(t('login.button')) ?></button>
    </form>
    <div class="demo-credentials mt-4">
      <div><strong><?= htmlspecialchars(t('login.demo')) ?></strong> admin@alkelink.org / password123</div>
    </div>
    <div class="text-center mt-3">
      <small class="text-secondary"><?= htmlspecialchars(t('common.language')) ?>:
        <a href="index.php?route=login&lang=en" class="text-decoration-none">English</a>
        &middot;
        <a href="index.php?route=login&lang=pt" class="text-decoration-none">Português</a>
      </small>
    </div>
  </div>
</div>
