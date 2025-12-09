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
        $data['promo'] = $this->PromoModel->findAll();
        return view('promo/index' , $data);
    }

    public function detail($id)
    {
        $model = new PromoModel();
        
        // Ambil data promo berdasarkan ID
        $data['promo'] = $model->find($id);

        if (empty($data['promo'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Promo tidak ditemukan.");
        }

        return view('promo/promo_detail', $data);
    }
}

