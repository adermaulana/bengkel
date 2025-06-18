<?php
include '../koneksi.php';

session_start();

if($_SESSION['status'] != 'login' || !isset($_SESSION['username_pelanggan'])){
    header("location:../");
}

$nik_pelanggan = $_SESSION['nik_pelanggan'];

// Ambil data profil pelanggan yang sedang login
$tampil = mysqli_query($koneksi, "SELECT * FROM pelanggan_221032 WHERE nik_221032 = '$nik_pelanggan'");
$data = mysqli_fetch_array($tampil);
if ($data) {
    $nik = $data['nik_221032'];
    $nama = $data['nama_221032'];
    $username = $data['username_221032'];
    $alamat = $data['alamat_221032'];
    $telepon = $data['telepon_221032'];
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_221032'];
    $username = $_POST['username_221032'];
    $password = $_POST['password_221032'];
    $alamat = $_POST['alamat_221032'];
    $telepon = $_POST['telepon_221032'];
    
    // Hash password dengan MD5 jika tidak kosong
    $hashed_password = !empty($password) ? md5($password) : null;

    // Check if username sudah digunakan oleh pelanggan lain
    $checkUsername = mysqli_query($koneksi, "SELECT * FROM pelanggan_221032 WHERE username_221032 = '$username' AND nik_221032 != '$nik_pelanggan'");
    if (mysqli_num_rows($checkUsername) > 0) {
        echo "<script>
                alert('Username sudah digunakan oleh pelanggan lain!');
                document.location='profil.php';
            </script>";
        exit;
    }

    // Proses Update Data
    if (!empty($password)) {
        $update = mysqli_query(
            $koneksi,
            "UPDATE pelanggan_221032 SET 
            nama_221032 = '$nama',
            username_221032 = '$username',
            password_221032 = '$hashed_password',
            alamat_221032 = '$alamat',
            telepon_221032 = '$telepon'
            WHERE nik_221032 = '$nik_pelanggan'"
        );
    } else {
        $update = mysqli_query(
            $koneksi,
            "UPDATE pelanggan_221032 SET 
            nama_221032 = '$nama',
            username_221032 = '$username',
            alamat_221032 = '$alamat',
            telepon_221032 = '$telepon'
            WHERE nik_221032 = '$nik_pelanggan'"
        );
    }

    if ($update) {
        // Update session data
        $_SESSION['nama_pelanggan'] = $nama;
        $_SESSION['username_pelanggan'] = $username;
        
        echo "<script>
                alert('Update profil berhasil!');
                document.location='profil.php';
            </script>";
    } else {
        echo "<script>
                alert('Update profil gagal!');
                document.location='profil.php';
            </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profil Saya</title>

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
  
  <style>
    .error-message {
        color: red;
        font-size: 0.8rem;
        margin-top: 5px;
        display: none;
    }
  </style>
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
    <a href="index.php" class="brand-link">
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
          <li class="nav-item">
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
          <li class="nav-item menu-open">
                <a href="profil.php" class="nav-link active">
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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Profil</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Profil</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Form Edit Profil</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST">
                <div class="card-body">
                  <div class="form-group">
                    <label for="nik">NIK</label>
                    <input type="text" name="nik_221032" class="form-control"
                        id="nik" value="<?= @$nik ?>"
                        placeholder="NIK" readonly>
                    <small class="text-muted">NIK tidak dapat diubah</small>
                  </div>
                  <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" name="nama_221032" class="form-control"
                        id="nama" value="<?= @$nama ?>"
                        placeholder="Masukkan Nama Lengkap" required>
                    <div id="namaError" class="error-message">Nama harus diisi</div>
                  </div>
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username_221032" class="form-control"
                        id="username" value="<?= @$username ?>"
                        placeholder="Masukkan Username" required>
                    <div id="usernameError" class="error-message">Username harus diisi</div>
                  </div>
                  <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" name="password_221032" class="form-control"
                        id="password" placeholder="Masukkan Password Baru">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    <div id="passwordError" class="error-message">Password harus diisi</div>
                  </div>
                  <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat_221032" class="form-control"
                        id="alamat" placeholder="Masukkan Alamat" required><?= @$alamat ?></textarea>
                    <div id="alamatError" class="error-message">Alamat harus diisi</div>
                  </div>
                  <div class="form-group">
                    <label for="telepon">Telepon</label>
                    <input type="text" name="telepon_221032" class="form-control"
                        id="telepon" value="<?= @$telepon ?>"
                        placeholder="Masukkan Nomor Telepon" required>
                    <div id="teleponError" class="error-message">Telepon harus diisi</div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" id="submitButton" name="simpan" class="btn btn-primary">
                    Update Profil
                  </button>
                  <a href="index.php" class="btn btn-default">Kembali ke Dashboard</a>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->
          
          <!-- right column -->
          <div class="col-md-6">
            <!-- Info box -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Informasi Profil</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Nama Lengkap</span>
                        <span class="info-box-number"><?= $nama ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-success"><i class="fas fa-id-card"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">NIK</span>
                        <span class="info-box-number"><?= $nik ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-warning"><i class="fas fa-at"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Username</span>
                        <span class="info-box-number"><?= $username ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-danger"><i class="fas fa-phone"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Telepon</span>
                        <span class="info-box-number"><?= $telepon ?></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="alert alert-info">
                  <h5><i class="icon fas fa-info"></i> Catatan!</h5>
                  Pastikan data yang Anda masukkan sudah benar sebelum menyimpan perubahan.
                </div>
              </div>
            </div>
          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
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

<script>
// Validasi form
$(document).ready(function() {
    $('#submitButton').click(function(e) {
        let isValid = true;
        
        // Reset error messages
        $('.error-message').hide();
        
        // Validasi nama
        if ($('#nama').val().trim() === '') {
            $('#namaError').show();
            isValid = false;
        }
        
        // Validasi username
        if ($('#username').val().trim() === '') {
            $('#usernameError').show();
            isValid = false;
        }
        
        // Validasi alamat
        if ($('#alamat').val().trim() === '') {
            $('#alamatError').show();
            isValid = false;
        }
        
        // Validasi telepon
        if ($('#telepon').val().trim() === '') {
            $('#teleponError').show();
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>