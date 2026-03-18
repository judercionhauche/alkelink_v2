<?php
namespace App\Models;

class FacilitiesModel extends BaseModel
{
    public function all(): array
    {
        return [
            'facilities' => DemoData::facilities(),
            'syncQueue' => DemoData::syncQueue(),
            'forecast' => DemoData::forecast(),
        ];
    }
}
