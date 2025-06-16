<?php

include 'koneksi.php';

session_start();

if(isset($_SESSION['username_admin'])) {
    $isLoggedIn = true;
    $namaAdmin = $_SESSION['nama_admin']; // Ambil nama user dari session
  } else if(isset($_SESSION['username_pelanggan'])) {
    $isLoggedIn = true;
    $namaPelanggan = $_SESSION['nama_pelanggan']; // Ambil nama user dari session

  }
  else {
      $isLoggedIn = false;
  }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Bengkel</title>
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
                <a href="index.php" class="nav-item nav-link active">Home</a>
                <a href="layanan.php" class="nav-item nav-link">Layanan Kami</a>
                <?php if($isLoggedIn): ?>
                <?php if(isset($_SESSION['username_admin'])): ?>
                    <a href="admin" class="nav-item nav-link">Dashboard</a>
                    <a href="logout.php" class="nav-item nav-link">Logout</a>
                </nav>
                <?php else: ?>
                    <a href="pelanggan" class="nav-item nav-link">Dashboard</a>
                    <a href="keranjang.php" class="nav-item nav-link">Keranjang</a>
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


    <!-- Carousel Start -->
    <div class="container-fluid p-0 mb-5">
    <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="w-100" src="assets/home/img/bengkel.jpg" alt="Image">
                <div class="carousel-caption">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7 pt-5">
                            <?php if($isLoggedIn): ?>
                                <?php if(isset($_SESSION['username_admin'])): ?>
                                    <h1 class="display-4 text-white mb-4 animated slideInDown mt-n5">Selamat Datang, <?= $_SESSION['nama_admin'] ?></h1>
                                <?php elseif(isset($_SESSION['username_teknisi'])): ?>
                                    <h1 class="display-4 text-white mb-4 animated slideInDown mt-n5">Selamat Datang, <?= $_SESSION['nama_teknisi'] ?></h1>
                                <?php else: ?>
                                    <h1 class="display-4 text-white mb-4 animated slideInDown mt-n5">Selamat Datang, <?= $_SESSION['nama_pelanggan'] ?></h1>
                                <?php endif; ?>
                            <?php endif; ?>
                                <h1 class="display-4 text-white mb-4 animated slideInDown mt-5">Bengkel Motor & Mobil Terpercaya</h1>
                                <p class="fs-5 text-body mb-4 pb-2 mx-sm-5 animated slideInDown">Kami menyediakan layanan service motor dan mobil berkualitas tinggi dengan teknisi berpengalaman dan peralatan modern.</p>
                                <a href="layanan.php" class="btn btn-primary py-3 px-5 animated slideInDown">Pesan Sekarang Juga</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="w-100" src="assets/home/img/bengkel2.webp" alt="Image">
                <div class="carousel-caption">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7 pt-5">
                                <h1 class="display-4 text-white mb-4 animated slideInDown">Layanan Service Kendaraan Berkualitas Tinggi</h1>
                                <p class="fs-5 text-body mb-4 pb-2 mx-sm-5 animated slideInDown">Mekanik ahli kami siap membantu Anda dengan berbagai masalah kendaraan. Kualitas dan kepuasan pelanggan adalah prioritas utama kami.</p>
                                <a href="layanan.php" class="btn btn-primary py-3 px-5 animated slideInDown">Pesan Sekarang Juga</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Sebelumnya</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Selanjutnya</span>
        </button>
    </div>
</div>

    <!-- Carousel End -->



<!-- Service Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <h1 class="display-6 mb-5">Kami Menyediakan Layanan Service Kendaraan Profesional</h1>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="service-item">
                    <img class="img-fluid" src="assets/home/img/bengkel3.webp" alt="Service Mesin">
                    <div class="d-flex align-items-center bg-light">
                        <div class="service-icon flex-shrink-0 bg-primary">
                            <img class="img-fluid" src="assets/home/img/icon/icon-01-light.png" alt="Service Mesin">
                        </div>
                        <a class="h4 mx-4 mb-0" href="">Service Mesin</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="service-item">
                    <img class="img-fluid" src="assets/home/img/bengkel2.webp" alt="Ganti Oli & Filter">
                    <div class="d-flex align-items-center bg-light">
                        <div class="service-icon flex-shrink-0 bg-primary">
                            <img class="img-fluid" src="assets/home/img/icon/icon-02-light.png" alt="Ganti Oli & Filter">
                        </div>
                        <a class="h4 mx-4 mb-0" href="">Ganti Oli & Filter</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="service-item">
                    <img class="img-fluid" src="assets/home/img/bengkel.jpg" alt="Service Rem">
                    <div class="d-flex align-items-center bg-light">
                        <div class="service-icon flex-shrink-0 bg-primary">
                            <img class="img-fluid" src="assets/home/img/icon/icon-03-light.png" alt="Service Rem">
                        </div>
                        <a class="h4 mx-4 mb-0" href="">Service Rem</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="service-item">
                    <img class="img-fluid" src="assets/home/img/bengkel.jpg" alt="Tune Up">
                    <div class="d-flex align-items-center bg-light">
                        <div class="service-icon flex-shrink-0 bg-primary">
                            <img class="img-fluid" src="assets/home/img/icon/icon-04-light.png" alt="Tune Up">
                        </div>
                        <a class="h4 mx-4 mb-0" href="">Tune Up</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="service-item">
                    <img class="img-fluid" src="assets/home/img/bengkel2.webp" alt="Service AC Mobil">
                    <div class="d-flex align-items-center bg-light">
                        <div class="service-icon flex-shrink-0 bg-primary">
                            <img class="img-fluid" src="assets/home/img/icon/icon-05-light.png" alt="Service AC Mobil">
                        </div>
                        <a class="h4 mx-4 mb-0" href="">Service AC Mobil</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="service-item">
                    <img class="img-fluid" src="assets/home/img/bengkel3.webp" alt="Konsultasi Mekanik">
                    <div class="d-flex align-items-center bg-light">
                        <div class="service-icon flex-shrink-0 bg-primary">
                            <img class="img-fluid" src="assets/home/img/icon/icon-06-light.png" alt="Konsultasi Mekanik">
                        </div>
                        <a class="h4 mx-4 mb-0" href="">Konsultasi Mekanik</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Service End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-md-6">
                    <h1 class="text-white mb-4"><img class="img-fluid me-3" src="assets/home/icon/icon-02-light.png" alt="">AutoCare</h1>
                    <span>Bengkel terpercaya dengan pengalaman puluhan tahun dalam melayani service motor dan mobil. Kami berkomitmen memberikan pelayanan terbaik dengan teknisi berpengalaman dan suku cadang berkualitas.</span>
                </div>
                <div class="col-md-6">
                    <h5 class="text-light mb-4">Newsletter</h5>
                    <p>Dapatkan informasi terbaru tentang tips perawatan kendaraan dan promo menarik dari kami.</p>
                    <div class="position-relative">
                        <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="Email Anda">
                        <button type="button" class="btn btn-primary py-2 px-3 position-absolute top-0 end-0 mt-2 me-2">Daftar</button>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-light mb-4">Hubungi Kami</h5>
                    <p><i class="fa fa-map-marker-alt me-3"></i>Jl. Raya Bengkel No. 123, Jakarta</p>
                    <p><i class="fa fa-phone-alt me-3"></i>+62 812 3456 7890</p>
                    <p><i class="fa fa-envelope me-3"></i>info@bengkelautocare.com</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-light mb-4">Layanan Kami</h5>
                    <a class="btn btn-link" href="">Service Mesin</a>
                    <a class="btn btn-link" href="">Ganti Oli</a>
                    <a class="btn btn-link" href="">Service Rem</a>
                    <a class="btn btn-link" href="">Tune Up Berkala</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-light mb-4">Link Cepat</h5>
                    <a class="btn btn-link" href="">Tentang Kami</a>
                    <a class="btn btn-link" href="">Kontak</a>
                    <a class="btn btn-link" href="">Layanan Kami</a>
                    <a class="btn btn-link" href="">Syarat & Ketentuan</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-light mb-4">Ikuti Kami</h5>
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
                        &copy; <a href="#">Bengkel AutoCare</a>, All Right Reserved.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <!--/*** This template is free as long as you keep the footer author's credit link/attribution link/backlink. If you'd like to use the template without the footer author's credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
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