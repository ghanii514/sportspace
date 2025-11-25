<?php namespace App\Controllers;

// Import model
use App\Models\FieldModel;
use App\Models\PromoModel;

class Home extends BaseController
{
    public function index()
    {
        // 1. Inisialisasi kedua model
        $fieldModel = new FieldModel();
        $promoModel = new PromoModel(); // <-- TAMBAHKAN INI

        $data = [
            'title' => 'Home | SportSpace',
            'fields' => $fieldModel->findAll(), // Ambil semua data lapangan

            // --- TAMBAHKAN 2 BARIS INI ---
            // Ambil 3 promo terbaru
            'promos' => $promoModel->orderBy('created_at', 'DESC')->findAll(3) 
        ];

        return view('pages/home', $data);
    }
}