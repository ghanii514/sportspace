<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'venue_id', 'booking_date', 'start_time', 'end_time', 'total_price', 'status'];
}