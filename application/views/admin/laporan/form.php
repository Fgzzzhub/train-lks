<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php if (validation_errors()): ?>
<div class="alert alert-danger"><?php echo validation_errors(); ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Upload Laporan</h3>
            </div>
            <div class="card-body">
                <form action="<?php echo site_url('laporan/create'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Mata Lomba</label>
                        <select name="lomba_id" class="form-control" required>
                            <option value="">Pilih mata lomba...</option>
                            <?php foreach ($lomba_list as $lomba): ?>
                            <option value="<?php echo (int) $lomba->id; ?>"                                   <?php echo set_select('lomba_id', $lomba->id); ?>>
                                <?php echo html_escape($lomba->nama_lomba); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="judul" class="form-control" value="<?php echo set_value('judul'); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Keterangan (opsional)</label>
                        <textarea name="keterangan" class="form-control" rows="4"
                            placeholder="Catatan singkat laporan..."><?php echo set_value('keterangan'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>File Laporan</label>
                        <input type="file" name="file" class="form-control-file"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.csv" required>
                        <small class="text-muted d-block mt-1">Format: PDF/DOC/DOCX/XLS/XLSX/CSV.</small>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="<?php echo site_url('laporan'); ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
