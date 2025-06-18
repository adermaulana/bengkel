<?php

include '../koneksi.php';

session_start();

if($_SESSION['status'] != 'login' || !isset($_SESSION['username_pelanggan'])){

  header("location:../");

}

  $nik_pelanggan = $_SESSION['nik_pelanggan'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../assets/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../assets/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../assets/plugins/summernote/summernote-bs4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../assets/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>

    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="../assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Pelanggan</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../assets/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?= $_SESSION['nama_pelanggan'] ?> </a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="index.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../layanan.php" class="nav-link">
              <i class="nav-icon fas fa-columns"></i>
              <p>
                Pilih Layanan
              </p>
            </a>
          </li>
          <li class="nav-item">
                <a href="service.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Riwayat Service</p>
                </a>
          </li>
          <li class="nav-item">
                <a href="pembayaran.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pembayaran</p>
                </a>
          </li>
          <li class="nav-item">
                <a href="profil.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Profil</p>
                </a>
          </li>
          <li class="nav-item">
                <a href="laporan.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan</p>
                </a>
          </li>
          <li class="nav-item">
            <a href="hapusSession.php" class="nav-link">
              <i class="nav-icon fas fa-columns"></i>
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

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Pembayaran</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="card-body">
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                    <?= $_SESSION['error'] ?>
                                </div>
                                <?php unset($_SESSION['error']); ?>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                                    <?= $_SESSION['success'] ?>
                                </div>
                                <?php unset($_SESSION['success']); ?>
                            <?php endif; ?>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Booking</th>
                                        <th>NIK</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Tanggal Booking</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $nik_pelanggan = $_SESSION['nik_pelanggan'];
                                    $tampil = mysqli_query($koneksi, "SELECT b.*, p.nama_221032 
                                                                    FROM booking_221032 b
                                                                    JOIN pelanggan_221032 p ON b.nik_221032 = p.nik_221032
                                                                    WHERE b.nik_221032 = '$nik_pelanggan'
                                                                    ORDER BY b.tanggal_booking_221032 DESC");
                                    while($data = mysqli_fetch_array($tampil)):
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $data['kode_booking_221032'] ?></td>
                                        <td><?= $data['nik_221032'] ?></td>
                                        <td><?= $data['nama_221032'] ?></td>
                                        <td><?= date('d-m-Y', strtotime($data['tanggal_booking_221032'])) ?></td>
                                        <?php if ($data['status_221032'] == 'dikonfirmasi'): ?>
                                        <td><span class="badge bg-success"><?= $data['status_221032'] ?></span></td>
                                        <?php else: ?>
                                        <td><span class="badge bg-warning"><?= $data['status_221032'] ?></span></td>
                                        <?php endif; ?>
                                        <td>
                                            <?php if ($data['status_221032'] == 'Pending' || $data['status_221032'] == 'pending'): ?>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadModal<?= $data['kode_booking_221032'] ?>">
                                                Upload Bukti
                                            </button>
                                            <!-- Modal Upload Bukti Pembayaran -->
                                            <div class="modal fade" id="uploadModal<?= $data['kode_booking_221032'] ?>" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="uploadModalLabel">Upload Bukti Pembayaran</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="proses_upload_bukti.php" method="post" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="kode_booking" value="<?= $data['kode_booking_221032'] ?>">
                                                                
                                                                <div class="form-group">
                                                                    <label>Metode Pembayaran</label>
                                                                    <select name="metode_pembayaran" class="form-control" required>
                                                                        <option value="">Pilih Metode</option>
                                                                        <option value="Transfer Bank">Transfer Bank</option>
                                                                        <option value="E-Wallet">E-Wallet</option>
                                                                        <option value="Tunai">Tunai</option>
                                                                    </select>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label>Bukti Pembayaran</label>
                                                                    <input type="file" name="bukti_pembayaran" class="form-control" required accept="image/*">
                                                                    <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-primary">Upload</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php else: ?>
                                            <span class="badge bg-info">Sudah Upload</span>
                                                <?php 
                                                    // Cek apakah sudah ada data pembayaran untuk booking ini
                                                    $cek_pembayaran = mysqli_query($koneksi, "SELECT * FROM pembayaran_221032 WHERE kode_booking_221032 = '".$data['kode_booking_221032']."'");
                                                    $data_pembayaran = mysqli_fetch_assoc($cek_pembayaran);
                                                    
                                                    if ($data_pembayaran): ?>
                                                        <!-- Jika sudah upload bukti -->
                                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#lihatBuktiModal<?= $data['kode_booking_221032'] ?>">
                                                            Lihat Bukti
                                                        </button>
                                                        
                                                        <!-- Modal Lihat Bukti Pembayaran -->
                                                        <div class="modal fade" id="lihatBuktiModal<?= $data['kode_booking_221032'] ?>" tabindex="-1" role="dialog" aria-labelledby="lihatBuktiModalLabel">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="lihatBuktiModalLabel">Bukti Pembayaran #<?= $data['kode_booking_221032'] ?></h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        <img src="<?= $data_pembayaran['bukti_pembayaran_221032'] ?>" class="img-fluid" alt="Bukti Pembayaran">
                                                                        <div class="mt-3">
                                                                            <p><strong>Metode Pembayaran:</strong> <?= $data_pembayaran['metode_pembayaran_221032'] ?></p>
                                                                            <p><strong>Status:</strong> 
                                                                                <span class="badge 
                                                                                    <?= $data_pembayaran['status_pembayaran_221032'] == 'diterima' ? 'bg-success' : 
                                                                                    ($data_pembayaran['status_pembayaran_221032'] == 'ditolak' ? 'bg-danger' : 'bg-warning') ?>">
                                                                                    <?= $data_pembayaran['status_pembayaran_221032'] ?>
                                                                                </span>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Booking</th>
                                        <th>NIK</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Tanggal Booking</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../assets/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="../assets/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../assets/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../assets/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../assets/plugins/moment/moment.min.js"></script>
<script src="../assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../assets/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../assets/js/adminlte.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../assets/js/pages/dashboard.js"></script>
</body>
</html>
