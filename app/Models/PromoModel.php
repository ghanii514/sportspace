<?php namespace App\Models;

use CodeIgniter\Model;

class PromoModel extends Model
{
    protected $table = 'promo';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['promo', 'deskripsi', 'image', 'promo_code'];

    // Menggunakan timestamps
    protected $useTimestamps = true;
}