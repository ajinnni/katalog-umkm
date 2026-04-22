<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Umkm extends MY_Controller {

    const FONNTE_TOKEN = 'aFUCu1xfixcmZjDD3XS6'; // token Fonnte

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id') || $this->session->userdata('role') !== 'umkm') {
            redirect('index.php/login');
        }
        $this->load->model(['User_model', 'Umkm_model', 'Product_model', 'Kategori_model', 'Pesanan_model']);
    }

    // Helper ambil $user & $umkm sekaligus
    private function _get_user_umkm() {
        $user_id = $this->session->userdata('user_id');
        $user    = $this->User_model->get($user_id);
        if (!$user) {
            $this->session->set_flashdata('error', 'Data pengguna tidak ditemukan.');
            redirect('index.php/login');
        }
        $umkm = $this->Umkm_model->get_by_user($user_id);
        return [$user, $umkm];
    }

    public function dashboard() {
        list($user, $umkm) = $this->_get_user_umkm();

        $products = $umkm ? $this->Product_model->get_by_umkm($umkm->id) : [];

        $total_pesanan = 0;
        $total_pending = 0;
        $total_omzet   = 0;
        if ($umkm) {
            $pesanan       = $this->Pesanan_model->get_by_umkm($umkm->id);
            $total_pesanan = count($pesanan);
            $total_pending = count(array_filter($pesanan, fn($p) => $p->status === 'pending'));
            $total_omzet   = array_sum(array_map(fn($p) => $p->total_harga, array_filter($pesanan, fn($p) => $p->status === 'selesai')));
        }

        $this->load->view('umkm/dashboard', [
            'title'         => 'Dashboard UMKM',
            'user'          => $user,
            'umkm'          => $umkm,
            'products'      => $products,
            'total_pesanan' => $total_pesanan,
            'total_pending' => $total_pending,
            'total_omzet'   => $total_omzet,
        ]);
    }

    public function laporan() {
        list($user, $umkm) = $this->_get_user_umkm();

        if (!$umkm) {
            $this->session->set_flashdata('error', 'UMKM tidak ditemukan.');
            redirect('index.php/umkm/dashboard');
        }

        $pesanan       = $this->Pesanan_model->get_by_umkm($umkm->id);
        $total_pesanan = count($pesanan);
        $total_pending = count(array_filter($pesanan, fn($p) => $p->status === 'pending'));
        $total_selesai = count(array_filter($pesanan, fn($p) => $p->status === 'selesai'));
        $total_omzet   = array_sum(array_map(fn($p) => $p->total_harga, array_filter($pesanan, fn($p) => $p->status === 'selesai')));

        $this->load->view('umkm/laporan', [
            'title'         => 'Laporan Penjualan',
            'user'          => $user,
            'umkm'          => $umkm,
            'pesanan'       => $pesanan,
            'total_pesanan' => $total_pesanan,
            'total_omzet'   => $total_omzet,
            'total_pending' => $total_pending,
            'total_selesai' => $total_selesai,
        ]);
    }

    public function kelola_produk() {
        list($user, $umkm) = $this->_get_user_umkm();

        if (!$umkm) {
            $this->session->set_flashdata('error', 'UMKM tidak ditemukan.');
            redirect('index.php/umkm/dashboard');
        }

        $this->load->view('umkm/produk/index', [
            'title'    => 'Kelola Produk',
            'user'     => $user,
            'umkm'     => $umkm,
            'products' => $this->Product_model->get_by_umkm($umkm->id),
            'kategori' => $this->Kategori_model->get_all(),
        ]);
    }

    public function tambah_produk() {
        list($user, $umkm) = $this->_get_user_umkm();

        $this->load->view('umkm/produk/form', [
            'title'    => 'Tambah Produk',
            'user'     => $user,
            'umkm'     => $umkm,
            'kategori' => $this->Kategori_model->get_all(),
        ]);
    }

    public function simpan_produk() {
        list($user, $umkm) = $this->_get_user_umkm();

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
        list($user, $umkm) = $this->_get_user_umkm();
        $product = $this->Product_model->get($id);

        if (!$product || $product->umkm_id != $umkm->id) {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan.');
            redirect('index.php/umkm/dashboard');
        }

        $this->load->view('umkm/produk/form', [
            'title'    => 'Edit Produk',
            'user'     => $user,
            'umkm'     => $umkm,
            'product'  => $product,
            'kategori' => $this->Kategori_model->get_all(),
        ]);
    }

    public function update_produk($id) {
        list($user, $umkm) = $this->_get_user_umkm();
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
        list($user, $umkm) = $this->_get_user_umkm();
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

    // -------------------------------------------------------
    // UPDATE STATUS PESANAN + KIRIM WA OTOMATIS
    // -------------------------------------------------------
    public function update_status_pesanan($id) {
        $status = $this->input->post('status');

        if (!$status) {
            $this->session->set_flashdata('error', 'Status tidak valid.');
            redirect('index.php/umkm/laporan');
        }

        // Ambil data pesanan sebelum diupdate
        $pesanan = $this->Pesanan_model->get($id);

        // Update status
        $this->Pesanan_model->update_status($id, $status);

        // Kirim WA notifikasi ke pembeli
        if ($pesanan && !empty($pesanan->no_wa_pemesan)) {
            $this->_kirim_notif_status($pesanan, $status);
        }

        $this->session->set_flashdata('success', 'Status pesanan diupdate dan notifikasi WA terkirim.');
        redirect('index.php/umkm/laporan');
    }

    // -------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------

    private function _kirim_notif_status($pesanan, $status) {
        $status_pesan = [
            'dikonfirmasi' => "✅ *Pesanan Dikonfirmasi!*\n\nHalo {$pesanan->nama_pemesan}, pesanan kamu dengan kode *{$pesanan->kode_pesanan}* sudah dikonfirmasi oleh toko.\n\nSilakan tunggu ya, kami sedang memproses pesananmu! 😊",
            'diproses'     => "⚙️ *Pesanan Sedang Diproses!*\n\nHalo {$pesanan->nama_pemesan}, pesanan *{$pesanan->kode_pesanan}* sedang kami siapkan.\n\nTidak lama lagi akan segera dikirim! 📦",
            'dikirim'      => "🚚 *Pesanan Sedang Dikirim!*\n\nHalo {$pesanan->nama_pemesan}, pesanan *{$pesanan->kode_pesanan}* sudah dalam perjalanan ke alamatmu.\n\nAlamat: {$pesanan->alamat_pengiriman}\n\nMohon tunggu kedatangannya ya! 😊",
            'selesai'      => "🎉 *Pesanan Selesai!*\n\nHalo {$pesanan->nama_pemesan}, pesanan *{$pesanan->kode_pesanan}* telah selesai.\n\nTerima kasih sudah berbelanja! Jangan lupa beli lagi ya 🛍️",
            'dibatalkan'   => "❌ *Pesanan Dibatalkan*\n\nHalo {$pesanan->nama_pemesan}, pesanan *{$pesanan->kode_pesanan}* telah dibatalkan.\n\nJika ada pertanyaan, silakan hubungi toko kami.",
        ];

        // Hanya kirim untuk status yang perlu notifikasi
        if (!isset($status_pesan[$status])) return;

        $no_wa = preg_replace('/\D/', '', $pesanan->no_wa_pemesan);
        if (substr($no_wa, 0, 1) === '0') $no_wa = '62' . substr($no_wa, 1);
        if (substr($no_wa, 0, 1) === '8') $no_wa = '62' . $no_wa;

        $ch = curl_init('https://api.fonnte.com/send');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => ['Authorization: ' . self::FONNTE_TOKEN],
            CURLOPT_POSTFIELDS     => [
                'target'  => $no_wa,
                'message' => $status_pesan[$status],
            ],
        ]);
        $result = curl_exec($ch);
        $error  = curl_error($ch);
        unset($ch);

        log_message('debug', 'Notif WA status [' . $status . '] ke ' . $no_wa . ': ' . $result);
        if ($error) log_message('error', 'Notif WA error: ' . $error);
    }

    public function daftar_toko() {
        list($user, $umkm) = $this->_get_user_umkm();
 
        // Kalau sudah punya toko, redirect ke dashboard
        if ($umkm) {
            $this->session->set_flashdata('info', 'Kamu sudah memiliki toko.');
            redirect('index.php/umkm/dashboard');
        }
 
        $this->load->view('umkm/daftar_toko', [
            'title' => 'Daftar Toko',
            'user'  => $user,
        ]);
    }
 
    public function simpan_toko() {
        list($user, $umkm) = $this->_get_user_umkm();
 
        // Cek sudah punya toko
        if ($umkm) {
            redirect('index.php/umkm/dashboard');
        }
 
        $nama_toko  = $this->input->post('nama_toko',  TRUE);
        $deskripsi  = $this->input->post('deskripsi',  TRUE);
        $alamat     = $this->input->post('alamat',     TRUE);
        $no_wa_toko = $this->input->post('no_wa_toko', TRUE);
 
        // Validasi field wajib
        if (!$nama_toko || !$deskripsi || !$alamat || !$no_wa_toko) {
            $this->session->set_flashdata('error', 'Semua field wajib diisi.');
            redirect('index.php/umkm/daftar-toko');
        }
 
        // Validasi foto wajib
        if (empty($_FILES['foto']['name'])) {
            $this->session->set_flashdata('error', 'Foto toko wajib diupload.');
            redirect('index.php/umkm/daftar-toko');
        }
 
        // Upload foto
        $upload_path = FCPATH . 'uploads/toko/';
        if (!is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);
 
        $this->load->library('upload');
        $this->upload->initialize([
            'upload_path'   => $upload_path,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size'      => 2048,
            'file_name'     => uniqid('toko_'),
        ]);
 
        if (!$this->upload->do_upload('foto')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            redirect('index.php/umkm/daftar-toko');
        }
 
        $foto = $this->upload->data('file_name');
 
        // Format no WA
        $no_wa_toko = preg_replace('/\D/', '', $no_wa_toko);
        if (substr($no_wa_toko, 0, 2) === '08') $no_wa_toko = '628' . substr($no_wa_toko, 2);
        if (substr($no_wa_toko, 0, 1) === '8')  $no_wa_toko = '62'  . $no_wa_toko;
 
        // Simpan toko — is_active = 0 (menunggu aktivasi admin)
        $this->Umkm_model->create([
            'user_id'    => $user->id,
            'nama_toko'  => $nama_toko,
            'deskripsi'  => $deskripsi,
            'alamat'     => $alamat,
            'no_wa_toko' => $no_wa_toko,
            'foto'       => $foto,
            'is_active'  => 0,
        ]);
 
        $this->session->set_flashdata('success', 'Toko berhasil didaftarkan! Tunggu aktivasi dari admin ya.');
        redirect('index.php/umkm/dashboard');
    }
}