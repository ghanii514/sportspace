<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\FieldModel;

class Booking extends BaseController
{
    public function summary()
    {
        // 1. Ambil data yang dikirim dari form detail
        $name = $this->request->getPost("name");
        $venueId = $this->request->getPost('venue_id');
        $tanggal = $this->request->getPost('tanggal');
        $jamMulai = $this->request->getPost('jam_mulai'); // Format "08:00:00"
        $jamSelesai = $this->request->getPost('jam_selesai'); // Format "10:00:00"
        
        // 2. Ambil Info Lapangan dari Database agar valid
        $fieldModel = new FieldModel();
        $field = $fieldModel->find($venueId);

        // 3. Hitung Durasi (Logika PHP agar aman)
        $start = strtotime($jamMulai);
        $end = strtotime($jamSelesai);
        $diff = $end - $start;
        $durasi = $diff / (60 * 60); // Konversi detik ke jam

        // 4. Hitung Total Biaya
        $hargaSewa = $durasi * $field['harga'];
        $biayaLayanan = 2000; // Contoh biaya admin
        $diskon = 0; // Nanti bisa dikembangin pakai logika promo
        $totalBayar = $hargaSewa + $biayaLayanan - $diskon;

        $data = [
            'title' => 'Ringkasan Pemesanan',
            'field' => $field,
            'booking_data' => [
                'venue_id' => $venueId,
                'tanggal' => $tanggal,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'durasi' => $durasi,
                'harga_sewa' => $hargaSewa,
                'biaya_layanan' => $biayaLayanan,
                'diskon' => $diskon,
                'total_bayar' => $totalBayar
            ],
            // Data User Dummy (Nanti ganti pakai session user login)
            'user' => [
                'nama' => 'Budi Santoso',
                'email' => 'budisantoso03@gmail.com',
                'phone' => '08192802329320'
            ]
        ];

        return view('booking/checkout', $data);
    }

    public function save()
    {
        // INI PROSES SIMPAN KE DATABASE (FINAL)
        $bookingModel = new BookingModel();

        $time_end = $this->request->getPost('selesai');
        $time_start = $this->request->getPost('mulai');
        $data = [
            'name' => $this->request->getPost('username') ,
            'user_id' => $this->request->getPost('id_user') ,
            'venue_id' => $this->request->getPost('venue_id') ,
            'booking_date' => $this->request->getPost('jadwal') ,
            'start_time' => $time_start ,
            'end_time' => $time_end,
            'total_price' => $this->request->getPost('total') ,
            'status' => "Success" ,
        ];

        $bookingModel->save($data);

        return redirect()->to('/')->with('success', 'Pembayaran Berhasil! Booking telah dibuat.');
    }
}