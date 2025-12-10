<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\FieldModel;
use App\Models\PromoModel;
use App\Models\MessageModel; // <--- TAMBAHAN: Panggil Model Pesan

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
                'nama' => user()->username, // Update biar dinamis
                'email' => user()->email,
                'phone' => '-'
            ]
        ];

        return view('booking/checkout', $data);
    }

    public function save()
    {
        // 1. Ambil data diskon dari form (kalau kosong, anggap 0)
        $inputDiskon = $this->request->getPost('discount_amount');
        if(empty($inputDiskon)) {
            $inputDiskon = 0;
        }

        // 2. Siapkan data untuk disimpan
        $data = [
            'user_id'         => $this->request->getPost('id_user'),
            'venue_id'        => $this->request->getPost('venue_id'),
            'booking_date'    => $this->request->getPost('jadwal'),
            'start_time'      => $this->request->getPost('mulai'),
            'end_time'        => $this->request->getPost('selesai'),
            'status'          => 'pending', 
            'pembayaran'      => $this->request->getPost('pembayaran'),

            // Total Harga (ini harga SETELAH diskon)
            'total_price'     => $this->request->getPost('total'),

            // Data Promo Baru
            'discount_amount' => $inputDiskon,
            'promo_code'      => $this->request->getPost('kodepromo'),
        ];

        // 3. Simpan ke Database
        $bookingModel = new BookingModel();
        $bookingModel->save($data);

        return redirect()->to('/riwayat?tab=upcoming')->with('success', 'Pemesanan berhasil, silakan lakukan pembayaran.');
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

        // 1. Update Status Jadi Success (Lunas)
        $bookingModel->update($id, [
            'status' => 'success'
        ]);

        // 2. LOGIKA CHAT BROADCAST OTOMATIS
        // Ambil data detail booking + info lapangan (terutama nomor telepon)
        $booking = $bookingModel
            ->select('booking.user_id, booking.booking_date, booking.start_time, lapangan.id as venue_id, lapangan.nama, lapangan.nomor_telepon')
            ->join('lapangan', 'lapangan.id = booking.venue_id')
            ->where('booking.id', $id)
            ->first();

        if ($booking) {
            // Rancang pesan otomatis
            $text  = "Halo kak, Pembayaran untuk " . $booking['nama'] . " berhasil dikonfirmasi! ✅\n";
            $text .= "Jadwal main: " . date('d M Y', strtotime($booking['booking_date'])) . " jam " . substr($booking['start_time'], 0, 5) . ".\n\n";
            $text .= "Silakan datang tepat waktu ya! Jika butuh bantuan atau konfirmasi, hubungi WA Admin Lapangan: " . $booking['nomor_telepon'];

            // Simpan ke tabel messages
            $msgModel = new MessageModel();
            $msgModel->save([
                'user_id'  => $booking['user_id'],
                'venue_id' => $booking['venue_id'],
                'message'  => $text,
                'sender'   => 'admin'
            ]);
        }

        return redirect()->to('/riwayat?tab=completed')->with('success', 'Pembayaran Berhasil! Cek menu Chat untuk info kontak lapangan.');
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
}