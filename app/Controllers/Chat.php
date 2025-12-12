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
            ->join('lapangan' , 'lapangan.id = owners.lapangan')
            ->findAll();
        return view('pages/select_owner', $data);
    }

    public function startChat($ownerId)
    {
        $userId = session()->get('id');
        $chatRoomModel = new ChatRoomModel();

        // cek apakah sudah ada room
        $room = $chatRoomModel
            ->where('user_id', $userId)
            ->where('owner_id', $ownerId)
            ->first();

        // jika belum ada → buat baru
        if (!$room) {
            $roomId = $chatRoomModel->insert([
                'user_id' => $userId,
                'owner_id' => $ownerId,
            ]);
        } else {
            $roomId = $room['id'];
        }

        return redirect()->to("/chat/rooms/".$roomId);
    }


    // =========================
    // 2. KIRIM PESAN DARI USER
    // =========================
    public function send()
    {
        $userId = user_id();
        $roomId = $this->request->getPost('room_id');
        $message = $this->request->getPost('message');

        if (!$roomId || !$message) {
            return redirect()->back();
        }

        // Simpan pesan
        $this->msgModel->insert([
            'room_id' => $roomId,
            'sender' => 'user',
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Update last message
        $this->roomModel->update($roomId, [
            'last_message' => $message,
            'last_message_time' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/user/chat');
    }
}
