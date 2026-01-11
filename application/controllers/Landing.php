<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Landing extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Lomba_model');
        $this->load->model('Berita_model', 'berita');
        $this->load->library('pagination');
    }

    public function index()
    {
        $user_id   = (int) $this->session->userdata('user_id');
        $role_id   = (int) $this->session->userdata('role_id');
        $logged_in = (bool) $this->session->userdata('logged_in');

        if ($logged_in && in_array($role_id, [1, 2, 3], true)) {
            redirect('admin');
            return;
        }

        $page   = (int) ($this->input->get('page') ?: 1);
        $limit  = 4;
        $offset = ($page > 1) ? (($page - 1) * $limit) : 0;

        $total_lomba                    = $this->Lomba_model->count_all();
        $config['base_url']             = site_url('landing');
        $config['total_rows']           = $total_lomba;
        $config['per_page']             = $limit;
        $config['page_query_string']    = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers']     = true;
        $config['first_link']           = 'First';
        $config['last_link']            = 'Last';
        $config['next_link']            = '&raquo;';
        $config['prev_link']            = '&laquo;';
        $config['full_tag_open']        = '<ul class="pagination pagination-sm m-0">';
        $config['full_tag_close']       = '</ul>';
        $config['num_tag_open']         = '<li class="page-item">';
        $config['num_tag_close']        = '</li>';
        $config['cur_tag_open']         = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']        = '</span></li>';
        $config['next_tag_open']        = '<li class="page-item">';
        $config['next_tag_close']       = '</li>';
        $config['prev_tag_open']        = '<li class="page-item">';
        $config['prev_tag_close']       = '</li>';
        $config['first_tag_open']       = '<li class="page-item">';
        $config['first_tag_close']      = '</li>';
        $config['last_tag_open']        = '<li class="page-item">';
        $config['last_tag_close']       = '</li>';
        $config['attributes']           = ['class' => 'page-link'];

        $this->pagination->initialize($config);

        $show_berita = (! $logged_in) || in_array($role_id, [4, 5], true);
        $berita      = $show_berita ? $this->berita->get_latest(3) : [];

        $data = array_merge($this->data, [
            'title'        => 'Web LKS',
            'notif_latest' => [],
            'matalomba'    => $this->Lomba_model->get_paginated($limit, $offset),
            'berita'       => $berita,
            'show_berita'  => $show_berita,
            'pagination'   => $this->pagination->create_links(),
            'page'         => $page,
            'limit'        => $limit,
            'total_lomba'  => $total_lomba,
        ]);

        // Hanya tampilkan notifikasi untuk panitia/user
        if ($logged_in && in_array($role_id, [4, 5], true)) {
            $data['notif_unread'] = $this->Notifikasi_model->count_unread($user_id);
            $data['notif_latest'] = $this->Notifikasi_model->get_latest($user_id, 5);
        }

        $this->load->view('landing/index', $data);
    }
}
