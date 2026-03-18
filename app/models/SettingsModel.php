<?php
namespace App\Models;

class SettingsModel extends BaseModel
{
    public function all(): array
    {
        return ['settings' => DemoData::settings()];
    }
}
