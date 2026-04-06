<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Umkm extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id') || $this->session->userdata('role') !== 'umkm') {
            redirect('index.php/login');
        }
        $this->load->model(['User_model', 'Umkm_model', 'Product_model', 'Kategori_model']);
    }

    public function dashboard() {
        $user_id = $this->session->userdata('user_id');
        $user    = $this->User_model->get($user_id);
        if (!$user) {
            $this->session->set_flashdata('error', 'Data pengguna tidak ditemukan.');
            redirect('index.php/login');
        }

        $umkm     = $this->Umkm_model->get_by_user($user_id);
        $products = $umkm ? $this->Product_model->get_by_umkm($umkm->id) : [];

        $this->load->view('umkm/dashboard', [
            'title'    => 'Dashboard UMKM',
            'user'     => $user,
            'umkm'     => $umkm,
            'products' => $products,
        ]);
    }

    public function kelola_produk() {
        $user_id = $this->session->userdata('user_id');
        $umkm    = $this->Umkm_model->get_by_user($user_id);
        if (!$umkm) {
            $this->session->set_flashdata('error', 'UMKM tidak ditemukan.');
            redirect('index.php/umkm/dashboard');
        }

        $this->load->view('umkm/produk/index', [
            'title'    => 'Kelola Produk',
            'umkm'     => $umkm,
            'products' => $this->Product_model->get_by_umkm($umkm->id),
            'kategori' => $this->Kategori_model->get_all(),
        ]);
    }

    public function tambah_produk() {
        $user_id = $this->session->userdata('user_id');
        $umkm    = $this->Umkm_model->get_by_user($user_id);

        $this->load->view('umkm/produk/form', [
            'title'    => 'Tambah Produk',
            'umkm'     => $umkm,
            'kategori' => $this->Kategori_model->get_all(),
        ]);
    }

    public function simpan_produk() {
        $user_id = $this->session->userdata('user_id');
        $umkm    = $this->Umkm_model->get_by_user($user_id);

        $this->form_validation->set_rules('nama',        'Nama Produk', 'required|trim');
        $this->form_validation->set_rules('harga',       'Harga',       'required|numeric');
        $this->form_validation->set_rules('kategori_id', 'Kategori',    'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('index.php/umkm/produk/tambah');
        }

        $foto = NULL;
        if (!empty($_FILES['foto']['name'])) {
            $upload_path = FCPATH . 'uploads/produk/';
            if (!is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);

            $this->load->library('upload');
            $this->upload->initialize([
                'upload_path'   => $upload_path,
                'allowed_types' => 'jpg|jpeg|png',
                'max_size'      => 2048,
                'file_name'     => uniqid('produk_'),
            ]);

            if ($this->upload->do_upload('foto')) {
                $foto = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('index.php/umkm/produk/tambah');
            }
        }

        $this->Product_model->create([
            'umkm_id'     => $umkm->id,
            'kategori_id' => $this->input->post('kategori_id'),
            'nama'        => $this->input->post('nama',      TRUE),
            'harga'       => $this->input->post('harga'),
            'stok'        => $this->input->post('stok') ?: 0,
            'foto'        => $foto,
            'deskripsi'   => $this->input->post('deskripsi', TRUE),
            'is_active'   => $this->input->post('is_active') ? 1 : 0,
        ]);

        $this->session->set_flashdata('success', 'Produk berhasil ditambahkan.');
        redirect('index.php/umkm/dashboard');
    }

    public function edit_produk($id) {
        $user_id = $this->session->userdata('user_id');
        $umkm    = $this->Umkm_model->get_by_user($user_id);
        $product = $this->Product_model->get($id);

        if (!$product || $product->umkm_id != $umkm->id) {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan.');
            redirect('index.php/umkm/dashboard');
        }

        $this->load->view('umkm/produk/form', [
            'title'    => 'Edit Produk',
            'umkm'     => $umkm,
            'product'  => $product,
            'kategori' => $this->Kategori_model->get_all(),
        ]);
    }

    public function update_produk($id) {
        $user_id = $this->session->userdata('user_id');
        $umkm    = $this->Umkm_model->get_by_user($user_id);
        $product = $this->Product_model->get($id);

        if (!$product || $product->umkm_id != $umkm->id) {
            redirect('index.php/umkm/dashboard');
        }

        $this->form_validation->set_rules('nama',        'Nama Produk', 'required|trim');
        $this->form_validation->set_rules('harga',       'Harga',       'required|numeric');
        $this->form_validation->set_rules('kategori_id', 'Kategori',    'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('index.php/umkm/produk/' . $id . '/edit');
        }

        $foto = $product->foto;
        if (!empty($_FILES['foto']['name'])) {
            $upload_path = FCPATH . 'uploads/produk/';
            if (!is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);

            $this->load->library('upload');
            $this->upload->initialize([
                'upload_path'   => $upload_path,
                'allowed_types' => 'jpg|jpeg|png',
                'max_size'      => 2048,
                'file_name'     => uniqid('produk_'),
            ]);

            if ($this->upload->do_upload('foto')) {
                if ($product->foto && file_exists(FCPATH . 'uploads/produk/' . $product->foto)) {
                    unlink(FCPATH . 'uploads/produk/' . $product->foto);
                }
                $foto = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('index.php/umkm/produk/' . $id . '/edit');
            }
        }

        $this->Product_model->update($id, [
            'kategori_id' => $this->input->post('kategori_id'),
            'nama'        => $this->input->post('nama',      TRUE),
            'harga'       => $this->input->post('harga'),
            'stok'        => $this->input->post('stok') ?: 0,
            'foto'        => $foto,
            'deskripsi'   => $this->input->post('deskripsi', TRUE),
            'is_active'   => $this->input->post('is_active') ? 1 : 0,
        ]);

        $this->session->set_flashdata('success', 'Produk berhasil diupdate.');
        redirect('index.php/umkm/dashboard');
    }

    public function hapus_produk($id) {
        $user_id = $this->session->userdata('user_id');
        $umkm    = $this->Umkm_model->get_by_user($user_id);
        $product = $this->Product_model->get($id);

        if ($product && $product->umkm_id == $umkm->id) {
            if ($product->foto && file_exists(FCPATH . 'uploads/produk/' . $product->foto)) {
                unlink(FCPATH . 'uploads/produk/' . $product->foto);
            }
            $this->Product_model->delete($id);
            $this->session->set_flashdata('success', 'Produk berhasil dihapus.');
        }

        redirect('index.php/umkm/dashboard');
    }
}