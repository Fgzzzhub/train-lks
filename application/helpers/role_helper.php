<?php defined('BASEPATH') or exit('No direct script access allowed');

function role_label($role_id)
{
    $map = [
        1 => 'Administrator',
        2 => 'Operator',
        3 => 'Koordinator',
        4 => 'Panitia',
        5 => 'Pengguna',
    ];

    return $map[(int) $role_id] ?? 'Tidak diketahui';
}
