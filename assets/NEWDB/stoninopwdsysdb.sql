-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2026 at 03:04 AM
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
-- Database: `stoninopwdsysdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admincreds`
--

CREATE TABLE `admincreds` (
  `ID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','encoder') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admincreds`
--

INSERT INTO `admincreds` (`ID`, `username`, `full_name`, `password`, `role`) VALUES
(1, 'test', 'Test Creds', '$2y$10$8.YlYO3uPamtHsr2N/i5Ze4h41.LvQYt1p/Q63H8Ac5ER8LXb0Maq', 'admin'),
(12, 'testt', 'testtt', '$2y$10$qaLVMGN5407enU4Ox5hSp.nReE0nmUIjQ6toczbzfix0m37HE1Idy', 'encoder'),
(19, 'testttt', 'test', 'test', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `ID` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `admin_name` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `resident_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `module` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`ID`, `admin_id`, `admin_name`, `role`, `resident_id`, `action`, `module`, `description`, `created_at`) VALUES
(5, 1, '', '', NULL, 'LOGIN', '', 'logged in', '2026-05-13 18:04:19'),
(6, 1, '', '', NULL, 'ARCHIVE', '', 'Archived resident: RYAN CARLO SESE', '2026-05-13 18:06:52'),
(7, 1, '', '', NULL, 'UPDATE', '', 'Updated account:  (test) to Test Creds (test), role: admin', '2026-05-13 18:07:08'),
(8, 1, '', '', NULL, 'CREATE', '', 'Added account: Test Creds 2 (teste) as encoder', '2026-05-13 18:07:17'),
(9, 1, '', '', NULL, 'DELETE', '', 'Permanently deleted archived resident: RYAN CARLO SESE', '2026-05-13 18:07:38'),
(10, 1, '', '', NULL, 'RESTORE', '', 'Restored database backup', '2026-05-13 18:07:51'),
(11, 1, '', '', NULL, 'RESTORE', '', 'Restored database backup', '2026-05-13 18:08:24'),
(12, 1, '', '', NULL, 'LOGIN', '', 'Test Creds logged in', '2026-05-13 19:05:15'),
(13, 1, '', '', NULL, 'LOGIN', '', 'Test Creds logged in', '2026-05-13 19:10:21'),
(14, 1, '', '', NULL, 'LOGIN', '', 'Test Creds logged in', '2026-05-13 19:25:38'),
(15, 1, '', '', NULL, 'LOGIN', '', 'Test Creds logged in', '2026-05-14 03:11:28'),
(16, NULL, '', '', NULL, 'LOGIN', '', 'Test Creds 2 logged in', '2026-05-14 03:11:44'),
(17, 1, '', '', NULL, 'LOGIN', '', 'Test Creds logged in', '2026-05-14 03:11:48'),
(18, 1, '', '', NULL, 'CREATE', '', 'Registered new resident: expired test', '2026-05-14 03:14:34'),
(19, 1, '', '', NULL, 'LOGIN', '', 'Test Creds logged in', '2026-05-14 03:18:12'),
(20, 1, '', '', NULL, 'BACKUP', '', 'Created database backup: backup_2026-05-14_05-19-08.sql', '2026-05-14 03:19:08'),
(21, 1, '', '', NULL, 'CREATE', '', 'Registered new resident: test', '2026-05-14 03:22:24'),
(22, 1, '', '', NULL, 'ARCHIVE', '', 'Archived resident: test', '2026-05-14 03:24:06'),
(23, 1, '', '', NULL, 'ARCHIVE', '', 'Archived resident: expired test', '2026-05-14 03:24:16'),
(24, 1, '', '', NULL, 'RESTORE', '', 'Restored resident from archive: test', '2026-05-14 03:35:50'),
(25, 1, '', '', NULL, 'DELETE', '', 'Permanently deleted archived resident: expired test', '2026-05-14 03:53:51'),
(26, 1, '', '', NULL, 'ARCHIVE', '', 'Archived resident: test', '2026-05-14 03:55:51'),
(27, 1, '', '', NULL, 'DELETE', '', 'Permanently deleted archived resident: test', '2026-05-14 03:55:58'),
(28, 1, '', '', NULL, 'ARCHIVE', '', 'Archived resident: test test', '2026-05-14 03:56:21'),
(29, 1, '', '', NULL, 'ARCHIVE', '', 'Archived resident: test test', '2026-05-14 03:57:51'),
(30, NULL, '', '', NULL, 'LOGIN', '', 'Test Creds 2 logged in', '2026-05-14 04:29:29'),
(31, 1, '', '', NULL, 'LOGIN', '', 'Test Creds logged in', '2026-05-14 04:53:29'),
(32, 1, '', '', 3, 'UPDATE', '', 'Updated resident profile: ARLENE AGUINALDO', '2026-05-14 05:49:59'),
(33, 1, '', '', 4, 'UPDATE', '', 'Updated resident profile: ALLAN ALABANZA', '2026-05-14 05:52:27'),
(34, 1, '', '', 6, 'UPDATE', '', 'Updated resident profile: IISHA KARMEL ALDAY', '2026-05-14 05:53:36'),
(35, 1, '', '', 7, 'UPDATE', '', 'Updated resident profile: EDGILINE AQUINO', '2026-05-14 05:54:51'),
(36, 1, '', '', 8, 'UPDATE', '', 'Updated resident profile: LIZA BACSAL', '2026-05-14 05:55:24'),
(37, 1, '', '', 9, 'UPDATE', '', 'Updated resident profile: LAN\'S BARTOLATA', '2026-05-14 05:55:49'),
(38, 1, '', '', 10, 'UPDATE', '', 'Updated resident profile: HARVEIAME BARRACA', '2026-05-14 05:57:07'),
(39, 1, '', '', 10, 'UPDATE', '', 'Updated resident profile: HARVEIAME BARRACA', '2026-05-14 05:57:39'),
(40, 1, '', '', 13, 'UPDATE', '', 'Updated resident profile: ALEC JOSHUA BERNARDO', '2026-05-14 05:59:28'),
(41, 1, '', '', 17, 'UPDATE', '', 'Updated resident profile: LEICESTER MICHAEL CADANO', '2026-05-14 06:01:06'),
(42, 1, '', '', 18, 'UPDATE', '', 'Updated resident profile: KRISTOFFHERSON JUAN CARLOS CAGUIWA', '2026-05-14 06:02:20'),
(43, 1, '', '', 20, 'UPDATE', '', 'Updated resident profile: ROSEMARIE CALING', '2026-05-14 06:02:53'),
(44, 1, '', '', 22, 'UPDATE', '', 'Updated resident profile: MA. MELISSA CANDADO', '2026-05-14 06:05:24'),
(45, 1, '', '', 24, 'UPDATE', '', 'Updated resident profile: RIZZA JONE CASIO', '2026-05-14 06:06:13'),
(46, 1, '', '', 25, 'UPDATE', '', 'Updated resident profile: KATHLEEN ROSE CASTILLO', '2026-05-14 06:09:16'),
(47, 1, '', '', 26, 'UPDATE', '', 'Updated resident profile: DIANNA JAZZETTE CENTILLO', '2026-05-14 06:10:16'),
(48, 1, '', '', 29, 'UPDATE', '', 'Updated resident profile: ROMMEL CRISTAL', '2026-05-14 06:10:53'),
(49, 1, '', '', 30, 'UPDATE', '', 'Updated resident profile: DARWIN CRUZ', '2026-05-14 06:11:22'),
(50, 1, '', '', 31, 'UPDATE', '', 'Updated resident profile: JOHN RANDOLF CRUZ', '2026-05-14 06:12:09'),
(51, 1, '', '', 32, 'UPDATE', '', 'Updated resident profile: KURT REY CRUZ', '2026-05-14 06:12:27'),
(52, 1, '', '', 32, 'UPDATE', '', 'Updated resident profile: KURT REY CRUZ', '2026-05-14 06:12:45'),
(53, 1, '', '', 34, 'UPDATE', '', 'Updated resident profile: CHRISTIE DANGANAN', '2026-05-14 06:13:24'),
(54, 1, '', '', 38, 'UPDATE', '', 'Updated resident profile: FERNANDO DE GUZMAN', '2026-05-14 06:14:04'),
(55, 1, '', '', 42, 'UPDATE', '', 'Updated resident profile: ELISA DE LEON', '2026-05-14 06:14:54'),
(56, 1, '', '', 44, 'UPDATE', '', 'Updated resident profile: ARNOLD DELGADO', '2026-05-14 06:20:13'),
(57, 1, '', '', 45, 'UPDATE', '', 'Updated resident profile: MARIBETH DIEZ', '2026-05-14 06:21:22'),
(58, 1, '', '', 50, 'UPDATE', '', 'Updated resident profile: MICHAEL LOUIS DUCUSIN', '2026-05-14 06:22:28'),
(59, 1, '', '', 58, 'UPDATE', '', 'Updated resident profile: DEJAN KALJEVIC ESPINOSA', '2026-05-14 06:24:13'),
(60, 1, '', '', 88, 'UPDATE', '', 'Updated resident profile: MELISSA RIVERA MATIAS', '2026-05-14 06:30:19'),
(61, 1, '', '', 2, 'UPDATE', '', 'Updated resident profile: BRENT NYLSEN AGUILLERA', '2026-05-14 06:41:49'),
(62, 1, '', '', 7, 'UPDATE', '', 'Updated resident profile: EDGILINE AQUINO', '2026-05-14 06:48:08'),
(63, 1, '', '', 11, 'UPDATE', '', 'Updated resident profile: NYMPHA BASCONES', '2026-05-14 06:56:39'),
(64, 1, '', '', 25, 'UPDATE', '', 'Updated resident profile: KATHLEEN ROSE CASTILLO', '2026-05-14 07:05:27'),
(65, 1, '', '', 38, 'UPDATE', '', 'Updated resident profile: FERNANDO DE GUZMAN', '2026-05-14 07:10:04'),
(66, 1, '', '', 112, 'UPDATE', '', 'Updated resident profile: JOYCE REYES', '2026-05-14 07:11:16'),
(67, 1, '', '', 35, 'UPDATE', '', 'Updated resident profile: JOSE DAYO JR.', '2026-05-14 07:30:39'),
(68, 1, '', '', 43, 'UPDATE', '', 'Updated resident profile: FELICITY MHAE DELA VEGA', '2026-05-14 07:34:42'),
(69, 1, '', '', 142, 'UPDATE', '', 'Updated resident profile: EIJAY DIVINA', '2026-05-14 07:39:23'),
(70, 1, '', '', 48, 'UPDATE', '', 'Updated resident profile: AARON GABRIEL DOMONDON', '2026-05-14 07:40:46'),
(71, 1, '', '', 49, 'UPDATE', '', 'Updated resident profile: AEON STEFAN DORNIDON', '2026-05-14 07:42:35'),
(72, 1, '', '', 50, 'UPDATE', '', 'Updated resident profile: MICHAEL LOUIS DUCUSIN', '2026-05-14 07:43:44'),
(73, 1, '', '', NULL, 'BACKUP', '', 'Created database backup: backup_2026-05-14_09-50-01.sql', '2026-05-14 07:50:01'),
(74, 1, '', '', 55, 'UPDATE', '', 'Updated resident profile: JOHN CYREL ENRIQUEZ', '2026-05-14 07:50:22'),
(75, 1, '', '', 67, 'UPDATE', '', 'Updated resident profile: LANI GALAPIN', '2026-05-14 07:58:04'),
(76, 1, '', '', 68, 'UPDATE', '', 'Updated resident profile: ALMIRA MIGEL GALERA', '2026-05-14 07:58:45'),
(77, 1, '', '', 71, 'UPDATE', '', 'Updated resident profile: MA. THERESA GUANIZO', '2026-05-14 08:00:34'),
(78, 1, '', '', 74, 'UPDATE', '', 'Updated resident profile: RUDY HUAB', '2026-05-14 08:04:28'),
(79, 1, '', '', 73, 'UPDATE', '', 'Updated resident profile: ANNE BERNADETTE HUAB', '2026-05-14 08:04:48'),
(80, 1, '', '', 75, 'UPDATE', '', 'Updated resident profile: JOHN MICHAEL JINDANI', '2026-05-14 08:05:49'),
(81, NULL, '', '', 77, 'UPDATE', '', 'Updated resident profile: MARIA JUNIO', '2026-05-14 08:08:56'),
(82, NULL, '', '', 78, 'UPDATE', '', 'Updated resident profile: HONEY FAYE LAXAMANA', '2026-05-14 08:09:26'),
(83, NULL, '', '', 82, 'UPDATE', '', 'Updated resident profile: JACKLYN MANABAT', '2026-05-14 08:11:42'),
(84, NULL, '', '', 84, 'UPDATE', '', 'Updated resident profile: CHRISTEL MANIPOL', '2026-05-14 08:12:22'),
(85, NULL, '', '', 85, 'UPDATE', '', 'Updated resident profile: CHRIAN MANIPOL', '2026-05-14 08:12:41'),
(86, NULL, '', '', 86, 'UPDATE', '', 'Updated resident profile: EXEQUIEL IVAN MARIÑAS', '2026-05-14 08:29:20'),
(87, NULL, '', '', 87, 'UPDATE', '', 'Updated resident profile: DAVID JOSIAH MARQUEZ', '2026-05-14 08:29:48'),
(88, NULL, '', '', 91, 'UPDATE', '', 'Updated resident profile: JONATHAN MELO', '2026-05-14 08:31:30'),
(89, NULL, '', '', 96, 'UPDATE', '', 'Updated resident profile: BENEDICT MUYONG', '2026-05-14 08:33:52'),
(90, NULL, '', '', 97, 'UPDATE', '', 'Updated resident profile: ERIC NUADA', '2026-05-14 08:34:33'),
(91, NULL, '', '', 98, 'UPDATE', '', 'Updated resident profile: ENRICO ORTIZ', '2026-05-14 08:35:00'),
(92, NULL, '', '', 99, 'UPDATE', '', 'Updated resident profile: YUAN PADILLA', '2026-05-14 08:35:35'),
(93, NULL, '', '', 101, 'UPDATE', '', 'Updated resident profile: JOSHUA PAGARAGAN', '2026-05-14 08:36:10'),
(94, NULL, '', '', 102, 'UPDATE', '', 'Updated resident profile: DAN AEDHEN PAJARILLO', '2026-05-14 08:36:26'),
(95, NULL, '', '', 103, 'UPDATE', '', 'Updated resident profile: MARLON PAMEL', '2026-05-14 08:36:37'),
(96, NULL, '', '', 106, 'UPDATE', '', 'Updated resident profile: KATRINA PANGAN', '2026-05-14 08:37:39'),
(97, NULL, '', '', 109, 'UPDATE', '', 'Updated resident profile: DEAN RYLLE QUIAMBAO', '2026-05-14 08:38:38'),
(98, NULL, '', '', 110, 'UPDATE', '', 'Updated resident profile: EMMANUEL RAMOS', '2026-05-14 08:39:02'),
(99, NULL, '', '', 111, 'UPDATE', '', 'Updated resident profile: DWAYNE MATTHEW REYES', '2026-05-14 08:39:28'),
(100, NULL, '', '', 113, 'UPDATE', '', 'Updated resident profile: IVANNA RODRIGUEZ', '2026-05-14 08:39:54'),
(101, NULL, '', '', 141, 'UPDATE', '', 'Updated resident profile: SOJIRO VILLENA', '2026-05-14 08:40:49'),
(102, NULL, '', '', 116, 'UPDATE', '', 'Updated resident profile: ALEXEUZ JOSIAH SALAZAR', '2026-05-14 08:48:37'),
(103, NULL, '', '', 117, 'UPDATE', '', 'Updated resident profile: DAMIEN ELEAZAR SALVADOR', '2026-05-14 08:49:48'),
(104, NULL, '', '', 118, 'UPDATE', '', 'Updated resident profile: LOUISSE DANE SALVADOR', '2026-05-14 08:50:25'),
(105, NULL, '', '', 122, 'UPDATE', '', 'Updated resident profile: MARCUS SHAWN FRANCIS SAMSON', '2026-05-14 08:51:44'),
(106, NULL, '', '', 121, 'UPDATE', '', 'Updated resident profile: MARCO SANTHINO SAMSON', '2026-05-14 08:51:59'),
(107, NULL, '', '', 123, 'UPDATE', '', 'Updated resident profile: ANGEL SANTIAGO JR.', '2026-05-14 08:52:22'),
(108, NULL, '', '', 125, 'UPDATE', '', 'Updated resident profile: ANA LIZA SARSALIJO', '2026-05-14 08:52:48'),
(109, NULL, '', '', 126, 'UPDATE', '', 'Updated resident profile: KHEANE ANGELO SIOSON', '2026-05-14 08:53:51'),
(110, NULL, '', '', 127, 'UPDATE', '', 'Updated resident profile: CANDY SIU', '2026-05-14 08:54:14'),
(111, NULL, '', '', 130, 'UPDATE', '', 'Updated resident profile: LANS TAMAYO', '2026-05-14 08:55:16'),
(112, NULL, '', '', 135, 'UPDATE', '', 'Updated resident profile: JENALYNE TRINIDAD', '2026-05-14 08:57:31'),
(113, NULL, '', '', 136, 'UPDATE', '', 'Updated resident profile: ALVIN TUGAY', '2026-05-14 08:57:53'),
(114, NULL, '', '', 137, 'UPDATE', '', 'Updated resident profile: AMERIZZA TUGAY', '2026-05-14 08:58:25'),
(115, NULL, '', '', 138, 'UPDATE', '', 'Updated resident profile: APRILYN TUGAY', '2026-05-14 08:58:50'),
(116, 1, '', '', NULL, 'LOGIN', '', 'Test Creds logged in', '2026-05-14 08:59:32'),
(117, 1, '', '', NULL, 'BACKUP', '', 'Created database backup: backup_2026-05-14_10-59-41.sql', '2026-05-14 08:59:41'),
(118, 1, '', '', NULL, 'LOGIN', '', 'Test Creds logged in', '2026-05-14 13:52:01'),
(119, 1, '', '', NULL, 'LOGIN', '', 'Test Creds logged in', '2026-05-14 16:26:25'),
(120, 1, '', '', NULL, 'LOGIN', '', 'Test Creds logged in', '2026-05-14 16:33:33'),
(121, 1, '', '', NULL, 'LOGIN', '', 'Test Creds logged in', '2026-05-14 16:35:46'),
(122, 1, '', '', 1, 'ARCHIVE', '', 'Archived resident ID: 1', '2026-05-14 16:50:45'),
(123, 1, '', '', NULL, 'CREATE', '', 'Registered new resident', '2026-05-14 17:07:17'),
(124, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 18:03:43'),
(125, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-14 18:48:42'),
(126, NULL, 'Test Creds 2', 'encoder', NULL, 'LOGIN', 'Authentication', 'Test Creds 2 logged in', '2026-05-14 18:50:35'),
(127, NULL, 'Test Creds 2', 'encoder', NULL, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 19:04:17'),
(128, NULL, 'Test Creds 2', 'encoder', 2, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 19:46:04'),
(129, NULL, 'Test Creds 2', 'encoder', 2, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 19:46:21'),
(130, NULL, 'Test Creds 2', 'encoder', 2, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 19:46:32'),
(131, NULL, 'Test Creds 2', 'encoder', NULL, 'LOGOUT', 'Authentication', 'Test Creds 2 logged out', '2026-05-14 19:49:35'),
(132, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-14 19:50:10'),
(133, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 21:46:39'),
(134, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 21:49:25'),
(135, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 21:59:02'),
(136, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 22:04:39'),
(137, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 22:07:09'),
(138, 1, 'Test Creds', 'admin', NULL, 'CREATE', 'Registration', 'Registered new resident', '2026-05-14 22:14:55'),
(139, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 22:15:11'),
(140, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-14 22:16:51'),
(141, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-14 22:19:18'),
(142, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-14 22:19:19'),
(143, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-14 22:19:27'),
(144, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-14 22:19:31'),
(145, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-14 22:19:37'),
(146, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-14 22:19:48'),
(147, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-14 22:19:57'),
(148, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 22:26:36'),
(149, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 22:26:54'),
(150, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 22:29:48'),
(151, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 22:29:55'),
(152, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-14 22:44:24'),
(153, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-14 22:48:48'),
(154, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-14 22:48:52'),
(155, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-14 22:49:08'),
(156, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-14 22:49:09'),
(157, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-14 22:51:15'),
(158, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-14 22:51:41'),
(159, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-14 22:51:42'),
(160, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-14 22:52:41'),
(161, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-14 22:52:42'),
(162, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-14 22:52:51'),
(163, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-14 22:53:33'),
(164, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-14 22:53:36'),
(165, NULL, 'Test Creds 2', 'encoder', NULL, 'LOGIN', 'Authentication', 'Test Creds 2 logged in', '2026-05-14 22:53:39'),
(166, NULL, 'Test Creds 2', 'encoder', NULL, 'LOGOUT', 'Authentication', 'Test Creds 2 logged out', '2026-05-14 22:53:48'),
(167, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-14 22:53:50'),
(168, 1, 'Test Creds', 'admin', 1, 'RESTORE', 'Archive', 'Resident restored from archive', '2026-05-14 23:20:25'),
(169, 1, 'Test Creds', 'admin', NULL, 'ARCHIVE', 'Residents', 'Archived resident ID: 143', '2026-05-14 23:20:47'),
(171, 1, 'Test Creds', 'admin', NULL, 'CREATE', 'Registration', 'Registered new resident', '2026-05-14 23:21:09'),
(172, 1, 'Test Creds', 'admin', NULL, 'ARCHIVE', 'Residents', 'Archived resident ID: 146', '2026-05-14 23:21:17'),
(174, 1, 'Test Creds', 'admin', NULL, 'CREATE', 'Registration', 'Registered new resident', '2026-05-14 23:24:23'),
(175, 1, 'Test Creds', 'admin', NULL, 'ARCHIVE', 'Residents', 'Archived resident ID: 147', '2026-05-14 23:24:29'),
(176, 1, 'Test Creds', 'admin', NULL, 'DELETE', 'Archive', 'Deleted archived resident permanently. Former resident ID: 147', '2026-05-14 23:24:32'),
(177, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Residents', 'Updated resident profile', '2026-05-14 23:25:00'),
(178, 1, 'Test Creds', 'admin', NULL, 'ARCHIVE', 'Residents', 'Archived resident ID: 145', '2026-05-14 23:25:07'),
(179, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Accounts', 'Updated account: Test Creds 2 (teste) to Test Creds 2 (teste), role: encoder', '2026-05-14 23:37:24'),
(180, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Accounts', 'Updated account: Test Creds 2 (teste) to Test Creds 2 (teste), role: encoder, password changed', '2026-05-14 23:37:30'),
(181, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Accounts', 'Updated account: Test Creds 2 (teste) to Test Creds 2 (teste), role: encoder, password changed', '2026-05-14 23:45:07'),
(182, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Accounts', 'Updated account: Test Creds (test) to Test Creds (test), role: admin, password changed', '2026-05-14 23:45:15'),
(183, 1, 'Test Creds', 'admin', NULL, 'CREATE', 'Accounts', 'Added account: Test Creds 3 (testt) as admin', '2026-05-14 23:45:26'),
(184, 1, 'Test Creds', 'admin', NULL, 'DELETE', 'Accounts', 'Deleted account: Test Creds 3 (testt), role: admin', '2026-05-14 23:50:24'),
(185, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Accounts', 'Updated account: Test Creds 2 (teste) to Test Creds 2 (teste), role: encoder, password changed', '2026-05-14 23:50:28'),
(186, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Accounts', 'Updated account: Test Creds 2 (teste) to Test Creds 2 (teste), role: encoder, password changed', '2026-05-14 23:51:04'),
(187, 1, 'Test Creds', 'admin', NULL, 'DELETE', 'Accounts', 'Deleted account: Test Creds 2 (teste), role: encoder', '2026-05-14 23:51:09'),
(188, 1, 'Test Creds', 'admin', NULL, 'CREATE', 'Accounts', 'Added account: testtt (testt) as encoder', '2026-05-14 23:59:58'),
(189, 1, 'Test Creds', 'admin', NULL, 'UPDATE', 'Accounts', 'Updated account: testtt (testt) to testtt (testt), role: encoder, password changed', '2026-05-15 00:00:03'),
(190, 1, 'Test Creds', 'admin', NULL, 'CREATE', 'Accounts', 'Added account: testt (testtt) as admin', '2026-05-15 00:00:25'),
(191, 1, 'Test Creds', 'admin', NULL, 'DELETE', 'Accounts', 'Deleted account: testt (testtt), role: admin', '2026-05-15 00:00:29'),
(192, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-15 00:27:46'),
(193, 19, 'test', 'admin', NULL, 'LOGIN', 'Authentication', 'test logged in', '2026-05-15 00:38:36'),
(194, 19, 'test', 'admin', NULL, 'DELETE', 'Accounts', 'Deleted account: test (testtt), role: admin', '2026-05-15 00:38:49'),
(195, 19, 'test', 'admin', NULL, 'UPDATE', 'Accounts', 'Updated account: testtt (testt) to testtt (testt), role: encoder, password changed', '2026-05-15 00:39:14'),
(196, 19, 'test', 'admin', NULL, 'UPDATE', 'Accounts', 'Updated account: Test Creds (test) to Test Creds (test), role: admin, password changed', '2026-05-15 00:39:22'),
(197, 19, 'test', 'admin', NULL, 'LOGOUT', 'Authentication', 'test logged out', '2026-05-15 00:41:42'),
(198, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-15 00:41:58'),
(199, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-15 00:42:00'),
(200, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-15 00:42:08'),
(201, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-15 00:43:42'),
(202, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-15 00:43:48'),
(203, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-15 00:43:51'),
(204, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-15 00:43:58'),
(205, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-15 00:44:06'),
(206, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-15 00:44:10'),
(207, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-15 00:44:15'),
(208, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-15 00:44:17'),
(209, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-15 00:49:00'),
(210, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-15 00:49:16'),
(211, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-15 00:49:18'),
(212, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-15 00:49:22'),
(213, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-15 00:49:26'),
(214, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-15 00:49:29'),
(215, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-15 00:49:54'),
(216, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-15 00:49:57'),
(217, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-15 00:50:00'),
(218, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-15 00:50:06'),
(219, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-15 00:50:42'),
(220, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-15 00:50:44'),
(221, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-15 00:50:50'),
(222, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-15 00:50:52'),
(223, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-15 00:51:01'),
(224, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-15 00:51:04'),
(225, 1, 'Test Creds', 'admin', NULL, 'LOGOUT', 'Authentication', 'Test Creds logged out', '2026-05-15 00:51:27'),
(226, 1, 'Test Creds', 'admin', NULL, 'LOGIN', 'Authentication', 'Test Creds logged in', '2026-05-15 00:51:42'),
(227, 1, 'Test Creds', 'admin', NULL, 'ARCHIVE', 'Residents', 'Archived resident ID: 145', '2026-05-15 00:51:53'),
(228, 1, 'Test Creds', 'admin', NULL, 'DELETE', 'Archive', 'Deleted archived resident permanently. Former resident ID: 145', '2026-05-15 00:52:01'),
(229, 1, 'Test Creds', 'admin', NULL, 'ARCHIVE', 'Residents', 'Archived resident ID: 144', '2026-05-15 00:52:10'),
(230, 1, 'Test Creds', 'admin', NULL, 'DELETE', 'Archive', 'Deleted archived resident permanently. Former resident ID: 144', '2026-05-15 00:52:20');

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `ID` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `civil_status` enum('Single','Married','Widowed','Separated') DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `birthplace` varchar(255) DEFAULT NULL,
  `sex` enum('male','female') DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `resident_type` enum('PWD','CWD') NOT NULL,
  `control_num` varchar(255) DEFAULT NULL,
  `pwdid_num` varchar(255) DEFAULT NULL,
  `idissue_date` date DEFAULT NULL,
  `idexpiration_date` date DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `application_status` enum('needs correction','under review','approved','rejected') NOT NULL DEFAULT 'under review',
  `record_status` enum('active','expired','archived') NOT NULL DEFAULT 'active',
  `med_cert` varchar(255) DEFAULT NULL,
  `correction_remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`ID`, `first_name`, `middle_name`, `last_name`, `civil_status`, `birthdate`, `birthplace`, `sex`, `address`, `resident_type`, `control_num`, `pwdid_num`, `idissue_date`, `idexpiration_date`, `profile`, `application_status`, `record_status`, `med_cert`, `correction_remarks`) VALUES
(1, 'PERLITA', 'R', 'ABELLERA', NULL, NULL, NULL, 'female', '63-C SANTOL ST.', 'PWD', NULL, '109-525', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(2, 'BRENT NYLSEN', 'PABLICO', 'AGUILLERA', '', '2008-10-03', '', 'male', '68 WOMEN\'S CLUB ST.', 'CWD', '', '899-217', '0000-00-00', '0000-00-00', '', 'approved', 'active', '', NULL),
(3, 'ARLENE', 'ALFILER', 'AGUINALDO', NULL, '1972-05-12', NULL, 'female', '65 SANTOL ST.', 'PWD', NULL, '12400000204300', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(4, 'ALLAN', 'HILANTAGAAN', 'ALABANZA', NULL, '1975-12-31', NULL, 'male', '18 BIAK N BATO ST.', 'PWD', NULL, '12400000974777', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(5, 'NORMA', 'B', 'ALCUINO', NULL, NULL, NULL, NULL, '88 WOMEN\'S CLUB ST.', 'PWD', NULL, '109-484', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(6, 'IISHA KARMEL', 'ULIT', 'ALDAY', NULL, '2004-08-31', NULL, 'female', '15 BIAK NA BATO ST.', 'PWD', NULL, '21400000652846', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(7, 'EDGILINE', 'DENOFRA', 'AQUINO', NULL, '1992-05-27', NULL, 'female', '93 WOMEN\'S CLUB ST.', 'PWD', NULL, '1240001464691', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(8, 'LIZA', 'ARAGON', 'BACSAL', NULL, '1994-02-10', NULL, 'female', '106 UNION CIVICA ST.', 'PWD', NULL, '12400007487793', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(9, 'LAN\'S', 'TAMAYO', 'BARTOLATA', NULL, '2000-02-16', NULL, 'male', '6-8 BAGONG BUHAY ST.', 'PWD', NULL, '12400000723870', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(10, 'HARVEIAME', 'JUMAWAN', 'BARRACA', NULL, '2024-02-23', NULL, 'male', '75B CUATRO DE JULIO ST.', 'CWD', NULL, '12400001794303', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(11, 'NYMPHA', 'PANIZARES', 'BASCONES', NULL, NULL, NULL, 'female', '43 TOMAS PINPIN ST.', 'PWD', NULL, '109-555', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(12, 'ADRIAN DEXTER', 'ESTEVES', 'BELLA', NULL, NULL, NULL, 'male', '101 CUATRO DE JULIO ST.', 'PWD', NULL, '109-456', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(13, 'ALEC JOSHUA', 'J', 'BERNARDO', NULL, '1999-08-04', NULL, 'male', '62 WOMEN\'S CLUB ST.', 'PWD', NULL, '133901060014', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(14, 'VLADY', 'B', 'BORILLO', NULL, NULL, NULL, 'male', '72 BAGONG BUHAY ST.', 'PWD', NULL, '109-589', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(15, 'HENRY', 'MANRIQUE', 'BORNALES III', NULL, NULL, NULL, 'male', '30 BIAK NA BATO ST.', 'PWD', NULL, '109-477', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(16, 'JONALYN', 'DIEGA', 'BUCASAS', NULL, NULL, NULL, 'female', '101 CUATRO DE JULIO ST.', 'PWD', NULL, '109-073', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(17, 'LEICESTER MICHAEL', 'ASIS', 'CADANO', NULL, '1979-02-13', NULL, 'male', '43 LIBERATION ST.', 'PWD', NULL, '109-474', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(18, 'KRISTOFFHERSON JUAN CARLOS', 'BATICBATIC', 'CAGUIWA', NULL, '2015-12-08', NULL, 'male', '78 WOMENS CLUB ST,', 'CWD', NULL, '12400000569932', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(19, 'ALIHUR', 'P', 'CALEZAR', NULL, NULL, NULL, 'male', '110 BUSTAMANTE ST.', 'PWD', NULL, '109-542', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(20, 'ROSEMARIE', NULL, 'CALING', NULL, '2004-02-09', NULL, 'female', '32 UNION CIVICA ST.', 'PWD', NULL, '10200000726757', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(21, 'JONATHAN', 'NAKPIL', 'CANDADO', NULL, NULL, NULL, 'male', '92 LIBERATION ST.', 'PWD', NULL, '109-117', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(22, 'MA. MELISSA', 'MANGALUS', 'CANDADO', NULL, '1980-08-30', NULL, 'female', '92 LIBERATION ST.', 'PWD', NULL, '256342', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(23, 'BONIFACIO', NULL, 'CANLAS', NULL, NULL, NULL, 'male', '96 LIBERATION ST.', 'PWD', NULL, '109-476', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(24, 'RIZZA JONE', 'EVASCO', 'CASIO', NULL, '1987-03-22', NULL, 'female', '81 CUATRO DE JULIO ST.', 'PWD', NULL, '763-033', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(25, 'KATHLEEN ROSE', 'JOCSON', 'CASTILLO', NULL, '2012-10-23', NULL, 'female', '15 PHODACA ST.', 'CWD', NULL, '12400001018873', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(26, 'DIANNA JAZZETTE', 'DELGADO', 'CENTILLO', NULL, '1986-09-24', NULL, 'female', '106 UNION CIVICA ST.', 'PWD', NULL, '12400000437294', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(27, 'ANTHONY ALEZER', 'T.', 'CHICA', NULL, NULL, NULL, 'male', '6-B MINDANAO ST.', 'PWD', NULL, '109-591', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(28, 'REYNALDO', NULL, 'CORTEZ', NULL, NULL, NULL, 'male', '30 LIBERATION ST.', 'PWD', NULL, '12400000098039', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(29, 'ROMMEL', 'LLAMOSO', 'CRISTAL', NULL, '1984-10-06', NULL, 'male', '114 BUSTAMANTE ST.', 'PWD', NULL, '12400000192431', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(30, 'DARWIN', 'V', 'CRUZ', NULL, '1992-04-28', NULL, 'male', '153 BATANES ST.', 'PWD', NULL, '109-605', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(31, 'JOHN RANDOLF', 'E', 'CRUZ', NULL, '2013-01-06', NULL, 'male', '81 CUATRO DE JULIO ST.', 'CWD', NULL, '109-500', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(32, 'KURT REY', 'REYES', 'CRUZ', NULL, '2001-09-21', NULL, 'male', '7 MADIAS-AS ST.', 'PWD', NULL, '1135-929', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(33, 'ALLAN', 'P', 'DAMES', NULL, NULL, NULL, 'male', '112 LIBERATION ST.', 'PWD', NULL, '109-030', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(34, 'CHRISTIE', 'RAMOS', 'DANGANAN', NULL, '1974-06-04', NULL, 'female', '71B SAN ISIDRO ST.', 'PWD', NULL, '10900000274205', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(35, 'JOSE', NULL, 'DAYO JR.', NULL, NULL, NULL, 'female', '82 BAGONG BUHAY ST', 'PWD', NULL, '109-466', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(36, 'RAUL', 'G', 'DE CASTRO JR.', NULL, NULL, NULL, 'male', '115 BAGONG BUHAY ST.', 'PWD', NULL, '109-420', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(37, 'ROGEL', 'G', 'DE CASTRO', NULL, NULL, NULL, 'male', '114 BAGONG BUHAY ST.', 'PWD', NULL, '109-509', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(38, 'FERNANDO', 'FRIAS', 'DE GUZMAN', NULL, '1979-12-24', NULL, 'male', '20 VISAYAN LIBERATION', 'PWD', NULL, '138570', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(39, 'MANUEL', 'GUEVARRA', 'DE GUZMAN', NULL, NULL, NULL, 'male', '82 BAGONG BUHAY ST', 'PWD', NULL, '109-116', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(40, 'CATALINA', 'DIZON', 'DE JESUS', NULL, NULL, NULL, 'female', '66 CUATRO DE JULIO ST.', 'PWD', NULL, '109-389', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(41, 'TAGUMPAY', 'DIZON', 'DE JESUS', NULL, NULL, NULL, 'female', '66 CUATRO DE JULIO ST.', 'PWD', NULL, '109-388', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(42, 'ELISA', 'A', 'DE LEON', NULL, '1975-09-16', NULL, 'female', '75 WOMEN\'S CLUB ST.', 'PWD', NULL, '109-647', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(43, 'FELICITY MHAE', 'M', 'DELA VEGA', NULL, '2018-01-05', NULL, 'female', '65 UNANG HAKBANG ST.', 'CWD', NULL, '109-600', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(44, 'ARNOLD', 'S', 'DELGADO', NULL, '1968-04-20', NULL, 'male', '79 LIBERATION ST.', 'PWD', NULL, '109-604', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(45, 'MARIBETH', 'TRINIDAD', 'DIEZ', NULL, '1970-08-19', NULL, 'female', '64 SAN ISIDRO ST.', 'PWD', NULL, '12400001339920', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(46, 'EDWIN', NULL, 'DIOCTON', NULL, NULL, NULL, 'male', '64 SAN ISIDRO ST.', 'PWD', NULL, '109-109', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(47, 'ARIEN', 'CAMPO', 'DIVINA', NULL, NULL, NULL, 'female', '93 LIBERATION ST.', 'PWD', NULL, '109-539', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(48, 'AARON GABRIEL', 'DC', 'DOMONDON', NULL, NULL, NULL, 'male', '3 P.D. TAVERA ST.', 'PWD', NULL, '109-418', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(49, 'AEON STEFAN', 'U', 'DORNIDON', NULL, NULL, NULL, 'male', '16 SAN CRISTOBAL ST.', 'PWD', NULL, '109-382', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(50, 'MICHAEL LOUIS', 'NIEVERA', 'DUCUSIN', NULL, '1992-01-28', NULL, 'male', '9-B MINDANAO AVE.', 'PWD', NULL, '104-344', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(51, 'JOHN MICHAEL', 'SANTOS', 'DUMANIG', NULL, '1986-12-28', NULL, 'male', '68 WOMEN\'S CLUB ST.', 'PWD', NULL, '656906', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(52, 'MARIA ESTELA', 'SANTOS', 'DUMANIG', NULL, NULL, NULL, 'female', '68 WOMEN\'S CLUB ST.', 'PWD', NULL, '109-533', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(53, 'ANALYN', 'O', 'EISMA', NULL, '1972-05-28', NULL, 'female', '23-A SAN ISIDRO EXT.', 'PWD', NULL, '098-174', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(54, 'ARIEL', NULL, 'ELIQUEN', NULL, NULL, NULL, 'male', '', 'PWD', NULL, '109-081', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(55, 'JOHN CYREL', 'I', 'ENRIQUEZ', NULL, NULL, NULL, 'male', '38 MINDANAO AVE.', 'PWD', NULL, NULL, NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(56, 'DANICA', 'SISON', 'ESGUERRA', NULL, NULL, NULL, 'female', '89 UNION CIVICA ST.', 'PWD', NULL, '124000026627', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(57, 'DIANA', 'SISON', 'ESGUERRA', NULL, NULL, NULL, 'female', '86 BUSTAMANTE ST.', 'PWD', NULL, '137404', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(58, 'DEJAN KALJEVIC', 'B', 'ESPINOSA', NULL, '2005-09-18', NULL, 'male', '24 SANCIANGCO ST.', 'PWD', NULL, '109-552', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(59, 'SOPHIA ALEXA', 'B', 'ESPINOSA', NULL, '2013-04-15', NULL, 'female', '24 SANCIANGCO ST.', 'CWD', NULL, '109-563', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(60, 'SETH FAVIO', 'MENDEZ', 'ESTOY', NULL, '2020-06-11', NULL, 'male', '65 UNANG HAKBANG ST.', 'CWD', NULL, '124000018115111', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(61, 'JOSHUA REN DE', 'CASTRO', 'FAGARAGAN', NULL, NULL, NULL, 'male', '7 SAN ISIDRO ST.', 'PWD', NULL, '109-469', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(62, 'EVA MARICRIS CATHERINE', 'DOCTOR', 'FAMA', NULL, NULL, NULL, 'female', '4 MINDANAO AVE.', 'PWD', NULL, '109-508', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(63, 'GLADYS', 'BENITEZ', 'FULGENCIO', NULL, '1966-07-04', NULL, 'female', '93 WOMEN\'S CLUB ST.', 'PWD', NULL, '562-551', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(64, 'JONATHAN', 'BENITEZ', 'FULGENCIO', NULL, '1990-06-30', NULL, 'male', '93 WOMEN\'S CLUB ST.', 'PWD', NULL, '562-552', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(65, 'JOHN ULAP MALAIAH', 'SANTOS', 'FULGENCIO', NULL, '2020-02-14', NULL, 'male', '90 BUSTAMANTE ST.', 'CWD', NULL, '12400000816098', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(66, 'LUCIAN', NULL, 'FULGENCIO', NULL, '2017-09-11', NULL, 'male', '93 WOMEN\'S CLUB ST.', 'CWD', NULL, '01662079', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(67, 'LANI', 'SIMPORIOS', 'GALAPIN', NULL, NULL, NULL, 'female', '65 WOMEN\'S CLUB ST.', 'PWD', NULL, '090-181', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(68, 'ALMIRA MIGEL', 'ALATEIT', 'GALERA', NULL, '2020-09-13', NULL, 'female', '74 WOMEN\'S CLUB ST.', 'CWD', NULL, '1402979', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(69, 'FERNANDEZ', 'LAGROSA', 'GALVEZ', NULL, NULL, NULL, 'male', '37 TOMAS PINPIN ST.', 'PWD', NULL, '109-129', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(70, 'ERICSON', 'LUSTADO', 'GATDULA', NULL, '1965-09-21', NULL, 'male', '90 BAGONG BUHAY ST.', 'PWD', NULL, '12400000137404', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(71, 'MA. THERESA', 'V', 'GUANIZO', NULL, NULL, NULL, 'female', '59 CUATRO DE JULIO ST.', 'PWD', NULL, '109-533', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(72, 'ELAIJAH JADE', 'M', 'HATI', NULL, NULL, NULL, 'male', '18 G. SANCIANGCO ST.', 'PWD', NULL, '109-095', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(73, 'ANNE BERNADETTE', NULL, 'HUAB', NULL, NULL, NULL, 'female', '89 CUATRO DE JULIO ST.', 'PWD', NULL, '109-490', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(74, 'RUDY', 'G', 'HUAB', NULL, NULL, NULL, 'male', '89 CUATRO DE JULIO ST.', 'PWD', NULL, '109-565', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(75, 'JOHN MICHAEL', NULL, 'JINDANI', NULL, NULL, NULL, 'male', '25 G. SANCIANGCO ST.', 'PWD', NULL, '109-402', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(76, 'SALVE REGINA', 'LUCERO', 'JUMAWAN', NULL, '1991-05-21', NULL, 'female', '758 CUATRO DE JULIO ST.', 'PWD', NULL, '553229', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(77, 'MARIA', 'GUSI', 'JUNIO', NULL, '1966-12-09', NULL, 'female', '108 BAGONG BUHAY ST.', 'PWD', NULL, '1240000502151', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(78, 'HONEY FAYE', NULL, 'LAXAMANA', NULL, NULL, NULL, 'female', '8 SAN CRISTOBAL ST.', 'PWD', NULL, '109-428', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(79, 'SHEIAN IZABEL', 'ANTESCO', 'MAALIW', NULL, '2017-03-22', NULL, 'female', '99C -BAGONG BUHAY ST.', 'CWD', NULL, '137-404', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(80, 'JUAN GARCIA', NULL, 'MAGALONG III', NULL, '2013-10-22', NULL, 'male', '98-A CUATRO DE JULIO ST.', 'CWD', NULL, '12400001020497', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(81, 'AILEEN ELEONOR', 'GARCIA', 'MAGALONG', NULL, '1991-07-24', NULL, 'female', '98-A CUATRO DE JULIO ST.', 'PWD', NULL, '124000001100213', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(82, 'JACKLYN', 'J', 'MANABAT', NULL, NULL, NULL, 'female', '82 BAGONG BUHAY ST.', 'PWD', NULL, '109-078', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(83, 'CHRISTIAN PAU', 'PLATON', 'MANGILAYA', NULL, '1984-05-17', NULL, 'male', '103 BAGONG BUHAY ST.', 'PWD', NULL, '109-616', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(84, 'CHRISTEL', 'M', 'MANIPOL', NULL, NULL, NULL, 'female', '72 LIBERATION ST.', 'PWD', NULL, '109-036', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(85, 'CHRIAN', 'M', 'MANIPOL', NULL, NULL, NULL, 'male', '72 LIBERATION ST.', 'PWD', NULL, '109-014', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(86, 'EXEQUIEL IVAN', 'URIEL', 'MARIÑAS', NULL, '1994-09-17', NULL, 'male', '114 BAGONG BUHAY ST.', 'PWD', NULL, '109-491', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(87, 'DAVID JOSIAH', 'F', 'MARQUEZ', NULL, '2016-06-01', NULL, 'male', '6 SAN CRISTOBAL ST.', 'CWD', NULL, '0950000574119', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(88, 'MELISSA RIVERA', NULL, 'MATIAS', NULL, '2010-01-19', NULL, 'male', '76-E BAYANI ST.', 'CWD', NULL, '12400000624704', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(89, 'GIL ANTHONY', 'GUEVARRA', 'MEDEL', NULL, '1977-06-06', NULL, 'male', '11 BIAK N BATO ST.', 'PWD', NULL, '12400000654198', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(90, 'JEAN AUSTIN', 'JOCSON', 'MEDEL', NULL, '1998-09-13', NULL, 'male', '11 BIAK N BATO ST.', 'PWD', NULL, '12400000903116', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(91, 'JONATHAN', 'N', 'MELO', NULL, NULL, NULL, 'male', '82 LIBERATION ST.', 'PWD', NULL, '109-432', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(92, 'JAMES', 'ABELLAR', 'MENDOZA', NULL, NULL, NULL, 'male', '34-D MINDANAO AVE.', 'PWD', NULL, '109-442', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(93, 'OSCAR', 'MORAN', 'MESIAS JR.', NULL, '1965-12-28', NULL, 'male', '78 WOMEN\'S CLUB ST.', 'PWD', NULL, '678-989', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(94, 'STEPHEN DAVID', 'C', 'MESIAS', NULL, '1995-03-18', NULL, 'male', '78 WOMEN\'S CLUB ST.', 'PWD', NULL, '12400000678914', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(95, 'FRANCIS DAVID', 'SISON', 'MOSQUEDA', NULL, '2019-07-31', NULL, 'male', '25 G. SANCIANGCO ST.', 'CWD', NULL, '998623', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(96, 'BENEDICT', 'MANGHIRANG', 'MUYONG', NULL, '1992-06-01', NULL, 'male', '69 BUSTAMANTE ST.', 'PWD', NULL, '12400000987895', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(97, 'ERIC', 'BIO', 'NUADA', NULL, NULL, NULL, 'male', '10-A MADIAS-AS ST', 'PWD', NULL, '109-386', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(98, 'ENRICO', NULL, 'ORTIZ', NULL, NULL, NULL, 'male', '16 VISAYAN AVE.', 'PWD', NULL, '109-479', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(99, 'YUAN', 'ROA', 'PADILLA', NULL, NULL, NULL, 'male', '85-8 LIBERATION ST.', 'PWD', NULL, '109-050', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(100, 'EDWIN', 'ESPINOSA', 'PADUA', NULL, '1978-04-17', NULL, 'male', '83 LIBERATION ST.', 'PWD', NULL, '12400000137404', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(101, 'JOSHUA', NULL, 'PAGARAGAN', NULL, NULL, NULL, 'male', '7 SAN ISIDRO ST', 'PWD', NULL, '109-399', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(102, 'DAN AEDHEN', 'A', 'PAJARILLO', NULL, NULL, NULL, 'male', '91 LIBERATION ST.', 'PWD', NULL, '109-089', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(103, 'MARLON', 'T', 'PAMEL', NULL, '1980-05-31', NULL, 'male', '46 TOMAS PINPIN ST.', 'PWD', NULL, '109-387', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(104, 'MARVIN', 'TORRES', 'PAMEL', NULL, '1978-07-21', NULL, 'male', '27 G. SANCIANGCO ST.', 'PWD', NULL, '12400000748540', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(105, 'MICHAEL', 'TORRES', 'PAMEL', NULL, '1974-11-21', NULL, 'male', '114 BAGONG BUHAY ST.', 'PWD', NULL, '12400001128672', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(106, 'KATRINA', 'IDA', 'PANGAN', NULL, NULL, NULL, 'female', '82 LIBERATION ST.', 'PWD', NULL, '109-481', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(107, 'ROWENA', 'AGUILERA', 'PASCUAL', NULL, NULL, NULL, 'female', 'AGUILERA', 'PWD', NULL, '109-103', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(108, 'VIRGILIO', 'ORTEGA', 'PETALLO JR.', NULL, '1983-08-22', NULL, 'male', '75 BUSTAMANTE ST.', 'PWD', NULL, '12400000503600', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(109, 'DEAN RYLLE', 'GONZALES', 'QUIAMBAO', NULL, '2015-01-05', NULL, 'male', '71-C BAGONG BUHAY ST.', 'CWD', NULL, '12400000926293', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(110, 'EMMANUEL', 'L', 'RAMOS', NULL, NULL, NULL, 'male', '71 SAN ISIDRO ST.', 'PWD', NULL, '109-386', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(111, 'DWAYNE MATTHEW', 'SANTOS', 'REYES', NULL, '2017-01-27', NULL, 'male', '96 BAGONG BUHAY ST.', 'CWD', NULL, '12400001029444', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(112, 'JOYCE', 'GUILLERMO', 'REYES', NULL, NULL, NULL, 'female', '38 MINDANAO AVE.', 'PWD', NULL, '12400000109537', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(113, 'IVANNA', 'C', 'RODRIGUEZ', NULL, NULL, NULL, 'female', '112 UNION CIVICA ST', 'PWD', NULL, '109-125', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(114, 'HENRY', 'LETRUDO', 'SAJORDA', NULL, '1981-08-15', NULL, 'male', '76 BAYANI ST.', 'PWD', NULL, '1093830', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(115, 'JODERIC', 'LETRUDO', 'SAJORDA', NULL, '1980-10-01', NULL, 'male', '76-F BAYANI ST...', 'PWD', NULL, '12400008590432', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(116, 'ALEXEUZ JOSIAH', 'LAYUG', 'SALAZAR', NULL, '2019-03-12', NULL, 'male', '96 BAGONG BUHAY ST.', 'CWD', NULL, '124000000214606', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(117, 'DAMIEN ELEAZAR', 'B', 'SALVADOR', NULL, '2016-09-22', NULL, 'male', '111 UNION CIVICA ST.', 'CWD', NULL, '01320062', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(118, 'LOUISSE DANE', 'B', 'SALVADOR', NULL, '2003-01-01', NULL, 'female', '111 UNION CIVICA ST.', 'PWD', NULL, '545282', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(119, 'SHANE MARINETH', 'GONZALVO', 'SAMSON', NULL, '2004-10-11', NULL, 'female', '65 SANTOL ST', 'PWD', NULL, '12400000109106', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(120, 'SHENNA', 'GONZALVO', 'SAMSON', NULL, NULL, NULL, 'female', '65 SANTOL ST', 'PWD', NULL, NULL, NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(121, 'MARCO SANTHINO', 'GONZALVO', 'SAMSON', NULL, '2009-02-08', NULL, 'male', '65 SANTOL ST.', 'CWD', NULL, '12400000099118', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(122, 'MARCUS SHAWN FRANCIS', 'GONZALVO', 'SAMSON', NULL, '2013-03-14', NULL, 'male', '65 SANTOL ST.', 'CWD', NULL, '124100000109422', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(123, 'ANGEL', 'B', 'SANTIAGO JR.', NULL, NULL, NULL, 'male', '88 WOMEN\'S CLUB ST.', 'PWD', NULL, '109-454', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(124, 'ALEXANDER', 'MANGALUS', 'SAQUING', NULL, '1980-07-24', NULL, 'male', '92 LIBERATION ST.', 'PWD', NULL, '303813', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(125, 'ANA LIZA', 'V', 'SARSALIJO', NULL, NULL, NULL, 'female', '57 SAN ISIDRO ST.', 'PWD', NULL, '109-594', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(126, 'KHEANE ANGELO', 'G', 'SIOSON', NULL, NULL, NULL, 'male', '117-C MADIAS-AS ST.', 'PWD', NULL, '109-384', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(127, 'CANDY', 'SIO', 'SIU', NULL, '1980-10-19', NULL, 'female', '118 UNION CIVICA ST', 'PWD', NULL, '12400000610828', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(128, 'MARINA', 'A', 'SY', NULL, '1975-03-01', NULL, 'female', '114 UNION CIVICA ST', 'PWD', NULL, '109-638', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(129, 'HIROMI', 'UMALI', 'TAKANO', NULL, '2014-07-02', NULL, 'female', '76 SAN ISIDRO ST.', 'CWD', NULL, '12400000553396', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(130, 'LANS', NULL, 'TAMAYO', NULL, '2000-02-16', NULL, 'male', '6-B BAGONG BUHAY ST.', 'PWD', NULL, '137-404', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(131, 'JUAN', 'TORCUATOR', 'TEVAR', NULL, '1955-07-04', NULL, 'male', '64 WOMEN\'S CLUB ST.', 'PWD', NULL, '124000007748294', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(132, 'CHARINA', 'CORTEZ', 'TOLENTINO', NULL, '1976-09-10', NULL, 'female', '37 SAN ISIDRO ST.', 'PWD', NULL, '12400000702565', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(133, 'KERBY', 'E', 'TORNILLA', NULL, NULL, NULL, 'male', '118 LIBERATIONS ST.', 'PWD', NULL, '110-220', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(134, 'RYAN', 'VERANO', 'TORRALBA', NULL, '1979-12-12', NULL, 'male', '62 SAN ISIDRO ST.', 'PWD', NULL, '12400001241736', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(135, 'JENALYNE', 'C', 'TRINIDAD', NULL, NULL, NULL, 'female', '75 CUATRO DE JULIO ST.', 'PWD', NULL, '109-483', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(136, 'ALVIN', 'DEXTER', 'TUGAY', NULL, NULL, NULL, 'male', '27 G. SANCIANGCO ST.', 'PWD', NULL, '109-421', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(137, 'AMERIZZA', 'L', 'TUGAY', NULL, NULL, NULL, 'female', '27 G. SANČIANGCO ST.', 'PWD', NULL, '109-406', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(138, 'APRILYN', 'L', 'TUGAY', NULL, NULL, NULL, 'female', '27 G. SANCIANGCO ST.', 'PWD', NULL, '109-390', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(139, 'JOSEFINA', 'GENEROSO', 'VELASCO', NULL, '1973-01-10', NULL, 'female', '84 UNION CIVICA ST.', 'PWD', NULL, '12400000802728', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(140, 'MARCO', 'S', 'VILLACORTA', NULL, '2006-12-12', NULL, 'male', '114 UNION CIVICA ST', 'PWD', NULL, '337-404', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(141, 'SOJIRO', 'DE GUZMAN', 'VILLENA', NULL, '2012-09-18', NULL, 'male', '82 BAGONG BUHAY ST.', 'CWD', NULL, '12400001320024', NULL, NULL, NULL, 'approved', 'active', NULL, NULL),
(142, 'EIJAY', 'C', 'DIVINA', NULL, NULL, NULL, 'male', '93 LIBERATION ST.', 'PWD', NULL, '109-060', NULL, NULL, NULL, 'approved', 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `resident_contacts`
--

CREATE TABLE `resident_contacts` (
  `ID` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `contact_num` varchar(255) DEFAULT NULL,
  `socials` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resident_contacts`
--

INSERT INTO `resident_contacts` (`ID`, `resident_id`, `name`, `contact_num`, `socials`) VALUES
(1, 1, 'PERLITA R ABELLERA', '09472622313', NULL),
(3, 3, 'ARLENE ALFILER AGUINALDO', '09215751865', NULL),
(4, 4, 'ALLAN HILANTAGAAN ALABANZA', '09457821893', NULL),
(5, 5, 'NORMA B ALCUINO', '09291019040', NULL),
(6, 6, 'IISHA KARMEL ULIT ALDAY', '09273242992', NULL),
(7, 7, 'EDGILINE DENOFRA AQUINO', '09171749249', NULL),
(8, 8, 'LIZA ARAGON BACSAL', '09158935460', NULL),
(9, 9, 'LAN\'S TAMAYO BARTOLATA', '09555432325', NULL),
(10, 10, 'HARVEIAME JUMAWAN BARRACA', '09274220847', NULL),
(11, 11, 'NYMPHA PANIZARES BASCONES', NULL, NULL),
(12, 12, 'ADRIAN DEXTER ESTEVES BELLA', '09074101145', NULL),
(13, 13, 'ALEC JOSHUA J BERNARDO', '09758566752', NULL),
(14, 14, 'VLADY B BORILLO', '09281357524', NULL),
(15, 15, 'HENRY MANRIQUE BORNALES III', '09956337288', NULL),
(16, 16, 'JONALYN DIEGA BUCASAS', '09383706780', NULL),
(17, 17, 'LEICESTER MICHAEL ASIS CADANO', '09174967964', NULL),
(18, 18, 'KRISTOFFHERSON JUAN CARLOS BATICBATIC CAGUIWA', '091760082223', NULL),
(19, 19, 'ALIHUR P CALEZAR', '09212715147', NULL),
(20, 20, 'ROSEMARIE CALING', NULL, NULL),
(21, 21, 'JONATHAN NAKPIL CANDADO', '09669764920', NULL),
(22, 22, 'MA. MELISSA MANGALUS CANDADO', NULL, NULL),
(23, 23, 'BONIFACIO CANLAS', '09453292998', NULL),
(24, 24, 'RIZZA JONE EVASCO CASIO', '09360389313', NULL),
(25, 25, 'KATHLEEN ROSE JOCSON CASTILLO', '9360389313', NULL),
(26, 26, 'DIANNA JAZZETTE DELGADO CENTILLO', NULL, NULL),
(27, 27, 'ANTHONY ALEZER T. CHICA', '09178573537', NULL),
(28, 28, 'REYNALDO CORTEZ', '09166942002', NULL),
(29, 29, 'ROMMEL LLAMOSO CRISTAL', '09755481852', NULL),
(30, 30, 'DARWIN V CRUZ', NULL, NULL),
(31, 31, 'JOHN RANDOLF E CRUZ', '09357421813', NULL),
(32, 32, 'KURT REY REYES CRUZ', '09501370574', NULL),
(33, 33, 'ALLAN P DAMES', '09950270700', NULL),
(34, 34, 'CHRISTIE RAMOS DANGANAN', NULL, NULL),
(35, 35, 'JOSE DAYO JR.', '09153411583', NULL),
(36, 36, 'RAUL G DE CASTRO JR.', '09061683132', NULL),
(37, 37, 'ROGEL G DE CASTRO', '09475803830', NULL),
(38, 38, 'FERNANDO FRIAS DE GUZMAN', NULL, NULL),
(39, 39, 'MANUEL GUEVARRA DE GUZMAN', '09269903907', NULL),
(40, 40, 'CATALINA DIZON DE JESUS', '09497455168', NULL),
(41, 41, 'TAGUMPAY DIZON DE JESUS', '09497455168', NULL),
(42, 42, 'ELISA A DE LEON', NULL, NULL),
(43, 43, 'FELICITY MHAE M DELA VEGA', NULL, NULL),
(44, 44, 'ARNOLD S DELGADO', '09074830015', NULL),
(45, 45, 'MARIBETH TRINIDAD DIEZ', '09958251845', NULL),
(46, 46, 'EDWIN DIOCTON', '09984469990', NULL),
(47, 47, 'ARIEN CAMPO DIVINA', '0917874819', NULL),
(48, 48, 'AARON GABRIEL DC DOMONDON', NULL, NULL),
(49, 49, 'AEON STEFAN U DORNIDON', '09173263984', NULL),
(50, 50, 'MICHAEL LOUIS NIEVERA DUCUSIN', '0918429960', NULL),
(51, 51, 'JOHN MICHAEL SANTOS DUMANIG', '09057008025', NULL),
(52, 52, 'MARIA ESTELA SANTOS DUMANIG', '09323274574', NULL),
(53, 53, 'ANALYN O EISMA', '09955427384', NULL),
(54, 54, 'ARIEL ELIQUEN', '09561578008', NULL),
(55, 55, 'JOHN CYREL I ENRIQUEZ', '09561578008', NULL),
(56, 56, 'DANICA SISON ESGUERRA', '09178724747', NULL),
(57, 57, 'DIANA SISON ESGUERRA', '09185836268', NULL),
(58, 58, 'DEJAN KALJEVIC B ESPINOSA', '09398508515', NULL),
(59, 59, 'SOPHIA ALEXA B ESPINOSA', '09398508515', NULL),
(60, 60, 'SETH FAVIO MENDEZ ESTOY', '09275817962', NULL),
(61, 61, 'JOSHUA REN DE CASTRO FAGARAGAN', '09956920902', NULL),
(62, 62, 'EVA MARICRIS CATHERINE DOCTOR FAMA', '09165138474', NULL),
(63, 63, 'GLADYS BENITEZ FULGENCIO', '09184180284', NULL),
(64, 64, 'JONATHAN BENITEZ FULGENCIO', NULL, NULL),
(65, 65, 'JOHN ULAP MALAIAH SANTOS FULGENCIO', '09562533877', NULL),
(66, 66, 'LUCIAN FULGENCIO', '09184180284', NULL),
(67, 67, 'LANI SIMPORIOS GALAPIN', '9230902949', NULL),
(68, 68, 'ALMIRA MIGEL ALATEIT GALERA', NULL, NULL),
(69, 69, 'FERNANDEZ LAGROSA GALVEZ', '09276036854', NULL),
(70, 70, 'ERICSON LUSTADO GATDULA', NULL, NULL),
(71, 71, 'MA. THERESA V GUANIZO', '09293776666', NULL),
(72, 72, 'ELAIJAH JADE M HATI', '09777785504', NULL),
(73, 73, 'ANNE BERNADETTE HUAB', '09392261464', NULL),
(74, 74, 'RUDY G HUAB', '09994167734', NULL),
(75, 75, 'JOHN MICHAEL JINDANI', '09166179589', NULL),
(76, 76, 'SALVE REGINA LUCERO JUMAWAN', '09244220847', NULL),
(77, 77, 'MARIA GUSI JUNIO', '09297466853', NULL),
(78, 78, 'HONEY FAYE LAXAMANA', '09560636227', NULL),
(79, 79, 'SHEIAN IZABEL ANTESCO MAALIW', NULL, NULL),
(80, 80, 'JUAN GARCIA MAGALONG III', NULL, NULL),
(81, 81, 'AILEEN ELEONOR GARCIA MAGALONG', '09274019686', NULL),
(82, 82, 'JACKLYN J MANABAT', '09561081835', NULL),
(83, 83, 'CHRISTIAN PAU PLATON MANGILAYA', NULL, NULL),
(84, 84, 'CHRISTEL M MANIPOL', '09569431517', NULL),
(85, 85, 'CHRIAN M MANIPOL', '09260645577', NULL),
(86, 86, 'EXEQUIEL IVAN URIEL MARIÑAS', '09485216800', NULL),
(87, 87, 'DAVID JOSIAH F MARQUEZ', '09156423670', NULL),
(88, 88, 'MELISSA RIVERA MATIAS', '092734700159', NULL),
(89, 89, 'GIL ANTHONY GUEVARRA MEDEL', '09982030099', NULL),
(90, 90, 'JEAN AUSTIN JOCSON MEDEL', '09269588600', NULL),
(91, 91, 'JONATHAN N MELO', '09216365356', NULL),
(92, 92, 'JAMES ABELLAR MENDOZA', NULL, NULL),
(93, 93, 'OSCAR MORAN MESIAS JR.', NULL, NULL),
(94, 94, 'STEPHEN DAVID C MESIAS', NULL, NULL),
(95, 95, 'FRANCIS DAVID SISON MOSQUEDA', '09663303899', NULL),
(96, 96, 'BENEDICT MANGHIRANG MUYONG', '090634822667', NULL),
(97, 97, 'ERIC BIO NUADA', '09228846605', NULL),
(98, 98, 'ENRICO ORTIZ', '0945345594', NULL),
(99, 99, 'YUAN ROA PADILLA', '09656221956', NULL),
(100, 100, 'EDWIN ESPINOSA PADUA', '09561578029', NULL),
(101, 101, 'JOSHUA PAGARAGAN', '09956920902', NULL),
(102, 102, 'DAN AEDHEN A PAJARILLO', '09219292642', NULL),
(103, 103, 'MARLON T PAMEL', '09982796255', NULL),
(104, 104, 'MARVIN TORRES PAMEL', '09958700496', NULL),
(105, 105, 'MICHAEL TORRES PAMEL', '09913158053', NULL),
(106, 106, 'KATRINA IDA PANGAN', '09178675026', NULL),
(107, 107, 'ROWENA AGUILERA PASCUAL', '09334719604', NULL),
(108, 108, 'VIRGILIO ORTEGA PETALLO JR.', NULL, NULL),
(109, 109, 'DEAN RYLLE GONZALES QUIAMBAO', '09273611693', NULL),
(110, 110, 'EMMANUEL L RAMOS', '09955427384', NULL),
(111, 111, 'DWAYNE MATTHEW SANTOS REYES', '09477145183', NULL),
(112, 112, 'JOYCE GUILLERMO REYES', '09982278335', NULL),
(113, 113, 'IVANNA C RODRIGUEZ', '09206881276', NULL),
(114, 114, 'HENRY LETRUDO SAJORDA', '09205909595', NULL),
(115, 115, 'JODERIC LETRUDO SAJORDA', '09067191026', NULL),
(116, 116, 'ALEXEUZ JOSIAH LAYUG SALAZAR', '09230675795', NULL),
(117, 117, 'DAMIEN ELEAZAR B SALVADOR', '09773585087', NULL),
(118, 118, 'LOUISSE DANE B SALVADOR', '09773585087', NULL),
(119, 119, 'SHANE MARINETH GONZALVO SAMSON', '09175228891', NULL),
(120, 120, 'SHENNA GONZALVO SAMSON', NULL, NULL),
(121, 121, 'MARCO SANTHINO GONZALVO SAMSON', '09175228891', NULL),
(122, 122, 'MARCUS SHAWN FRANCIS GONZALVO SAMSON', '09175228891', NULL),
(123, 123, 'ANGEL B SANTIAGO JR.', '09982030099', NULL),
(124, 124, 'ALEXANDER MANGALUS SAQUING', '09979176520', NULL),
(125, 125, 'ANA LIZA V SARSALIJO', '09984985385', NULL),
(126, 126, 'KHEANE ANGELO G SIOSON', '09326281495', NULL),
(127, 127, 'CANDY SIO SIU', '09176745845', NULL),
(128, 128, 'MARINA A SY', '09157423993', NULL),
(129, 129, 'HIROMI UMALI TAKANO', '09515217676', NULL),
(130, 130, 'LANS TAMAYO', NULL, NULL),
(131, 131, 'JUAN TORCUATOR TEVAR', '09169628676', NULL),
(132, 132, 'CHARINA CORTEZ TOLENTINO', '09218650621', NULL),
(133, 133, 'KERBY E TORNILLA', '09164940046', NULL),
(134, 134, 'RYAN VERANO TORRALBA', '09277880489', NULL),
(135, 135, 'JENALYNE C TRINIDAD', '09487092827', NULL),
(136, 136, 'ALVIN DEXTER TUGAY', '09359343180', NULL),
(137, 137, 'AMERIZZA L TUGAY', '09155963303', NULL),
(138, 138, 'APRILYN L TUGAY', '09164908709', NULL),
(139, 139, 'JOSEFINA GENEROSO VELASCO', '09067191129', NULL),
(140, 140, 'MARCO S VILLACORTA', NULL, NULL),
(141, 141, 'SOJIRO DE GUZMAN VILLENA', '09537803625', NULL),
(142, 142, 'EIJAY C DIVINA', '0917874819', NULL),
(147, 2, 'Primary Contact', '09158828474', '');

-- --------------------------------------------------------

--
-- Table structure for table `resident_disabilities`
--

CREATE TABLE `resident_disabilities` (
  `ID` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `disability_type` enum('Physical','Visual','Auditory','Speech','Cognitive','Psychosocial','Others') DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resident_disabilities`
--

INSERT INTO `resident_disabilities` (`ID`, `resident_id`, `disability_type`, `notes`) VALUES
(1, 1, 'Physical', NULL),
(4, 4, 'Visual', NULL),
(6, 6, 'Auditory', NULL),
(7, 7, 'Others', 'Cancer'),
(8, 8, 'Speech', NULL),
(9, 9, 'Psychosocial', NULL),
(10, 10, 'Speech', NULL),
(11, 11, 'Others', 'Cancer'),
(12, 12, 'Auditory', NULL),
(15, 15, 'Cognitive', NULL),
(16, 16, 'Auditory', NULL),
(17, 17, 'Visual', NULL),
(18, 18, 'Psychosocial', NULL),
(20, 20, 'Visual', NULL),
(21, 21, 'Psychosocial', NULL),
(22, 22, 'Psychosocial', NULL),
(24, 24, 'Psychosocial', NULL),
(25, 25, 'Physical', NULL),
(26, 26, 'Physical', NULL),
(29, 29, 'Physical', NULL),
(32, 32, 'Auditory', NULL),
(34, 34, 'Physical', NULL),
(38, 38, 'Others', 'CANCER'),
(39, 39, 'Physical', NULL),
(40, 40, 'Psychosocial', NULL),
(41, 41, 'Cognitive', NULL),
(44, 44, 'Others', NULL),
(45, 45, 'Physical', NULL),
(47, 47, 'Physical', NULL),
(50, 50, 'Cognitive', NULL),
(51, 51, 'Physical', NULL),
(52, 52, 'Auditory', NULL),
(53, 53, 'Others', 'N/A'),
(54, 54, 'Others', 'N/A'),
(56, 56, 'Physical', NULL),
(57, 57, 'Psychosocial', NULL),
(60, 60, 'Physical', NULL),
(61, 61, 'Cognitive', NULL),
(62, 62, 'Physical', NULL),
(63, 63, 'Physical', 'ORTHOPEDIC'),
(64, 64, 'Physical', 'ORTHOPEDIC'),
(65, 65, 'Psychosocial', NULL),
(66, 66, 'Speech', NULL),
(67, 67, 'Speech', NULL),
(68, 68, 'Speech', NULL),
(69, 69, 'Physical', NULL),
(70, 70, 'Physical', NULL),
(72, 72, 'Others', 'N/A'),
(76, 76, 'Psychosocial', NULL),
(77, 77, 'Physical', NULL),
(79, 79, 'Speech', NULL),
(80, 80, 'Cognitive', NULL),
(81, 81, 'Physical', NULL),
(83, 83, 'Psychosocial', NULL),
(88, 88, 'Cognitive', NULL),
(89, 89, 'Cognitive', 'MENTAL'),
(90, 90, 'Psychosocial', NULL),
(92, 92, 'Physical', NULL),
(93, 93, 'Physical', NULL),
(94, 94, 'Cognitive', 'LEARNING'),
(95, 95, 'Physical', NULL),
(96, 96, 'Auditory', NULL),
(97, 97, 'Auditory', NULL),
(99, 99, 'Cognitive', NULL),
(100, 100, 'Physical', NULL),
(104, 104, 'Physical', NULL),
(105, 105, 'Physical', NULL),
(107, 107, 'Physical', NULL),
(108, 108, 'Physical', 'ORTHOPEDIC'),
(109, 109, 'Cognitive', NULL),
(111, 111, 'Speech', NULL),
(112, 112, 'Others', 'CANCER'),
(114, 114, 'Physical', NULL),
(115, 115, 'Physical', NULL),
(116, 116, 'Speech', NULL),
(117, 117, 'Speech', NULL),
(118, 118, 'Psychosocial', NULL),
(119, 119, 'Visual', NULL),
(120, 120, 'Visual', NULL),
(121, 121, 'Cognitive', NULL),
(122, 122, 'Cognitive', NULL),
(124, 124, 'Visual', NULL),
(127, 127, 'Physical', NULL),
(128, 128, 'Physical', NULL),
(129, 129, 'Visual', NULL),
(131, 131, 'Physical', NULL),
(132, 132, 'Psychosocial', NULL),
(133, 133, 'Others', 'N/A'),
(134, 134, 'Physical', NULL),
(135, 135, 'Others', NULL),
(139, 139, 'Physical', NULL),
(140, 140, 'Physical', NULL),
(141, 141, 'Cognitive', NULL),
(145, 2, 'Cognitive', '');

-- --------------------------------------------------------

--
-- Table structure for table `resident_emergency_contacts`
--

CREATE TABLE `resident_emergency_contacts` (
  `ID` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `contact_num` varchar(255) DEFAULT NULL,
  `relationship` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resident_emergency_contacts`
--

INSERT INTO `resident_emergency_contacts` (`ID`, `resident_id`, `name`, `contact_num`, `relationship`) VALUES
(5, 2, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `resident_family_members`
--

CREATE TABLE `resident_family_members` (
  `ID` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `relationship` varchar(100) DEFAULT NULL,
  `contact_num` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resident_family_members`
--

INSERT INTO `resident_family_members` (`ID`, `resident_id`, `name`, `relationship`, `contact_num`) VALUES
(1, 1, 'MAGCAWAS, JESSIELEN', 'Guardian', NULL),
(5, 5, 'SANTIAGO JR., CARLOS', 'Guardian', NULL),
(6, 6, 'ALDAY, ALLANDALE M.', 'Guardian', NULL),
(7, 7, 'AQUINO, GILDA', 'Guardian', NULL),
(8, 8, 'HOMBREBUENO, DOLORES', 'Guardian', NULL),
(9, 9, 'BARTOLATA, MARYANNE', 'Guardian', NULL),
(10, 10, 'JUMAWAN, SALVE REGINA', 'Guardian', NULL),
(11, 11, 'BASCONES JR., GUILLERMO', 'Guardian', NULL),
(12, 12, 'BELLA, ANNALY', 'Guardian', NULL),
(14, 14, 'BORILLO, ROLLIE', 'Guardian', NULL),
(17, 17, 'CADANO, ARABELLE MICHELLE', 'Guardian', NULL),
(22, 22, 'CANDADO, JONATHAN N.', 'Guardian', NULL),
(23, 23, 'CANLAS, MARY JANE', 'Guardian', NULL),
(26, 26, 'NIMIA CENTILLO', 'Guardian', NULL),
(29, 29, 'LUNA, REBECCA L.', 'Guardian', NULL),
(32, 32, 'CRUZ, RAQUEL R.', 'Guardian', NULL),
(33, 33, 'DAMES, MAGDALENA', 'Guardian', NULL),
(34, 34, 'DANGANAN, JOSELITO', 'Guardian', NULL),
(35, 35, 'DAYO, BABY', 'Guardian', NULL),
(36, 36, 'DE CASTRO, RAQUEL', 'Guardian', NULL),
(39, 39, 'DE GUZMAN, TERESITA', 'Guardian', NULL),
(40, 40, 'DE JESUS, RODOLFO', 'Guardian', NULL),
(41, 41, 'DE JESUS, RODOLFO', 'Guardian', NULL),
(44, 44, 'DELGADO, ALLAN', 'Guardian', NULL),
(47, 47, 'DIVINA, MA. EURNE BETH CAMPO', 'Guardian', NULL),
(48, 48, 'DELA CRUZ, ELIZABETH', 'Guardian', NULL),
(51, 51, 'NGO, MARY ANN G.', 'Guardian', NULL),
(55, 55, 'ENRIQUEZ, ARNILO D.', 'Guardian', NULL),
(57, 57, 'ESGUERRA, DANILO B.', 'Guardian', NULL),
(58, 58, 'ESPINOSA, TERESA', 'Guardian', NULL),
(59, 59, 'ESPINOSA, TERESA', 'Guardian', NULL),
(60, 60, 'ESTOY, ARLENE M.', 'Guardian', NULL),
(61, 61, 'FAGARAGAN, ANGELITA', 'Guardian', NULL),
(63, 63, 'FULGENCIO, NEIL', 'Guardian', NULL),
(65, 65, 'FULGENCIO, JOHN TEDEN', 'Guardian', NULL),
(66, 66, 'FULGENCIO, GLADYS', 'Guardian', NULL),
(68, 68, 'GALERA, MICHELLE', 'Guardian', NULL),
(70, 70, 'MERCADO, MA. LOURDES', 'Guardian', NULL),
(71, 71, 'LOZA, EDWIN', 'Guardian', NULL),
(72, 72, 'HATI, ELAINE', 'Guardian', NULL),
(73, 73, 'HUAB, ROSITA', 'Guardian', NULL),
(74, 74, 'HUAB, ROSITA', 'Guardian', NULL),
(75, 75, 'JINDANI, ALIIN', 'Guardian', NULL),
(76, 76, 'BARRACA, JAMES', 'Guardian', NULL),
(78, 78, 'LAXAMANA, ROLANDO', 'Guardian', NULL),
(85, 85, 'MANIPOL, LETICIA', 'Guardian', NULL),
(86, 86, 'ELIQUEN, ARIEL', 'Guardian', NULL),
(87, 87, 'FLORES, RUSHELLE', 'Guardian', NULL),
(88, 88, 'MATIAS, JHON JOSEPH', 'Guardian', NULL),
(89, 89, 'MEDEL, GIL ANTONIO', 'Guardian', NULL),
(91, 91, 'PANGAN, KATRINA', 'Guardian', NULL),
(94, 94, 'MESIAS, OSCAR', 'Guardian', NULL),
(95, 95, 'MOSQUEDA, MICHELLE S.', 'Guardian', NULL),
(97, 97, 'NUADA, ROSALIE', 'Guardian', NULL),
(99, 99, 'PADILLA, MICHELLE', 'Guardian', NULL),
(102, 102, 'PAJARILLOLEAH,', 'Guardian', NULL),
(103, 103, 'TUGAY, APRILYN', 'Guardian', NULL),
(104, 104, 'PAMEL, ANNALYN S.', 'Guardian', NULL),
(106, 106, 'PANGAN, MILDRED', 'Guardian', NULL),
(109, 109, 'GONZALES, RAQUEL C.', 'Guardian', NULL),
(111, 111, 'MAGLENTE, SHIRYL-LYN S.', 'Guardian', NULL),
(112, 112, 'REYES, VICTORIA', 'Guardian', NULL),
(116, 116, 'SALAZAR, JACQUIELYN', 'Guardian', NULL),
(117, 117, 'SALVADOR, ELYLOU BALA', 'Guardian', NULL),
(118, 118, 'SALVADOR, ELYLOU BALA', 'Guardian', NULL),
(119, 119, 'SAMSON, SHENNA', 'Guardian', NULL),
(120, 120, 'GONZALVO, SERGIO C', 'Guardian', NULL),
(121, 121, 'SAMSON, SHENNA', 'Guardian', NULL),
(122, 122, 'SAMSON, SHENNA', 'Guardian', NULL),
(124, 124, 'SAQUING, MICHAEL', 'Guardian', NULL),
(125, 125, 'TABLIGAN, MARY ANN S.', 'Guardian', NULL),
(126, 126, 'AUSTRIA, YOLANDA', 'Guardian', NULL),
(129, 129, 'UMALI, CAREN JOY A.', 'Guardian', NULL),
(131, 131, 'TEVAR, JEFFREY', 'Guardian', NULL),
(132, 132, 'TOLENTINO, ROSARIO C.', 'Guardian', NULL),
(135, 135, 'CARINO JR., GUILLERMO', 'Guardian', NULL),
(136, 136, 'TUGAY, APRILYN', 'Guardian', NULL),
(139, 139, 'VELASCO, MENCHITA', 'Guardian', NULL),
(140, 140, 'SY, MARINA', 'Guardian', NULL),
(141, 141, 'VILLENA, CRISELDA', 'Guardian', NULL),
(148, 2, 'AGUILLERA, LYN ROSE P.', 'Guardian', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admincreds`
--
ALTER TABLE `admincreds`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `username_unique` (`username`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `audit_logs_admin_id_idx` (`admin_id`),
  ADD KEY `audit_logs_resident_id_idx` (`resident_id`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `resident_contacts`
--
ALTER TABLE `resident_contacts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `resident_contacts_resident_id_idx` (`resident_id`);

--
-- Indexes for table `resident_disabilities`
--
ALTER TABLE `resident_disabilities`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `resident_disabilities_resident_id_idx` (`resident_id`);

--
-- Indexes for table `resident_emergency_contacts`
--
ALTER TABLE `resident_emergency_contacts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `resident_emergency_contacts_resident_id_idx` (`resident_id`);

--
-- Indexes for table `resident_family_members`
--
ALTER TABLE `resident_family_members`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `resident_family_members_resident_id_idx` (`resident_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admincreds`
--
ALTER TABLE `admincreds`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `resident_contacts`
--
ALTER TABLE `resident_contacts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `resident_disabilities`
--
ALTER TABLE `resident_disabilities`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `resident_emergency_contacts`
--
ALTER TABLE `resident_emergency_contacts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `resident_family_members`
--
ALTER TABLE `resident_family_members`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_admin_fk` FOREIGN KEY (`admin_id`) REFERENCES `admincreds` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `audit_logs_resident_fk` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `resident_contacts`
--
ALTER TABLE `resident_contacts`
  ADD CONSTRAINT `resident_contacts_resident_fk` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `resident_disabilities`
--
ALTER TABLE `resident_disabilities`
  ADD CONSTRAINT `resident_disabilities_resident_fk` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `resident_emergency_contacts`
--
ALTER TABLE `resident_emergency_contacts`
  ADD CONSTRAINT `resident_emergency_contacts_resident_fk` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `resident_family_members`
--
ALTER TABLE `resident_family_members`
  ADD CONSTRAINT `resident_family_members_resident_fk` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
