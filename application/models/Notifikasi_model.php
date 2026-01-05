<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notifikasi_model extends CI_Model
{
    private $table = 'notifikasi';

    public function get_latest($user_id, $limit = 5)
    {
        return $this->db->from($this->table)
            ->where('user_id', (int) $user_id)
            ->order_by('created_at', 'DESC')
            ->limit((int) $limit)
            ->get()
            ->result_array();
    }

    public function count_unread($user_id)
    {
        return (int) $this->db->from($this->table)
            ->where('user_id', (int) $user_id)
            ->where('is_read', 0)
            ->count_all_results();
    }

    public function get_all($user_id, $limit = 20, $offset = 0)
    {
        return $this->db->from($this->table)
            ->where('user_id', (int) $user_id)
            ->order_by('created_at', 'DESC')
            ->limit((int) $limit, (int) $offset)
            ->get()
            ->result_array();
    }

    public function get_one($notif_id, $user_id)
    {
        return $this->db->from($this->table)
            ->where('id', (int) $notif_id)
            ->where('user_id', (int) $user_id)
            ->get()
            ->row_array();
    }

    public function mark_read($notif_id, $user_id)
    {
        $this->db->where('id', (int) $notif_id);
        $this->db->where('user_id', (int) $user_id);
        return $this->db->update($this->table, ['is_read' => 1]);
    }

    public function mark_all_read($user_id)
    {
        $this->db->where('user_id', (int) $user_id);
        $this->db->where('is_read', 0);
        return $this->db->update($this->table, ['is_read' => 1]);
    }
}
