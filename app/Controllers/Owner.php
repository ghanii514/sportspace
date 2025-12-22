<?php

namespace App\Controllers;

use App\Models\BookingModel;

class Owner extends BaseController
{
    public function index()
    {
        // 1. Cek Login & Role (Pastikan dia Owner)
        if (!logged_in()) { return redirect()->to('/login'); }
        // if (!in_groups('owner')) { return redirect()->to('/'); } // Aktifkan kalo udah setup role

        $bookingModel = new BookingModel();
        $db = \Config\Database::connect();
        
        // Ambil ID User yang sedang login
        $ownerId = user_id(); 

        // --- HITUNG-HITUNGAN RINGKASAN ---

        // 1. Total Booking Masuk (Semua status)
        $totalBooking = $bookingModel
            ->join('lapangan', 'lapangan.id = booking.venue_id')
            ->where('lapangan.owner_id', $ownerId) // Filter punya dia aja
            ->countAllResults();

        // 2. Booking Menunggu Konfirmasi (Pending & Sudah Upload Bukti)
        // Ini penting biar Owner tau ada kerjaan nge-ACC
        $needConfirm = $bookingModel
            ->join('lapangan', 'lapangan.id = booking.venue_id')
            ->where('lapangan.owner_id', $ownerId)
            ->where('booking.status', 'pending')
            ->where('booking.bukti_bayar IS NOT NULL') // Yang udah upload bukti
            ->countAllResults();

        // 3. Pendapatan Simpel (Yang statusnya Success/Paid)
        $incomeQuery = $db->table('booking')
            ->selectSum('total_price')
            ->join('lapangan', 'lapangan.id = booking.venue_id')
            ->where('lapangan.owner_id', $ownerId)
            ->where('booking.status', 'success')
            ->get()->getRow();
        $income = $incomeQuery->total_price ?? 0;

        // 4. List Booking Terbaru (Buat tabel di dashboard)
        $recent = $bookingModel
            ->select('booking.*, users.username as penyewa, lapangan.nama as lapangan')
            ->join('users', 'users.id = booking.user_id')
            ->join('lapangan', 'lapangan.id = booking.venue_id')
            ->where('lapangan.owner_id', $ownerId)
            ->orderBy('booking.id', 'DESC')
            ->limit(5)
            ->findAll();

        $data = [
            'title' => 'Dashboard Pemilik Lapangan',
            'user'  => user(),
            'total_booking' => $totalBooking,
            'need_confirm'  => $needConfirm,
            'income'        => $income,
            'recent'        => $recent
        ];

        return view('owner/dashboard', $data);
    }
}