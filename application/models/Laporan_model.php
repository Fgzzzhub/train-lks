<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_model extends CI_Model
{
    private $table = 'laporan';

    public function get_all()
    {
        $this->db->from($this->table . ' lp');
        $this->db->join('lomba l', 'l.id = lp.lomba_id', 'left');
        $this->db->select([
            'lp.*',
            'l.nama_lomba',
            'l.bidang',
        ]);
        $this->db->order_by('lp.id', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table . ' lp');
        $this->db->join('lomba l', 'l.id = lp.lomba_id', 'left');
        $this->db->select([
            'lp.*',
            'l.nama_lomba',
            'l.bidang',
        ]);
        $this->db->where('lp.id', (int) $id);

        return $this->db->get()->row_array();
    }

    public function create(array $data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', (int) $id);
        return $this->db->delete($this->table);
    }
}
