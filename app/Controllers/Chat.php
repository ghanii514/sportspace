<?php

namespace App\Controllers;

use App\Models\MessageModel;

class Chat extends BaseController
{
    public function index()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $msgModel = new MessageModel();
        
        $data = [
            'title' => 'Chat Pihak Lapangan',
            // Ambil pesan user yang sedang login
            'messages' => $msgModel->getMessagesByUser(user()->id)
        ];

        return view('pages/chat_list', $data);
    }

    public function detail($id)
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $msgModel = new \App\Models\MessageModel();

        // 1. Cek dulu pesan yang diklik itu milik venue mana
        $clickedMsg = $msgModel->find($id);

        if (!$clickedMsg) {
            return redirect()->to('/chat');
        }

        $venueId = $clickedMsg['venue_id'];

        // 2. AMBIL SEMUA HISTORY CHAT dengan venue tersebut
        // Urutkan ASC (Ascending) biar chat lama di atas, chat baru di bawah
        $history = $msgModel
            ->select('messages.*, lapangan.nama as nama_lapangan, lapangan.image as gambar_lapangan')
            ->join('lapangan', 'lapangan.id = messages.venue_id')
            ->where('user_id', user()->id)
            ->where('venue_id', $venueId)
            ->orderBy('created_at', 'ASC') // <--- PENTING: Dari lama ke baru
            ->findAll();

        $data = [
            'title'   => 'Chat',
            'chats'   => $history, // Kirim semua data chat
            'venue_id'=> $venueId, // Kirim ID venue buat reply
            'header_info' => $history[0] // Ambil info nama/foto dari data pertama
        ];

        return view('pages/chat_detail', $data);
    }

    public function send()
    {
        if (!logged_in()) {
            return $this->response->setJSON(['status' => 'error']);
        }

        $msgModel = new \App\Models\MessageModel();

        $msgModel->save([
            'user_id'   => user()->id,
            'venue_id'  => $this->request->getPost('venue_id'),
            'message'   => $this->request->getPost('message'),
            'sender'    => 'user' // <--- PENTING: Tandai ini chat dari user
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }

}