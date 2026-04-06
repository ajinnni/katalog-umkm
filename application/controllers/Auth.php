<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function login() {
        if ($this->session->userdata('user_id')) {
            $this->_redirect_by_role();
        }

        if ($this->input->post('no_wa')) {
            $no_wa = $this->_format_wa($this->input->post('no_wa', TRUE));
            $user  = $this->User_model->get_by_wa($no_wa);

            if (!$user) {
                $this->session->set_flashdata('error', 'Nomor WhatsApp tidak terdaftar.');
                redirect('login');
            }

            $otp = rand(100000, 999999);
            $this->User_model->set_otp($user->id, $otp);
            $this->_kirim_otp($no_wa, $otp);

            $this->session->set_flashdata('info', 'OTP kamu: <strong>' . $otp . '</strong>');
            $this->session->set_userdata('otp_user_id', $user->id);
            redirect('verifikasi-otp');
        }

        $this->render_public('auth/login', ['title' => 'Login']);
    }

    public function register() {
        if ($this->input->post('nama')) {
            $this->form_validation->set_rules('nama',  'Nama',  'required|trim');
            $this->form_validation->set_rules('no_wa', 'No WA', 'required|trim');
            $this->form_validation->set_rules('role',  'Role',  'required|in_list[user,umkm]');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('register');
            }

            $no_wa = $this->_format_wa($this->input->post('no_wa', TRUE));
            if ($this->User_model->get_by_wa($no_wa)) {
                $this->session->set_flashdata('error', 'Nomor sudah terdaftar.');
                redirect('register');
            }

            $otp = rand(100000, 999999);
            $user_id = $this->User_model->create([
                'nama'        => $this->input->post('nama', TRUE),
                'no_wa'       => $no_wa,
                'role'        => $this->input->post('role', TRUE),
                'otp_code'    => $otp,
                'otp_expired' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
                'is_verified' => 0,
            ]);

            $this->_kirim_otp($no_wa, $otp);
            $this->session->set_flashdata('info', 'OTP kamu: <strong>' . $otp . '</strong>');
            $this->session->set_userdata('otp_user_id', $user_id);
            redirect('verifikasi-otp');
        }

        $this->render_public('auth/register', ['title' => 'Daftar']);
    }

    public function verifikasi_otp() {
        $user_id = $this->session->userdata('otp_user_id');
        if (!$user_id) redirect('login');

        if ($this->input->post('otp')) {
            $otp  = $this->input->post('otp', TRUE);
            $user = $this->User_model->get($user_id);

            if ($user->otp_code == $otp && strtotime($user->otp_expired) >= time()) {
                $this->User_model->verify($user->id);
                $this->session->unset_userdata('otp_user_id');
                $this->_set_session($user);
                $this->_redirect_by_role();
            } else {
                $this->session->set_flashdata('error', 'OTP salah atau kadaluarsa.');
                redirect('verifikasi-otp');
            }
        }

        $this->render_public('auth/otp', ['title' => 'Verifikasi OTP']);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }

    private function _set_session($user) {
        $this->session->set_userdata([
            'user_id' => $user->id,
            'nama'    => $user->nama,
            'no_wa'   => $user->no_wa,
            'role'    => $user->role,
        ]);
    }

    private function _redirect_by_role() {
        switch ($this->session->userdata('role')) {
            case 'admin': redirect('admin'); break;
            case 'umkm':  redirect('umkm/dashboard'); break;
            default:      redirect('index.php/');
        }
    }

    private function _format_wa($no) {
        $no = preg_replace('/\D/', '', $no);
        if (substr($no, 0, 2) === '08') $no = '628' . substr($no, 2);
        if (substr($no, 0, 1) === '8')  $no = '62'  . $no;
        return $no;
    }

    private function _kirim_otp($no_wa, $otp) {
        // Uncomment kalau sudah punya token Fonnte:
        // $ch = curl_init('https://api.fonnte.com/send');
        // curl_setopt_array($ch, [
        //     CURLOPT_POST           => TRUE,
        //     CURLOPT_RETURNTRANSFER => TRUE,
        //     CURLOPT_HTTPHEADER     => ['Authorization: TOKEN_FONNTE_KAMU'],
        //     CURLOPT_POSTFIELDS     => ['target' => $no_wa, 'message' => "OTP: {$otp}"],
        // ]);
        // curl_exec($ch);
        // curl_close($ch);

        log_message('debug', "OTP untuk {$no_wa}: {$otp}");
    }
}