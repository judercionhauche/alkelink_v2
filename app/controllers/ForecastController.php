<?php
namespace App\Controllers;

use App\Models\ForecastModel;

class ForecastController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();
        $model = new ForecastModel();
        $this->render('forecast/index', [
            'forecast' => $model->all(),
            'scenario' => $model->scenario(),
        ], 'Forecasting');
    }
}
