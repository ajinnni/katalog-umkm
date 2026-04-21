<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Product_model', 'Kategori_model', 'Umkm_model', 'Pesanan_model']);
        $this->load->helper(['url', 'form']);
        $this->load->library('session');

        if (!$this->session->userdata('user_id')) {
            redirect('index.php/login');
        }
        if ($this->session->userdata('role') !== 'user') {
            redirect('index.php/login');
        }
    }

    public function index() {
        $keyword  = $this->input->get('q');
        $kategori = $this->input->get('kategori');

        $data['produk']         = $this->Product_model->get_all_active($keyword, $kategori);
        $data['kategori']       = $this->Kategori_model->get_all();
        $data['keyword']        = $keyword;
        $data['kategori_aktif'] = $kategori;
        $data['title']          = 'Toko UMKM';

        $this->load->view('user/partials/header', $data);
        $this->load->view('user/landing', $data);
        $this->load->view('user/partials/footer');
    }

    public function tambah_keranjang() {
    $id_produk = $this->input->post('id_produk');
    $qty       = (int) $this->input->post('qty') ?: 1;

    $produk = $this->Product_model->get($id_produk);
    if (!$produk) show_error('Produk tidak ditemukan', 404);

    $keranjang = $this->session->userdata('keranjang') ?? [];

    if (isset($keranjang[$id_produk])) {
        $keranjang[$id_produk]['qty'] += $qty;
    } else {
        $keranjang[$id_produk] = [
            'id'      => $produk->id,
            'nama'    => $produk->nama,
            'harga'   => $produk->harga,
            'foto'    => $produk->foto,
            'umkm_id' => $produk->umkm_id,
            'qty'     => $qty,
        ];
    }

    $this->session->set_userdata('keranjang', $keranjang);

    header('Content-Type: application/json');
    echo json_encode([
        'status'    => 'ok',
        'total'     => count($keranjang),
        'csrf_hash' => $this->security->get_csrf_hash(),
    ]);
    exit;
}

    public function keranjang() {
        $keranjang   = $this->session->userdata('keranjang') ?? [];
        $grand_total = array_sum(array_map(fn($i) => $i['harga'] * $i['qty'], $keranjang));

        $this->load->view('user/partials/header', [
            'title'       => 'Keranjang Belanja',
            'keranjang'   => $keranjang,
            'grand_total' => $grand_total,
        ]);
        $this->load->view('user/keranjang', [
            'keranjang'   => $keranjang,
            'grand_total' => $grand_total,
        ]);
        $this->load->view('user/partials/footer');
    }

    public function hapus_keranjang($id) {
        $keranjang = $this->session->userdata('keranjang') ?? [];
        unset($keranjang[$id]);
        $this->session->set_userdata('keranjang', $keranjang);
        redirect('index.php/user/keranjang');
    }

    public function update_keranjang() {
    $id   = $this->input->post('id');
    $aksi = $this->input->post('aksi');

    $keranjang = $this->session->userdata('keranjang') ?? [];

    if (isset($keranjang[$id])) {
        if ($aksi === 'tambah') {
            $keranjang[$id]['qty']++;
        } elseif ($aksi === 'kurang' && $keranjang[$id]['qty'] > 1) {
            $keranjang[$id]['qty']--;
        } elseif ($aksi === 'kurang' && $keranjang[$id]['qty'] <= 1) {
            unset($keranjang[$id]);
        }
    }

    $this->session->set_userdata('keranjang', $keranjang);

    // ✅ Wajib ada ini
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit; // ✅ Wajib exit
}

    public function checkout() {
        $keranjang = $this->session->userdata('keranjang') ?? [];
        if (empty($keranjang)) {
            redirect('index.php/user/keranjang');
        }

        $nama   = $this->input->post('nama', TRUE);
        $no_wa  = $this->input->post('no_wa', TRUE);
        $alamat = $this->input->post('alamat', TRUE);

        if (!$nama || !$no_wa || !$alamat) {
            $this->session->set_flashdata('error', 'Semua field harus diisi.');
            redirect('index.php/user/keranjang');
        }

        $per_umkm = [];
        foreach ($keranjang as $item) {
            $umkm_id = $item['umkm_id'];
            if (!isset($per_umkm[$umkm_id])) $per_umkm[$umkm_id] = [];
            $per_umkm[$umkm_id][] = $item;
        }

        $wa_links = [];

        foreach ($per_umkm as $umkm_id => $items) {
            $umkm       = $this->Umkm_model->get($umkm_id);
            $total      = array_sum(array_map(fn($i) => $i['harga'] * $i['qty'], $items));
            $kode       = 'ORD-' . strtoupper(substr(uniqid(), -6));
            $no_wa_toko = $umkm->no_wa_toko ?? $umkm->wa_pemilik ?? '';

            $pesanan_id = $this->Pesanan_model->create([
                'kode_pesanan'      => $kode,
                'user_id'           => $this->session->userdata('user_id'),
                'umkm_id'           => $umkm_id,
                'nama_pemesan'      => $nama,
                'no_wa_pemesan'     => $no_wa,
                'alamat_pengiriman' => $alamat,
                'total_harga'       => $total,
                'status'            => 'pending',
            ]);

            foreach ($items as $item) {
                $this->Pesanan_model->create_detail([
                    'pesanan_id'   => $pesanan_id,
                    'produk_id'    => $item['id'],
                    'nama_produk'  => $item['nama'],
                    'harga_satuan' => $item['harga'],
                    'qty'          => $item['qty'],
                    'subtotal'     => $item['harga'] * $item['qty'],
                ]);

                
            }
                    $this->_kirim_wa_bot([
            'kode_pesanan'  => $kode,
            'nama_pemesan'  => $nama,
            'no_wa_pemesan' => $no_wa,
            'alamat'        => $alamat,
            'grand_total'   => $total,
            'no_wa_umkm'    => $no_wa_toko,
            'nama_toko'     => $umkm->nama_toko,
            'items'         => array_values($items),
        ]);

            $pesan = "Halo Kak, saya ingin memesan:\n\n";
            foreach ($items as $item) {
                $pesan .= "- {$item['nama']} x{$item['qty']} = Rp " . number_format($item['harga'] * $item['qty'], 0, ',', '.') . "\n";
            }
            $pesan .= "\nTotal: Rp " . number_format($total, 0, ',', '.');
            $pesan .= "\n\nNama: {$nama}";
            $pesan .= "\nAlamat: {$alamat}";
            $pesan .= "\nKode Order: {$kode}";

            $no_toko = preg_replace('/\D/', '', $no_wa_toko);
            if (substr($no_toko, 0, 1) === '0') $no_toko = '62' . substr($no_toko, 1);

            $wa_links[] = [
                'toko' => $umkm->nama_toko,
                'link' => 'https://wa.me/' . $no_toko . '?text=' . urlencode($pesan),
                'kode' => $kode,
            ];
            
        }

        $this->session->unset_userdata('keranjang');

        $this->load->view('user/partials/header', ['title' => 'Pesanan Berhasil']);
        $this->load->view('user/sukses', ['wa_links' => $wa_links]);
        $this->load->view('user/partials/footer');
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('index.php/login');
    }
        private function _kirim_wa_bot($data) {
            $url = 'http://localhost:3000/kirim-pesanan';
            $ch  = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST           => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
                CURLOPT_POSTFIELDS     => json_encode($data),
                CURLOPT_TIMEOUT        => 5,
            ]);
            curl_exec($ch);
            curl_close($ch);
        }
  
}