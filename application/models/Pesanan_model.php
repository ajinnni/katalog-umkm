<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesanan_model extends CI_Model {

    protected $table = 'pesanan';

    public function get($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_by_umkm($umkm_id) {
        return $this->db
            ->where('umkm_id', $umkm_id)
            ->order_by('created_at', 'DESC')
            ->get($this->table)->result();
    }

    public function create($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function create_detail($data) {
        $this->db->insert('detail_pesanan', $data);
        return $this->db->insert_id();
    }

    public function get_detail($pesanan_id) {
        return $this->db->get_where('detail_pesanan', ['pesanan_id' => $pesanan_id])->result();
    }

    public function update_status($id, $status) {
        $this->db->update($this->table, ['status' => $status], ['id' => $id]);
    }

    public function count_by_umkm($umkm_id) {
        return $this->db->where('umkm_id', $umkm_id)->count_all_results($this->table);
    }
}