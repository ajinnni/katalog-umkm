<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model {

    protected $table = 'kategori'; // pastikan tabel kategori sudah ada di database

    public function __construct() {
        parent::__construct();
    }

    /**
     * Ambil semua kategori, diurutkan berdasarkan nama
     */
    public function get_all() {
        return $this->db->order_by('nama', 'ASC')
                        ->get($this->table)
                        ->result();
    }

    /**
     * Ambil kategori berdasarkan ID
     */
    public function get($id) {
        return $this->db->where('id', $id)
                        ->get($this->table)
                        ->row();
    }

    /**
     * Tambah kategori baru
     */
    public function create($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update kategori
     */
    public function update($id, $data) {
        $this->db->where('id', $id)
                 ->update($this->table, $data);
        return $this->db->affected_rows();
    }

    /**
     * Hapus kategori
     */
    public function delete($id) {
        $this->db->where('id', $id)
                 ->delete($this->table);
        return $this->db->affected_rows();
    }
}