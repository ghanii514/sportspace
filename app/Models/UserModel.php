<?php

namespace App\Models;

use CodeIgniter\Model;
use Myth\Auth\Entities\User;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = ['username', 'email', 'password', 'verification_token', 'is_active', 'profile_picture'];

    protected $useTimestamps = true;

    protected $useSoftDeletes = false; 
    // Tambahkan 'profile_picture' ke dalam sini
    protected $allowedFields = [
        'email', 'username', 'password_hash', 'reset_hash', 'reset_at', 'reset_expires', 'activate_hash',
        'status', 'status_message', 'active', 'force_pass_reset', 'permissions', 'deleted_at',
        'profile_picture' // <--- WAJIB DITAMBAHKAN!
    ];

    protected $useTimestamps = true;
    protected $returnType = User::class; // Agar returnnya Object (user()->name)
}