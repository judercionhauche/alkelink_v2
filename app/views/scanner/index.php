<section class="page-body">
  <div class="section-head mb-3">
    <div>
      <h2>Barcode Scan Station</h2>
      <p class="text-muted mb-0">Use camera scan or handheld scanner input for receiving and dispensing workflows.</p>
    </div>
    <span class="badge bg-warning-subtle text-warning-emphasis">Offline-ready UX</span>
  </div>
  <div class="row g-4">
    <div class="col-lg-7">
      <div class="card alk-card">
        <div class="card-body">
          <h5>Camera Scanner</h5>
          <div id="reader" class="scanner-box"></div>
          <div class="small text-muted mt-2">Saved locally when offline. Sync resumes when connection returns.</div>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card alk-card mb-3">
        <div class="card-body">
          <h5>Handheld Scanner / Manual Input</h5>
          <label class="form-label">Barcode / QR code</label>
          <input class="form-control mb-2" id="barcodeInput" placeholder="Scan code here using USB scanner">
          <button class="btn btn-alk w-100" id="simulateLookup">Lookup Item</button>
        </div>
      </div>
      <div class="card alk-card">
        <div class="card-body">
          <h5>Scan Result</h5>
          <div id="scanResult" class="scan-result">No scan yet.</div>
          <div class="d-grid gap-2 mt-3">
            <button class="btn btn-outline-secondary">Receive Delivery</button>
            <button class="btn btn-outline-secondary">Dispense / Issue Stock</button>
            <button class="btn btn-outline-secondary">Transfer Between Departments</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
