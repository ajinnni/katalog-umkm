<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    protected $table = 'produk';

    public function get($id) {
        return $this->db
            ->select('produk.*, kategori.nama AS kategori_nama')
            ->from($this->table)
            ->join('kategori', 'kategori.id = produk.kategori_id', 'left')
            ->where('produk.id', $id)
            ->get()->row();
    }

    public function get_by_umkm($umkm_id) {
    $query = $this->db
        ->select('produk.*, COALESCE(kategori.nama, "-") AS kategori_nama')
        ->from($this->table)
        ->join('kategori', 'kategori.id = produk.kategori_id', 'left')
        ->where('produk.umkm_id', $umkm_id)
        ->order_by('produk.id', 'DESC')
        ->get();

    if (!$query) return [];
    return $query->result();
}

    public function get_all_active($keyword = '', $kategori_id = null) {
        $this->db
            ->select('produk.*, kategori.nama AS kategori_nama, umkm.nama_toko')
            ->from($this->table)
            ->join('kategori', 'kategori.id = produk.kategori_id', 'left')
            ->join('umkm', 'umkm.id = produk.umkm_id', 'left')
            ->where('produk.is_active', 1)
            ->where('umkm.is_active', 1);

        if ($keyword) {
            $this->db->group_start()
                ->like('produk.nama', $keyword)
                ->or_like('umkm.nama_toko', $keyword)
                ->group_end();
        }

        if ($kategori_id) {
            $this->db->where('produk.kategori_id', $kategori_id);
        }

        return $this->db->order_by('produk.id', 'DESC')->get()->result();
    }

    public function create($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }


    public function get_produk($keyword = null, $kategori = null)
    {
        $this->db->select('*');
        $this->db->from('produk');

        if ($keyword) {
            $this->db->like('nama', $keyword);
        }

        if ($kategori) {
            $this->db->where('kategori_id', $kategori);
        }

        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('produk', ['id' => $id])->row();
    }
}

