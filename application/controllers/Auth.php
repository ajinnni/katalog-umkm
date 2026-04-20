<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    const FONNTE_TOKEN   = 'aFUCu1xfixcmZjDD3XS6'; // token asli Fonnte
    const OTP_EXPIRE_MIN = 5;
    const MAX_OTP_TRY    = 3;

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    // -------------------------------------------------------
    // LOGIN
    // -------------------------------------------------------
    public function login() {
        if ($this->session->userdata('user_id')) {
            $this->_redirect_by_role();
            return;
        }
        $this->load->view('auth/login', ['title' => 'Login']);
    }

    public function proses_login() {
        $no_wa    = $this->_format_wa($this->input->post('no_wa', TRUE));
        $password = $this->input->post('password');

        if (!$no_wa || !$password) {
            $this->session->set_flashdata('error', 'No WA dan password wajib diisi.');
            redirect('index.php/login');
        }

        $user = $this->User_model->get_by_wa($no_wa);

        if (!$user || !password_verify($password, $user->password)) {
            $this->session->set_flashdata('error', 'No WA atau password salah.');
            redirect('index.php/login');
        }

        if (!$user->is_verified) {
            $this->session->set_flashdata('error', 'Akun belum diverifikasi.');
            redirect('index.php/login');
        }

        $this->session->set_userdata([
            'user_id' => $user->id,
            'nama'    => $user->nama,
            'no_wa'   => $user->no_wa,
            'role'    => $user->role,
        ]);

        $this->_redirect_by_role();
    }

    // -------------------------------------------------------
    // REGISTER
    // -------------------------------------------------------
    public function register() {
        if ($this->session->userdata('user_id')) {
            $this->_redirect_by_role();
            return;
        }
        $this->load->view('auth/register', ['title' => 'Daftar']);
    }

    public function proses_register() {
        $nama     = $this->input->post('nama', TRUE);
        $no_wa    = $this->_format_wa($this->input->post('no_wa', TRUE));
        $password = $this->input->post('password');
        $role     = $this->input->post('role');

        if (!$nama || !$no_wa || !$password || !$role) {
            $this->session->set_flashdata('error', 'Semua field wajib diisi.');
            redirect('index.php/register');
        }

        if (strlen($password) < 8) {
            $this->session->set_flashdata('error', 'Password minimal 8 karakter.');
            redirect('index.php/register');
        }

        if (!in_array($role, ['user', 'umkm'])) {
            $this->session->set_flashdata('error', 'Role tidak valid.');
            redirect('index.php/register');
        }

        if ($this->User_model->get_by_wa($no_wa)) {
            $this->session->set_flashdata('error', 'Nomor WhatsApp sudah terdaftar.');
            redirect('index.php/register');
        }

        $user_id = $this->User_model->create([
            'nama'        => $nama,
            'no_wa'       => $no_wa,
            'password'    => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
            'role'        => $role,
            'is_verified' => 0,
        ]);

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->User_model->set_otp($user_id, $otp);
        $this->_kirim_otp($no_wa, $otp);

        $this->session->set_userdata([
            'reg_user_id' => $user_id,
            'reg_no_wa'   => $no_wa,
            'reg_otp'     => $otp,
        ]);

        redirect('index.php/verifikasi-otp');
    }

    // -------------------------------------------------------
    // OTP
    // -------------------------------------------------------
    public function verifikasi_otp() {
        if (!$this->session->userdata('reg_user_id')) {
            redirect('index.php/register');
        }
        $this->load->view('auth/otp', ['title' => 'Verifikasi OTP']);
    }

    public function proses_otp() {
        $user_id = $this->session->userdata('reg_user_id');
        if (!$user_id) {
            redirect('index.php/register');
        }

        $otp_input = trim($this->input->post('otp'));
        $user      = $this->User_model->get($user_id);

        if (!$user) {
            redirect('index.php/register');
        }

        if (strtotime($user->otp_expired) < time()) {
            $this->session->set_flashdata('error', 'OTP kadaluarsa. Minta kirim ulang.');
            redirect('index.php/verifikasi-otp');
        }

        if ($otp_input !== $user->otp_code) {
            $this->session->set_flashdata('error', 'OTP salah. Coba lagi.');
            redirect('index.php/verifikasi-otp');
        }

        $this->User_model->verify($user_id);

        $this->session->unset_userdata(['reg_user_id', 'reg_no_wa', 'reg_otp']);

        $this->session->set_userdata([
            'user_id' => $user->id,
            'nama'    => $user->nama,
            'no_wa'   => $user->no_wa,
            'role'    => $user->role,
        ]);

        $this->session->set_flashdata('success', 'Akun berhasil diverifikasi!');
        $this->_redirect_by_role();
    }

    public function resend_otp() {
        $user_id = $this->session->userdata('reg_user_id');
        $no_wa   = $this->session->userdata('reg_no_wa');

        if (!$user_id || !$no_wa) {
            redirect('index.php/register');
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->User_model->set_otp($user_id, $otp);
        $this->_kirim_otp($no_wa, $otp);
        $this->session->set_userdata('reg_otp', $otp);

        $this->session->set_flashdata('info', 'OTP baru sudah dikirim.');
        redirect('index.php/verifikasi-otp');
    }

    // -------------------------------------------------------
    // LOGOUT
    // -------------------------------------------------------
    public function logout() {
        $this->session->sess_destroy();
        redirect('index.php/login');
    }

    // -------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------

    private function _kirim_otp($no_wa, $otp) {
        // Hanya skip kalau token masih placeholder
        if (self::FONNTE_TOKEN === 'ISI_TOKEN_FONNTE_KAMU') {
            log_message('error', 'FONNTE: Token belum diisi!');
            return;
        }

        $ch = curl_init('https://api.fonnte.com/send');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => ['Authorization: ' . self::FONNTE_TOKEN],
            CURLOPT_POSTFIELDS     => [
                'target'  => $no_wa,
                'message' => "Kode OTP UMKM Catalog: *{$otp}*\nBerlaku 5 menit. Jangan bagikan kode ini kepada siapapun!",
            ],
        ]);
        $result = curl_exec($ch);
        $error  = curl_error($ch);
        curl_close($ch);

        log_message('debug', 'FONNTE target: '   . $no_wa);
        log_message('debug', 'FONNTE response: ' . $result);
        if ($error) log_message('error', 'FONNTE curl error: ' . $error);
    }

    private function _redirect_by_role() {
        switch ($this->session->userdata('role')) {
            case 'admin': redirect('index.php/admin');          break;
            case 'umkm':  redirect('index.php/umkm/dashboard'); break;
            default:      redirect('index.php/user');
        }
    }

    private function _format_wa($no) {
        $no = preg_replace('/\D/', '', $no);
        if (substr($no, 0, 2) === '08') $no = '628' . substr($no, 2);
        if (substr($no, 0, 1) === '8')  $no = '62'  . $no;
        return $no;
    }
}