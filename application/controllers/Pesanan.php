<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Pesanan — UMKM side
 * Route: /pesanan/...
 * Extends UMKM_Controller (sama seperti Product.php)
 */
class Pesanan extends UMKM_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pesanan_model');
    }

    // ── LIST PESANAN ──────────────────────────────────────
    public function index() {
        $umkm_id = $this->session->userdata('umkm_id');
        $status  = $this->input->get('status');

        $data['title']   = 'Kelola Pesanan';
        $data['pesanan'] = $this->Pesanan_model->get_by_umkm_with_user($umkm_id, $status ?: null);
        $data['status_filter'] = $status;
        $data['stats'] = [
            'pending'      => $this->Pesanan_model->count_by_status($umkm_id, 'pending'),
            'dikonfirmasi' => $this->Pesanan_model->count_by_status($umkm_id, 'dikonfirmasi'),
            'diproses'     => $this->Pesanan_model->count_by_status($umkm_id, 'diproses'),
            'dikirim'      => $this->Pesanan_model->count_by_status($umkm_id, 'dikirim'),
            'selesai'      => $this->Pesanan_model->count_by_status($umkm_id, 'selesai'),
        ];

        $this->render('umkm/pesanan/index', $data);
    }

    // ── DETAIL + FORM PENGIRIMAN ──────────────────────────
    public function detail($id) {
        $umkm_id = $this->session->userdata('umkm_id');
        $pesanan = $this->Pesanan_model->get_detail_full($id);

        if (!$pesanan || $pesanan->umkm_id != $umkm_id) show_404();

        $data['title']   = 'Detail Pesanan #' . $pesanan->kode_pesanan;
        $data['pesanan'] = $pesanan;
        $this->render('umkm/pesanan/detail', $data);
    }

    // ── KONFIRMASI PESANAN (pending → dikonfirmasi) ───────
    public function konfirmasi($id) {
        $umkm_id = $this->session->userdata('umkm_id');
        $pesanan = $this->Pesanan_model->get($id);
        if (!$pesanan || $pesanan->umkm_id != $umkm_id) show_404();

        $this->Pesanan_model->update_status($id, 'dikonfirmasi');
        $this->session->set_flashdata('success', 'Pesanan dikonfirmasi.');
        redirect('pesanan/detail/' . $id);
    }

    // ── ATUR PENGIRIMAN (post dari form di detail) ────────
    public function atur_kirim($id) {
        $umkm_id = $this->session->userdata('umkm_id');
        $pesanan = $this->Pesanan_model->get($id);
        if (!$pesanan || $pesanan->umkm_id != $umkm_id) show_404();

        // Kalau pickup, langsung tandai selesai/siap
        if ($pesanan->metode_pengiriman === 'pickup') {
            $this->Pesanan_model->update_status($id, 'dikirim'); // "siap diambil"
            $this->session->set_flashdata('success', 'Pesanan ditandai siap diambil.');
            redirect('pesanan/detail/' . $id);
        }

        // Validasi
        $metode = $this->input->post('metode_kirim_umkm', TRUE);
        if (!in_array($metode, ['jasa', 'sendiri'])) {
            $this->session->set_flashdata('error', 'Pilih metode pengiriman.');
            redirect('pesanan/detail/' . $id);
        }

        $update = [
            'metode_kirim_umkm' => $metode,
            'status'            => 'dikirim',
        ];

        if ($metode === 'jasa') {
            $kurir = $this->input->post('jasa_kurir', TRUE);
            if (!$kurir) {
                $this->session->set_flashdata('error', 'Pilih jasa kurir.');
                redirect('pesanan/detail/' . $id);
            }
            $update['jasa_kurir'] = $kurir;
            $update['no_resi']    = $this->input->post('no_resi', TRUE) ?: null;
            $update['estimasi_pengiriman'] = $this->input->post('estimasi_pengiriman', TRUE) ?: null;
        } else {
            // antar sendiri
            $nama_pengantar = $this->input->post('nama_pengantar', TRUE);
            $no_hp          = $this->input->post('no_hp_pengantar', TRUE);
            if (!$nama_pengantar || !$no_hp) {
                $this->session->set_flashdata('error', 'Nama dan nomor HP pengantar wajib diisi.');
                redirect('pesanan/detail/' . $id);
            }
            $update['nama_pengantar']      = $nama_pengantar;
            $update['no_hp_pengantar']     = $no_hp;
            $update['estimasi_pengiriman'] = $this->input->post('estimasi_pengiriman', TRUE) ?: null;
        }

        $this->Pesanan_model->update_pengiriman($id, $update);
        $this->session->set_flashdata('success', 'Pengiriman berhasil diatur.');
        redirect('pesanan/detail/' . $id);
    }

    // ── UPDATE NO RESI (setelah kirim) ────────────────────
    public function update_resi($id) {
        $umkm_id = $this->session->userdata('umkm_id');
        $pesanan = $this->Pesanan_model->get($id);
        if (!$pesanan || $pesanan->umkm_id != $umkm_id) show_404();

        $no_resi = $this->input->post('no_resi', TRUE);
        $this->Pesanan_model->update_pengiriman($id, ['no_resi' => $no_resi]);
        $this->session->set_flashdata('success', 'Nomor resi diperbarui.');
        redirect('pesanan/detail/' . $id);
    }

    // ── SELESAIKAN PESANAN ────────────────────────────────
    public function selesai($id) {
        $umkm_id = $this->session->userdata('umkm_id');
        $pesanan = $this->Pesanan_model->get($id);
        if (!$pesanan || $pesanan->umkm_id != $umkm_id) show_404();

        $this->Pesanan_model->update_status($id, 'selesai');
        $this->session->set_flashdata('success', 'Pesanan selesai.');
        redirect('pesanan/detail/' . $id);
    }

    // ── BATALKAN PESANAN ──────────────────────────────────
    public function batalkan($id) {
        $umkm_id = $this->session->userdata('umkm_id');
        $pesanan = $this->Pesanan_model->get($id);
        if (!$pesanan || $pesanan->umkm_id != $umkm_id) show_404();

        $this->Pesanan_model->update_status($id, 'dibatalkan');
        $this->session->set_flashdata('success', 'Pesanan dibatalkan.');
        redirect('pesanan');
    }
}
