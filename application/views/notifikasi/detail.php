<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-12 col-lg-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Detail Notifikasi</h3>
                <a href="<?php echo site_url('notifikasi'); ?>" class="btn btn-sm btn-secondary">Kembali</a>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Judul</dt>
                    <dd class="col-sm-9"><?php echo html_escape($row['judul']); ?></dd>

                    <dt class="col-sm-3">Waktu</dt>
                    <dd class="col-sm-9 text-muted"><?php echo html_escape($row['created_at']); ?></dd>

                    <dt class="col-sm-3">Status</dt>
                    <dd class="col-sm-9">
                        <?php if ((int) $row['is_read'] === 1): ?>
                        <span class="badge badge-secondary">Sudah dibaca</span>
                        <?php else: ?>
                        <span class="badge badge-success">Baru</span>
                        <?php endif; ?>
                    </dd>

                    <dt class="col-sm-3">Pesan</dt>
                    <dd class="col-sm-9" style="white-space: pre-wrap; word-break: break-word;">
                        <?php echo html_escape($row['pesan']); ?>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
