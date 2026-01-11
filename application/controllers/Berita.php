<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Berita extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Berita_model', 'berita');
        $this->load->model('Lomba_model', 'lomba');
    }

    public function index()
    {
        if (! $this->require_admin()) {
            return;
        }

        $data = [
            'title' => 'Berita',
            'rows'  => $this->berita->get_all(),
        ];

        $this->render('admin/berita/index', $data);
    }

    public function create()
    {
        if (! $this->require_admin()) {
            return;
        }
        $this->require_role([1]);

        $data = [
            'title'      => 'Tambah Berita',
            'lomba_list' => $this->lomba->get_all(),
            'row'        => null,
        ];

        if ($this->input->method() !== 'post') {
            $this->render('admin/berita/form', $data);
            return;
        }

        $this->form_validation->set_rules('lomba_id', 'Mata Lomba', 'required|integer');
        $this->form_validation->set_rules('judul', 'Judul', 'trim|required');
        $this->form_validation->set_rules('isi', 'Isi', 'trim');

        if ($this->form_validation->run() === false) {
            $this->render('admin/berita/form', $data);
            return;
        }

        $upload = $this->handle_upload('foto', 'uploads/berita', 'jpg|jpeg|png|webp', 4096);
        if (isset($upload['error'])) {
            $this->session->set_flashdata('error', $upload['error']);
            $this->render('admin/berita/form', $data);
            return;
        }

        $payload = [
            'lomba_id'   => (int) $this->input->post('lomba_id', true),
            'judul'      => $this->input->post('judul', true),
            'isi'        => $this->input->post('isi', true),
            'foto'       => $upload['path'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null,
        ];

        $this->berita->create($payload);
        $this->session->set_flashdata('success', 'Berita berhasil ditambahkan.');
        redirect('berita');
    }

    public function edit($id)
    {
        $this->require_role([1]);
        if (! $this->require_admin()) {
            return;
        }

        $row = $this->berita->get_by_id((int) $id);
        if (! $row) {
            show_404();
        }

        $data = [
            'title'      => 'Edit Berita',
            'lomba_list' => $this->lomba->get_all(),
            'row'        => $row,
        ];

        if ($this->input->method() !== 'post') {
            $this->render('admin/berita/form', $data);
            return;
        }

        $this->form_validation->set_rules('lomba_id', 'Mata Lomba', 'required|integer');
        $this->form_validation->set_rules('judul', 'Judul', 'trim|required');
        $this->form_validation->set_rules('isi', 'Isi', 'trim');

        if ($this->form_validation->run() === false) {
            $this->render('admin/berita/form', $data);
            return;
        }

        $upload = $this->handle_upload('foto', 'uploads/berita', 'jpg|jpeg|png|webp', 4096);
        if (isset($upload['error'])) {
            $this->session->set_flashdata('error', $upload['error']);
            $this->render('admin/berita/form', $data);
            return;
        }

        if (! empty($upload['path']) && ! empty($row['foto'])) {
            $old_path = FCPATH . $row['foto'];
            if (is_file($old_path)) {
                unlink($old_path);
            }
        }

        $payload = [
            'lomba_id'   => (int) $this->input->post('lomba_id', true),
            'judul'      => $this->input->post('judul', true),
            'isi'        => $this->input->post('isi', true),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (! empty($upload['path'])) {
            $payload['foto'] = $upload['path'];
        }

        $this->berita->update((int) $id, $payload);
        $this->session->set_flashdata('success', 'Berita berhasil diperbarui.');
        redirect('berita');
    }

    public function delete($id)
    {
        $this->require_role([1]);
        if (! $this->require_admin()) {
            return;
        }

        $row = $this->berita->get_by_id((int) $id);
        if (! $row) {
            show_404();
        }

        if (! empty($row['foto'])) {
            $path = FCPATH . $row['foto'];
            if (is_file($path)) {
                unlink($path);
            }
        }

        $this->berita->delete((int) $id);
        $this->session->set_flashdata('success', 'Berita berhasil dihapus.');
        redirect('berita');
    }

    public function public_index()
    {
        $logged_in = (bool) $this->session->userdata('logged_in');
        $role_id   = (int) $this->session->userdata('role_id');

        if ($logged_in && in_array($role_id, [1, 2, 3], true)) {
            redirect('admin');
            return;
        }

        $data = [
            'title' => 'Berita',
            'rows'  => $this->berita->get_all(),
        ];

        $this->load->view('landing/berita_index', $data);
    }

    public function detail($id)
    {
        $logged_in = (bool) $this->session->userdata('logged_in');
        $role_id   = (int) $this->session->userdata('role_id');

        if ($logged_in && ! in_array($role_id, [4, 5], true)) {
            show_404();
        }

        $row = $this->berita->get_by_id((int) $id);
        if (! $row) {
            show_404();
        }

        $data = array_merge($this->data, [
            'title' => 'Detail Berita',
            'row'   => $row,
        ]);

        $this->load->view('landing/berita_detail', $data);
    }

    private function handle_upload(string $field, string $directory, string $allowed_types, int $max_size): array
    {
        if (empty($_FILES[$field]['name'])) {
            return ['path' => null];
        }

        $upload_dir = FCPATH . $directory;
        if (! is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $config = [
            'upload_path'   => $upload_dir,
            'allowed_types' => $allowed_types,
            'max_size'      => $max_size,
            'encrypt_name'  => true,
        ];

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (! $this->upload->do_upload($field)) {
            return ['error' => $this->upload->display_errors('', '')];
        }

        $data = $this->upload->data();

        return [
            'path' => $directory . '/' . $data['file_name'],
        ];
    }
}
