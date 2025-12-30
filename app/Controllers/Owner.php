<?php

namespace App\Controllers;

use App\Models\BookingModel;

class Owner extends BaseController
{
    public function index()
    {
        
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $bookingModel = new BookingModel();
        $db = \Config\Database::connect();

        $ownerId = user_id();


        $totalBooking = $bookingModel
            ->join('lapangan', 'lapangan.id = booking.venue_id')
            ->where('lapangan.owner_id', $ownerId) 
            ->countAllResults();

        $needConfirm = $bookingModel
            ->join('lapangan', 'lapangan.id = booking.venue_id')
            ->where('lapangan.owner_id', $ownerId)
            ->where('booking.status', 'pending')
            ->where('booking.bukti_bayar IS NOT NULL') 
            ->countAllResults();

        $incomeQuery = $db->table('booking')
            ->selectSum('total_price')
            ->join('lapangan', 'lapangan.id = booking.venue_id')
            ->where('lapangan.owner_id', $ownerId)
            ->where('booking.status', 'success')
            ->get()->getRow();
        $income = $incomeQuery->total_price ?? 0;

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
            'user' => user(),
            'total_booking' => $totalBooking,
            'need_confirm' => $needConfirm,
            'income' => $income,
            'recent' => $recent
        ];

        return view('owner/dashboard', $data);
    }

    public function bookings()
    {
        // 1. Cek Login
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $bookingModel = new BookingModel();

        // 2. Ambil ID Owner yang sedang login
        $ownerId = user_id();

        // 3. Query Database
        // Logika: Ambil Booking -> Sambungkan ke Lapangan -> Cek apakah Lapangan itu punya Owner ID ini
        $bookings = $bookingModel
            ->select('booking.*, users.username as penyewa, lapangan.nama as nama_lapangan, lapangan.owner_id')
            ->join('users', 'users.id = booking.user_id')          // Ambil data User penyewa
            ->join('lapangan', 'lapangan.id = booking.venue_id')   // Ambil data Lapangan (venue_id di booking = id di lapangan)
            ->where('lapangan.owner_id', $ownerId)                 // <--- INI KUNCINYA (Filter hanya lapangan milik owner ini)
            ->orderBy('booking.id', 'DESC')                        // Urutkan dari booking terbaru
            ->findAll();

        $data = [
            'title' => 'Daftar Booking Masuk',
            'user' => user(),
            'bookings' => $bookings
        ];

        return view('owner/bookings', $data);
    }

    public function approve($idbooking){
        $model = new BookingModel();
        $model->update($idbooking , [
            'status' => 'success'
        ]);
        return redirect()->to('owner/bookings');
    }

    public function reject($idbooking){
        $model = new BookingModel();
        $model->update($idbooking , [
            'status' => 'cancelled'
        ]);
        return redirect()->to('owner/bookings');
    }

}