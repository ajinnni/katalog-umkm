<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends UMKM_Controller { // Controller khusus pemilik UMKM

    public function __construct() {
        parent::__construct();
        $this->load->model(['Product_model', 'Kategori_model']);
        $this->load->library(['form_validation', 'upload']);
    }

    public function index() {
        $umkm_id = $this->session->userdata('umkm_id'); // pastikan pemilik UMKM punya session umkm_id
        $data['title'] = 'Kelola Produk';
        $data['products'] = $this->Product_model->get_by_umkm($umkm_id);
        $this->render('umkm/product/index', $data);
    }

    public function tambah() {
        $data['title'] = 'Tambah Produk';
        $data['kategori'] = $this->Kategori_model->get_all();
        $this->render('umkm/product/form', $data);
    }

    public function simpan() {
        $umkm_id = $this->session->userdata('umkm_id');

        $this->form_validation->set_rules('nama', 'Nama Produk', 'required|trim');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('stok', 'Stok', 'required|integer');
        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('product/tambah');
        }

        $foto = null;
        if (!empty($_FILES['foto']['name'])) {
            $foto = $this->_upload_foto();
            if (!$foto) {
                $this->session->set_flashdata('error', 'Gagal upload foto: ' . $this->upload->display_errors());
                redirect('product/tambah');
            }
        }

        $this->Product_model->create([
            'umkm_id'     => $umkm_id,
            'kategori_id' => $this->input->post('kategori_id', TRUE),
            'nama'        => $this->input->post('nama', TRUE),
            'harga'       => $this->input->post('harga', TRUE),
            'stok'        => $this->input->post('stok', TRUE),
            'deskripsi'   => $this->input->post('deskripsi', TRUE),
            'foto'        => $foto,
            'is_active'   => 1,
        ]);

        $this->session->set_flashdata('success', 'Produk berhasil ditambahkan.');
        redirect('product');
    }

    public function edit($id) {
        $product = $this->Product_model->get($id);
        if (!$product) show_404();

        $data['title'] = 'Edit Produk';
        $data['product'] = $product;
        $data['kategori'] = $this->Kategori_model->get_all();
        $this->render('umkm/product/form', $data);
    }

    public function update($id) {
        $product = $this->Product_model->get($id);
        if (!$product) show_404();

        $this->form_validation->set_rules('nama', 'Nama Produk', 'required|trim');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('stok', 'Stok', 'required|integer');
        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('product/' . $id . '/edit');
        }

        $update = [
            'kategori_id' => $this->input->post('kategori_id', TRUE),
            'nama'        => $this->input->post('nama', TRUE),
            'harga'       => $this->input->post('harga', TRUE),
            'stok'        => $this->input->post('stok', TRUE),
            'deskripsi'   => $this->input->post('deskripsi', TRUE),
        ];

        if (!empty($_FILES['foto']['name'])) {
            $foto = $this->_upload_foto();
            if ($foto) {
                if ($product->foto && file_exists(FCPATH . 'uploads/product/' . $product->foto)) {
                    unlink(FCPATH . 'uploads/product/' . $product->foto);
                }
                $update['foto'] = $foto;
            }
        }

        $this->Product_model->update($id, $update);
        $this->session->set_flashdata('success', 'Produk berhasil diperbarui.');
        redirect('product');
    }

    public function hapus($id) {
        $product = $this->Product_model->get($id);
        if (!$product) show_404();

        if ($product->foto && file_exists(FCPATH . 'uploads/product/' . $product->foto)) {
            unlink(FCPATH . 'uploads/product/' . $product->foto);
        }

        $this->Product_model->delete($id);
        $this->session->set_flashdata('success', 'Produk berhasil dihapus.');
        redirect('product');
    }

    private function _upload_foto() {
        $config['upload_path']   = FCPATH . 'uploads/product/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size']      = 2048;
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('foto')) {
            return $this->upload->data('file_name');
        }
        return false;
    }

}