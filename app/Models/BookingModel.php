<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'booking'; 
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'user_id', 
        'venue_id', 
        'name',          
        'booking_date',  
        'start_time',    
        'end_time',      
        'total_price',   
        'status',
        'pembayaran',
        'discount_amount', 
        'promo_code',
        'bukti_bayar'
    
    ];

    public function getBookingsLengkap($userId)
    {
        
        return $this->select('booking.*, users.username, users.email, lapangan.nama as nama_lapangan')
                    ->join('users', 'users.id = booking.user_id')
                    ->join('lapangan', 'lapangan.id = booking.venue_id')
                    ->where('booking.user_id' , $userId)
                    ->where('booking.status' , 'cancelled')
                    ->orWhere('booking.status' , 'pending')
                    ->orderBy('booking.id', 'DESC')
                    ->findAll();
    }
    public function getBookingsSuccess($userId)
    {
        
        return $this->select('booking.*, users.username, users.email, lapangan.nama as nama_lapangan')
                    ->join('users', 'users.id = booking.user_id')
                    ->join('lapangan', 'lapangan.id = booking.venue_id')
                    ->where('users.id' , $userId)
                    ->where('booking.status' , 'success')
                    ->orderBy('booking.id', 'DESC')
                    ->findAll();
    }
    public function getBooking($id)
    {
        
        return $this->select('booking.*, users.username, users.email, lapangan.nama as nama_lapangan')
                    ->join('users', 'users.id = booking.user_id')
                    ->join('lapangan', 'lapangan.id = booking.venue_id')
                    ->where('booking.id' , $id)
                    ->first();
    }
}