-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 26, 2025 at 03:16 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_spp_udis`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `cetak_invoice` (IN `id_bayar` INT)   BEGIN

SELECT pembayaran.id_pembayaran, pembayaran.tgl_bayar, siswa.nisn, siswa.nis, siswa.nama, kelas.nama_kelas,
kelas.kompetensi_keahlian, spp.tahun, pembayaran.bulan_dibayar, pembayaran.jumlah_bayar, petugas.nama_petugas
FROM `pembayaran` 
INNER JOIN siswa ON siswa.nisn = pembayaran.nisn
INNER JOIN spp ON pembayaran.id_spp = spp.id_spp
INNER JOIN petugas ON petugas.id_petugas = pembayaran.id_petugas
INNER JOIN kelas ON kelas.id_kelas = siswa.id_kelas
WHERE pembayaran.id_pembayaran = id_bayar;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `dashboard_siswa` (IN `p_nisn` VARCHAR(20))   BEGIN

    SELECT 
        spp.nominal AS nominal_spp,

        (SELECT IFNULL(SUM(jumlah_bayar), 0)
         FROM pembayaran
         WHERE nisn COLLATE utf8mb4_unicode_ci = p_nisn
         AND pembayaran.deleted_at IS NULL
        ) AS total_sudah_bayar,

        (
            (spp.nominal * 12) -
            IFNULL((
                SELECT SUM(jumlah_bayar)
                FROM pembayaran
                WHERE nisn COLLATE utf8mb4_unicode_ci = p_nisn
                AND pembayaran.deleted_at IS NULL
            ), 0)
        ) AS total_tunggakan

    FROM siswa
    JOIN spp ON siswa.id_spp = spp.id_spp
    WHERE siswa.nisn COLLATE utf8mb4_unicode_ci = p_nisn;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `dashboard_petugas`
-- (See below for the actual view)
--
CREATE TABLE `dashboard_petugas` (
`total_hari_ini` decimal(32,0)
,`total_siswa` bigint
,`total_transaksi` bigint
);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int NOT NULL,
  `nama_kelas` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kompetensi_keahlian` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `kompetensi_keahlian`, `created_at`, `updated_at`) VALUES
(1, '12', 'Rekayasa Perangkat Lunak', '2025-11-21 12:43:47', '2025-11-21 12:43:47'),
(2, '12', 'Teknik Komputer dan Jaringan', '2025-11-21 12:43:47', '2025-11-21 12:43:47'),
(3, '11', 'Rekayasa Perangkat Lunak', '2025-11-21 12:43:47', '2025-11-21 12:43:47'),
(4, '11', 'Teknik Komputer dan Jaringan', '2025-11-21 12:43:47', '2025-11-21 12:43:47'),
(5, '11', 'Teknik Elektronika Industri', '2025-11-21 12:43:47', '2025-11-21 12:43:47'),
(6, '10', 'Rekayasa Perangkat Lunak', '2025-11-21 12:43:47', '2025-11-21 12:43:47'),
(7, '10', 'Teknik Komputer dan Jaringan', '2025-11-21 12:43:47', '2025-11-21 12:43:47'),
(8, '10', 'Teknik Elektronika Industri', '2025-11-21 12:43:47', '2025-11-21 12:43:47'),
(9, '10', 'Teknik Pendingin dan Tata Udara', '2025-11-21 12:43:47', '2025-11-21 12:43:47');

-- --------------------------------------------------------

--
-- Stand-in structure for view `list_pembayaran`
-- (See below for the actual view)
--
CREATE TABLE `list_pembayaran` (
`id_kelas` int
,`kompetensi_keahlian` varchar(50)
,`nama` varchar(35)
,`nama_kelas` varchar(10)
,`nis` char(8)
,`nisn` char(10)
,`nominal` int
,`status_pembayaran` varchar(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` bigint UNSIGNED NOT NULL,
  `aktifitas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_petugas` int DEFAULT NULL,
  `nisn` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `aktifitas`, `id_petugas`, `nisn`, `created_at`, `updated_at`) VALUES
(1, 'Melakukan logout', 1, NULL, '2025-11-21 12:44:42', '2025-11-21 12:44:42'),
(2, 'Melakukan login', 2, NULL, '2025-11-21 12:46:54', '2025-11-21 12:46:54'),
(3, 'Input Pembayaran Bulan Juli, Siswa/i: ', 2, '0031587670', '2025-11-21 12:48:33', '2025-11-21 12:48:33'),
(4, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 2, '0031587670', '2025-11-21 12:48:33', '2025-11-21 12:48:33'),
(5, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 2, '0031587670', '2025-11-21 12:48:45', '2025-11-21 12:48:45'),
(6, 'Melakukan login', 1, NULL, '2025-11-21 16:01:10', '2025-11-21 16:01:10'),
(7, 'Melakukan logout', 1, NULL, '2025-11-21 16:17:54', '2025-11-21 16:17:54'),
(8, 'Melakukan login', 2, NULL, '2025-11-21 16:18:09', '2025-11-21 16:18:09'),
(9, 'Melakukan logout', 2, NULL, '2025-11-21 16:18:23', '2025-11-21 16:18:23'),
(10, 'Melakukan login', NULL, '0000499987', '2025-11-21 16:18:54', '2025-11-21 16:18:54'),
(11, 'Melakukan logout', NULL, '0000499987', '2025-11-21 16:20:44', '2025-11-21 16:20:44'),
(12, 'Melakukan login', 6, NULL, '2025-11-21 16:21:04', '2025-11-21 16:21:04'),
(13, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0000499987', '2025-11-21 16:21:22', '2025-11-21 16:21:22'),
(14, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0000499987', '2025-11-21 16:21:22', '2025-11-21 16:21:22'),
(15, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0003530465', '2025-11-21 16:21:30', '2025-11-21 16:21:30'),
(16, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0004245058', '2025-11-21 16:21:41', '2025-11-21 16:21:41'),
(17, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0004245058', '2025-11-21 16:21:41', '2025-11-21 16:21:41'),
(18, 'Input Pembayaran Bulan September, Siswa/i: ', 6, '0004245058', '2025-11-21 16:21:41', '2025-11-21 16:21:41'),
(19, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0007032717', '2025-11-21 16:21:49', '2025-11-21 16:21:49'),
(20, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0007947311', '2025-11-21 16:22:22', '2025-11-21 16:22:22'),
(21, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0009402859', '2025-11-21 16:22:36', '2025-11-21 16:22:36'),
(22, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0009402859', '2025-11-21 16:22:36', '2025-11-21 16:22:36'),
(23, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0099125116', '2025-11-21 16:22:47', '2025-11-21 16:22:47'),
(24, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0011581563', '2025-11-21 16:23:27', '2025-11-21 16:23:27'),
(25, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0011581563', '2025-11-21 16:23:27', '2025-11-21 16:23:27'),
(26, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0013342357', '2025-11-21 16:23:36', '2025-11-21 16:23:36'),
(27, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0018307471', '2025-11-21 16:23:47', '2025-11-21 16:23:47'),
(28, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0018307471', '2025-11-21 16:23:47', '2025-11-21 16:23:47'),
(29, 'Input Pembayaran Bulan September, Siswa/i: ', 6, '0018307471', '2025-11-21 16:23:47', '2025-11-21 16:23:47'),
(30, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0018591476', '2025-11-21 16:24:03', '2025-11-21 16:24:03'),
(31, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0018591476', '2025-11-21 16:24:03', '2025-11-21 16:24:03'),
(32, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0019866053', '2025-11-21 16:24:11', '2025-11-21 16:24:11'),
(33, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0024528978', '2025-11-21 16:24:23', '2025-11-21 16:24:23'),
(34, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0024528978', '2025-11-21 16:24:23', '2025-11-21 16:24:23'),
(35, 'Input Pembayaran Bulan September, Siswa/i: ', 6, '0024528978', '2025-11-21 16:24:23', '2025-11-21 16:24:23'),
(36, 'Input Pembayaran Bulan Oktober, Siswa/i: ', 6, '0024528978', '2025-11-21 16:24:23', '2025-11-21 16:24:23'),
(37, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0030428420', '2025-11-21 16:24:31', '2025-11-21 16:24:31'),
(38, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0030428420', '2025-11-21 16:24:31', '2025-11-21 16:24:31'),
(39, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0031489732', '2025-11-21 16:24:41', '2025-11-21 16:24:41'),
(40, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0031489732', '2025-11-21 16:24:41', '2025-11-21 16:24:41'),
(41, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0098206573', '2025-11-21 16:24:51', '2025-11-21 16:24:51'),
(42, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0098206573', '2025-11-21 16:24:51', '2025-11-21 16:24:51'),
(43, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0096317297', '2025-11-21 16:25:00', '2025-11-21 16:25:00'),
(44, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0096317297', '2025-11-21 16:25:00', '2025-11-21 16:25:00'),
(45, 'Input Pembayaran Bulan September, Siswa/i: ', 6, '0096317297', '2025-11-21 16:25:00', '2025-11-21 16:25:00'),
(46, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0095880080', '2025-11-21 16:25:11', '2025-11-21 16:25:11'),
(47, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0095880080', '2025-11-21 16:25:11', '2025-11-21 16:25:11'),
(48, 'Input Pembayaran Bulan September, Siswa/i: ', 6, '0095880080', '2025-11-21 16:25:11', '2025-11-21 16:25:11'),
(49, 'Input Pembayaran Bulan Oktober, Siswa/i: ', 6, '0095880080', '2025-11-21 16:25:11', '2025-11-21 16:25:11'),
(50, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0095591431', '2025-11-21 16:25:21', '2025-11-21 16:25:21'),
(51, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0095591431', '2025-11-21 16:25:21', '2025-11-21 16:25:21'),
(52, 'Input Pembayaran Bulan September, Siswa/i: ', 6, '0095591431', '2025-11-21 16:25:21', '2025-11-21 16:25:21'),
(53, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0095436105', '2025-11-21 16:25:31', '2025-11-21 16:25:31'),
(54, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0095436105', '2025-11-21 16:25:31', '2025-11-21 16:25:31'),
(55, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0094685585', '2025-11-21 16:25:40', '2025-11-21 16:25:40'),
(56, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0039584160', '2025-11-21 16:25:52', '2025-11-21 16:25:52'),
(57, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0039584160', '2025-11-21 16:25:52', '2025-11-21 16:25:52'),
(58, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0094604840', '2025-11-21 16:26:12', '2025-11-21 16:26:12'),
(59, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0094604840', '2025-11-21 16:26:12', '2025-11-21 16:26:12'),
(60, 'Input Pembayaran Bulan September, Siswa/i: ', 6, '0094604840', '2025-11-21 16:26:12', '2025-11-21 16:26:12'),
(61, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0049729895', '2025-11-21 16:26:29', '2025-11-21 16:26:29'),
(62, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0049729895', '2025-11-21 16:26:29', '2025-11-21 16:26:29'),
(63, 'Input Pembayaran Bulan September, Siswa/i: ', 6, '0049729895', '2025-11-21 16:26:29', '2025-11-21 16:26:29'),
(64, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0050607639', '2025-11-21 16:26:56', '2025-11-21 16:26:56'),
(65, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0050607639', '2025-11-21 16:26:56', '2025-11-21 16:26:56'),
(66, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0053781192', '2025-11-21 16:27:06', '2025-11-21 16:27:06'),
(67, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0093381634', '2025-11-21 16:27:18', '2025-11-21 16:27:18'),
(68, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0093381634', '2025-11-21 16:27:18', '2025-11-21 16:27:18'),
(69, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0093148402', '2025-11-21 16:27:28', '2025-11-21 16:27:28'),
(70, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0093148402', '2025-11-21 16:27:28', '2025-11-21 16:27:28'),
(71, 'Input Pembayaran Bulan September, Siswa/i: ', 6, '0093148402', '2025-11-21 16:27:28', '2025-11-21 16:27:28'),
(72, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0093033717', '2025-11-21 16:27:36', '2025-11-21 16:27:36'),
(73, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0093033717', '2025-11-21 16:27:36', '2025-11-21 16:27:36'),
(74, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0085933191', '2025-11-21 16:27:48', '2025-11-21 16:27:48'),
(75, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0084300534', '2025-11-21 16:27:57', '2025-11-21 16:27:57'),
(76, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0082151639', '2025-11-21 16:28:10', '2025-11-21 16:28:10'),
(77, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0082151639', '2025-11-21 16:28:10', '2025-11-21 16:28:10'),
(78, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0079568927', '2025-11-21 16:28:32', '2025-11-21 16:28:32'),
(79, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0079568927', '2025-11-21 16:28:32', '2025-11-21 16:28:32'),
(80, 'Input Pembayaran Bulan September, Siswa/i: ', 6, '0079568927', '2025-11-21 16:28:32', '2025-11-21 16:28:32'),
(81, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0079428260', '2025-11-21 16:28:43', '2025-11-21 16:28:43'),
(82, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0079428260', '2025-11-21 16:28:43', '2025-11-21 16:28:43'),
(83, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0078077509', '2025-11-21 16:28:56', '2025-11-21 16:28:56'),
(84, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0076229530', '2025-11-21 16:29:13', '2025-11-21 16:29:13'),
(85, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0073718700', '2025-11-21 16:29:23', '2025-11-21 16:29:23'),
(86, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0073718700', '2025-11-21 16:29:23', '2025-11-21 16:29:23'),
(87, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0070078544', '2025-11-21 16:29:35', '2025-11-21 16:29:35'),
(88, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0070078544', '2025-11-21 16:29:35', '2025-11-21 16:29:35'),
(89, 'Input Pembayaran Bulan September, Siswa/i: ', 6, '0070078544', '2025-11-21 16:29:35', '2025-11-21 16:29:35'),
(90, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0066815417', '2025-11-21 16:29:49', '2025-11-21 16:29:49'),
(91, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0066815417', '2025-11-21 16:29:49', '2025-11-21 16:29:49'),
(92, 'Input Pembayaran Bulan September, Siswa/i: ', 6, '0066815417', '2025-11-21 16:29:49', '2025-11-21 16:29:49'),
(93, 'Input Pembayaran Bulan Oktober, Siswa/i: ', 6, '0066815417', '2025-11-21 16:29:49', '2025-11-21 16:29:49'),
(94, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0066236060', '2025-11-21 16:30:02', '2025-11-21 16:30:02'),
(95, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0066236060', '2025-11-21 16:30:02', '2025-11-21 16:30:02'),
(96, 'Input Pembayaran Bulan September, Siswa/i: ', 6, '0066236060', '2025-11-21 16:30:02', '2025-11-21 16:30:02'),
(97, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0064924424', '2025-11-21 16:30:11', '2025-11-21 16:30:11'),
(98, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0064924424', '2025-11-21 16:30:11', '2025-11-21 16:30:11'),
(99, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0064604313', '2025-11-21 16:30:29', '2025-11-21 16:30:29'),
(100, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0060950030', '2025-11-21 16:30:43', '2025-11-21 16:30:43'),
(101, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0059978990', '2025-11-21 16:30:59', '2025-11-21 16:30:59'),
(102, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0059978990', '2025-11-21 16:30:59', '2025-11-21 16:30:59'),
(103, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0003530465', '2025-11-21 16:31:44', '2025-11-21 16:31:44'),
(104, 'Input Pembayaran Bulan September, Siswa/i: ', 6, '0003530465', '2025-11-21 16:31:44', '2025-11-21 16:31:44'),
(105, 'Input Pembayaran Bulan Oktober, Siswa/i: ', 6, '0003530465', '2025-11-21 16:31:44', '2025-11-21 16:31:44'),
(106, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0031587670', '2025-11-21 16:31:55', '2025-11-21 16:31:55'),
(107, 'Input Pembayaran Bulan September, Siswa/i: ', 6, '0031587670', '2025-11-21 16:31:55', '2025-11-21 16:31:55'),
(108, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0085682025', '2025-11-21 16:32:13', '2025-11-21 16:32:13'),
(109, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 6, '0085682025', '2025-11-21 16:32:13', '2025-11-21 16:32:13'),
(110, 'Input Pembayaran Bulan Juli, Siswa/i: ', 6, '0055640859', '2025-11-21 16:32:39', '2025-11-21 16:32:39'),
(494, 'Melakukan login', NULL, '0060950030', '2025-11-21 17:26:16', '2025-11-21 17:26:16'),
(495, 'Melakukan logout', NULL, '0060950030', '2025-11-21 17:26:30', '2025-11-21 17:26:30'),
(496, 'Melakukan login', 10, NULL, '2025-11-21 17:27:02', '2025-11-21 17:27:02'),
(497, 'Input Pembayaran Bulan September, Siswa/i: ', 10, '0000499987', '2025-11-21 17:27:20', '2025-11-21 17:27:20'),
(498, 'Melakukan logout', 10, NULL, '2025-11-21 17:27:26', '2025-11-21 17:27:26'),
(499, 'Melakukan login', 8, NULL, '2025-11-21 17:28:03', '2025-11-21 17:28:03'),
(500, 'Input Pembayaran Bulan Juli, Siswa/i: ', 8, '0050534585', '2025-11-21 17:28:44', '2025-11-21 17:28:44'),
(501, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 8, '0050534585', '2025-11-21 17:28:44', '2025-11-21 17:28:44'),
(502, 'Melakukan logout', 8, NULL, '2025-11-21 17:28:55', '2025-11-21 17:28:55'),
(503, 'Melakukan logout', 6, NULL, '2025-11-21 17:33:09', '2025-11-21 17:33:09'),
(504, 'Melakukan login', 2, NULL, '2025-11-24 01:48:25', '2025-11-24 01:48:25'),
(505, 'Melakukan logout', 2, NULL, '2025-11-24 01:49:53', '2025-11-24 01:49:53'),
(506, 'Melakukan login', 21, NULL, '2025-11-24 01:50:06', '2025-11-24 01:50:06'),
(507, 'Melakukan logout', 21, NULL, '2025-11-24 01:50:15', '2025-11-24 01:50:15'),
(508, 'Melakukan login', 2, NULL, '2025-11-24 01:50:32', '2025-11-24 01:50:32'),
(509, 'Melakukan logout', 2, NULL, '2025-11-24 01:50:58', '2025-11-24 01:50:58'),
(510, 'Melakukan login', 21, NULL, '2025-11-24 01:51:04', '2025-11-24 01:51:04'),
(511, 'Melakukan logout', 21, NULL, '2025-11-24 01:51:07', '2025-11-24 01:51:07'),
(512, 'Melakukan login', 2, NULL, '2025-11-24 01:51:20', '2025-11-24 01:51:20'),
(513, 'Input Pembayaran Bulan Juli, Siswa/i: ', 2, '0000499987', '2025-11-24 01:55:35', '2025-11-24 01:55:35'),
(514, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 2, '0000499987', '2025-11-24 01:55:35', '2025-11-24 01:55:35'),
(515, 'Input Pembayaran Bulan September, Siswa/i: ', 2, '0000499987', '2025-11-24 01:55:35', '2025-11-24 01:55:35'),
(516, 'Hapus Pembayaran Bulan September, Siswa/i: ', 2, '0000499987', '2025-11-24 01:55:44', '2025-11-24 01:55:44'),
(517, 'Input Pembayaran Bulan Juli, Siswa/i: ', 2, '0031587670', '2025-11-24 01:57:05', '2025-11-24 01:57:05'),
(518, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 2, '0031587670', '2025-11-24 01:57:05', '2025-11-24 01:57:05'),
(519, 'Input Pembayaran Bulan Juli, Siswa/i: ', 2, '0066815417', '2025-11-24 01:57:26', '2025-11-24 01:57:26'),
(520, 'Input Pembayaran Bulan Juli, Siswa/i: ', 2, '0079568927', '2025-11-24 01:57:36', '2025-11-24 01:57:36'),
(521, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 2, '0079568927', '2025-11-24 01:57:36', '2025-11-24 01:57:36'),
(522, 'Input Pembayaran Bulan September, Siswa/i: ', 2, '0079568927', '2025-11-24 01:57:36', '2025-11-24 01:57:36'),
(523, 'Input Pembayaran Bulan Juli, Siswa/i: ', 2, '0070078544', '2025-11-24 01:57:50', '2025-11-24 01:57:50'),
(524, 'Input Pembayaran Bulan Juli, Siswa/i: ', 2, '0009402859', '2025-11-24 01:58:04', '2025-11-24 01:58:04'),
(525, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 2, '0009402859', '2025-11-24 01:58:04', '2025-11-24 01:58:04'),
(526, 'Input Pembayaran Bulan Juli, Siswa/i: ', 2, '0073718700', '2025-11-24 01:58:17', '2025-11-24 01:58:17'),
(527, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 2, '0073718700', '2025-11-24 01:58:17', '2025-11-24 01:58:17'),
(528, 'Input Pembayaran Bulan Juli, Siswa/i: ', 2, '0050607639', '2025-11-24 01:58:34', '2025-11-24 01:58:34'),
(529, 'Melakukan logout', 2, NULL, '2025-11-24 01:59:27', '2025-11-24 01:59:27'),
(530, 'Melakukan login', NULL, '0003530465', '2025-11-24 01:59:47', '2025-11-24 01:59:47'),
(531, 'Melakukan logout', NULL, '0003530465', '2025-11-24 02:00:17', '2025-11-24 02:00:17'),
(532, 'Melakukan login', 11, NULL, '2025-11-24 02:03:34', '2025-11-24 02:03:34'),
(534, 'Input Pembayaran Bulan Juli, Siswa/i: ', 11, '0004245058', '2025-11-24 02:05:39', '2025-11-24 02:05:39'),
(535, 'Input Pembayaran Bulan Agustus, Siswa/i: ', 11, '0004245058', '2025-11-24 02:05:39', '2025-11-24 02:05:39'),
(536, 'Melakukan logout', 11, NULL, '2025-11-24 02:06:41', '2025-11-24 02:06:41'),
(537, 'Melakukan login', NULL, '0063769540', '2025-11-24 02:07:26', '2025-11-24 02:07:26'),
(538, 'Melakukan logout', NULL, '0063769540', '2025-11-24 02:08:09', '2025-11-24 02:08:09'),
(539, 'Melakukan login', 7, NULL, '2025-11-24 11:59:23', '2025-11-24 11:59:23'),
(540, 'Input Pembayaran Bulan November, Siswa/i: ', 7, '0003530465', '2025-11-24 12:15:08', '2025-11-24 12:15:08'),
(541, 'Input Pembayaran Bulan Desember, Siswa/i: ', 7, '0003530465', '2025-11-24 12:15:08', '2025-11-24 12:15:08'),
(542, 'Input Pembayaran Bulan Januari, Siswa/i: ', 7, '0003530465', '2025-11-24 12:15:08', '2025-11-24 12:15:08'),
(543, 'Input Pembayaran Bulan Februari, Siswa/i: ', 7, '0003530465', '2025-11-24 12:15:08', '2025-11-24 12:15:08'),
(544, 'Input Pembayaran Bulan Maret, Siswa/i: ', 7, '0003530465', '2025-11-24 12:15:08', '2025-11-24 12:15:08'),
(545, 'Input Pembayaran Bulan April, Siswa/i: ', 7, '0003530465', '2025-11-24 12:15:08', '2025-11-24 12:15:08'),
(546, 'Input Pembayaran Bulan Mei, Siswa/i: ', 7, '0003530465', '2025-11-24 12:15:08', '2025-11-24 12:15:08'),
(547, 'Input Pembayaran Bulan Juni, Siswa/i: ', 7, '0003530465', '2025-11-24 12:15:08', '2025-11-24 12:15:08'),
(548, 'Input Pembayaran Bulan September, Siswa/i: ', 7, '0000499987', '2025-11-24 12:17:13', '2025-11-24 12:17:13'),
(549, 'Input Pembayaran Bulan Oktober, Siswa/i: ', 7, '0000499987', '2025-11-24 12:17:13', '2025-11-24 12:17:13'),
(550, 'Input Pembayaran Bulan November, Siswa/i: ', 7, '0000499987', '2025-11-24 12:17:13', '2025-11-24 12:17:13'),
(551, 'Input Pembayaran Bulan Desember, Siswa/i: ', 7, '0000499987', '2025-11-24 12:17:13', '2025-11-24 12:17:13'),
(552, 'Input Pembayaran Bulan Januari, Siswa/i: ', 7, '0000499987', '2025-11-24 12:17:13', '2025-11-24 12:17:13'),
(553, 'Input Pembayaran Bulan Februari, Siswa/i: ', 7, '0000499987', '2025-11-24 12:17:13', '2025-11-24 12:17:13'),
(554, 'Input Pembayaran Bulan Maret, Siswa/i: ', 7, '0000499987', '2025-11-24 12:17:13', '2025-11-24 12:17:13'),
(555, 'Input Pembayaran Bulan April, Siswa/i: ', 7, '0000499987', '2025-11-24 12:17:13', '2025-11-24 12:17:13'),
(556, 'Input Pembayaran Bulan Mei, Siswa/i: ', 7, '0000499987', '2025-11-24 12:17:13', '2025-11-24 12:17:13'),
(557, 'Input Pembayaran Bulan Juni, Siswa/i: ', 7, '0000499987', '2025-11-24 12:17:13', '2025-11-24 12:17:13'),
(558, 'Melakukan logout', 7, NULL, '2025-11-24 12:28:10', '2025-11-24 12:28:10'),
(559, 'Melakukan login', 3, NULL, '2025-11-25 12:47:25', '2025-11-25 12:47:25'),
(560, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0003530465', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(561, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0007032717', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(562, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0007947311', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(563, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0099125116', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(564, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0011581563', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(565, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0011581563', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(566, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0013342357', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(567, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0018307471', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(568, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0018307471', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(569, 'Hapus Pembayaran Bulan September, Siswa/i: ', 6, '0018307471', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(570, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0018591476', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(571, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0018591476', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(572, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0019866053', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(573, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0024528978', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(574, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0024528978', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(575, 'Hapus Pembayaran Bulan September, Siswa/i: ', 6, '0024528978', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(576, 'Hapus Pembayaran Bulan Oktober, Siswa/i: ', 6, '0024528978', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(577, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0030428420', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(578, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0030428420', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(579, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0031489732', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(580, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0031489732', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(581, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0096317297', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(582, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0096317297', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(583, 'Hapus Pembayaran Bulan September, Siswa/i: ', 6, '0096317297', '2025-11-25 13:02:42', '2025-11-25 13:02:42'),
(584, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0095880080', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(585, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0095880080', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(586, 'Hapus Pembayaran Bulan September, Siswa/i: ', 6, '0095880080', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(587, 'Hapus Pembayaran Bulan Oktober, Siswa/i: ', 6, '0095880080', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(588, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0095591431', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(589, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0095591431', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(590, 'Hapus Pembayaran Bulan September, Siswa/i: ', 6, '0095591431', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(591, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0095436105', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(592, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0095436105', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(593, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0094685585', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(594, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0039584160', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(595, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0039584160', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(596, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0094604840', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(597, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0094604840', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(598, 'Hapus Pembayaran Bulan September, Siswa/i: ', 6, '0094604840', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(599, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0049729895', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(600, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0049729895', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(601, 'Hapus Pembayaran Bulan September, Siswa/i: ', 6, '0049729895', '2025-11-25 13:02:58', '2025-11-25 13:02:58'),
(602, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0053781192', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(603, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0093381634', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(604, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0093381634', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(605, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0093148402', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(606, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0093148402', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(607, 'Hapus Pembayaran Bulan September, Siswa/i: ', 6, '0093148402', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(608, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0093033717', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(609, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0093033717', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(610, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0085933191', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(611, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0084300534', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(612, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0082151639', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(613, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0082151639', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(614, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0079428260', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(615, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0079428260', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(616, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0078077509', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(617, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0076229530', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(618, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0066236060', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(619, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0066236060', '2025-11-25 13:03:09', '2025-11-25 13:03:09'),
(620, 'Hapus Pembayaran Bulan September, Siswa/i: ', 6, '0066236060', '2025-11-25 13:03:17', '2025-11-25 13:03:17'),
(621, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0064924424', '2025-11-25 13:03:17', '2025-11-25 13:03:17'),
(622, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0064924424', '2025-11-25 13:03:17', '2025-11-25 13:03:17'),
(623, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0064604313', '2025-11-25 13:03:17', '2025-11-25 13:03:17'),
(624, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0060950030', '2025-11-25 13:03:17', '2025-11-25 13:03:17'),
(625, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0003530465', '2025-11-25 13:03:17', '2025-11-25 13:03:17'),
(626, 'Hapus Pembayaran Bulan September, Siswa/i: ', 6, '0003530465', '2025-11-25 13:03:17', '2025-11-25 13:03:17'),
(627, 'Hapus Pembayaran Bulan Oktober, Siswa/i: ', 6, '0003530465', '2025-11-25 13:03:17', '2025-11-25 13:03:17'),
(628, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0085682025', '2025-11-25 13:03:30', '2025-11-25 13:03:30'),
(629, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0085682025', '2025-11-25 13:03:30', '2025-11-25 13:03:30'),
(630, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 6, '0055640859', '2025-11-25 13:03:30', '2025-11-25 13:03:30'),
(631, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 6, '0055640859', '2025-11-25 13:03:30', '2025-11-25 13:03:30'),
(632, 'Hapus Pembayaran Bulan Juli, Siswa/i: ', 8, '0050534585', '2025-11-25 13:03:30', '2025-11-25 13:03:30'),
(633, 'Hapus Pembayaran Bulan Agustus, Siswa/i: ', 8, '0050534585', '2025-11-25 13:03:30', '2025-11-25 13:03:30'),
(634, 'Melakukan logout', 3, NULL, '2025-11-25 13:24:25', '2025-11-25 13:24:25'),
(635, 'Melakukan login', NULL, '0003530465', '2025-11-25 13:24:37', '2025-11-25 13:24:37'),
(636, 'Melakukan login', NULL, '0003530465', '2025-11-26 00:41:29', '2025-11-26 00:41:29'),
(637, 'Melakukan logout', NULL, '0003530465', '2025-11-26 00:52:00', '2025-11-26 00:52:00'),
(638, 'Melakukan login', NULL, '0030428420', '2025-11-26 00:52:08', '2025-11-26 00:52:08'),
(639, 'Melakukan logout', NULL, '0030428420', '2025-11-26 00:54:49', '2025-11-26 00:54:49'),
(640, 'Melakukan login', 5, NULL, '2025-11-26 00:55:14', '2025-11-26 00:55:14'),
(641, 'Melakukan logout', 5, NULL, '2025-11-26 01:07:25', '2025-11-26 01:07:25'),
(642, 'Melakukan login', NULL, '0007032717', '2025-11-26 01:07:31', '2025-11-26 01:07:31'),
(643, 'Melakukan logout', NULL, '0007032717', '2025-11-26 01:08:05', '2025-11-26 01:08:05'),
(644, 'Melakukan login', 7, NULL, '2025-11-26 01:43:05', '2025-11-26 01:43:05'),
(645, 'Melakukan login', 6, NULL, '2025-11-26 02:20:12', '2025-11-26 02:20:12'),
(646, 'Melakukan login', 6, NULL, '2025-11-26 02:20:13', '2025-11-26 02:20:13'),
(647, 'Melakukan login', 2, NULL, '2025-11-26 02:24:53', '2025-11-26 02:24:53'),
(648, 'Melakukan login', 2, NULL, '2025-11-26 02:27:41', '2025-11-26 02:27:41'),
(649, 'Melakukan login', 2, NULL, '2025-11-26 02:28:26', '2025-11-26 02:28:26'),
(650, 'Melakukan login', 2, NULL, '2025-11-26 02:30:21', '2025-11-26 02:30:21'),
(651, 'Melakukan login', 2, NULL, '2025-11-26 02:31:28', '2025-11-26 02:31:28'),
(652, 'Melakukan login', 2, NULL, '2025-11-26 02:52:42', '2025-11-26 02:52:42'),
(653, 'Melakukan login', 2, NULL, '2025-11-26 02:55:49', '2025-11-26 02:55:49'),
(654, 'Melakukan login', 2, NULL, '2025-11-26 02:57:42', '2025-11-26 02:57:42'),
(655, 'Melakukan login', 2, NULL, '2025-11-26 02:58:54', '2025-11-26 02:58:54'),
(656, 'Melakukan login', 2, NULL, '2025-11-26 02:59:54', '2025-11-26 02:59:54'),
(657, 'Melakukan login', 2, NULL, '2025-11-26 03:01:02', '2025-11-26 03:01:02'),
(658, 'Melakukan login', 2, NULL, '2025-11-26 03:04:43', '2025-11-26 03:04:43'),
(659, 'Melakukan logout', 2, NULL, '2025-11-26 03:06:33', '2025-11-26 03:06:33'),
(660, 'Melakukan login', 2, NULL, '2025-11-26 03:11:32', '2025-11-26 03:11:32'),
(661, 'Melakukan logout', 7, NULL, '2025-11-26 03:11:44', '2025-11-26 03:11:44'),
(662, 'Melakukan login', NULL, '0000499987', '2025-11-26 03:12:03', '2025-11-26 03:12:03'),
(663, 'Melakukan logout', NULL, '0000499987', '2025-11-26 03:14:54', '2025-11-26 03:14:54');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2025_11_13_113216_create_kelas_table', 1),
(3, '2025_11_13_113349_create_spps_table', 1),
(4, '2025_11_13_113452_create_siswas_table', 1),
(5, '2025_11_13_113616_create_petugas_table', 1),
(6, '2025_11_13_113707_create_pembayarans_table', 1),
(7, '2025_11_21_124302_create_logs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_petugas` int NOT NULL,
  `nisn` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_bayar` date NOT NULL,
  `bulan_dibayar` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_dibayar` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_spp` int NOT NULL,
  `jumlah_bayar` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_petugas`, `nisn`, `tgl_bayar`, `bulan_dibayar`, `tahun_dibayar`, `id_spp`, `jumlah_bayar`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 6, '0003530465', '2025-07-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:21:30', '2025-11-25 13:02:42', NULL),
(9, 6, '0007032717', '2025-07-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:21:49', '2025-11-25 13:02:42', NULL),
(10, 6, '0007947311', '2025-07-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:22:22', '2025-11-25 13:02:42', NULL),
(13, 6, '0099125116', '2025-07-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:22:47', '2025-11-25 13:02:42', NULL),
(14, 6, '0011581563', '2025-07-01', 'Juli', '2024', 2, 220000, '2025-11-21 16:23:27', '2025-11-25 13:02:42', NULL),
(15, 6, '0011581563', '2025-07-01', 'Agustus', '2024', 2, 220000, '2025-11-21 16:23:27', '2025-11-25 13:02:42', NULL),
(16, 6, '0013342357', '2025-07-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:23:36', '2025-11-25 13:02:42', NULL),
(17, 6, '0018307471', '2025-07-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:23:47', '2025-11-25 13:02:42', NULL),
(18, 6, '0018307471', '2025-07-01', 'Agustus', '2025', 3, 250000, '2025-11-21 16:23:47', '2025-11-25 13:02:42', NULL),
(19, 6, '0018307471', '2025-07-01', 'September', '2025', 3, 250000, '2025-11-21 16:23:47', '2025-11-25 13:02:42', NULL),
(20, 6, '0018591476', '2025-07-01', 'Juli', '2024', 2, 220000, '2025-11-21 16:24:03', '2025-11-25 13:02:42', NULL),
(21, 6, '0018591476', '2025-07-01', 'Agustus', '2024', 2, 220000, '2025-11-21 16:24:03', '2025-11-25 13:02:42', NULL),
(22, 6, '0019866053', '2025-07-01', 'Juli', '2024', 2, 220000, '2025-11-21 16:24:11', '2025-11-25 13:02:42', NULL),
(23, 6, '0024528978', '2025-07-01', 'Juli', '2024', 2, 220000, '2025-11-21 16:24:23', '2025-11-25 13:02:42', NULL),
(24, 6, '0024528978', '2025-07-01', 'Agustus', '2024', 2, 220000, '2025-11-21 16:24:23', '2025-11-25 13:02:42', NULL),
(25, 6, '0024528978', '2025-07-01', 'September', '2024', 2, 220000, '2025-11-21 16:24:23', '2025-11-25 13:02:42', NULL),
(26, 6, '0024528978', '2025-07-01', 'Oktober', '2024', 2, 220000, '2025-11-21 16:24:23', '2025-11-25 13:02:42', NULL),
(27, 6, '0030428420', '2025-07-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:24:31', '2025-11-25 13:02:42', NULL),
(28, 6, '0030428420', '2025-07-01', 'Agustus', '2025', 3, 250000, '2025-11-21 16:24:31', '2025-11-25 13:02:42', NULL),
(29, 6, '0031489732', '2025-07-01', 'Juli', '2024', 2, 220000, '2025-11-21 16:24:41', '2025-11-25 13:02:42', NULL),
(30, 6, '0031489732', '2025-07-01', 'Agustus', '2024', 2, 220000, '2025-11-21 16:24:41', '2025-11-25 13:02:42', NULL),
(33, 6, '0096317297', '2025-07-01', 'Juli', '2024', 2, 220000, '2025-11-21 16:25:00', '2025-11-25 13:02:42', NULL),
(34, 6, '0096317297', '2025-07-01', 'Agustus', '2024', 2, 220000, '2025-11-21 16:25:00', '2025-11-25 13:02:42', NULL),
(35, 6, '0096317297', '2025-07-01', 'September', '2024', 2, 220000, '2025-11-21 16:25:00', '2025-11-25 13:02:42', NULL),
(36, 6, '0095880080', '2025-08-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:25:11', '2025-11-25 13:02:58', NULL),
(37, 6, '0095880080', '2025-08-01', 'Agustus', '2025', 3, 250000, '2025-11-21 16:25:11', '2025-11-25 13:02:58', NULL),
(38, 6, '0095880080', '2025-08-01', 'September', '2025', 3, 250000, '2025-11-21 16:25:11', '2025-11-25 13:02:58', NULL),
(39, 6, '0095880080', '2025-08-01', 'Oktober', '2025', 3, 250000, '2025-11-21 16:25:11', '2025-11-25 13:02:58', NULL),
(40, 6, '0095591431', '2025-08-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:25:21', '2025-11-25 13:02:58', NULL),
(41, 6, '0095591431', '2025-08-01', 'Agustus', '2025', 3, 250000, '2025-11-21 16:25:21', '2025-11-25 13:02:58', NULL),
(42, 6, '0095591431', '2025-08-01', 'September', '2025', 3, 250000, '2025-11-21 16:25:21', '2025-11-25 13:02:58', NULL),
(43, 6, '0095436105', '2025-08-01', 'Juli', '2024', 2, 220000, '2025-11-21 16:25:31', '2025-11-25 13:02:58', NULL),
(44, 6, '0095436105', '2025-08-01', 'Agustus', '2024', 2, 220000, '2025-11-21 16:25:31', '2025-11-25 13:02:58', NULL),
(45, 6, '0094685585', '2025-08-01', 'Juli', '2024', 2, 220000, '2025-11-21 16:25:40', '2025-11-25 13:02:58', NULL),
(46, 6, '0039584160', '2025-08-01', 'Juli', '2024', 2, 220000, '2025-11-21 16:25:52', '2025-11-25 13:02:58', NULL),
(47, 6, '0039584160', '2025-08-01', 'Agustus', '2024', 2, 220000, '2025-11-21 16:25:52', '2025-11-25 13:02:58', NULL),
(48, 6, '0094604840', '2025-08-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:26:12', '2025-11-25 13:02:58', NULL),
(49, 6, '0094604840', '2025-08-01', 'Agustus', '2025', 3, 250000, '2025-11-21 16:26:12', '2025-11-25 13:02:58', NULL),
(50, 6, '0094604840', '2025-08-01', 'September', '2025', 3, 250000, '2025-11-21 16:26:12', '2025-11-25 13:02:58', NULL),
(51, 6, '0049729895', '2025-08-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:26:29', '2025-11-25 13:02:58', NULL),
(52, 6, '0049729895', '2025-08-01', 'Agustus', '2025', 3, 250000, '2025-11-21 16:26:29', '2025-11-25 13:02:58', NULL),
(53, 6, '0049729895', '2025-08-01', 'September', '2025', 3, 250000, '2025-11-21 16:26:29', '2025-11-25 13:02:58', NULL),
(56, 6, '0053781192', '2025-09-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:27:06', '2025-11-25 13:03:09', NULL),
(57, 6, '0093381634', '2025-09-01', 'Juli', '2024', 2, 220000, '2025-11-21 16:27:18', '2025-11-25 13:03:09', NULL),
(58, 6, '0093381634', '2025-09-01', 'Agustus', '2024', 2, 220000, '2025-11-21 16:27:18', '2025-11-25 13:03:09', NULL),
(59, 6, '0093148402', '2025-09-01', 'Juli', '2024', 2, 220000, '2025-11-21 16:27:28', '2025-11-25 13:03:09', NULL),
(60, 6, '0093148402', '2025-09-01', 'Agustus', '2024', 2, 220000, '2025-11-21 16:27:28', '2025-11-25 13:03:09', NULL),
(61, 6, '0093148402', '2025-09-01', 'September', '2024', 2, 220000, '2025-11-21 16:27:28', '2025-11-25 13:03:09', NULL),
(62, 6, '0093033717', '2025-09-01', 'Juli', '2024', 2, 220000, '2025-11-21 16:27:36', '2025-11-25 13:03:09', NULL),
(63, 6, '0093033717', '2025-09-01', 'Agustus', '2024', 2, 220000, '2025-11-21 16:27:36', '2025-11-25 13:03:09', NULL),
(64, 6, '0085933191', '2025-09-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:27:48', '2025-11-25 13:03:09', NULL),
(65, 6, '0084300534', '2025-09-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:27:57', '2025-11-25 13:03:09', NULL),
(66, 6, '0082151639', '2025-09-01', 'Juli', '2024', 2, 220000, '2025-11-21 16:28:10', '2025-11-25 13:03:09', NULL),
(67, 6, '0082151639', '2025-09-01', 'Agustus', '2024', 2, 220000, '2025-11-21 16:28:10', '2025-11-25 13:03:09', NULL),
(71, 6, '0079428260', '2025-09-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:28:43', '2025-11-25 13:03:09', NULL),
(72, 6, '0079428260', '2025-09-01', 'Agustus', '2025', 3, 250000, '2025-11-21 16:28:43', '2025-11-25 13:03:09', NULL),
(73, 6, '0078077509', '2025-09-01', 'Juli', '2024', 2, 220000, '2025-11-21 16:28:56', '2025-11-25 13:03:09', NULL),
(74, 6, '0076229530', '2025-09-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:29:13', '2025-11-25 13:03:09', NULL),
(84, 6, '0066236060', '2025-09-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:30:02', '2025-11-25 13:03:09', NULL),
(85, 6, '0066236060', '2025-09-01', 'Agustus', '2025', 3, 250000, '2025-11-21 16:30:02', '2025-11-25 13:03:09', NULL),
(86, 6, '0066236060', '2025-09-01', 'September', '2025', 3, 250000, '2025-11-21 16:30:02', '2025-11-25 13:03:17', NULL),
(87, 6, '0064924424', '2025-09-01', 'Juli', '2024', 2, 220000, '2025-11-21 16:30:11', '2025-11-25 13:03:17', NULL),
(88, 6, '0064924424', '2025-09-01', 'Agustus', '2024', 2, 220000, '2025-11-21 16:30:11', '2025-11-25 13:03:17', NULL),
(89, 6, '0064604313', '2025-09-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:30:29', '2025-11-25 13:03:17', NULL),
(90, 6, '0060950030', '2025-09-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:30:43', '2025-11-25 13:03:17', NULL),
(93, 6, '0003530465', '2025-09-01', 'Agustus', '2025', 3, 250000, '2025-11-21 16:31:44', '2025-11-25 13:03:17', NULL),
(94, 6, '0003530465', '2025-09-01', 'September', '2025', 3, 250000, '2025-11-21 16:31:44', '2025-11-25 13:03:17', NULL),
(95, 6, '0003530465', '2025-09-01', 'Oktober', '2025', 3, 250000, '2025-11-21 16:31:44', '2025-11-25 13:03:17', NULL),
(98, 6, '0085682025', '2025-10-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:32:13', '2025-11-25 13:03:30', NULL),
(99, 6, '0085682025', '2025-10-01', 'Agustus', '2025', 3, 250000, '2025-11-21 16:32:13', '2025-11-25 13:03:30', NULL),
(100, 6, '0055640859', '2025-10-01', 'Juli', '2025', 3, 250000, '2025-11-21 16:32:39', '2025-11-25 13:03:30', NULL),
(101, 6, '0055640859', '2025-10-01', 'Agustus', '2025', 3, 250000, '2025-11-21 16:32:39', '2025-11-25 13:03:30', NULL),
(104, 8, '0050534585', '2025-10-01', 'Juli', '2025', 3, 250000, '2025-11-21 17:28:44', '2025-11-25 13:03:30', NULL),
(105, 8, '0050534585', '2025-10-01', 'Agustus', '2025', 3, 250000, '2025-11-21 17:28:44', '2025-11-25 13:03:30', NULL),
(106, 2, '0000499987', '2025-11-24', 'Juli', '2023', 1, 200000, '2025-11-24 01:55:35', '2025-11-24 01:55:35', NULL),
(107, 2, '0000499987', '2025-11-24', 'Agustus', '2023', 1, 200000, '2025-11-24 01:55:35', '2025-11-24 01:55:35', NULL),
(108, 2, '0000499987', '2025-11-24', 'September', '2023', 1, 200000, '2025-11-24 01:55:35', '2025-11-24 01:55:44', '2025-11-24 01:55:44'),
(109, 2, '0031587670', '2025-11-24', 'Juli', '2023', 1, 200000, '2025-11-24 01:57:05', '2025-11-24 01:57:05', NULL),
(110, 2, '0031587670', '2025-11-24', 'Agustus', '2023', 1, 200000, '2025-11-24 01:57:05', '2025-11-24 01:57:05', NULL),
(111, 2, '0066815417', '2025-11-24', 'Juli', '2023', 1, 200000, '2025-11-24 01:57:26', '2025-11-24 01:57:26', NULL),
(112, 2, '0079568927', '2025-11-24', 'Juli', '2023', 1, 200000, '2025-11-24 01:57:36', '2025-11-24 01:57:36', NULL),
(113, 2, '0079568927', '2025-11-24', 'Agustus', '2023', 1, 200000, '2025-11-24 01:57:36', '2025-11-24 01:57:36', NULL),
(114, 2, '0079568927', '2025-11-24', 'September', '2023', 1, 200000, '2025-11-24 01:57:36', '2025-11-24 01:57:36', NULL),
(115, 2, '0070078544', '2025-11-24', 'Juli', '2023', 1, 200000, '2025-11-24 01:57:50', '2025-11-24 01:57:50', NULL),
(116, 2, '0009402859', '2025-11-24', 'Juli', '2023', 1, 200000, '2025-11-24 01:58:04', '2025-11-24 01:58:04', NULL),
(117, 2, '0009402859', '2025-11-24', 'Agustus', '2023', 1, 200000, '2025-11-24 01:58:04', '2025-11-24 01:58:04', NULL),
(118, 2, '0073718700', '2025-11-24', 'Juli', '2023', 1, 200000, '2025-11-24 01:58:17', '2025-11-24 01:58:17', NULL),
(119, 2, '0073718700', '2025-11-24', 'Agustus', '2023', 1, 200000, '2025-11-24 01:58:17', '2025-11-24 01:58:17', NULL),
(120, 2, '0050607639', '2025-11-24', 'Juli', '2023', 1, 200000, '2025-11-24 01:58:34', '2025-11-24 01:58:34', NULL),
(121, 11, '0004245058', '2025-11-24', 'Juli', '2023', 1, 200000, '2025-11-24 02:05:39', NULL, NULL),
(122, 11, '0004245058', '2025-11-24', 'Agustus', '2023', 1, 200000, '2025-11-24 02:05:39', NULL, NULL),
(123, 7, '0003530465', '2025-11-24', 'November', '2025', 3, 250000, '2025-11-24 12:15:08', '2025-11-24 12:15:08', NULL),
(124, 7, '0003530465', '2025-11-24', 'Desember', '2025', 3, 250000, '2025-11-24 12:15:08', '2025-11-24 12:15:08', NULL),
(125, 7, '0003530465', '2025-11-24', 'Januari', '2025', 3, 250000, '2025-11-24 12:15:08', '2025-11-24 12:15:08', NULL),
(126, 7, '0003530465', '2025-11-24', 'Februari', '2025', 3, 250000, '2025-11-24 12:15:08', '2025-11-24 12:15:08', NULL),
(127, 7, '0003530465', '2025-11-24', 'Maret', '2025', 3, 250000, '2025-11-24 12:15:08', '2025-11-24 12:15:08', NULL),
(128, 7, '0003530465', '2025-11-24', 'April', '2025', 3, 250000, '2025-11-24 12:15:08', '2025-11-24 12:15:08', NULL),
(129, 7, '0003530465', '2025-11-24', 'Mei', '2025', 3, 250000, '2025-11-24 12:15:08', '2025-11-24 12:15:08', NULL),
(130, 7, '0003530465', '2025-11-24', 'Juni', '2025', 3, 250000, '2025-11-24 12:15:08', '2025-11-24 12:15:08', NULL),
(131, 7, '0000499987', '2025-11-24', 'September', '2023', 1, 200000, '2025-11-24 12:17:13', '2025-11-24 12:17:13', NULL),
(132, 7, '0000499987', '2025-11-24', 'Oktober', '2023', 1, 200000, '2025-11-24 12:17:13', '2025-11-24 12:17:13', NULL),
(133, 7, '0000499987', '2025-11-24', 'November', '2023', 1, 200000, '2025-11-24 12:17:13', '2025-11-24 12:17:13', NULL),
(134, 7, '0000499987', '2025-11-24', 'Desember', '2023', 1, 200000, '2025-11-24 12:17:13', '2025-11-24 12:17:13', NULL),
(135, 7, '0000499987', '2025-11-24', 'Januari', '2023', 1, 200000, '2025-11-24 12:17:13', '2025-11-24 12:17:13', NULL),
(136, 7, '0000499987', '2025-11-24', 'Februari', '2023', 1, 200000, '2025-11-24 12:17:13', '2025-11-24 12:17:13', NULL),
(137, 7, '0000499987', '2025-11-24', 'Maret', '2023', 1, 200000, '2025-11-24 12:17:13', '2025-11-24 12:17:13', NULL),
(138, 7, '0000499987', '2025-11-24', 'April', '2023', 1, 200000, '2025-11-24 12:17:13', '2025-11-24 12:17:13', NULL),
(139, 7, '0000499987', '2025-11-24', 'Mei', '2023', 1, 200000, '2025-11-24 12:17:13', '2025-11-24 12:17:13', NULL),
(140, 7, '0000499987', '2025-11-24', 'Juni', '2023', 1, 200000, '2025-11-24 12:17:13', '2025-11-24 12:17:13', NULL);

--
-- Triggers `pembayaran`
--
DELIMITER $$
CREATE TRIGGER `log_bayar` AFTER INSERT ON `pembayaran` FOR EACH ROW INSERT INTO logs VALUES (NULL, CONCAT('Input Pembayaran Bulan ', NEW.bulan_dibayar, ', Siswa/i: '), NEW.id_petugas, NEW.nisn, NOW(), NOW())
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_hapus_bayar` AFTER UPDATE ON `pembayaran` FOR EACH ROW INSERT INTO logs VALUES (NULL, CONCAT('Hapus Pembayaran Bulan ', NEW.bulan_dibayar, ', Siswa/i: '), NEW.id_petugas, NEW.nisn, NOW(), NOW())
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` int NOT NULL,
  `username` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_petugas` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` enum('admin','petugas') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `username`, `password`, `nama_petugas`, `level`, `created_at`, `updated_at`) VALUES
(1, 'wani80', '$2y$10$ipjbXO3b1ot0AMeVXKpqoum8x3unI/LRAqCEZjTnuvgkp12OX2Ipi', 'Uli Mayasari', 'petugas', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(2, 'endah81', '$2y$10$q560enhcRY2LEMXpXCEQ/OK0n9lc4xlSvC13XsTtUmwKt7gAhQ4VG', 'Gilang Wahyudin M.M.', 'admin', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(3, 'kamila47', '$2y$10$tcSwf5tvitVza2UYz7BJGuNQn2Y5BCZto8xeEdyv.jId4wPAa3kwK', 'Pranata Prakasa', 'admin', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(4, 'mulya01', '$2y$10$zSesc7TIdnPTyZyV0VIt9.p2BA4lgbLceDyL/QKFc00A0dklFVyGq', 'Harja Lazuardi', 'admin', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(5, 'wijaya.umi', '$2y$10$e8eP/RODWIV2U1AmaRzxlOkIRz6wcrzwXS6er6u5aYS6.sNg5UtqG', 'Taswir Cahyono Hakim S.IP', 'admin', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(6, 'ilsa07', '$2y$10$ILpAaqiqLiyX0oIoRi2H8eA0X1rS74ZYtbq0rrkOIJWM68ld.8OpC', 'Tasdik Winarno', 'admin', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(7, 'gandi.januar', '$2y$10$SonG4dwMQQs9C9R2qlIPuuq5NaPYNXAZ47Ro5d/wfjvrrNRfWMV4y', 'Diah Usyi Mardhiyah', 'admin', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(8, 'wani84', '$2y$10$uywutpjdrQ4hAvlSkSWwlOAIG0z93nr32FqvKPdwsL.Hlk002funi', 'Bagiya Hutapea S.IP', 'admin', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(9, 'jasmani.susanti', '$2y$10$OAWiT1ZH0EjtPQuVToPjz.KuHyLnOXkV/rTZwZmVU3bbnn1btDsXi', 'Artanto Rusman Januar', 'admin', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(10, 'timbul17', '$2y$10$U8R10n/JWTNjkmKV8FwDleB9.pCcwFeKyQdjaprC/WigGhLScAr0G', 'Okta Tamba', 'petugas', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(11, 'kayun63', '$2y$10$uuE1ZiaHvbfB9jMlSUTJJ.4rCkoxNHWU58qIzeFwmjaYRa7VsUmDy', 'Hasna Eka Nasyiah', 'petugas', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(12, 'asirwanda95', '$2y$10$of1hDZ3MbF.1G83kdIdUhe5rNAbCSjGKOp28rYFUi3zziGE0od.DS', 'Ismail Pranowo M.Farm', 'petugas', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(13, 'dian.hasanah', '$2y$10$xRYx/AIiGv0RNmvVNou/COFD3L1nWMvgth8lfEZ8T2izQ1DTLQeoG', 'Vanya Suartini M.M.', 'petugas', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(14, 'aryani.umi', '$2y$10$QyZsnmCA3.J5qkCaxuLp9eo8lXBBODJGiCSj78GsweSv9HIqZAKzy', 'Danu Alambana Suryono M.Ak', 'petugas', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(15, 'susanti.yessi', '$2y$10$WbmgUlGydfUXRDFnzQhMb.QPSGEiGpnlUlBnvssfOt0fL5ABrTmuy', 'Capa Vino Sitorus S.Pt', 'petugas', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(16, 'atmaja29', '$2y$10$XSVXlTg3FiNxLnq/Sfi2Zu1bf/rrspU7awSwmW0QjWYmpeGhp8tba', 'Ega Sinaga', 'petugas', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(17, 'hgunarto', '$2y$10$66XpTEX2e0yiX5/.cUVxNuoqHOoqeDZhmL7aFD/EIvwf.xoWYSb7q', 'Zalindra Olivia Rahayu M.Kom.', 'admin', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(18, 'panggraini', '$2y$10$dk/R283OMaOaWe85z755CejSx1B4THGHDf2SPB5Hv1xtPhR9SJqFK', 'Gaduh Hutagalung', 'petugas', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(19, 'julia.hartati', '$2y$10$9wRKcaz5Q.3IaXTN2itZrOnKCK0zWm7SAXzwYxZ/uxWwmfohRijv6', 'Anita Mandasari S.Sos', 'admin', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(20, 'mpuspasari', '$2y$10$EDiMlmQlD8A8UQxhhmWiF.Cdi5BtpaGogLvzkKJS3xVr5JfblZW3q', 'Jati Nainggolan', 'petugas', '2025-11-21 12:43:53', '2025-11-21 12:43:53'),
(21, 'dapap', '$2y$10$vU.q6JdLOw8m2FM8W7cbIudeVZBG6z7V4Cm3.7jeTB0ulRV4ryVB6', 'Daffa Dhaifullah Ahmad', 'petugas', '2025-11-24 01:49:26', '2025-11-24 01:50:54');

-- --------------------------------------------------------

--
-- Stand-in structure for view `riwayat_pembayaran`
-- (See below for the actual view)
--
CREATE TABLE `riwayat_pembayaran` (
`bulan_dibayar` bigint
,`id_kelas` int
,`id_spp` int
,`kompetensi_keahlian` varchar(50)
,`nama` varchar(35)
,`nama_kelas` varchar(10)
,`nis` char(8)
,`nisn` char(10)
,`nominal` int
,`tahun` varchar(11)
,`total_bayar` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `nisn` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nis` char(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kelas` int NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_spp` int NOT NULL,
  `username` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`nisn`, `nis`, `nama`, `id_kelas`, `alamat`, `no_telp`, `id_spp`, `username`, `password`, `created_at`, `updated_at`) VALUES
('0000499987', '10179516', 'Jelita Yolanda S.Kom', 2, 'Kpg. Otto No. 38, Palembang 21063, Sulteng', '088352309942', 1, 'sihotang.limar', '$2y$10$ie.YUoPdzTEzSsB8SjtXQ.qlRhSXGo3rddTgUXJX6hjDS3BW/p31K', '2025-11-21 12:43:52', '2025-11-24 12:02:59'),
('0003530465', '10734867', 'Koko Pratama M.Ak', 8, 'Gg. Basmol Raya No. 997, Pontianak 35627, Pabar', '086594579234', 3, 'ika.puspasari', '$2y$10$blVGI8Q8Ds5ZE9SNsKYiL.76FDpdnLF2n3JKThewsXlWGVi2tVRXO', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0004245058', '10620990', 'Jarwadi Rajata', 2, 'Jr. HOS. Cjokroaminoto (Pasirkaliki) No. 80, Sawahlunto 36031, Bali', '080102963447', 1, 'dina.manullang', '$2y$10$OmVZV0rGaaxdUlXazasSJO..MSJvVD8YgjatMneF4WVbUs6TCgHXm', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0007032717', '10792520', 'Ulva Safitri', 7, 'Jln. HOS. Cjokroaminoto (Pasirkaliki) No. 736, Semarang 45982, Papua', '084995443976', 3, 'usyi.irawan', '$2y$10$7iyJzSnx2FcFjQXvjDLEg.FLLn8VHzTfuPsutLB6w9Z2.Y9RDXfsa', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0007947311', '10349407', 'Clara Qori Hartati', 6, 'Kpg. Suryo Pranoto No. 555, Bitung 56313, Maluku', '080754138284', 3, 'cinthia.zulaika', '$2y$10$rOpPymCHk9kj7.HILS13weoS7OWm.Kn676Op8iC3MHlZcNj0/1Zgy', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0009402859', '10787309', 'Usman Pradipta', 2, 'Ds. Bank Dagang Negara No. 399, Padangpanjang 32398, Sulut', '080907916188', 1, 'chassanah', '$2y$10$zTrrNcLpRXc6598XFVDkR.HV3ODGDkFSjxa3YKHFDmgxQx03UnkSy', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0011581563', '10053422', 'Faizah Rahayu Andriani', 3, 'Jln. Badak No. 592, Binjai 55967, Pabar', '087936321462', 2, 'zpuspasari', '$2y$10$4XQFDn7S.HySZ80EQpWIt.EwwZmchal1O87hck3Z7Cv8jD7kdtFQe', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0013342357', '10533363', 'Perkasa Prakasa', 7, 'Ds. Flores No. 23, Ambon 26045, Kalsel', '089746220647', 3, 'twibowo', '$2y$10$YKsjxGMNfLmyF3/7LomFW.msM2C07.ohHagCS8YXYjytNXShYeRiK', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0018307471', '10601347', 'Nardi Taswir Ramadan', 9, 'Ki. Peta No. 208, Serang 37299, Jambi', '083273373264', 3, 'qrahayu', '$2y$10$ntyRoKJujkhRhri../SFVezPvm/omUb0Bk4MSOMH6G9Cy2L/wHw5y', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0018591476', '10290610', 'Gilda Hassanah S.I.Kom', 4, 'Dk. Bhayangkara No. 796, Bengkulu 85788, Sulteng', '082049513070', 2, 'wijaya.cahyadi', '$2y$10$KUnH8vHKV221WGKpkE1SKO8Ph546GOy/IW79hFdA9cUV5dSpK88xi', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0019866053', '10935839', 'Yuni Wahyuni', 4, 'Dk. Dr. Junjunan No. 217, Depok 63176, NTT', '085888061234', 2, 'nadine.halimah', '$2y$10$zT58AV5V5WsgKGLpKN4b8OOPFU14oG/AjQw9spapl14DG/Eh5Bkfm', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0024528978', '10338826', 'Jaswadi Eluh Siregar M.Farm', 4, 'Dk. Ikan No. 661, Pagar Alam 72902, NTB', '087913026464', 2, 'haryanto.jasmin', '$2y$10$yQhwIzonjZriuzNrLT5WQe.FVzTni8vPYjvHJ4Pro9tfcDBGl4l0m', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0030428420', '10592193', 'Cayadi Prasasta', 7, 'Ds. Asia Afrika No. 791, Kendari 60920, NTT', '081845388233', 3, 'safitri.ikhsan', '$2y$10$UpE9EJoK3oCwNbgKOBqKneyBcaGehYUBngNw7evmMEaoy3BKuHeeu', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0031489732', '10817611', 'Arta Zulkarnain', 5, 'Gg. Diponegoro No. 659, Palopo 24411, Bali', '080863286095', 2, 'wpuspita', '$2y$10$uzTW.VrRHmOISi8zfL6RaOl.PXJz18lFUC4R.0lYJyj3XtQljEUIm', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0031587670', '10594155', 'Kamidin Suryono', 1, 'Jr. Thamrin No. 685, Lubuklinggau 80275, Kaltara', '081917254044', 1, 'uyulianti', '$2y$10$TwIraZ/K4mV.a3inh870YuuMEKWdivytzWReyyq1FKO8StslCPkrG', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0039584160', '10799190', 'Eli Rahmawati S.H.', 4, 'Jln. Mahakam No. 423, Administrasi Jakarta Utara 70985, Jabar', '089752032041', 2, 'uyainah.gantar', '$2y$10$TVK6fWjuIwyl/.nqS5/MUOROo8af8BOEjrZEXVz4f1SW1YWhvX0GC', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0049729895', '10321123', 'Wulan Lailasari', 9, 'Dk. Bara No. 192, Bandung 85001, DIY', '081851820448', 3, 'satya.nurdiyanti', '$2y$10$4xbbYTDounffG92BRa/TcOoUio1LW4b4ShTclFrbTKyNltzelznle', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0050534585', '10415469', 'Ella Prastuti', 9, 'Gg. Elang No. 15, Parepare 12518, Gorontalo', '088688737743', 3, 'ikhsan43', '$2y$10$wms/xxfxD5E7GfWJWa716.Alflvq8rpJTwM/4IOy97n0UGlYy4fQ6', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0050607639', '10860635', 'Endah Nasyiah', 2, 'Kpg. Siliwangi No. 706, Padangpanjang 11456, Sulsel', '080049300710', 1, 'yulia.haryanti', '$2y$10$WbhtlzBYRej9fnrlS6L/Rup9NwLBiVtIuJxRyAXs8An4LNdT4UHg.', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0053781192', '10226922', 'Anita Aryani', 9, 'Gg. Bacang No. 267, Denpasar 15720, Sumbar', '088577034939', 3, 'jmandala', '$2y$10$jptPnOen3DCgp2CPGqtq...RrsBKJQ9tulUqM759csG/.tiT0Py6S', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0055640859', '10073369', 'Lala Pudjiastuti', 9, 'Psr. Jakarta No. 884, Lubuklinggau 95362, Sumut', '087581217878', 3, 'mardhiyah.hesti', '$2y$10$fmNt0uAO4T2.VpsQ40UoF.wg84RhWQJgHC4J8Db/LM6ItgNO2oAQa', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0059978990', '10747347', 'Nadine Hariyah', 2, 'Ds. Diponegoro No. 132, Gunungsitoli 78669, Bengkulu', '082110313023', 1, 'epadmasari', '$2y$10$xYmm/bRvd1Ho2dbQuriJKugFlGP.8GX1e6sus7SmPj0Lwl2E52mQG', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0060950030', '10991583', 'Elma Haryanti', 6, 'Psr. Sudirman No. 932, Pontianak 13863, Banten', '087477196120', 3, 'tuwais', '$2y$10$l9FIK0uW1rh5symiNJZgTuw3bbSlbsAR.duMDwbsom5pbJ0Jtd5q2', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0063769540', '10451237', 'Argono Suryono M.Ak', 9, 'Psr. Sutarjo No. 555, Administrasi Jakarta Pusat 95347, Banten', '082068450644', 3, 'lega.utami', '$2y$10$Ml.vWkTDnHi9u5OHnQcnUuOgGj2bE7JJhvHxpjTkB7KUkPQ59nSu2', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0064604313', '10195932', 'Rina Prastuti', 8, 'Jln. Babakan No. 947, Palu 66793, Sulbar', '086576777227', 3, 'mandasari.jumari', '$2y$10$0XXQR2lUf8eX/TqUNnM/0uLxh9nEW1/bewLaIM7/RZAsSWuM5.Eka', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0064924424', '10720554', 'Genta Widiastuti', 5, 'Jr. Panjaitan No. 92, Lhokseumawe 86268, Pabar', '081642693043', 2, 'tami54', '$2y$10$cEsln3tLIRSjKhvb9jV39Od2SCzwqOqMBRaLlqKzmkM5uPnaml542', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0066236060', '10170773', 'Jayadi Wahyudin', 6, 'Ds. Rumah Sakit No. 482, Sukabumi 97400, Kalsel', '085868504066', 3, 'dprasetyo', '$2y$10$Gh7JCT4ZDdnYMNUuNAwuPuukdXUu4oAAqOFQv.ZdlQD/KiYOotu1W', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0066815417', '10612903', 'Lintang Sarah Usamah M.M.', 1, 'Ki. Cut Nyak Dien No. 681, Bekasi 40894, NTT', '087067232637', 1, 'aris36', '$2y$10$yRAK1Nag7YThM2wS3g4o2.Js4JD36rACbp8.KeiHs1v3QHSy4Hb8O', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0070078544', '10321443', 'Jelita Oktaviani', 2, 'Kpg. Wahidin Sudirohusodo No. 898, Tebing Tinggi 79359, Jambi', '082666203180', 1, 'lurhur03', '$2y$10$4eCLJ1totZwl/scuNzHiKORTtfjWMxXSV6lXZN.ZSmlePFrj6hFsu', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0073718700', '10051527', 'Kawaca Latupono S.T.', 2, 'Kpg. Achmad Yani No. 869, Kupang 51692, Kaltim', '087353180745', 1, 'imam58', '$2y$10$WOYJ8JDj8vUsyQu0hd5D8e/jy/SR3KjJUc519C4AOmt8x6Cq8zTDy', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0076229530', '10181056', 'Kamidin Winarno', 7, 'Kpg. Astana Anyar No. 279, Administrasi Jakarta Pusat 23239, Aceh', '088835513066', 3, 'rwastuti', '$2y$10$f3Uz5ISyXMayS2W5qhVtf.hUEIbCqrMZmzvSPl08JevYyYPKirjgi', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0076887575', '10798541', 'Samiah Yolanda M.Farm', 6, 'Gg. Halim No. 44, Tangerang Selatan 84058, Sumbar', '089062059882', 3, 'zulkarnain.amelia', '$2y$10$PVSZts9qnousyACzSOjjR.a.cdVRrkMHFg716krwKFRKsMNQ..ft6', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0078077509', '10352727', 'Oliva Rahmi Nasyidah', 4, 'Ki. Casablanca No. 885, Kupang 74008, DKI', '089413712334', 2, 'mujur.agustina', '$2y$10$05bVstDjaL./BIutISi/SuSPndwGAKv2sUAOV6m4XpM.r84KmKYmu', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0079428260', '10500898', 'Bella Novitasari', 6, 'Gg. Bara Tambar No. 319, Batam 93150, Sumut', '089432469909', 3, 'lhariyah', '$2y$10$gtwxMcA2daj3xsfnHLUh..4oIYI/b0iHYmXIJ1QTB5iwIIiUxsM3.', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0079568927', '10040451', 'Genta Agustina', 1, 'Ki. Supomo No. 721, Tanjung Pinang 58599, Sulteng', '082782845533', 1, 'diana62', '$2y$10$1AaR.wc6kFnVibGdMuCu2..5ulVcyn.wp4F8GQtgfGolfmEqgv7T6', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0082151639', '10116973', 'Salsabila Utami', 3, 'Ds. W.R. Supratman No. 639, Serang 41319, Banten', '087006279569', 2, 'wharyanti', '$2y$10$fZkl9.uBO1OD/DRD3Vunm.6j/FLApqo/MRBk1.ghCQ.vsBHiXRTj6', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0084300534', '10145516', 'Najwa Melani', 6, 'Gg. Baya Kali Bungur No. 931, Administrasi Jakarta Barat 58963, Kaltim', '088089314139', 3, 'kamidin75', '$2y$10$ysxwwiaIpcloevFVm6QknutUsKBndTbxKN448odup7TznTFdhgsqm', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0085682025', '10119676', 'Nurul Lestari', 7, 'Gg. Tangkuban Perahu No. 757, Batu 66655, Jambi', '086529254265', 3, 'arahayu', '$2y$10$EV4Nh9JRx.66IpfufV.N0eORNcH2ug0iKFVhVzl6m91sTGmpxDAEe', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0085933191', '10725434', 'Narji Viman Tamba S.Pt', 7, 'Gg. Sam Ratulangi No. 550, Bogor 21195, Aceh', '083289535367', 3, 'titi.permadi', '$2y$10$vB113xVrxsmYJFt.gOmYM.xki8FShHIlX7GCXi9gpnVD3IOct.dw6', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0093033717', '10633933', 'Hasna Hariyah', 5, 'Jln. Kalimalang No. 893, Gorontalo 90027, Gorontalo', '082964438233', 2, 'rika.yuliarti', '$2y$10$l6Eowj/yv3RB44711tiEN.QZAhX4fdECXCVvIfiCj.vtEQNYr2bji', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0093148402', '10060057', 'Hamima Susanti', 5, 'Jr. Babadak No. 177, Banda Aceh 53488, Sumbar', '086214466221', 2, 'awibisono', '$2y$10$Zv7Xu3D9GVH0BspyOqKQZ.tRVUkMQ1Ee2h3hMEVV7wIwcTS12TVey', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0093381634', '10286969', 'Hamzah Utama', 3, 'Jr. Pelajar Pejuang 45 No. 698, Singkawang 10708, Sumut', '083555899449', 2, 'indah.halim', '$2y$10$oP2Nk/qisJ9veB5bVIE6r.OHpcHkpzRU.awUXcV7NtUWn0isqlJki', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0094604840', '10905342', 'Indah Safitri', 6, 'Dk. Nangka No. 635, Kotamobagu 51833, Kepri', '084984009584', 3, 'cakrajiya.permadi', '$2y$10$yiCfwem4LhdIVzrM8EfjuOWyWINXPq5wzcbzi2Zlwjxbna4FwDaMa', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0094685585', '10417773', 'Sakti Lulut Firmansyah S.T.', 5, 'Ki. Tubagus Ismail No. 267, Payakumbuh 38196, Kaltim', '086283321984', 2, 'jaeman42', '$2y$10$EwAaPH1UHNgHJ038gbszFuJ/tcn5fufh/1qSeYqZKUCpy6/nx7J.K', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0095436105', '10047984', 'Chelsea Qori Mulyani S.E.', 5, 'Psr. Gatot Subroto No. 969, Pekalongan 52917, Jambi', '081177629338', 2, 'dsihombing', '$2y$10$6/vthcwOtoeMEmmFj63IceEv5XJcrq/aycSzyjWcISTe8vprj9nUW', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0095591431', '10828403', 'Elma Yuliarti', 7, 'Dk. Gardujati No. 331, Palangka Raya 12216, Kaltim', '088250024620', 3, 'gangsa49', '$2y$10$yeZrDZBSMRDXIuGluxIj9OjQp9lFxmJMhfEI1LF9kZuJ1sRs8OwI.', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0095880080', '10052626', 'Jumari Firgantoro S.Pt', 8, 'Ki. Barasak No. 284, Dumai 75586, Jateng', '085494925064', 3, 'salwa.saputra', '$2y$10$SZ13kzsQ4b7pvyqvm52kL.bjIkoWMW1AGgw32nLl7kgOAWZzfnFAu', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0096317297', '10655214', 'Mahmud Prasetya', 4, 'Ki. Cikutra Timur No. 972, Kediri 35786, Babel', '080036799873', 2, 'yance53', '$2y$10$TeLN26XtXUTExn25ZVUysOXtFSGGTq8MZ84Xs/d2mBjlqDezAo5de', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0098206573', '10817312', 'Timbul Umar Zulkarnain M.Ak', 1, 'Psr. Yap Tjwan Bing No. 260, Jambi 99189, Banten', '086530035134', 1, 'ella.pradana', '$2y$10$14zFb3./sHb4SUZ6w8KHVOS9cpKouapzJsaSJrrEwlIRTg.R23Ype', '2025-11-21 12:43:52', '2025-11-21 12:43:52'),
('0099125116', '10629967', 'Prakosa Kasiran Prakasa', 6, 'Dk. Tambak No. 801, Singkawang 75348, Jateng', '088940413052', 3, 'nurdiyanti.salsabila', '$2y$10$zexnLiQXLk4gvNPBPc9gw.h7fBO2/sthtxyZ1gNEuOxvfZCGWqote', '2025-11-21 12:43:52', '2025-11-21 12:43:52');

-- --------------------------------------------------------

--
-- Table structure for table `spp`
--

CREATE TABLE `spp` (
  `id_spp` int NOT NULL,
  `tahun` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spp`
--

INSERT INTO `spp` (`id_spp`, `tahun`, `nominal`, `created_at`, `updated_at`) VALUES
(1, '2023', 200000, '2025-11-21 12:43:47', '2025-11-21 12:43:47'),
(2, '2024', 220000, '2025-11-21 12:43:47', '2025-11-21 12:43:47'),
(3, '2025', 250000, '2025-11-21 12:43:47', '2025-11-21 12:43:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logs_id_petugas_foreign` (`id_petugas`),
  ADD KEY `logs_nisn_foreign` (`nisn`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `pembayaran_id_petugas_foreign` (`id_petugas`),
  ADD KEY `pembayaran_nisn_foreign` (`nisn`),
  ADD KEY `pembayaran_id_spp_foreign` (`id_spp`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`),
  ADD UNIQUE KEY `petugas_username_unique` (`username`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`nisn`),
  ADD UNIQUE KEY `siswa_username_unique` (`username`),
  ADD KEY `siswa_id_kelas_foreign` (`id_kelas`),
  ADD KEY `siswa_id_spp_foreign` (`id_spp`);

--
-- Indexes for table `spp`
--
ALTER TABLE `spp`
  ADD PRIMARY KEY (`id_spp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=664;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `spp`
--
ALTER TABLE `spp`
  MODIFY `id_spp` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

-- --------------------------------------------------------

--
-- Structure for view `dashboard_petugas`
--
DROP TABLE IF EXISTS `dashboard_petugas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dashboard_petugas`  AS SELECT (select count(0) from `siswa`) AS `total_siswa`, (select count(0) from `pembayaran` where (`pembayaran`.`deleted_at` is null)) AS `total_transaksi`, (select sum(`pembayaran`.`jumlah_bayar`) from `pembayaran` where ((`pembayaran`.`deleted_at` is null) and (cast(`pembayaran`.`tgl_bayar` as date) = curdate()))) AS `total_hari_ini` ;

-- --------------------------------------------------------

--
-- Structure for view `list_pembayaran`
--
DROP TABLE IF EXISTS `list_pembayaran`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `list_pembayaran`  AS SELECT `s`.`nisn` AS `nisn`, `s`.`nis` AS `nis`, `s`.`nama` AS `nama`, `k`.`id_kelas` AS `id_kelas`, `k`.`nama_kelas` AS `nama_kelas`, `k`.`kompetensi_keahlian` AS `kompetensi_keahlian`, `spp`.`nominal` AS `nominal`, (case when (count(`p`.`bulan_dibayar`) >= 12) then 'lunas' else 'belum lunas' end) AS `status_pembayaran` FROM (((`siswa` `s` join `kelas` `k` on((`k`.`id_kelas` = `s`.`id_kelas`))) join `spp` on((`spp`.`id_spp` = `s`.`id_spp`))) left join `pembayaran` `p` on((`p`.`nisn` = `s`.`nisn`))) GROUP BY `s`.`nisn`, `s`.`nis`, `s`.`nama`, `k`.`id_kelas`, `k`.`nama_kelas`, `k`.`kompetensi_keahlian`, `spp`.`nominal` ;

-- --------------------------------------------------------

--
-- Structure for view `riwayat_pembayaran`
--
DROP TABLE IF EXISTS `riwayat_pembayaran`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `riwayat_pembayaran`  AS SELECT `s`.`nisn` AS `nisn`, `s`.`nis` AS `nis`, `s`.`nama` AS `nama`, `k`.`id_kelas` AS `id_kelas`, `k`.`nama_kelas` AS `nama_kelas`, `k`.`kompetensi_keahlian` AS `kompetensi_keahlian`, `sp`.`id_spp` AS `id_spp`, `sp`.`nominal` AS `nominal`, `sp`.`tahun` AS `tahun`, if((sum(`p`.`jumlah_bayar`) is null),0,sum(`p`.`jumlah_bayar`)) AS `total_bayar`, count(`p`.`id_pembayaran`) AS `bulan_dibayar` FROM (((`siswa` `s` left join `kelas` `k` on((`k`.`id_kelas` = `s`.`id_kelas`))) left join `pembayaran` `p` on(((`p`.`nisn` = `s`.`nisn`) and (`p`.`deleted_at` is null)))) left join `spp` `sp` on((`sp`.`id_spp` = `s`.`id_spp`))) GROUP BY `s`.`nisn`, `s`.`nis`, `s`.`nama`, `k`.`id_kelas`, `k`.`nama_kelas`, `k`.`kompetensi_keahlian`, `sp`.`nominal`, `sp`.`tahun` ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_id_petugas_foreign` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`) ON DELETE CASCADE,
  ADD CONSTRAINT `logs_nisn_foreign` FOREIGN KEY (`nisn`) REFERENCES `siswa` (`nisn`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_spp`) REFERENCES `spp` (`id_spp`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pembayaran_id_petugas_foreign` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayaran_nisn_foreign` FOREIGN KEY (`nisn`) REFERENCES `siswa` (`nisn`) ON DELETE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_id_kelas_foreign` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE,
  ADD CONSTRAINT `siswa_id_spp_foreign` FOREIGN KEY (`id_spp`) REFERENCES `spp` (`id_spp`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
