<?php 

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['username', 'email', 'password', 'verification_token', 'is_active', 'profile_picture'];

    // Menggunakan timestamps
    protected $useTimestamps = true;
}