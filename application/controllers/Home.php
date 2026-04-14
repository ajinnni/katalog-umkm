<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Product_model', 'Kategori_model', 'Umkm_model']);
        $this->load->library('session');
    }

    // -------------------------------------------------------
    // LANDING PAGE
    // -------------------------------------------------------
    public function index() {
        $keyword    = $this->input->get('q', TRUE);
        $kategori_id = $this->input->get('kategori', TRUE);

        $data['title']     = 'Katalog UMKM';
        $data['produk']    = $this->Product_model->get_all_active($keyword, $kategori_id);
        $data['kategori']  = $this->Kategori_model->get_all();
        $data['keyword']   = $keyword;
        $data['kategori_aktif'] = $kategori_id;
        $this->render_public('home/index', $data);
    }

    // -------------------------------------------------------
    // DETAIL PRODUK
    // -------------------------------------------------------
    public function detail($id) {
        $produk = $this->Product_model->get($id);
        if (!$produk) show_404();

        $umkm = $this->Umkm_model->get($produk->umkm_id);

        $data['title']  = $produk->nama;
        $data['produk'] = $produk;
        $data['umkm']   = $umkm;
        $this->render_public('home/detail', $data);
    }

    // -------------------------------------------------------
    // KERANJANG
    // -------------------------------------------------------
    public function keranjang() {
        $keranjang  = $this->session->userdata('keranjang') ?: [];
        $grand_total = 0;
        foreach ($keranjang as $item) {
            $grand_total += $item['harga'] * $item['qty'];
        }

        $data['title']       = 'Keranjang Belanja';
        $data['keranjang']   = $keranjang;
        $data['grand_total'] = $grand_total;
        $this->render_public('home/keranjang', $data);
    }

    public function tambah_keranjang($id) {
        $produk = $this->Product_model->get($id);
        if (!$produk) show_404();

        $keranjang = $this->session->userdata('keranjang') ?: [];

        if (isset($keranjang[$id])) {
            $keranjang[$id]['qty']++;
        } else {
            $keranjang[$id] = [
                'id'       => $produk->id,
                'nama'     => $produk->nama,
                'harga'    => $produk->harga,
                'foto'     => $produk->foto,
                'umkm_id'  => $produk->umkm_id,
                'qty'      => 1,
            ];
        }

        $this->session->set_userdata('keranjang', $keranjang);
        $this->session->set_flashdata('success', 'Produk ditambahkan ke keranjang.');
        redirect('keranjang');
    }

    public function update_keranjang() {
        $id  = $this->input->post('id');
        $qty = (int) $this->input->post('qty');

        $keranjang = $this->session->userdata('keranjang') ?: [];

        if (isset($keranjang[$id])) {
            if ($qty <= 0) {
                unset($keranjang[$id]);
            } else {
                $keranjang[$id]['qty'] = $qty;
            }
        }

        $this->session->set_userdata('keranjang', $keranjang);
        redirect('keranjang');
    }

    public function hapus_keranjang($id) {
        $keranjang = $this->session->userdata('keranjang') ?: [];
        unset($keranjang[$id]);
        $this->session->set_userdata('keranjang', $keranjang);
        redirect('keranjang');
    }

    public function kosongkan_keranjang() {
        $this->session->unset_userdata('keranjang');
        redirect('keranjang');
    }

    // -------------------------------------------------------
    // CHECKOUT — kirim ke Node.js WA Bot
    // -------------------------------------------------------
    public function checkout() {
        $keranjang = $this->session->userdata('keranjang') ?: [];
        if (empty($keranjang)) {
            $this->session->set_flashdata('error', 'Keranjang kosong.');
            redirect('keranjang');
        }

        $nama   = $this->input->post('nama',   TRUE);
        $alamat = $this->input->post('alamat', TRUE);
        $no_wa  = $this->input->post('no_wa',  TRUE);

        if (!$nama || !$alamat || !$no_wa) {
            $this->session->set_flashdata('error', 'Lengkapi data pemesan.');
            redirect('keranjang');
        }

        // Format nomor WA
        $no_wa = preg_replace('/\D/', '', $no_wa);
        if (substr($no_wa, 0, 2) === '08') $no_wa = '628' . substr($no_wa, 2);
        if (substr($no_wa, 0, 1) === '8')  $no_wa = '62'  . $no_wa;

        // Hitung total & kelompokkan per UMKM
        $grand_total = 0;
        $per_umkm    = [];
        foreach ($keranjang as $item) {
            $grand_total += $item['harga'] * $item['qty'];
            $per_umkm[$item['umkm_id']][] = $item;
        }

        // Simpan pesanan ke DB
        $this->load->model('Pesanan_model');
        $kode = 'ORD-' . strtoupper(uniqid());

        // Ambil umkm_id pertama (jika multi-UMKM ambil yang pertama)
        $umkm_id = array_key_first($per_umkm);

        $pesanan_id = $this->Pesanan_model->create([
            'kode_pesanan'      => $kode,
            'user_id'           => $this->session->userdata('user_id') ?: NULL,
            'umkm_id'           => $umkm_id,
            'nama_pemesan'      => $nama,
            'no_wa_pemesan'     => $no_wa,
            'alamat_pengiriman' => $alamat,
            'total_harga'       => $grand_total,
            'status'            => 'pending',
        ]);

        // Simpan detail pesanan
        foreach ($keranjang as $item) {
            $this->Pesanan_model->create_detail([
                'pesanan_id'   => $pesanan_id,
                'produk_id'    => $item['id'],
                'nama_produk'  => $item['nama'],
                'harga_satuan' => $item['harga'],
                'qty'          => $item['qty'],
                'subtotal'     => $item['harga'] * $item['qty'],
            ]);
        }

        // Kirim ke Node.js WA Bot
        $umkm = $this->Umkm_model->get($umkm_id);
        $this->_kirim_wa_bot([
            'kode_pesanan'  => $kode,
            'nama_pemesan'  => $nama,
            'no_wa_pemesan' => $no_wa,
            'alamat'        => $alamat,
            'grand_total'   => $grand_total,
            'no_wa_umkm'    => $umkm ? $umkm->no_wa_toko : null,
            'nama_toko'     => $umkm ? $umkm->nama_toko  : null,
            'items'         => array_values($keranjang),
        ]);

        // Kosongkan keranjang
        $this->session->unset_userdata('keranjang');
        $this->session->set_flashdata('success', 'Pesanan berhasil! Pemilik UMKM akan segera menghubungi kamu.');
        redirect('keranjang');
    }

    // -------------------------------------------------------
    // HELPER — kirim ke Node.js
    // -------------------------------------------------------
    private function _kirim_wa_bot($data) {
        $url     = 'http://localhost:3000/kirim-pesanan'; // URL Node.js
        $payload = json_encode($data);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 5,
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}