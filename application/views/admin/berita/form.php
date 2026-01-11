<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php $is_edit = ! empty($row); ?>

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
                <h3 class="card-title mb-0"><?php echo $is_edit ? 'Edit Berita' : 'Tambah Berita'; ?></h3>
            </div>
            <div class="card-body">
                <form action="<?php echo site_url($is_edit ? 'berita/edit/' . (int) $row['id'] : 'berita/create'); ?>"
                    method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Mata Lomba</label>
                        <select name="lomba_id" class="form-control" required>
                            <option value="">Pilih mata lomba...</option>
                            <?php foreach ($lomba_list as $lomba): ?>
                            <option value="<?php echo (int) $lomba->id; ?>"                                   <?php echo set_select('lomba_id', $lomba->id, $is_edit && (int) $row['lomba_id'] === (int) $lomba->id); ?>>
                                <?php echo html_escape($lomba->nama_lomba); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="judul" class="form-control"
                            value="<?php echo set_value('judul', $row['judul'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Isi</label>
                        <textarea name="isi" class="form-control" rows="5"
                            placeholder="Isi berita..."><?php echo set_value('isi', $row['isi'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" name="foto" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
                        <?php if ($is_edit && ! empty($row['foto'])): ?>
                        <div class="mt-2">
                            <img src="<?php echo site_url($row['foto']); ?>" alt="Foto" style="max-height:120px;">
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="<?php echo site_url('berita'); ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
