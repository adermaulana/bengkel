<?php
include '../koneksi.php';

session_start();

if ($_SESSION['status'] != 'login' || !isset($_SESSION['username_admin'])) {
    header('location:../pelanggan');
}

// Proses filter jika form disubmit
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Buat kondisi WHERE berdasarkan filter untuk digunakan di semua query
$where_condition = "";
$filter_label = "";

switch ($filter) {
    case 'week':
        $where_condition = " WHERE YEARWEEK(b.tanggal_booking_221032, 1) = YEARWEEK(CURDATE(), 1)";
        $filter_label = "Minggu Ini";
        break;
    case 'month':
        $where_condition = " WHERE MONTH(b.tanggal_booking_221032) = MONTH(CURDATE()) 
                           AND YEAR(b.tanggal_booking_221032) = YEAR(CURDATE())";
        $filter_label = "Bulan Ini";
        break;
    case 'year':
        $where_condition = " WHERE YEAR(b.tanggal_booking_221032) = YEAR(CURDATE())";
        $filter_label = "Tahun Ini";
        break;
    case 'custom':
        if (!empty($start_date) && !empty($end_date)) {
            $where_condition = " WHERE b.tanggal_booking_221032 BETWEEN '$start_date' AND '$end_date'";
            $filter_label = "Periode " . date('d/m/Y', strtotime($start_date)) . " - " . date('d/m/Y', strtotime($end_date));
        } else {
            $filter_label = "Semua Data";
        }
        break;
    default:
        $filter_label = "Semua Data";
        break;
}

// Query untuk data booking (sama seperti sebelumnya)
$query = "SELECT b.*, p.nama_221032 
          FROM booking_221032 b
          JOIN pelanggan_221032 p ON b.nik_221032 = p.nik_221032";
$query .= $where_condition;
$query .= " ORDER BY b.tanggal_booking_221032 DESC";

// Query untuk menghitung pemasukan berdasarkan filter yang dipilih
$income_query = "SELECT SUM(l.harga_layanan_221032) as total_income
                FROM detail_layanan_221032 dl
                JOIN layanan_221032 l ON dl.kode_layanan_221032 = l.kode_layanan_221032
                JOIN booking_221032 b ON dl.kode_booking_221032 = b.kode_booking_221032";
$income_query .= $where_condition;
$income_query .= " AND b.status_221032 = 'dikonfirmasi'";

$income_result = mysqli_query($koneksi, $income_query);
$income_data = mysqli_fetch_assoc($income_result);

// Query untuk layanan paling populer berdasarkan filter yang dipilih
$popular_services_query = "SELECT l.kode_layanan_221032, l.nama_layanan_221032, COUNT(*) as total_orders, 
                          SUM(l.harga_layanan_221032) as total_income
                          FROM detail_layanan_221032 dl
                          JOIN layanan_221032 l ON dl.kode_layanan_221032 = l.kode_layanan_221032
                          JOIN booking_221032 b ON dl.kode_booking_221032 = b.kode_booking_221032";
$popular_services_query .= $where_condition;
$popular_services_query .= " AND b.status_221032 = 'dikonfirmasi'
                           GROUP BY l.kode_layanan_221032, l.nama_layanan_221032
                           ORDER BY total_orders DESC
                           LIMIT 5";

$popular_services_result = mysqli_query($koneksi, $popular_services_query);
$popular_services = [];
while ($row = mysqli_fetch_assoc($popular_services_result)) {
    $popular_services[] = $row;
}

function generatePDF($data, $filter, $koneksi, $start_date = null, $end_date = null, $income_data, $popular_services, $filter_label) {
    require_once('../tcpdf/tcpdf.php');
    
    // Buat instance TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set dokumen informasi
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Admin Bengkel');
    $pdf->SetTitle('Laporan Booking Bengkel');
    $pdf->SetSubject('Laporan Booking');
    
    // Set margin
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
    // Add a page
    $pdf->AddPage();
    
    // Set judul laporan
    $title = 'LAPORAN DATA BOOKING BENGKEL';
    
    // Konten HTML untuk PDF
    $html = '
    <h1 style="text-align:center;">' . $title . '</h1>
    <p style="text-align:center;">Periode: ' . $filter_label . '</p>
    <p style="text-align:center;">Dicetak pada: ' . date('d/m/Y H:i:s') . '</p>';
    
    // Tambahkan ringkasan pemasukan berdasarkan filter
    $html .= '
    <h3 style="text-align:center;">Ringkasan Pemasukan - ' . $filter_label . '</h3>
    <table border="1" cellpadding="4">
        <tr>
            <th width="70%"><b>Total Pemasukan (' . $filter_label . ')</b></th>
            <td width="30%">Rp ' . number_format($income_data['total_income'], 0, ',', '.') . '</td>
        </tr>
    </table>';
    
    // Tambahkan layanan populer jika ada
    if (!empty($popular_services)) {
        $html .= '
        <h3 style="text-align:center; margin-top:20px;">5 Layanan Paling Populer - ' . $filter_label . '</h3>
        <table border="1" cellpadding="4">
            <tr>
                <th width="5%"><b>No</b></th>
                <th width="45%"><b>Nama Layanan</b></th>
                <th width="25%"><b>Jumlah Pesanan</b></th>
                <th width="25%"><b>Total Pemasukan</b></th>
            </tr>';
        
        $no = 1;
        foreach ($popular_services as $service) {
            $html .= '
            <tr>
                <td>' . $no++ . '</td>
                <td>' . $service['nama_layanan_221032'] . '</td>
                <td>' . $service['total_orders'] . '</td>
                <td>Rp ' . number_format($service['total_income'], 0, ',', '.') . '</td>
            </tr>';
        }
        
        $html .= '
        </table>';
    }
    
    $html .= '
    <h3 style="text-align:center; margin-top:20px;">Detail Booking</h3>
    <table border="1" cellpadding="4">
        <tr>
            <th width="5%"><b>No</b></th>
            <th width="15%"><b>Kode Booking</b></th>
            <th width="15%"><b>NIK</b></th>
            <th width="20%"><b>Nama Pelanggan</b></th>
            <th width="15%"><b>Tanggal Booking</b></th>
            <th width="15%"><b>Total Harga</b></th>
            <th width="15%"><b>Status</b></th>
        </tr>';
    
    $no = 1;
    $total_keseluruhan = 0;
    foreach ($data as $row) {
        // Calculate total price for each booking in the PDF
        $total_harga = 0;
        $query_layanan = mysqli_query($koneksi, "SELECT dl.*, l.harga_layanan_221032 
                                              FROM detail_layanan_221032 dl
                                              JOIN layanan_221032 l ON dl.kode_layanan_221032 = l.kode_layanan_221032
                                              WHERE dl.kode_booking_221032 = '".$row['kode_booking_221032']."'");
        while($layanan = mysqli_fetch_array($query_layanan)) {
            $total_harga += $layanan['harga_layanan_221032'];
        }
        $total_keseluruhan += $total_harga;
        
        $status = $row['status_221032'];
        
        $html .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . $row['kode_booking_221032'] . '</td>
            <td>' . $row['nik_221032'] . '</td>
            <td>' . $row['nama_221032'] . '</td>
            <td>' . date('d/m/Y', strtotime($row['tanggal_booking_221032'])) . '</td>
            <td>Rp ' . number_format($total_harga, 0, ',', '.') . '</td>
            <td>' . $status . '</td>
        </tr>';
    }
    
    $html .= '
    </table>
    <p style="text-align:right;"><b>Total Keseluruhan: Rp ' . number_format($total_keseluruhan, 0, ',', '.') . '</b></p>
    <p style="text-align:right;">Total Data: ' . ($no-1) . '</p>';
    
    // Output HTML ke PDF
    $pdf->writeHTML($html, true, false, true, false, '');
    
    // Close and output PDF document
    $pdf->Output('laporan_booking_' . date('YmdHis') . '.pdf', 'I');
}

// Update the function call where you generate the PDF
if (isset($_GET['cetak'])) {
    $result = mysqli_query($koneksi, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    generatePDF($data, $filter, $koneksi, $start_date, $end_date, $income_data, $popular_services, $filter_label);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Dashboard</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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

    <link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../assets/img/AdminLTELogo.png" alt="AdminLTELogo" height="60"
                width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>

            </ul>

        </nav>
        <!-- /.navbar -->

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
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="../assets/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?= $_SESSION['nama_admin'] ?> </a>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
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
                        <li class="nav-header">Fitur</li>
                        <li class="nav-item">
                            <a href="bengkel.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Data Bengkel
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="layanan.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Data Layanan
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="booking.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Data Booking
                                </p>
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
                            <h1 class="m-0">Laporan</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Filter Laporan</h3>
                                </div>
                                <div class="card-body">
                                    <form method="get" action="">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Pilih Periode</label>
                                                    <select name="filter" class="form-control" onchange="toggleCustomDate()">
                                                        <option value="all" <?= $filter == 'all' ? 'selected' : '' ?>>Semua Data</option>
                                                        <option value="week" <?= $filter == 'week' ? 'selected' : '' ?>>Minggu Ini</option>
                                                        <option value="month" <?= $filter == 'month' ? 'selected' : '' ?>>Bulan Ini</option>
                                                        <option value="year" <?= $filter == 'year' ? 'selected' : '' ?>>Tahun Ini</option>
                                                        <option value="custom" <?= $filter == 'custom' ? 'selected' : '' ?>>Custom</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3" id="custom-date-start" style="<?= $filter != 'custom' ? 'display:none;' : '' ?>">
                                                <div class="form-group">
                                                    <label>Tanggal Mulai</label>
                                                    <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3" id="custom-date-end" style="<?= $filter != 'custom' ? 'display:none;' : '' ?>">
                                                <div class="form-group">
                                                    <label>Tanggal Akhir</label>
                                                    <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group" style="margin-top: 32px;">
                                                    <button type="submit" class="btn btn-primary">Filter</button>
                                                    <button type="submit" name="cetak" value="1" class="btn btn-success">Cetak PDF</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Pemasukan - <?= $filter_label ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <h4>Total Pemasukan: Rp <?= number_format($income_data['total_income'], 0, ',', '.') ?></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-success">
                                        <div class="card-header">
                                            <h3 class="card-title">Layanan Paling Populer - <?= $filter_label ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <?php if (!empty($popular_services)): ?>
                                                <ul class="list-group">
                                                    <?php foreach ($popular_services as $service): ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?= $service['nama_layanan_221032'] ?>
                                                        <span class="badge badge-primary badge-pill"><?= $service['total_orders'] ?> pesanan</span>
                                                    </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <p>Tidak ada data layanan untuk periode ini.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Booking</th>
                                                <th>NIK</th>
                                                <th>Nama Pelanggan</th>
                                                <th>Tanggal Booking</th>
                                                <th>Total Harga</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            $tampil = mysqli_query($koneksi, $query);
                                            while($data = mysqli_fetch_array($tampil)):
                                                // Calculate total price for each booking
                                                $total_harga = 0;
                                                $query_layanan = mysqli_query($koneksi, "SELECT dl.*, l.harga_layanan_221032 
                                                                                        FROM detail_layanan_221032 dl
                                                                                        JOIN layanan_221032 l ON dl.kode_layanan_221032 = l.kode_layanan_221032
                                                                                        WHERE dl.kode_booking_221032 = '".$data['kode_booking_221032']."'");
                                                while($layanan = mysqli_fetch_array($query_layanan)) {
                                                    $total_harga += $layanan['harga_layanan_221032'];
                                                }
                                            ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $data['kode_booking_221032'] ?></td>
                                                <td><?= $data['nik_221032'] ?></td>
                                                <td><?= $data['nama_221032'] ?></td>
                                                <td><?= date('d-m-Y', strtotime($data['tanggal_booking_221032'])) ?></td>
                                                <td>Rp <?= number_format($total_harga, 0, ',', '.') ?></td>
                                                <?php if ($data['status_221032'] == 'dikonfirmasi'): ?>
                                                <td><span class="badge bg-success"><?= $data['status_221032'] ?></span></td>
                                                <?php else: ?>
                                                <td><span class="badge bg-warning"><?= $data['status_221032'] ?></span></td>
                                                <?php endif; ?>
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
                                                <th>Total Harga</th>
                                                <th>Status</th>
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

    <script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../assets/plugins/jszip/jszip.min.js"></script>
    <script src="../assets/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../assets/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>

 <script>
        function toggleCustomDate() {
            var filter = document.getElementsByName('filter')[0].value;
            if (filter == 'custom') {
                document.getElementById('custom-date-start').style.display = 'block';
                document.getElementById('custom-date-end').style.display = 'block';
            } else {
                document.getElementById('custom-date-start').style.display = 'none';
                document.getElementById('custom-date-end').style.display = 'none';
            }
        }
    </script>

</body>

</html>
