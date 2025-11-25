<?php

namespace App\Controllers; 

use App\Models\FieldModel;

class Field extends BaseController // Ganti 'FieldController' jika Anda pakai suffix
{
    protected $fieldModel;

    public function __construct()
    {
        $this->fieldModel = new FieldModel();
        helper(['form', 'url']); // Load helper untuk form dan URL
    }

    public function detail($id)
    {
        $field = $this->fieldModel->find($id);

        if (empty($field)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Lapangan tidak ditemukan: ' . $id);
        }

        $data = [
            'title' => $field['nama'],
            'field' => $field
        ];

        // Buat view-nya di app/Views/field/detail.php
        return view('field/detail', $data); 
    }

    // --- METHOD BARU UNTUK MENAMPILKAN FORM ---
    public function tambah()
    {
        // Cek dulu apakah sudah login
        if (!session()->get('logged_in')) {
            session()->setFlashdata('msg', 'Anda harus login untuk menambah data.');
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Tambah Lapangan Baru'
        ];

        return view('field/tambah', $data); // Arahkan ke view form
    }

    // --- METHOD BARU UNTUK MENYIMPAN DATA ---
    public function save()
    {
        // Cek dulu apakah sudah login
        if (!session()->get('logged_in')) {
            session()->setFlashdata('msg', 'Anda harus login untuk menambah data.');
            return redirect()->to('/login');
        }

        // --- Aturan Validasi ---
        $rules = [
            'nama' => 'required|min_length[3]',
            'alamat' => 'required',
            'harga' => 'required|numeric',
            'image' => 'uploaded[image]|max_size[image,1024]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            // Jika validasi gagal, kembalikan ke form dengan pesan error
            // Kita akan kirim pesan error-nya ke view
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->to('/lapangan/tambah')->withInput();
        }
        // --- Akhir Validasi ---

        // --- Proses Upload Gambar ---
        $img = $this->request->getFile('image');
        
        if ($img->isValid() && !$img->hasMoved()) {
            // Pindahkan file ke folder public/img/fields/
            // Pastikan Anda SUDAH MEMBUAT folder "fields" di dalam "public/img"
            $newName = $img->getRandomName(); // Dapatkan nama random
            $img->move(ROOTPATH . 'public/img/fields', $newName);
        } else {
            // Jika gagal upload, pakai gambar default
            $newName = 'default.jpg';
        }
        // --- Akhir Upload Gambar ---

        // --- Simpan ke Database ---
        $this->fieldModel->save([
            'nama' => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'alamat' => $this->request->getPost('alamat'),
            'harga' => $this->request->getPost('harga'),
            'image' => $newName // Simpan nama file gambar ke DB
        ]);
        // --- Akhir Simpan DB ---

        // Beri pesan sukses dan redirect ke Halaman Utama
        session()->setFlashdata('success', 'Lapangan baru berhasil ditambahkan!');
        return redirect()->to('/');
    }

    public function search()
    {
        // 1. Ambil keyword dari URL (yang kita namai 'q' di form)
        $keyword = $this->request->getGet('q');

        // 2. Siapkan data
        $data = [
            'title' => 'Hasil Pencarian',
            'keyword' => $keyword,
            'fields' => null // Set 'fields' jadi null dulu
        ];

        // 3. Jika ada keyword, cari di database
        if ($keyword) {
            // Gunakan like() untuk mencari yang mirip
            $data['fields'] = $this->fieldModel
                ->like('nama', $keyword) // Cari di kolom 'name'
                ->orLike('alamat', $keyword) // ATAU cari di kolom 'address'
                ->findAll(); // Ambil semua hasilnya
        }

        // 4. Tampilkan view baru
        return view('field/search_results', $data);
    }
}