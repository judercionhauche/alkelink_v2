<section class="page-body">
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card alk-card h-100">
        <div class="card-body d-flex flex-column">
          <div class="section-head mb-3">
            <div>
              <h2>AlkeLink AI Copilot</h2>
              <p class="text-muted mb-0">Explainable, hospital-grade decision support.</p>
            </div>
            <span class="badge bg-primary-subtle text-primary">Live</span>
          </div>
          <div class="quick-actions mb-3">
            <?php foreach ([
              'Which drugs need urgent action?',
              'What should we reorder this week?',
              'Show expiry risks by department',
              'Why is oxytocin forecast changing?',
              'Summarize malaria medicine demand',
            ] as $action): ?>
              <button class="btn btn-alk-soft btn-sm me-2 mb-2 ai-prompt" data-prompt="<?= htmlspecialchars($action) ?>"><?= htmlspecialchars($action) ?></button>
            <?php endforeach; ?>
          </div>
          <div class="ai-chat-window" id="aiChatWindow">
            <div class="chat-msg bot">Hello. I can help with stockout prediction, reorder planning, expiry risk, and department usage intelligence.</div>
          </div>
          <div class="mt-3 d-flex gap-2">
            <input id="aiPromptInput" class="form-control" placeholder="Ask the copilot something operational...">
            <button class="btn btn-alk" id="sendAiPrompt">Ask</button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card alk-card h-100">
        <div class="card-body">
          <h5 class="mb-3">Latest AI Insights</h5>
          <div id="aiInsightsList" style="max-height: 360px; overflow-y: auto;">
            <?php foreach (array_slice($insights, 0, 6) as $insight): ?>
              <div class="feed-item" data-insight-id="<?= (int)$insight['id'] ?>">
                <div class="d-flex justify-content-between gap-2">
                  <strong><?= htmlspecialchars($insight['title']) ?></strong>
                  <div class="d-flex gap-2 align-items-center">
                    <span class="small text-muted"><?= htmlspecialchars(date('d M', strtotime($insight['generated_at']))) ?></span>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-clear-insight" title="Remove this insight">×</button>
                  </div>
                </div>
                <div class="small text-muted mb-2"><?= htmlspecialchars($insight['text']) ?></div>
                <div class="small">Confidence <?= (int) $insight['confidence'] ?>%</div>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="mt-2 text-end">
            <button id="refreshAiInsights" class="btn btn-sm btn-outline-secondary">Refresh</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  // Endpoints used by app/assets/js/app.js and this page
  window.aiApiUrl = 'index.php?route=ai-ask';
  window.aiInsightsEndpoint = 'index.php?route=ai-insights';

  function renderInsights(insights) {
    const list = document.getElementById('aiInsightsList');
    if (!list) return;
    list.innerHTML = '';

    const limited = Array.isArray(insights) ? insights.slice(0, 6) : [];
    limited.forEach(insight => {
      const item = document.createElement('div');
      item.className = 'feed-item';
      item.dataset.insightId = insight.id;
      item.innerHTML = `
        <div class="d-flex justify-content-between gap-2">
          <strong>${insight.title || '(no title)'}</strong>
          <div class="d-flex gap-2 align-items-center">
            <span class="small text-muted">${new Date(insight.generated_at).toLocaleDateString(undefined, { day: '2-digit', month: 'short' })}</span>
            <button type="button" class="btn btn-sm btn-outline-danger btn-clear-insight" title="Remove this insight">×</button>
          </div>
        </div>
        <div class="small text-muted mb-2">${insight.text || ''}</div>
        <div class="small">Confidence ${parseInt(insight.confidence || 0, 10)}%</div>
      `;
      list.appendChild(item);
    });

    // Attach delete handlers
    list.querySelectorAll('.btn-clear-insight').forEach(btn => {
      btn.addEventListener('click', async () => {
        const parent = btn.closest('[data-insight-id]');
        const id = parent?.dataset?.insightId;
        if (!id) return;
        await deleteInsight(id);
        await fetchInsights();
      });
    });
  }

  async function deleteInsight(id) {
    try {
      await fetch('index.php?route=ai-insights-delete&id=' + encodeURIComponent(id), {
        method: 'POST',
        credentials: 'same-origin',
      });
    } catch (e) {
      // ignore
    }
  }

  async function fetchInsights() {
    try {
      const res = await fetch(window.aiInsightsEndpoint, { credentials: 'same-origin' });
      if (!res.ok) return;
      const data = await res.json();
      if (Array.isArray(data)) {
        renderInsights(data);
      }
    } catch (e) {
      // silent
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    const refreshBtn = document.getElementById('refreshAiInsights');
    refreshBtn?.addEventListener('click', () => fetchInsights());
    // Poll for new insights every 15 seconds
    setInterval(fetchInsights, 15000);
  });
</script>
