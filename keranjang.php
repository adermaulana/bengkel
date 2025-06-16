<?php

include 'koneksi.php';

session_start();

if(isset($_SESSION['username_admin'])) {
    $isLoggedIn = true;
    $namaAdmin = $_SESSION['nama_admin'];
  } else if(isset($_SESSION['username_pelanggan'])) {
    $isLoggedIn = true;
    $namaPelanggan = $_SESSION['nama_pelanggan'];
    $nik_pelanggan = $_SESSION['nik_pelanggan']; // Ambil NIK pelanggan dari session
  }
  else {
      $isLoggedIn = false;
      header("Location: login.php");
      exit();
  }

// Proses hapus item dari keranjang
if(isset($_POST['hapus_item'])) {
    $id_detail = $_POST['id_detail'];
    mysqli_query($koneksi, "DELETE FROM detail_layanan_221032 WHERE id_detaillayanan_221032 = '$id_detail'");
    header("Location: keranjang.php");
    exit();
}

// Proses checkout
if(isset($_POST['checkout'])) {
    $kode_booking = $_POST['kode_booking'];
    
    // Update status booking dari 'Keranjang' ke 'Pending'
    $update_booking = mysqli_query($koneksi, "UPDATE booking_221032 SET status_221032 = 'Pending' WHERE kode_booking_221032 = '$kode_booking'");
    
    if($update_booking) {
        echo "<script>alert('Booking berhasil! Silakan lakukan pembayaran.'); window.location='pelanggan/index.php';</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Keranjang - Bengkel</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="assets/img/laptoplogo.avif" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;600;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="assets/home/lib/animate/animate.min.css" rel="stylesheet">
    <link href="assets/home/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="assets/home/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="assets/home/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5">
        <a href="index.php" class="navbar-brand d-flex align-items-center">
            <h1 class="m-0">Bengkel</h1>
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav mx-auto bg-light pe-4 py-3 py-lg-0">
                <a href="index.php" class="nav-item nav-link">Home</a>
                <a href="layanan.php" class="nav-item nav-link">Layanan Kami</a>
                <?php if($isLoggedIn): ?>
                <?php if(isset($_SESSION['username_admin'])): ?>
                    <a href="admin" class="nav-item nav-link">Dashboard</a>
                <?php else: ?>
                    <a href="pelanggan" class="nav-item nav-link">Dashboard</a>
                    <a href="keranjang.php" class="nav-item nav-link active">Keranjang</a>
                    <a href="logout.php" class="nav-item nav-link">Logout</a>
                <?php endif; ?>
                <?php else: ?>
                    <a href="login.php" class="nav-item nav-link">Login</a>
                <?php endif; ?>
            </div>
            <div class="h-100 d-lg-inline-flex align-items-center d-none">
                <a class="btn btn-square rounded-circle bg-light text-primary me-2" href=""><i class="fab fa-facebook-f"></i></a>
                <a class="btn btn-square rounded-circle bg-light text-primary me-2" href=""><i class="fab fa-twitter"></i></a>
                <a class="btn btn-square rounded-circle bg-light text-primary me-2" href=""><i class="fab fa-linkedin-in"></i></a>
                <a class="btn btn-square rounded-circle bg-light text-primary me-0" href=""><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center py-5">
            <h1 class="display-4 text-white animated slideInDown mb-4">Booking Layanan</h1>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Booking Content Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row">
                <!-- Data Booking Section -->
                <div class="col-lg-8">
                    <div class="bg-light rounded p-4">
                        <h4 class="mb-4">Data Booking</h4>
                        
                        <?php
                        // Cek apakah ada booking dengan status 'Keranjang' untuk pelanggan ini
                        $cek_booking = mysqli_query($koneksi, "SELECT * FROM booking_221032 WHERE nik_221032 = '$nik_pelanggan' AND status_221032 = 'Keranjang' ORDER BY tanggal_booking_221032 DESC LIMIT 1");
                        $booking_keranjang = mysqli_fetch_array($cek_booking);
                        $total_harga = 0;
                        
                        if($booking_keranjang) {
                            $kode_booking = $booking_keranjang['kode_booking_221032'];
                            $keranjang = mysqli_query($koneksi, "SELECT dl.*, l.nama_layanan_221032, l.harga_layanan_221032 FROM detail_layanan_221032 dl JOIN layanan_221032 l ON dl.kode_layanan_221032 = l.kode_layanan_221032 WHERE dl.kode_booking_221032 = '$kode_booking'");
                        }
                        ?>
                        
                        <?php if($booking_keranjang && mysqli_num_rows($keranjang) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Layanan</th>
                                        <th>Harga Layanan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    mysqli_data_seek($keranjang, 0); // Reset pointer
                                    while($data = mysqli_fetch_array($keranjang)): 
                                    $total_harga += $data['harga_layanan_221032'];
                                    ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= $data['nama_layanan_221032'] ?></td>
                                        <td>Rp <?= number_format($data['harga_layanan_221032'], 0, ',', '.') ?></td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="id_detail" value="<?= $data['id_detaillayanan_221032'] ?>">
                                                <button type="submit" name="hapus_item" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus item ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php 
                                    $no++;
                                    endwhile; 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            <a href="layanan.php" class="btn btn-secondary">Lanjut Pilih Layanan</a>
                        </div>
                        
                        <?php else: ?>
                        <div class="text-center py-5">
                            <h5>Keranjang Anda Kosong</h5>
                            <p>Silakan pilih layanan terlebih dahulu</p>
                            <a href="layanan.php" class="btn btn-primary">Pilih Layanan</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Ringkasan Booking Section -->
                <?php if($booking_keranjang && mysqli_num_rows($keranjang) > 0): ?>
                <div class="col-lg-4">
                    <div class="bg-primary text-white rounded p-4">
                        <h4 class="mb-4 text-white">Ringkasan Booking</h4>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Grand Total</span>
                            <span class="fw-bold">Rp <?= number_format($total_harga, 0, ',', '.') ?></span>
                        </div>
                        
                        <hr class="text-white">
                        
                        <form method="POST">
                            <input type="hidden" name="kode_booking" value="<?= $kode_booking ?>">
                            <button type="submit" name="checkout" class="btn btn-light btn-lg w-100" onclick="return confirm('Konfirmasi booking layanan?')">
                                <i class="fas fa-shopping-cart me-2"></i>Bayar
                            </button>
                        </form>
                        
                        <div class="mt-3 text-center">
                            <small>Pastikan data booking sudah benar sebelum melakukan pembayaran</small>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Booking Content End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-md-6">
                    <h1 class="text-white mb-4"><img class="img-fluid me-3" src="assets/home/icon/icon-02-light.png" alt="">Bengkel</h1>
                    <span>Bengkel terpercaya untuk semua kebutuhan perawatan dan perbaikan kendaraan Anda. Kami melayani dengan profesional dan berkualitas tinggi.</span>
                </div>
                <div class="col-md-6">
                    <h5 class="text-light mb-4">Newsletter</h5>
                    <p>Dapatkan informasi terbaru tentang layanan dan promo dari bengkel kami.</p>
                    <div class="position-relative">
                        <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                        <button type="button" class="btn btn-primary py-2 px-3 position-absolute top-0 end-0 mt-2 me-2">SignUp</button>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-light mb-4">Get In Touch</h5>
                    <p><i class="fa fa-map-marker-alt me-3"></i>Jl. Raya Bengkel No. 123, Makassar</p>
                    <p><i class="fa fa-phone-alt me-3"></i>+62 811 1234 5678</p>
                    <p><i class="fa fa-envelope me-3"></i>info@bengkel.com</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-light mb-4">Our Services</h5>
                    <a class="btn btn-link" href="">Service Rutin</a>
                    <a class="btn btn-link" href="">Tambal Ban</a>
                    <a class="btn btn-link" href="">Ganti Oli</a>
                    <a class="btn btn-link" href="">Perbaikan Mesin</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-light mb-4">Quick Links</h5>
                    <a class="btn btn-link" href="">About Us</a>
                    <a class="btn btn-link" href="">Contact Us</a>
                    <a class="btn btn-link" href="">Our Services</a>
                    <a class="btn btn-link" href="">Terms & Condition</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-light mb-4">Follow Us</h5>
                    <div class="d-flex">
                        <a class="btn btn-square rounded-circle me-1" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-square rounded-circle me-1" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square rounded-circle me-1" href=""><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-square rounded-circle me-1" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a href="#">Bengkel Automotive</a>, All Right Reserved.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        Designed By <a href="https://htmlcodex.com">HTML Codex</a>  Distributed by <a href="https://themewagon.com">ThemeWagon</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/home/lib/wow/wow.min.js"></script>
    <script src="assets/home/lib/easing/easing.min.js"></script>
    <script src="assets/home/lib/waypoints/waypoints.min.js"></script>
    <script src="assets/home/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="assets/home/lib/counterup/counterup.min.js"></script>
    <script src="assets/home/lib/parallax/parallax.min.js"></script>

    <!-- Template Javascript -->
    <script src="assets/home/js/main.js"></script>
</body>

</html>