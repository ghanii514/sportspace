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

    // LIST USER CHAT
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

    // OPEN CHAT WITH SPECIFIC USER
    public function room($userId)
    {
        $ownerId = user()->id;

        // Cek apakah room sudah ada
        $room = $this->roomModel
            ->where([
                'user_id'  => $userId,
                'owner_id' => $ownerId
            ])->first();

        // Jika belum ada, buat baru
        if (!$room) {
            $roomId = $this->roomModel->insert([
                'user_id' => $userId,
                'owner_id' => $ownerId
            ]);

            $room = $this->roomModel->find($roomId);
        }

        // Ambil user
        $user = $this->userModel->find($userId);

        // Ambil pesan
        $messages = $this->msgModel
            ->where('room_id', $room['id'])
            ->orderBy('id', 'ASC')
            ->findAll();

        // Ambil daftar chat user
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

    // SEND MESSAGE
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
