<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\FieldModel;

class Owner extends BaseController
{
    public function index()
    {
        
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $bookingModel = new BookingModel();
        $fieldModel = new FieldModel();
        $db = \Config\Database::connect();

        $ownerId = user_id();

        $venues = $fieldModel
            ->where('owner_id', $ownerId)
            ->findAll();
        $venueNames = !empty($venues) ? implode(', ', array_column($venues, 'nama')) : '-';
        $venueImage = !empty($venues) ? $venues[0]['image'] : 'default.jpg';

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
            'venue_names' => $venueNames,
            'venue_image' => $venueImage,
            'total_booking' => $totalBooking,
            'need_confirm' => $needConfirm,
            'income' => $income,
            'recent' => $recent
        ];

        return view('owner/dashboard', $data);
    }

    public function bookings()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $bookingModel = new BookingModel();
        $fieldModel = new FieldModel();

        $ownerId = user_id();

        $venues = $fieldModel
            ->where('owner_id', $ownerId)
            ->findAll();
        $venueNames = !empty($venues) ? implode(', ', array_column($venues, 'nama')) : '-';
        $venueImage = !empty($venues) ? $venues[0]['image'] : 'default.jpg';

        $bookings = $bookingModel
            ->select('booking.*, users.username as penyewa, lapangan.nama as nama_lapangan, lapangan.owner_id')
            ->join('users', 'users.id = booking.user_id')
            ->join('lapangan', 'lapangan.id = booking.venue_id')
            ->where('lapangan.owner_id', $ownerId)
            ->orderBy('booking.id', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Daftar Booking Masuk',
            'user' => user(),
            'venue_names' => $venueNames,
            'venue_image' => $venueImage,
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