<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as SpreadsheetDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        if (! $this->require_admin()) {
            return;
        }

        $status = $this->input->get('status', true) ?: ''; // pending/approved/rejected/''(all)
        $q      = $this->input->get('q', true) ?: '';
        $lomba_id = (int) ($this->input->get('lomba_id') ?: 0);
        $page   = (int) ($this->input->get('page') ?: 1);
        $limit  = 10;
        $offset = ($page > 1) ? (($page - 1) * $limit) : 0;

        $total = $this->pendaftaran->count_admin($status, $q, $lomba_id);

        $config['base_url']             = site_url('admin/pendaftaran?status=' . urlencode($status) . '&q=' . urlencode($q) . '&lomba_id=' . $lomba_id);
        $config['total_rows']           = $total;
        $config['per_page']             = $limit;
        $config['page_query_string']    = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers']     = true;

        $this->pagination->initialize($config);

        $data['title']      = 'Pendaftaran';
        $data['rows']       = $this->pendaftaran->get_list_admin($status, $q, $limit, $offset, $lomba_id);
        $data['total']      = $total;
        $data['status']     = $status;
        $data['q']          = $q;
        $data['lomba_id']   = $lomba_id;
        $data['lomba_list'] = $this->lomba->get_all();
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
        if (! $this->require_admin()) {
            return;
        }

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
        if (! $this->require_admin()) {
            return;
        }

        $ok = $this->pendaftaran->approve((int) $id);
        $this->session->set_flashdata('success', 'Approved');
        redirect('admin/pendaftaran');
    }

    public function reject($id)
    {
        if (! $this->require_admin()) {
            return;
        }

        $catatan = $this->input->post('catatan', true);
        $ok      = $this->pendaftaran->reject((int) $id, $catatan);
        redirect('admin/pendaftaran');
    }

    public function export()
    {
        if (! $this->require_admin()) {
            return;
        }

        if (! $this->load_spreadsheet()) {
            redirect('admin/pendaftaran');
            return;
        }

        $status   = $this->input->get('status', true) ?: '';
        $q        = $this->input->get('q', true) ?: '';
        $lomba_id = (int) ($this->input->get('lomba_id') ?: 0);

        $rows = $this->pendaftaran->get_all_admin($status, $q, $lomba_id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'Nama',
            'Jenis Kelamin (L/P)',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Sekolah',
            'NPSN',
            'Kabupaten',
            'Lomba ID',
            'Status',
            'Catatan',
            'Nama Lomba',
            'Bidang',
            'Tanggal Lomba',
            'Lokasi',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $rowIndex = 2;
        foreach ($rows as $row) {
            $sheet->fromArray([
                (string) ($row['nama'] ?? ''),
                (string) ($row['jenis_kelamin'] ?? ''),
                (string) ($row['tanggal_lahir'] ?? ''),
                (string) ($row['sekolah'] ?? ''),
                (string) ($row['npsn'] ?? ''),
                (string) ($row['kabupaten'] ?? ''),
                (string) ($row['lomba_id'] ?? ''),
                (string) ($row['status'] ?? ''),
                (string) ($row['catatan'] ?? ''),
                (string) ($row['nama_lomba'] ?? ''),
                (string) ($row['bidang'] ?? ''),
                (string) ($row['tanggal'] ?? ''),
                (string) ($row['lokasi'] ?? ''),
            ], null, 'A' . $rowIndex);
            $rowIndex++;
        }

        $filename = 'export-peserta-' . date('Ymd-His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function template()
    {
        if (! $this->require_admin()) {
            return;
        }

        if (! $this->load_spreadsheet()) {
            redirect('admin/pendaftaran');
            return;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([
            'Nama',
            'Jenis Kelamin (L/P)',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Sekolah',
            'NPSN',
            'Kabupaten',
            'Lomba ID',
            'Status (pending/approved/rejected)',
            'Catatan',
        ], null, 'A1');

        $filename = 'template-import-peserta.xlsx';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function import()
    {
        if (! $this->require_admin()) {
            return;
        }

        $data = [
            'title' => 'Import Peserta',
        ];

        if ($this->input->method() !== 'post') {
            $this->render('admin/pendaftaran/import', $data);
            return;
        }

        if (! $this->load_spreadsheet()) {
            $this->session->set_flashdata('error', 'Autoload composer tidak ditemukan. Jalankan composer install.');
            $this->render('admin/pendaftaran/import', $data);
            return;
        }

        if (empty($_FILES['file']['name'])) {
            $this->session->set_flashdata('error', 'File belum dipilih.');
            $this->render('admin/pendaftaran/import', $data);
            return;
        }

        $upload_dir = FCPATH . 'uploads/tmp';
        if (! is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $config = [
            'upload_path'   => $upload_dir,
            'allowed_types' => 'xlsx|xls|csv',
            'max_size'      => 5120,
            'encrypt_name'  => true,
        ];

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (! $this->upload->do_upload('file')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            $this->render('admin/pendaftaran/import', $data);
            return;
        }

        $upload_data = $this->upload->data();
        $file_path   = $upload_data['full_path'];

        try {
            $reader = IOFactory::createReaderForFile($file_path);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file_path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, false);
        } catch (Exception $e) {
            if (is_file($file_path)) {
                unlink($file_path);
            }

            $this->session->set_flashdata('error', 'File tidak bisa dibaca. Pastikan format Excel/CSV sesuai.');
            $this->render('admin/pendaftaran/import', $data);
            return;
        }

        $inserted = 0;
        $skipped  = 0;

        $lomba_list = $this->lomba->get_all();
        $lomba_map = [];
        foreach ($lomba_list as $lomba) {
            $lomba_map[(int) $lomba->id] = true;
        }

        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue; // header
            }

            $has_value = false;
            foreach ($row as $value) {
                if (trim((string) $value) !== '') {
                    $has_value = true;
                    break;
                }
            }

            if (! $has_value) {
                continue;
            }

            $nama          = trim((string) ($row[0] ?? ''));
            $jenis_kelamin = strtoupper(trim((string) ($row[1] ?? '')));
            $tanggal_lahir = $this->normalize_excel_date($row[2] ?? null);
            $sekolah       = trim((string) ($row[3] ?? ''));
            $npsn          = trim((string) ($row[4] ?? ''));
            $kabupaten     = trim((string) ($row[5] ?? ''));
            $lomba_id      = (int) ($row[6] ?? 0);
            $status        = strtolower(trim((string) ($row[7] ?? 'pending')));
            $catatan       = trim((string) ($row[8] ?? ''));

            if ($jenis_kelamin === 'LAKI-LAKI' || $jenis_kelamin === 'LAKI LAKI') {
                $jenis_kelamin = 'L';
            } elseif ($jenis_kelamin === 'PEREMPUAN') {
                $jenis_kelamin = 'P';
            }

            if ($status === '') {
                $status = 'pending';
            }

            if (! in_array($status, ['pending', 'approved', 'rejected'], true)) {
                $status = 'pending';
            }

            if ($nama === '' || $jenis_kelamin === '' || $tanggal_lahir === null || $sekolah === '' || $npsn === '' || $kabupaten === '' || $lomba_id === 0) {
                $skipped++;
                continue;
            }

            if (! in_array($jenis_kelamin, ['L', 'P'], true)) {
                $skipped++;
                continue;
            }

            if (! isset($lomba_map[$lomba_id])) {
                $skipped++;
                continue;
            }

            if ($this->pendaftaran->exists_by_name_lomba($nama, $lomba_id)) {
                $skipped++;
                continue;
            }

            $payload = [
                'user_id'       => null,
                'lomba_id'      => $lomba_id,
                'nama'          => $nama,
                'jenis_kelamin' => $jenis_kelamin,
                'tanggal_lahir' => $tanggal_lahir,
                'sekolah'       => $sekolah,
                'npsn'          => $npsn,
                'kabupaten'     => $kabupaten,
                'status'        => $status,
                'catatan'       => $catatan !== '' ? $catatan : null,
                'created_at'    => date('Y-m-d H:i:s'),
            ];

            if ($this->pendaftaran->create($payload)) {
                $inserted++;
            } else {
                $skipped++;
            }
        }

        if (is_file($file_path)) {
            unlink($file_path);
        }

        $this->session->set_flashdata('success', 'Import selesai. Berhasil: ' . $inserted . ', dilewati: ' . $skipped . '.');
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

    private function load_spreadsheet(): bool
    {
        if (class_exists('PhpOffice\\PhpSpreadsheet\\Spreadsheet')) {
            return true;
        }

        $autoload = FCPATH . 'vendor/autoload.php';
        if (! file_exists($autoload)) {
            return false;
        }

        require_once $autoload;

        return class_exists('PhpOffice\\PhpSpreadsheet\\Spreadsheet');
    }

    private function normalize_excel_date($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            try {
                $date = SpreadsheetDate::excelToDateTimeObject((float) $value);
                return $date->format('Y-m-d');
            } catch (Exception $e) {
                return null;
            }
        }

        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return null;
        }

        return date('Y-m-d', $timestamp);
    }
}
