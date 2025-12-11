<?php

namespace App\Controllers;

use App\Models\BookingModel;

class Riwayat extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            session()->setFlashdata('msg', 'Silakan login untuk melihat riwayat.');
            return redirect()->to('/login');
        }

        $riwayatData = new BookingModel();

        $status = $this->request->getGet('tab');

        if ($status == 'upcoming'){
            $data = [
                'title' => 'Riwayat Booking | SportSpace',
                'riwayat' => $riwayatData->getBookingsLengkap(user()->id)
            ];
            
        }else if ($status == 'completed'){
            
            $data = [
                'title' => 'Riwayat Booking | SportSpace',
                'riwayat' => $riwayatData->getBookingsSuccess(user()->id)
            ];
        }
        else {
            $data = [
                'title' => 'Riwayat Booking | SportSpace',
                'riwayat' => $riwayatData->getBookingsLengkap(user()->id)
            ];
        }


        return view('pages/riwayat', $data);
    }


}