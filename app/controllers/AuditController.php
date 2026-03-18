<?php
namespace App\Controllers;

use App\Models\AuditModel;

class AuditController extends BaseController
{
    public function index(): void
    {
        $this->requireRole(['Super Admin','Hospital Administrator','Auditor']);
        $model = new AuditModel();
        $this->render('audit/index', $model->all(), 'Audit Trail');
    }
}
