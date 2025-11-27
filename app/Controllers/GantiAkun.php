<?php

namespace App\Controllers;

class GantiAkun extends BaseController
{
    public function index()
    {
        // Load helper auth agar fungsi logged_in() dan user_id() jalan
        helper(['auth']);
        
        $data = [
            'title' => 'Ganti Akun',
            // Kita kirim ID user yang sedang login saat ini
            'currentUserId' => logged_in() ? user_id() : null
        ];

        // Memanggil view 'user/ganti_akun'
        return view('user/ganti_akun', $data);
    }

    public function tambah()
    {
        // 1. Logout pakai Myth:Auth
        $auth = service('authentication');
        if ($auth->check()) {
            $auth->logout();
        }

        // 2. [TAMBAHAN] Hancurkan Session CI4 secara paksa
        // Ini memastikan browser benar-benar lupa siapa user ini
        session()->destroy();

        // 3. Paksa Redirect ke halaman Login
        return redirect()->to('/login');
    }
}