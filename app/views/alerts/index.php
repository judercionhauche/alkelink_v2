<?php $criticalCount = count(array_filter($alerts, fn($a)=>$a['severity']==='Critical' && !in_array($a['status'],['Resolved','Dismissed'],true))); ?>
<section class="page-body compact-page">
  <div class="section-head mb-3">
    <div>
      <h2>Alerts Center</h2>
      <p class="text-muted mb-0"><?= count(array_filter($alerts, fn($a)=>!in_array($a['status'],['Resolved','Dismissed'],true))) ?> unresolved <?php if($criticalCount): ?><span class="badge bg-danger-subtle text-danger border border-danger-subtle ms-2"><?= $criticalCount ?> Critical</span><?php endif; ?></p>
    </div>
    <button class="btn btn-alk" data-bs-toggle="modal" data-bs-target="#createAlertModal"><i class="bi bi-plus-lg"></i> Create Alert</button>
  </div>
  <form class="row g-3 mb-3" method="get"><input type="hidden" name="route" value="alerts"><div class="col-md-3"><select class="form-select" name="severity"><?php foreach(['All Severities','Critical','High','Medium','Low'] as $opt): ?><option <?= $severity===$opt?'selected':''; ?>><?= $opt ?></option><?php endforeach; ?></select></div><div class="col-md-3"><select class="form-select" name="type"><?php foreach(['All Types','Low Stock','Out of Stock','Near Expiry','Usage Spike','Cold Chain Risk','Sync Reliability'] as $opt): ?><option <?= $type===$opt?'selected':''; ?>><?= $opt ?></option><?php endforeach; ?></select></div><div class="col-md-3"><select class="form-select" name="state"><?php foreach(['All Statuses','New','Acknowledged','In Progress','Resolved','Dismissed'] as $opt): ?><option <?= $state===$opt?'selected':''; ?>><?= $opt ?></option><?php endforeach; ?></select></div><div class="col-md-2"><button class="btn btn-alk-soft w-100">Apply</button></div></form>
  <div class="alerts-list-wrap">
  <?php foreach ($alerts as $alert): ?>
      <div class="alert-card <?= strtolower($alert['severity']) ?> mb-3" data-alert-id="<?= (int)$alert['id'] ?>">
        <div class="d-flex justify-content-between gap-3 flex-wrap">
          <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
              <span class="pill-severity <?= strtolower($alert['severity']) ?>"><?= htmlspecialchars($alert['severity']) ?></span>
              <span class="badge text-bg-light border"><?= htmlspecialchars($alert['type']) ?></span>
              <span class="badge <?= in_array($alert['status'],['New'])?'text-bg-danger':(in_array($alert['status'],['Acknowledged'])?'text-bg-warning':(in_array($alert['status'],['In Progress'])?'text-bg-primary':'text-bg-secondary')) ?>"><?= htmlspecialchars($alert['status']) ?></span>
            </div>
            <h5><?= htmlspecialchars($alert['title']) ?></h5>
            <div class="small text-muted mb-1"><?= htmlspecialchars($alert['medicine']) ?> · <?= htmlspecialchars($alert['department']) ?> · <?= htmlspecialchars($alert['facility']) ?> · <?= htmlspecialchars(date('d M Y, H:i', strtotime($alert['created_at']))) ?></div>
            <div class="recommended-box small"><strong>Recommended:</strong> <?= htmlspecialchars($alert['recommended_action']) ?></div>
          </div>
          <div class="alert-actions">
            <?php if ($alert['status'] === 'New'): ?><a class="btn btn-sm btn-outline-dark alert-action" href="index.php?route=alerts&action=status&id=<?= (int)$alert['id'] ?>&value=Acknowledged">Acknowledge</a><?php endif; ?>
            <?php if ($alert['status'] === 'Acknowledged'): ?><a class="btn btn-sm btn-outline-primary alert-action" href="index.php?route=alerts&action=status&id=<?= (int)$alert['id'] ?>&value=In%20Progress">In Progress</a><?php endif; ?>
            <?php if (in_array($alert['status'],['Acknowledged','In Progress'],true)): ?><a class="btn btn-sm btn-alk alert-action" href="index.php?route=alerts&action=status&id=<?= (int)$alert['id'] ?>&value=Resolved">Resolve</a><?php endif; ?>
            <?php if (!in_array($alert['status'],['Resolved','Dismissed'],true)): ?><a class="btn btn-sm btn-link text-muted alert-action" href="index.php?route=alerts&action=status&id=<?= (int)$alert['id'] ?>&value=Dismissed">Dismiss</a><?php endif; ?>
          </div>
        </div>
      </div>
  <?php endforeach; ?>
  </div>
</section>

<div class="modal fade" id="createAlertModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"><form method="post"><input type="hidden" name="action" value="create-alert"><div class="modal-header"><h5 class="modal-title">Create Alert</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body row g-3"><div class="col-md-6"><label class="form-label">Title</label><input class="form-control" name="title" required></div><div class="col-md-3"><label class="form-label">Severity</label><select class="form-select" name="severity"><?php foreach(['Critical','High','Medium','Low'] as $opt): ?><option><?= $opt ?></option><?php endforeach; ?></select></div><div class="col-md-3"><label class="form-label">Type</label><select class="form-select" name="type"><?php foreach(['Low Stock','Out of Stock','Near Expiry','Usage Spike','Cold Chain Risk'] as $opt): ?><option><?= $opt ?></option><?php endforeach; ?></select></div><div class="col-md-6"><label class="form-label">Medicine</label><input class="form-control" name="medicine"></div><div class="col-md-3"><label class="form-label">Department</label><input class="form-control" name="department" value="Pharmacy"></div><div class="col-md-3"><label class="form-label">Facility</label><input class="form-control" name="facility" value="Maputo Central"></div><div class="col-12"><label class="form-label">Recommended Action</label><textarea class="form-control" name="recommended_action"></textarea></div></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-alk">Create Alert</button></div></form></div></div></div>

<script>
  function updateAlertBadge(delta) {
    const badge = document.getElementById('alertCountBadge');
    if (!badge) return;
    const current = parseInt(badge.textContent || '0', 10);
    const next = Math.max(0, current + delta);
    badge.textContent = next;
    if (next > 0) {
      badge.classList.remove('bg-secondary', 'text-white-75');
      badge.classList.add('bg-danger', 'text-white');
    } else {
      badge.classList.remove('bg-danger', 'text-white');
      badge.classList.add('bg-secondary', 'text-white-75');
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.alert-action').forEach(el => {
      el.addEventListener('click', async event => {
        event.preventDefault();
        const href = el.getAttribute('href');
        if (!href) return;

        const url = new URL(href, window.location.origin);
        const value = (url.searchParams.get('value') || '').toLowerCase();
        const isResolvedAction = value === 'resolved' || value === 'dismissed';

        // If this is not a resolve/dismiss action, fall back to normal navigation (status updates will be visible after reload).
        if (!isResolvedAction) {
          window.location.href = href;
          return;
        }

        // Resolve/Dismiss: update server, then update local UI.
        try {
          const res = await fetch(href, { credentials: 'same-origin' });
          if (!res.ok) {
            window.location.href = href;
            return;
          }
          const card = el.closest('[data-alert-id]');
          if (card) card.remove();
          updateAlertBadge(-1);
        } catch (e) {
          window.location.href = href;
        }
      });
    });
  });
</script>
