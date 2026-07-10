<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\PromoModel;

class PromoApi extends BaseController
{
    public function index()
    {
        $model = new PromoModel();
        $promos = $model->orderBy('id', 'DESC')->findAll();

        return json_response($promos);
    }

    public function detail($id)
    {
        $model = new PromoModel();
        $promo = $model->find($id);

        if (!$promo) {
            return json_response(null, 404, 'Promo tidak ditemukan');
        }

        return json_response($promo);
    }
}
