-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2024 at 06:52 PM
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
(0, 1, 'Cardo Dalisay', '2024-10-15', '00:00:00', 'fever', 'Pending', '2024-10-14 13:08:16');

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
(0, '2024-10-15', 'AM', 14, NULL),
(0, '2024-10-15', 'PM', 15, NULL),
(0, '2024-10-20', '', 0, 'dayoff');

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
  `pat_date_joined` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `pat_ailment` varchar(200) DEFAULT NULL,
  `pat_discharge_status` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `his_patients`
--

INSERT INTO `his_patients` (`pat_id`, `pat_fname`, `pat_lname`, `pat_dob`, `pat_age`, `pat_parent_name`, `pat_number`, `pat_addr`, `pat_phone`, `pat_sex`, `pat_date_joined`, `pat_ailment`, `pat_discharge_status`) VALUES
(5, 'Kaitlyn ', 'Payne', '2019-12-12', '4', 'Connor Payne', '5946817', 'Dicklum, Manolo Fortich, Bukidnon', '09090909092', 'Female', '2024-10-14 12:14:04.974712', 'fever', NULL),
(6, 'Edward', 'Cochran', '2020-11-11', '3', 'Wesson Cochran', '4697805', 'Manolo Fortich, Bukidnon', '09111111111', 'Male', '2024-10-14 12:15:22.987372', 'Allergies', NULL),
(7, 'Louisa', 'Graham', '2020-05-29', '4', 'Karla Graham', '3106982', 'Damilag, Manolo Fortcih, Bukidnon', '09333333333', 'Female', '2024-10-14 12:16:31.054794', 'Fever', NULL),
(8, 'Alfredo', 'Ponce', '2019-01-01', '5', 'Elise Ponce', '7628095', 'Dicklum, Manolo Fortich, Bukidnon', '09090909091', 'Male', '2024-10-14 12:18:35.872012', 'fever', NULL),
(9, 'Samuel ', 'Poole', '2020-02-22', '4', 'Bonnie Poole', '7380451', 'Alae, Manolo Fortich, Bukidnon', '09090909092', 'Male', '2024-10-14 12:20:54.913074', 'fever', NULL),
(10, 'Sophia ', 'Terrell', '2015-11-11', '9', 'Drake Terrell', '6187329', 'Alae, Manolo Fortich, Bukidnon', '09090909091', 'Female', '2024-10-14 12:21:53.244223', 'fever', NULL),
(11, 'Mario ', 'Pierce', '2020-05-22', '4', 'Mara Pierce', '1235876', 'Alae, Manolo Fortich, Bukidnon', '09333333333', 'Male', '2024-10-14 12:25:22.566333', 'Allergies', NULL),
(12, 'Lorenzo ', 'Marsh', '2015-06-22', '9', 'Tripp Marsh', '2375089', 'Damilag, Manolo Fortcih, Bukidnon', '09333333333', 'Male', '2024-10-14 12:26:03.114944', 'Chronic pain', NULL),
(13, 'Nora ', 'Vang', '2016-07-22', '8', 'Clay Vang', '2384957', 'Manolo Fortich, Bukidnon', '09090909092', 'Female', '2024-10-14 12:27:14.879647', 'fever', NULL),
(14, 'Louie ', 'Crosby', '2019-08-22', '5', 'Olive Crosby', '1897053', 'Manolo Fortich, Bukidnon', '09090909091', 'Male', '2024-10-14 12:27:54.202248', 'Fever', NULL);

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

--
-- Dumping data for table `his_soap_records`
--

INSERT INTO `his_soap_records` (`soap_id`, `mdr_number`, `soap_pat_name`, `soap_pat_sex`, `soap_pat_parent_name`, `soap_pat_adr`, `soap_pat_age`, `soap_pat_number`, `soap_pat_ailment`, `soap_subjective`, `soap_objective`, `soap_assessment`, `soap_plan`, `created_at`) VALUES
(674, '5719-0286-5719-0286', 'Kaitlyn  Payne', 'Female', 'Connor Payne', 'Dicklum, Manolo Fortich, Bukidnon', '4', '5946817', 'fever', 'The findings suggest a possible middle ear infection, likely otitis media', 'The findings suggest a possible middle ear infection, likely otitis media', 'The findings suggest a possible middle ear infection, likely otitis media', 'The findings suggest a possible middle ear infection, likely otitis media', '2024-10-14 14:32:00'),
(675, '5719-0286-5719-0286', 'Kaitlyn  Payne', 'Female', 'Connor Payne', 'Dicklum, Manolo Fortich, Bukidnon', '4', '5946817', 'fever', 'Physical examination reveals redness and fluid buildup in the right ear. No fever is present. The child winces when the ear is gently touched.', 'Physical examination reveals redness and fluid buildup in the right ear. No fever is present. The child winces when the ear is gently touched.', 'Physical examination reveals redness and fluid buildup in the right ear. No fever is present. The child winces when the ear is gently touched.', 'Physical examination reveals redness and fluid buildup in the right ear. No fever is present. The child winces when the ear is gently touched.', '2024-10-14 14:38:02'),
(676, '5719-0286-5719-0286', 'Kaitlyn  Payne', 'Female', 'Connor Payne', 'Dicklum, Manolo Fortich, Bukidnon', '4', '5946817', 'fever', 'A 4-year-old patient’s father reports that the child has been frequently scratching her ears and seems irritable. The child complains of “ear hurting.”', 'A 4-year-old patient’s father reports that the child has been frequently scratching her ears and seems irritable. The child complains of “ear hurting.”', 'A 4-year-old patient’s father reports that the child has been frequently scratching her ears and seems irritable. The child complains of “ear hurting.”', 'A 4-year-old patient’s father reports that the child has been frequently scratching her ears and seems irritable. The child complains of “ear hurting.”', '2024-10-14 14:38:17'),
(677, '8763-4029-8763-4029', 'Edward Cochran', 'Male', 'Wesson Cochran', 'Manolo Fortich, Bukidnon', '3', '4697805', 'Allergies', 'Prescribe a course of antibiotics suitable for the child’s age and size. Advise the parents to monitor the child’s pain and return if symptoms worsen or do not improve in 48 hours. Recommend over-the-counter pain relief if necessary and schedule a follow-up appointment in one week to reassess the ear condition.', 'Prescribe a course of antibiotics suitable for the child’s age and size. Advise the parents to monitor the child’s pain and return if symptoms worsen or do not improve in 48 hours. Recommend over-the-counter pain relief if necessary and schedule a follow-up appointment in one week to reassess the ear condition.', 'Prescribe a course of antibiotics suitable for the child’s age and size. Advise the parents to monitor the child’s pain and return if symptoms worsen or do not improve in 48 hours. Recommend over-the-counter pain relief if necessary and schedule a follow-up appointment in one week to reassess the ear condition.', 'Prescribe a course of antibiotics suitable for the child’s age and size. Advise the parents to monitor the child’s pain and return if symptoms worsen or do not improve in 48 hours. Recommend over-the-counter pain relief if necessary and schedule a follow-up appointment in one week to reassess the ear condition.', '2024-10-14 14:38:44'),
(678, '8763-4029-8763-4029', 'Edward Cochran', 'Male', 'Wesson Cochran', 'Manolo Fortich, Bukidnon', '3', '4697805', 'Allergies', 'Vision screening in the office shows reduced visual acuity in the left eye. No signs of eye inflammation or discharge.', 'Vision screening in the office shows reduced visual acuity in the left eye. No signs of eye inflammation or discharge.', 'Vision screening in the office shows reduced visual acuity in the left eye. No signs of eye inflammation or discharge.', 'Vision screening in the office shows reduced visual acuity in the left eye. No signs of eye inflammation or discharge.', '2024-10-14 14:39:08');

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
(2, 'arnel@gmail.com', 'ar nel', 'arnel@123', 'manolo', '', '2001-10-10', '09000000000'),
(3, 'sa@gmail.com', 'joh kenneth', 'james@123', 'Damilag', '', '2001-11-11', '09099999999'),
(4, 'roniel@gmail.com', 'Roniel Barrio', 'roniel@123', 'Lingion, Manolo Fortich, Bukidnon', '', '2001-11-11', '09090909090');

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
  MODIFY `pat_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `his_pharmaceuticals`
--
ALTER TABLE `his_pharmaceuticals`
  MODIFY `phar_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `his_soap_records`
--
ALTER TABLE `his_soap_records`
  MODIFY `soap_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=679;

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
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
