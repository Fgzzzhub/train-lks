<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row mb-3">
    <div class="col-md-7">
        <h5 class="mb-1">Daftar Pendaftar</h5>
        <small class="text-muted">Total:                                         <?php echo (int) $total; ?></small>
    </div>
</div>

<!-- Filter & Search -->
<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title mb-0">Filter</h3>
            </div>
            <div class="card-body">
                <form method="get" action="<?php echo site_url('admin/pendaftaran'); ?>">
                    <div class="form-row">
                        <div class="col-md-3 mb-2">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value=""                                                 <?php echo($status === '' ? 'selected' : ''); ?>>Semua</option>
                                <option value="pending"                                                        <?php echo($status === 'pending' ? 'selected' : ''); ?>>Pending
                                </option>
                                <option value="approved"                                                         <?php echo($status === 'approved' ? 'selected' : ''); ?>>
                                    Approved</option>
                                <option value="rejected"                                                         <?php echo($status === 'rejected' ? 'selected' : ''); ?>>
                                    Rejected</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Cari (Nama/Email)</label>
                            <input type="text" name="q" value="<?php echo html_escape($q); ?>" class="form-control"
                                placeholder="Ketik nama atau email...">
                        </div>

                        <div class="col-md-3 mb-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-search"></i> Terapkan
                            </button>
                            <a href="<?php echo site_url('admin/pendaftaran'); ?>" class="btn btn-secondary">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">List Pendaftar</h3>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th style="width:60px;">No</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Lomba</th>
                            <th>Status</th>
                            <th>Daftar</th>
                            <th style="width:220px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="8" class="text-center p-4">
                                Data tidak ditemukan.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php
                            $page  = (int) ($this->input->get('page') ?: 1);
                            $limit = 10;
                            $no    = ($page > 1) ? (($page - 1) * $limit) + 1 : 1;
                        ?>
                        <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo html_escape($r['username']); ?></td>
                            <td>
                                <span class="badge badge-info"><?php echo html_escape($r['role']); ?></span>
                            </td>
                            <td>
                                <div><strong><?php echo html_escape($r['nama_lomba']); ?></strong></div>
                                <small class="text-muted">
                                    <?php echo html_escape($r['bidang']); ?>
                                    <?php echo ! empty($r['tanggal']) ? ' - ' . html_escape($r['tanggal']) : ''; ?>
                                </small>
                            </td>
                            <td>
                                <?php
                                    $badge = 'secondary';
                                    if ($r['status'] === 'pending') {
                                        $badge = 'warning';
                                    }

                                    if ($r['status'] === 'approved') {
                                        $badge = 'success';
                                    }

                                    if ($r['status'] === 'rejected') {
                                        $badge = 'danger';
                                    }
                                ?>
                                <span
                                    class="badge badge-<?php echo $badge; ?>"><?php echo strtoupper(html_escape($r['status'])); ?></span>
                                <?php if (! empty($r['catatan']) && $r['status'] === 'rejected'): ?>
                                <div><small class="text-muted">Catatan:
                                        <?php echo html_escape($r['catatan']); ?></small></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small><?php echo html_escape($r['created_at']); ?></small>
                            </td>
                            <td>
                                <a href="<?php echo site_url('admin/pendaftaran/detail/' . $r['pendaftaran_id']); ?>"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>

                                <?php if ($r['status'] === 'pending'): ?>
                                <a href="<?php echo site_url('admin/pendaftaran/approve/' . $r['pendaftaran_id']); ?>"
                                    class="btn btn-sm btn-success"
                                    onclick="return confirm('Approve pendaftaran ini?');">
                                    <i class="fas fa-check"></i> Approve
                                </a>

                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                    data-target="#modalReject" data-id="<?php echo (int) $r['pendaftaran_id']; ?>"
                                    data-nama="<?php echo html_escape($r['username']); ?>"
                                    data-lomba="<?php echo html_escape($r['nama_lomba']); ?>">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (! empty($pagination)): ?>
            <div class="card-footer clearfix">
                <div class="float-right">
                    <?php echo $pagination; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="modalReject" tabindex="-1" role="dialog" aria-labelledby="modalRejectLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="" id="formReject">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRejectLabel">Reject Pendaftaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p class="mb-2">
                        <strong id="rejectNama"></strong><br>
                        <small class="text-muted" id="rejectLomba"></small>
                    </p>

                    <div class="form-group">
                        <label>Catatan (opsional tapi disarankan)</label>
                        <textarea name="catatan" class="form-control" rows="3"
                            placeholder="Contoh: Berkas belum lengkap / data tidak sesuai..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Pastikan binding modal dieksekusi setelah jQuery/Bootstrap dimuat dari layout footer
document.addEventListener('DOMContentLoaded', function() {
    var $modal = $('#modalReject');

    $modal.on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nama = button.data('nama');
        var lomba = button.data('lomba');

        $('#rejectNama').text(nama);
        $('#rejectLomba').text('Lomba: ' + lomba);

        // set action ke endpoint reject/{id}
        $('#formReject').attr('action', '<?php echo site_url('admin/pendaftaran/reject/'); ?>' + id);
    });
});
</script>