<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-12 col-xl-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0">Notifikasi</h3>
                    <span class="badge badge-warning ml-2">Belum dibaca: <?php echo (int) $unread; ?></span>
                </div>
                <div>
                    <a href="<?php echo site_url('notifikasi/read_all'); ?>"
                        class="btn btn-sm btn-outline-primary <?php echo $unread ? '' : 'disabled'; ?>">
                        Tandai semua dibaca
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($rows)): ?>
                <div class="p-4 text-center text-muted">Belum ada notifikasi.</div>
                <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($rows as $row): ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <a href="<?php echo site_url('notifikasi/detail/' . (int) $row['id']); ?>">
                                        <strong><?php echo html_escape($row['judul']); ?></strong>
                                    </a>
                                    <?php if ((int) $row['is_read'] === 0): ?>
                                    <span class="badge badge-success ml-2">Baru</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-muted small mb-2">
                                    <?php echo html_escape($row['created_at']); ?>
                                </div>
                                <div style="white-space: normal; word-break: break-word;">
                                    <?php echo nl2br(html_escape($row['pesan'])); ?>
                                </div>
                            </div>
                            <div class="text-right" style="min-width:140px;">
                                <a href="<?php echo site_url('notifikasi/detail/' . (int) $row['id']); ?>"
                                    class="btn btn-sm btn-outline-primary mb-1">
                                    Lihat detail
                                </a>
                                <?php if ((int) $row['is_read'] === 0): ?>
                                <a href="<?php echo site_url('notifikasi/read/' . (int) $row['id']); ?>"
                                    class="btn btn-sm btn-outline-secondary">
                                    Tandai dibaca
                                </a>
                                <?php else: ?>
                                <span class="badge badge-secondary">Sudah dibaca</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
