<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ChatRoomModel;
use App\Models\ChatMessageModel;
use App\Models\OwnerModel;

class Chat extends BaseController
{
    protected $roomModel;
    protected $msgModel;

    public function __construct()
    {
        $this->roomModel = new ChatRoomModel();
        $this->msgModel = new ChatMessageModel();
    }

    // =========================
    // 1. HALAMAN CHAT UTAMA
    // =========================
    public function index()
    {
        $userId = user_id();

        // Cek apakah user sudah punya room dengan owner
        $room = $this->roomModel
            ->where('user_id', $userId)
            ->first();

        if (!$room) {
            // Jika tidak ada room, buat baru
            $this->roomModel->insert([
                'user_id' => $userId,
                'owner_id' => 0, // owner default ID = 0 (jika banyak owner, sesuaikan)
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $room = $this->roomModel
                ->where('user_id', $userId)
                ->first();
        }

        // Ambil pesan dalam room ini
        $messages = $this->msgModel
            ->where('room_id', $room['id'])
            ->orderBy('created_at', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Chat Dengan Pemilik',
            'room' => $room,
            'messages' => $messages
        ];

        return view('pages/chat_list', $data);
    }

    public function selectOwner()
    {
        $ownerModel = new OwnerModel();
        $data['owners'] = $ownerModel
            ->select('owners.id, users.username, owners.lapangan, owners.photo , lapangan.nama AS nama_lapangan , lapangan.image AS lapangan_foto ')
            ->join('users', 'users.id = owners.user_id')
            ->join('lapangan', 'lapangan.id = owners.lapangan')
            ->findAll();
        return view('pages/select_owner', $data);
    }

    public function startChat($ownerId)
    {
        $roomModel = new ChatRoomModel();
        $ownerModel = new OwnerModel();
        $chatMessageModel = new ChatMessageModel();


        $full_data = $roomModel
            ->select('chat_rooms.* , owners.* , users.* , chat_rooms.id AS id_room')
            ->join('owners', 'owners.id = chat_rooms.owner_id')
            ->join('users', 'users.id = owners.user_id')
            ->where('chat_rooms.owner_id', $ownerId)
            ->where('chat_rooms.user_id', user()->id)
            ->first();

        $fullMessage = $chatMessageModel
            ->select('chat_messages.* , users.username , users.id AS user_id')
            ->join('users', 'users.id = chat_messages.sender_id')
            ->where('chat_messages.sender_id', user()->id)
            ->findAll();

        if (!$full_data) {
            $room = $roomModel->insert([
                'owner_id' => $ownerId,
                'user_id' => user()->id
            ]);

            if ($room){
                dd($room);
                    session()->set([
                    'room_id' => $room['id']
                ]);
            }
        }

        if ($full_data) {
            session()->set([
                'room_id' => $full_data['id_room']
            ]);
        }

        $data = [
            'messages' => $fullMessage,
            'owner' => $full_data
        ];

        // dd($full_data);

        // 7. Kembalikan view secara langsung
        return view('pages/chat_list', $data); // Sesuaikan path folder view Anda
    }


    // =========================
    // 2. KIRIM PESAN DARI USER
    // =========================
    public function send()
    {
        $chatMessageModel = new ChatMessageModel();
        $roomId = session()->get('room_id');
        $type = 'user';
        $message = $this->request->getPost('message');

        $sender = user()->id;
        $receiver = $this->request->getPost('owner_id');

        $chatMessageModel->insert([
            'room_id' => $roomId,
            'type' => $type,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s'),
            'sender_id' => $sender,
            'receiver_id' => $receiver
        ]);

        return redirect()->to('chat/start/' . $roomId);

    }
}
