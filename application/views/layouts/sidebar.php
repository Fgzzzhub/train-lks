 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
     <!-- Sidebar -->
     <div class="sidebar">
         <?php
             $segment1     = $this->uri->segment(1);
             $segment2     = $this->uri->segment(2);
             $isDashboard  = $segment1 === 'admin' && (empty($segment2) || $segment2 === 'dashboard');
             $isNotifikasi = $segment1 === 'notifikasi';
             $isBerita     = $segment1 === 'berita';
             $isLaporan    = $segment1 === 'laporan';
             $isUsers      = $segment1 === 'users';
             $isPendaftar  = $segment1 === 'pendaftar';
             $isBackupDb   = $segment1 === 'backupdb';
             $notif_unread = isset($notif_unread) ? (int) $notif_unread : 0;
         ?>
         <!-- Sidebar user panel (optional) -->
         <div class="user-panel pb-3 mb-3 d-flex">
             <div class="image">
                 <img src="<?php echo site_url('/assets/adminlte/'); ?>dist/img/user2-160x160.jpg"
                     class="img-circle elevation-2" alt="User Image">
             </div>
             <div class="info">
                 <a href="#" class="d-block"><?php echo role_label($this->session->userdata('role_id')); ?>
                 </a>
             </div>
         </div>
         <!-- Sidebar Menu -->
         <nav class="mt-2">
             <ul class="nav nav-pills nav-sidebar flex-column">
                 <li class="nav-item">
                     <a href="<?php echo site_url('admin'); ?>"
                         class="nav-link                                                                                                                                                                 <?php echo $isDashboard ? 'active' : ''; ?>">
                         <i class="nav-icon fas fa-tachometer-alt"></i>
                         <p>
                             Dashboard
                         </p>
                     </a>
                 </li>
                 <?php if ($this->session->userdata('role_id') == 1): ?>
                 <li class="nav-item">
                     <a href="<?php echo site_url('notifikasi'); ?>"
                         class="nav-link                                                                                                                                                                 <?php echo $isNotifikasi ? 'active' : ''; ?>">
                         <i class="nav-icon fas fa-bell"></i>
                         <p>
                             Notifikasi
                             <?php if ($notif_unread > 0): ?>
                             <span class="badge badge-warning right"><?php echo $notif_unread; ?></span>
                             <?php endif; ?>
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="<?php echo site_url('berita'); ?>"
                         class="nav-link                                                                                                                                                                 <?php echo $isBerita ? 'active' : ''; ?>">
                         <i class="nav-icon fas fa-newspaper"></i>
                         <p>
                             Berita
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="<?php echo site_url('laporan'); ?>"
                         class="nav-link                                                                                                                                                                 <?php echo $isLaporan ? 'active' : ''; ?>">
                         <i class="nav-icon fas fa-file-alt"></i>
                         <p>
                             Laporan
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="<?php echo site_url('users'); ?>"
                         class="nav-link                                                                                                                                                                 <?php echo $isUsers ? 'active' : ''; ?>">
                         <i class="nav-icon fas fa-users"></i>
                         <p>
                             Users
                         </p>
                     </a>
                 </li>
                 <?php endif; ?>
                 <li class="nav-item">
                     <a href="<?php echo site_url('pendaftar'); ?>"
                         class="nav-link                                                                                                                                                                 <?php echo $isPendaftar ? 'active' : ''; ?>">
                         <i class="nav-icon fas fa-users"></i>
                         <p>
                             Pendaftar
                         </p>
                     </a>
                 </li>

                 <?php if ($this->session->userdata('role_id') == 1): ?>
                 <li class="nav-item">
                     <a href="<?php echo site_url('backupdb'); ?>"
                         class="nav-link                                                                                                                                                                 <?php echo $isBackupDb ? 'active' : ''; ?>">
                         <i class="nav-icon fas fa-database"></i>
                         <p>
                             Backup DB
                         </p>
                     </a>
                 </li>
                 <?php endif; ?>
                 <li class="nav-item">
                     <a href="<?php echo site_url('logout'); ?>" class="nav-link">
                         <i class="nav-icon fas fa-sign-out-alt"></i>
                         <p>
                             Logout
                         </p>
                     </a>
                 </li>
             </ul>
         </nav>
         <!-- /.sidebar-menu -->
     </div>
     <!-- /.sidebar -->
 </aside>