<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AboutModel;

class AboutApi extends BaseController
{
    public function index()
    {
        $model = new AboutModel();
        $about = $model->first();

        if (!$about) {
            return json_response(null, 404, 'Konten tentang kami tidak ditemukan');
        }

        $about['image_url'] = base_url('api/image/about/sportspace.jpeg');

        return json_response($about);
    }
}
