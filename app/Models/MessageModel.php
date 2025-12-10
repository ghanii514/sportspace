<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'venue_id', 'message', 'created_at', 'sender'];

    public function getMessagesByUser($userId)
    {
        $subQuery = $this->db->table('messages')
                         ->select('MAX(id)')
                         ->where('user_id', $userId)
                         ->groupBy('venue_id');

        return $this->select('messages.*, lapangan.nama as nama_lapangan, lapangan.image as gambar_lapangan')
                    ->join('lapangan', 'lapangan.id = messages.venue_id')
                    ->whereIn('messages.id', $subQuery)
                    ->orderBy('messages.created_at', 'DESC') // Urutkan dari yang paling baru
                    ->findAll();
    }
}