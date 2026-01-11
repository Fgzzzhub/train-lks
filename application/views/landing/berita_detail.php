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
                        <li class="nav-item">
                            <a href="<?php echo site_url('landing'); ?>" class="nav-link">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo site_url('berita/umum'); ?>" class="nav-link active">Berita</a>
                        </li>
                    </ul>
                </div>

                <?php $logged_in = $this->session->userdata('logged_in');
                $role_id             = (int) $this->session->userdata('role_id'); ?>
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item dropdown">
                        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false" class="nav-link dropdown-toggle">
                            <?php echo 'Auth'; ?>
                        </a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                            <?php if (! $logged_in): ?>
                            <li><a href="<?php echo site_url('login'); ?>" class="dropdown-item">Login</a></li>
                            <li><a href="<?php echo site_url('register'); ?>" class="dropdown-item">Register</a></li>
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
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?php echo html_escape($row['judul']); ?></h1>
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
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted small mb-2">
                                <?php echo html_escape($row['nama_lomba'] ?? '-'); ?>
                                <?php if (! empty($row['bidang'])): ?>
                                - <?php echo html_escape($row['bidang']); ?>
                                <?php endif; ?>
                                <?php if (! empty($row['created_at'])): ?>
                                <span class="ml-2">| <?php echo html_escape($row['created_at']); ?></span>
                                <?php endif; ?>
                            </div>

                            <?php if (! empty($row['foto'])): ?>
                            <img src="<?php echo site_url($row['foto']); ?>" alt="Foto berita"
                                class="img-fluid rounded mb-3">
                            <?php endif; ?>

                            <?php if (! empty($row['isi'])): ?>
                            <div class="text-body" style="white-space: pre-line;">
                                <?php echo html_escape($row['isi']); ?>
                            </div>
                            <?php else: ?>
                            <div class="text-muted">Konten belum tersedia.</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <a href="<?php echo site_url('landing'); ?>" class="btn btn-secondary btn-sm">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                Anything you want
            </div>
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights
            reserved.
        </footer>
    </div>

    <script src="<?php echo site_url('assets/adminlte'); ?>/plugins/jquery/jquery.min.js"></script>
    <script src="<?php echo site_url('assets/adminlte'); ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo site_url('assets/adminlte'); ?>/dist/js/adminlte.min.js"></script>
</body>

</html>
