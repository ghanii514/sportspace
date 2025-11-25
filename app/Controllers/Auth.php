<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\I18n\Time; // Tidak wajib, tapi bagus untuk timestamps
use Myth\Auth\AuthTrait;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\LoginModel;
class Auth extends BaseController
{
    use AuthTrait;
    public function __construct()
    {
        helper(['form', 'url', 'auth']);
        $this->auth = service('authentication');
    }

    //REGISTER

   
public function saveRegister()
{
    $rules = [
        'username' => 'required|min_length[3]|max_length[20]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]',
        'pass_confirm' => 'required|matches[password]',
    ];

    if (! $this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Siapkan entity user
    $user = new User([
        'username' => $this->request->getPost('username'),
        'email'    => $this->request->getPost('email'),
        'password' => $this->request->getPost('password'),
    ]);

    $model = new UserModel();

    // Simpan user (password otomatis di-hash oleh Entity)
    $model->save($user);

    // Flash message bawaan Myth/Auth
    session()->setFlashdata('message', 'Registrasi berhasil! Kamu bisa login sekarang.');

    return redirect()->to('/auth/login');
}

    // --- BUAT FUNGSI BARU INI DI DALAM CLASS AUTH ---
    private function _sendVerificationEmail($userEmail, $token)
    {
        $email = \Config\Services::email(); // Load library email

        $email->setFrom(env('email.fromEmail'), env('email.fromName'));
        $email->setTo($userEmail);
        $email->setSubject('Verifikasi Akun SportSpace Anda');

        // Buat isi email (bisa dibuat lebih cantik dengan HTML)
        $message = "Halo,<br><br>"
            . "Terima kasih telah mendaftar di SportSpace. "
            . "Silakan klik link di bawah ini untuk mengaktifkan akun Anda:<br><br>"
            . "<a href='" . base_url('/verify/' . $token) . "' style='padding: 10px 15px; background-color: #2ECC71; color: white; text-decoration: none; border-radius: 5px;'>"
            . "Aktifkan Akun Saya"
            . "</a><br><br>"
            . "Jika Anda tidak bisa mengklik link, salin URL ini ke browser Anda:<br>"
            . base_url('/verify/' . $token);

        $email->setMessage($message);

        if ($email->send()) {
            return true;
        } else {
            // Jika Anda di mode development, ini akan menampilkan error
            log_message('error', $email->printDebugger(['headers']));
            return false;
        }
    }

    public function authLogin()
{
    $rules = [
        'email'    => 'required|valid_email',
        'password' => 'required'
    ];

    if (! $this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Coba login dengan AuthTrait
    $credentials = [
        'email'    => $this->request->getPost('email'),
        'password' => $this->request->getPost('password'),
    ];

    if (! $this->authenticate->attempt($credentials)) {

        // Jika login gagal → pesan bawaan Myth/Auth
        return redirect()->back()
            ->withInput()
            ->with('errors', ['Login gagal! Periksa email dan password Anda.']);
    }

    // Cek is_active (kalau tabel users kamu punya field ini)
    if (!auth()->user()->is_active) {
        auth()->logout();
        return redirect()->back()->with('errors', 
            ['Akun Anda belum aktif. Silakan cek email verifikasi.']
        );
    }

    // Login sukses → redirect ke halaman utama
    return redirect()->to('/');
}



    public function verify($token)
    {
        $model = new UserModel();

        // Cari user berdasarkan token
        $user = $model->where('verification_token', $token)->first();

        if ($user) {
            // Jika user ditemukan

            // Cek apakah user sudah aktif (mungkin link diklik 2x)
            if ($user['is_active'] == 1) {
                session()->setFlashdata('success', 'Akun Anda sudah aktif. Silakan login.');
                return redirect()->to('/login');
            }

            // Aktifkan user
            $model->update($user['id'], [
                'is_active' => 1,
                'verification_token' => null // Hapus token agar tidak bisa dipakai lagi
            ]);

            session()->setFlashdata('success', 'Verifikasi akun berhasil! Silakan login.');
            return redirect()->to('/auth/login');

        } else {
            // Jika token salah/tidak ada
            session()->setFlashdata('msg', 'Token verifikasi tidak valid atau kedaluwarsa.');
            return redirect()->to('/auth/login');
        }
    }
}
