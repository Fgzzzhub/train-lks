<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0">Notifikasi Terbaru</h3>
                    <span class="badge badge-warning ml-2">Belum dibaca:                                                                         <?php echo (int) $notif_unread; ?></span>
                </div>
                <div class="card-tools">
                    <a href="<?php echo site_url('notifikasi'); ?>" class="btn btn-sm btn-outline-primary">Lihat
                        semua</a>
                    <a href="<?php echo site_url('notifikasi/read_all'); ?>"
                        class="btn btn-sm btn-outline-secondary                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <?php echo $notif_unread ? '' : 'disabled'; ?>">
                        Tandai semua dibaca
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($notif_latest)): ?>
                <div class="p-4 text-center text-muted">Belum ada notifikasi.</div>
                <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($notif_latest as $item): ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <strong><?php echo html_escape($item['judul']); ?></strong>
                                    <?php if ((int) $item['is_read'] === 0): ?>
                                    <span class="badge badge-success ml-2">Baru</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-muted small mb-1">
                                    <?php echo html_escape($item['created_at']); ?>
                                </div>
                                <div class="text-muted" style="white-space: normal; word-break: break-word;">
                                    <?php echo nl2br(html_escape($item['pesan'])); ?>
                                </div>
                            </div>
                            <div class="text-right" style="min-width:120px;">
                                <a href="<?php echo site_url('notifikasi/detail/' . (int) $item['id']); ?>"
                                    class="btn btn-sm btn-outline-primary mb-1">Lihat detail</a>
                                <?php if ((int) $item['is_read'] === 0): ?>
                                <a href="<?php echo site_url('notifikasi/read/' . (int) $item['id']); ?>"
                                    class="btn btn-sm btn-outline-secondary">Tandai dibaca</a>
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
    <div class="col-lg-3 col-6">
        <!-- small card -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?php echo $total; ?></h3>

                <p>Pendaftar</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <a href="<?php echo site_url('pendaftar');?>" class="small-box-footer">
                Selengkapnya <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>