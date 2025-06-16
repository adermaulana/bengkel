<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_booking = $_POST['kode_booking'];
    $status = $_POST['status'];
    
    // Update status pembayaran
    $update = mysqli_query($koneksi, "UPDATE pembayaran_221032 
                                     SET status_pembayaran_221032 = '$status'
                                     WHERE kode_booking_221032 = '$kode_booking'");
    
    // Jika diterima, update status booking
    if ($status == 'diterima') {
        mysqli_query($koneksi, "UPDATE booking_221032 
                              SET status_221032 = 'dikonfirmasi'
                              WHERE kode_booking_221032 = '$kode_booking'");
    }
    
    if ($update) {
        $_SESSION['success'] = 'Status pembayaran berhasil diperbarui';
    } else {
        $_SESSION['error'] = 'Gagal memperbarui status pembayaran';
    }
    
    echo json_encode(['status' => 'success']);
}
?>