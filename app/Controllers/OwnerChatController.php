<?php

namespace App\Controllers;

class OwnerChatController extends BaseController
{
    public function index()
    {
        // Data Dummy untuk Profil di Sidebar
        $data['profile'] = [
            'name' => 'Budi Santoso',
            'role' => 'Owner Admin Chat',
            'avatar' => 'https://i.pravatar.cc/150?img=3' // Gambar placeholder
        ];

        // Data Dummy untuk Daftar Chat (Kolom Tengah)
        $data['chatList'] = [
            [
                'id' => 1,
                'name' => 'Aamarl Nunung',
                'venue' => '[Futsal Kroya]',
                'last_message' => 'Baik, terima kasih infonya pak.',
                'time' => 'Yesterday',
                'avatar' => 'https://i.pravatar.cc/150?img=1',
                'active' => false
            ],
            [
                'id' => 2,
                'name' => 'Ahmad Zaky',
                'venue' => '[Futsal Kroya]',
                'last_message' => 'Halo min, bukti transfer sudah saya upload.',
                'time' => '11:28 AM',
                'avatar' => 'https://i.pravatar.cc/150?img=8',
                'active' => true // Ini chat yang sedang dibuka
            ],
            [
                'id' => 3,
                'name' => 'Aamarl Nunung',
                'venue' => '[Futsal Kroya]',
                'last_message' => 'Apakah jam 7 malam kosong?',
                'time' => 'Monday',
                'avatar' => 'https://i.pravatar.cc/150?img=1',
                'active' => false
            ],
        ];

        // Data Dummy untuk Isi Pesan (Kolom Kanan) - Ahmad Zaky
        $data['activeChat'] = [
            'user' => $data['chatList'][1], // Mengambil data Ahmad Zaky
            'messages' => [
                [
                    'type' => 'separator',
                    'text' => 'Today'
                ],
                [
                    'type' => 'admin', // Pesan dari Admin (Hijau, Kanan)
                    'text' => 'Sudah masuk kak. Status booking sudah saya ACC.',
                    'time' => '16:53 AM'
                ],
                [
                    'type' => 'user', // Pesan dari User (Putih, Kiri)
                    'text' => 'Halo min, bukti transfer sudah saya upload.',
                    'time' => '11:28 AM'
                ]
            ]
        ];

        return view('owner/chat_view', $data);
    }
}