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
        $user_id = (int) $this->session->userdata('user_id');
        $role_id = (int) $this->session->userdata('role_id');

        $data = array_merge($this->data, [
            'title'        => 'Web LKS',
            'notif_latest' => [],
        ]);

        // Hanya tampilkan notifikasi untuk panitia/user
        if ($this->session->userdata('logged_in') && in_array($role_id, [4, 5], true)) {
            $data['notif_unread'] = $this->Notifikasi_model->count_unread($user_id);
            $data['notif_latest'] = $this->Notifikasi_model->get_latest($user_id, 5);
        }

        $this->load->view('landing/index', $data);
    }
}
