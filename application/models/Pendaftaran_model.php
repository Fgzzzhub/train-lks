<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pendaftaran_model extends CI_Model
{
    private $table = 'pendaftar';

    private function _base_admin_query($status = '', $q = '', $lomba_id = 0)
    {
        $this->db->from($this->table . ' p');
        $this->db->join('users u', 'u.id = p.user_id', 'left'); // user_id bisa NULL
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        $this->db->join('lomba l', 'l.id = p.lomba_id', 'left');

        // Select field untuk admin
        $this->db->select([
            'p.id AS pendaftar_id',
            'p.user_id',
            'u.username',
            'r.name AS role',
            'p.lomba_id',
            'l.nama_lomba',
            'l.bidang',
            'l.tanggal',
            'l.lokasi',

            // data pendaftar
            'p.nama',
            'p.jenis_kelamin',
            'p.tanggal_lahir',
            'p.sekolah',
            'p.npsn',
            'p.kabupaten',

            // status
            'p.status',
            'p.catatan',
            'p.created_at',
        ]);

        // Filter status jika ada
        if (! empty($status)) {
            $this->db->where('p.status', $status); // pending/approved/rejected
        }

        if ((int) $lomba_id > 0) {
            $this->db->where('p.lomba_id', (int) $lomba_id);
        }

        // Search (karena users tidak punya nama/email, pakai kolom yg ada)
        if (! empty($q)) {
            $this->db->group_start()
                ->like('p.nama', $q)
                ->or_like('u.username', $q)
                ->or_like('p.sekolah', $q)
                ->or_like('p.kabupaten', $q)
                ->or_like('l.nama_lomba', $q)
                ->group_end();
        }
    }

    /** List admin (pagination) */
    public function get_list_admin($status = '', $q = '', $limit = 10, $offset = 0, $lomba_id = 0)
    {
        $this->_base_admin_query($status, $q, $lomba_id);
        $this->db->order_by('p.id', 'DESC'); // created_at kamu varchar, jadi aman pakai id desc
        $this->db->limit((int) $limit, (int) $offset);

        return $this->db->get()->result_array();
    }

    /** Count untuk pagination */
    public function count_admin($status = '', $q = '', $lomba_id = 0)
    {
        $this->_base_admin_query($status, $q, $lomba_id);
        return (int) $this->db->count_all_results();
    }

    /** List admin tanpa pagination (export) */
    public function get_all_admin($status = '', $q = '', $lomba_id = 0)
    {
        $this->_base_admin_query($status, $q, $lomba_id);
        $this->db->order_by('p.id', 'DESC');

        return $this->db->get()->result_array();
    }

    /** Detail admin by id */
    public function get_detail_admin($pendaftar_id)
    {
        $this->_base_admin_query('', '');
        $this->db->where('p.id', (int) $pendaftar_id);

        return $this->db->get()->row_array();
    }

    /** Approve + notifikasi (jika user_id ada) */
    public function approve($pendaftar_id)
    {
        $detail = $this->get_detail_admin($pendaftar_id);
        if (! $detail) {
            return false;
        }

        $this->db->trans_start();

        $this->db->where('id', (int) $pendaftar_id);
        $this->db->update($this->table, [
            'status'  => 'approved',
            'catatan' => null,
        ]);

        // kirim notifikasi kalau ada user_id
        if (! empty($detail['user_id'])) {
            $this->db->insert('notifikasi', [
                'user_id' => (int) $detail['user_id'],
                'judul'   => 'Pendaftaran diterima',
                'pesan'   => 'Selamat! Pendaftaran Anda untuk "' . ($detail['nama_lomba'] ?? '-') . '" sudah disetujui.',
                'is_read' => 0,
            ]);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /** Reject + notifikasi (jika user_id ada) */
    public function reject($pendaftar_id, $catatan)
    {
        $detail = $this->get_detail_admin($pendaftar_id);
        if (! $detail) {
            return false;
        }

        $this->db->trans_start();

        $this->db->where('id', (int) $pendaftar_id);
        $this->db->update($this->table, [
            'status'  => 'rejected',
            'catatan' => $catatan,
        ]);

        if (! empty($detail['user_id'])) {
            $pesan = 'Pendaftaran Anda untuk "' . ($detail['nama_lomba'] ?? '-') . '" ditolak.';
            if (! empty($catatan)) {
                $pesan .= ' Catatan: ' . $catatan;
            }

            $this->db->insert('notifikasi', [
                'user_id' => (int) $detail['user_id'],
                'judul'   => 'Pendaftaran ditolak',
                'pesan'   => $pesan,
                'is_read' => 0,
            ]);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /** Cek sudah daftar berdasarkan user & lomba */
    public function exists_by_user_lomba($user_id, $lomba_id)
    {
        $count = $this->db->from($this->table)
            ->where('user_id', (int) $user_id)
            ->where('lomba_id', (int) $lomba_id)
            ->count_all_results();

        return $count > 0;
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    public function exists_by_name_lomba($nama, $lomba_id)
    {
        $count = $this->db->from($this->table)
            ->where('nama', $nama)
            ->where('lomba_id', (int) $lomba_id)
            ->count_all_results();

        return $count > 0;
    }

    /** Insert pendaftar */
    public function create(array $data)
    {
        // default (karena kolom di tabel kamu varchar)
        if (! isset($data['status'])) {
            $data['status'] = 'pending';
        }

        if (! isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $this->db->insert($this->table, $data);
    }
}
