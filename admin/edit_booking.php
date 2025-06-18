<?php
include '../koneksi.php';

session_start();

if ($_SESSION['status'] != 'login') {
    session_unset();
    session_destroy();
    header('location:../');
}

// Get booking ID from URL
$kode_booking = $_GET['id'] ?? '';

if (empty($kode_booking)) {
    echo "<script>
        alert('Kode booking tidak valid!');
        document.location='booking.php';
    </script>";
    exit;
}

// Get booking data
$booking_query = mysqli_query($koneksi, "SELECT b.*, p.nama_221032 
                                       FROM booking_221032 b
                                       JOIN pelanggan_221032 p ON b.nik_221032 = p.nik_221032
                                       WHERE b.kode_booking_221032 = '$kode_booking'");

if (mysqli_num_rows($booking_query) == 0) {
    echo "<script>
        alert('Booking tidak ditemukan!');
        document.location='booking.php';
    </script>";
    exit;
}

$booking_data = mysqli_fetch_array($booking_query);

// Get existing services in this booking
$existing_services = mysqli_query($koneksi, "SELECT dl.*, l.nama_layanan_221032, l.harga_layanan_221032
                                           FROM detail_layanan_221032 dl
                                           JOIN layanan_221032 l ON dl.kode_layanan_221032 = l.kode_layanan_221032
                                           WHERE dl.kode_booking_221032 = '$kode_booking'");

// Get all available services
$all_services = mysqli_query($koneksi, "SELECT * FROM layanan_221032");

// Add new service to booking
if (isset($_POST['add_service'])) {
    $kode_layanan = $_POST['kode_layanan'];
    
    // Check if service already exists in this booking
    $check_existing = mysqli_query($koneksi, "SELECT * FROM detail_layanan_221032 
                                            WHERE kode_booking_221032 = '$kode_booking' 
                                            AND kode_layanan_221032 = '$kode_layanan'");
    
    if (mysqli_num_rows($check_existing) > 0) {
        echo "<script>alert('Layanan sudah ada dalam booking ini!');</script>";
    } else {
        // Insert new service
        $insert_service = mysqli_query($koneksi, "INSERT INTO detail_layanan_221032 
                                                (kode_booking_221032, kode_layanan_221032) 
                                                VALUES 
                                                ('$kode_booking', '$kode_layanan')");
        
        if ($insert_service) {
            echo "<script>
                alert('Layanan berhasil ditambahkan!');
                document.location='edit_booking.php?id=$kode_booking';
            </script>";
        } else {
            echo "<script>alert('Gagal menambahkan layanan!');</script>";
        }
    }
}

// Remove service from booking
if (isset($_GET['remove_service'])) {
    $kode_layanan = $_GET['remove_service'];
    
    // Check if this is the only service (prevent removing all services)
    $count_services = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM detail_layanan_221032 
                                            WHERE kode_booking_221032 = '$kode_booking'");
    $service_count = mysqli_fetch_array($count_services)['total'];
    
    if ($service_count <= 1) {
        echo "<script>alert('Tidak dapat menghapus layanan ini karena harus ada minimal 1 layanan dalam booking!');</script>";
    } else {
        $delete_service = mysqli_query($koneksi, "DELETE FROM detail_layanan_221032 
                                                WHERE kode_booking_221032 = '$kode_booking' 
                                                AND kode_layanan_221032 = '$kode_layanan'");
        
        if ($delete_service) {
            echo "<script>
                alert('Layanan berhasil dihapus!');
                document.location='edit_booking.php?id=$kode_booking';
            </script>";
        } else {
            echo "<script>alert('Gagal menghapus layanan!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Edit Booking</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="../assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                    style="opacity: .8">
                <span class="brand-text font-weight-light">Admin</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="../assets/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?= $_SESSION['nama_admin'] ?></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-header">Fitur</li>
                        <li class="nav-item">
                            <a href="bengkel.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Bengkel</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="layanan.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Layanan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="booking.php" class="nav-link active">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Booking</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pembayaran.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Pembayaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pelanggan.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Pelanggan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="laporan.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Laporan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="hapusSession.php" class="nav-link">
                                <i class="nav-icon fas fa-columns"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Edit Booking - Tambah Layanan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="booking.php">Data Booking</a></li>
                                <li class="breadcrumb-item active">Edit Booking</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Booking Info -->
                        <div class="col-12">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Informasi Booking</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Kode Booking:</strong><br>
                                            <?= $booking_data['kode_booking_221032'] ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Nama Pelanggan:</strong><br>
                                            <?= $booking_data['nama_221032'] ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Tanggal Booking:</strong><br>
                                            <?= date('d/m/Y H:i', strtotime($booking_data['tanggal_booking_221032'])) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Status:</strong><br>
                                            <span class="badge <?= $booking_data['status_221032'] == 'dikonfirmasi' ? 'bg-success' : 'bg-warning' ?>">
                                                <?= $booking_data['status_221032'] ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add New Service -->
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Tambah Layanan Baru</h3>
                                </div>
                                <form method="POST">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Pilih Layanan</label>
                                            <select name="kode_layanan" class="form-control" required>
                                                <option value="">Pilih Layanan</option>
                                                <?php while($service = mysqli_fetch_array($all_services)): ?>
                                                    <option value="<?= $service['kode_layanan_221032'] ?>">
                                                        <?= $service['nama_layanan_221032'] ?> - Rp<?= number_format($service['harga_layanan_221032'], 0, ',', '.') ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <button type="submit" name="add_service" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Layanan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Current Services -->
                        <div class="col-md-6">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Layanan Saat Ini</h3>
                                </div>
                                <div class="card-body">
                                    <?php if (mysqli_num_rows($existing_services) > 0): ?>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Layanan</th>
                                                    <th>Harga</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $no = 1;
                                                $total = 0;
                                                mysqli_data_seek($existing_services, 0);
                                                while($service = mysqli_fetch_array($existing_services)): 
                                                    $total += $service['harga_layanan_221032'];
                                                ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $service['nama_layanan_221032'] ?></td>
                                                    <td>Rp<?= number_format($service['harga_layanan_221032'], 0, ',', '.') ?></td>
                                                    <td>
                                                        <a href="?id=<?= $kode_booking ?>&remove_service=<?= $service['kode_layanan_221032'] ?>" 
                                                           class="btn btn-danger btn-sm"
                                                           onclick="return confirm('Yakin ingin menghapus layanan ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2">Total Harga</th>
                                                    <th colspan="2">Rp<?= number_format($total, 0, ',', '.') ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    <?php else: ?>
                                        <div class="alert alert-info">Belum ada layanan dalam booking ini</div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer">
                                    <a href="booking.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali ke Data Booking
                                    </a>
                                    <a href="detail_service.php?id=<?= $kode_booking ?>" class="btn btn-info">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
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
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/js/adminlte.js"></script>
</body>

</html>