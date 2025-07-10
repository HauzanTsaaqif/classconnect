-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2025 at 12:59 AM
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
-- Database: `classconnect_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `module_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `file_link` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `module_id`, `title`, `description`, `deadline`, `file_link`, `created_at`) VALUES
(1, 1, 'Tugas Kalkulus part 1', 'Kerjakan tugas PDF berikut', '2025-06-20 19:20:00', 'matematika.pdf', '2025-06-11 14:19:48'),
(2, 1, 'Tugas Kalkulus part 2', 'Kerjakan tugas di pdf dan kumpulkan dalam ppt', '2025-06-27 23:59:00', 'kalkulus.pdf', '2025-06-11 16:04:16'),
(3, 5, 'Kalkulus 1', 'awedde', '2025-07-11 06:22:00', 'daed', '2025-07-08 01:22:16');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`, `description`, `teacher_id`) VALUES
(1, 'Kelas 12 A', 'Selamat datang anak anak, kelas ini dikhususkan bagi mereka yang mau berubah, berkarya dan berilmu.', 2),
(2, 'Kelas 12 B', 'Ilmu alam', 2),
(3, 'Kelas 12 C', 'Ilmu nongkrong', 2),
(5, 'Kelas 1 A', 'abc class', 4),
(6, 'Kelas 1 B', 'B class', 4);

-- --------------------------------------------------------

--
-- Table structure for table `class_enrollments`
--

CREATE TABLE `class_enrollments` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_enrollments`
--

INSERT INTO `class_enrollments` (`id`, `class_id`, `student_id`) VALUES
(1, 1, 1),
(2, 3, 3),
(3, 2, 1),
(4, 5, 5),
(5, 5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` int(11) NOT NULL,
  `module_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `content` text DEFAULT NULL,
  `file_link` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `module_id`, `title`, `content`, `file_link`) VALUES
(1, 1, 'Penambahan dan Pengurangan', '📘 Materi Penambahan dan Pengurangan\n🔢 1. Penambahan ( + )\nPenambahan adalah proses menghitung total dari dua atau lebih angka.\n\nContoh:\n\n2 + 3 = 5\n(Artinya: jika kamu punya 2 apel lalu ditambah 3 apel, kamu punya 5 apel)\n\nCiri-ciri:\n\nHasil penambahan disebut jumlah.\n\nTanda operasi: +\n\nSifat: bisa dibolak-balik → 4 + 5 = 9 dan 5 + 4 = 9\n\n🧠 Latihan:\n\n7 + 2 = ___\n\n10 + 5 = ___\n\n➖ 2. Pengurangan ( - )\nPengurangan adalah proses mengurangi jumlah suatu angka dari angka lainnya.\n\nContoh:\n\n5 - 2 = 3\n(Artinya: jika kamu punya 5 kue dan dimakan 2, sisa 3 kue)\n\nCiri-ciri:\n\nHasil pengurangan disebut selisih.\n\nTanda operasi: −\n\nTidak bisa dibolak-balik → 9 − 4 ≠ 4 − 9\n\n🧠 Latihan:\n\n8 - 3 = ___\n\n15 - 7 = ___\n\n🧮 3. Soal Cerita Sederhana\nRani punya 6 pensil. Ia membeli 4 lagi. Berapa pensil Rani sekarang?\n→ 6 + 4 = ___\n\nIbu membeli 12 jeruk. 5 jeruk dimakan. Berapa sisa jeruk?\n→ 12 - 5 = ___\n\n', 'https://matematika.com'),
(3, 5, 'Penambahan dan Pengurangan', 'aaaaa', 'aaaaa');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `class_id`, `title`, `description`) VALUES
(1, 1, 'Kalkulus 1', 'Pembelajaran akar kuadrat'),
(2, 1, 'Biologi', 'Ilmu hewan dan tumbuhan'),
(3, 3, 'Kalkulus 1', 'Pembelajaran akar kuadrat'),
(4, 2, 'Kalkulus 2', 'Pembelajaran akar tingkat lanjutan'),
(5, 5, 'Kalkulus 1', 'Pembelajaran akar kuadrat');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `assignment_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `submission_link` text DEFAULT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `status` enum('belum','dikirim','diterima','revisi') DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `assignment_id`, `student_id`, `note`, `submission_link`, `submitted_at`, `status`, `feedback`, `reviewed_at`) VALUES
(1, 2, 1, 'www', 'www', '2025-06-12 10:44:48', 'dikirim', 'Perbaiki lagi yah', '2025-06-12 15:23:05'),
(2, 1, 1, 'www', 'www', '2025-06-12 10:39:21', 'diterima', NULL, '2025-06-12 15:13:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('guru','siswa') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Budi Yanto', 'budi123@gmail.com', 'budi123', 'siswa', '2025-06-10 09:47:56'),
(2, 'Idat', 'idat123@gmail.com', 'idat123', 'guru', '2025-06-10 10:09:28'),
(3, 'Anton Cahyadi', 'anton123@gmail.com', '1234', 'siswa', '2025-06-12 09:04:28'),
(4, 'Anton Cahyadi 2', 'anton1234@gmail.com', '12345', 'guru', '2025-06-12 09:05:05'),
(5, 'Anton Cahyadi', 'admin123@gmail.com', '123', 'siswa', '2025-07-07 22:47:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `class_enrollments`
--
ALTER TABLE `class_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignment_id` (`assignment_id`),
  ADD KEY `student_id` (`student_id`);

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
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `class_enrollments`
--
ALTER TABLE `class_enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class_enrollments`
--
ALTER TABLE `class_enrollments`
  ADD CONSTRAINT `class_enrollments_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_enrollments_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `materials`
--
ALTER TABLE `materials`
  ADD CONSTRAINT `materials_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
