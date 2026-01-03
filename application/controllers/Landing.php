<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Landing extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [
            'title' => 'Web LKS',
        ];
        $this->load->view('landing/index', $data);
    }
}
