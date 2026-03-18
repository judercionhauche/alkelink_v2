<?php
namespace App\Models;

class AuditModel extends BaseModel
{
    public function all(): array
    {
        return ['logs' => DemoData::auditLogs()];
    }
}
