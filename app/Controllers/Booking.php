<?php

namespace App\Controllers;

use App\Models\BookingModel;

class Booking extends BaseController
{
    public function detail($id_lapangan)
    {
        // Simulasi data lapangan (Nanti ganti ini dengan data dari Database/Model Lapangan kamu)
        $data = [
            'lapangan' => [
                'id' => $id_lapangan,
                'nama' => 'Lapangan El-Salam',
                'kategori' => 'Futsal',
                'harga_per_jam' => 100000, // Sesuai gambar
                'deskripsi' => 'Lapangan Futsal El Salam merupakan salah satu tempat favorit...',
                'gambar' => 'default.jpg' // Ganti dengan path gambar kamu
            ]
        ];

        return view('booking_detail', $data);
    }

    public function process()
    {
        $bookingModel = new BookingModel();

        // Ambil data dari form
        $data = [
            'user_id' => 1, // Ganti dengan session user yang login
            'venue_id' => $this->request->getPost('venue_id'),
            'booking_date' => $this->request->getPost('tanggal'),
            'start_time' => $this->request->getPost('jam_mulai'), // Contoh: "10:00"
            'end_time' => $this->request->getPost('jam_selesai'),
            'total_price' => $this->request->getPost('total_harga'),
            'status' => 'pending'
        ];

        // Simpan ke database
        $bookingModel->save($data);

        // Redirect atau tampilkan pesan sukses
        return redirect()->to('/riwayat')->with('success', 'Booking berhasil dibuat!');
    }
}