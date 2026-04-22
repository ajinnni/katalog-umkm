<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Umkm_model extends CI_Model {

    protected $table = 'umkm';

    // Ambil semua UMKM join dengan users
    public function get_all() {
        return $this->db
            ->select('umkm.*, users.nama as nama_pemilik, users.no_wa as wa_pemilik')
            ->from($this->table)
            ->join('users', 'users.id = umkm.user_id', 'left')
            ->order_by('umkm.created_at', 'DESC')
            ->get()->result();
    }

    // Ambil satu UMKM by ID
    public function get($id) {
        return $this->db
            ->select('umkm.*, users.nama as nama_pemilik, users.no_wa as wa_pemilik')
            ->from($this->table)
            ->join('users', 'users.id = umkm.user_id', 'left')
            ->where('umkm.id', $id)
            ->get()->row();
    }

    // Ambil UMKM by user_id
    public function get_by_user($user_id) {
        return $this->db->get_where($this->table, ['user_id' => $user_id])->row();
    }

    // Buat UMKM baru
    public function create($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // Update UMKM
    public function update($id, $data) {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    // Hapus UMKM
    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    // Toggle aktif/nonaktif
    public function toggle_active($id) {
        $umkm = $this->db->get_where($this->table, ['id' => $id])->row();
        if (!$umkm) return FALSE;
        return $this->db->update($this->table, ['is_active' => !$umkm->is_active], ['id' => $id]);
    }

    // Hitung total UMKM
    public function count_all() {
        return $this->db->count_all($this->table);
    }

    // Ambil users dengan role umkm yang belum punya toko
    public function get_available_owners() {
        $existing = $this->db->select('user_id')->get($this->table)->result_array();
        $taken_ids = array_column($existing, 'user_id');

        $this->db->select('id, nama, no_wa')
                 ->from('users')
                 ->where('role', 'umkm');
        if (!empty($taken_ids)) {
            $this->db->where_not_in('id', $taken_ids);
        }
        return $this->db->get()->result();
    }

    public function get_pending() {
    return $this->db
        ->select('umkm.*, users.nama as nama_pemilik')
        ->from($this->table)
        ->join('users', 'users.id = umkm.user_id', 'left')
        ->where('umkm.is_active', 0)
        ->order_by('umkm.created_at', 'ASC')
        ->get()->result();
}
}