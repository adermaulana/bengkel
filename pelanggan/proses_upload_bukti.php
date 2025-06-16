<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_booking = $_POST['kode_booking'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    
    // Upload file
    $target_dir = "uploads/bukti_pembayaran/";
    $file_name = basename($_FILES["bukti_pembayaran"]["name"]);
    $target_file = $target_dir . $kode_booking . "_" . $file_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["bukti_pembayaran"]["tmp_name"]);
    if($check === false) {
        $_SESSION['error'] = "File bukan gambar.";
        header("Location: pembayaran.php");
        exit();
    }

    // Check file size
    if ($_FILES["bukti_pembayaran"]["size"] > 2000000) {
        $_SESSION['error'] = "Ukuran file terlalu besar (max 2MB).";
        header("Location: pembayaran.php");
        exit();
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $_SESSION['error'] = "Hanya format JPG, JPEG, PNG yang diizinkan.";
        header("Location: booking.php");
        exit();
    }

    // Upload file
    if (move_uploaded_file($_FILES["bukti_pembayaran"]["tmp_name"], $target_file)) {

        $kode_pembayaran = 'PEM' . date('YmdHis');
        // Simpan ke database
        $query = "INSERT INTO pembayaran_221032 (
                    kode_pembayaran_221032, 
                    kode_booking_221032, 
                    metode_pembayaran_221032, 
                    status_pembayaran_221032, 
                    bukti_pembayaran_221032
                ) VALUES (
                    '$kode_pembayaran',
                    '$kode_booking',
                    '$metode_pembayaran',
                    'menunggu verifikasi',
                    '$target_file'
                )";
        
        if (mysqli_query($koneksi, $query)) {
            // Update status booking
            mysqli_query($koneksi, "UPDATE booking_221032 SET status_221032 = 'menunggu verifikasi' WHERE kode_booking_221032 = '$kode_booking'");
            
            $_SESSION['success'] = "Bukti pembayaran berhasil diupload dan menunggu verifikasi.";
            header("Location: pembayaran.php");
            exit();
        } else {
            $_SESSION['error'] = "Gagal menyimpan data pembayaran: " . mysqli_error($koneksi);
            header("Location: pembayaran.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat upload file.";
        header("Location: pembayaran.php");
        exit();
    }
} else {
    header("Location: pembayaran.php");
    exit();
}
?>