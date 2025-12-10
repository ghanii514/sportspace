<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'booking'; // Sesuai nama tabel
    protected $primaryKey = 'id';
    
    // SESUAIKAN DENGAN SCREENSHOT DATABASE KAMU
    protected $allowedFields = [
        'user_id', 
        'venue_id', 
        'name',          // Ada kolom name di screenshot
        'booking_date',  // Ganti tanggal -> booking_date
        'start_time',    // Ganti jam_mulai -> start_time
        'end_time',      // Ganti jam_selesai -> end_time
        'total_price',   // Ganti total_bayar -> total_price
        'status',
        'pembayaran',
        'discount_amount', 
        'promo_code'
    ];

    public function getBookingsLengkap()
    {
        // Select & Join tetap sama, tapi pastikan kolomnya benar
        return $this->select('booking.*, users.username, users.email, lapangan.nama as nama_lapangan')
                    ->join('users', 'users.id = booking.user_id')
                    ->join('lapangan', 'lapangan.id = booking.venue_id')
                    ->where('booking.status' , 'pending')
                    ->orderBy('booking.id', 'DESC')
                    ->findAll();
    }
    public function getBookingsSuccess()
    {
        // Select & Join tetap sama, tapi pastikan kolomnya benar
        return $this->select('booking.*, users.username, users.email, lapangan.nama as nama_lapangan')
                    ->join('users', 'users.id = booking.user_id')
                    ->join('lapangan', 'lapangan.id = booking.venue_id')
                    ->where('booking.status' , 'success')
                    ->orderBy('booking.id', 'DESC')
                    ->findAll();
    }
    public function getBooking($id)
    {
        // Select & Join tetap sama, tapi pastikan kolomnya benar
        return $this->select('booking.*, users.username, users.email, lapangan.nama as nama_lapangan')
                    ->join('users', 'users.id = booking.user_id')
                    ->join('lapangan', 'lapangan.id = booking.venue_id')
                    ->where('booking.id' , $id)
                    ->first();
    }
}