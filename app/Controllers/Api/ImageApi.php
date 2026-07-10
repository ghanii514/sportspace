<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class ImageApi extends BaseController
{
    public function serve($filename = null)
    {
        if (!$filename) {
            return json_response(null, 404, 'File tidak ditemukan');
        }

        $path = FCPATH . 'img/fields/' . $filename;

        if (!file_exists($path)) {
            return json_response(null, 404, 'File tidak ditemukan');
        }

        $mime = mime_content_type($path);
        $data = file_get_contents($path);

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Length', (string) filesize($path))
            ->setHeader('Cache-Control', 'public, max-age=31536000')
            ->setBody($data);
    }

    public function servePromo($filename = null)
    {
        if (!$filename) {
            return json_response(null, 404, 'File tidak ditemukan');
        }

        $path = FCPATH . 'img/promo/' . $filename;

        if (!file_exists($path)) {
            return json_response(null, 404, 'File tidak ditemukan');
        }

        $mime = mime_content_type($path);
        $data = file_get_contents($path);

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Length', (string) filesize($path))
            ->setHeader('Cache-Control', 'public, max-age=31536000')
            ->setBody($data);
    }

    public function serveAbout($filename = null)
    {
        if (!$filename) {
            return json_response(null, 404, 'File tidak ditemukan');
        }

        $path = FCPATH . 'img/about/' . $filename;

        if (!file_exists($path)) {
            return json_response(null, 404, 'File tidak ditemukan');
        }

        $mime = mime_content_type($path);
        $data = file_get_contents($path);

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Length', (string) filesize($path))
            ->setHeader('Cache-Control', 'public, max-age=31536000')
            ->setBody($data);
    }

    public function serveProfile($filename = null)
    {
        if (!$filename) {
            return json_response(null, 404, 'File tidak ditemukan');
        }

        $path = FCPATH . 'img/users/' . $filename;

        if (!file_exists($path)) {
            return json_response(null, 404, 'File tidak ditemukan');
        }

        $mime = mime_content_type($path);
        $data = file_get_contents($path);

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Length', (string) filesize($path))
            ->setHeader('Cache-Control', 'public, max-age=31536000')
            ->setBody($data);
    }
}
