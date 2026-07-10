<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\FieldModel;
use App\Models\PromoModel;
use App\Models\ChatRoomModel;

class BookingApi extends BaseController
{
    private function getInput(): array
    {
        return $this->request->getJSON(true) ?? $this->request->getPost() ?? [];
    }

    public function checkAvailability()
    {
        $venueId = $this->request->getGet('venue_id');
        $date    = $this->request->getGet('date');

        if (!$venueId || !$date) {
            return json_response(null, 400, 'Parameter venue_id dan date wajib diisi');
        }

        $bookings = (new BookingModel())
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

        return json_response([
            'date'         => $date,
            'venue_id'     => $venueId,
            'booked_slots' => $bookedSlots,
        ]);
    }

    public function checkPromo()
    {
        $input = $this->getInput();
        $kodeInput = $input['kode_promo'] ?? null;
        $hargaSewa = $input['harga_sewa'] ?? null;

        if (!$kodeInput || !$hargaSewa) {
            return json_response(null, 400, 'Parameter kode_promo dan harga_sewa wajib diisi');
        }

        $promo = (new PromoModel())->where('promo_code', $kodeInput)->first();

        if (!$promo) {
            return json_response(null, 404, 'Kode promo tidak valid');
        }

        $persen        = $promo['jumlah_diskon'];
        $nominalDiskon = ($hargaSewa * $persen) / 100;

        return json_response([
            'promo_code'       => $promo['promo_code'],
            'persen'           => $persen,
            'nominal_diskon'   => $nominalDiskon,
            'total_setelah_diskon' => $hargaSewa - $nominalDiskon,
        ]);
    }

    public function create()
    {
        if (!$this->request->user) {
            return json_response(null, 401, 'Unauthorized');
        }

        $input = $this->getInput();

        $rules = [
            'venue_id'    => 'required|numeric',
            'booking_date' => 'required|valid_date[Y-m-d]',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'name'        => 'permit_empty',
        ];

        if (!$this->validate($rules, $input)) {
            return json_response(null, 400, $this->validator->getErrors());
        }

        $userId   = $this->request->user->sub;
        $venueId  = $input['venue_id'];
        $field    = (new FieldModel())->find($venueId);

        if (!$field) {
            return json_response(null, 404, 'Lapangan tidak ditemukan');
        }

        $start     = strtotime($input['start_time']);
        $end       = strtotime($input['end_time']);
        $durasi    = ($end - $start) / 3600;
        $hargaSewa = $durasi * $field['harga'];
        $biayaLayanan = 2000;
        $kodePromo = $input['promo_code'] ?? null;
        $diskon    = 0;

        if ($kodePromo) {
            $promo = (new PromoModel())->where('promo_code', $kodePromo)->first();
            if ($promo) {
                $diskon = ($hargaSewa * $promo['jumlah_diskon']) / 100;
            }
        }

        $totalBayar = $hargaSewa + $biayaLayanan - $diskon;

        $bookingModel = new BookingModel();
        $bookingModel->save([
            'user_id'         => $userId,
            'venue_id'        => $venueId,
            'name'            => $input['name'] ?? '',
            'booking_date'    => $input['booking_date'],
            'start_time'      => $input['start_time'],
            'end_time'        => $input['end_time'],
            'total_price'     => $totalBayar,
            'status'          => 'pending',
            'pembayaran'      => 'transfer',
            'discount_amount' => $diskon,
            'promo_code'      => $kodePromo,
        ]);

        $bookingId = $bookingModel->getInsertID();

        if (!empty($field['owner_id'])) {
            $roomModel = new ChatRoomModel();
            $existingRoom = $roomModel
                ->where('user_id', $userId)
                ->where('owner_id', $field['owner_id'])
                ->first();

            if (!$existingRoom) {
                $roomModel->save([
                    'user_id'  => $userId,
                    'owner_id' => $field['owner_id'],
                ]);
            }
        }

        $booking = $bookingModel->getBooking($bookingId);

        return json_response($booking, 201, 'Pemesanan berhasil');
    }

    public function index()
    {
        if (!$this->request->user) {
            return json_response(null, 401, 'Unauthorized');
        }

        $userId = $this->request->user->sub;
        $status = $this->request->getGet('status');

        $model = new BookingModel();

        if ($status === 'completed') {
            $bookings = $model->getBookingsSuccess($userId);
        } else {
            $bookings = $model->getBookingsLengkap($userId);
        }

        return json_response($bookings);
    }

    public function detail($id)
    {
        if (!$this->request->user) {
            return json_response(null, 401, 'Unauthorized');
        }

        $booking = (new BookingModel())->getBooking($id);

        if (!$booking) {
            return json_response(null, 404, 'Booking tidak ditemukan');
        }

        if ($booking['user_id'] != $this->request->user->sub) {
            return json_response(null, 403, 'Akses ditolak');
        }

        return json_response($booking);
    }

    public function uploadBukti($id)
    {
        if (!$this->request->user) {
            return json_response(null, 401, 'Unauthorized');
        }

        $bookingModel = new BookingModel();
        $booking = $bookingModel->find($id);

        if (!$booking) {
            return json_response(null, 404, 'Booking tidak ditemukan');
        }

        if ($booking['user_id'] != $this->request->user->sub) {
            return json_response(null, 403, 'Akses ditolak');
        }

        $file = $this->request->getFile('bukti_bayar');

        if (!$file || !$file->isValid()) {
            return json_response(null, 400, 'File bukti bayar wajib diupload');
        }

        $allowedMime = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file->getMimeType(), $allowedMime)) {
            return json_response(null, 400, 'File harus berupa gambar JPG atau PNG');
        }

        if ($file->getSize() > 5 * 1024 * 1024) {
            return json_response(null, 400, 'Ukuran file maksimal 5 MB');
        }

        $newName = $file->getRandomName();
        $file->move('img/bukti', $newName);

        $updateData = ['bukti_bayar' => $newName];
        $pembayaran = $this->request->getPost('pembayaran');
        if ($pembayaran) {
            $updateData['pembayaran'] = $pembayaran;
        }
        $bookingModel->update($id, $updateData);

        return json_response(['bukti_bayar' => $newName], 200, 'Bukti pembayaran berhasil diupload');
    }

    public function cancel($id)
    {
        if (!$this->request->user) {
            return json_response(null, 401, 'Unauthorized');
        }

        $bookingModel = new BookingModel();
        $booking = $bookingModel->find($id);

        if (!$booking) {
            return json_response(null, 404, 'Booking tidak ditemukan');
        }

        if ($booking['user_id'] != $this->request->user->sub) {
            return json_response(null, 403, 'Akses ditolak');
        }

        if ($booking['status'] !== 'pending') {
            return json_response(null, 400, 'Booking sudah tidak bisa dibatalkan');
        }

        $bookingModel->update($id, ['status' => 'cancel']);

        return json_response(['id' => (int)$id], 200, 'Booking berhasil dibatalkan');
    }
}
