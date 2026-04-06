<?php
// application/models/User_model.php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    protected $table = 'users';

    public function get($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_by_wa($no_wa) {
        return $this->db->get_where($this->table, ['no_wa' => $no_wa])->row();
    }

    public function create($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function set_otp($id, $otp) {
        $this->db->update($this->table, [
            'otp_code'   => $otp,
            'otp_expired'=> date('Y-m-d H:i:s', strtotime('+5 minutes')),
        ], ['id' => $id]);
    }

    public function verify($id) {
        $this->db->update($this->table, [
            'is_verified' => 1,
            'otp_code'    => NULL,
            'otp_expired' => NULL,
        ], ['id' => $id]);
    }

    public function count_by_role($role) {
        return $this->db->where('role', $role)->count_all_results($this->table);
    }


    public function get_all() {
        return $this->db->order_by('created_at', 'DESC')->get($this->table)->result();
    }

    public function update($id, $data) {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }


}