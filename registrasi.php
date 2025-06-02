<?php
    include 'koneksi.php';

    session_start();

    if(isset($_SESSION['status']) == 'login'){
        header("location:admin");
    }

    if (isset($_POST['registrasi'])) {

        $username = $_POST['username'];
        $telepon = $_POST['telepon'];
        $password = md5($_POST['password']); // Mengamankan password dengan md5
        $username = $_POST['username'];
        $nik = $_POST['nik']; // Added NIK field

        // Cek jika username sudah ada
        $checkUsername = mysqli_query($koneksi, "SELECT * FROM pelanggan_221032 WHERE username_221032='$username'");
        if (mysqli_num_rows($checkUsername) > 0) {
            echo "<script>
                    alert('Username sudah digunakan, pilih Username lain.');
                    document.location='registrasi.php';
                </script>";
            exit;
        }

        // Cek jika NIK sudah ada
        $checkNik = mysqli_query($koneksi, "SELECT * FROM pelanggan_221032 WHERE nik_221032='$nik'");
        if (mysqli_num_rows($checkNik) > 0) {
            echo "<script>
                    alert('NIK sudah terdaftar.');
                    document.location='registrasi.php';
                </script>";
            exit;
        }

        // Jika username dan NIK belum terdaftar, lanjutkan dengan registrasi
        $simpan = mysqli_query($koneksi, "INSERT INTO pelanggan_221032 
                                        (nik_221032, nama_221032, username_221032, password_221032, alamat_221032, telepon_221032) 
                                        VALUES 
                                        ('$nik', '$_POST[nama]', '$username', '$password', '$_POST[alamat]', '$_POST[telepon]')");

        if ($simpan) {
            echo "<script>
                    alert('Berhasil Registrasi!');
                    document.location='index.php';
                </script>";
        } else {
            echo "<script>
                    alert('Gagal! Silakan coba lagi.');
                    document.location='registrasi.php';
                </script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registrasi</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/css/adminlte.min.css">

    <style>
        .error-message {
            color: red;
            font-size: 0.8rem;
            margin-top: 5px;
            display: none;
        }
    </style>

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="registrasi.php" class="h1"><b>Registrasi</b></a>
    </div>
    <div class="card-body">
    <form method="post">
        <div id="nikError" class="error-message">NIK harus diisi</div>
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="nik" name="nik" placeholder="NIK" required>
            <div class="input-group-append">
                <div class="input-group-text">
                </div>
            </div>
        </div>
        <div id="namaError" class="error-message">Nama harus diisi</div>
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" required>
            <div class="input-group-append">
                <div class="input-group-text">
                </div>
            </div>
        </div>
        <div id="usernameError" class="error-message">Username harus diisi</div>
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
            <div class="input-group-append">
                <div class="input-group-text">
                </div>
            </div>
        </div>
        <div id="teleponError" class="error-message">Telepon harus diisi</div>
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="telepon" name="telepon" placeholder="Telepon" required>
            <div class="input-group-append">
                <div class="input-group-text">
                </div>
            </div>
        </div>
        <div id="alamatError" class="error-message">Alamat harus diisi</div>
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" required>
            <div class="input-group-append">
                <div class="input-group-text">
                </div>
            </div>
        </div>
        <div id="passwordError" class="error-message">Password harus diisi</div>
        <div class="input-group mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-4 ">
                <button type="submit" name="registrasi" class="btn btn-primary btn-block">Registrasi</button>
            </div>
        </div>
    </form>
      <p class="mb-0">Sudah punya akun?
        <a href="index.php" class="text-center">Login</a>
      </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/js/adminlte.min.js"></script>


</body>
</html>
