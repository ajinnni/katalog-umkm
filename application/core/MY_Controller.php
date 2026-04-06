<?php
// application/core/MY_Controller.php
// Controller dasar — semua controller inherit dari sini

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $data = [];

    public function __construct() {
        parent::__construct();
        $this->data['base_url']  = base_url();
        $this->data['site_name'] = 'UMKM Catalog';
    }

    // Render view dengan layout SB Admin 2
    protected function render($view, $data = []) {
        $merged = array_merge($this->data, $data);
        $this->load->view('layouts/header', $merged);
        $this->load->view($view, $merged);
        $this->load->view('layouts/footer', $merged);
    }

    // Render halaman publik (tanpa sidebar admin)
    protected function render_public($view, $data = []) {
        $merged = array_merge($this->data, $data);
        $this->load->view('layouts/public_header', $merged);
        $this->load->view($view, $merged);
        $this->load->view('layouts/public_footer', $merged);
    }
}

// -----------------------------------------------
// Controller untuk halaman yang butuh login
// -----------------------------------------------
class Auth_Controller extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
        $this->data['user'] = $this->session->userdata();
    }
}

// -----------------------------------------------
// Controller khusus Admin
// -----------------------------------------------
class Admin_Controller extends Auth_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('role') !== 'admin') {
            show_error('Akses ditolak.', 403);
        }
    }
}

// -----------------------------------------------
// Controller khusus Pemilik UMKM
// -----------------------------------------------
class Umkm_Controller extends Auth_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('role') !== 'umkm') {
            show_error('Akses ditolak.', 403);
        }
    }
}