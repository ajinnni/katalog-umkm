<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $data = [];

    public function __construct() {
        parent::__construct();

        $this->load->helper(['url']);
        $this->load->library(['session']);

        $this->data['base_url']  = base_url();
        $this->data['site_name'] = 'UMKM Catalog';
    }

    // 🔥 INI YANG KAMU KURANG (WAJIB ADA)
    protected function render($view, $data = []) {
        $merged = array_merge($this->data, $data);

        $this->load->view('layouts/header', $merged);
        $this->load->view($view, $merged);
        $this->load->view('layouts/footer', $merged);
    }
}

/* =========================
   AUTH BASE CONTROLLER
   ========================= */
class Auth_Controller extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $method  = $this->router->fetch_method();
        $user_id = $this->session->userdata('user_id');
        $stage   = $this->session->userdata('auth_stage');

        // Halaman yang boleh diakses tanpa login sama sekali
        $public_pages = [
            'login',
            'proses_login',
            'register',
            'proses_register',
        ];

        // Halaman khusus OTP (termasuk logout supaya bisa keluar saat nyangkut)
        $otp_pages = [
            'verifikasi_otp',
            'proses_otp',
            'resend_otp',
            'logout',
        ];

        // ── KASUS 1: Belum login sama sekali ──────────────────────────────
        if (!$user_id) {
            if (!in_array($method, $public_pages)) {
                redirect('login');
                exit;
            }
            // Lanjut ke halaman public
            return;
        }

        // ── KASUS 2: Login ada, stage = pending_otp ───────────────────────
        // User harus selesaikan OTP dulu, tidak boleh kemana-mana
        if ($stage === 'pending_otp') {
            if (!in_array($method, $otp_pages)) {
                redirect('verifikasi-otp');
                exit;
            }
            // Lanjut ke halaman OTP
            return;
        }

        // ── KASUS 3: Login ada, stage = null / complete ───────────────────
        // User sudah login penuh → jangan bisa balik ke halaman auth
        if (in_array($method, $public_pages)) {
            $this->_redirect_by_role();
            exit;
        }

        $this->data['user'] = $this->session->userdata();
    }

    // Dipanggil dari Auth_Controller guard — tidak di-wrap redirect()
    protected function _redirect_by_role() {
        $role = $this->session->userdata('role');
        switch ($role) {
            case 'admin': redirect('admin');           break;
            case 'umkm':  redirect('umkm/dashboard'); break;
            default:      redirect('user');
        }
        exit; // Defensive stop
    }

    
}