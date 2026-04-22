<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesanan_model extends CI_Model {

    protected $table        = 'pesanan';
    protected $table_detail = 'detail_pesanan';

    // ── BASIC ─────────────────────────────────────────────

    public function get($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_by_kode($kode) {
        return $this->db->get_where($this->table, ['kode_pesanan' => $kode])->row();
    }

    public function get_by_umkm($umkm_id, $status = null) {
        $this->db->where('umkm_id', $umkm_id);
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db
            ->order_by('created_at', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function get_by_umkm_with_user($umkm_id, $status = null) {
        $this->db->select('pesanan.*, users.name as nama_user, users.email as email_user')
                 ->from($this->table)
                 ->join('users', 'users.id = pesanan.user_id', 'left')
                 ->where('pesanan.umkm_id', $umkm_id);

        if ($status) {
            $this->db->where('pesanan.status', $status);
        }

        return $this->db
            ->order_by('pesanan.created_at', 'DESC')
            ->get()
            ->result();
    }

    // ── CREATE ────────────────────────────────────────────

    public function create($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function create_detail($data) {
        $this->db->insert($this->table_detail, $data);
        return $this->db->insert_id();
    }

    // ── DETAIL ────────────────────────────────────────────

    public function get_detail($pesanan_id) {
        return $this->db
            ->select('detail_pesanan.*, produk.nama as nama_produk, produk.foto')
            ->from($this->table_detail)
            ->join('produk', 'produk.id = detail_pesanan.produk_id', 'left')
            ->where('detail_pesanan.pesanan_id', $pesanan_id)
            ->get()
            ->result();
    }

    // ── UPDATE ────────────────────────────────────────────

    public function update_status($id, $status) {
        return $this->db->update($this->table, ['status' => $status], ['id' => $id]);
    }

    public function update_pengiriman($id, $data) {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    // ── USER SIDE ─────────────────────────────────────────

    public function get_by_user_with_details($user_id) {
        $pesanan = $this->db
            ->select('pesanan.*, umkm.nama_toko, umkm.no_wa_toko')
            ->from($this->table)
            ->join('umkm', 'umkm.id = pesanan.umkm_id', 'left')
            ->where('pesanan.user_id', $user_id)
            ->order_by('pesanan.created_at', 'DESC')
            ->get()
            ->result();

        foreach ($pesanan as &$p) {
            $p->details = $this->get_detail($p->id);
        }

        return $pesanan;
    }

    public function get_detail_full($id, $user_id = null) {
        $this->db->select('pesanan.*, umkm.nama_toko, umkm.no_wa_toko, umkm.alamat as alamat_toko')
                 ->from($this->table)
                 ->join('umkm', 'umkm.id = pesanan.umkm_id', 'left')
                 ->where('pesanan.id', $id);

        if ($user_id) {
            $this->db->where('pesanan.user_id', $user_id);
        }

        $p = $this->db->get()->row();

        if ($p) {
            $p->details = $this->get_detail($id);
        }

        return $p;
    }

    // ── STATS ─────────────────────────────────────────────

    public function count_by_umkm($umkm_id) {
        return $this->db
            ->where('umkm_id', $umkm_id)
            ->count_all_results($this->table);
    }

    public function count_by_status($umkm_id, $status) {
        return $this->db
            ->where('umkm_id', $umkm_id)
            ->where('status', $status)
            ->count_all_results($this->table);
    }
}