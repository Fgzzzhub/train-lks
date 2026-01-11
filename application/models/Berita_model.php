<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Berita_model extends CI_Model
{
    private $table = 'berita';

    public function get_all()
    {
        $this->db->from($this->table . ' b');
        $this->db->join('lomba l', 'l.id = b.lomba_id', 'left');
        $this->db->select([
            'b.*',
            'l.nama_lomba',
            'l.bidang',
        ]);
        $this->db->order_by('b.id', 'DESC');

        return $this->db->get()->result_array();
    }

    public function set_headline($id)
    {
        $this->db->trans_start();

        $this->db->set('is_headline', 0)->update('berita');

        $this->db->where('id', $id)
            ->set('is_headline', 1)
            ->update('berita');

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_headline()
    {
        return $this->db->where('is_headline', 1)
            ->where('is_active', 1) // kalau ada
            ->order_by('created_at', 'DESC')
            ->get('berita')
            ->row();
    }

    public function get_latest($limit = 6)
    {
        $this->db->from($this->table . ' b');
        $this->db->join('lomba l', 'l.id = b.lomba_id', 'left');
        $this->db->select([
            'b.*',
            'l.nama_lomba',
            'l.bidang',
        ]);
        $this->db->order_by('b.id', 'DESC');
        $this->db->limit((int) $limit);

        return $this->db->get()->result_array();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table . ' b');
        $this->db->join('lomba l', 'l.id = b.lomba_id', 'left');
        $this->db->select([
            'b.*',
            'l.nama_lomba',
            'l.bidang',
        ]);
        $this->db->where('b.id', (int) $id);

        return $this->db->get()->row_array();
    }

    public function create(array $data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, array $data)
    {
        $this->db->where('id', (int) $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', (int) $id);
        return $this->db->delete($this->table);
    }
}
