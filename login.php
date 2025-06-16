<?php
    include 'koneksi.php';
    session_start();

    if(isset($_SESSION['status']) == 'login'){
        header("location:admin");
    }

    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = md5($_POST['password']); // Mengamankan password dengan md5
    
        // Cek login untuk admin
        $loginAdmin = mysqli_query($koneksi, "SELECT * FROM admin_221032 WHERE username_221032='$username' AND password_221032='$password'");
        $cekAdmin = mysqli_num_rows($loginAdmin);
    
        // Cek login untuk pelanggan
        $loginPelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan_221032 WHERE username_221032='$username' AND password_221032='$password'");
        $cekPelanggan = mysqli_num_rows($loginPelanggan);

        if ($cekAdmin > 0) {
            $admin_data = mysqli_fetch_assoc($loginAdmin);
            $_SESSION['nik_admin'] = $admin_data['nik_221032'];
            $_SESSION['nama_admin'] = $admin_data['nama_221032'];
            $_SESSION['username_admin'] = $username;
            $_SESSION['status'] = "login";
            $_SESSION['role'] = "admin";
            header('location:admin');
            exit;
    
        } else if ($cekPelanggan > 0) {
            $pelanggan_data = mysqli_fetch_assoc($loginPelanggan);
            $_SESSION['nik_pelanggan'] = $pelanggan_data['nik_221032'];
            $_SESSION['nama_pelanggan'] = $pelanggan_data['nama_221032'];
            $_SESSION['username_pelanggan'] = $username;
            $_SESSION['status'] = "login";
            $_SESSION['role'] = "pelanggan";
            header('location:pelanggan');
            exit;
    
        } else {
            echo "<script>
                alert('Login Gagal, Periksa Username dan Password Anda!');
                document.location='login.php';
            </script>";
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="login.php" class="h1"><b>Login</b></a>
    </div>
    <div class="card-body">
    <form method="post">
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="username" placeholder="Username" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span> <!-- Changed from envelope to user icon -->
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-4 ">
                <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
            </div>
        </div>
    </form>
      <p class="mb-0">Belum punya akun?
        <a href="registrasi.php" class="text-center">Registrasi</a>
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
