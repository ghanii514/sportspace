<?php

namespace App\Controllers;

class Mitra extends BaseController
{
    public function index()
    {
        // Pengecekan Login (Nanti bisa ditambah filter role:mitra)
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Dashboard Mitra',
            'user'  => user(),
            // Data Dummy untuk Statistik
            'income_month' => 4500000, // Rp 4.5jt
            'total_bookings' => 24,
            'rating' => 4.8,
            'recent_bookings' => [
                [
                    'nama' => 'Budi Santoso',
                    'lapangan' => 'Lapangan Futsal A',
                    'tanggal' => '10 Des 2024',
                    'jam' => '19:00 - 20:00',
                    'status' => 'paid'
                ],
                [
                    'nama' => 'Siti Aminah',
                    'lapangan' => 'Lapangan Basket Indoor',
                    'tanggal' => '11 Des 2024',
                    'jam' => '16:00 - 18:00',
                    'status' => 'pending'
                ]
            ]
        ];

        return view('mitra/dashboard', $data);
    }
}