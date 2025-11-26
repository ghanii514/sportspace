<?php namespace App\Controllers;

// Import model
use App\Models\FieldModel;
use App\Models\PromoModel;

class Home extends BaseController
{
    public function index()
    {
        $fieldModel = new \App\Models\FieldModel(); // Pastikan namespace model benar
        $promoModel = new \App\Models\PromoModel();

        $data = [
            'title'  => 'Home | SportSpace',
            // UBAH findAll() JADI paginate()
            // Angka 6 artinya menampilkan 6 lapangan per halaman
            'fields' => $fieldModel->paginate(6), 
            'pager'  => $fieldModel->pager, // Kirim objek pager ke view
            'promos' => $promoModel->orderBy('created_at', 'DESC')->findAll(3)
        ];
        
        return view('pages/home', $data);
    }
}