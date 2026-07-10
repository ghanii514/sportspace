<?php

if (!function_exists('json_response')) {
    function json_response($data = null, int $code = 200, $message = null)
    {
        $body = [];
        if ($code >= 200 && $code < 300) {
            $body['status'] = 'success';
            $body['data'] = $data;
        } else {
            $body['status'] = 'error';
            if (is_array($message)) {
                $body['message'] = 'Validasi gagal';
                $body['errors'] = $message;
            } else {
                $body['message'] = $message ?? 'Terjadi kesalahan';
            }
        }

        return service('response')
            ->setStatusCode($code)
            ->setContentType('application/json')
            ->setJSON($body);
    }
}
