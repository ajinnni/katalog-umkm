<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Otp_model extends CI_Model {

    protected $table = 'otp_codes';

    public function create($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // Ambil OTP aktif terbaru milik user
    public function get_active($user_id) {
        return $this->db
            ->where('user_id', $user_id)
            ->where('is_used', 0)
            ->where('expired_at >', date('Y-m-d H:i:s'))
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get($this->table)->row();
    }

    // OTP terbaru (untuk cek cooldown resend)
    public function get_latest($user_id) {
        return $this->db
            ->where('user_id', $user_id)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get($this->table)->row();
    }

    public function increment_attempts($id) {
        $this->db->set('attempts', 'attempts + 1', false)
                 ->where('id', $id)
                 ->update($this->table);
    }

    public function mark_used($id) {
        $this->db->update($this->table, ['is_used' => 1], ['id' => $id]);
    }

    public function invalidate($id) {
        $this->db->update($this->table, ['is_used' => 1], ['id' => $id]);
    }

    public function invalidate_all($user_id) {
        $this->db->update($this->table, ['is_used' => 1], ['user_id' => $user_id]);
    }

    // Cleanup OTP expired (bisa dijadwalkan)
    public function cleanup_expired() {
        $this->db->where('expired_at <', date('Y-m-d H:i:s'))
                 ->delete($this->table);
    }
}