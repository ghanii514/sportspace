<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    protected $userModel; 
    
    public function __construct()
    {
        // Inisialisasi model dan helper
        $this->userModel = new UserModel();
        // Load helper 'auth' agar fungsi user() dan user_id() bisa dipakai
        helper(['form', 'url', 'filesystem', 'auth']); 
    }

    // === INI FUNGSI UNTUK MENAMPILKAN HALAMAN PROFIL ===
    public function index()
    {
        // Cek login pakai helper Myth:Auth
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        // user() otomatis mengambil data user yang login sebagai OBJECT
        // Jadi $user->username di view akan BERHASIL
        $data = [
            'title' => 'Profil Saya',
            'user'  => user() 
        ];

        return view('user/profile', $data);
    }

    // === FUNGSI UPDATE FOTO ===
    public function updateProfilePicture()
    {
        // 1. Cek login pakai helper Myth:Auth
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        // Ambil ID user dari Myth:Auth
        $userId = user_id(); 

        // Kita cari data user lama untuk menghapus foto lama (jika ada)
        // find() defaultnya mengembalikan array, jadi aksesnya pakai ['key'] khusus di fungsi ini
        $oldUser = $this->userModel->find($userId);

        // 2. Definisikan Aturan Validasi
        $rules = [
            'profile_picture' => 'uploaded[profile_picture]|max_size[profile_picture,1024]|is_image[profile_picture]|mime_in[profile_picture,image/jpg,image/jpeg,image/png]'
        ];

        // 3. Pesan Error Custom
        $errors = [
            'profile_picture' => [
                'uploaded' => 'Anda harus memilih gambar.',
                'max_size' => 'Ukuran gambar terlalu besar (Maks 1MB).',
                'is_image' => 'File yang diupload bukan gambar.',
                'mime_in'  => 'Format file tidak didukung. Harap upload .jpg, .jpeg, atau .png.'
            ]
        ];

        // 4. Validasi
        $validation = \Config\Services::validation();
        $validation->setRules($rules, $errors);

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('errors', $validation->getErrors());
            return redirect()->to('/profile');
        }

        // 5. Proses Upload
        $img = $this->request->getFile('profile_picture');
        
        if ($img->isValid() && !$img->hasMoved()) {
            // Cek foto lama (akses array karena hasil dari $this->userModel->find)
            $oldPicture = $oldUser['profile_picture'] ?? 'default.svg'; // Default fallback

            // Hapus foto lama jika bukan default
            if ($oldPicture && $oldPicture != 'default.svg' && $oldPicture != 'default.jpg' && file_exists(ROOTPATH . 'public/img/users/' . $oldPicture)) {
                unlink(ROOTPATH . 'public/img/users/' . $oldPicture);
            }

            // Pindahkan foto baru
            $newName = $img->getRandomName();
            // Pastikan folder tujuannya benar (/img/users/ atau /img/profiles/)
            $img->move(ROOTPATH . 'public/img/users', $newName);
            
            // Update database
            $this->userModel->update($userId, [
                'profile_picture' => $newName
            ]);

            session()->setFlashdata('success', 'Foto profil berhasil diperbarui!');
            return redirect()->to('/profile');

        } else {
            session()->setFlashdata('msg', 'Gagal mengupload foto profil.');
            return redirect()->to('/profile');
        }
    }
}