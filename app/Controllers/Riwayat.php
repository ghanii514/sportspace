<?php

namespace App\Controllers;

class Riwayat extends BaseController
{
    public function index()
    {
        // 1. Cek apakah user sudah login
        if (!session()->get('logged_in')) {
            session()->setFlashdata('msg', 'Silakan login untuk melihat riwayat.');
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Riwayat Booking | SportSpace',
            // Nanti di sini kita bisa ambil data booking dari database
        ];

        return view('pages/riwayat', $data);
    }
}