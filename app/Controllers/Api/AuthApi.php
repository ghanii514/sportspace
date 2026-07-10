<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Password;

class AuthApi extends BaseController
{
    private function getInput(): array
    {
        return $this->request->getJSON(true) ?? $this->request->getPost() ?? [];
    }

    public function login()
    {
        $input = $this->getInput();

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules, $input)) {
            return json_response(null, 400, $this->validator->getErrors());
        }

        $email    = $input['email'];
        $password = $input['password'];

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user || !Password::verify($password, $user->password_hash)) {
            return json_response(null, 401, 'Email atau password salah');
        }

        if (!$user->active) {
            return json_response(null, 403, 'Akun belum diaktivasi. Cek email Anda.');
        }

        $groupModel = new GroupModel();
        $groups = $groupModel->getGroupsForUser($user->id);

        $key = 'sportspace_secret_key_2025_very_long_secret_for_hs256';
        $iat = time();
        $exp = $iat + (60 * 60 * 24 * 7);

        $payload = [
            'iss' => 'sportspace',
            'iat' => $iat,
            'exp' => $exp,
            'sub' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
            'groups' => array_column($groups, 'name'),
        ];

        try {
            $token = JWT::encode($payload, $key, 'HS256');
        } catch (\Exception $e) {
            return json_response(null, 500, 'JWT: ' . $e->getMessage() . ' (key_len=' . strlen($key) . ')');
        }

        return json_response([
            'token' => $token,
            'user' => [
                'id'       => $user->id,
                'email'    => $user->email,
                'username' => $user->username,
                'groups'   => array_column($groups, 'name'),
                'profile_picture' => $user->profile_picture,
            ],
        ]);
    }

    public function register()
    {
        $input = $this->getInput();

        $rules = [
            'email'    => 'required|valid_email',
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules, $input)) {
            return json_response(null, 400, $this->validator->getErrors());
        }

        $userModel = new UserModel();

        // Cek apakah email sudah terdaftar
        $existing = $userModel->where('email', $input['email'])->first();

        if ($existing) {
            // Jika akun sudah aktif, tolak
            if ($existing->active) {
                return json_response(null, 409, 'Email sudah terdaftar');
            }

            // Jika akun belum aktif, hapus data lama agar bisa daftar ulang
            $userModel->delete($existing->id);
        }

        // Cek apakah username sudah terdaftar (tapi email berbeda)
        $existingUsername = $userModel->where('username', $input['username'])
            ->where('email !=', $input['email'])
            ->first();

        if ($existingUsername) {
            return json_response(null, 409, 'Username sudah digunakan');
        }

        $user = new User([
            'email'    => $input['email'],
            'username' => $input['username'],
            'password' => $input['password'],
            'active'   => 1,
        ]);

        if (!$userModel->save($user)) {
            return json_response(null, 500, 'Gagal menyimpan user');
        }

        $userId = $userModel->getInsertID();

        $authorize = service('authorization');
        $authorize->addUserToGroup($userId, 'user');

        return json_response(['id' => $userId], 201, 'Registrasi berhasil, silakan login.');
    }

    public function forgotPassword()
    {
        $input = $this->getInput();

        $rules = [
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules, $input)) {
            return json_response(null, 400, $this->validator->getErrors());
        }

        $email = $input['email'];
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        // Tetap return sukses walau email tidak ditemukan (keamanan)
        if (!$user || !$user->active) {
            return json_response(null, 200, 'Jika email terdaftar, tautan reset password telah dikirim.');
        }

        $user->generateResetHash();
        $userModel->save($user);

        $resetter = service('resetter');
        $sent = $resetter->send($user);

        if (!$sent) {
            log_message('error', 'Gagal kirim email reset password ke: ' . $email);
        }

        return json_response(null, 200, 'Jika email terdaftar, tautan reset password telah dikirim.');
    }

    public function me()
    {
        $userModel = new UserModel();
        $user = $userModel->find($this->request->user->sub);

        if (!$user) {
            return json_response(null, 404, 'User tidak ditemukan');
        }

        $groupModel = new GroupModel();
        $groups = $groupModel->getGroupsForUser($user->id);

        return json_response([
            'id'       => $user->id,
            'email'    => $user->email,
            'username' => $user->username,
            'groups'   => array_column($groups, 'name'),
            'profile_picture' => $user->profile_picture,
        ]);
    }

    public function updateProfile()
    {
        $input = $this->getInput();

        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username,id,' . $this->request->user->sub . ']',
        ];

        if (!$this->validate($rules, $input)) {
            return json_response(null, 400, $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->find($this->request->user->sub);

        if (!$user) {
            return json_response(null, 404, 'User tidak ditemukan');
        }

        if (!$userModel->update($this->request->user->sub, ['username' => $input['username']])) {
            return json_response(null, 500, 'Gagal memperbarui profil');
        }

        return json_response([
            'id'       => $user->id,
            'email'    => $user->email,
            'username' => $input['username'],
        ]);
    }

    public function updateProfilePhoto()
    {
        $file = $this->request->getFile('photo');

        if (!$file || !$file->isValid()) {
            return json_response(null, 400, 'File foto tidak valid');
        }

        if ($file->getSize() > 2 * 1024 * 1024) {
            return json_response(null, 400, 'Ukuran foto maksimal 2 MB');
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return json_response(null, 400, 'Hanya file JPG, PNG, atau WebP');
        }

        $uploadPath = FCPATH . 'img/users';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $ext = $file->getExtension() ?: 'jpg';
        $filename = 'user_' . $this->request->user->sub . '_' . time() . '.' . $ext;

        $file->move($uploadPath, $filename, true);

        $userModel = new UserModel();
        $userModel->update($this->request->user->sub, ['profile_picture' => $filename]);

        return json_response([
            'profile_picture' => $filename,
        ]);
    }
}
