<?php
namespace App\Models;

class ForecastModel extends BaseModel
{
    public function all(): array
    {
        return $this->sessionCollection('forecast', fn() => DemoData::forecast());
    }

    public function scenario(): array
    {
        return [
            ['label'=>'7 days','buffer'=>1.00,'stockout_count'=>3,'spend'=>145000],
            ['label'=>'14 days','buffer'=>1.12,'stockout_count'=>5,'spend'=>188000],
            ['label'=>'30 days','buffer'=>1.18,'stockout_count'=>7,'spend'=>251000],
            ['label'=>'60 days','buffer'=>1.25,'stockout_count'=>9,'spend'=>396000],
        ];
    }
}
