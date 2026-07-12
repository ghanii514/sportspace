<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\FieldModel;
use App\Models\PromoModel;
use App\Models\MessageModel; // <--- TAMBAHAN: Panggil Model Pesan
use App\Models\ChatRoomModel;

class Booking extends BaseController
{
    public function summary()
    {
        $name = $this->request->getPost("name");
        $venueId = $this->request->getPost('venue_id');
        $tanggal = $this->request->getPost('tanggal');
        $jamMulai = $this->request->getPost('jam_mulai');
        
        if (!logged_in()) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu untuk melakukan booking.');
            return redirect()->to('/login');
        }

        $jamSelesai = $this->request->getPost('jam_selesai');

        $fieldModel = new FieldModel();
        $field = $fieldModel->find($venueId);

        $start = strtotime($jamMulai);
        $end = strtotime($jamSelesai);
        $diff = $end - $start;
        $durasi = $diff / (60 * 60);


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
                'nama' => user()->username, 
                'email' => user()->email,
                'phone' => '-'
            ]
        ];

        return view('booking/checkout', $data);
    }

    public function save()
    {
        $userId  = $this->request->getPost('id_user');
        $venueId = $this->request->getPost('venue_id');

        $fieldModel = new FieldModel();
        $field = $fieldModel->find($venueId);

        $start = strtotime($this->request->getPost('mulai'));
        $end   = strtotime($this->request->getPost('selesai'));
        $durasi = ($end - $start) / 3600;

        $hargaSewa    = $durasi * $field['harga'];
        $biayaLayanan = 2000;
        $kodePromo    = $this->request->getPost('kodepromo');
        $diskon       = 0;

        if (!empty($kodePromo)) {
            $promo = (new PromoModel())->where('promo_code', $kodePromo)->first();
            if ($promo) {
                $diskon = ($hargaSewa * $promo['jumlah_diskon']) / 100;
            }
        }

        $totalBayar = $hargaSewa + $biayaLayanan - $diskon;

        $data = [
            'user_id'         => $userId,
            'venue_id'        => $venueId,
            'booking_date'    => $this->request->getPost('jadwal'),
            'start_time'      => $this->request->getPost('mulai'),
            'end_time'        => $this->request->getPost('selesai'),
            'status'          => 'pending',
            'pembayaran'      => $this->request->getPost('pembayaran'),
            'total_price'     => $totalBayar,
            'discount_amount' => $diskon,
            'promo_code'      => $kodePromo,
        ];

        $bookingModel = new BookingModel();
        $bookingModel->save($data);

        if ($field && !empty($field['owner_id'])) {
            $ownerId = $field['owner_id'];

            $chatRoomModel = new ChatRoomModel();

            $existingRoom = $chatRoomModel
                ->where('user_id', $userId)
                ->where('owner_id', $ownerId)
                ->first();

            if (!$existingRoom) {
                $chatRoomModel->save([
                    'user_id'  => $userId,
                    'owner_id' => $ownerId
                ]);
            }
        }

        return redirect()->to('/riwayat?tab=upcoming')
            ->with('success', 'Pemesanan berhasil! Silakan cek menu Chat untuk hubungi Owner.');
    }

    public function batal($id)
    {
        $booking = new BookingModel();
        $booking->delete($id);
        return redirect()->to('/riwayat?tab=upcoming');
    }

    public function detail($id)
    {
        $riwayatData = new BookingModel();
        $data = [
            'title' => 'Detail Booking',
            'booking' => $riwayatData->getBooking($id)
        ];
        return view('pages/detail-riwayat', $data);
    }

    public function bayar($id)
    {
        $bookingModel = new BookingModel();

        $bookingModel->update($id, ['status' => 'success']);

        $booking = $bookingModel
            ->select('booking.user_id, booking.booking_date, booking.start_time, lapangan.id as venue_id, lapangan.nama, lapangan.owner_id, lapangan.nomor_telepon')
            ->join('lapangan', 'lapangan.id = booking.venue_id')
            ->where('booking.id', $id)
            ->first();

        if ($booking) {
            $chatRoomModel = new ChatRoomModel();
            $room = $chatRoomModel
                ->where('user_id', $booking['user_id'])
                ->where('owner_id', $booking['owner_id'])
                ->first();

            if ($room) {
                $text  = "Halo kak, Pembayaran untuk " . $booking['nama'] . " berhasil dikonfirmasi! ✅\n";
                $text .= "Jadwal main: " . date('d M Y', strtotime($booking['booking_date'])) . " jam " . substr($booking['start_time'], 0, 5) . ".";

                $chatMsgModel = new \App\Models\ChatMessageModel(); 
                
                $chatMsgModel->save([
                    'room_id' => $room['id'], 
                    'sender'  => 'admin',     
                    'message' => $text,
                ]);
            }
        }

        return redirect()->to('/riwayat?tab=completed')->with('success', 'Pembayaran Berhasil! Cek chat untuk info tiket.');
    }

    public function payment($id)
    {
        if (!logged_in()) { return redirect()->to('/login'); }

        $bookingModel = new BookingModel();
        $booking = $bookingModel->getBooking($id); 

        if ($booking['user_id'] != user()->id) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Pembayaran',
            'booking' => $booking
        ];

        return view('booking/payment_page', $data);
    }

    public function uploadBukti()
    {
        $bookingModel = new BookingModel();
        $id = $this->request->getPost('booking_id');

        $fileGambar = $this->request->getFile('bukti_bayar');

        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('img/bukti', $namaGambar);

            $bookingModel->update($id, [
                'bukti_bayar' => $namaGambar,
            ]);

            return redirect()->to('/riwayat?tab=upcoming')->with('success', 'Bukti pembayaran berhasil diupload! Tunggu konfirmasi Admin ya.');
        } else {
            return redirect()->back()->with('error', 'Gagal upload gambar.');
        }
    }

    public function check_promo()
    {
        $kodeInput = $this->request->getPost('kode_promo');
        $hargaSewa = $this->request->getPost('harga_sewa'); 

        $promoModel = new PromoModel();
        $promo = $promoModel->where('promo_code', $kodeInput)->first();

        if (!$promo) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Kode promo tidak valid!'
            ]);
        }

        $persen = $promo['jumlah_diskon'];
        $nominalDiskon = ($hargaSewa * $persen) / 100;

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Kode berhasil digunakan!',
            'diskon_rupiah' => $nominalDiskon,
            'persen' => $persen
        ]);
    }

    public function checkAvailability()
    {
        $venueId = $this->request->getGet('venue_id');
        $date    = $this->request->getGet('date');

        $bookingModel = new \App\Models\BookingModel();

        $bookings = $bookingModel
            ->where('venue_id', $venueId)
            ->where('booking_date', $date)
            ->where('status !=', 'cancel') 
            ->findAll();

        $bookedSlots = [];

        foreach ($bookings as $b) {
            $start = intval(substr($b['start_time'], 0, 2));
            $end   = intval(substr($b['end_time'], 0, 2));

            
            for ($i = $start; $i < $end; $i++) {
                $bookedSlots[] = $i;
            }
        }

        return $this->response->setJSON($bookedSlots);
    }
}