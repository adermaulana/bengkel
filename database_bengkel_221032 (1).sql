-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2025 at 09:58 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database_bengkel_221032`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_221032`
--

CREATE TABLE `admin_221032` (
  `nik_221032` varchar(20) NOT NULL,
  `nama_221032` varchar(100) DEFAULT NULL,
  `username_221032` varchar(50) DEFAULT NULL,
  `password_221032` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_221032`
--

INSERT INTO `admin_221032` (`nik_221032`, `nama_221032`, `username_221032`, `password_221032`) VALUES
('12345', 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `bengkel_221032`
--

CREATE TABLE `bengkel_221032` (
  `kode_bengkel_221032` varchar(10) NOT NULL,
  `nama_221032` varchar(100) DEFAULT NULL,
  `alamat_221032` text DEFAULT NULL,
  `telepon_221032` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bengkel_221032`
--

INSERT INTO `bengkel_221032` (`kode_bengkel_221032`, `nama_221032`, `alamat_221032`, `telepon_221032`) VALUES
('BNGKL001', 'Bengkel', 'bengkel', '08548');

-- --------------------------------------------------------

--
-- Table structure for table `booking_221032`
--

CREATE TABLE `booking_221032` (
  `kode_booking_221032` varchar(15) NOT NULL,
  `nik_221032` varchar(20) DEFAULT NULL,
  `tanggal_booking_221032` date DEFAULT NULL,
  `status_221032` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_booking_bengkel_221032`
--

CREATE TABLE `detail_booking_bengkel_221032` (
  `id_bookingbengkel_221032` int(11) NOT NULL,
  `kode_booking_221032` varchar(15) DEFAULT NULL,
  `kode_bengkel_221032` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_layanan_221032`
--

CREATE TABLE `detail_layanan_221032` (
  `id_detaillayanan_221032` int(11) NOT NULL,
  `kode_booking_221032` varchar(15) DEFAULT NULL,
  `kode_layanan_221032` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `layanan_221032`
--

CREATE TABLE `layanan_221032` (
  `kode_layanan_221032` varchar(10) NOT NULL,
  `nama_layanan_221032` varchar(100) DEFAULT NULL,
  `harga_layanan_221032` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan_221032`
--

CREATE TABLE `pelanggan_221032` (
  `nik_221032` varchar(20) NOT NULL,
  `nama_221032` varchar(100) DEFAULT NULL,
  `username_221032` varchar(50) DEFAULT NULL,
  `password_221032` varchar(255) DEFAULT NULL,
  `alamat_221032` text DEFAULT NULL,
  `telepon_221032` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran_221032`
--

CREATE TABLE `pembayaran_221032` (
  `kode_pembayaran_221032` varchar(15) NOT NULL,
  `kode_booking_221032` varchar(15) DEFAULT NULL,
  `metode_pembayaran_221032` varchar(20) DEFAULT NULL,
  `status_pembayaran_221032` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_221032`
--
ALTER TABLE `admin_221032`
  ADD PRIMARY KEY (`nik_221032`);

--
-- Indexes for table `bengkel_221032`
--
ALTER TABLE `bengkel_221032`
  ADD PRIMARY KEY (`kode_bengkel_221032`);

--
-- Indexes for table `booking_221032`
--
ALTER TABLE `booking_221032`
  ADD PRIMARY KEY (`kode_booking_221032`),
  ADD KEY `booking_221032_ibfk_1` (`nik_221032`);

--
-- Indexes for table `detail_booking_bengkel_221032`
--
ALTER TABLE `detail_booking_bengkel_221032`
  ADD PRIMARY KEY (`id_bookingbengkel_221032`),
  ADD KEY `kode_booking_221032` (`kode_booking_221032`),
  ADD KEY `kode_bengkel_221032` (`kode_bengkel_221032`);

--
-- Indexes for table `detail_layanan_221032`
--
ALTER TABLE `detail_layanan_221032`
  ADD PRIMARY KEY (`id_detaillayanan_221032`),
  ADD KEY `detail_layanan_221032_ibfk_1` (`kode_booking_221032`),
  ADD KEY `detail_layanan_221032_ibfk_2` (`kode_layanan_221032`);

--
-- Indexes for table `layanan_221032`
--
ALTER TABLE `layanan_221032`
  ADD PRIMARY KEY (`kode_layanan_221032`);

--
-- Indexes for table `pelanggan_221032`
--
ALTER TABLE `pelanggan_221032`
  ADD PRIMARY KEY (`nik_221032`);

--
-- Indexes for table `pembayaran_221032`
--
ALTER TABLE `pembayaran_221032`
  ADD PRIMARY KEY (`kode_pembayaran_221032`),
  ADD KEY `kode_booking_221032` (`kode_booking_221032`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_booking_bengkel_221032`
--
ALTER TABLE `detail_booking_bengkel_221032`
  MODIFY `id_bookingbengkel_221032` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_layanan_221032`
--
ALTER TABLE `detail_layanan_221032`
  MODIFY `id_detaillayanan_221032` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_221032`
--
ALTER TABLE `booking_221032`
  ADD CONSTRAINT `booking_221032_ibfk_1` FOREIGN KEY (`nik_221032`) REFERENCES `pelanggan_221032` (`nik_221032`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_booking_bengkel_221032`
--
ALTER TABLE `detail_booking_bengkel_221032`
  ADD CONSTRAINT `detail_booking_bengkel_221032_ibfk_1` FOREIGN KEY (`kode_booking_221032`) REFERENCES `booking_221032` (`kode_booking_221032`),
  ADD CONSTRAINT `detail_booking_bengkel_221032_ibfk_2` FOREIGN KEY (`kode_bengkel_221032`) REFERENCES `bengkel_221032` (`kode_bengkel_221032`);

--
-- Constraints for table `detail_layanan_221032`
--
ALTER TABLE `detail_layanan_221032`
  ADD CONSTRAINT `detail_layanan_221032_ibfk_1` FOREIGN KEY (`kode_booking_221032`) REFERENCES `booking_221032` (`kode_booking_221032`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_layanan_221032_ibfk_2` FOREIGN KEY (`kode_layanan_221032`) REFERENCES `layanan_221032` (`kode_layanan_221032`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran_221032`
--
ALTER TABLE `pembayaran_221032`
  ADD CONSTRAINT `pembayaran_221032_ibfk_1` FOREIGN KEY (`kode_booking_221032`) REFERENCES `booking_221032` (`kode_booking_221032`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
