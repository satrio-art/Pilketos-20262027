-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2026 at 03:52 AM
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
-- Database: `e-vote`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth`
--

CREATE TABLE `auth` (
  `id_auth` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth`
--

INSERT INTO `auth` (`id_auth`, `username`, `password`) VALUES
(6, 'yuda', '$2y$10$VrKtwsrmFa/ppv1AlQPufuZ.yJPsyf4WrL69DuPlO6B4cJvhXiPhK'),
(8, 'smkn3rl', '$2y$10$KKe57TpUx9fzWDEfmkyygu9COM6QuohvRXNnzr.BGG09/PUQCZJ/y');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_info`
--

CREATE TABLE `laporan_info` (
  `id_info` int(11) NOT NULL,
  `nama_kegiatan` varchar(100) DEFAULT NULL,
  `hari_tanggal` varchar(100) DEFAULT NULL,
  `waktu` varchar(50) DEFAULT NULL,
  `tempat` varchar(100) DEFAULT NULL,
  `metode` varchar(100) DEFAULT NULL,
  `pembina_osis` varchar(100) DEFAULT NULL,
  `nip_pembina_osis` varchar(50) DEFAULT NULL,
  `kepala_sekolah` varchar(100) DEFAULT NULL,
  `nip_kepala_sekolah` varchar(50) DEFAULT NULL,
  `ketua_osis_demisioner` varchar(100) DEFAULT NULL,
  `nis_ketua_osis_lama` varchar(50) DEFAULT NULL,
  `logo_sekolah` varchar(255) NOT NULL,
  `logo_osis` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan_info`
--

INSERT INTO `laporan_info` (`id_info`, `nama_kegiatan`, `hari_tanggal`, `waktu`, `tempat`, `metode`, `pembina_osis`, `nip_pembina_osis`, `kepala_sekolah`, `nip_kepala_sekolah`, `ketua_osis_demisioner`, `nis_ketua_osis_lama`, `logo_sekolah`, `logo_osis`) VALUES
(1, 'Pemilihan Ketua Osis SMK Negeri 3 RL Tahun 2025', 'Kamis, 16 Oktober 2025', '08.00 Wib - 11.00 Wib', 'Aula SMKN 3 Rejang Lebong', 'Pemilihan dan Perekapan Digital (Evote)', 'Ardian Budi Kusuma, S.Pd', '199308132022211006', 'Budi Setia Edy, S.Pd', '196702212006041004', 'Rifki Ardiansyah', '2838', '6968753d14600.png', '6968754886a7f.png');

-- --------------------------------------------------------

--
-- Table structure for table `mata_pilih`
--

CREATE TABLE `mata_pilih` (
  `id_matapilih` int(11) NOT NULL,
  `nama_pemilih` varchar(50) NOT NULL,
  `nip` varchar(50) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `skor_mata` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mata_pilih`
--

INSERT INTO `mata_pilih` (`id_matapilih`, `nama_pemilih`, `nip`, `kelas`, `skor_mata`) VALUES
(346, 'Bambang', 'evote2026', 'XII TJKT', 0),
(347, 'Pamungkas', 'evote2027', 'XII APAT', 0),
(348, 'Cristian', 'evote2028', 'XII ATU', 0),
(349, 'Gonzales', 'evote2029', 'XII FKK', 0);

-- --------------------------------------------------------

--
-- Table structure for table `paslon`
--

CREATE TABLE `paslon` (
  `id_paslon` int(11) NOT NULL,
  `nama_ketua` varchar(50) NOT NULL,
  `nama_wakil` varchar(50) NOT NULL,
  `motto` text NOT NULL,
  `skor` int(11) NOT NULL,
  `foto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paslon`
--

INSERT INTO `paslon` (`id_paslon`, `nama_ketua`, `nama_wakil`, `motto`, `skor`, `foto`) VALUES
(9, 'Desmuvita', 'M. Hafizh Gio Syaputra', 'SMKN 3 RL BISA', 0, 'paslon_68ef12dccc18f.jpg'),
(10, 'Ranna Mozza', 'Lisa Puspita Sari', 'SMKN 3 RL BISA', 0, 'paslon_68ef12f62119c.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pilihan`
--

CREATE TABLE `pilihan` (
  `id_pilihan` int(11) NOT NULL,
  `id_matapilih` int(11) NOT NULL,
  `id_paslon` int(11) NOT NULL,
  `skor_pilih` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `pilihan`
--
DELIMITER $$
CREATE TRIGGER `kurang` BEFORE UPDATE ON `pilihan` FOR EACH ROW UPDATE paslon SET paslon.skor = paslon.skor - old.skor_pilih
WHERE paslon.id_paslon = old.id_paslon
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `mata` AFTER INSERT ON `pilihan` FOR EACH ROW BEGIN
UPDATE mata_pilih SET mata_pilih.skor_mata = mata_pilih.skor_mata + NEW.skor_pilih
WHERE id_matapilih = NEW.id_matapilih;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tambah` AFTER INSERT ON `pilihan` FOR EACH ROW BEGIN
UPDATE paslon SET paslon.skor = paslon.skor + NEW.skor_pilih
WHERE id_paslon = NEW.id_paslon;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tambah_manual` BEFORE UPDATE ON `pilihan` FOR EACH ROW UPDATE paslon SET paslon.skor = paslon.skor + NEW.skor_pilih
WHERE paslon.id_paslon = NEW.id_paslon
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth`
--
ALTER TABLE `auth`
  ADD PRIMARY KEY (`id_auth`);

--
-- Indexes for table `laporan_info`
--
ALTER TABLE `laporan_info`
  ADD PRIMARY KEY (`id_info`);

--
-- Indexes for table `mata_pilih`
--
ALTER TABLE `mata_pilih`
  ADD PRIMARY KEY (`id_matapilih`);

--
-- Indexes for table `paslon`
--
ALTER TABLE `paslon`
  ADD PRIMARY KEY (`id_paslon`);

--
-- Indexes for table `pilihan`
--
ALTER TABLE `pilihan`
  ADD PRIMARY KEY (`id_pilihan`),
  ADD KEY `id_matapilih` (`id_matapilih`),
  ADD KEY `id_paslon` (`id_paslon`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth`
--
ALTER TABLE `auth`
  MODIFY `id_auth` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `laporan_info`
--
ALTER TABLE `laporan_info`
  MODIFY `id_info` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mata_pilih`
--
ALTER TABLE `mata_pilih`
  MODIFY `id_matapilih` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=350;

--
-- AUTO_INCREMENT for table `paslon`
--
ALTER TABLE `paslon`
  MODIFY `id_paslon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pilihan`
--
ALTER TABLE `pilihan`
  MODIFY `id_pilihan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pilihan`
--
ALTER TABLE `pilihan`
  ADD CONSTRAINT `pilihan_ibfk_1` FOREIGN KEY (`id_matapilih`) REFERENCES `mata_pilih` (`id_matapilih`),
  ADD CONSTRAINT `pilihan_ibfk_2` FOREIGN KEY (`id_paslon`) REFERENCES `paslon` (`id_paslon`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
