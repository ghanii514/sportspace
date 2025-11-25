<?php 

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    // Pastikan deleted_at TIDAK ADA di sini
    protected $allowedFields = ['username', 'email', 'password', 'verification_token', 'is_active', 'profile_picture'];

    protected $useTimestamps = true;

    // --- INI KUNCINYA: MATIKAN SOFT DELETES ---
    protected $useSoftDeletes = false; 
}