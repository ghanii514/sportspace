<?php

namespace App\Controllers;

use App\Models\ChatMessageModel;
use App\Models\FieldModel;

class OwnerChatController extends BaseController
{
    protected $chatModel;

    public function __construct()
    {
        $this->chatModel = new ChatMessageModel();
    }

    public function index($active_room_id = null)
    {
        $ownerId = user()->id;
        $fieldModel = new FieldModel();

        $venues = $fieldModel
            ->where('owner_id', $ownerId)
            ->findAll();
        $venueNames = !empty($venues) ? implode(', ', array_column($venues, 'nama')) : '-';
        $venueImage = !empty($venues) ? $venues[0]['image'] : 'default.jpg';

        // 1. DAFTAR CHAT (SIDEBAR)
        $rawChatList = $this->getChatListRaw($ownerId);

        if ($active_room_id === null && !empty($rawChatList)) {
            $active_room_id = $rawChatList[0]['room_id'];
        }

        $chatList = [];
        foreach ($rawChatList as $chat) {
            // Logika: Jika pengirim adalah Owner, maka identitas User ada di u_target.
            // Jika pengirim adalah User, maka identitas User ada di u_sender.
            $isOwnerSender = ($chat['sender_id'] == $ownerId);
            $userName      = $isOwnerSender ? $chat['target_name'] : $chat['sender_name'];
            $userPic       = $isOwnerSender ? $chat['target_pic'] : $chat['sender_pic'];

            $chatList[] = [
                'id'           => $chat['room_id'],
                'name'         => $userName,
                'venue'        => '',
                'last_message' => $chat['message'],
                'time'         => strtotime($chat['created_at']),
                'avatar'       => '/img/users/' . ($userPic ?? 'default.png'),
                'active'       => ($chat['room_id'] == $active_room_id)
            ];
        }

        // 2. ISI PESAN (CHAT WINDOW)
        $messages = [];
        $activeUser = ['name' => 'Pilih Chat', 'venue' => '-', 'avatar' => '/img/users/default.png'];

        if ($active_room_id) {
            $rawMessages = $this->chatModel
                ->where('room_id', $active_room_id)
                ->orderBy('created_at', 'ASC')
                ->findAll();

        foreach ($rawMessages as $msg) {
            $messages[] = [
                'type' => ($msg['type'] === 'owner') ? 'admin' : 'user',
                'text' => $msg['message'],
                'time' => date('H:i', strtotime($msg['created_at']))
            ];
        }

            // Cari identitas lawan bicara (User) untuk header
            // Kita ambil satu pesan dari room ini yang bertipe 'user'
            $userData = $this->chatModel
                ->select('users.username, users.profile_picture')
                ->join('users', 'users.id = chat_messages.sender_id')
                ->where('room_id', $active_room_id)
                ->where('type', 'user')
                ->first();

            if ($userData) {
                $activeUser = [
                    'name'   => $userData['username'],
                    'venue'  => '',
                    'avatar' => '/img/users/' . ($userData['profile_picture'] ?? 'default.png')
                ];
            }
        }

        return view('owner/chat_view', [
            'chatList'    => $chatList,
            'venue_names' => $venueNames,
            'venue_image' => $venueImage,
            'activeChat' => [
                'user'     => $activeUser,
                'messages' => $messages
            ]
        ]);
    }

    public function apiGetMessages($roomId)
    {
        $ownerId = user()->id;
        
        // Ambil semua pesan di room ini, urutkan dari lama ke baru
        $rawMessages = $this->chatModel
            ->where('room_id', $roomId)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        $data = [];
        foreach ($rawMessages as $msg) {
            $data[] = [
                'id' => $msg['id'],
                'text' => $msg['message'],
                'type' => ($msg['sender_id'] == $ownerId) ? 'owner' : 'user',
                'time' => strtotime($msg['created_at'])
            ];
        }

        return $this->response->setJSON($data);
    }

    public function apiGetChatList()
    {
        $ownerId = user()->id;
        $rawChatList = $this->getChatListRaw($ownerId);

        $chatList = [];
        foreach ($rawChatList as $chat) {
            $isOwnerSender = ($chat['sender_id'] == $ownerId);
            $userName      = $isOwnerSender ? $chat['target_name'] : $chat['sender_name'];
            $userPic       = $isOwnerSender ? $chat['target_pic'] : $chat['sender_pic'];

            $chatList[] = [
                'room_id'      => (int) $chat['room_id'],
                'name'         => $userName,
                'venue'        => '',
                'last_message' => $chat['message'],
                'time'         => strtotime($chat['created_at']),
                'avatar'       => '/img/users/' . ($userPic ?? 'default.png'),
            ];
        }

        return $this->response->setJSON($chatList);
    }

    private function getChatListRaw($ownerId)
    {
        return $this->chatModel
            ->select('chat_messages.*, 
                      u_sender.username as sender_name, u_sender.profile_picture as sender_pic,
                      u_target.username as target_name, u_target.profile_picture as target_pic')
            ->join('users u_sender', 'u_sender.id = chat_messages.sender_id')
            ->join('users u_target', 'u_target.id = chat_messages.receiver_id')
            ->whereIn('chat_messages.id', function($builder) use ($ownerId) {
                return $builder->select('MAX(id)')
                               ->from('chat_messages')
                               ->where('receiver_id', $ownerId)
                               ->orWhere('sender_id', $ownerId)
                               ->groupBy('room_id');
            })
            ->orderBy('chat_messages.created_at', 'DESC')
            ->findAll();
    }

    private function formatTime($datetime)
    {
        $timestamp = strtotime($datetime);
        return (date('Y-m-d', $timestamp) == date('Y-m-d')) 
               ? date('H:i', $timestamp) 
               : date('d/m/y', $timestamp);
    }

  public function send()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setBody('Bad Request');
        }

        $chatMessageModel = new ChatMessageModel();
        
        $roomId  = $this->request->getPost('room_id');
        $message = $this->request->getPost('message');
        $ownerId = user()->id;

        if (!$roomId || empty(trim($message))) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pesan kosong']);
        }

        $existingChat = $chatMessageModel->where('room_id', $roomId)->first();
        if ($existingChat) {
            $receiverId = ($existingChat['sender_id'] == $ownerId) ? $existingChat['receiver_id'] : $existingChat['sender_id'];
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Room tidak valid']);
        }

        $chatMessageModel->insert([
            'room_id'     => $roomId,
            'type'        => 'owner',
            'message'     => $message,
            'sender_id'   => $ownerId,
            'receiver_id' => $receiverId,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }
}