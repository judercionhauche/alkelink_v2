(function () {
  if (window.dashboardData) {
    const commonColors = ['#147a70', '#ee9b00', '#e23737', '#8a5cf6', '#4f6f97', '#7bc7bd'];
    const statusCtx = document.getElementById('statusChart');
    const categoryCtx = document.getElementById('categoryChart');
    const trendCtx = document.getElementById('trendChart');
    const deptCtx = document.getElementById('departmentChart');
    const baseOptions = { maintainAspectRatio: false, responsive: true };
    if (statusCtx) {
      new Chart(statusCtx, {
        type: 'doughnut',
        data: { labels: window.dashboardData.statusLabels, datasets: [{ data: window.dashboardData.statusValues, backgroundColor: commonColors, borderWidth: 0 }] },
        options: { ...baseOptions, plugins: { legend: { position: 'bottom' } }, cutout: '62%' }
      });
    }
    if (categoryCtx) {
      new Chart(categoryCtx, {
        type: 'bar',
        data: { labels: window.dashboardData.categoryLabels, datasets: [{ data: window.dashboardData.categoryValues, backgroundColor: '#147a70', borderRadius: 8 }] },
        options: { ...baseOptions, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
      });
    }
    if (trendCtx) {
      new Chart(trendCtx, {
        type: 'line',
        data: { labels: window.dashboardData.trendLabels, datasets: [{ data: window.dashboardData.trendValues, borderColor: '#147a70', backgroundColor: 'rgba(20,122,112,.12)', fill: true, tension: .35, pointRadius: 3, pointBackgroundColor: '#147a70' }] },
        options: { ...baseOptions, plugins: { legend: { display: false } }, scales: { y: { suggestedMin: 40, suggestedMax: 100 } } }
      });
    }
    if (deptCtx) {
      new Chart(deptCtx, {
        type: 'polarArea',
        data: { labels: window.dashboardData.deptLabels, datasets: [{ data: window.dashboardData.deptValues, backgroundColor: ['rgba(20,122,112,.85)','rgba(238,155,0,.75)','rgba(138,92,246,.72)','rgba(226,55,55,.72)','rgba(79,111,151,.72)'] }] },
        options: { ...baseOptions, plugins: { legend: { position: 'bottom' } } }
      });
    }
  }

  const aiWindow = document.getElementById('aiChatWindow');
  const sendBtn = document.getElementById('sendAiPrompt');
  const promptInput = document.getElementById('aiPromptInput');
  const suggestions = document.querySelectorAll('.ai-prompt');
  function localBotResponse(prompt) {
    const p = prompt.toLowerCase();
    if (p.includes('urgent')) return 'Urgent action: Ceftriaxone in Beira Emergency, Magnesium Sulfate in Maputo Maternity, and Ketamine in Beira Theatre. Oxytocin should also be reordered this week.';
    if (p.includes('reorder')) return 'Recommended reorder queue this week: Oxytocin, Magnesium Sulfate, Artemether-Lumefantrine, Ceftriaxone, Ketamine, and malaria rapid tests.';
    if (p.includes('expiry')) return 'Highest expiry risk: Amoxicillin in Maternity. Suggested action is redistribution to Pediatrics and Outpatient before current batch expiry.';
    if (p.includes('malaria')) return 'Malaria-related demand is above baseline. Add an 18% seasonal safety buffer for antimalarials and rapid tests in high-burden facilities.';
    return 'Based on current data, the safest next step is to review critical alerts, validate batch positions, and align procurement with high-risk departments.';
  }

  async function getBotResponse(prompt) {
    if (!window.aiApiUrl) {
      return localBotResponse(prompt);
    }

    try {
      const res = await fetch(window.aiApiUrl, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ prompt }),
      });
      if (!res.ok) {
        throw new Error('AI request failed');
      }
      const data = await res.json();
      if (data && data.ok && data.answer) {
        return data.answer;
      }
      // When the API request fails, return a clear error message.
      const err = data && data.error ? data.error : 'OpenAI request failed';
      return `⚠️ ${err}`;
    } catch (e) {
      return `⚠️ ${e.message || 'OpenAI request failed'}`;
    }
  }

  function appendChat(text, type) {
    if (!aiWindow) return null;
    const msg = document.createElement('div');
    msg.className = 'chat-msg ' + type;
    msg.textContent = text;
    aiWindow.appendChild(msg);
    aiWindow.scrollTop = aiWindow.scrollHeight;
    return msg;
  }

  if (sendBtn && promptInput) {
    sendBtn.addEventListener('click', async function () {
      const text = promptInput.value.trim();
      if (!text) return;
      appendChat(text, 'user');
      promptInput.value = '';
      const placeholder = appendChat('Generating response…', 'bot');
      const answer = await getBotResponse(text);
      if (placeholder) placeholder.textContent = answer;
      aiWindow.scrollTop = aiWindow.scrollHeight;
    });
  }

  suggestions.forEach(btn => btn.addEventListener('click', async () => {
    const text = btn.getAttribute('data-prompt') || btn.textContent.trim();
    appendChat(text, 'user');
    const placeholder = appendChat('Generating response…', 'bot');
    const answer = await getBotResponse(text);
    if (placeholder) placeholder.textContent = answer;
    aiWindow.scrollTop = aiWindow.scrollHeight;
  }));

  const scanResult = document.getElementById('scanResult');
  const barcodeInput = document.getElementById('barcodeInput');
  const lookupBtn = document.getElementById('simulateLookup');
  function updateScanResult(code) {
    if (!scanResult) return;
    scanResult.innerHTML = '<strong>Scanned Code:</strong> ' + code + '<br><strong>Matched Item:</strong> Artemether-Lumefantrine<br><strong>Workflow:</strong> Ready for receiving, issuing, or transfer.<br><strong>Offline State:</strong> Saved locally if network drops.<br><small class="text-muted">Sync queue and conflict review remain visible to the store manager.</small>';
  }
  if (lookupBtn && barcodeInput) lookupBtn.addEventListener('click', () => updateScanResult(barcodeInput.value || 'ALK-MED-001'));

  const readerEl = document.getElementById('reader');
  if (readerEl && window.Html5Qrcode) {
    const html5QrCode = new Html5Qrcode('reader');
    Html5Qrcode.getCameras().then(devices => {
      if (devices && devices.length) {
        html5QrCode.start({ facingMode: 'environment' }, { fps: 10, qrbox: { width: 250, height: 140 } }, decodedText => {
          updateScanResult(decodedText); html5QrCode.stop().catch(() => {});
        }, () => {}).catch(() => { readerEl.innerHTML = '<div class="p-4 text-muted">Camera unavailable. Use the handheld scanner input on the right.</div>'; });
      } else {
        readerEl.innerHTML = '<div class="p-4 text-muted">No camera detected. Use the handheld scanner input on the right.</div>';
      }
    }).catch(() => { readerEl.innerHTML = '<div class="p-4 text-muted">Camera permission not granted. Use the handheld scanner input on the right.</div>'; });
  }

  const orderItemsBox = document.getElementById('orderItemsBox');
  const addOrderItem = document.getElementById('addOrderItem');
  const totalText = document.getElementById('orderTotalText');
  const totalInput = document.getElementById('orderTotalInput');
  function bindOrderRow(row) {
    const med = row.querySelector('.item-medicine');
    const qty = row.querySelector('.item-qty');
    const cost = row.querySelector('.item-cost');
    const line = row.querySelector('.item-line-total');
    const removeBtn = row.querySelector('.remove-order-item');
    function recalc() {
      if (med && med.selectedOptions[0] && med.selectedOptions[0].dataset.cost && (!cost.value || cost.value === '0')) cost.value = med.selectedOptions[0].dataset.cost;
      const lineValue = (parseFloat(qty.value || 0) * parseFloat(cost.value || 0));
      line.textContent = 'MZN ' + lineValue.toFixed(0);
      recalcTotals();
    }
    [med, qty, cost].forEach(el => el && el.addEventListener('change', recalc));
    removeBtn && removeBtn.addEventListener('click', () => { if (document.querySelectorAll('.order-item-row').length > 1) { row.remove(); recalcTotals(); } });
    recalc();
  }
  function recalcTotals() {
    let total = 0;
    document.querySelectorAll('.order-item-row').forEach(row => {
      total += parseFloat(row.querySelector('.item-qty')?.value || 0) * parseFloat(row.querySelector('.item-cost')?.value || 0);
    });
    if (totalText) totalText.textContent = 'MZN ' + total.toFixed(0);
    if (totalInput) totalInput.value = total.toFixed(0);
  }
  if (orderItemsBox) {
    orderItemsBox.querySelectorAll('.order-item-row').forEach(bindOrderRow);
    addOrderItem && addOrderItem.addEventListener('click', () => {
      const first = orderItemsBox.querySelector('.order-item-row');
      const clone = first.cloneNode(true);
      clone.querySelectorAll('input').forEach(i => { if (i.classList.contains('item-qty')) i.value = '1'; else i.value = '0'; });
      const sel = clone.querySelector('select'); if (sel) sel.selectedIndex = 0;
      orderItemsBox.appendChild(clone);
      bindOrderRow(clone);
      recalcTotals();
    });
  }
})();
