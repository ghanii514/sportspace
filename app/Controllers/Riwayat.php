<?php

namespace App\Controllers;

class Riwayat extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            session()->setFlashdata('msg', 'Silakan login untuk melihat riwayat.');
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Riwayat Booking | SportSpace',
        ];

        return view('pages/riwayat', $data);
    }
}