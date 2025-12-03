<?php namespace App\Controllers;

use App\Models\FieldModel;
use App\Models\PromoModel;

class Home extends BaseController
{
    public function index()
    {
        $fieldModel = new \App\Models\FieldModel(); 
        $promoModel = new \App\Models\PromoModel();

        $data = [
            'title'  => 'Home | SportSpace',
            'fields' => $fieldModel->paginate(6), 
            'pager'  => $fieldModel->pager, 
            'promos' => $promoModel->orderBy('created_at', 'DESC')->findAll(3)
        ];
        
        return view('pages/home', $data);
    }
}