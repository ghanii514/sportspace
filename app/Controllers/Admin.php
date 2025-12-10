<?php

namespace App\Controllers;

use App\Models\FieldModel;
use App\Models\PromoModel;
use App\Models\BookingModel;

class Admin extends BaseController
{
    protected $fieldModel;
    protected $promoModel;
    protected $bookingModel;

    public function __construct()
    {
        $this->fieldModel = new FieldModel();
        $this->promoModel = new PromoModel();
        $this->bookingModel = new BookingModel();
        
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

        // Logika Ganti Gambar
        $fileGambar = $this->request->getFile('image');
        
        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('img/fields', $namaGambar);
            
            // Hapus gambar lama
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
        // Hapus file gambar fisik (Opsional)
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
        // Hapus file gambar fisik
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

        // 1. Ambil data promo berdasarkan ID
        $dataPromo = $promoModel->find($id);

        // 2. Cek validasi: Kalau ID ngawur/tidak ditemukan, balikin ke list
        if (empty($dataPromo)) {
            return redirect()->to('/admin/promos');
        }

        // 3. Siapkan data untuk dikirim ke View
        $data = [
            'promo' => $dataPromo
        ];

        // 4. Panggil View promo_edit
        return view('admin/promo_edit', $data);
    }

    public function updatePromo($id)
    {
        $promoModel = new \App\Models\PromoModel();
        
        $fileImage = $this->request->getFile('image');

        // Cek apakah user upload gambar baru
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
                // Ubah 'fields' jadi 'lapangan'
                ->select('booking.*, users.username, users.email, lapangan.nama AS nama_lapangan')
                ->join('users', 'users.id = booking.user_id')
                
                // INI YANG PENTING: Join ke tabel 'lapangan'
                ->join('lapangan', 'lapangan.id = booking.venue_id') 
                
                ->orderBy('booking.id', 'DESC')
                ->findAll()
        ];
        return view('admin/booking_list', $data);
    }

    // Aksi: Konfirmasi Pembayaran (Jadi Lunas)
    public function confirmBooking($id)
    {
        // 1. Update status jadi success
        $this->bookingModel->update($id, ['status' => 'success']);
        
        // 2. AMBIL DATA DULU (User & Lapangan)
        $booking = $this->bookingModel
            ->select('bookings.user_id, bookings.booking_date, bookings.start_time, lapangan.id as venue_id, lapangan.nama, lapangan.nomor_telepon')
            ->join('lapangan', 'lapangan.id = bookings.venue_id')
            ->where('bookings.id', $id)
            ->first();

        if ($booking) {
            // 3. RANCANG PESAN OTOMATIS
            // Ini text broadcast-nya
            $text  = "Halo kak, terima kasih sudah memesan " . $booking['nama'] . ".\n";
            $text .= "Jadwal: " . date('d M Y', strtotime($booking['booking_date'])) . " jam " . substr($booking['start_time'], 0, 5) . ".\n\n";
            $text .= "Ditunggu kedatangannya ya! Jika ada pertanyaan hubungi kami di WA: " . $booking['nomor_telepon'];

            // 4. SIMPAN KE INBOX USER
            $msgModel = new \App\Models\MessageModel();
            $msgModel->save([
                'user_id'  => $booking['user_id'],
                'venue_id' => $booking['venue_id'],
                'message'  => $text
            ]);
        }

        return redirect()->to('/admin/bookings')->with('success', 'Booking Lunas & Pesan otomatis terkirim!');
    }

    // Aksi: Batalkan Pesanan
    public function cancelBooking($id)
    {
        $this->bookingModel->update($id, ['status' => 'cancelled']);
        return redirect()->to('/admin/bookings')->with('success', 'Booking telah dibatalkan.');
    }
    
    // Aksi: Hapus History
    public function deleteBooking($id)
    {
        $this->bookingModel->delete($id);
        return redirect()->to('/admin/bookings')->with('success', 'Data booking dihapus permanen.');
    }
}