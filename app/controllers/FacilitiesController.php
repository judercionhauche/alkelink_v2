<?php
namespace App\Controllers;

use App\Models\FacilitiesModel;

class FacilitiesController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();
        $model = new FacilitiesModel();
        $this->render('facilities/index', $model->all(), 'Facilities');
    }
}
