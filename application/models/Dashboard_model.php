<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{

    public function get_all($table)
    {
        return $this->db->get($table)->result();
    }
}
