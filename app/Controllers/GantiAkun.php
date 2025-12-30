<?php

namespace App\Controllers;

use App\Models\UserModel;
use Myth\Auth\Entities\User;

class GantiAkun extends BaseController
{
    public function index()
    {
        helper(['auth']);

        $data = [
            'title' => 'Ganti Akun',
            'currentUserId' => logged_in() ? user_id() : null
        ];

        return view('user/ganti_akun', $data);
    }

    public function tambah()
    {
        $auth = service('authentication');
        if ($auth->check()) {
            $auth->logout();
        }

        session()->destroy();

        return redirect()->to('/login');
    }


    public function switchAction()
    {
        $targetEmail = $this->request->getGet('email');

        if (!$targetEmail) {
            return redirect()->back()->with('error', 'Tidak ada email yang dipilih.');
        }

        $userModel = new \Myth\Auth\Models\UserModel();

        // Cari user berdasarkan email
        $user = $userModel->where('email', $targetEmail)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Akun tidak ditemukan.');
        }

        
        $auth = service('authentication');

        
        if ($auth->check()) {
            $auth->logout();
        }

        
        $auth->loginById($user->id);

        return redirect()->to('/')->with('message', 'Berhasil berpindah akun.');
    }

}