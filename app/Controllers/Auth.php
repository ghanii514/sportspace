<?php namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\I18n\Time; // Tidak wajib, tapi bagus untuk timestamps

class Auth extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url']);
    }

    //REGISTER
    public function register()
    {
        $data = [
            'title' => 'Sign Up'
        ];
            return view('auth/register', $data);
    }

    public function saveRegister()
{
    // Load helper string untuk token
    helper('text');

    // Aturan Validasi (tetap sama)
    $rules = [
        'username' => 'required|min_length[3]|max_length[20]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]|max_length[200]',
        'pass_confirm' => 'matches[password]'
    ];

    if (!$this->validate($rules)) {
        $data = [
            'title' => 'Sign Up',
            'validation' => $this->validator
        ];
        return view('auth/register', $data);
    }

    // Jika validasi sukses
    $model = new UserModel();
    $token = random_string('alnum', 32); // Buat token acak 32 karakter

    $data = [
        'username' => $this->request->getPost('username'),
        'email' => $this->request->getPost('email'),
        'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        'verification_token' => $token, // Simpan token
        'is_active' => 0 // Set user tidak aktif
    ];

    // Kirim email SEBELUM menyimpan, untuk jaga-jaga jika email gagal
    if ($this->_sendVerificationEmail($data['email'], $token)) {

        $model->save($data); // Simpan data ke DB

        // Ubah pesan sukses
        session()->setFlashdata('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi.');
        return redirect()->to('/login');

    } else {
        // Jika email gagal terkirim
        session()->setFlashdata('msg', 'Registrasi gagal. Tidak dapat mengirim email verifikasi.');
        return redirect()->to('/register')->withInput();
    }
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

    //LOGIN
    public function login()
    {
        $data = [
            'title' => 'Sign In'
        ];

        return view('auth/login', $data);
    }

    public function authLogin()
{
    $session = session();
    $model = new UserModel();

    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    $data = $model->where('email', $email)->first();

    if ($data) {

        // --- TAMBAHKAN PENGECEKAN INI ---
        if ($data['is_active'] == 0) {
            $session->setFlashdata('msg', 'Akun Anda belum aktif. Silakan cek email verifikasi.');
            return redirect()->to('/login');
        }
        // --- AKHIR PENGECEKAN ---

        $pass = $data['password'];
        $verify_pass = password_verify($password, $pass);

        if ($verify_pass) {
            // ... (sisa logika session Anda, ini sudah benar)
            $ses_data = [
                'user_id' => $data['id'],
                'username' => $data['username'],
                'email' => $data['email'],
                'logged_in' => TRUE
            ];
            $session->set($ses_data);
            return redirect()->to('/');
        } else {
            $session->setFlashdata('msg', 'Password salah.');
            return redirect()->to('/login');
        }
    } else {
        $session->setFlashdata('msg', 'Email tidak ditemukan.');
        return redirect()->to('/login');
    }
}

    // LOGOUT
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
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
        return redirect()->to('/login');

    } else {
        // Jika token salah/tidak ada
        session()->setFlashdata('msg', 'Token verifikasi tidak valid atau kedaluwarsa.');
        return redirect()->to('/login');
    }
}
}
