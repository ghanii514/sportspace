<?php

namespace App\Controllers;

use App\Models\FieldModel;
use App\Models\MessageModel;

class Chat extends BaseController
{
     public function index()
    {
        $fieldModel = new FieldModel();

        $data = [
            'fields' => $fieldModel->findAll()
        ];

        return view('pages/chat', $data);
    }
}