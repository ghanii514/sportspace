<?php

namespace App\Controllers; 

use App\Models\FieldModel;

class Field extends BaseController 
{
    protected $fieldModel;

    public function __construct()
    {
        $this->fieldModel = new FieldModel();

        helper(['form', 'url']); 
        helper(['form', 'url']);
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

        return view('field/detail', $data); 
    }

    public function tambah()
    {
        if (!session()->get('logged_in')) {
            session()->setFlashdata('msg', 'Anda harus login untuk menambah data.');
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Tambah Lapangan Baru'
        ];

        return view('field/tambah', $data); 
    }

    public function save()
    {
        if (!session()->get('logged_in')) {
            session()->setFlashdata('msg', 'Anda harus login untuk menambah data.');
            return redirect()->to('/login');
        }

        $rules = [
            'nama' => 'required|min_length[3]',
            'alamat' => 'required',
            'harga' => 'required|numeric',
            'image' => 'uploaded[image]|max_size[image,1024]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->to('/lapangan/tambah')->withInput();
        }

        $img = $this->request->getFile('image');
        
        if ($img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName(); 
            $img->move(ROOTPATH . 'public/img/fields', $newName);
        } else {
            $newName = 'default.jpg';
        }

        $this->fieldModel->save([
            'nama' => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'alamat' => $this->request->getPost('alamat'),
            'harga' => $this->request->getPost('harga'),
            'image' => $newName 
        ]);

        session()->setFlashdata('success', 'Lapangan baru berhasil ditambahkan!');
        return redirect()->to('/');
    }

    public function search()
    {
        $keyword = $this->request->getGet('q');

        $data = [
            'title' => 'Hasil Pencarian',
            'keyword' => $keyword,
            'fields' => null 
        ];
    
        if ($keyword) {
            
            $data['fields'] = $this->fieldModel
                ->like('nama', $keyword) 
                ->orLike('alamat', $keyword) 
                ->findAll(); 
            
        if ($keyword) {
            $data['fields'] = $this->fieldModel
                ->like('nama', $keyword) 
                ->orLike('alamat', $keyword) 
                ->findAll();
        }

        return view('field/search_results', $data);
    }
}
}