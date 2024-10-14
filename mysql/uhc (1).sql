-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2024 at 04:46 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uhc`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `appointment_reason` varchar(255) DEFAULT NULL,
  `appointment_status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `patient_name`, `appointment_date`, `appointment_time`, `appointment_reason`, `appointment_status`, `created_at`) VALUES
(0, 1, 'arnel', '2024-10-10', '00:00:00', 'fever', 'Pending', '2024-10-10 12:53:46'),
(0, 1, 'James Kenneth', '2024-10-12', '00:00:00', 'fever', 'Pending', '2024-10-11 16:17:18'),
(0, 1, 'Cardo Dalisay', '2024-10-12', '00:00:00', 'Headache', 'Pending', '2024-10-11 18:00:08'),
(0, 2, 'Cardo Dalisay', '2024-10-12', '00:00:00', 'Headache', 'Pending', '2024-10-11 23:34:11');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_schedule`
--

CREATE TABLE `appointment_schedule` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(2) NOT NULL,
  `slots` int(11) NOT NULL DEFAULT 1,
  `exception_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_schedule`
--

INSERT INTO `appointment_schedule` (`id`, `date`, `time`, `slots`, `exception_reason`) VALUES
(17, '2024-10-10', 'AM', 7, NULL),
(18, '2024-10-10', 'PM', 14, NULL),
(19, '2024-10-11', '', 0, 'dayoff'),
(0, '2024-10-12', 'AM', 8, NULL),
(0, '2024-10-12', 'PM', 9, NULL),
(0, '2024-10-13', 'AM', 10, NULL),
(0, '2024-10-13', 'PM', 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `docid` int(11) NOT NULL,
  `docemail` varchar(255) DEFAULT NULL,
  `docname` varchar(255) DEFAULT NULL,
  `docpassword` varchar(255) DEFAULT NULL,
  `docnic` varchar(15) DEFAULT NULL,
  `doctel` varchar(15) DEFAULT NULL,
  `specialties` int(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`docid`, `docemail`, `docname`, `docpassword`, `docnic`, `doctel`, `specialties`) VALUES
(1, 'doctor@gmail.com', 'Dr. Juan Dela Cruz', 'doctor', '000000000', '0110000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `his_admin`
--

CREATE TABLE `his_admin` (
  `ad_id` int(20) NOT NULL,
  `ad_fname` varchar(200) DEFAULT NULL,
  `ad_lname` varchar(200) DEFAULT NULL,
  `ad_email` varchar(200) DEFAULT NULL,
  `ad_pwd` varchar(200) DEFAULT NULL,
  `ad_dpic` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_admin`
--

INSERT INTO `his_admin` (`ad_id`, `ad_fname`, `ad_lname`, `ad_email`, `ad_pwd`, `ad_dpic`) VALUES
(1, 'System', 'Admin', 'doctor@gmail.com', 'df42ebd6ccd778be32d8c4a1d876cf1d8589382d', 'doc-icon.png');

-- --------------------------------------------------------

--
-- Table structure for table `his_nurse_note`
--

CREATE TABLE `his_nurse_note` (
  `nur_id` int(20) NOT NULL,
  `nur_note_name` varchar(200) NOT NULL,
  `nur_note_ailment` varchar(200) NOT NULL,
  `nur_note_number` varchar(200) NOT NULL,
  `nur_note_tests` longtext NOT NULL,
  `nur_note_results` longtext NOT NULL,
  `nur_number` varchar(200) NOT NULL,
  `nur_date_rec` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `his_nurse_note`
--

INSERT INTO `his_nurse_note` (`nur_id`, `nur_note_name`, `nur_note_ailment`, `nur_note_number`, `nur_note_tests`, `nur_note_results`, `nur_number`, `nur_date_rec`) VALUES
(1, 'James Kenneth Manait', 'Fever', 'SQ0DO', '<p>1</p>\r\n', '2', 'GJH2V', '2024-09-03 13:47:44'),
(2, 'Roniel  Barrio', 'Diabetes', 'AYZKI', '<p>asd</p>\r\n', 'fasasfsaasf', 'XM4S7', '2024-09-03 14:16:11'),
(3, 'Arnel  Puagang', 'demo test', 'BX946', '<p>hehe</p>\r\n', 'haha', 'W7HGK', '2024-09-03 14:23:23'),
(4, 'Roniel  Barrio', 'Diabetes', 'AYZKI', '<p>sadas</p>\r\n', '', 'F9BDE', '2024-09-10 05:40:18');

-- --------------------------------------------------------

--
-- Table structure for table `his_patients`
--

CREATE TABLE `his_patients` (
  `pat_id` int(20) NOT NULL,
  `pat_fname` varchar(200) DEFAULT NULL,
  `pat_lname` varchar(200) DEFAULT NULL,
  `pat_dob` varchar(200) DEFAULT NULL,
  `pat_age` varchar(200) DEFAULT NULL,
  `pat_parent_name` varchar(200) DEFAULT NULL,
  `pat_number` varchar(200) DEFAULT NULL,
  `pat_addr` varchar(200) DEFAULT NULL,
  `pat_phone` varchar(200) DEFAULT NULL,
  `pat_type` varchar(200) DEFAULT NULL,
  `pat_date_joined` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `pat_ailment` varchar(200) DEFAULT NULL,
  `pat_discharge_status` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_patients`
--

INSERT INTO `his_patients` (`pat_id`, `pat_fname`, `pat_lname`, `pat_dob`, `pat_age`, `pat_parent_name`, `pat_number`, `pat_addr`, `pat_phone`, `pat_type`, `pat_date_joined`, `pat_ailment`, `pat_discharge_status`) VALUES
(1, 'Arnel ', 'Puagang', '1999-10-22', '23', 'Roniel Barrio', '2485670', 'Manolo Fortich, Bukidnon', '09333333333', 'OutPatient', '2024-10-12 01:39:24.632075', 'fever', NULL),
(2, 'Hencez Heart', 'Taborno', '2002-11-11', '22', 'Karla', '7523408', 'Manolo Fortich, Bukidnon', '09333333333', 'OutPatient', '2024-10-12 01:39:47.539848', 'Chronic pain', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `his_pharmaceuticals`
--

CREATE TABLE `his_pharmaceuticals` (
  `phar_id` int(20) NOT NULL,
  `phar_name` varchar(200) DEFAULT NULL,
  `phar_bcode` varchar(200) DEFAULT NULL,
  `phar_desc` longtext DEFAULT NULL,
  `phar_qty` varchar(200) DEFAULT NULL,
  `phar_cat` varchar(200) DEFAULT NULL,
  `phar_vendor` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `his_soap_records`
--

CREATE TABLE `his_soap_records` (
  `soap_id` int(11) NOT NULL,
  `mdr_number` varchar(50) DEFAULT NULL,
  `soap_pat_name` varchar(255) NOT NULL,
  `soap_pat_parent_name` varchar(255) DEFAULT NULL,
  `soap_pat_adr` varchar(255) NOT NULL,
  `soap_pat_age` varchar(10) NOT NULL,
  `soap_pat_number` varchar(50) NOT NULL,
  `soap_pat_ailment` varchar(255) NOT NULL,
  `soap_subjective` text NOT NULL,
  `soap_objective` text NOT NULL,
  `soap_assessment` text NOT NULL,
  `soap_plan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `his_soap_records`
--

INSERT INTO `his_soap_records` (`soap_id`, `mdr_number`, `soap_pat_name`, `soap_pat_parent_name`, `soap_pat_adr`, `soap_pat_age`, `soap_pat_number`, `soap_pat_ailment`, `soap_subjective`, `soap_objective`, `soap_assessment`, `soap_plan`, `created_at`) VALUES
(1, '3270-8615-3270-8615', 'Arnel  Puagang', 'Roniel Barrio', 'Manolo Fortich, Bukidnon', '23', '2485670', 'fever', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaa\r\naaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'bbbbbbbbbbbbbb\r\nbbbbbbbbbbbbbb', 'cccccccccccccccccccc\r\ncccccccccccccccccccc', 'dddddddddddddddddd\r\ndddddddddddddddddd', '2024-10-12 01:40:34'),
(6709, '3270-8615-3270-8615', 'Arnel  Puagang', NULL, 'Manolo Fortich, Bukidnon', '23', '2485670', 'headache', 'eeeeeeeeeeeeeee\r\neeeeeeeeeeeeeee', 'fffffffffffffff\r\nfffffffffffffff', 'ggggggggggggggggg\r\nggggggggggggggggg', 'hhhhhhhhhhhhhh\r\nhhhhhhhhhhhhhh', '2024-10-12 01:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `his_surgery`
--

CREATE TABLE `his_surgery` (
  `s_id` int(200) NOT NULL,
  `s_number` varchar(200) DEFAULT NULL,
  `s_doc` varchar(200) DEFAULT NULL,
  `s_pat_number` varchar(200) DEFAULT NULL,
  `s_pat_name` varchar(200) DEFAULT NULL,
  `s_pat_ailment` varchar(200) DEFAULT NULL,
  `s_pat_date` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `s_pat_status` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) DEFAULT 'Unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `patient_id` int(11) NOT NULL,
  `pemail` varchar(255) NOT NULL,
  `pname` varchar(255) NOT NULL,
  `ppassword` varchar(255) NOT NULL,
  `paddress` varchar(255) DEFAULT NULL,
  `pnic` varchar(15) DEFAULT NULL,
  `pdob` date DEFAULT NULL,
  `ptel` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`patient_id`, `pemail`, `pname`, `ppassword`, `paddress`, `pnic`, `pdob`, `ptel`) VALUES
(1, 'jkmanait@gmail.com', 'james kenneth manait', 'james@123', 'Damilag', '', '2001-05-29', '09090909090'),
(2, 'arnel@gmail.com', 'ar nel', 'arnel@123', 'manolo', '', '2001-10-10', '09000000000');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `scheduleid` int(11) NOT NULL,
  `docid` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `scheduledate` date DEFAULT NULL,
  `scheduletime` time DEFAULT NULL,
  `nop` int(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `webuser`
--

CREATE TABLE `webuser` (
  `email` varchar(255) NOT NULL,
  `usertype` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `webuser`
--

INSERT INTO `webuser` (`email`, `usertype`) VALUES
('arnel@gmail.com', 'p'),
('jkmanait@gmail.com', 'p'),
('sample@gmail.com', 'p');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`docid`),
  ADD KEY `specialties` (`specialties`);

--
-- Indexes for table `his_admin`
--
ALTER TABLE `his_admin`
  ADD PRIMARY KEY (`ad_id`);

--
-- Indexes for table `his_nurse_note`
--
ALTER TABLE `his_nurse_note`
  ADD PRIMARY KEY (`nur_id`);

--
-- Indexes for table `his_patients`
--
ALTER TABLE `his_patients`
  ADD PRIMARY KEY (`pat_id`);

--
-- Indexes for table `his_pharmaceuticals`
--
ALTER TABLE `his_pharmaceuticals`
  ADD PRIMARY KEY (`phar_id`);

--
-- Indexes for table `his_soap_records`
--
ALTER TABLE `his_soap_records`
  ADD PRIMARY KEY (`soap_id`);

--
-- Indexes for table `his_surgery`
--
ALTER TABLE `his_surgery`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`scheduleid`),
  ADD KEY `docid` (`docid`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `webuser`
--
ALTER TABLE `webuser`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `docid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `his_admin`
--
ALTER TABLE `his_admin`
  MODIFY `ad_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `his_nurse_note`
--
ALTER TABLE `his_nurse_note`
  MODIFY `nur_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `his_patients`
--
ALTER TABLE `his_patients`
  MODIFY `pat_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `his_pharmaceuticals`
--
ALTER TABLE `his_pharmaceuticals`
  MODIFY `phar_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `his_soap_records`
--
ALTER TABLE `his_soap_records`
  MODIFY `soap_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6710;

--
-- AUTO_INCREMENT for table `his_surgery`
--
ALTER TABLE `his_surgery`
  MODIFY `s_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `scheduleid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
