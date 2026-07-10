<?php namespace App\Models;

use CodeIgniter\Model;

class AboutModel extends Model
{
    protected $table = 'about';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['visi', 'misi', 'tujuan'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
