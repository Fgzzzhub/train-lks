<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->require_login();
        $this->require_role([1, 2, 3]);
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard Admin',
        ];
        $this->render('admin/index', $data);
    }

    public function users()
    {
        $data = [
            'title' => 'Data Pengguna',
            'users' => $this->Dashboard_model->get_all('users'),
        ];

        $this->render('admin/users', $data);
    }
}
