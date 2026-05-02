-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 07:07 AM
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
-- Database: `capstone`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_qr_login_tokens`
--

CREATE TABLE `admin_qr_login_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(64) NOT NULL,
  `admin_user_id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `used_at` timestamp NULL DEFAULT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_qr_login_tokens`
--

INSERT INTO `admin_qr_login_tokens` (`id`, `token`, `admin_user_id`, `ip_address`, `user_agent`, `expires_at`, `used_at`, `is_used`, `created_at`, `updated_at`) VALUES
(1, '4zXQ1n4N5tCFcoYfViWaQSw39KozAqjsRcCH0vZCB2Oc848qD9FJ5XQJ7ciUkYH0', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-07 06:08:01', NULL, 0, '2025-12-07 06:03:01', '2025-12-07 06:03:01'),
(2, 'JKKbMKuabIvCrokBVp1lca7kQNy35XdmtWGeuFPuPXxTepXsqbElRoUGRaJ17vhM', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-07 06:10:01', NULL, 0, '2025-12-07 06:05:01', '2025-12-07 06:05:01'),
(3, 'G6aYAvYetDxSqKotjsdDQSJUFeuRkSfE1DOrAuwU6ShJ7mY4B0K8VqZrWbxxnnf0', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-07 06:10:44', NULL, 0, '2025-12-07 06:05:44', '2025-12-07 06:05:44');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_logout_at` timestamp NULL DEFAULT NULL,
  `is_online` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `name`, `email`, `email_verified_at`, `password`, `last_login_at`, `last_logout_at`, `is_online`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Admin', 'ctuAdmin@ctu.com', NULL, '$2y$12$uivS1WTtZ8xpF.VXBy6HWO8MZRFikjkXlDRoEj8jmgEbwjLQpMIR.', '2025-12-01 07:50:49', '2025-12-01 08:19:55', 0, NULL, '2025-11-30 18:22:01', '2025-12-01 08:19:55'),
(3, 'Glenn Lander Calderon', 'calderonzer@gmail.com', NULL, '$2y$12$5mSY9Uj8byKoCVp3XhThrObIwYTXdjFhy81wb2mqerDdmHurMOkpi', '2025-12-07 07:04:05', '2025-12-07 07:03:09', 1, NULL, '2025-12-07 06:37:17', '2025-12-07 07:04:05');

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `job_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `cover_letter` text DEFAULT NULL,
  `resume_path` varchar(255) NOT NULL,
  `status` enum('pending','shortlisted','interview_scheduled','approved','hired','rejected') DEFAULT 'pending',
  `rejection_reason` varchar(255) DEFAULT NULL,
  `is_editable_by_user` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cover_letter_path` varchar(255) DEFAULT NULL,
  `transcript_path` varchar(255) DEFAULT NULL,
  `certificate_path` varchar(255) DEFAULT NULL,
  `portfolio_path` varchar(255) DEFAULT NULL,
  `additional_documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`additional_documents`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`id`, `user_id`, `job_id`, `name`, `email`, `phone`, `cover_letter`, `resume_path`, `status`, `rejection_reason`, `is_editable_by_user`, `created_at`, `updated_at`, `cover_letter_path`, `transcript_path`, `certificate_path`, `portfolio_path`, `additional_documents`) VALUES
(1, 2, 6, 'jhon mark genela', 'genelamark27@gmail.com', '', 'Maria Clara Santos\r\nPoblacion, Danao City, Cebu\r\nmaria.santos@email.com\r\n+63 912 345 6789\r\nNovember 25, 2025\r\n\r\nSubject: Application for Assistant Professor - Information Technology\r\n\r\nDear Hiring Committee,\r\n\r\nI am writing to express my strong interest in the Assistant Professor position in Information Technology at CTU-Danao Campus, as advertised . With a Master\'s degree in Computer Science and three years of teaching experience at a prestigious university, I am confident in my ability to significantly contribute to your academic programs and the development of your students.\r\n\r\nMy teaching philosophy centers on student engagement and the practical application of theoretical concepts. I believe in creating an interactive learning environment where students can bridge the gap between classroom theory and real-world industry requirements. During my tenure, I have successfully taught a range of challenging courses, including:\r\n\r\nWeb Development\r\nDatabase Management\r\nSoftware Engineering\r\n\r\nI am proud to have consistently received positive feedback from students, which reflects my commitment to effective instruction and mentorship.\r\n\r\nI am excited about the opportunity to join CTU-Danao Campus, an institution known for its dedication to producing competent professionals. I look forward to contributing my expertise to your Information Technology department and to the development of future IT professionals.\r\n\r\nThank you for considering my application. I have attached my resume for your review and welcome the opportunity to discuss my qualifications further in an interview.\r\n\r\nSincerely,\r\n\r\nMaria Clara Santos', 'applicants/1/9owHYbhxYYYzgKNwc4T2C2SKyR9ZoyxzzhOdrztW.pdf', 'hired', NULL, 0, '2025-12-01 07:05:33', '2025-12-01 08:07:44', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interviews`
--

CREATE TABLE `interviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `applicant_id` bigint(20) UNSIGNED NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('scheduled','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `item_number` varchar(255) DEFAULT NULL,
  `campus` varchar(255) NOT NULL,
  `vacancies` int(11) NOT NULL DEFAULT 1,
  `education_requirements` text DEFAULT NULL,
  `experience_requirements` text DEFAULT NULL,
  `training_requirements` text DEFAULT NULL,
  `eligibility_requirements` text DEFAULT NULL,
  `email_subject_format` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `department` varchar(255) NOT NULL,
  `position_type` varchar(255) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `application_deadline` date NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `item_number`, `campus`, `vacancies`, `education_requirements`, `experience_requirements`, `training_requirements`, `eligibility_requirements`, `email_subject_format`, `description`, `department`, `position_type`, `salary`, `application_deadline`, `is_published`, `created_at`, `updated_at`) VALUES
(1, 'INSTRUCTOR I - Computer Science', 'CS-2024-001', 'CTU Danao Campus', 2, 'Bachelor\'s degree in Computer Science, Information Technology, or related field. Master\'s degree preferred.', 'At least 2 years of teaching experience or 3 years of industry experience in software development, database management, or related field.', 'Training in pedagogy, educational technology, or curriculum development is an advantage.', 'RA 1080 (Licensed Professional Teacher) or CSC Eligibility (Second Level)', NULL, 'We are seeking a qualified Instructor I for the Computer Science Department. The successful candidate will be responsible for teaching undergraduate courses in computer science, developing curriculum, and engaging in research activities. Responsibilities include preparing lesson plans, conducting lectures and laboratory sessions, evaluating student performance, and participating in departmental activities.', 'College of Computer Studies', 'Full-time', 35000.00, '2026-02-01', 1, '2025-12-01 06:24:19', '2025-12-01 06:24:19'),
(2, 'INSTRUCTOR I - Mathematics', 'MATH-2024-002', 'CTU Danao Campus', 1, 'Bachelor\'s degree in Mathematics, Applied Mathematics, or Mathematics Education. Master\'s degree in Mathematics preferred.', 'Minimum 2 years of teaching experience in mathematics at the tertiary level.', 'Training in modern teaching methodologies and educational technology.', 'RA 1080 (Licensed Professional Teacher) or CSC Eligibility (Second Level)', NULL, 'The Mathematics Department is looking for an Instructor I to teach mathematics courses to undergraduate students. The position involves teaching algebra, calculus, statistics, and other mathematics subjects, preparing instructional materials, and assisting in curriculum development. The ideal candidate should have a strong background in mathematics and excellent communication skills.', 'College of Arts and Sciences', 'Full-time', 35000.00, '2026-02-01', 1, '2025-12-01 06:24:19', '2025-12-01 06:24:19'),
(3, 'ADMINISTRATIVE ASSISTANT III', 'HRMO-2024-003', 'CTU Danao Campus', 1, 'Bachelor\'s degree in Business Administration, Public Administration, or related field.', 'At least 1 year of relevant experience in administrative work, preferably in a government or educational institution.', 'Training in records management, office administration, or human resource management.', 'CSC Eligibility (Sub-professional)', NULL, 'We are hiring an Administrative Assistant III for the Human Resource Management Office. The position involves providing administrative support, managing office records, assisting in recruitment processes, preparing reports, and handling correspondence. The successful candidate should be organized, detail-oriented, and proficient in office software applications.', 'Human Resource Management Office', 'Full-time', 25000.00, '2026-01-01', 1, '2025-12-01 06:24:19', '2025-12-01 06:24:19'),
(4, 'INSTRUCTOR I - Electrical Engineering', 'EE-2024-004', 'CTU Danao Campus', 1, 'Bachelor\'s degree in Electrical Engineering. Master\'s degree in Electrical Engineering or related field preferred.', 'Minimum 2 years of teaching experience or 3 years of professional practice as a licensed electrical engineer.', 'Training in engineering education, laboratory management, or research methodology.', 'RA 1080 (Licensed Professional Teacher) or PRC License (Professional Electrical Engineer)', NULL, 'The College of Engineering is seeking an Instructor I for the Electrical Engineering program. The position requires teaching electrical engineering courses, supervising laboratory activities, and mentoring students. The candidate should have expertise in electrical circuits, power systems, electronics, and control systems. Research and extension activities are also expected.', 'College of Engineering', 'Full-time', 40000.00, '2026-03-01', 1, '2025-12-01 06:24:19', '2025-12-01 06:24:19'),
(5, 'INSTRUCTOR', 'JP-2025-001', 'CTU - Danao Campus', 3, 'Must have a Degree and a professional license', 'At least 2 years experience', NULL, NULL, '(INSTRUCTOR - CTU - Danao Campus)', 'Basta maka tudlo okay nana', 'College of Engineering', 'Part-time', 20000.00, '2025-12-31', 1, '2025-12-01 06:48:12', '2025-12-01 06:48:12'),
(6, 'INTRUCTOR', '3', 'CTU - Danao Campus', 3, '- Master\'s degree in Information Technology, Computer Science, or related field\r\n- At least 2 years of teaching experience in higher education\r\n- Strong knowledge of programming languages and web development\r\n- Research publications preferred\r\n- Excellent communication and interpersonal skills', NULL, NULL, NULL, '(INTRUCTOR - CTU - Danao Campus)', 'The College of Technology is seeking a qualified Assistant Professor \r\nfor the Information Technology program. The successful candidate will \r\nteach undergraduate courses, conduct research, and contribute to \r\ncurriculum development.', 'College of Information and Communications Technology', 'Full-time', 20000.00, '2025-12-31', 1, '2025-12-01 07:04:23', '2025-12-01 07:04:23');

-- --------------------------------------------------------

--
-- Table structure for table `job_postings`
--

CREATE TABLE `job_postings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `department` varchar(255) NOT NULL,
  `position_type` varchar(255) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `application_deadline` date NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `applicant_id` bigint(20) UNSIGNED NOT NULL,
  `job_id` bigint(20) UNSIGNED NOT NULL,
  `sender_type` enum('applicant','admin') NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `applicant_id`, `job_id`, `sender_type`, `admin_id`, `message`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(6, 1, 6, 'applicant', NULL, 'Hello', 1, '2025-12-01 08:07:02', '2025-12-01 08:04:51', '2025-12-01 08:07:02'),
(7, 1, 6, 'applicant', NULL, 'GoodDay Admin', 1, '2025-12-01 08:07:02', '2025-12-01 08:05:01', '2025-12-01 08:07:02'),
(8, 1, 6, 'admin', 2, 'Good day dong', 1, '2025-12-01 08:07:26', '2025-12-01 08:07:16', '2025-12-01 08:07:26');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '2025_03_27_160502_create_admin_users_table', 1),
(4, '2025_04_08_104724_create_job_postings_table', 1),
(5, '2025_09_13_051832_create_jobs_table', 1),
(6, '2025_09_13_051853_create_applicants_table_new', 1),
(7, '2025_09_13_053742_create_interviews_table_new', 1),
(8, '2025_09_13_135511_add_ctu_fields_to_jobs_table', 1),
(9, '2025_09_13_141631_create_messages_table', 1),
(10, '2025_09_13_142438_add_user_id_to_applicants_table', 1),
(11, '2025_09_13_144537_update_applicants_status_enum', 1),
(12, '2025_09_13_145615_add_hired_status_to_applicants', 1),
(13, '2025_09_13_150528_add_profile_fields_to_users_table', 1),
(14, '2025_09_14_143055_add_document_fields_to_applicants_table', 1),
(15, '2025_09_15_000001_add_rejection_reason_to_applicants_table', 1),
(16, '2025_09_15_000002_add_is_editable_by_user_to_applicants_table', 1),
(17, '2025_09_15_000003_add_admin_presence_fields', 1),
(18, '2025_12_01_000001_make_job_requirements_nullable', 2),
(19, '2025_12_07_135411_create_admin_qr_login_tokens_table', 3),
(20, '2025_12_07_141644_add_qr_code_to_users_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('IDDxBRdCfnt8a2Y0Xx56mt0pBEixInD0N16rg9YF', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid0xSU05zcEpNQkJSdE5ncHN0SkVSZzhSNE41VlJYZURXY29KUXRjRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO31zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==', 1765091350);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `portfolio_url` varchar(255) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `education` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `qr_code`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `bio`, `phone`, `address`, `birth_date`, `profile_picture`, `linkedin_url`, `portfolio_url`, `skills`, `experience`, `education`) VALUES
(1, 'jhon mark genela', 'genelamark278@gmail.com', NULL, NULL, '$2y$12$hQPUQT2MVqcCGR3DhzomKeWr9a2CQ7S3hRfiyrLTBEBbiBSksTAlG', NULL, '2025-11-30 18:20:03', '2025-11-30 18:20:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'jhon mark genela', 'genelamark27@gmail.com', NULL, NULL, '$2y$12$3vkVpy.VtT9Xczx/03QM2uMY7E7apQt4yAuRYTUjCkIAmFTytHpj2', NULL, '2025-12-01 06:21:26', '2025-12-01 06:21:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Calderon Zer', 'calderonzer@gmail.com', 'CTU-3-5943CEFB', NULL, '$2y$12$HlKb//8wsfYBOtDzXRnFWO30p4PKu1kmb4IeSGrTyt.IMR/zXj1Sy', NULL, '2025-12-07 06:42:26', '2025-12-07 06:42:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_qr_login_tokens`
--
ALTER TABLE `admin_qr_login_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_qr_login_tokens_token_unique` (`token`),
  ADD KEY `admin_qr_login_tokens_admin_user_id_foreign` (`admin_user_id`),
  ADD KEY `admin_qr_login_tokens_token_is_used_expires_at_index` (`token`,`is_used`,`expires_at`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_users_email_unique` (`email`);

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicants_job_id_foreign` (`job_id`),
  ADD KEY `applicants_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `interviews`
--
ALTER TABLE `interviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `interviews_applicant_id_foreign` (`applicant_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_postings`
--
ALTER TABLE `job_postings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_applicant_id_foreign` (`applicant_id`),
  ADD KEY `messages_job_id_foreign` (`job_id`),
  ADD KEY `messages_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_qr_code_unique` (`qr_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_qr_login_tokens`
--
ALTER TABLE `admin_qr_login_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `interviews`
--
ALTER TABLE `interviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `job_postings`
--
ALTER TABLE `job_postings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_qr_login_tokens`
--
ALTER TABLE `admin_qr_login_tokens`
  ADD CONSTRAINT `admin_qr_login_tokens_admin_user_id_foreign` FOREIGN KEY (`admin_user_id`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `applicants`
--
ALTER TABLE `applicants`
  ADD CONSTRAINT `applicants_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `interviews`
--
ALTER TABLE `interviews`
  ADD CONSTRAINT `interviews_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
