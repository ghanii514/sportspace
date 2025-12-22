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

    public function edit()
    {
        $data = [
            'title' => 'Edit Profil',
            'user'  => $this->userModel->find(user_id()) // Ambil data user yang sedang login
        ];

        return view('user/edit_profile', $data);
    }

    public function update()
    {
        $id = user_id();

        // 1. Validasi Input
        if (!$this->validate([
            'username' => [
                'rules'  => "required|alpha_numeric_space|min_length[3]|is_unique[users.username,id,{$id}]",
                'errors' => [
                    'required' => 'Username wajib diisi.',
                    'is_unique' => 'Username sudah dipakai orang lain.',
                    'min_length' => 'Username minimal 3 karakter.'
                ]
            ],
            'foto' => [
                'rules' => 'is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]|max_size[foto,2048]',
                'errors' => [
                    'is_image' => 'File harus berupa gambar.',
                    'max_size' => 'Ukuran gambar maksimal 2MB.'
                ]
            ]
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Siapkan data update (Username saja, Email tidak)
        $data = [
            'username' => $this->request->getPost('username'),
        ];

        // 3. Cek apakah user upload foto baru? (Opsional, tapi biasanya Edit Profil butuh ini)
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Generate nama random
            $namaFoto = $fileFoto->getRandomName();
            // Pindahkan file ke folder img/users
            $fileFoto->move('img/users', $namaFoto);
            // Masukkan nama foto ke database
            $data['profile_picture'] = $namaFoto;
        }

        // 4. Update Database
        $this->userModel->update($id, $data);

        return redirect()->to('/profile')->with('message', 'Profil berhasil diperbarui!');
    }
}