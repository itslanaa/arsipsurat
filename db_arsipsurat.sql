-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 09, 2025 at 11:47 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_arsipsurat`
--

-- --------------------------------------------------------

--
-- Table structure for table `arsip`
--

CREATE TABLE `arsip` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `id_kategori` int NOT NULL,
  `tgl_upload` date NOT NULL,
  `author` varchar(150) DEFAULT NULL,
  `id_user_uploader` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `arsip`
--

INSERT INTO `arsip` (`id`, `judul`, `id_kategori`, `tgl_upload`, `author`, `id_user_uploader`) VALUES
(2, 'Surat Undangan Rapat Koordinasi', 1, '2025-04-12', NULL, 1),
(4, 'SK Camat ', 4, '2025-08-05', NULL, 1),
(5, 'Arsip Kepegawaian 2025', 3, '2025-08-06', NULL, 1),
(7, 'Sosialisasi', 3, '2025-08-06', NULL, 1),
(8, 'Penkes', 1, '2025-08-06', NULL, 1),
(9, 'Umum dan Kepegawaian', 2, '2025-08-06', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `arsip_files`
--

CREATE TABLE `arsip_files` (
  `id` int NOT NULL,
  `id_arsip` int NOT NULL,
  `nama_file_asli` varchar(255) NOT NULL,
  `nama_file_unik` varchar(255) NOT NULL,
  `path_file` varchar(255) NOT NULL,
  `filesize` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `arsip_files`
--

INSERT INTO `arsip_files` (`id`, `id_arsip`, `nama_file_asli`, `nama_file_unik`, `path_file`, `filesize`) VALUES
(7, 9, 'SK Camat Cibunbulang.docx', 'arsip-68930301167ec.docx', 'uploads/arsip/arsip-68930301167ec.docx', 45892),
(8, 8, 'Evaluasi Kinerja Pegawai.docx', 'arsip-68942837784bb.docx', 'uploads/arsip/arsip-68942837784bb.docx', 12482),
(9, 9, 'SK Camat Cibunbulang (1).docx', 'arsip-68a7d969c0502.docx', 'uploads/arsip/arsip-68a7d969c0502.docx', 45892),
(10, 9, 'Daftar Hadir PL.pdf', 'arsip-68a7d98515281.pdf', 'uploads/arsip/arsip-68a7d98515281.pdf', 128580);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_arsip`
--

CREATE TABLE `kategori_arsip` (
  `id` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_arsip`
--

INSERT INTO `kategori_arsip` (`id`, `nama_kategori`) VALUES
(1, 'Surat Masuk'),
(2, 'Laporan Keuangan'),
(3, 'Dokumen Kepegawaian'),
(4, 'Aset Daerah');

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `login_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text NOT NULL,
  `status` enum('success','failed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_history`
--

INSERT INTO `login_history` (`id`, `id_user`, `login_time`, `ip_address`, `user_agent`, `status`) VALUES
(1, 1, '2025-08-08 10:09:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'success'),
(2, 1, '2025-08-08 10:10:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'success'),
(3, 1, '2025-08-08 13:27:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'success'),
(4, 1, '2025-08-16 20:38:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'success'),
(5, 1, '2025-08-19 09:58:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'success'),
(6, 1, '2025-08-19 13:33:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'success'),
(7, 1, '2025-08-19 13:46:08', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:141.0) Gecko/20100101 Firefox/141.0', 'failed'),
(8, 1, '2025-08-19 13:46:14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:141.0) Gecko/20100101 Firefox/141.0', 'success'),
(9, 1, '2025-08-20 08:26:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'success'),
(10, 1, '2025-08-22 08:56:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'success'),
(11, 1, '2025-08-23 08:25:45', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:141.0) Gecko/20100101 Firefox/141.0', 'success'),
(12, 1, '2025-08-23 22:05:24', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'success'),
(13, 1, '2025-08-24 10:22:28', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'success'),
(14, 1, '2025-08-24 20:11:42', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'failed'),
(15, 1, '2025-08-24 20:11:53', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'success'),
(16, 1, '2025-08-25 08:02:53', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'success'),
(17, 1, '2025-08-25 13:07:25', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'success'),
(18, 1, '2025-08-25 23:43:37', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'success'),
(19, 1, '2025-08-26 19:36:22', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'success'),
(20, 1, '2025-08-26 21:46:43', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'success'),
(21, 1, '2025-08-27 06:11:39', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'success'),
(22, 1, '2025-08-27 06:14:34', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'success'),
(23, 1, '2025-08-27 09:11:20', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:142.0) Gecko/20100101 Firefox/142.0', 'success'),
(24, 1, '2025-08-31 16:04:56', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(25, 1, '2025-08-31 16:05:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'failed'),
(26, 1, '2025-08-31 16:06:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(27, 1, '2025-09-01 08:45:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(28, 1, '2025-09-01 13:26:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(29, 1, '2025-09-01 19:40:31', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(30, 1, '2025-09-02 06:26:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(31, 1, '2025-09-02 07:58:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(32, 1, '2025-09-02 11:09:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(33, 1, '2025-09-02 11:09:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(34, 1, '2025-09-03 08:00:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(35, 1, '2025-09-04 07:55:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(36, 1, '2025-09-04 09:35:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(37, 1, '2025-09-05 09:18:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(38, 1, '2025-09-05 16:11:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(39, 1, '2025-09-05 16:28:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(40, 1, '2025-09-05 17:11:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'success'),
(41, 1, '2025-09-06 01:50:34', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'success'),
(42, 1, '2025-09-06 10:36:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'failed'),
(43, 1, '2025-09-06 10:36:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'success'),
(44, 1, '2025-09-08 08:44:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', 'success'),
(45, 2, '2025-09-08 10:53:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', 'success'),
(46, 1, '2025-09-10 06:37:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', 'success');

-- --------------------------------------------------------

--
-- Table structure for table `pejabat`
--

CREATE TABLE `pejabat` (
  `id` int NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `pangkat_gol` varchar(100) DEFAULT NULL,
  `nip` varchar(30) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Camat default'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pejabat`
--

INSERT INTO `pejabat` (`id`, `nama_lengkap`, `jabatan`, `pangkat_gol`, `nip`, `is_default`) VALUES
(1, 'Agung Surachman Ali, S.STP, M.M', 'Camat', 'Pembina', '198412082003121001', 1),
(2, 'Subhi, S.H, M.Si', 'Sekcam', 'Penata Tingkat I III/d', '197608092007011007', 0);

-- --------------------------------------------------------

--
-- Table structure for table `surat_keluar`
--

CREATE TABLE `surat_keluar` (
  `id` int NOT NULL,
  `id_template` int NOT NULL,
  `nomor_surat` varchar(100) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `perihal` varchar(255) DEFAULT NULL,
  `data_surat` text NOT NULL,
  `id_user_pembuat` int NOT NULL,
  `nama_file_pdf` varchar(255) NOT NULL,
  `path_file` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `template_surat`
--

CREATE TABLE `template_surat` (
  `id` int NOT NULL,
  `nama_template` varchar(100) NOT NULL COMMENT 'Nama yang tampil di UI',
  `kode_template` varchar(50) NOT NULL COMMENT 'Kode unik untuk sistem',
  `path_file_template` varchar(255) NOT NULL COMMENT 'Path ke file template HTML/PHP'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `template_surat`
--

INSERT INTO `template_surat` (`id`, `nama_template`, `kode_template`, `path_file_template`) VALUES
(1, 'Surat Tugas', 'tugas', 'templates/surat/templates_html/surat_tugas.php'),
(2, 'Surat Keterangan', 'keterangan', 'templates/surat/templates_html/surat_keterangan.php');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Disimpan dalam bentuk hash',
  `role` enum('admin','staf') NOT NULL DEFAULT 'staf'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `password`, `role`) VALUES
(1, 'Staf Kecamatan', 'staf', '$2y$10$VmGAlK5XpyoW9Hmmqd6azew2SSFxF3eKpZ7Hl7wEkGYd7LPe/JsHG', 'staf'),
(2, 'Admin Kecamatan', 'admin', '$2y$10$m/e.ezl0PN31sdmAMe9/0ONABjYwbS5BCDkLO/t857ZcEq22ASANe', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `arsip`
--
ALTER TABLE `arsip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_user_uploader` (`id_user_uploader`);

--
-- Indexes for table `arsip_files`
--
ALTER TABLE `arsip_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_arsip` (`id_arsip`);

--
-- Indexes for table `kategori_arsip`
--
ALTER TABLE `kategori_arsip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `pejabat`
--
ALTER TABLE `pejabat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surat_keluar`
--
ALTER TABLE `surat_keluar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tanggal` (`tanggal_surat`),
  ADD KEY `idx_template` (`id_template`);

--
-- Indexes for table `template_surat`
--
ALTER TABLE `template_surat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_template` (`kode_template`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `arsip`
--
ALTER TABLE `arsip`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `arsip_files`
--
ALTER TABLE `arsip_files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `kategori_arsip`
--
ALTER TABLE `kategori_arsip`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `pejabat`
--
ALTER TABLE `pejabat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `surat_keluar`
--
ALTER TABLE `surat_keluar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `template_surat`
--
ALTER TABLE `template_surat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `arsip`
--
ALTER TABLE `arsip`
  ADD CONSTRAINT `arsip_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_arsip` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `arsip_ibfk_2` FOREIGN KEY (`id_user_uploader`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `arsip_files`
--
ALTER TABLE `arsip_files`
  ADD CONSTRAINT `arsip_files_ibfk_1` FOREIGN KEY (`id_arsip`) REFERENCES `arsip` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `login_history`
--
ALTER TABLE `login_history`
  ADD CONSTRAINT `login_history_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
