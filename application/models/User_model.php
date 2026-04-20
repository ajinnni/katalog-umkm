<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    protected $table = 'users';

    public function get($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    // Login by email atau username
    public function get_by_identifier($identifier) {
        return $this->db
            ->where('email', $identifier)
            ->or_where('username', $identifier)
            ->get($this->table)->row();
    }

    public function get_by_wa($no_wa) {
        return $this->db->get_where($this->table, ['no_wa' => $no_wa])->row();
    }

    public function email_exists($email) {
        return $this->db->where('email', $email)->count_all_results($this->table) > 0;
    }

    public function username_exists($username) {
        return $this->db->where('username', $username)->count_all_results($this->table) > 0;
    }

   public function create($data) {
    $insert = $this->db->insert('users', $data);

    if (!$insert) {
        return false;
    }

    return $this->db->insert_id();
}

    public function update_wa_verified($id, $no_wa) {
        $this->db->update($this->table, [
            'no_wa'       => $no_wa,
            'is_verified' => 1,
        ], ['id' => $id]);
    }

    public function reset_login_attempts($id) {
        $this->db->update($this->table, [
            'login_attempts' => 0,
            'locked_until'   => null,
        ], ['id' => $id]);
    }

    public function get_all() {
        return $this->db->get($this->table)->result();
    }

    public function count_by_role($role)
{
    return $this->db
        ->where('role', $role)
        ->count_all_results($this->table);
}

    public function set_otp($id, $otp) {
        $this->db->update($this->table, [
            'otp_code'    => $otp,
            'otp_expired' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
        ], ['id' => $id]);
    }

    public function verify($id) {
        $this->db->update($this->table, [
            'is_verified' => 1,
            'otp_code'    => null,
            'otp_expired' => null,
        ], ['id' => $id]);
    }
}