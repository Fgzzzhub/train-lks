<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Import Peserta</h3>
            </div>
            <div class="card-body">
                <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                <?php endif; ?>

                <form action="<?php echo site_url('admin/pendaftaran/import'); ?>" method="post"
                    enctype="multipart/form-data">
                    <div class="form-group">
                        <label>File Excel (.xlsx / .xls / .csv)</label>
                        <input type="file" name="file" class="form-control-file" required>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Import
                        </button>
                        <a href="<?php echo site_url('admin/pendaftaran'); ?>" class="btn btn-secondary">Kembali</a>
                        <a href="<?php echo site_url('admin/pendaftaran/template'); ?>"
                            class="btn btn-outline-secondary">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Petunjuk</h3>
            </div>
            <div class="card-body">
                <ol class="pl-3 mb-0">
                    <li>Gunakan template agar urutan kolom sesuai.</li>
                    <li>Kolom wajib: nama, jenis_kelamin, tanggal_lahir, sekolah, npsn, kabupaten, lomba_id.</li>
                    <li>Jenis kelamin gunakan L atau P.</li>
                    <li>Status bisa diisi pending/approved/rejected (opsional).</li>
                </ol>
            </div>
        </div>
    </div>
</div>
