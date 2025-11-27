<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PromoModel;
use CodeIgniter\HTTP\ResponseInterface;

class Promo extends BaseController
{
    public function __construct() {
        $this->PromoModel = new PromoModel();
    }
    public function showPromo()
    {
        //
        $data['promo'] = $this->PromoModel->findAll();
        return view('promo/index' , $data);
    }
}
