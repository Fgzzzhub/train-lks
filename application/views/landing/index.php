<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo site_url('assets/adminlte'); ?>/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo site_url('assets/adminlte'); ?>/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="<?php echo site_url('landing'); ?>" class="navbar-brand">
                    <img src="<?php echo site_url('assets/adminlte'); ?>/dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
                        class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light"><?php echo $title; ?></span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse"
                    data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <?php $logged_in = $this->session->userdata('logged_in');
                        $role_id                                 = (int) $this->session->userdata('role_id'); ?>
                        <li class="nav-item">
                            <a href="<?php echo site_url('landing'); ?>" class="nav-link active">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo site_url('berita/umum'); ?>" class="nav-link">Berita</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" class="nav-link dropdown-toggle">
                                <?php echo 'Auth'; ?>
                            </a>
                            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                <?php if (! $logged_in): ?>
                                <li><a href="<?php echo site_url('login'); ?>" class="dropdown-item">Login</a></li>
                                <li><a href="<?php echo site_url('register'); ?>" class="dropdown-item">Register</a>
                                </li>
                                <?php else: ?>
                                <?php if (in_array($role_id, [1, 2, 3], true)): ?>
                                <li><a href="<?php echo site_url('admin'); ?>" class="dropdown-item">Dashboard</a></li>
                                <?php endif; ?>
                                <li><a href="<?php echo site_url('logout'); ?>"
                                        class="dropdown-item text-danger">Logout</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    </ul>

                </div>

                <?php if ($logged_in && in_array($role_id, [4, 5], true)): ?>
                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <!-- Notifications Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="far fa-bell"></i>
                            <?php if (! empty($notif_unread)): ?>
                            <span class="badge badge-warning navbar-badge"><?php echo (int) $notif_unread; ?></span>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <span class="dropdown-header">Notifikasi</span>
                            <?php if (empty($notif_latest)): ?>
                            <div class="dropdown-divider"></div>
                            <span class="dropdown-item text-muted small">Belum ada notifikasi.</span>
                            <?php else: ?>
                            <?php foreach ($notif_latest as $n): ?>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo site_url('notifikasi/detail/' . (int) $n['id']); ?>"
                                class="dropdown-item">
                                <i class="fas fa-bell mr-2"></i>
                                <?php echo html_escape($n['judul']); ?>
                                <span class="float-right text-muted text-sm">
                                    <?php echo html_escape($n['created_at']); ?>
                                </span>
                                <div class="text-muted small mt-1" style="white-space: normal; word-break: break-word;">
                                    <?php echo nl2br(html_escape($n['pesan'])); ?>
                                </div>
                            </a>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo site_url('notifikasi'); ?>" class="dropdown-item dropdown-footer">Lihat
                                semua</a>
                        </div>
                    </li>
                </ul>
                <?php endif; ?>
            </div>
        </nav>
        <!-- /.navbar -->


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Beranda</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?php echo site_url('landing'); ?>">Home</a></li>
                                <li class="breadcrumb-item active">Beranda</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container">
                    <?php if ($this->session->flashdata('error')): ?>
                    <p class="text-sm text-danger my-0"><?php echo $this->session->flashdata('error') ?></p>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('success')): ?>
                    <p class="alert-success alert my-2"><?php echo $this->session->flashdata('success') ?></p>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Mata Lomba</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-0">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Nama</th>
                                                <th>Bidang</th>
                                                <th>Lokasi</th>
                                                <th style="width: 40px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($matalomba)): ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted p-3">
                                                    Mata lomba belum tersedia.
                                                </td>
                                            </tr>
                                            <?php else: ?>
                                            <?php
                                                $no = ($page > 1) ? (($page - 1) * $limit) + 1 : 1;
                                            ?>
                                            <?php foreach ($matalomba as $m): ?>
                                            <tr>
                                                <td><?php echo $no++; ?></td>
                                                <td><?php echo $m->nama_lomba; ?></td>
                                                <td><?php echo $m->bidang; ?></td>
                                                <td><?php echo $m->lokasi; ?></td>
                                                <td>
                                                    <?php if ($logged_in && in_array($role_id, [4, 5], true)): ?>
                                                    <a href="<?php echo site_url('pendaftaran/daftar') . '?lomba_id=' . (int) $m->id; ?>"
                                                        class="btn btn-sm btn-success">Daftar</a>
                                                    <?php elseif (! $logged_in): ?>
                                                    <span class="text-muted small">Login untuk daftar</span>
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
                                <!-- /.card-body -->
                                <?php if (! empty($pagination)): ?>
                                <div class="card-footer clearfix">
                                    <div class="float-right">
                                        <?php echo $pagination; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- /.col-md-6 -->
                        <div class="col-lg-6">
                            <?php
                                if (! function_exists('berita_ringkas')) {
                                    function berita_ringkas($text, $limit = 60)
                                    {
                                        $text = trim(strip_tags((string) $text));
                                        if ($text === '') {
                                            return '';
                                        }

                                        if (function_exists('mb_strlen') && function_exists('mb_substr')) {
                                            if (mb_strlen($text) <= $limit) {
                                                return $text;
                                            }

                                            return rtrim(mb_substr($text, 0, $limit)) . '...';
                                        }

                                        if (strlen($text) <= $limit) {
                                            return $text;
                                        }

                                        return rtrim(substr($text, 0, $limit)) . '...';
                                    }
                                }
                            ?>
                            <?php if ($show_berita): ?>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Berita Terbaru</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($berita)): ?>
                                    <div class="text-muted">Belum ada berita.</div>
                                    <?php else: ?>
                                    <div class="row">
                                        <?php foreach ($berita as $item): ?>
                                        <?php $ringkas = berita_ringkas($item['isi'] ?? '', 60); ?>
                                        <div class="col-12 col-md-6 mb-3">
                                            <div class="card h-100 mb-0">
                                                <?php if (! empty($item['foto'])): ?>
                                                <img src="<?php echo site_url($item['foto']); ?>" alt="Foto berita"
                                                    class="card-img-top" style="height:160px;object-fit:cover;">
                                                <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                    style="height:160px;">
                                                    <i class="far fa-image text-muted"></i>
                                                </div>
                                                <?php endif; ?>
                                                <div class="card-body">
                                                    <div class="text-muted small mb-1">
                                                        <?php echo html_escape($item['nama_lomba'] ?? '-'); ?>
                                                        <?php if (! empty($item['bidang'])): ?>
                                                        -<?php echo html_escape($item['bidang']); ?>
                                                        <?php endif; ?>
                                                    </div>
                                                    <h6 class="card-title mb-2">
                                                        <?php echo html_escape($item['judul']); ?>
                                                    </h6>
                                                    <?php if ($ringkas !== ''): ?>
                                                    <p class="card-text text-muted mb-0">
                                                        <?php echo html_escape($ringkas); ?>
                                                    </p>
                                                    <?php else: ?>
                                                    <p class="card-text text-muted mb-0">Ringkasan belum tersedia.</p>
                                                    <?php endif; ?>
                                                    <a href="<?php echo site_url('berita/detail/' . (int) $item['id']); ?>"
                                                        class="d-inline-block mt-2 text-sm">Baca selengkapnya</a>
                                                </div>
                                                <?php if (! empty($item['created_at'])): ?>
                                                <div class="card-footer text-muted small">
                                                    <?php echo html_escape($item['created_at']); ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- Default to the left -->
            <strong>Copyright &copy;                                     <?php echo date('Y'); ?>.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="<?php echo site_url('assets/adminlte'); ?>/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo site_url('assets/adminlte'); ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo site_url('assets/adminlte'); ?>/dist/js/adminlte.min.js"></script>
</body>

</html>
