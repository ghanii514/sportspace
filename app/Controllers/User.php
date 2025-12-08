<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    protected $userModel; 
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url', 'filesystem', 'auth']); 
    }

    public function index()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Profil Saya',
            'user'  => user() 
        ];

        return view('user/profile', $data);
    }

    public function updateProfilePicture()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $userId = user_id(); 

       
        $oldUser = $this->userModel->find($userId);

        $rules = [
            'profile_picture' => 'uploaded[profile_picture]|max_size[profile_picture,1024]|is_image[profile_picture]|mime_in[profile_picture,image/jpg,image/jpeg,image/png]'
        ];

        $errors = [
            'profile_picture' => [
                'uploaded' => 'Anda harus memilih gambar.',
                'max_size' => 'Ukuran gambar terlalu besar (Maks 1MB).',
                'is_image' => 'File yang diupload bukan gambar.',
                'mime_in'  => 'Format file tidak didukung. Harap upload .jpg, .jpeg, atau .png.'
            ]
        ];

        $validation = \Config\Services::validation();
        $validation->setRules($rules, $errors);

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('errors', $validation->getErrors());
            return redirect()->to('/profile');
        }

        $img = $this->request->getFile('profile_picture');
        
        if ($img->isValid() && !$img->hasMoved()) {
            $oldPicture = $oldUser->profile_picture ?? 'default.svg'; 

            if ($oldPicture && $oldPicture != 'default.svg' && $oldPicture != 'default.jpg' && file_exists(ROOTPATH . 'public/img/users/' . $oldPicture)) {
                unlink(ROOTPATH . 'public/img/users/' . $oldPicture);
            }

            $newName = $img->getRandomName();
            $img->move(ROOTPATH . 'public/img/users', $newName);
            
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