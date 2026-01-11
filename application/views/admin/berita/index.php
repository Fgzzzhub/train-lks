<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Daftar Berita</h3>
                <a href="<?php echo site_url('berita/create'); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Berita
                </a>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th style="width:60px;">No</th>
                            <th>Judul</th>
                            <th>Mata Lomba</th>
                            <th>Foto</th>
                            <th>Created</th>
                            <th style="width:180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="6" class="text-center p-4">Belum ada data.</td>
                        </tr>
                        <?php else: ?>
                        <?php $no = 1; ?>
                        <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo html_escape($row['judul']); ?></td>
                            <td>
                                <div><strong><?php echo html_escape($row['nama_lomba'] ?? '-'); ?></strong></div>
                                <small class="text-muted"><?php echo html_escape($row['bidang'] ?? '-'); ?></small>
                            </td>
                            <td>
                                <?php if (! empty($row['foto'])): ?>
                                <img src="<?php echo site_url($row['foto']); ?>" alt="Foto" style="height:40px;">
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><small><?php echo html_escape($row['created_at'] ?? '-'); ?></small></td>
                            <td>
                                <a href="<?php echo site_url('berita/edit/' . (int) $row['id']); ?>"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?php echo site_url('berita/delete/' . (int) $row['id']); ?>"
                                    class="btn btn-sm btn-danger" onclick="return confirm('Hapus berita ini?');">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
