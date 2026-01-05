<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row mb-3">
    <div class="col-sm-6">
        <h5 class="mb-1">Detail Pendaftaran</h5>
        <small class="text-muted">ID:                                      <?php echo (int) $row['pendaftaran_id']; ?></small>
    </div>
    <div class="col-sm-6 text-sm-right">
        <a href="<?php echo site_url('admin/pendaftaran'); ?>" class="btn btn-secondary mt-2 mt-sm-0">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h5>Data Pendaftar</h5>
                <table class="table table-bordered">
                    <tr>
                        <th style="width:200px;">Nama</th>
                        <td><?php echo html_escape($row['username']); ?></td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td><?php echo html_escape($row['role']); ?></td>
                    </tr>
                </table>

                <h5 class="mt-4">Data Lomba</h5>
                <table class="table table-bordered">
                    <tr>
                        <th style="width:200px;">Nama Lomba</th>
                        <td><?php echo html_escape($row['nama_lomba']); ?></td>
                    </tr>
                    <tr>
                        <th>Bidang</th>
                        <td><?php echo html_escape($row['bidang']); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td><?php echo html_escape($row['tanggal']); ?></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td><?php echo html_escape($row['lokasi']); ?></td>
                    </tr>
                </table>

                <h5 class="mt-4">Status</h5>
                <table class="table table-bordered">
                    <tr>
                        <th style="width:200px;">Status</th>
                        <td><?php echo html_escape($row['status']); ?></td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td><?php echo html_escape($row['catatan']); ?></td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td><?php echo html_escape($row['created_at']); ?></td>
                    </tr>
                    <tr>
                        <th>Updated</th>
                        <td><?php echo html_escape($row['updated_at']); ?></td>
                    </tr>
                </table>

                <?php if ($row['status'] === 'pending'): ?>
                <div class="mt-3">
                    <a href="<?php echo site_url('admin/pendaftaran/approve/' . $row['pendaftaran_id']); ?>"
                        class="btn btn-success" onclick="return confirm('Approve pendaftaran ini?');">
                        <i class="fas fa-check"></i> Approve
                    </a>

                    <form method="post"
                        action="<?php echo site_url('admin/pendaftaran/reject/' . $row['pendaftaran_id']); ?>"
                        class="d-inline">
                        <input type="hidden" name="catatan" value="Ditolak oleh admin (lihat detail).">
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Reject pendaftaran ini?');">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </form>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>