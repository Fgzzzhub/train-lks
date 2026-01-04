<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

    protected $table = 'users';

	public function get_by_username($username)
	{
		return $this->db->get_where($this->table,['username'=> $username])->row();
	}

    public function create(array $data)
    {
        return $this->db->insert($this->table, $data);
    }
}
