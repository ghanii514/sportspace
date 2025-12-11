<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\FieldModel;

class Mitra extends BaseController
{
    // =================================================================
    // 1. DASHBOARD UTAMA MITRA
    // =================================================================
    public function index()
    {
        // Cek Login
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        // (Opsional) Cek apakah user ini benar-benar grup Mitra
        // if (!in_groups('mitra')) { return redirect()->to('/'); }

        $bookingModel = new BookingModel();
        $db = \Config\Database::connect();
        
        // AMBIL ID USER YANG SEDANG LOGIN (ID Mitra)
        $mitraId = user_id(); 

        // ---------------------------------------------------------
        // A. HITUNG PENDAPATAN BULAN INI (Hanya punya Mitra ini)
        // ---------------------------------------------------------
        $bulanIni = date('m');
        $tahunIni = date('Y');

        $incomeQuery = $db->table('bookings')
            ->selectSum('total_price')
            ->join('lapangan', 'lapangan.id = bookings.venue_id') // Kita join ke tabel lapangan
            ->where('bookings.status', 'success')                 // Hanya yang lunas
            ->where('lapangan.owner_id', $mitraId)                // <--- FILTER WAJIB: Hanya lapangan milik dia
            ->where('MONTH(booking_date)', $bulanIni)
            ->where('YEAR(booking_date)', $tahunIni)
            ->get()->getRow();
            
        $incomeMonth = $incomeQuery->total_price ?? 0;

        // ---------------------------------------------------------
        // B. HITUNG TOTAL BOOKING (Milik Mitra ini)
        // ---------------------------------------------------------
        $totalBookings = $bookingModel
            ->join('lapangan', 'lapangan.id = bookings.venue_id')
            ->where('lapangan.owner_id', $mitraId)                // <--- FILTER WAJIB
            ->countAllResults();

        // ---------------------------------------------------------
        // C. AMBIL RECENT BOOKINGS (List Tabel Dashboard)
        // ---------------------------------------------------------
        $recentBookings = $bookingModel
            ->select('bookings.*, users.username as nama_penyewa, users.email, lapangan.nama as nama_lapangan')
            ->join('users', 'users.id = bookings.user_id')
            ->join('lapangan', 'lapangan.id = bookings.venue_id')
            ->where('lapangan.owner_id', $mitraId)                // <--- FILTER WAJIB
            ->orderBy('bookings.id', 'DESC')
            ->limit(5)
            ->findAll();

        $data = [
            'title'           => 'Dashboard Mitra',
            'user'            => user(), // Data user login buat profil di sidebar
            'income_month'    => $incomeMonth,
            'total_bookings'  => $totalBookings,
            'rating'          => 4.8, // Masih dummy (hardcode)
            'recent_bookings' => $recentBookings
        ];

        return view('mitra/index', $data);
    }

    // =================================================================
    // 2. HALAMAN KELOLA LAPANGAN (LIST LAPANGAN SAYA)
    // =================================================================
    public function fields()
    {
        if (!logged_in()) { return redirect()->to('/login'); }
        
        $fieldModel = new FieldModel();
        
        // Ambil ID Mitra
        $mitraId = user_id();

        // Ambil lapangan yang owner_id-nya SAMA dengan ID Mitra ini
        // Jadi dia gak bisa lihat lapangan orang lain
        $myFields = $fieldModel->where('owner_id', $mitraId)->findAll();

        $data = [
            'title'  => 'Lapangan Saya',
            'user'   => user(),
            'fields' => $myFields
        ];

        return view('mitra/fields_list', $data);
    }
}