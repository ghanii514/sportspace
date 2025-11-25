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
        helper(['form', 'url', 'filesystem']);
    }

    public function profile()
    {
        if (!session()->get('logged_in')) {
            session()->setFlashdata('msg', 'Anda harus login untuk mengakses halaman ini.');
            return redirect()->to('/login');
        }

        $model = new UserModel();

        $userId = session()->get('user_id');

        $userData = $model->find($userId);

        if (!$userData) {
            session()->destroy();
            session()->setFlashdata('msg', 'Data user tidak ditemukan.');
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Profil Saya',
            'user' => $userData // Kirim semua data user ke view
        ];

        return view('user/profile', $data);
    }
    
    // app/Controllers/User.php

    public function updateProfilePicture()
    {
        // 1. Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $oldUser = $this->userModel->find($userId);

        // 2. Definisikan Aturan Validasi
        $rules = [
            'profile_picture' => 'uploaded[profile_picture]|max_size[profile_picture,1024]|is_image[profile_picture]|mime_in[profile_picture,image/jpg,image/jpeg,image/png]'
        ];

        // --- INI BAGIAN BARUNYA ---
        // 3. Definisikan Pesan Error Custom
        $errors = [
            'profile_picture' => [
                'uploaded' => 'Anda harus memilih gambar.',
                'max_size' => 'Ukuran gambar terlalu besar (Maks 1MB).',
                'is_image' => 'File yang diupload bukan gambar.',
                'mime_in'  => 'Format file tidak didukung (seperti .webp). Harap upload .jpg, .jpeg, atau .png.'
            ]
        ];
        // --- AKHIR BAGIAN BARU ---

        // 4. Ambil validator service
        $validation = \Config\Services::validation();
        
        // 5. Set Aturan DAN Pesan Error
        $validation->setRules($rules, $errors); // <- Kita tambahkan $errors di sini

        if (!$validation->withRequest($this->request)->run()) {
            // Jika validasi gagal
            session()->setFlashdata('errors', $validation->getErrors()); // Kirim semua error
            return redirect()->to('/profile');
        }

        // 6. Proses Upload Gambar (Tidak berubah)
        $img = $this->request->getFile('profile_picture');
        
        if ($img->isValid() && !$img->hasMoved()) {
            $oldPicture = $oldUser['profile_picture'];

            if ($oldPicture && $oldPicture != 'default_profile.jpg' && file_exists(ROOTPATH . 'public/img/profiles/' . $oldPicture)) {
                unlink(ROOTPATH . 'public/img/profiles/' . $oldPicture);
            }

            $newName = $img->getRandomName();
            $img->move(ROOTPATH . 'public/img/profiles', $newName);
            
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