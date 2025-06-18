<?php
require_once('../tcpdf/tcpdf.php');
include '../koneksi.php';
session_start();

if($_SESSION['status'] != 'login' || !isset($_SESSION['username_pelanggan'])){
    header("location:../");
    exit;
}

// Ambil NIK pelanggan dari session
$nik_pelanggan = $_SESSION['nik_pelanggan'];
$nama_pelanggan = $_SESSION['nama_pelanggan'];

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

// Ambil parameter filter
$filter_type = isset($_GET['filter']) ? $_GET['filter'] : 'bulan';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Tentukan judul periode
$periode = '';
switch($filter_type) {
    case 'minggu':
        $periode = 'Minggu Ini';
        break;
    case 'bulan':
        $periode = 'Bulan Ini';
        break;
    case 'tahun':
        $periode = 'Tahun Ini';
        break;
    case 'custom':
        if($start_date && $end_date) {
            $periode = date('d-m-Y', strtotime($start_date)) . ' s/d ' . date('d-m-Y', strtotime($end_date));
        } else {
            $periode = 'Periode Custom';
        }
        break;
}

// Ambil data laporan
$result = getLaporanData($koneksi, $nik_pelanggan, $filter_type, $start_date, $end_date);

// Hitung total
$total_pengeluaran = 0;
$data_laporan = [];
while($row = mysqli_fetch_assoc($result)) {
    $data_laporan[] = $row;
    $total_pengeluaran += $row['total_biaya'];
}

// Buat PDF
class MYPDF extends TCPDF {
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        // $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, 'LAPORAN PENGELUARAN SERVICE', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln();
        $this->SetFont('helvetica', '', 12);
        $this->Cell(0, 10, 'Bengkel Service Kendaraan', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Halaman '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// Create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistem Bengkel');
$pdf->SetTitle('Laporan Pengeluaran');
$pdf->SetSubject('Laporan Pengeluaran Service');

// Set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 10);

// Info pelanggan dan periode
$html = '<br>
<table cellpadding="2" cellspacing="0" border="0">
    <tr>
        <td width="120"><strong>Nama Pelanggan</strong></td>
        <td width="10">:</td>
        <td>' . $nama_pelanggan . '</td>
    </tr>
    <tr>
        <td><strong>NIK</strong></td>
        <td>:</td>
        <td>' . $nik_pelanggan . '</td>
    </tr>
    <tr>
        <td><strong>Periode</strong></td>
        <td>:</td>
        <td>' . $periode . '</td>
    </tr>
    <tr>
        <td><strong>Tanggal Cetak</strong></td>
        <td>:</td>
        <td>' . date('d-m-Y H:i:s') . '</td>
    </tr>
</table>
<br>';

$pdf->writeHTML($html, true, false, true, false, '');

// Ringkasan
$html_ringkasan = '
<h3>RINGKASAN PENGELUARAN</h3>
<table cellpadding="5" cellspacing="0" border="1" style="border-collapse: collapse;">
    <tr style="background-color: #f0f0f0;">
        <td width="200"><strong>Total Transaksi</strong></td>
        <td width="150">' . count($data_laporan) . ' transaksi</td>
    </tr>
    <tr>
        <td><strong>Total Pengeluaran</strong></td>
        <td><strong>Rp ' . number_format($total_pengeluaran, 0, ',', '.') . '</strong></td>
    </tr>
    <tr style="background-color: #f0f0f0;">
        <td><strong>Rata-rata per Transaksi</strong></td>
        <td>Rp ' . (count($data_laporan) > 0 ? number_format($total_pengeluaran / count($data_laporan), 0, ',', '.') : '0') . '</td>
    </tr>
</table>
<br><br>';

$pdf->writeHTML($html_ringkasan, true, false, true, false, '');

// Detail transaksi
$html_detail = '<h3>DETAIL TRANSAKSI</h3>';

if(count($data_laporan) > 0) {
    $html_detail .= '
    <table cellpadding="5" cellspacing="0" border="1" style="border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f0f0f0;">
                <th width="50"><strong>No</strong></th>
                <th width="120"><strong>Kode Booking</strong></th>
                <th width="80"><strong>Tanggal</strong></th>
                <th width="100"><strong>Metode Bayar</strong></th>
                <th width="100"><strong>Status</strong></th>
                <th width="120"><strong>Total</strong></th>
            </tr>
        </thead>
        <tbody>';
    
    $no = 1;
    foreach($data_laporan as $row) {
        $html_detail .= '
            <tr>
                <td width="50">' . $no++ . '</td>
                <td width="120">' . $row['kode_booking_221032'] . '</td>
                <td width="80">' . date('d-m-Y', strtotime($row['tanggal_booking_221032'])) . '</td>
                <td width="100">' . ucfirst($row['metode_pembayaran_221032']) . '</td>
                <td width="100">' . ucfirst($row['status_pembayaran_221032']) . '</td>
                <td width="120">Rp ' . number_format($row['total_biaya'], 0, ',', '.') . '</td>
            </tr>';
    }
    
    $html_detail .= '
        </tbody>
        <tfoot>
            <tr style="background-color: #d0d0d0;">
                <th colspan="5" align="right"><strong>TOTAL PENGELUARAN:</strong></th>
                <th align="right"><strong>Rp ' . number_format($total_pengeluaran, 0, ',', '.') . '</strong></th>
            </tr>
        </tfoot>
    </table>';
} else {
    $html_detail .= '<p>Tidak ada data transaksi untuk periode yang dipilih.</p>';
}

$pdf->writeHTML($html_detail, true, false, true, false, '');

// Tambahkan tanda tangan
$html_ttd = '<br><br>
<table cellpadding="4" cellspacing="0" border="0">
    <tr>
        <td width="300"></td>
        <td width="200" align="center">
            Makassar, ' . date('d-m-Y') . '<br><br><br><br><br>
            <strong>' . $nama_pelanggan . '</strong><br>
            Pelanggan
        </td>
    </tr>
</table>';

$pdf->writeHTML($html_ttd, true, false, true, false, '');

// Output PDF - make sure no output has been sent before this
ob_clean(); // Clean any output buffer
$filename = 'Laporan_Pengeluaran_' . $nama_pelanggan . '_' . date('Y-m-d') . '.pdf';
$pdf->Output($filename, 'I');
?>