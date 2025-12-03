<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\FieldModel;

class Booking extends BaseController
{
    public function summary()
    {
<<<<<<< HEAD
        $name = $this->request->getPost("name");
        $venueId = $this->request->getPost('venue_id');
        $tanggal = $this->request->getPost('tanggal');
        $jamMulai = $this->request->getPost('jam_mulai'); 
=======
        if (!logged_in()) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu untuk melakukan booking.');
            return redirect()->to('/login');
        }
        
        $name = $this->request->getPost("name");
        $venueId = $this->request->getPost('venue_id');
        $tanggal = $this->request->getPost('tanggal');
        $jamMulai = $this->request->getPost('jam_mulai');
>>>>>>> bca3344cbcec1ae11085a016de1ee129d58402ae
        $jamSelesai = $this->request->getPost('jam_selesai'); 
        
        $fieldModel = new FieldModel();
        $field = $fieldModel->find($venueId);

<<<<<<< HEAD
=======
       
>>>>>>> bca3344cbcec1ae11085a016de1ee129d58402ae
        $start = strtotime($jamMulai);
        $end = strtotime($jamSelesai);
        $diff = $end - $start;
        $durasi = $diff / (60 * 60); 

<<<<<<< HEAD
=======
      
>>>>>>> bca3344cbcec1ae11085a016de1ee129d58402ae
        $hargaSewa = $durasi * $field['harga'];
        $biayaLayanan = 2000; 
        $diskon = 0; 
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