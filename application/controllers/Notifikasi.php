<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notifikasi extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Notifikasi_model', 'notif');

        $this->require_login();
    }

    public function index()
    {
        $user_id = (int) $this->session->userdata('user_id');
        $limit   = 50;
        $unread  = $this->notif->count_unread($user_id);

        $data = array_merge($this->data, [
            'title'        => 'Notifikasi',
            'unread'       => $unread,
            'notif_unread' => $unread,
            'rows'         => $this->notif->get_all($user_id, $limit, 0),
        ]);

        $this->render_for_role('notifikasi/index', $data);
    }

    public function read($id)
    {
        $user_id = (int) $this->session->userdata('user_id');
        $this->notif->mark_read((int) $id, $user_id);
        redirect('notifikasi');
    }

    public function detail($id)
    {
        $user_id = (int) $this->session->userdata('user_id');
        $row     = $this->notif->get_one((int) $id, $user_id);

        if (! $row) {
            show_404();
        }

        if ((int) $row['is_read'] === 0) {
            $this->notif->mark_read((int) $id, $user_id);
            $row['is_read'] = 1;
        }

        $unread = $this->notif->count_unread($user_id);

        $data = array_merge($this->data, [
            'title'        => 'Detail Notifikasi',
            'row'          => $row,
            'notif_unread' => $unread,
        ]);

        $this->render_for_role('notifikasi/detail', $data);
    }

    public function read_all()
    {
        $user_id = (int) $this->session->userdata('user_id');
        $this->notif->mark_all_read($user_id);
        redirect('notifikasi');
    }

    private function render_for_role(string $view, array $data = []): void
    {
        $role_id = (int) $this->session->userdata('role_id');

        if (in_array($role_id, [4, 5], true)) {
            $this->load->view('notifikasi/layout_header', $data);
            $this->load->view($view, $data);
            $this->load->view('notifikasi/layout_footer', $data);
            return;
        }

        $this->render($view, $data);
    }
}
