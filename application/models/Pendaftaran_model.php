<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pendaftaran_model extends CI_Model
{
    private $table = 'pendaftaran';

    private function _base_admin_query($status = '', $q = '')
    {
        $this->db->from($this->table . ' p');
        $this->db->join('users u', 'u.id = p.user_id', 'inner');
        $this->db->join('roles r', 'r.id = u.role_id', 'inner');
        $this->db->join('lomba l', 'l.id = p.lomba_id', 'inner');

        // Select field yang dibutuhkan admin
        $this->db->select([
            'p.id AS pendaftaran_id',
            'p.status',
            'p.catatan',
            'p.created_at',
            'p.updated_at',
            'u.id AS user_id',
            'u.username',
            'r.name AS role',
            'l.id AS lomba_id',
            'l.nama_lomba',
            'l.bidang',
            'l.tanggal',
            'l.lokasi',
        ]);

        // Filter status jika ada
        if (! empty($status)) {
            $this->db->where('p.status', $status); // pending/approved/rejected
        }

        // Search nama/email jika ada
        if (! empty($q)) {
            $this->db->group_start()
                ->like('u.nama', $q)
                ->or_like('u.email', $q)
                ->group_end();
        }
    }

    /**
     * Ambil list pendaftaran untuk admin (with pagination).
     */
    public function get_list_admin($status = '', $q = '', $limit = 10, $offset = 0)
    {
        $this->_base_admin_query($status, $q);
        $this->db->order_by('p.created_at', 'DESC');
        $this->db->limit((int) $limit, (int) $offset);

        return $this->db->get()->result_array();
    }

    /**
     * Hitung total data pendaftaran sesuai filter (untuk pagination).
     */
    public function count_admin($status = '', $q = '')
    {
        $this->_base_admin_query($status, $q);
        return (int) $this->db->count_all_results();
    }

    /**
     * Detail pendaftaran by id (admin).
     */
    public function get_detail_admin($pendaftaran_id)
    {
        $this->_base_admin_query('', ''); // join & select
        $this->db->where('p.id', (int) $pendaftaran_id);

        return $this->db->get()->row_array();
    }

    /**
     * Approve pendaftaran + insert notifikasi.
     * Disarankan dipakai dengan transaksi.
     */
    public function approve($pendaftaran_id)
    {
        $detail = $this->get_detail_admin($pendaftaran_id);
        if (! $detail) {
            return false;
        }

        $this->db->trans_start();

        $this->db->where('id', (int) $pendaftaran_id);
        $this->db->update($this->table, [
            'status'  => 'approved',
            'catatan' => null,
        ]);

        $this->db->insert('notifikasi', [
            'user_id' => (int) $detail['user_id'],
            'judul'   => 'Pendaftaran diterima',
            'pesan'   => 'Selamat! Pendaftaran Anda untuk "' . $detail['nama_lomba'] . '" sudah disetujui.',
            'is_read' => 0,
        ]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Reject pendaftaran + insert notifikasi.
     */
    public function reject($pendaftaran_id, $catatan)
    {
        $detail = $this->get_detail_admin($pendaftaran_id);
        if (! $detail) {
            return false;
        }

        $this->db->trans_start();

        $this->db->where('id', (int) $pendaftaran_id);
        $this->db->update($this->table, [
            'status'  => 'rejected',
            'catatan' => $catatan,
        ]);

        $pesan = 'Pendaftaran Anda untuk "' . $detail['nama_lomba'] . '" ditolak.';
        if (! empty($catatan)) {
            $pesan .= ' Catatan: ' . $catatan;
        }

        $this->db->insert('notifikasi', [
            'user_id' => (int) $detail['user_id'],
            'judul'   => 'Pendaftaran ditolak',
            'pesan'   => $pesan,
            'is_read' => 0,
        ]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function exists_by_user_lomba($user_id, $lomba_id)
    {
        $count = $this->db->from($this->table)
            ->where('user_id', (int) $user_id)
            ->where('lomba_id', (int) $lomba_id)
            ->count_all_results();

        return $count > 0;
    }

    public function create(array $data)
    {
        return $this->db->insert('pendaftar', $data);
    }
}
