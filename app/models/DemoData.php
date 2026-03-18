<?php
namespace App\Models;

class DemoData
{
    public static function users(): array
    {
        return [
            ['id'=>1,'name'=>'Judercio Nhauche','email'=>'admin@alkelink.org','password'=>'password123','role'=>'Super Admin'],
            ['id'=>2,'name'=>'Anabela Mucavele','email'=>'pharmacist@alkelink.org','password'=>'password123','role'=>'Pharmacist'],
            ['id'=>3,'name'=>'Carlos Sitoe','email'=>'procurement@alkelink.org','password'=>'password123','role'=>'Procurement Officer'],
            ['id'=>4,'name'=>'Dr. Lina Mavota','email'=>'admin.hospital@alkelink.org','password'=>'password123','role'=>'Hospital Administrator'],
            ['id'=>5,'name'=>'Mateus Cossa','email'=>'auditor@alkelink.org','password'=>'password123','role'=>'Auditor'],
        ];
    }

    public static function medicines(): array
    {
        return [
            ['id'=>1,'name'=>'Artemether-Lumefantrine','brand_name'=>'Coartem','category'=>'Antimalarial','dosage_form'=>'Tablet','strength'=>'20/120mg','sku'=>'ALK-MED-001','stock'=>180,'reorder_point'=>220,'reorder_qty'=>600,'avg_monthly_usage'=>540,'unit_cost'=>145,'status'=>'Low Stock','is_critical'=>1,'is_essential'=>1,'facility'=>'Maputo Central','department'=>'Pharmacy','expiry_date'=>'2026-07-14'],
            ['id'=>2,'name'=>'Amoxicillin','brand_name'=>'Moxil','category'=>'Antibiotic','dosage_form'=>'Capsule','strength'=>'500mg','sku'=>'ALK-MED-002','stock'=>44,'reorder_point'=>120,'reorder_qty'=>400,'avg_monthly_usage'=>190,'unit_cost'=>18,'status'=>'Low Stock','is_critical'=>1,'is_essential'=>1,'facility'=>'Maputo Central','department'=>'Maternity','expiry_date'=>'2026-05-20'],
            ['id'=>3,'name'=>'Paracetamol','brand_name'=>'Panadol','category'=>'Analgesic','dosage_form'=>'Tablet','strength'=>'500mg','sku'=>'ALK-MED-003','stock'=>900,'reorder_point'=>300,'reorder_qty'=>1200,'avg_monthly_usage'=>620,'unit_cost'=>5,'status'=>'In Stock','is_critical'=>0,'is_essential'=>1,'facility'=>'Maputo Central','department'=>'Outpatient','expiry_date'=>'2027-01-19'],
            ['id'=>4,'name'=>'Oxytocin','brand_name'=>'Oxytocare','category'=>'Maternal Care','dosage_form'=>'Injection','strength'=>'10IU/ml','sku'=>'ALK-MED-004','stock'=>32,'reorder_point'=>80,'reorder_qty'=>250,'avg_monthly_usage'=>110,'unit_cost'=>42,'status'=>'Low Stock','is_critical'=>1,'is_essential'=>1,'facility'=>'Maputo Central','department'=>'Maternity','expiry_date'=>'2026-04-18'],
            ['id'=>5,'name'=>'Ceftriaxone','brand_name'=>'Rocephin','category'=>'Antibiotic','dosage_form'=>'Injection','strength'=>'1g','sku'=>'ALK-MED-005','stock'=>0,'reorder_point'=>90,'reorder_qty'=>300,'avg_monthly_usage'=>140,'unit_cost'=>58,'status'=>'Out of Stock','is_critical'=>1,'is_essential'=>1,'facility'=>'Beira Regional','department'=>'Emergency','expiry_date'=>'2026-09-30'],
            ['id'=>6,'name'=>'Normal Saline','brand_name'=>'NS','category'=>'IV Fluid','dosage_form'=>'IV Fluid','strength'=>'500ml','sku'=>'ALK-MED-006','stock'=>470,'reorder_point'=>180,'reorder_qty'=>800,'avg_monthly_usage'=>290,'unit_cost'=>35,'status'=>'In Stock','is_critical'=>1,'is_essential'=>1,'facility'=>'Maputo Central','department'=>'Operating Theatre','expiry_date'=>'2026-12-03'],
            ['id'=>7,'name'=>'Insulin Regular','brand_name'=>'Humulin R','category'=>'Antidiabetic','dosage_form'=>'Injection','strength'=>'100IU/ml','sku'=>'ALK-MED-007','stock'=>68,'reorder_point'=>60,'reorder_qty'=>180,'avg_monthly_usage'=>45,'unit_cost'=>290,'status'=>'In Stock','is_critical'=>1,'is_essential'=>1,'facility'=>'Nampula District','department'=>'Pharmacy','expiry_date'=>'2026-06-08'],
            ['id'=>8,'name'=>'ORS Sachets','brand_name'=>'Rehydralyte','category'=>'Emergency','dosage_form'=>'Powder','strength'=>'1L sachet','sku'=>'ALK-MED-008','stock'=>210,'reorder_point'=>120,'reorder_qty'=>500,'avg_monthly_usage'=>170,'unit_cost'=>9,'status'=>'In Stock','is_critical'=>0,'is_essential'=>1,'facility'=>'Beira Regional','department'=>'Pediatrics','expiry_date'=>'2026-08-01'],
            ['id'=>9,'name'=>'Surgical Gloves','brand_name'=>'SafeHands','category'=>'Surgical','dosage_form'=>'Unit','strength'=>'Medium','sku'=>'ALK-MED-009','stock'=>1200,'reorder_point'=>500,'reorder_qty'=>2000,'avg_monthly_usage'=>880,'unit_cost'=>7,'status'=>'In Stock','is_critical'=>1,'is_essential'=>1,'facility'=>'Maputo Central','department'=>'Operating Theatre','expiry_date'=>'2027-02-10'],
            ['id'=>10,'name'=>'Magnesium Sulfate','brand_name'=>'Mag-Safe','category'=>'Maternal Care','dosage_form'=>'Injection','strength'=>'500mg/ml','sku'=>'ALK-MED-010','stock'=>21,'reorder_point'=>50,'reorder_qty'=>160,'avg_monthly_usage'=>40,'unit_cost'=>73,'status'=>'Low Stock','is_critical'=>1,'is_essential'=>1,'facility'=>'Maputo Central','department'=>'Maternity','expiry_date'=>'2026-03-29'],
            ['id'=>11,'name'=>'Rabies Vaccine','brand_name'=>'Rabipur','category'=>'Vaccine','dosage_form'=>'Injection','strength'=>'2.5IU','sku'=>'ALK-MED-011','stock'=>12,'reorder_point'=>20,'reorder_qty'=>60,'avg_monthly_usage'=>8,'unit_cost'=>680,'status'=>'Low Stock','is_critical'=>0,'is_essential'=>0,'facility'=>'Nampula District','department'=>'Outpatient','expiry_date'=>'2026-11-13'],
            ['id'=>12,'name'=>'Azithromycin','brand_name'=>'Zithro','category'=>'Antibiotic','dosage_form'=>'Tablet','strength'=>'250mg','sku'=>'ALK-MED-012','stock'=>250,'reorder_point'=>90,'reorder_qty'=>300,'avg_monthly_usage'=>100,'unit_cost'=>25,'status'=>'In Stock','is_critical'=>0,'is_essential'=>1,'facility'=>'Beira Regional','department'=>'Outpatient','expiry_date'=>'2026-07-27'],
            ['id'=>13,'name'=>'Anti-malarial Rapid Tests','brand_name'=>'CareStart','category'=>'Other','dosage_form'=>'Kit','strength'=>'Single','sku'=>'ALK-MED-013','stock'=>60,'reorder_point'=>100,'reorder_qty'=>250,'avg_monthly_usage'=>120,'unit_cost'=>38,'status'=>'Low Stock','is_critical'=>1,'is_essential'=>1,'facility'=>'Maputo Central','department'=>'Laboratory','expiry_date'=>'2026-10-15'],
            ['id'=>14,'name'=>'Misoprostol','brand_name'=>'Misotab','category'=>'Maternal Care','dosage_form'=>'Tablet','strength'=>'200mcg','sku'=>'ALK-MED-014','stock'=>86,'reorder_point'=>70,'reorder_qty'=>220,'avg_monthly_usage'=>95,'unit_cost'=>21,'status'=>'In Stock','is_critical'=>1,'is_essential'=>1,'facility'=>'Quelimane Provincial','department'=>'Maternity','expiry_date'=>'2026-09-10'],
            ['id'=>15,'name'=>'Ketamine','brand_name'=>'Ketalar','category'=>'Emergency','dosage_form'=>'Injection','strength'=>'50mg/ml','sku'=>'ALK-MED-015','stock'=>14,'reorder_point'=>35,'reorder_qty'=>80,'avg_monthly_usage'=>32,'unit_cost'=>140,'status'=>'Low Stock','is_critical'=>1,'is_essential'=>1,'facility'=>'Beira Regional','department'=>'Operating Theatre','expiry_date'=>'2026-06-01'],
            ['id'=>16,'name'=>'Zinc Tablets','brand_name'=>'Zincol','category'=>'Pediatrics','dosage_form'=>'Tablet','strength'=>'20mg','sku'=>'ALK-MED-016','stock'=>310,'reorder_point'=>140,'reorder_qty'=>500,'avg_monthly_usage'=>180,'unit_cost'=>4,'status'=>'In Stock','is_critical'=>0,'is_essential'=>1,'facility'=>'Nampula District','department'=>'Pediatrics','expiry_date'=>'2026-12-12'],
        ];
    }

    public static function alerts(): array
    {
        return [
            ['id'=>1,'title'=>'Ceftriaxone fully depleted in Emergency','severity'=>'Critical','type'=>'Out of Stock','status'=>'New','medicine'=>'Ceftriaxone','department'=>'Emergency','facility'=>'Beira Regional','recommended_action'=>'Emergency procurement within 24 hours','created_at'=>'2026-03-15 08:05:00'],
            ['id'=>2,'title'=>'Oxytocin below maternity safety stock','severity'=>'High','type'=>'Low Stock','status'=>'Acknowledged','medicine'=>'Oxytocin','department'=>'Maternity','facility'=>'Maputo Central','recommended_action'=>'Transfer from central store and reorder this week','created_at'=>'2026-03-15 06:20:00'],
            ['id'=>3,'title'=>'Amoxicillin batch may expire before full use','severity'=>'Medium','type'=>'Near Expiry','status'=>'In Progress','medicine'=>'Amoxicillin','department'=>'Maternity','facility'=>'Maputo Central','recommended_action'=>'Redistribute to outpatient and pediatric units','created_at'=>'2026-03-14 17:10:00'],
            ['id'=>4,'title'=>'Antimalarial usage spike in outpatient ward','severity'=>'High','type'=>'Usage Spike','status'=>'New','medicine'=>'Artemether-Lumefantrine','department'=>'Outpatient','facility'=>'Maputo Central','recommended_action'=>'Prepare seasonal malaria buffer order','created_at'=>'2026-03-15 07:35:00'],
            ['id'=>5,'title'=>'Scanner sync queue has 3 pending entries','severity'=>'Low','type'=>'Sync Reliability','status'=>'Resolved','medicine'=>'','department'=>'Pharmacy','facility'=>'Maputo Central','recommended_action'=>'Review offline queue logs','created_at'=>'2026-03-14 09:45:00'],
            ['id'=>6,'title'=>'Magnesium Sulfate critical maternity buffer risk','severity'=>'Critical','type'=>'Low Stock','status'=>'New','medicine'=>'Magnesium Sulfate','department'=>'Maternity','facility'=>'Maputo Central','recommended_action'=>'Approve urgent order today','created_at'=>'2026-03-15 08:42:00'],
            ['id'=>7,'title'=>'Cold-chain review needed for insulin fridge','severity'=>'Medium','type'=>'Cold Chain Risk','status'=>'Acknowledged','medicine'=>'Insulin Regular','department'=>'Pharmacy','facility'=>'Nampula District','recommended_action'=>'Inspect temperature logs and backup power','created_at'=>'2026-03-13 15:30:00'],
            ['id'=>8,'title'=>'Ketamine theatre buffer below safe target','severity'=>'High','type'=>'Low Stock','status'=>'New','medicine'=>'Ketamine','department'=>'Operating Theatre','facility'=>'Beira Regional','recommended_action'=>'Escalate theatre order before next trauma weekend','created_at'=>'2026-03-15 09:05:00'],
        ];
    }

    public static function aiInsights(): array
    {
        return [
            ['id'=>1,'type'=>'Daily Briefing','title'=>'Today\'s operational briefing','text'=>'Two critical stock risks require action today: Ceftriaxone in Beira Emergency and Magnesium Sulfate in Maputo Maternity. Antimalarial consumption is running 24% above baseline due to seasonal conditions.','severity'=>'High','confidence'=>87,'actioned'=>0,'generated_at'=>'2026-03-15 08:50:00'],
            ['id'=>2,'type'=>'Stockout Prediction','title'=>'Projected Ceftriaxone stockout already reached','text'=>'Ceftriaxone demand exceeded forecast in Beira Emergency. Estimated unmet need may affect sepsis and surgical care if not replenished immediately.','severity'=>'Critical','confidence'=>94,'actioned'=>0,'generated_at'=>'2026-03-15 08:30:00'],
            ['id'=>3,'type'=>'Reorder Recommendation','title'=>'Reorder Oxytocin now','text'=>'Based on maternity usage and average supplier lead time of 9 days, Oxytocin should be reordered now to avoid dipping below safe threshold in 6 days.','severity'=>'High','confidence'=>90,'actioned'=>0,'generated_at'=>'2026-03-15 08:12:00'],
            ['id'=>4,'type'=>'Expiry Risk','title'=>'Amoxicillin batch underutilization risk','text'=>'One maternity batch of Amoxicillin is unlikely to be consumed before expiry unless redistributed within 10 days.','severity'=>'Medium','confidence'=>76,'actioned'=>1,'generated_at'=>'2026-03-14 16:50:00'],
            ['id'=>5,'type'=>'Seasonal Alert','title'=>'Prepare malaria surge buffer','text'=>'Antimalarial demand is trending above expected seasonal range. Add 18% buffer to the next procurement cycle for high-burden facilities.','severity'=>'High','confidence'=>84,'actioned'=>0,'generated_at'=>'2026-03-15 07:58:00'],
            ['id'=>6,'type'=>'Procurement Optimization','title'=>'Consolidate IV fluid purchase','text'=>'Combining NS and ORS purchasing across Maputo and Beira may reduce cost per unit by an estimated 7% with current supplier rates.','severity'=>'Low','confidence'=>67,'actioned'=>0,'generated_at'=>'2026-03-13 10:22:00'],
            ['id'=>7,'type'=>'Usage Anomaly','title'=>'Unexpected analgesic drawdown in outpatient unit','text'=>'Paracetamol issue volume rose 19% week-over-week. Review whether outreach clinics or campaign activity changed the baseline.','severity'=>'Medium','confidence'=>72,'actioned'=>0,'generated_at'=>'2026-03-15 09:01:00'],
        ];
    }

    public static function procurementOrders(): array
    {
        return [
            ['id'=>1,'order_number'=>'PO-20260315-101','supplier'=>'MedSupply Mozambique','status'=>'Pending Approval','order_date'=>'2026-03-15','expected_delivery'=>'2026-03-22','value'=>145000,'ai_recommended'=>1,'notes'=>'Urgent maternity and emergency replenishment'],
            ['id'=>2,'order_number'=>'PO-20260312-233','supplier'=>'PharmaLogix Beira','status'=>'Ordered','order_date'=>'2026-03-12','expected_delivery'=>'2026-03-18','value'=>89000,'ai_recommended'=>0,'notes'=>'Routine antibiotic resupply'],
            ['id'=>3,'order_number'=>'PO-20260310-454','supplier'=>'Vida Health Procurement','status'=>'Received','order_date'=>'2026-03-10','expected_delivery'=>'2026-03-14','value'=>211000,'ai_recommended'=>1,'notes'=>'Seasonal antimalarial restock'],
            ['id'=>4,'order_number'=>'PO-20260315-205','supplier'=>'BioFrio Logistics','status'=>'Draft','order_date'=>'2026-03-15','expected_delivery'=>'2026-03-21','value'=>56000,'ai_recommended'=>1,'notes'=>'Cold chain replenishment and backup fridge consumables'],
        ];
    }

    public static function roles(): array
    {
        return [
            ['name'=>'Super Admin','description'=>'Full platform control including users, settings, financial data, and audit views'],
            ['name'=>'Hospital Administrator','description'=>'Executive dashboards, approvals, reports, and operational oversight'],
            ['name'=>'Pharmacist','description'=>'Stock visibility, dispensing, batch review, and clinical inventory action'],
            ['name'=>'Procurement Officer','description'=>'Purchase planning, supplier management, and reorder workflows'],
            ['name'=>'Store Manager','description'=>'Receiving, transfers, stock adjustments, and scanner operations'],
            ['name'=>'Clinician','description'=>'Read-only stock visibility and request escalation'],
            ['name'=>'Data Analyst','description'=>'Forecast review, reporting, and performance monitoring'],
            ['name'=>'Auditor','description'=>'Audit trail, exception review, and compliance visibility'],
        ];
    }

    public static function reports(): array
    {
        return [
            ['title'=>'Stockout Risk Report','summary'=>'7 medicines projected below threshold within 14 days'],
            ['title'=>'Expiry Risk Report','summary'=>'2 batches require redistribution this week'],
            ['title'=>'Procurement Performance','summary'=>'Average lead time 8.6 days across top suppliers'],
            ['title'=>'Department Consumption','summary'=>'Maternity and Emergency remain highest-risk units'],
            ['title'=>'Forecast Accuracy','summary'=>'90-day model tracking at 82% directional accuracy'],
            ['title'=>'Offline Sync Reliability','summary'=>'97.8% of queued scanner events synced within 2 hours'],
        ];
    }

    public static function facilities(): array
    {
        return [
            ['name'=>'Maputo Central','type'=>'Referral Hospital','departments'=>7,'health_score'=>61,'critical_alerts'=>2,'sync'=>'Healthy','risk'=>'High'],
            ['name'=>'Beira Regional','type'=>'Regional Hospital','departments'=>5,'health_score'=>48,'critical_alerts'=>2,'sync'=>'Delayed','risk'=>'Critical'],
            ['name'=>'Nampula District','type'=>'District Hospital','departments'=>4,'health_score'=>74,'critical_alerts'=>0,'sync'=>'Healthy','risk'=>'Moderate'],
            ['name'=>'Quelimane Provincial','type'=>'Provincial Hospital','departments'=>4,'health_score'=>79,'critical_alerts'=>0,'sync'=>'Healthy','risk'=>'Low'],
        ];
    }

    public static function forecast(): array
    {
        return [
            ['medicine'=>'Artemether-Lumefantrine','days_to_stockout'=>9,'recommended_qty'=>700,'confidence'=>86,'trend'=>'up'],
            ['medicine'=>'Oxytocin','days_to_stockout'=>6,'recommended_qty'=>250,'confidence'=>90,'trend'=>'up'],
            ['medicine'=>'Magnesium Sulfate','days_to_stockout'=>8,'recommended_qty'=>180,'confidence'=>88,'trend'=>'up'],
            ['medicine'=>'Ketamine','days_to_stockout'=>12,'recommended_qty'=>90,'confidence'=>74,'trend'=>'up'],
            ['medicine'=>'Insulin Regular','days_to_stockout'=>43,'recommended_qty'=>120,'confidence'=>69,'trend'=>'flat'],
        ];
    }

    public static function auditLogs(): array
    {
        return [
            ['time'=>'2026-03-15 09:16:00','actor'=>'Anabela Mucavele','role'=>'Pharmacist','action'=>'Dispensed 24 units of Oxytocin','facility'=>'Maputo Central','status'=>'Success'],
            ['time'=>'2026-03-15 09:02:00','actor'=>'Carlos Sitoe','role'=>'Procurement Officer','action'=>'Created draft PO-20260315-205','facility'=>'Maputo Central','status'=>'Success'],
            ['time'=>'2026-03-15 08:55:00','actor'=>'System AI','role'=>'Risk Engine','action'=>'Generated high-severity seasonal malaria buffer recommendation','facility'=>'All facilities','status'=>'Success'],
            ['time'=>'2026-03-15 08:27:00','actor'=>'Store Scanner 02','role'=>'Scan Station','action'=>'Queued 3 offline receiving events for later sync','facility'=>'Beira Regional','status'=>'Queued'],
            ['time'=>'2026-03-15 07:50:00','actor'=>'Mateus Cossa','role'=>'Auditor','action'=>'Viewed exception trail for maternity stock adjustments','facility'=>'Maputo Central','status'=>'Read only'],
            ['time'=>'2026-03-14 17:19:00','actor'=>'Dr. Lina Mavota','role'=>'Hospital Administrator','action'=>'Approved emergency transfer from central pharmacy to maternity','facility'=>'Maputo Central','status'=>'Approved'],
        ];
    }

    public static function syncQueue(): array
    {
        return [
            ['device'=>'Scanner 02','facility'=>'Beira Regional','pending'=>3,'last_sync'=>'11:03','status'=>'Pending'],
            ['device'=>'Pharmacy Tablet 01','facility'=>'Maputo Central','pending'=>0,'last_sync'=>'11:05','status'=>'Healthy'],
            ['device'=>'Ward Mobile 03','facility'=>'Nampula District','pending'=>1,'last_sync'=>'10:58','status'=>'Retrying'],
        ];
    }

    public static function settings(): array
    {
        return [
            ['group'=>'Localization','items'=>['Primary language: English','Secondary language ready: Portuguese','Currency display: MZN','Time zone: Africa/Maputo']],
            ['group'=>'Offline & Sync','items'=>['Local save confirmation enabled','Conflict review required for negative stock edits','Sync retry window: 15 minutes','Cold-chain alerts escalate after 20 minutes offline']],
            ['group'=>'AI Governance','items'=>['Explainable AI summaries on','Confidence threshold for red alerts: 80%','Daily briefing auto-generated at 06:00','Forecast horizon: 7 / 14 / 30 / 60 days']],
        ];
    }
    public static function suppliers(): array
    {
        return [
            ['id'=>1,'name'=>'MedSupply Mozambique','lead_time_days'=>7,'reliability_score'=>91,'contact'=>'procurement@medsupply.co.mz','category'=>'General Medicines','status'=>'Preferred'],
            ['id'=>2,'name'=>'PharmaLogix Beira','lead_time_days'=>10,'reliability_score'=>83,'contact'=>'ops@pharmalogix.co.mz','category'=>'Antibiotics & Emergency','status'=>'Active'],
            ['id'=>3,'name'=>'Vida Health Procurement','lead_time_days'=>8,'reliability_score'=>88,'contact'=>'orders@vidahealth.co.mz','category'=>'Antimalarials','status'=>'Preferred'],
            ['id'=>4,'name'=>'BioFrio Logistics','lead_time_days'=>6,'reliability_score'=>79,'contact'=>'coldchain@biofrio.co.mz','category'=>'Cold Chain','status'=>'Watch'],
        ];
    }

    public static function stockMovements(): array
    {
        return [
            ['id'=>1,'medicine_id'=>4,'medicine'=>'Oxytocin','type'=>'Dispensing','quantity'=>24,'facility'=>'Maputo Central','department'=>'Maternity','performed_by'=>'Anabela Mucavele','created_at'=>'2026-03-15 09:16:00','notes'=>'Routine maternity issue'],
            ['id'=>2,'medicine_id'=>10,'medicine'=>'Magnesium Sulfate','type'=>'Adjustment','quantity'=>-2,'facility'=>'Maputo Central','department'=>'Maternity','performed_by'=>'Store Scanner 02','created_at'=>'2026-03-15 08:27:00','notes'=>'Offline queue pending reconciliation'],
            ['id'=>3,'medicine_id'=>1,'medicine'=>'Artemether-Lumefantrine','type'=>'Receipt','quantity'=>120,'facility'=>'Maputo Central','department'=>'Pharmacy','performed_by'=>'Carlos Sitoe','created_at'=>'2026-03-14 15:10:00','notes'=>'Partial supplier delivery'],
            ['id'=>4,'medicine_id'=>5,'medicine'=>'Ceftriaxone','type'=>'Transfer Request','quantity'=>60,'facility'=>'Beira Regional','department'=>'Emergency','performed_by'=>'Dr. Lina Mavota','created_at'=>'2026-03-15 07:48:00','notes'=>'Cross-facility emergency request'],
        ];
    }

    public static function queueEvents(): array
    {
        return [
            ['id'=>1,'type'=>'Receiving','reference'=>'RCV-001','device'=>'Scanner 02','facility'=>'Beira Regional','payload'=>'3 Ceftriaxone cartons queued offline','status'=>'Pending','created_at'=>'2026-03-15 10:56:00'],
            ['id'=>2,'type'=>'Dispensing','reference'=>'DSP-019','device'=>'Ward Mobile 03','facility'=>'Nampula District','payload'=>'2 insulin vials issue awaiting sync','status'=>'Retrying','created_at'=>'2026-03-15 10:58:00'],
        ];
    }


    public static function departments(): array
    {
        return ['Pharmacy','Maternity','Emergency','Operating Theatre','Pediatrics','Outpatient','Laboratory','Central Store'];
    }

    public static function dataQuality(): array
    {
        return [
            ['label'=>'Data completeness','value'=>'94%','note'=>'12 medicine records missing secondary supplier or cold-chain field'],
            ['label'=>'Sync reliability','value'=>'97.8%','note'=>'Average queue flush under 2 hours across devices'],
            ['label'=>'Forecast coverage','value'=>'81%','note'=>'Remaining items need cleaner consumption history'],
            ['label'=>'Audit logging','value'=>'100%','note'=>'All critical actions tracked with actor and timestamp'],
        ];
    }

    public static function userDirectory(): array
    {
        return [
            ['id'=>1,'name'=>'Judercio Nhauche','email'=>'admin@alkelink.org','role'=>'Super Admin','facility'=>'Maputo Central','status'=>'Active'],
            ['id'=>2,'name'=>'Anabela Mucavele','email'=>'pharmacist@alkelink.org','role'=>'Pharmacist','facility'=>'Maputo Central','status'=>'Active'],
            ['id'=>3,'name'=>'Carlos Sitoe','email'=>'procurement@alkelink.org','role'=>'Procurement Officer','facility'=>'Maputo Central','status'=>'Active'],
            ['id'=>4,'name'=>'Dr. Lina Mavota','email'=>'admin.hospital@alkelink.org','role'=>'Hospital Administrator','facility'=>'Maputo Central','status'=>'Active'],
            ['id'=>5,'name'=>'Mateus Cossa','email'=>'auditor@alkelink.org','role'=>'Auditor','facility'=>'Maputo Central','status'=>'Active'],
            ['id'=>6,'name'=>'Celina Uamusse','email'=>'store@alkelink.org','role'=>'Store Manager','facility'=>'Beira Regional','status'=>'Active'],
        ];
    }

    public static function permissionMatrix(): array
    {
        return [
            ['capability'=>'View stock & dashboards','Super Admin'=>'Yes','Hospital Administrator'=>'Yes','Pharmacist'=>'Yes','Procurement Officer'=>'Yes','Store Manager'=>'Yes','Clinician'=>'Limited','Auditor'=>'Read only'],
            ['capability'=>'Receive / dispense / transfer stock','Super Admin'=>'Yes','Hospital Administrator'=>'Approve','Pharmacist'=>'Yes','Procurement Officer'=>'No','Store Manager'=>'Yes','Clinician'=>'Request only','Auditor'=>'No'],
            ['capability'=>'Approve procurement','Super Admin'=>'Yes','Hospital Administrator'=>'Yes','Pharmacist'=>'Recommend','Procurement Officer'=>'Prepare','Store Manager'=>'No','Clinician'=>'No','Auditor'=>'No'],
            ['capability'=>'View finance & supplier score','Super Admin'=>'Yes','Hospital Administrator'=>'Yes','Pharmacist'=>'Limited','Procurement Officer'=>'Yes','Store Manager'=>'Limited','Clinician'=>'No','Auditor'=>'Read only'],
            ['capability'=>'User / role management','Super Admin'=>'Yes','Hospital Administrator'=>'Scoped','Pharmacist'=>'No','Procurement Officer'=>'No','Store Manager'=>'No','Clinician'=>'No','Auditor'=>'No'],
            ['capability'=>'Audit & exception logs','Super Admin'=>'Yes','Hospital Administrator'=>'Yes','Pharmacist'=>'Own actions','Procurement Officer'=>'Own actions','Store Manager'=>'Own actions','Clinician'=>'No','Auditor'=>'Yes'],
        ];
    }

}
