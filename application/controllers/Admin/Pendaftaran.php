<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pendaftaran extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pendaftaran_model', 'pendaftaran');
        $this->load->library('pagination');
        $this->require_role([1, 2, 3]);
    }

    public function index()
    {
        $status = $this->input->get('status', true) ?: ''; // pending/approved/rejected/''(all)
        $q      = $this->input->get('q', true) ?: '';
        $page   = (int) ($this->input->get('page') ?: 1);
        $limit  = 10;
        $offset = ($page > 1) ? (($page - 1) * $limit) : 0;

        $total = $this->pendaftaran->count_admin($status, $q);

        $config['base_url']             = site_url('admin/pendaftaran?status=' . urlencode($status) . '&q=' . urlencode($q));
        $config['total_rows']           = $total;
        $config['per_page']             = $limit;
        $config['page_query_string']    = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers']     = true;

        $this->pagination->initialize($config);

        $data['title']      = 'Pendaftaran';
        $data['rows']       = $this->pendaftaran->get_list_admin($status, $q, $limit, $offset);
        $data['total']      = $total;
        $data['status']     = $status;
        $data['q']          = $q;
        $data['pagination'] = $this->pagination->create_links();

        $this->render('admin/pendaftaran/index', $data);
    }

    public function detail($id)
    {
        $row = $this->pendaftaran->get_detail_admin((int) $id);
        if (! $row) {
            show_404();
        }

        $data = [
            'title' => 'Detail Pendaftaran',
            'row'   => $row,
        ];

        $this->render('admin/pendaftaran/detail', $data);
    }

    public function approve($id)
    {
        $ok = $this->pendaftaran->approve((int) $id);
        $this->session->set_flashdata('success', 'Approved');
        redirect('admin/pendaftaran');
    }

    public function reject($id)
    {
        $catatan = $this->input->post('catatan', true);
        $ok      = $this->pendaftaran->reject((int) $id, $catatan);
        redirect('admin/pendaftaran');
    }
}
