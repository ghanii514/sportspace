<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class PartnerLogoApi extends BaseController
{
    private const LOGOS = [
        'pertamina' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Pertamina_Logo.svg/960px-Pertamina_Logo.svg.png',
        'fifa' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/aa/FIFA_logo_without_slogan.svg/960px-FIFA_logo_without_slogan.svg.png',
        'adidas' => 'https://upload.wikimedia.org/wikipedia/commons/e/ee/Logo_brand_Adidas.png',
        'pocari_sweat' => 'https://upload.wikimedia.org/wikipedia/commons/6/6f/Pocari_Sweat_logo.png',
        's2p' => 'https://www.ssprimadaya.co.id/images/s2p/logo.png',
        'dynamix' => '',
    ];

    public function serve($name = null)
    {
        if (!$name || !isset(self::LOGOS[$name])) {
            return $this->response
                ->setStatusCode(404)
                ->setContentType('application/json')
                ->setJSON(['status' => 'error', 'message' => 'Logo not found']);
        }

        $url = self::LOGOS[$name];
        $data = $this->fetchUrl($url);

        if (!$data) {
            return $this->response
                ->setStatusCode(502)
                ->setContentType('application/json')
                ->setJSON(['status' => 'error', 'message' => 'Failed to fetch logo']);
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($data);

        return $this->response
            ->setHeader('Content-Type', $mime ?: 'image/png')
            ->setHeader('Content-Length', (string) strlen($data))
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setBody($data);
    }

    private function fetchUrl($url)
    {
        // Try file_get_contents first
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36\r\n",
                'timeout' => 15,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];
        $context = stream_context_create($opts);

        $data = @file_get_contents($url, false, $context);
        if ($data !== false) {
            return $data;
        }

        // Fallback: try curl
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ]);
            $data = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $data) {
                return $data;
            }
        }

        return null;
    }
}
