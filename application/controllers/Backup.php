<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Backup extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->dbutil();
        $this->load->helper('download');
        $this->require_login();
        $this->require_role([1]);
    }

    public function backup_db()
    {
        $prefs = [
            'format'     => 'zip',
            'filename'   => 'backup.sql',
            'add_drop'   => true,
            'add_insert' => true,
            'newline'    => '\n',
        ];

        $backup = $this->dbutil->backup($prefs);

        $namafile = 'backup-db-' . date('Y-m-d H:i:s') . '.zip';
        force_download($namafile, $backup);
    }
}
