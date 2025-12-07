<?php

namespace App\Controllers;

use App\Models\FieldModel;
use App\Models\PromoModel;

class Admin extends BaseController
{
    protected $fieldModel;
    protected $promoModel;

    public function __construct()
    {
        $this->fieldModel = new FieldModel();
        $this->promoModel = new PromoModel();
        helper(['form', 'url', 'auth']);
    }

    // Halaman Utama Dashboard
    public function index()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'total_fields' => $this->fieldModel->countAll(),
            'total_promos' => $this->promoModel->countAll()
        ];
        return view('admin/index', $data);
    }

    // === FITUR LAPANGAN ===
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
            'validation' => \Config\Services::validation() // Kirim validasi (opsional)
        ];
        return view('admin/field_create', $data);
    }

    // 2. Proses Simpan Data
    public function saveField()
    {
        // Validasi Input
        if (!$this->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
            'image' => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|max_size[image,2048]'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Cek kembali data inputan (terutama gambar).');
        }

        // Proses Upload Gambar
        $fileGambar = $this->request->getFile('image');
        
        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
            // Generate nama random
            $namaGambar = $fileGambar->getRandomName();
            // Pindahkan ke folder public/img/fields
            $fileGambar->move('img/fields', $namaGambar);
        } else {
            $namaGambar = 'default.jpg';
        }

        // Bikin Slug Otomatis (biar URL-nya cantik)
        // Contoh: "Lapangan Futsal A" -> "lapangan-futsal-a"
        $slug = url_title($this->request->getPost('nama'), '-', true);

        // Simpan ke Database
        $this->fieldModel->save([
            'nama'      => $this->request->getPost('nama'),
            'slug'      => $slug,
            'kategori'  => $this->request->getPost('kategori'),
            'alamat'    => $this->request->getPost('alamat'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga'     => $this->request->getPost('harga'),
            'image'     => $namaGambar
        ]);

        return redirect()->to('/admin/fields')->with('success', 'Lapangan berhasil ditambahkan!');
    }
    
    public function editField($id)
    {
        $data = [
            'title' => 'Edit Lapangan',
            'field' => $this->fieldModel->find($id) // Ambil data berdasarkan ID
        ];

        if (!$data['field']) {
            return redirect()->to('/admin/fields')->with('error', 'Data tidak ditemukan.');
        }

        return view('admin/field_edit', $data);
    }

    // 4. Proses Update Data
    public function updateField($id)
    {
        // Validasi simpel
        if (!$this->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid.');
        }

        // --- LOGIKA GANTI GAMBAR ---
        $fileGambar = $this->request->getFile('image');
        
        // Cek: Apakah admin upload gambar baru?
        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
            // 1. Generate nama baru
            $namaGambar = $fileGambar->getRandomName();
            // 2. Pindahkan gambar baru
            $fileGambar->move('img/fields', $namaGambar);
            
            // 3. (Opsional) Hapus gambar lama biar server gak penuh
            $oldImage = $this->request->getPost('old_image');
            if ($oldImage != 'default.jpg' && file_exists('img/fields/' . $oldImage)) {
                unlink('img/fields/' . $oldImage);
            }
        } else {
            // Kalau tidak upload, pakai nama gambar lama
            $namaGambar = $this->request->getPost('old_image');
        }
        // ---------------------------

        // Update Slug biar sesuai nama baru
        $slug = url_title($this->request->getPost('nama'), '-', true);

        // Simpan Perubahan
        $this->fieldModel->update($id, [
            'nama'      => $this->request->getPost('nama'),
            'slug'      => $slug,
            'kategori'  => $this->request->getPost('kategori'),
            'alamat'    => $this->request->getPost('alamat'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga'     => $this->request->getPost('harga'),
            'image'     => $namaGambar // Simpan nama gambar (baru/lama)
        ]);

        return redirect()->to('/admin/fields')->with('success', 'Data lapangan berhasil diperbarui!');
    }

    public function deleteField($id)
    {
        $this->fieldModel->delete($id);
        return redirect()->to('/admin/fields')->with('success', 'Lapangan dihapus');
    }

    // === FITUR PROMO ===
    public function promos()
    {
        $data = [
            'title' => 'Kelola Promo',
            'promos' => $this->promoModel->findAll()
        ];
        return view('admin/promos_list', $data);
    }
    
     public function deletePromo($id)
    {
        $this->promoModel->delete($id);
        return redirect()->to('/admin/promos')->with('success', 'Promo dihapus');
    }
}