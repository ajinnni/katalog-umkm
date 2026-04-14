<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    const MAX_LOGIN_ATTEMPTS = 5;
    const LOCKOUT_MINUTES    = 15;
    const OTP_EXPIRE_MINUTES = 5;
    const MAX_OTP_ATTEMPTS   = 3;
    const OTP_RESEND_SECONDS = 60;
    const FONNTE_TOKEN       = 'ISI_TOKEN_FONNTE_KAMU'; // ← ganti token asli

    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'Otp_model']);
    }

    // ==========================================
    // LOGIN — No WA + Password, langsung masuk
    // ==========================================

    public function login() {
        if ($this->session->userdata('user_id') &&
            $this->session->userdata('auth_stage') === 'complete') {
            $this->_redirect_by_role();
        }
        $this->load->view('auth/login', ['title' => 'Login']);
    }

    public function proses_login() {
        $identifier = $this->input->post('identifier', TRUE);
        $password   = $this->input->post('password');

        if (!$identifier || !$password) {
            $this->session->set_flashdata('error', 'No WA/username dan password wajib diisi.');
            redirect('index.php/login');
        }

        $user = $this->User_model->get_by_identifier($identifier);

        if (!$user) {
            $this->session->set_flashdata('error', 'Akun tidak ditemukan.');
            redirect('index.php/login');
        }

        if ($this->_is_locked($user)) {
            $menit = ceil((strtotime($user->locked_until) - time()) / 60);
            $this->session->set_flashdata('error',
                "Akun dikunci. Coba lagi dalam {$menit} menit.");
            redirect('index.php/login');
        }

        if (!password_verify($password, $user->password)) {
            $this->_increment_login_attempts($user);
            $sisa = self::MAX_LOGIN_ATTEMPTS - ($user->login_attempts + 1);
            $msg  = $sisa > 0
                ? "Password salah. Sisa percobaan: {$sisa}."
                : "Akun dikunci " . self::LOCKOUT_MINUTES . " menit.";
            $this->session->set_flashdata('error', $msg);
            redirect('index.php/login');
        }

        // Login berhasil langsung
        $this->User_model->reset_login_attempts($user->id);
        $this->session->set_userdata([
            'user_id'    => $user->id,
            'nama'       => $user->nama,
            'no_wa'      => $user->no_wa,
            'role'       => $user->role,
            'auth_stage' => 'complete',
        ]);

        $this->_redirect_by_role();
    }

    // ==========================================
    // REGISTER — Nama + No WA + Password + Role
    // → OTP verifikasi nomor setelah daftar
    // ==========================================

    public function register() {
        $this->load->view('auth/register', ['title' => 'Daftar Akun']);
    }

    public function proses_register() {
        $this->form_validation->set_rules('nama',     'Nama',     'required|trim|min_length[3]');
        $this->form_validation->set_rules('no_wa',    'No WA',    'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('role',     'Role',     'required|in_list[user,umkm]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('index.php/register');
        }

        $no_wa = $this->_format_wa($this->input->post('no_wa', TRUE));

        if ($this->User_model->get_by_wa($no_wa)) {
            $this->session->set_flashdata('error', 'Nomor WhatsApp sudah terdaftar.');
            redirect('index.php/register');
        }

        $user_id = $this->User_model->create([
            'nama'        => $this->input->post('nama', TRUE),
            'no_wa'       => $no_wa,
            'password'    => password_hash(
                                $this->input->post('password'),
                                PASSWORD_BCRYPT,
                                ['cost' => 12]
                             ),
            'role'        => $this->input->post('role'),
            'is_verified' => 0,
        ]);

        // Set session partial untuk OTP
        $this->session->set_userdata([
            'user_id'    => $user_id,
            'nama'       => $this->input->post('nama', TRUE),
            'no_wa'      => $no_wa,
            'role'       => $this->input->post('role'),
            'auth_stage' => 'pending_otp',
        ]);

        // Langsung kirim OTP ke nomor yang baru didaftarkan
        $this->_auto_kirim_otp($user_id, $no_wa);

        redirect('index.php/verifikasi-otp');
    }

    // ==========================================
    // OTP — Hanya untuk verifikasi setelah register
    // ==========================================

    public function verifikasi_otp() {
        $this->_require_stage('pending_otp');
        $this->load->view('auth/otp', ['title' => 'Verifikasi OTP']);
    }

    public function proses_otp() {
        $this->_require_stage('pending_otp');

        $otp_input  = trim($this->input->post('otp'));
        $user_id    = $this->session->userdata('user_id');
        $otp_record = $this->Otp_model->get_active($user_id);

        if (!$otp_record) {
            $this->session->set_flashdata('error', 'OTP tidak ditemukan. Minta kirim ulang.');
            redirect('index.php/verifikasi-otp');
        }

        if (strtotime($otp_record->expired_at) < time()) {
            $this->Otp_model->invalidate($otp_record->id);
            $this->session->set_flashdata('error', 'OTP kadaluarsa. Klik kirim ulang.');
            redirect('index.php/verifikasi-otp');
        }

        if ($otp_record->attempts >= self::MAX_OTP_ATTEMPTS) {
            $this->Otp_model->invalidate($otp_record->id);
            $this->session->set_flashdata('error', 'Terlalu banyak percobaan. Minta OTP baru.');
            redirect('index.php/verifikasi-otp');
        }

        if (!password_verify($otp_input, $otp_record->kode)) {
            $this->Otp_model->increment_attempts($otp_record->id);
            $sisa = self::MAX_OTP_ATTEMPTS - ($otp_record->attempts + 1);
            $this->session->set_flashdata('error', "OTP salah. Sisa percobaan: {$sisa}.");
            redirect('index.php/verifikasi-otp');
        }

        // OTP BENAR — verifikasi akun
        $this->Otp_model->mark_used($otp_record->id);
        $this->User_model->verify($user_id);
        $this->session->set_userdata([
            'auth_stage'  => 'complete',
            'is_verified' => 1,
        ]);
        $this->session->unset_userdata('otp_plain');

        $this->session->set_flashdata('success', 'Akun berhasil diverifikasi! Selamat datang.');
        $this->_redirect_by_role();
    }

    public function resend_otp() {
        $this->_require_stage('pending_otp');

        $user_id = $this->session->userdata('user_id');
        $no_wa   = $this->session->userdata('no_wa');

        if (!$no_wa) {
            redirect('index.php/register');
        }

        $last_otp = $this->Otp_model->get_latest($user_id);
        if ($last_otp) {
            $elapsed = time() - strtotime($last_otp->created_at);
            if ($elapsed < self::OTP_RESEND_SECONDS) {
                $tunggu = self::OTP_RESEND_SECONDS - $elapsed;
                $this->session->set_flashdata('error',
                    "Tunggu {$tunggu} detik sebelum kirim OTP lagi.");
                redirect('index.php/verifikasi-otp');
            }
        }

        $this->_auto_kirim_otp($user_id, $no_wa);
        $this->session->set_flashdata('info', 'OTP baru sudah dikirim.');
        redirect('index.php/verifikasi-otp');
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('index.php/login');
    }

    // ==========================================
    // PRIVATE HELPERS
    // ==========================================

    private function _auto_kirim_otp($user_id, $no_wa) {
        $otp_plain  = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $otp_hashed = password_hash($otp_plain, PASSWORD_BCRYPT);
        $expired_at = date('Y-m-d H:i:s', strtotime('+' . self::OTP_EXPIRE_MINUTES . ' minutes'));

        $this->Otp_model->invalidate_all($user_id);
        $this->Otp_model->create([
            'user_id'    => $user_id,
            'kode'       => $otp_hashed,
            'no_wa'      => $no_wa,
            'expired_at' => $expired_at,
        ]);

        // Selalu simpan OTP di session untuk ditampilkan di layar
        $this->session->set_userdata('otp_plain', $otp_plain);

        $hasil = $this->_kirim_via_fonnte($no_wa, $otp_plain);
        if (!$hasil['success']) {
            $this->session->set_flashdata('info',
                'Gagal kirim WA (' . $hasil['message'] . '). Gunakan kode di bawah.');
        }
    }

    private function _kirim_via_fonnte($no_wa, $otp) {
        $pesan = "Kode OTP UMKM Catalog: *{$otp}*\n" .
                 "Berlaku " . self::OTP_EXPIRE_MINUTES . " menit.\n" .
                 "Jangan bagikan ke siapapun!";

        $ch = curl_init('https://api.fonnte.com/send');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => ['Authorization: ' . self::FONNTE_TOKEN],
            CURLOPT_POSTFIELDS     => [
                'target'  => $no_wa,
                'message' => $pesan,
                'delay'   => '2',
            ],
        ]);

        $response = curl_exec($ch);
        $err      = curl_error($ch);
        curl_close($ch);

        if ($err) {
            log_message('error', 'Fonnte error: ' . $err);
            return ['success' => false, 'message' => 'Koneksi gagal.'];
        }

        $data = json_decode($response, true);
        log_message('debug', 'Fonnte: ' . $response);

        if (!empty($data['status']) && $data['status'] === true) {
            return ['success' => true];
        }

        return ['success' => false, 'message' => $data['reason'] ?? 'Unknown error'];
    }

    private function _require_stage($stage) {
        if (!$this->session->userdata('user_id') ||
            $this->session->userdata('auth_stage') !== $stage) {
            redirect('index.php/login');
        }
    }

    private function _is_locked($user) {
        if (!$user->locked_until) return false;
        return strtotime($user->locked_until) > time();
    }

    private function _increment_login_attempts($user) {
        $attempts = $user->login_attempts + 1;
        $update   = ['login_attempts' => $attempts];
        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            $update['locked_until'] = date('Y-m-d H:i:s',
                strtotime('+' . self::LOCKOUT_MINUTES . ' minutes'));
        }
        $this->db->update('users', $update, ['id' => $user->id]);
    }

    private function _redirect_by_role() {
        switch ($this->session->userdata('role')) {
            case 'admin': redirect('index.php/admin'); break;
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