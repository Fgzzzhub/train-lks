<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Laporan_model', 'laporan');
        $this->load->model('Lomba_model', 'lomba');
    }

    public function index()
    {
        if (! $this->require_admin()) {
            return;
        }

        $data = [
            'title' => 'Laporan',
            'rows'  => $this->laporan->get_all(),
        ];

        $this->render('admin/laporan/index', $data);
    }

    public function create()
    {
        if (! $this->require_admin()) {
            return;
        }

        $data = [
            'title'      => 'Upload Laporan',
            'lomba_list' => $this->lomba->get_all(),
        ];

        if ($this->input->method() !== 'post') {
            $this->render('admin/laporan/form', $data);
            return;
        }

        $this->form_validation->set_rules('lomba_id', 'Mata Lomba', 'required|integer');
        $this->form_validation->set_rules('judul', 'Judul', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

        if ($this->form_validation->run() === false) {
            $this->render('admin/laporan/form', $data);
            return;
        }

        $upload = $this->handle_upload('file', 'uploads/laporan', 'pdf|doc|docx|xls|xlsx|csv', 10240);
        if (isset($upload['error'])) {
            $this->session->set_flashdata('error', $upload['error']);
            $this->render('admin/laporan/form', $data);
            return;
        }

        if (empty($upload['path'])) {
            $this->session->set_flashdata('error', 'File laporan belum dipilih.');
            $this->render('admin/laporan/form', $data);
            return;
        }

        $payload = [
            'lomba_id'   => (int) $this->input->post('lomba_id', true),
            'judul'      => $this->input->post('judul', true),
            'keterangan' => $this->input->post('keterangan', true),
            'file_path'  => $upload['path'],
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->laporan->create($payload);
        $this->session->set_flashdata('success', 'Laporan berhasil diunggah.');
        redirect('laporan');
    }

    public function delete($id)
    {
        if (! $this->require_admin()) {
            return;
        }

        $row = $this->laporan->get_by_id((int) $id);
        if (! $row) {
            show_404();
        }

        if (! empty($row['file_path'])) {
            $path = FCPATH . $row['file_path'];
            if (is_file($path)) {
                unlink($path);
            }
        }

        $this->laporan->delete((int) $id);
        $this->session->set_flashdata('success', 'Laporan berhasil dihapus.');
        redirect('laporan');
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
