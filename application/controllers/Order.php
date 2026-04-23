<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Pesanan_model', 'Product_model', 'Umkm_model']);
    }

    // ===============================
    // CHECKOUT
    // ===============================
    public function checkout() {
        $keranjang = $this->session->userdata('keranjang') ?? [];

        if (empty($keranjang)) {
            redirect('user/keranjang');
        }

        $items = [];
        $total_harga = 0;
        $umkm_id = null;

        foreach ($keranjang as $id => $item) {
            $produk = $this->Product_model->get($id);
            if (!$produk) continue;

            $subtotal = $produk->harga * $item['qty'];
            $total_harga += $subtotal;
            $umkm_id = $produk->umkm_id;

            $items[] = [
                'produk' => $produk,
                'qty' => $item['qty'],
                'subtotal' => $subtotal,
            ];
        }

        $data = [
            'title' => 'Checkout',
            'items' => $items,
            'total_harga' => $total_harga,
            'umkm' => $umkm_id ? $this->Umkm_model->get($umkm_id) : null
        ];

        $this->load->view('user/partials/header', $data);
        $this->load->view('user/order/checkout', $data);
        $this->load->view('user/partials/footer');
    }

    // ===============================
    // PROSES ORDER
    // ===============================
    public function proses() {

        $user_id = $this->session->userdata('user_id'); // 🔥 WAJIB

        $keranjang = $this->session->userdata('keranjang') ?? [];
        if (empty($keranjang)) redirect('user/keranjang');

        $metode = $this->input->post('metode_pengiriman', TRUE);

        if (!in_array($metode, ['pickup', 'delivery'])) {
            $this->session->set_flashdata('error', 'Pilih metode pengiriman');
            redirect('order/checkout');
        }

        $nama_pemesan = '';
        $no_wa = '';
        $alamat = '';

        if ($metode === 'delivery') {
            $nama_pemesan = $this->input->post('nama_pemesan', TRUE);
            $no_wa = $this->input->post('no_wa_pemesan', TRUE);
            $alamat = $this->input->post('alamat_pengiriman', TRUE);

            if (!$nama_pemesan || !$no_wa || !$alamat) {
                redirect('order/checkout');
            }
        } else {
            $nama_pemesan = $this->session->userdata('nama') ?? 'Customer';
            $no_wa = $this->input->post('no_wa_pemesan', TRUE);
        }

        $total_harga = 0;
        $items = [];
        $umkm_id = null;

        foreach ($keranjang as $id => $item) {
            $produk = $this->Product_model->get($id);
            if (!$produk) continue;

            $subtotal = $produk->harga * $item['qty'];
            $total_harga += $subtotal;
            $umkm_id = $produk->umkm_id;

            $items[] = [
                'produk' => $produk,
                'qty' => $item['qty']
            ];
        }

        $kode = 'ORD-' . strtoupper(substr(uniqid(), -6)) . '-' . date('Ymd');

        // 🔥 FIX: tambah user_id
        $pesanan_id = $this->Pesanan_model->create([
            'kode_pesanan' => $kode,
            'user_id' => $user_id,
            'umkm_id' => $umkm_id,
            'nama_pemesan' => $nama_pemesan,
            'no_wa_pemesan' => $no_wa,
            'alamat_pengiriman' => $alamat,
            'metode_pengiriman' => $metode,
            'total_harga' => $total_harga,
            'status' => 'pending',
            'catatan' => $this->input->post('catatan', TRUE) ?: null,
        ]);

        foreach ($items as $item) {
            $this->Pesanan_model->create_detail([
                'pesanan_id' => $pesanan_id,
                'produk_id' => $item['produk']->id,
                'qty' => $item['qty'],
                'harga' => $item['produk']->harga,
                'subtotal' => $item['produk']->harga * $item['qty'],
            ]);

            $this->Product_model->update($item['produk']->id, [
                'stok' => $item['produk']->stok - $item['qty'],
            ]);
        }

        $this->session->unset_userdata('keranjang');

        redirect('order/sukses/' . $pesanan_id);
    }

    // ===============================
    // RIWAYAT (FIXED)
    // ===============================
    public function riwayat() {

        $user_id = $this->session->userdata('user_id'); // 🔥 ambil user

        $data = [
            'title' => 'Riwayat Pesanan',
            'pesanan' => $this->Pesanan_model->get_by_user($user_id) // 🔥 FIX
        ];

        $this->load->view('user/partials/header', $data);
        $this->load->view('user/order/riwayat', $data);
        $this->load->view('user/partials/footer');
    }

    // ===============================
    // DETAIL
    // ===============================
    public function detail($id) {
        $pesanan = $this->Pesanan_model->get_detail($id);
        if (!$pesanan) show_404();

        $data = [
            'title' => 'Detail Pesanan',
            'pesanan' => $pesanan
        ];

        $this->load->view('user/partials/header', $data);
        $this->load->view('user/order/detail', $data);
        $this->load->view('user/partials/footer');
    }
}