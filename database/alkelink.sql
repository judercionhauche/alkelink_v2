CREATE DATABASE IF NOT EXISTS alkelink CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE alkelink;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  role VARCHAR(80) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  facility VARCHAR(120) NULL,
  status VARCHAR(80) DEFAULT 'Active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS medicines (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  brand_name VARCHAR(160) NULL,
  category VARCHAR(80) NOT NULL,
  dosage_form VARCHAR(80) NULL,
  strength VARCHAR(80) NULL,
  sku VARCHAR(80) NULL,
  barcode VARCHAR(80) NULL,
  stock INT DEFAULT 0,
  reorder_point INT DEFAULT 0,
  reorder_qty INT DEFAULT 0,
  avg_monthly_usage DECIMAL(10,2) DEFAULT 0,
  unit_cost DECIMAL(12,2) DEFAULT 0,
  status VARCHAR(50) DEFAULT 'In Stock',
  is_critical TINYINT(1) DEFAULT 0,
  is_essential TINYINT(1) DEFAULT 1,
  facility VARCHAR(120) NULL,
  department VARCHAR(120) NULL,
  expiry_date DATE NULL,
  storage VARCHAR(120) NULL,
  notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS alerts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  severity VARCHAR(50) NOT NULL,
  type VARCHAR(80) NOT NULL,
  status VARCHAR(80) NOT NULL,
  medicine VARCHAR(160) NULL,
  department VARCHAR(120) NULL,
  facility VARCHAR(120) NULL,
  recommended_action VARCHAR(255) NULL,
  created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS procurement_orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_number VARCHAR(60) NOT NULL,
  supplier VARCHAR(180) NOT NULL,
  status VARCHAR(80) NOT NULL,
  order_date DATE NOT NULL,
  expected_delivery DATE NULL,
  value DECIMAL(14,2) DEFAULT 0,
  ai_recommended TINYINT(1) DEFAULT 0,
  notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS ai_insights (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type VARCHAR(80) NOT NULL,
  title VARCHAR(255) NOT NULL,
  text TEXT NOT NULL,
  severity VARCHAR(50) NOT NULL,
  confidence INT DEFAULT 0,
  actioned TINYINT(1) DEFAULT 0,
  generated_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS stock_movements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  medicine_id INT NOT NULL,
  medicine VARCHAR(160) NOT NULL,
  type VARCHAR(80) NOT NULL,
  quantity INT NOT NULL,
  facility VARCHAR(120) NOT NULL,
  department VARCHAR(120) NOT NULL,
  performed_by VARCHAR(160) NOT NULL,
  notes TEXT NULL,
  created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS queue_events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type VARCHAR(80) NOT NULL,
  reference VARCHAR(80) NOT NULL,
  device VARCHAR(120) NOT NULL,
  facility VARCHAR(120) NOT NULL,
  payload TEXT NOT NULL,
  status VARCHAR(80) NOT NULL,
  created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS suppliers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(180) NOT NULL,
  lead_time_days INT DEFAULT 0,
  reliability_score INT DEFAULT 0,
  contact VARCHAR(180) NULL,
  category VARCHAR(120) NULL,
  status VARCHAR(80) NULL
);

INSERT INTO users (name, email, role, password_hash) VALUES
('Judercio Nhauche', 'admin@alkelink.org', 'Super Admin', '$2y$12$s720sa8xcb8pxDLYNPXosOt2K4hURrVtDfvzHkOUZF2JVCkHU/Tvy');

INSERT INTO medicines (name, brand_name, category, dosage_form, strength, sku, stock, reorder_point, reorder_qty, avg_monthly_usage, unit_cost, status, is_critical, is_essential, facility, department, expiry_date) VALUES
('Artemether-Lumefantrine','Coartem','Antimalarial','Tablet','20/120mg','ALK-MED-001',180,220,600,540,145,'Low Stock',1,1,'Hospital Central de Maputo','Pharmacy','2026-07-14'),
('Amoxicillin','Moxil','Antibiotic','Capsule','500mg','ALK-MED-002',44,120,400,190,18,'Low Stock',1,1,'Hospital Central de Maputo','Maternity','2026-05-20'),
('Paracetamol','Panadol','Analgesic','Tablet','500mg','ALK-MED-003',900,300,1200,620,5,'In Stock',0,1,'Hospital Central de Maputo','Outpatient','2027-01-19');

INSERT INTO alerts (title, severity, type, status, medicine, department, facility, recommended_action, created_at) VALUES
('Ceftriaxone fully depleted in Emergency','Critical','Out of Stock','New','Ceftriaxone','Emergency','Beira Regional','Emergency procurement within 24 hours','2026-03-15 08:05:00');

INSERT INTO procurement_orders (order_number, supplier, status, order_date, expected_delivery, value, ai_recommended, notes) VALUES
('PO-20260315-101','MedSupply Mozambique','Pending Approval','2026-03-15','2026-03-22',145000,1,'Urgent maternity and emergency replenishment');

INSERT INTO ai_insights (type, title, text, severity, confidence, actioned, generated_at) VALUES
('Daily Briefing','Today\'s operational briefing','Two critical stock risks require action today: Ceftriaxone in Beira Emergency and Magnesium Sulfate in Maputo Maternity.','High',87,0,'2026-03-15 08:50:00');

INSERT INTO suppliers (name, lead_time_days, reliability_score, contact, category, status) VALUES
('MedSupply Mozambique', 7, 91, 'procurement@medsupply.co.mz', 'General Medicines', 'Preferred'),
('PharmaLogix Beira', 10, 83, 'ops@pharmalogix.co.mz', 'Antibiotics & Emergency', 'Active'),
('Vida Health Procurement', 8, 88, 'orders@vidahealth.co.mz', 'Antimalarials', 'Preferred'),
('BioFrio Logistics', 6, 79, 'coldchain@biofrio.co.mz', 'Cold Chain', 'Watch');

INSERT INTO stock_movements (medicine_id, medicine, type, quantity, facility, department, performed_by, notes, created_at) VALUES
(5, 'Ceftriaxone', 'Adjustment', -30, 'Beira Regional', 'Emergency', 'System AI', 'Correcting stock after supplier update', '2026-03-15 08:10:00'),
(4, 'Oxytocin', 'Dispensing', 24, 'Maputo Central', 'Maternity', 'Anabela Mucavele', 'Routine issue', '2026-03-15 09:16:00');

INSERT INTO queue_events (type, reference, device, facility, payload, status, created_at) VALUES
('Receiving','RCV-001','Scanner 02','Beira Regional','3 Ceftriaxone cartons queued offline','Pending','2026-03-15 10:56:00'),
('Dispensing','DSP-019','Ward Mobile 03','Nampula District','2 insulin vials issue awaiting sync','Retrying','2026-03-15 10:58:00');
