<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('logged_in'))
        {
            redirect('login');
        }
        if($this->session->userdata('role') !== 'admin')
        {
            show_error('Akses ditolaaak', 403);
        }
    }

	public function index()
	{
        $data = [
            'title' => 'Dashboard Admin'
        ];
		$this->load->view('admin/index',$data);
	}
}