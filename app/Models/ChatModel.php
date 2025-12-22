<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model
{
    protected $table = 'chat';
    protected $primaryKey = 'id';
    protected $allowedFields = ['from_user' , 'pesan' , 'owner_id'];

    public function getAllPesan($owner_id){
        $this->select('chat.* , users.* , users.id AS user_id');
        $this->join('users' , 'users.id = chat.from_user');
        $this->where('owner_id' , $owner_id);
        return $this->findAll();
    }

}