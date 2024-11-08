-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2024 at 09:17 AM
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
  `appointment_time` varchar(2) NOT NULL,
  `appointment_reason` varchar(255) DEFAULT NULL,
  `appointment_status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `patient_name`, `appointment_date`, `appointment_time`, `appointment_reason`, `appointment_status`, `created_at`) VALUES
(0, 8, 'Cardo Dalisay', '2024-10-24', 'AM', 'fever', 'Pending', '2024-10-17 08:12:06'),
(0, 1, 'arnel', '2024-11-08', 'AM', 'fever', 'Pending', '2024-11-07 07:29:55');

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
(0, '2024-10-24', 'AM', 9, NULL),
(0, '2024-10-24', 'PM', 10, NULL),
(0, '2024-12-25', '', 0, 'christmas'),
(0, '2024-10-25', 'AM', 15, NULL),
(0, '2024-10-25', 'PM', 15, NULL),
(0, '2024-11-08', 'AM', 9, NULL),
(0, '2024-11-08', 'PM', 10, NULL),
(0, '2024-11-09', '', 0, 'dayoff');

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
  `pat_sex` varchar(200) DEFAULT NULL,
  `pat_date_joined` varchar(200) DEFAULT NULL,
  `pat_ailment` varchar(200) DEFAULT NULL,
  `pat_discharge_status` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `his_patient_chart`
--

CREATE TABLE `his_patient_chart` (
  `patient_chart_id` int(11) NOT NULL,
  `mdr_number` varchar(50) DEFAULT NULL,
  `patient_chart_pat_name` varchar(255) NOT NULL,
  `patient_chart_pat_sex` varchar(200) DEFAULT NULL,
  `patient_chart_pat_parent_name` varchar(255) DEFAULT NULL,
  `patient_chart_pat_adr` varchar(255) NOT NULL,
  `patient_chart_pat_age` varchar(10) NOT NULL,
  `patient_chart_pat_number` varchar(50) NOT NULL,
  `patient_chart_pat_ailment` varchar(255) NOT NULL,
  `patient_chart_weight` text NOT NULL,
  `patient_chart_length` text NOT NULL,
  `patient_chart_temp` text NOT NULL,
  `patient_chart_diagnosis` text NOT NULL,
  `patient_chart_prescription` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `his_patient_chart`
--

INSERT INTO `his_patient_chart` (`patient_chart_id`, `mdr_number`, `patient_chart_pat_name`, `patient_chart_pat_sex`, `patient_chart_pat_parent_name`, `patient_chart_pat_adr`, `patient_chart_pat_age`, `patient_chart_pat_number`, `patient_chart_pat_ailment`, `patient_chart_weight`, `patient_chart_length`, `patient_chart_temp`, `patient_chart_diagnosis`, `patient_chart_prescription`, `created_at`) VALUES
(5, NULL, 'Kaitlyn  Payne', 'Female', 'Connor Payne', 'Dicklum, Manolo Fortich, Bukidnon', '4', '5946817', 'fever', 'w', 'w', '11°C', 'w', 'w', '2024-11-05 14:22:00'),
(6, NULL, 'Edward Cochran', 'Male', 'Wesson Cochran', 'Manolo Fortich, Bukidnon', '3', '4697805', 'Allergies', 'x', 'x', '1°C', 'x', 'x', '2024-11-05 14:28:30'),
(7, NULL, 'Edward Cochran', 'Male', 'Wesson Cochran', 'Manolo Fortich, Bukidnon', '3', '4697805', 'Fever', 'c', 'c', '11°C', 'c', 'c', '2024-11-05 14:52:34'),
(8, NULL, 'Louisa Graham', 'Female', 'Karla Graham', 'Damilag, Manolo Fortcih, Bukidnon', '4', '3106982', 'Fever', '38', '260', '12°C', 'feveer', 'paracetamol', '2024-11-07 07:23:24'),
(9, NULL, 'Edward Cochran', 'Male', 'Wesson Cochran', 'Manolo Fortich, Bukidnon', '3', '4697805', 'Allergies', '68', '20', '60°C', 'headache', 'paracetamol', '2024-11-07 07:26:55');

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
  `soap_pat_sex` varchar(200) DEFAULT NULL,
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
(4, 'roniel@gmail.com', 'Roniel Barrio', 'roniel@123', 'Lingion, Manolo Fortich, Bukidnon', '', '2001-11-11', '09090909090'),
(5, 'john@gmail.com', 'john cruz', 'john@123', 'Damilag, Manolo Fortich, Bukidnon', '', '2001-11-11', '09999999999'),
(6, 'arnel@gmail.com', 'arnel puagang', 'arnel@123', 'Damilag, Manolo Fortich, Bukidnon', NULL, '0000-00-00', '09090909090'),
(7, 'janjan@gmail.com', 'jan jan', 'janjan@123', 'Damilag, Manolo Fortich, Bukidnon', NULL, '0000-00-00', '09090909090'),
(8, 'mike@gmail.com', 'Mike puagang', 'mike@123', 'Damilag, Manolo Fortich, Bukidnon', NULL, '0000-00-00', '09999999999');

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
('jan@gmail.com', 'p'),
('janjan@gmail.com', 'p'),
('jkmanait@gmail.com', 'p'),
('john@gmail.com', 'p'),
('johnn@gmail.com', 'p'),
('kenneth@gmail.com', 'p'),
('mike@gmail.com', 'p'),
('roniel@gmail.com', 'p'),
('sa@gmail.com', 'p'),
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
-- Indexes for table `his_patient_chart`
--
ALTER TABLE `his_patient_chart`
  ADD PRIMARY KEY (`patient_chart_id`);

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
-- AUTO_INCREMENT for table `his_patient_chart`
--
ALTER TABLE `his_patient_chart`
  MODIFY `patient_chart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `his_pharmaceuticals`
--
ALTER TABLE `his_pharmaceuticals`
  MODIFY `phar_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `his_soap_records`
--
ALTER TABLE `his_soap_records`
  MODIFY `soap_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=683;

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
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
