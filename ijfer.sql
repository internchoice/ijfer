-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2026 at 01:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ijfer`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `inquiry_type` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `full_name`, `email`, `phone`, `inquiry_type`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 'Prajwal Shinde', 'prajwalshinde166@gmail.com', '7499970466', 'Editorial Board', 'computer engineering', 'jsjwguwe', 'read', '2026-02-24 10:38:22'),
(2, 'Prajwal Shinde', 'prajwalshinde166@gmail.com', '7499970466', 'Editorial Board', 'computer engineering', 'jsjwguwe', 'read', '2026-02-24 10:55:02'),
(3, 'Prajwal Shinde', 'prajwalshinde166@gmail.com', '7499970466', 'Editorial Board', 'computer engineering', 'jsjwguwe', 'read', '2026-02-24 10:55:12'),
(4, 'Vishal Tandale', 'xyz@gmail.com', '9865315355', 'Reviewer Application', 'hdfjd', 'edhgde', 'read', '2026-02-24 10:56:05');

-- --------------------------------------------------------

--
-- Table structure for table `editorial_board`
--

CREATE TABLE `editorial_board` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `role` enum('Editor-in-Chief','Associate Editor','Editorial Board Member') NOT NULL,
  `designation` varchar(255) NOT NULL,
  `expertise` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `position_order` int(11) DEFAULT 0,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `editorial_board`
--

INSERT INTO `editorial_board` (`id`, `name`, `role`, `designation`, `expertise`, `email`, `photo`, `position_order`, `status`, `created_at`) VALUES
(1, 'Dr. R.K. Sharma', 'Editor-in-Chief', 'Editor-in-Chief', 'Engineering & Research', 'editor@ijfer.com', 'sample01.jpg', 1, 'active', '2026-02-19 08:35:33'),
(2, 'Prof. A. Kumar', 'Associate Editor', 'Associate Editor', 'Computer Science', 'associate.editor@ijfer.com', 'sample02.avif', 1, 'active', '2026-02-19 08:35:33'),
(3, 'Dr. S. Patel', 'Editorial Board Member', 'Board Member', 'Mechanical Engineering', 's.patel@ijfer.com', 'sample03.webp', 1, 'active', '2026-02-19 08:35:33'),
(4, 'Dr. M. Johnson', 'Editorial Board Member', 'Board Member', 'Electrical Engineering', 'm.johnson@ijfer.com', 'sample04.webp', 2, 'active', '2026-02-19 08:35:33'),
(5, 'Prof. L. Chen', 'Editorial Board Member', 'Board Member', 'Civil Engineering', 'l.chen@ijfer.com', 'sample05.webp', 3, 'active', '2026-02-19 08:35:33'),
(6, 'Dr. R. Williams', 'Editorial Board Member', 'Board Member', 'Chemical Engineering', 'r.williams@ijfer.com', 'sample06.jpg', 4, 'active', '2026-02-19 08:35:33'),
(7, 'Prof. K. Anderson', 'Editorial Board Member', 'Board Member', 'Biomedical Engineering', 'k.anderson@ijfer.com', 'sample07.webp', 5, 'active', '2026-02-19 08:35:33'),
(8, 'Dr. P. Garcia', 'Editorial Board Member', 'Board Member', 'Environmental Engineering', 'p.garcia@ijfer.com', 'sample08.jpg', 6, 'active', '2026-02-19 08:35:33');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(10) UNSIGNED NOT NULL,
  `heading` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `pdf_file` varchar(500) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `heading`, `description`, `pdf_file`, `created_at`) VALUES
(9, 'news 1', 'abcd', 'uploads/news/1771961748_Project_Documentation_IJFER_pdf', '2026-02-25 01:05:48'),
(10, 'news 2', 'jdfjhjh', 'uploads/news/1772020169_Project_Documentation_IJFER_pdf', '2026-02-25 17:19:29'),
(11, 'news 3', 'kjfvkndsan', 'uploads/news/1772020179_Project_Documentation_IJFER_pdf', '2026-02-25 17:19:39'),
(12, 'news 4', 'jhdjsjdjn', 'uploads/news/1772020192_Project_Documentation_IJFER_pdf', '2026-02-25 17:19:52'),
(13, 'news 5', 'sjdhugjgjdnf', 'uploads/news/1772020204_Project_Documentation_IJFER_pdf', '2026-02-25 17:20:04'),
(14, 'news 6', 'jdhjdsbmndsa', 'uploads/news/1772020214_Project_Documentation_IJFER_pdf', '2026-02-25 17:20:14'),
(15, 'news 7', 'bsddhjhjdss', 'uploads/news/1772020225_Project_Documentation_IJFER_pdf', '2026-02-25 17:20:25'),
(16, 'news 8', 'njdhhjan,dja', 'uploads/news/1772020238_Project_Documentation_IJFER_pdf', '2026-02-25 17:20:38'),
(17, 'news 9', 'dnjhsd', 'uploads/news/1772020252_Project_Documentation_IJFER_pdf', '2026-02-25 17:20:52'),
(18, 'news 10', 'mjhsbdbmnbc', 'uploads/news/1772020262_Project_Documentation_IJFER_pdf', '2026-02-25 17:21:02');

-- --------------------------------------------------------

--
-- Table structure for table `papers`
--

CREATE TABLE `papers` (
  `id` int(11) NOT NULL,
  `paper_id` varchar(100) NOT NULL,
  `volume` varchar(100) NOT NULL,
  `research_area` varchar(150) NOT NULL,
  `paper_title` varchar(255) NOT NULL,
  `abstract` text NOT NULL,
  `country` varchar(100) NOT NULL,
  `authors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`authors`)),
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `status` enum('submitted','under_review','accepted','rejected') DEFAULT 'submitted',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `papers`
--

INSERT INTO `papers` (`id`, `paper_id`, `volume`, `research_area`, `paper_title`, `abstract`, `country`, `authors`, `file_name`, `file_path`, `status`, `submitted_at`) VALUES
(1, 'IJFER_FEBRUARY_2026_001', 'Volume 6 Issue 6, June 2024', 'Mathematics', 'abc', 'jhcdcf', 'india', '[{\"name\":\"jhshj\",\"email\":\"bshd@gmail.com\",\"phone\":\"456154\",\"institution\":\"jdhhgsd\"},{\"name\":\"snbdd\",\"email\":\"axb@vam.com\",\"phone\":\"77474\",\"institution\":\"snbd\"}]', 'IJFER_FEBRUARY_2026_001.docx', 'admin/uploads/IJFER_FEBRUARY_2026_001.docx', 'accepted', '2026-02-19 07:36:14'),
(2, 'IJFER_FEBRUARY_2026_002', 'Volume 6 Issue 6, June 2024', 'Chemistry', 'xyz', 'adsreb', 'India', '[{\"name\":\"jdhhhd\",\"email\":\"bas@xyz.com\",\"phone\":\"856367282\",\"institution\":\"dhdjhdh\"}]', 'IJFER_FEBRUARY_2026_002.docx', 'admin/uploads/IJFER_FEBRUARY_2026_002.docx', 'accepted', '2026-02-24 08:24:37'),
(3, 'TEST_002', 'Volume 5 Issue 2, March 2023', 'Engineering', 'Test Past Paper', 'Sample abstract', 'India', '[{\"name\":\"Author Name\",\"email\":\"author@example.com\",\"phone\":\"1234567890\"}]', 'test.pdf', 'admin/uploads/test.pdf', 'accepted', '2026-02-24 08:36:26');

-- --------------------------------------------------------

--
-- Table structure for table `paper_templates`
--

CREATE TABLE `paper_templates` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paper_templates`
--

INSERT INTO `paper_templates` (`id`, `title`, `description`, `file_name`, `file_path`, `uploaded_at`) VALUES
(1, 'Sample Paper Format', 'Templates and guidelines for preparing your manuscript.', 'IJFER_Template.docx', 'uploads/templates/IJFER_Template.docx', '2026-02-22 13:49:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor','author') DEFAULT 'author',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'Admin', 'admin@ijfer.com', '$2y$10$tRXjTGIh4ToS/4pJBa857u14Gj616CMgjT2CYBMNMfkobPBeeUtlu', 'admin', 'active', '2026-02-19 06:54:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `editorial_board`
--
ALTER TABLE `editorial_board`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `papers`
--
ALTER TABLE `papers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `paper_id` (`paper_id`);

--
-- Indexes for table `paper_templates`
--
ALTER TABLE `paper_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `editorial_board`
--
ALTER TABLE `editorial_board`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `papers`
--
ALTER TABLE `papers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `paper_templates`
--
ALTER TABLE `paper_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
