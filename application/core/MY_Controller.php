<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->data['user'] = $this->session->userdata();
    }

    protected function require_login()
    {
        if (! $this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    protected function require_role($roles = [])
    {
        $role_id = (int) $this->session->userdata('role_id');
        if (! in_array($role_id, $roles, true)) {
            $this->load->view('errors/forbidden');
        }
    }

    protected function render($view, $data = [])
    {
        $data = array_merge($this->data, $data);

        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/navbar', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view('layouts/wrapper', $data);
        $this->load->view($view, $data);
        $this->load->view('layouts/footer', $data);
    }
}
