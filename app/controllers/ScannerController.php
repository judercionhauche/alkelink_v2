<?php
namespace App\Controllers;

class ScannerController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();
        $this->render('scanner/index', [], 'Scanner');
    }
}
