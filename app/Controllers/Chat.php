<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ChatRoomModel;
use App\Models\ChatMessageModel;
use App\Models\OwnerModel;

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
    }

    // ==========================================
    // 1. HALAMAN PILIH OWNER (ENTRY POINT)
    // ==========================================
    public function selectOwner()
    {
        // Menampilkan daftar owner/lapangan yang bisa dichat
        $data['owners'] = $this->ownerModel
            ->select('owners.id, users.username, owners.lapangan, owners.photo, lapangan.nama AS nama_lapangan, lapangan.image AS lapangan_foto')
            ->join('users', 'users.id = owners.user_id')
            ->join('lapangan', 'lapangan.id = owners.lapangan')
            ->findAll();
            
        return view('pages/select_owner', $data);
    }

    // ==========================================
    // 2. MULAI CHAT (MEMBUKA ROOM)
    // ==========================================
    public function startChat($ownerId)
    {
        // Pastikan User Login
        if (!user()) {
            return redirect()->to('/login');
        }

        $userId = user()->id; 

        // A. Cek apakah Room sudah ada (User <-> Owner)
        $existingRoom = $this->roomModel
            ->where('owner_id', $ownerId)
            ->where('user_id', $userId)
            ->first();

        // B. Logika Get or Create Room
        if ($existingRoom) {
            $roomId = $existingRoom['id'];
        } else {
            // Buat Room Baru
            $this->roomModel->insert([
                'owner_id'   => $ownerId,
                'user_id'    => $userId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $roomId = $this->roomModel->getInsertID();
        }

        // C. Ambil Data Owner untuk Header Chat
        // Join ke tabel users untuk dapat nama & foto profil
        $ownerData = $this->ownerModel
            ->select('owners.id, owners.user_id, users.username, users.profile_picture')
            ->join('users', 'users.id = owners.user_id')
            ->where('owners.id', $ownerId)
            ->first();

        // D. Siapkan Data untuk View
        $data = [
            'title'  => 'Chatting',
            'roomId' => $roomId,    // <--- PENTING: Dikirim untuk AJAX
            'owner'  => $ownerData, // <--- PENTING: Dikirim untuk Header
        ];

        // Load View (Sesuaikan nama file view Anda, misal: user/chat_view)
        return view('pages/chat_list', $data);
    }

    // ==========================================
    // 3. API: AMBIL PESAN (AJAX POLLING)
    // ==========================================
    public function apiGetMessages($roomId)
    {
        // Validasi Login
        if (!user()) {
            return $this->response->setJSON([]);
        }

        // Ambil pesan urut dari yang terlama ke terbaru
        $messages = $this->chatMessageModel
            ->where('room_id', $roomId)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        $data = [];
        foreach ($messages as $msg) {
            $data[] = [
                'id'      => $msg['id'],
                'message' => $msg['message'],
                'type'    => $msg['type'], // 'user' atau 'owner' (dari database)
                'time'    => date('H:i', strtotime($msg['created_at']))
            ];
        }

        return $this->response->setJSON($data);
    }

    // ==========================================
    // 4. API: KIRIM PESAN (AJAX POST)
    // ==========================================
    public function send()
    {
        // Validasi Request AJAX & Login
        if (!user()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        // Ambil Data dari FormData (Input Hidden di View)
        $roomId   = $this->request->getPost('room_id'); 
        $ownerId  = $this->request->getPost('owner_id'); 
        $message  = $this->request->getPost('message');
        $senderId = user()->id;

        // Validasi Input Kosong
        if (empty(trim($message)) || empty($roomId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }

        // Insert ke Database
        $this->chatMessageModel->insert([
            'room_id'     => $roomId,
            'type'        => 'user', // Karena yang ngirim User
            'message'     => $message,
            'sender_id'   => $senderId,
            'receiver_id' => $ownerId,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }
}