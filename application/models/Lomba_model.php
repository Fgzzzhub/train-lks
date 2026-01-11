<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lomba_model extends CI_Model
{
    private $table = 'lomba';

    public function get_all()
    {
        return $this->db->
            get($this->table)
            ->result();
    }

    public function count_all()
    {
        return (int) $this->db->count_all($this->table);
    }

    public function get_paginated($limit, $offset)
    {
        return $this->db
            ->limit((int) $limit, (int) $offset)
            ->get($this->table)
            ->result();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->get_where($this->table, ['id' => (int) $id])
            ->row();
    }
}
