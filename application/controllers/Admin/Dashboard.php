<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->load->model('Pendaftaran_model');
        $this->load->model('Notifikasi_model', 'notif');
        $this->require_login();
        $this->require_role([1, 2, 3]);
    }

    public function index()
    {
        $user_id      = (int) $this->session->userdata('user_id');
        $notif_unread = $this->notif->count_unread($user_id);
        $notif_latest = $this->notif->get_latest($user_id, 5);

        $data = [
            'title'        => 'Dashboard Admin',
            'notif_unread' => $notif_unread,
            'notif_latest' => $notif_latest,
            'total'        => $this->Pendaftaran_model->count_all(),
        ];
        $this->render('admin/index', $data);
    }

    public function users()
    {
        $this->require_role([1]);
        $data = [
            'title' => 'Data Pengguna',
            'users' => $this->Dashboard_model->get_all('users'),
        ];

        $this->render('admin/users', $data);
    }

    public function pendaftar()
    {
        $data = [
            'title'     => 'Data Pengguna',
            'pendaftar' => $this->Dashboard_model->get_all('pendaftaran'),
        ];

        $this->render('admin/pendaftar', $data);
    }

}
