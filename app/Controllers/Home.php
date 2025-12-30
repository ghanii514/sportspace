<?php namespace App\Controllers;

use App\Models\FieldModel;
use App\Models\PromoModel;

class Home extends BaseController
{

    public function index()
    {
        
        if (logged_in()) {
            
            if (in_groups('admin')) {
                return redirect()->to('/admin');
            }

            
            if (in_groups('mitra')) {
                return redirect()->to('/owner');
            }

            
        }

        $fieldModel = new FieldModel(); 
        $promoModel = new PromoModel();

        $data = [
            'title'  => 'Home | SportSpace',
            'fields' => $fieldModel->paginate(6), 
            'pager'  => $fieldModel->pager, 
            'promos' => $promoModel->orderBy('id', 'DESC')->findAll(3)
        ];
        
        return view('pages/home', $data);
    }

    public function filter()
    {
        $route = $this->request->getGet('filter');
        $field = new FieldModel();
        $promo = new PromoModel();
        
        $data = [
            'fields' => $field->like('kategori', $route)->paginate(6), 
            'pager'  => $field->pager,
            'promos' => $promo->orderBy('id', 'DESC')->findAll(3)
        ];

        return view('pages/home', $data);
    }
}