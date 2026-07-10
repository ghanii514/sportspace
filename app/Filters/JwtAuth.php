<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('Authorization');

        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return service('response')
                ->setStatusCode(401)
                ->setContentType('application/json')
                ->setJSON(['status' => 'error', 'message' => 'Token tidak ditemukan']);
        }

        $token = substr($header, 7);
        $key = 'sportspace_secret_key_2025_very_long_secret_for_hs256';

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $request->user = $decoded;
        } catch (\Exception $e) {
            return service('response')
                ->setStatusCode(401)
                ->setContentType('application/json')
                ->setJSON(['status' => 'error', 'message' => 'Token tidak valid atau kedaluwarsa']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
