<?php
include '../koneksi.php';
session_start();

if($_SESSION['status'] != 'login' || !isset($_SESSION['username_pelanggan'])){
    header("location:../");
}

// Ambil NIK pelanggan dari session
$nik_pelanggan = $_SESSION['nik_pelanggan'];

// Function untuk mendapatkan data laporan
function getLaporanData($koneksi, $nik_pelanggan, $filter_type, $start_date = null, $end_date = null) {
    $where_clause = "WHERE b.nik_221032 = '$nik_pelanggan' AND pb.status_pembayaran_221032 = 'diterima'";
    
    if ($filter_type == 'custom' && $start_date && $end_date) {
        $where_clause .= " AND DATE(b.tanggal_booking_221032) BETWEEN '$start_date' AND '$end_date'";
    } elseif ($filter_type == 'minggu') {
        $where_clause .= " AND WEEK(b.tanggal_booking_221032) = WEEK(NOW()) AND YEAR(b.tanggal_booking_221032) = YEAR(NOW())";
    } elseif ($filter_type == 'bulan') {
        $where_clause .= " AND MONTH(b.tanggal_booking_221032) = MONTH(NOW()) AND YEAR(b.tanggal_booking_221032) = YEAR(NOW())";
    } elseif ($filter_type == 'tahun') {
        $where_clause .= " AND YEAR(b.tanggal_booking_221032) = YEAR(NOW())";
    }
    
    $query = "SELECT 
                b.kode_booking_221032,
                b.tanggal_booking_221032,
                p.nama_221032 as nama_pelanggan,
                pb.metode_pembayaran_221032,
                pb.status_pembayaran_221032,
                SUM(l.harga_layanan_221032) as total_biaya
              FROM booking_221032 b
              JOIN pelanggan_221032 p ON b.nik_221032 = p.nik_221032
              JOIN pembayaran_221032 pb ON b.kode_booking_221032 = pb.kode_booking_221032
              JOIN detail_layanan_221032 dl ON b.kode_booking_221032 = dl.kode_booking_221032
              JOIN layanan_221032 l ON dl.kode_layanan_221032 = l.kode_layanan_221032
              $where_clause
              GROUP BY b.kode_booking_221032
              ORDER BY b.tanggal_booking_221032 DESC";
    
    return mysqli_query($koneksi, $query);
}

// Proses filter
$filter_type = isset($_GET['filter']) ? $_GET['filter'] : 'bulan';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Ambil data laporan
$result = getLaporanData($koneksi, $nik_pelanggan, $filter_type, $start_date, $end_date);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Pengeluaran</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/css/adminlte.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <!-- DatePicker -->
    <link rel="stylesheet" href="../assets/plugins/daterangepicker/daterangepicker.css">
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
        <a href="index.php" class="brand-link">
            <img src="../assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Pelanggan</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="../assets/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?= $_SESSION['nama_pelanggan'] ?></a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../layanan.php" class="nav-link">
                            <i class="nav-icon fas fa-columns"></i>
                            <p>Pilih Layanan</p>
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
                    <li class="nav-item menu-open">
                        <a href="laporan.php" class="nav-link active">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Laporan</p>
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
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Laporan Pengeluaran</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Filter Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Filter Laporan</h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Jenis Filter</label>
                                        <select name="filter" class="form-control" id="filterType" onchange="toggleDateInputs()">
                                            <option value="minggu" <?= $filter_type == 'minggu' ? 'selected' : '' ?>>Minggu Ini</option>
                                            <option value="bulan" <?= $filter_type == 'bulan' ? 'selected' : '' ?>>Bulan Ini</option>
                                            <option value="tahun" <?= $filter_type == 'tahun' ? 'selected' : '' ?>>Tahun Ini</option>
                                            <option value="custom" <?= $filter_type == 'custom' ? 'selected' : '' ?>>Custom</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" id="startDateDiv" style="display: <?= $filter_type == 'custom' ? 'block' : 'none' ?>">
                                    <div class="form-group">
                                        <label>Tanggal Mulai</label>
                                        <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>">
                                    </div>
                                </div>
                                <div class="col-md-3" id="endDateDiv" style="display: <?= $filter_type == 'custom' ? 'block' : 'none' ?>">
                                    <div class="form-group">
                                        <label>Tanggal Selesai</label>
                                        <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i> Filter
                                            </button>
                                            <a href="cetak_laporan.php?filter=<?= $filter_type ?>&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>" target="_blank" class="btn btn-success">
                                                <i class="fas fa-print"></i> Cetak PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Data Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Pengeluaran</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        $total_pengeluaran = 0;
                        $data_laporan = [];
                        
                        // Reset pointer dan ambil data
                        mysqli_data_seek($result, 0);
                        while($row = mysqli_fetch_assoc($result)) {
                            $data_laporan[] = $row;
                            $total_pengeluaran += $row['total_biaya'];
                        }
                        ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-chart-line"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Pengeluaran</span>
                                        <span class="info-box-number">Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-calendar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Transaksi</span>
                                        <span class="info-box-number"><?= count($data_laporan) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-calculator"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Rata-rata per Transaksi</span>
                                        <span class="info-box-number">Rp <?= count($data_laporan) > 0 ? number_format($total_pengeluaran / count($data_laporan), 0, ',', '.') : 0 ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table id="laporanTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Booking</th>
                                    <th>Tanggal</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Total Biaya</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach($data_laporan as $data): 
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $data['kode_booking_221032'] ?></td>
                                    <td><?= date('d-m-Y', strtotime($data['tanggal_booking_221032'])) ?></td>
                                    <td><?= ucfirst($data['metode_pembayaran_221032']) ?></td>
                                    <td>Rp <?= number_format($data['total_biaya'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge bg-success"><?= ucfirst($data['status_pembayaran_221032']) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total Pengeluaran:</th>
                                    <th>Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
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
<!-- DataTables -->
<script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="../assets/js/adminlte.js"></script>

<script>
function toggleDateInputs() {
    const filterType = document.getElementById('filterType').value;
    const startDateDiv = document.getElementById('startDateDiv');
    const endDateDiv = document.getElementById('endDateDiv');
    
    if (filterType === 'custom') {
        startDateDiv.style.display = 'block';
        endDateDiv.style.display = 'block';
    } else {
        startDateDiv.style.display = 'none';
        endDateDiv.style.display = 'none';
    }
}

$(function () {
    $("#laporanTable").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
        }
    }).buttons().container().appendTo('#laporanTable_wrapper .col-md-6:eq(0)');
});
</script>
</body>
</html>