<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\FieldModel;

class FieldApi extends BaseController
{
    private function addImageUrl($field)
    {
        $field['image_url'] = $field['image']
            ? base_url('api/image/' . $field['image'])
            : null;
        return $field;
    }

    public function index()
    {
        $model = new FieldModel();
        $keyword = $this->request->getGet('q');

        if ($keyword) {
            $fields = $model
                ->like('nama', $keyword)
                ->orLike('alamat', $keyword)
                ->findAll();
        } else {
            $fields = $model->findAll();
        }

        $fields = array_map([$this, 'addImageUrl'], $fields);

        return json_response($fields);
    }

    public function detail($id)
    {
        $model = new FieldModel();
        $field = $model->find($id);

        if (!$field) {
            return json_response(null, 404, 'Lapangan tidak ditemukan');
        }

        $field = $this->addImageUrl($field);

        return json_response($field);
    }
}
