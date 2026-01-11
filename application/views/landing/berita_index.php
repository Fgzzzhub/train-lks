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
                            <a href="<?php echo site_url('landing'); ?>" class="nav-link">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo site_url('berita/umum'); ?>" class="nav-link active">Berita</a>
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
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Berita</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?php echo site_url('landing'); ?>">Home</a></li>
                                <li class="breadcrumb-item active">Berita</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container">
                    <?php
                        if (! function_exists('berita_ringkas')) {
                            function berita_ringkas($text, $limit = 120)
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
                    <div class="row">
                        <?php if (empty($rows)): ?>
                        <div class="col-12">
                            <div class="alert alert-info mb-0">Belum ada berita.</div>
                        </div>
                        <?php else: ?>
                        <?php foreach ($rows as $item): ?>
                        <?php $ringkas = berita_ringkas($item['isi'] ?? '', 120); ?>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <?php if (! empty($item['foto'])): ?>
                                <img src="<?php echo site_url($item['foto']); ?>" alt="Foto berita" class="card-img-top"
                                    style="height:180px;object-fit:cover;">
                                <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                    style="height:180px;">
                                    <i class="far fa-image text-muted"></i>
                                </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <div class="text-muted small mb-2">
                                        <?php echo html_escape($item['nama_lomba'] ?? '-'); ?>
                                        <?php if (! empty($item['bidang'])): ?>
                                        -<?php echo html_escape($item['bidang']); ?>
                                        <?php endif; ?>
                                    </div>
                                    <h5 class="card-title mb-2"><?php echo html_escape($item['judul']); ?></h5>
                                    <?php if ($ringkas !== ''): ?>
                                    <p class="card-text text-muted"><?php echo html_escape($ringkas); ?></p>
                                    <?php else: ?>
                                    <p class="card-text text-muted">Ringkasan belum tersedia.</p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <?php echo html_escape($item['created_at'] ?? '-'); ?>
                                    </small>
                                    <a href="<?php echo site_url('berita/detail/' . (int) $item['id']); ?>"
                                        class="text-sm">Baca Selengkapnya</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy;                                     <?php echo date('Y'); ?>.</strong> All rights reserved.
        </footer>
    </div>

    <script src="<?php echo site_url('assets/adminlte'); ?>/plugins/jquery/jquery.min.js"></script>
    <script src="<?php echo site_url('assets/adminlte'); ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo site_url('assets/adminlte'); ?>/dist/js/adminlte.min.js"></script>
</body>

</html>