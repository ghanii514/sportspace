<?php namespace App\Controllers;

use App\Models\FieldModel;
use App\Models\PromoModel;

class Home extends BaseController
{

    public function index()
    {
        // LOGIKA REDIRECT USER BERDASARKAN ROLE (MYTH:AUTH)
        if (logged_in()) {
            // 1. Jika yang login adalah Admin Web -> Ke Dashboard Admin
            if (in_groups('admin')) {
                return redirect()->to('/admin');
            }

            // 2. Jika yang login adalah Mitra -> Ke Dashboard Mitra
            if (in_groups('mitra')) {
                return redirect()->to('/owner');
            }

            // 3. Jika User biasa, biarkan lanjut ke bawah (lihat Homepage)
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