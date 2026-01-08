<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pendaftaran extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pendaftaran_model', 'pendaftaran');
        $this->load->model('Lomba_model', 'lomba');
        $this->load->library('pagination');
        // $this->require_role([1, 2, 3]);
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

    public function daftar()
    {
        if (! $this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
            redirect('login');
            return;
        }

        $data = [
            'title'      => 'Daftar Lomba',
            'lomba'      => null,
            'lomba_list' => [],
        ];

        $lomba_id = (int) ($this->input->post('lomba_id') ?: $this->input->get('lomba_id'));
        if ($lomba_id > 0) {
            $data['lomba'] = $this->lomba->get_by_id($lomba_id);
            if (! $data['lomba']) {
                $this->session->set_flashdata('error', 'Mata lomba tidak ditemukan.');
                redirect('pendaftaran/daftar');
                return;
            }
        } else {
            $data['lomba_list'] = $this->lomba->get_all();
        }

        if ($this->input->method() !== 'post') {
            $this->render_for_role('admin/pendaftaran/daftar', $data);
            return;
        }

        $this->__set_register_rules();
        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('error', validation_errors('<p class="text-sm text-danger my-0">', '</p>'));
            if (empty($data['lomba']) && empty($data['lomba_list'])) {
                $data['lomba_list'] = $this->lomba->get_all();
            }
            $this->render_for_role('admin/pendaftaran/daftar', $data);

            return;
        }

        $user_id  = (int) $this->session->userdata('user_id');
        $lomba_id = (int) $this->input->post('lomba_id', true);

        if ($this->pendaftaran->exists_by_user_lomba($user_id, $lomba_id)) {
            $this->session->set_flashdata('error', 'Anda sudah terdaftar pada mata lomba ini.');
            redirect('pendaftaran/daftar?lomba_id=' . $lomba_id);
            return;
        }

        $payload = [
            'user_id'       => $user_id,
            'lomba_id'      => $lomba_id,
            'nama'          => $this->input->post('nama', true),
            'jenis_kelamin' => $this->input->post('jenis_kelamin', true),
            'tanggal_lahir' => $this->input->post('tanggal_lahir', true),
            'sekolah'       => $this->input->post('sekolah', true),
            'npsn'          => $this->input->post('npsn', true),
            'kabupaten'     => $this->input->post('kabupaten', true),
            'status'        => 'pending',
            'catatan'       => null,
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $is_saved = $this->pendaftaran->create($payload);

        if (! $is_saved) {
            $this->session->set_flashdata('error', 'Pendaftaran gagal, silakan coba lagi');
            $this->render_for_role('admin/pendaftaran/daftar', $data);

            return;
        }

        // var_dump($payload);
        // die;

        $this->session->set_flashdata('success', 'Pendaftaran berhasil');
        redirect('landing');
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

    private function __set_register_rules()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|is_unique[pendaftar.nama]');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required|in_list[L,P]');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim|required');
        $this->form_validation->set_rules('sekolah', 'Sekolah', 'trim|required');
        $this->form_validation->set_rules('npsn', 'Npsn', 'trim|required');
        $this->form_validation->set_rules('kabupaten', 'Kabupaten', 'trim|required');
        $this->form_validation->set_rules('lomba_id', 'Mata Lomba', 'trim|required|integer');
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
