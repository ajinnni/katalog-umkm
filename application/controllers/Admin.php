<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['User_model', 'Umkm_model']);
        $this->load->library(['form_validation', 'upload']);
        $this->load->helper('wa'); // helper untuk format WA
    }

    // ------------------ DASHBOARD ------------------
    public function index() {
        $this->dashboard();
    }

    public function dashboard() {
        $data['title']       = 'Dashboard Admin';
        $data['total_user']  = $this->User_model->count_by_role('user');
        $data['total_umkm']  = $this->User_model->count_by_role('umkm');
        $data['total_admin'] = $this->User_model->count_by_role('admin');
        $this->render('admin/dashboard', $data);
    }

    // ------------------ KELOLA UMKM ------------------
    public function kelola_umkm() {
        $data['title']     = 'Kelola UMKM';
        $data['list_umkm'] = $this->Umkm_model->get_all();
        $this->render('admin/umkm/index', $data);
    }

    public function tambah_umkm() {
        $data['title']  = 'Tambah UMKM';
        $data['owners'] = $this->Umkm_model->get_available_owners();
        $this->render('admin/umkm/form', $data);
    }

    public function simpan_umkm() {
        $this->form_validation->set_rules('nama_toko',  'Nama Toko',  'required|trim');
        $this->form_validation->set_rules('user_id',    'Pemilik',    'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/umkm');
        }

        $foto = NULL;
        if (!empty($_FILES['foto']['name'])) {
            $foto = $this->_upload_foto();
            if (!$foto) {
                $this->session->set_flashdata('error', 'Gagal upload foto: ' . $this->upload->display_errors());
                redirect('admin/umkm');
            }
        }

        $this->Umkm_model->create([
            'user_id'    => $this->input->post('user_id'),
            'nama_toko'  => $this->input->post('nama_toko', TRUE),
            'deskripsi'  => $this->input->post('deskripsi', TRUE),
            'alamat'     => $this->input->post('alamat', TRUE),
            'no_wa_toko' => format_wa($this->input->post('no_wa_toko', TRUE)), // helper
            'foto'       => $foto,
            'is_active'  => 1,
        ]);

        $this->session->set_flashdata('success', 'UMKM berhasil ditambahkan.');
        redirect('admin/umkm');
    }

    public function edit_umkm($id) {
        $umkm = $this->Umkm_model->get($id);
        if (!$umkm) show_404();

        $data['title']  = 'Edit UMKM';
        $data['umkm']   = $umkm;
        $data['owners'] = $this->Umkm_model->get_available_owners();
        $this->render('admin/umkm/form', $data);
    }

    public function update_umkm($id) {
        $umkm = $this->Umkm_model->get($id);
        if (!$umkm) show_404();

        $this->form_validation->set_rules('nama_toko', 'Nama Toko', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/umkm/' . $id . '/edit');
        }

        $update = [
            'nama_toko'  => $this->input->post('nama_toko', TRUE),
            'deskripsi'  => $this->input->post('deskripsi', TRUE),
            'alamat'     => $this->input->post('alamat', TRUE),
            'no_wa_toko' => format_wa($this->input->post('no_wa_toko', TRUE)), // helper
        ];

        if (!empty($_FILES['foto']['name'])) {
            $foto = $this->_upload_foto();
            if ($foto) {
                if ($umkm->foto && file_exists(FCPATH . 'uploads/umkm/' . $umkm->foto)) {
                    unlink(FCPATH . 'uploads/umkm/' . $umkm->foto);
                }
                $update['foto'] = $foto;
            }
        }

        $this->Umkm_model->update($id, $update);
        $this->session->set_flashdata('success', 'UMKM berhasil diperbarui.');
        redirect('admin/umkm');
    }

    public function hapus_umkm($id) {
        $umkm = $this->Umkm_model->get($id);
        if (!$umkm) show_404();

        if ($umkm->foto && file_exists(FCPATH . 'uploads/umkm/' . $umkm->foto)) {
            unlink(FCPATH . 'uploads/umkm/' . $umkm->foto);
        }

        $this->Umkm_model->delete($id);
        $this->session->set_flashdata('success', 'UMKM berhasil dihapus.');
        redirect('admin/umkm');
    }

    public function toggle_umkm($id) {
        $this->Umkm_model->toggle_active($id);
        $this->session->set_flashdata('success', 'Status UMKM diperbarui.');
        redirect('admin/umkm');
    }

    // ------------------ KELOLA USERS ------------------
    public function kelola_users() {
        $data['title'] = 'Data Pengguna';
        $data['users'] = $this->User_model->get_all();
        $this->render('admin/users/index', $data);
    }

    public function tambah_user() {
        $data['title'] = 'Tambah Pengguna';
        $this->render('admin/users/form', $data);
    }

    public function simpan_user() {
        $this->form_validation->set_rules('nama',  'Nama',  'required|trim');
        $this->form_validation->set_rules('no_wa', 'No WA', 'required|trim');
        $this->form_validation->set_rules('role',  'Role',  'required|in_list[admin,umkm,user]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/users/tambah');
        }

        $no_wa = format_wa($this->input->post('no_wa', TRUE));

        if ($this->User_model->get_by_wa($no_wa)) {
            $this->session->set_flashdata('error', 'Nomor WhatsApp sudah terdaftar.');
            redirect('admin/users/tambah');
        }

        $this->User_model->create([
            'nama'        => $this->input->post('nama', TRUE),
            'no_wa'       => $no_wa,
            'role'        => $this->input->post('role', TRUE),
            'is_verified' => 1,
        ]);

        $this->session->set_flashdata('success', 'Pengguna berhasil ditambahkan.');
        redirect('admin/users');
    }

    public function edit_user($id) {
        $user_data = $this->User_model->get($id);
        if (!$user_data) show_404();

        $data['title']     = 'Edit Pengguna';
        $data['user_data'] = $user_data;
        $this->render('admin/users/form', $data);
    }

    public function update_user($id) {
        $user_data = $this->User_model->get($id);
        if (!$user_data) show_404();

        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,umkm,user]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/users/' . $id . '/edit');
        }

        $no_wa = format_wa($this->input->post('no_wa', TRUE));

        // Cek no_wa duplikat (kecuali milik sendiri)
        $existing = $this->User_model->get_by_wa($no_wa);
        if ($existing && $existing->id != $id) {
            $this->session->set_flashdata('error', 'Nomor WhatsApp sudah dipakai pengguna lain.');
            redirect('admin/users/' . $id . '/edit');
        }

        $this->User_model->update($id, [
            'nama'        => $this->input->post('nama', TRUE),
            'no_wa'       => $no_wa,
            'role'        => $this->input->post('role', TRUE),
            'is_verified' => $this->input->post('is_verified', TRUE),
        ]);

        $this->session->set_flashdata('success', 'Pengguna berhasil diperbarui.');
        redirect('admin/users');
    }

    public function hapus_user($id) {
        // Cegah hapus diri sendiri
        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Tidak bisa menghapus akun sendiri.');
            redirect('admin/users');
        }

        $this->User_model->delete($id);
        $this->session->set_flashdata('success', 'Pengguna berhasil dihapus.');
        redirect('admin/users');
    }

    // ------------------ PRIVATE FUNCTION ------------------
    private function _upload_foto() {
        $config['upload_path']   = './uploads/umkm/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048; // 2MB
        $config['file_name']     = time() . '_' . $_FILES['foto']['name'];
        $config['overwrite']     = true;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('foto')) {
            return $this->upload->data('file_name');
        } else {
            return false;
        }
    }

    public function laporan() {
    $this->load->model('Pesanan_model');
    $pesanan = $this->db
        ->select('pesanan.*, umkm.nama_toko')
        ->from('pesanan')
        ->join('umkm', 'umkm.id = pesanan.umkm_id', 'left')
        ->order_by('pesanan.created_at', 'DESC')
        ->get()->result();

    $data['title']         = 'Laporan Penjualan';
    $data['pesanan']       = $pesanan;
    $data['total_pesanan'] = count($pesanan);
    $data['total_omzet']   = array_sum(array_map(fn($p) => $p->total_harga, $pesanan));
    $data['total_pending'] = count(array_filter($pesanan, fn($p) => $p->status === 'pending'));
    $data['total_selesai'] = count(array_filter($pesanan, fn($p) => $p->status === 'selesai'));
    $this->render('admin/laporan', $data);
}


}