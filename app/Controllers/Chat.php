<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ChatRoomModel;
use App\Models\ChatMessageModel;
use App\Models\OwnerModel;
use Myth\Auth\Models\UserModel;

class Chat extends BaseController
{
    protected $roomModel;
    protected $chatMessageModel;
    protected $ownerModel;

    public function __construct()
    {
        $this->roomModel = new ChatRoomModel();
        $this->chatMessageModel = new ChatMessageModel();
        $this->ownerModel = new OwnerModel();

        $this->userModel = new UserModel();
    }

    public function selectOwner()
    {
        $data['owners'] = $this->userModel
            ->select('users.* , lapangan.* , users.id AS user_id , lapangan.id AS id_lapangan')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->join('lapangan' , 'lapangan.owner_id = users.id')
            ->where('auth_groups_users.group_id', 2)
            ->findAll();

        $data['title'] = 'Pilih Owner untuk Chat';


        return view('pages/select_owner', $data);
    }

    public function startChat($ownerId)
    {
        if (!user()) {
            return redirect()->to('/login');
        }

        $userId = user()->id;

        $existingRoom = $this->roomModel
            ->where('owner_id', $ownerId)
            ->where('user_id', $userId)
            ->first();

        if ($existingRoom) {
            $roomId = $existingRoom['id'];
        } else {
            $this->roomModel->insert([
                'owner_id' => $ownerId,
                'user_id' => $userId,
            ]);
            $roomId = $this->roomModel->getInsertID();
        }

        $ownerData = $this->roomModel
            ->join('users', 'users.id = chat_rooms.owner_id')
            ->where('users.id', $ownerId)
            ->first();

        $data = [
            'title' => 'Chatting',
            'roomId' => $roomId,   
            'owner' => $ownerData, 
        ];

        return view('pages/chat_list', $data);
    }

    public function apiGetMessages($roomId)
    {
        if (!user()) {
            return $this->response->setJSON([]);
        }

        
        $messages = $this->chatMessageModel
            ->where('room_id', $roomId)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        $data = [];
        foreach ($messages as $msg) {
            $data[] = [
                'id' => $msg['id'],
                'message' => $msg['message'],
                'type' => $msg['type'], 
                'time' => date('H:i', strtotime($msg['created_at']))
            ];
        }

        return $this->response->setJSON($data);
    }

    // ==========================================
    // 4. API: KIRIM PESAN (AJAX POST)
    // ==========================================
    public function send()
    {
       
        if (!user()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        
        $roomId = $this->request->getPost('room_id');
        $ownerId = $this->request->getPost('owner_id');
        $message = $this->request->getPost('message');
        $senderId = user()->id;

        
        if (empty(trim($message)) || empty($roomId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }

        
        $this->chatMessageModel->insert([
            'room_id' => $roomId,
            'type' => 'user', 
            'message' => $message,
            'sender_id' => $senderId,
            'receiver_id' => $ownerId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }
}