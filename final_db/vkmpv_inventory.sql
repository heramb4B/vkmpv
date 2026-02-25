-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2026 at 04:27 PM
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
-- Database: `vkmpv_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `language` enum('Marathi','Hindi','English') NOT NULL,
  `writer` varchar(150) NOT NULL,
  `date_published` date NOT NULL,
  `total_stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `isbn`, `title`, `language`, `writer`, `date_published`, `total_stock`, `created_at`, `updated_at`) VALUES
(1, '978-81-7310-001-1', 'Vivekananda Charitra', 'Marathi', 'Swami Vivekananda', '2018-01-15', 220, '2026-02-25 12:18:41', '2026-02-25 14:10:56'),
(2, '978-81-7310-002-2', 'Karma Yoga', 'Marathi', 'Swami Vivekananda', '2019-03-20', 180, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(3, '978-81-7310-003-3', 'Raja Yoga', 'Hindi', 'Swami Vivekananda', '2017-06-10', 320, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(4, '978-81-7310-004-4', 'Jnana Yoga', 'Hindi', 'Swami Vivekananda', '2020-08-05', 145, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(5, '978-81-7310-005-5', 'Complete Works Vol. 1', 'English', 'Swami Vivekananda', '2016-11-22', 240, '2026-02-25 12:18:41', '2026-02-25 13:59:57'),
(6, '978-81-7310-006-6', 'Complete Works Vol. 2', 'English', 'Swami Vivekananda', '2016-11-22', 200, '2026-02-25 12:18:41', '2026-02-25 14:00:16'),
(7, '978-81-7310-007-7', 'Bhakti Yoga', 'Marathi', 'Swami Vivekananda', '2021-01-10', 300, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(8, '978-81-7310-008-8', 'Yuva Shakti', 'Marathi', 'Eknathji Ranade', '2015-07-04', 410, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(9, '978-81-7310-009-9', 'Rashtra Nirman', 'Hindi', 'Eknathji Ranade', '2014-09-11', 260, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(10, '978-81-7310-010-0', 'Man Making Education', 'English', 'Swami Vivekananda', '2013-05-18', 130, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(11, '978-81-7310-011-1', 'Vivek Vichar', 'Marathi', 'Various Authors', '2022-03-01', 85, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(12, '978-81-7310-012-2', 'Sandesh Granth', 'Hindi', 'Various Authors', '2022-06-15', 95, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(13, '978-81-7310-001-4', 'XYZ', 'Marathi', 'Prathamesh Bhasmare', '2025-06-12', 40, '2026-02-25 14:01:03', '2026-02-25 14:01:03');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('New','Contacted','Interested','Not Interested') NOT NULL DEFAULT 'New',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_submissions`
--

INSERT INTO `contact_submissions` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `submitted_at`, `updated_at`) VALUES
(1, 'Priya Sharma', 'priya.sharma@example.com', '9823456789', 'Bulk Order Inquiry', 'I am interested in placing a bulk order of Karma Yoga books for our school library.', 'New', '2025-01-10 04:53:00', '2026-02-25 15:14:57'),
(2, 'Arjun Desai', 'arjun.desai@gmail.com', '8765432109', 'Book Availability', 'Looking for the Hindi edition of Complete Works. Are these currently in stock?', 'Contacted', '2025-01-18 09:15:00', '2026-02-25 14:03:45'),
(3, 'Meena Kulkarni', 'meena.kulkarni@yahoo.com', NULL, 'Diary Wholesale Price', 'We run a bookstore and are interested in stocking your Vivekananda Diaries.', 'New', '2025-01-25 03:45:00', '2026-02-25 14:03:45'),
(4, 'Rohit Joshi', 'rohit.joshi@outlook.com', '7654321098', 'Donation Query', 'I would like to sponsor publication distribution to tribal schools in Maharashtra.', 'New', '2025-02-03 11:00:00', '2026-02-25 14:03:45'),
(5, 'Vikram Nair', 'vikram.nair@example.org', '8899001122', 'Calendar Order', 'Our NGO needs 100 wall calendars for branch offices. Please confirm availability.', 'Contacted', '2025-02-12 07:50:00', '2026-02-25 14:03:45'),
(6, 'Sudhir Marathe', 'sudhir.marathe@company.com', '9876500123', 'Corporate Gifting', 'Planning to gift Vivekananda literature to 200 employees. Can you customise packaging?', 'Interested', '2025-02-19 09:30:00', '2026-02-25 14:03:45'),
(7, 'Lalita Bendre', 'lalita.bendre@sample.in', '7788990011', 'Requesting Catalogue', 'Could you please email your full publication catalogue?', 'New', '2025-02-22 04:40:00', '2026-02-25 14:03:45'),
(8, 'Nikhil Wagh', 'nikhil.wagh@techmail.com', '9001122334', 'Website Feedback', 'Suggestion: an online ordering system with home delivery option would be very helpful.', 'Not Interested', '2025-02-24 12:00:00', '2026-02-25 14:54:53'),
(10, 'Saurav Gurav', 'saurav@gmail.com', '8777211145', 'Business Enquiry', 'I need some more books for the fair. Please contact asap', 'Contacted', '2026-02-25 15:23:31', '2026-02-25 15:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `monthly_sales`
--

CREATE TABLE `monthly_sales` (
  `id` int(11) NOT NULL,
  `month` varchar(20) NOT NULL,
  `year` int(11) NOT NULL,
  `book_sales` int(11) DEFAULT 0,
  `utility_sales` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monthly_sales`
--

INSERT INTO `monthly_sales` (`id`, `month`, `year`, `book_sales`, `utility_sales`, `created_at`) VALUES
(1, 'September', 2024, 320, 180, '2026-02-25 12:18:41'),
(2, 'October', 2024, 410, 220, '2026-02-25 12:18:41'),
(3, 'November', 2024, 280, 195, '2026-02-25 12:18:41'),
(4, 'December', 2024, 550, 380, '2026-02-25 12:18:41'),
(5, 'January', 2025, 390, 210, '2026-02-25 12:18:41'),
(6, 'February', 2025, 430, 260, '2026-02-25 12:18:41');

-- --------------------------------------------------------

--
-- Table structure for table `stock_log`
--

CREATE TABLE `stock_log` (
  `id` int(11) NOT NULL,
  `item_type` enum('book','utility') NOT NULL,
  `item_id` int(11) NOT NULL,
  `action` enum('add','reduce') NOT NULL,
  `quantity` int(11) NOT NULL,
  `performed_by` int(11) NOT NULL,
  `performed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_log`
--

INSERT INTO `stock_log` (`id`, `item_type`, `item_id`, `action`, `quantity`, `performed_by`, `performed_at`) VALUES
(1, 'utility', 1, 'reduce', 1, 1, '2026-02-25 12:29:40'),
(2, 'book', 5, 'add', 40, 1, '2026-02-25 13:59:57'),
(3, 'book', 6, 'add', 25, 1, '2026-02-25 14:00:16'),
(4, 'book', 1, 'reduce', 10, 4, '2026-02-25 14:10:27'),
(5, 'book', 1, 'reduce', 10, 4, '2026-02-25 14:10:43'),
(6, 'book', 1, 'reduce', 10, 4, '2026-02-25 14:10:56'),
(7, 'utility', 14, 'reduce', 73, 1, '2026-02-25 14:50:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$wEBnlHLfFrqoJoCxQd.ho.e8jFBGDf6ZCtQsRxqDxk.egGS0sdnZq', 'admin', '2026-02-25 12:18:41', '2026-02-25 14:06:08'),
(2, 'Ramesh Patil', 'user@gmail.com', '$2y$10$SB6QnE76w/32HKn39/aJBOOnQz7Vpva7Fvd6U3vp2jcDYeIIPwfkq', 'user', '2026-02-25 12:18:41', '2026-02-25 14:06:08'),
(4, 'Sanket Thankur', 'sanket@gmail.com', '$2y$10$UifPWUJ5eQKYOL/TnD/WeOikWUNe4993oe6HCIcK8Lt2yz4UC.InG', 'admin', '2026-02-25 14:05:15', '2026-02-25 14:05:15'),
(5, 'Heramb Kondhare', 'heramb2@gmail.com', '$2y$10$XPQURawsSYC/rdyHjSqyC.cfKQ/vSX1uBst.sDYwRQlvqgIOfU6TG', 'user', '2026-02-25 14:52:50', '2026-02-25 14:52:50'),
(6, 'Prathamesh Bhasmare', 'prathameshb@gmail.com', '$2y$10$bacy7662Kgh4pUITzrJfNeRW16M9Jzmpqop2bytmFFMBYJ3bJ2jpu', 'user', '2026-02-25 15:12:53', '2026-02-25 15:12:53'),
(7, 'Saurav Gurav', 'saurav@gmail.com', '$2y$10$dxoc9A/wASou8C.qq6hyAevCmcfnX6D7mYRNLJ5KfwKr9YYS46PUy', 'user', '2026-02-25 15:24:57', '2026-02-25 15:24:57');

-- --------------------------------------------------------

--
-- Table structure for table `utilities`
--

CREATE TABLE `utilities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `language` enum('English','Marathi','Hindi','NA') NOT NULL,
  `total_stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilities`
--

INSERT INTO `utilities` (`id`, `name`, `language`, `total_stock`, `created_at`, `updated_at`) VALUES
(1, 'Vivekananda Diary 2024', 'Marathi', 499, '2026-02-25 12:18:41', '2026-02-25 12:29:40'),
(2, 'Vivekananda Diary 2024', 'Hindi', 450, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(3, 'Vivekananda Diary 2024', 'English', 300, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(4, 'Vivekananda Wall Calendar 2024', 'Marathi', 800, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(5, 'Vivekananda Wall Calendar 2024', 'Hindi', 700, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(6, 'Vivekananda Desktop Calendar 2024', 'English', 400, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(7, 'Inspirational Poster Set', 'Marathi', 250, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(8, 'Inspirational Poster Set', 'Hindi', 220, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(9, 'Vivekananda Bookmark Set', 'English', 1000, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(10, 'Kendra Pen Set', 'Marathi', 600, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(11, 'Kendra Notepad', 'Hindi', 350, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(12, 'Vivekananda Tote Bag', 'English', 150, '2026-02-25 12:18:41', '2026-02-25 12:18:41'),
(16, 'kit', 'NA', 100, '2026-02-25 15:17:25', '2026-02-25 15:17:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contact_status` (`status`),
  ADD KEY `idx_contact_submitted` (`submitted_at`);

--
-- Indexes for table `monthly_sales`
--
ALTER TABLE `monthly_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_log`
--
ALTER TABLE `stock_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `performed_by` (`performed_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `utilities`
--
ALTER TABLE `utilities`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `monthly_sales`
--
ALTER TABLE `monthly_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stock_log`
--
ALTER TABLE `stock_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `utilities`
--
ALTER TABLE `utilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `stock_log`
--
ALTER TABLE `stock_log`
  ADD CONSTRAINT `stock_log_ibfk_1` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
