<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\ChatRoomModel;
use App\Models\ChatMessageModel;
use App\Models\UserModel;

class ChatController extends BaseController
{
    protected $roomModel;
    protected $msgModel;
    protected $userModel;

    public function __construct()
    {
        $this->roomModel = new ChatRoomModel();
        $this->msgModel  = new ChatMessageModel();
        $this->userModel = new UserModel();
    }

        public function index()
    {
        $ownerId = user()->id;

        $chatUsers = $this->roomModel
            ->select('chat_rooms.id AS room_id, users.id AS user_id, users.username, users.profile_picture,
                      (SELECT created_at FROM chat_messages WHERE room_id = chat_rooms.id ORDER BY id DESC LIMIT 1) 
                      AS last_message_time')
            ->join('users', 'users.id = chat_rooms.user_id')
            ->where('chat_rooms.owner_id', $ownerId)
            ->orderBy('last_message_time', 'DESC')
            ->findAll();

        return view('owner/chat/index', [
            'chat_users' => $chatUsers,
            'active_user' => null,
            'messages' => []
        ]);
    }

    
    public function room($userId)
    {
        $ownerId = user()->id;

       
        $room = $this->roomModel
            ->where([
                'user_id'  => $userId,
                'owner_id' => $ownerId
            ])->first();

        
        if (!$room) {
            $roomId = $this->roomModel->insert([
                'user_id' => $userId,
                'owner_id' => $ownerId
            ]);

            $room = $this->roomModel->find($roomId);
        }

        
        $user = $this->userModel->find($userId);

        
        $messages = $this->msgModel
            ->where('room_id', $room['id'])
            ->orderBy('id', 'ASC')
            ->findAll();

        
        $chatUsers = $this->roomModel
            ->select('chat_rooms.id AS room_id, users.id AS user_id, users.username, users.profile_picture,
                      (SELECT created_at FROM chat_messages WHERE room_id = chat_rooms.id ORDER BY id DESC LIMIT 1) 
                      AS last_message_time')
            ->join('users', 'users.id = chat_rooms.user_id')
            ->where('owner_id', $ownerId)
            ->orderBy('last_message_time', 'DESC')
            ->findAll();

        return view('owner/chat/index', [
            'chat_users' => $chatUsers,
            'active_user' => $user,
            'messages' => $messages,
            'room_id' => $room['id']
        ]);
    }

    
    public function send()
    {
        $roomId = $this->request->getPost('room_id');
        $message = $this->request->getPost('message');

        if (!$message) return redirect()->back();

        $this->msgModel->insert([
            'room_id' => $roomId,
            'sender'  => 'owner',
            'message' => $message
        ]);

        return redirect()->back();
    }
}
