<?php
include '../koneksi.php';

session_start();

if($_SESSION['status'] != 'login' || !isset($_SESSION['username_pelanggan'])){
    header("location:../");
}

// Ambil kode booking dari parameter URL
$kode_booking = $_GET['id'] ?? '';

// Query data booking
$query_booking = mysqli_query($koneksi, "SELECT b.*, p.* 
                                        FROM booking_221032 b
                                        JOIN pelanggan_221032 p ON b.nik_221032 = p.nik_221032
                                        WHERE b.kode_booking_221032 = '$kode_booking'");
$data_booking = mysqli_fetch_assoc($query_booking);

// Query layanan yang dipilih
$query_layanan = mysqli_query($koneksi, "SELECT dl.*, l.nama_layanan_221032, l.harga_layanan_221032
                                        FROM detail_layanan_221032 dl
                                        JOIN layanan_221032 l ON dl.kode_layanan_221032 = l.kode_layanan_221032
                                        WHERE dl.kode_booking_221032 = '$kode_booking'");

// Query data pembayaran jika ada
$query_pembayaran = mysqli_query($koneksi, "SELECT * FROM pembayaran_221032 
                                           WHERE kode_booking_221032 = '$kode_booking'");
$data_pembayaran = mysqli_fetch_assoc($query_pembayaran);

// Hitung total harga
$total_harga = 0;
while($layanan = mysqli_fetch_assoc($query_layanan)) {
    $total_harga += $layanan['harga_layanan_221032'];
}
// Reset pointer hasil query
mysqli_data_seek($query_layanan, 0);
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
            <h1 class="m-0">Detail Service #<?= $kode_booking ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item"><a href="service.php">Riwayat Service</a></li>
              <li class="breadcrumb-item active">Detail</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            <!-- Informasi Booking -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Informasi Booking</h3>
              </div>
              <div class="card-body">
                <dl class="row">
                  <dt class="col-sm-4">Kode Booking</dt>
                  <dd class="col-sm-8"><?= $data_booking['kode_booking_221032'] ?></dd>
                  
                  <dt class="col-sm-4">Tanggal Booking</dt>
                  <dd class="col-sm-8"><?= date('d-m-Y', strtotime($data_booking['tanggal_booking_221032'])) ?></dd>
                  
                  <dt class="col-sm-4">Status</dt>
                  <dd class="col-sm-8">
                    <span class="badge 
                      <?= $data_booking['status_221032'] == 'dikonfirmasi' ? 'bg-success' : 'bg-warning' ?>">
                      <?= $data_booking['status_221032'] ?>
                    </span>
                  </dd>
                  
                  <dt class="col-sm-4">Nama Pelanggan</dt>
                  <dd class="col-sm-8"><?= $data_booking['nama_221032'] ?></dd>
                  
                  <dt class="col-sm-4">NIK</dt>
                  <dd class="col-sm-8"><?= $data_booking['nik_221032'] ?></dd>
                  
                  <dt class="col-sm-4">Alamat</dt>
                  <dd class="col-sm-8"><?= $data_booking['alamat_221032'] ?></dd>
                  
                  <dt class="col-sm-4">Telepon</dt>
                  <dd class="col-sm-8"><?= $data_booking['telepon_221032'] ?></dd>
                </dl>
              </div>
            </div>
            
            <!-- Informasi Pembayaran -->
            <?php if($data_pembayaran): ?>
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Informasi Pembayaran</h3>
              </div>
              <div class="card-body">
                <dl class="row">
                  <dt class="col-sm-4">Metode Pembayaran</dt>
                  <dd class="col-sm-8"><?= $data_pembayaran['metode_pembayaran_221032'] ?></dd>
                  
                  <dt class="col-sm-4">Status Pembayaran</dt>
                  <dd class="col-sm-8">
                    <span class="badge 
                      <?= $data_pembayaran['status_pembayaran_221032'] == 'diterima' ? 'bg-success' : 
                        ($data_pembayaran['status_pembayaran_221032'] == 'ditolak' ? 'bg-danger' : 'bg-warning') ?>">
                      <?= $data_pembayaran['status_pembayaran_221032'] ?>
                    </span>
                  </dd>
                  
                  <dt class="col-sm-4">Bukti Pembayaran</dt>
                  <dd class="col-sm-8">
                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#buktiModal">
                      Lihat Bukti
                    </button>
                  </dd>
                </dl>
              </div>
            </div>
            <?php endif; ?>
          </div>
          
          <div class="col-md-6">
            <!-- Daftar Layanan -->
            <div class="card card-secondary">
              <div class="card-header">
                <h3 class="card-title">Layanan Dipilih</h3>
              </div>
              <div class="card-body p-0">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Layanan</th>
                      <th>Harga</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while($layanan = mysqli_fetch_assoc($query_layanan)): ?>
                    <tr>
                      <td><?= $layanan['nama_layanan_221032'] ?></td>
                      <td>Rp <?= number_format($layanan['harga_layanan_221032'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endwhile; ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Total</th>
                      <th>Rp <?= number_format($total_harga, 0, ',', '.') ?></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            
            <!-- Tombol Aksi -->
            <div class="card">
              <div class="card-body text-center">
                <?php if(!$data_pembayaran && $data_booking['status_221032'] == 'dikonfirmasi'): ?>
                  <a href="upload_bukti.php?id=<?= $kode_booking ?>" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                  </a>
                <?php endif; ?>
                <a href="service.php" class="btn btn-default">
                  <i class="fas fa-arrow-left"></i> Kembali
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
  <!-- Modal Bukti Pembayaran -->
  <?php if($data_pembayaran): ?>
  <div class="modal fade" id="buktiModal" tabindex="-1" role="dialog" aria-labelledby="buktiModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="buktiModalLabel">Bukti Pembayaran</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <img src="<?= $data_pembayaran['bukti_pembayaran_221032'] ?>" class="img-fluid" alt="Bukti Pembayaran">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>


    <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div>
  </footer>
  </div>

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