<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Form Daftar</h3>
    </div>
    <!-- /.card-header -->
    <?php if ($this->session->flashdata('error')): ?>
    <p class="text-sm text-danger my-0"><?php echo $this->session->flashdata('error') ?></p>
    <?php endif; ?>
    <div class="card-body">
        <form method="post" action="<?php echo site_url('/pendaftaran/daftar?lomba_id=' . (int) $lomba->id); ?>">
            <h3 class="my-2">Data Diri</h3>
            <div class="row">
                <div class="col-sm-12">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" placeholder="Sujiwo Tejo" name="nama">
                    </div>
                </div>
            </div>

            <?php $lomba_id = $this->input->get('lomba_id'); ?>
            <input type="hidden" name="lomba_id" value="<?php echo $lomba_id;?>">

            <div class="row">
                <div class="col-sm-12">
                    <!-- select -->
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select class="form-control" name="jenis_kelamin">
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input type="date" class="form-control" data-target="#reservationdate" name="tanggal_lahir">
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Mata Lomba</label>
                        <select class="form-control">
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
            </div>  -->

            <h3 class="my-2">Data Sekolah</h3>

            <div class="row">
                <div class="col-sm-6">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Sekolah Asal</label>
                        <input type="text" class="form-control" placeholder="SMK N 1 Brebes" name="sekolah">
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- text input -->
                    <div class="form-group">
                        <label>NPSN</label>
                        <input type="text" class="form-control" placeholder="1287" name="npsn">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Kabupaten</label>
                        <input type="text" class="form-control" placeholder="Brebes" name="kabupaten">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary float-right">Kirim</button>

        </form>
    </div>
    <!-- /.card-body -->
</div>