<?php

namespace App\Models;

use CodeIgniter\Model;

class FieldModel extends Model
{
    protected $table = 'lapangan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['nama', 'deskripsi', 'alamat', 'harga', 'image', 'kategori', 'nomor_telepon'];

    protected $useTimestamps = true;
}
