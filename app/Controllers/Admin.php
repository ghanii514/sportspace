<?php

namespace App\Controllers;

use App\Models\FieldModel;
use App\Models\PromoModel;
use App\Models\BookingModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    protected $fieldModel;
    protected $promoModel;
    protected $bookingModel;
    protected $userModel;

    public function __construct()
    {
        $this->fieldModel = new FieldModel();
        $this->promoModel = new PromoModel();
        $this->bookingModel = new BookingModel();
        $this->userModel = new UserModel();
        
        helper(['form', 'url', 'auth']);
    }

    // =========================================================================
    // 1. DASHBOARD UTAMA
    // =========================================================================
    public function index()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'total_fields' => $this->fieldModel->countAll(),
            'total_promos' => $this->promoModel->countAll()
        ];
        return view('admin/index', $data);
    }

    // =========================================================================
    // 2. MANAJEMEN LAPANGAN (CRUD)
    // =========================================================================
    public function fields()
    {
        $data = [
            'title' => 'Kelola Lapangan',
            'fields' => $this->fieldModel->findAll()
        ];
        return view('admin/fields_list', $data);
    }

    public function createField()
    {
        $data = [
            'title' => 'Tambah Lapangan',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/field_create', $data);
    }

    public function saveField()
    {
        if (!$this->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
            'image' => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|max_size[image,2048]'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Cek kembali data inputan.');
        }

        $fileGambar = $this->request->getFile('image');
        
        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('img/fields', $namaGambar);
        } else {
            $namaGambar = 'default.jpg';
        }

        $slug = url_title($this->request->getPost('nama'), '-', true);

        $this->fieldModel->save([
            'nama'      => $this->request->getPost('nama'),
            'slug'      => $slug,
            'kategori'  => $this->request->getPost('kategori'),
            'alamat'    => $this->request->getPost('alamat'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga'     => $this->request->getPost('harga'),
            'nomor_telepon' => $this->request->getPost('nomor_telepon'),
            'image'     => $namaGambar
        ]);

        return redirect()->to('/admin/fields')->with('success', 'Lapangan berhasil ditambahkan!');
    }

    public function editField($id)
    {
        $data = [
            'title' => 'Edit Lapangan',
            'field' => $this->fieldModel->find($id)
        ];

        if (!$data['field']) {
            return redirect()->to('/admin/fields')->with('error', 'Data tidak ditemukan.');
        }

        return view('admin/field_edit', $data);
    }

    public function updateField($id)
    {
        if (!$this->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid.');
        }

        
        $fileGambar = $this->request->getFile('image');
        
        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('img/fields', $namaGambar);
            
           
            $oldImage = $this->request->getPost('old_image');
            if ($oldImage != 'default.jpg' && file_exists('img/fields/' . $oldImage)) {
                unlink('img/fields/' . $oldImage);
            }
        } else {
            $namaGambar = $this->request->getPost('old_image');
        }

        $slug = url_title($this->request->getPost('nama'), '-', true);

        $this->fieldModel->update($id, [
            'nama'      => $this->request->getPost('nama'),
            'slug'      => $slug,
            'kategori'  => $this->request->getPost('kategori'),
            'alamat'    => $this->request->getPost('alamat'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga'     => $this->request->getPost('harga'),
            'nomor_telepon' => $this->request->getPost('nomor_telepon'),
            'image'     => $namaGambar
        ]);

        return redirect()->to('/admin/fields')->with('success', 'Data lapangan berhasil diperbarui!');
    }

    public function deleteField($id)
    {
        
        $field = $this->fieldModel->find($id);
        if ($field['image'] != 'default.jpg' && file_exists('img/fields/' . $field['image'])) {
            unlink('img/fields/' . $field['image']);
        }

        $this->fieldModel->delete($id);
        return redirect()->to('/admin/fields')->with('success', 'Lapangan dihapus');
    }

    // =========================================================================
    // 3. MANAJEMEN PROMO
    // =========================================================================
    public function promos()
    {
        $data = [
            'title' => 'Kelola Promo',
            'promos' => $this->promoModel->findAll()
        ];
        return view('admin/promos_list', $data);
    }

    public function createPromo()
    {
        $data = [
            'title' => 'Tambah Promo Baru'
        ];
        return view('admin/promo_create', $data);
    }

    public function savePromo()
    {
        if (!$this->validate([
            'promo' => 'required',
            'promo_code' => 'required',
            'image' => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|max_size[image,2048]'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Cek kembali data inputan.');
        }

        $fileGambar = $this->request->getFile('image');
        
        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('img/promo', $namaGambar);
        } else {
            $namaGambar = 'default.jpg';
        }

        $this->promoModel->save([
            'promo'       => $this->request->getPost('promo'),
            'promo_code'  => $this->request->getPost('promo_code'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'image'       => $namaGambar
        ]);

        return redirect()->to('/admin/promos')->with('success', 'Promo berhasil ditambahkan!');
    }

    public function deletePromo($id)
    {
        
        $promo = $this->promoModel->find($id);
        if ($promo['image'] != 'default.jpg' && file_exists('img/promo/' . $promo['image'])) {
            unlink('img/promo/' . $promo['image']);
        }

        $this->promoModel->delete($id);
        return redirect()->to('/admin/promos')->with('success', 'Promo dihapus');
    }

    public function edit($id)
    {
        $promoModel = new \App\Models\PromoModel();

        
        $dataPromo = $promoModel->find($id);

        
        if (empty($dataPromo)) {
            return redirect()->to('/admin/promos');
        }

        
        $data = [
            'promo' => $dataPromo
        ];

        
        return view('admin/promo_edit', $data);
    }

    public function updatePromo($id)
    {
        $promoModel = new \App\Models\PromoModel();
        
        $fileImage = $this->request->getFile('image');

        
        if ($fileImage->getError() == 4) {
            $namaImage = $this->request->getPost('old_image');
        } else {
            $namaImage = $fileImage->getRandomName();
            $fileImage->move('img/promo', $namaImage);
        }

        $promoModel->save([
            'id'            => $id,
            'promo'         => $this->request->getPost('promo'),
            'promo_code'    => $this->request->getPost('promo_code'),
            'jumlah_diskon' => $this->request->getPost('jumlah_diskon'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'image'         => $namaImage
        ]);

        return redirect()->to('/admin/promos')->with('success', 'Data berhasil diupdate!');
    }

    // =========================================================================
    // 4. MANAJEMEN BOOKING (CEK PESANAN)
    // =========================================================================
    public function bookings()
    {
        $data = [
            'title' => 'Cek Booking',
            'bookings' => $this->bookingModel
                
                ->select('booking.*, users.username, users.email, lapangan.nama AS nama_lapangan')
                ->join('users', 'users.id = booking.user_id')
                
                
                ->join('lapangan', 'lapangan.id = booking.venue_id') 
                
                ->orderBy('booking.id', 'DESC')
                ->findAll()
        ];
        return view('admin/booking_list', $data);
    }

    
    public function confirmBooking($id)
    {
        
        $this->bookingModel->update($id, ['status' => 'success']);
        
        
        $booking = $this->bookingModel
            ->select('booking.user_id, booking.booking_date, booking.start_time, lapangan.id as venue_id, lapangan.nama, lapangan.nomor_telepon')
            ->join('lapangan', 'lapangan.id = booking.venue_id')
            ->where('booking.id', $id)
            ->first();

        if ($booking) {
            
            $text  = "Halo kak, terima kasih sudah memesan " . $booking['nama'] . ".\n";
            $text .= "Jadwal: " . date('d M Y', strtotime($booking['booking_date'])) . " jam " . substr($booking['start_time'], 0, 5) . ".\n\n";
            $text .= "Ditunggu kedatangannya ya! Jika ada pertanyaan hubungi kami di WA: " . $booking['nomor_telepon'];

            
            $msgModel = new \App\Models\MessageModel();
            $msgModel->save([
                'user_id'  => $booking['user_id'],
                'venue_id' => $booking['venue_id'],
                'message'  => $text
            ]);
        }

        return redirect()->to('/admin/bookings')->with('success', 'Booking Lunas & Pesan otomatis terkirim!');
    }

    
    public function cancelBooking($id)
    {
        $this->bookingModel->update($id, ['status' => 'cancelled']);
        return redirect()->to('/admin/bookings')->with('success', 'Booking telah dibatalkan.');
    }
    
    
    public function deleteBooking($id)
    {
        $this->bookingModel->delete($id);
        return redirect()->to('/admin/bookings')->with('success', 'Data booking dihapus permanen.');
    }

    // =========================================================================
    // 5. EDIT PROFIL ADMIN
    // =========================================================================
    public function profile()
    {
        $data = [
            'title' => 'Edit Profil',
            'user'  => user()
        ];
        return view('admin/profile_edit', $data);
    }

    public function updateProfile()
    {
        $id = user_id();

        if (!$this->validate([
            'username' => [
                'rules'  => "required|alpha_numeric_space|min_length[3]|is_unique[users.username,id,{$id}]",
                'errors' => [
                    'required'    => 'Username wajib diisi.',
                    'is_unique'   => 'Username sudah dipakai orang lain.',
                    'min_length'  => 'Username minimal 3 karakter.'
                ]
            ],
            'email' => [
                'rules'  => "required|valid_email|is_unique[users.email,id,{$id}]",
                'errors' => [
                    'required'    => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                    'is_unique'   => 'Email sudah dipakai orang lain.'
                ]
            ],
            'profile_picture' => [
                'rules' => 'is_image[profile_picture]|mime_in[profile_picture,image/jpg,image/jpeg,image/png]|max_size[profile_picture,1024]',
                'errors' => [
                    'is_image' => 'File harus berupa gambar.',
                    'mime_in'  => 'Format harus JPG/JPEG/PNG.',
                    'max_size' => 'Ukuran gambar maksimal 1MB.'
                ]
            ]
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
        ];

        $file = $this->request->getFile('profile_picture');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $old = user()->profile_picture ?? 'default.png';
            if ($old != 'default.png' && $old != 'default.jpg' && $old != 'default.svg' && file_exists(ROOTPATH . 'public/img/users/' . $old)) {
                unlink(ROOTPATH . 'public/img/users/' . $old);
            }
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/img/users', $newName);
            $data['profile_picture'] = $newName;
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/admin/profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updateProfilePicture()
    {
        $id = user_id();

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
            return redirect()->to('/admin/profile');
        }

        $img = $this->request->getFile('profile_picture');
        if ($img->isValid() && !$img->hasMoved()) {
            $oldPicture = user()->profile_picture ?? 'default.png';
            if ($oldPicture != 'default.png' && $oldPicture != 'default.jpg' && $oldPicture != 'default.svg' && file_exists(ROOTPATH . 'public/img/users/' . $oldPicture)) {
                unlink(ROOTPATH . 'public/img/users/' . $oldPicture);
            }
            $newName = $img->getRandomName();
            $img->move(ROOTPATH . 'public/img/users', $newName);
            $this->userModel->update($id, ['profile_picture' => $newName]);
            session()->setFlashdata('success', 'Foto profil berhasil diperbarui!');
            return redirect()->to('/admin/profile');
        }

        session()->setFlashdata('error', 'Gagal mengupload foto profil.');
        return redirect()->to('/admin/profile');
    }
}