<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Akses Ditolak</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>">
</head>

<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center align-items-center" style="height:100vh">
            <div class="col-md-6 text-center">

                <div class="card shadow">
                    <div class="card-body">

                        <h1 class="display-4 text-danger">403</h1>
                        <h4 class="mb-3">Akses Ditolak</h4>

                        <p class="text-muted">
                            Maaf, Anda tidak memiliki hak akses untuk membuka halaman ini.
                        </p>

                        <div class="mt-4">
                            <a href="<?php echo site_url('logout'); ?>" class="btn btn-danger">
                                Logout
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>