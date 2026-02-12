-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2026 at 07:13 AM
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
-- Database: `im`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `app_code` varchar(30) DEFAULT NULL,
  `app_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `department_id` bigint(20) DEFAULT NULL,
  `owner_name` varchar(100) DEFAULT NULL,
  `business_criticality` varchar(20) DEFAULT NULL,
  `repository_url` varchar(255) DEFAULT NULL,
  `production_url` varchar(255) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  `last_updated` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `app_code`, `app_name`, `description`, `department_id`, `owner_name`, `business_criticality`, `repository_url`, `production_url`, `version`, `status_id`, `date_created`, `last_updated`, `created_at`, `updated_at`) VALUES
(2, '12345', 'app01', 'test app', 9, 'test user', 'Low', '', '', '1.0.0', 1, '2026-02-11', '2026-02-11', '2026-02-11 09:42:29', '2026-02-11 09:42:29');

-- --------------------------------------------------------

--
-- Table structure for table `application_contacts`
--

CREATE TABLE `application_contacts` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `application_logs`
--

CREATE TABLE `application_logs` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `action` varchar(100) DEFAULT NULL,
  `performed_by` varchar(100) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_logs`
--

INSERT INTO `application_logs` (`id`, `application_id`, `action`, `performed_by`, `action_date`) VALUES
(2, 2, 'Application Created', 'System', '2026-02-12 01:42:29');

-- --------------------------------------------------------

--
-- Table structure for table `application_status`
--

CREATE TABLE `application_status` (
  `id` int(11) NOT NULL,
  `status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_status`
--

INSERT INTO `application_status` (`id`, `status_name`) VALUES
(1, 'Active'),
(5, 'Decommissioned'),
(3, 'Development'),
(2, 'Inactive'),
(4, 'Maintenance');

-- --------------------------------------------------------

--
-- Table structure for table `application_technologies`
--

CREATE TABLE `application_technologies` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `technology_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` bigint(20) NOT NULL,
  `asset_tag` varchar(100) DEFAULT NULL,
  `box_number` varchar(100) DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `device_image` varchar(255) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `model_number` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `sender` varchar(255) NOT NULL,
  `recipient` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','ready to deploy','archived','broken - not fixable','lost/stolen','out for diagnostics','out for repair') NOT NULL DEFAULT 'pending',
  `date_updated` datetime DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `purchase_cost` decimal(10,2) DEFAULT NULL,
  `order_number` varchar(100) DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `requestable` tinyint(1) DEFAULT 0,
  `byod` tinyint(1) DEFAULT 0,
  `department_id` bigint(20) DEFAULT NULL,
  `location_id` bigint(20) DEFAULT NULL,
  `workstation_id` bigint(20) DEFAULT NULL,
  `assigned_to_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `asset_tag`, `box_number`, `barcode`, `device_image`, `serial_number`, `model`, `model_number`, `manufacturer`, `category`, `qty`, `sender`, `recipient`, `address`, `description`, `status`, `date_updated`, `purchase_date`, `purchase_cost`, `order_number`, `supplier`, `requestable`, `byod`, `department_id`, `location_id`, `workstation_id`, `assigned_to_user_id`, `created_at`, `updated_at`) VALUES
(13, 'LBC-PC-000123', NULL, '1770787998_3bfbe19b691df491b281.png', '1770787998_b40b18c23f142f456ef8.jpg', '1PC05JQ7P', 'lenovo 2021 01', '1234567890', 'lenovo', NULL, 1, '', '', '', NULL, 'pending', '2026-02-11 13:33:18', '2021-01-01', 6000.00, 'PO-2021-0001', 'MOA_LENOVO', 0, 0, 9, 6, 1, 4, '2026-02-11 21:33:18', '2026-02-11 21:33:18'),
(14, 'LBC-PC-00012', NULL, '1770788442_27df90f66b47ede35757.png', '1770788442_d4981bfb292f21b1e174.jpg', '1PC05JQ7', 'lenovo 2021 01', '123456789', 'lenovo', '7', 1, '', '', '', NULL, 'pending', '2026-02-11 13:40:42', '2021-01-01', 6000.00, 'PO-2021-0001', 'MOA_LENOVO', 1, 0, 14, 6, 1, 5, '2026-02-11 21:40:43', '2026-02-11 21:40:43');

-- --------------------------------------------------------

--
-- Table structure for table `asset_history`
--

CREATE TABLE `asset_history` (
  `id` int(11) NOT NULL,
  `asset_id` bigint(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL COMMENT 'created, updated, deleted, status_changed, assigned, unassigned, etc.',
  `field_name` varchar(100) DEFAULT NULL COMMENT 'Name of the field that was changed',
  `old_value` text DEFAULT NULL COMMENT 'Previous value before the change',
  `new_value` text DEFAULT NULL COMMENT 'New value after the change',
  `description` text DEFAULT NULL COMMENT 'Human-readable description of the change',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asset_history`
--

INSERT INTO `asset_history` (`id`, `asset_id`, `user_id`, `action`, `field_name`, `old_value`, `new_value`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(8, 13, 1, 'created', NULL, NULL, NULL, 'Asset created', '192.168.88.36', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-11 13:33:18'),
(9, 14, 1, 'created', NULL, NULL, NULL, 'Asset created', '192.168.88.36', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '2026-02-11 13:40:43');

-- --------------------------------------------------------

--
-- Table structure for table `asset_notes`
--

CREATE TABLE `asset_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `note` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asset_notes`
--

INSERT INTO `asset_notes` (`id`, `asset_id`, `user_id`, `note`, `created_at`, `updated_at`) VALUES
(1, 9, 1, 'asd', '2026-02-09 15:37:11', '2026-02-09 15:37:11'),
(2, 3, 1, 'asd', '2026-02-09 17:01:11', '2026-02-09 17:01:11'),
(3, 10, 1, 'asd', '2026-02-10 13:51:04', '2026-02-10 13:51:04');

-- --------------------------------------------------------

--
-- Table structure for table `assignable_users`
--

CREATE TABLE `assignable_users` (
  `id` bigint(20) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignable_users`
--

INSERT INTO `assignable_users` (`id`, `full_name`, `created_at`) VALUES
(4, 'gabriel angelo estacio', '2026-02-10 09:26:49'),
(5, 'joshua jay boncajes', '2026-02-10 09:40:12');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(7) DEFAULT '#0d6efd',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `color`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Laptops', 'Desktop and laptop computers', '#0d6efd', 1, '2026-02-09 17:51:44', '2026-02-09 17:51:44'),
(2, 'Monitors', 'Computer display monitors', '#28a745', 1, '2026-02-09 17:51:44', '2026-02-09 17:51:44'),
(3, 'Printers', 'Printing devices', '#ffc107', 1, '2026-02-09 17:51:44', '2026-02-09 17:51:44'),
(4, 'Networking', 'Network equipment and routers', '#17a2b8', 1, '2026-02-09 17:51:44', '2026-02-09 17:51:44'),
(6, 'Storage', 'Hard drives and storage devices', '#dc3545', 1, '2026-02-09 17:51:44', '2026-02-09 17:51:44'),
(7, 'CPU', 'CPU', '#000000', 1, '2026-02-10 14:16:22', '2026-02-10 14:19:28');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department_name`, `created_at`) VALUES
(9, 'IT-DEV-PSY', '2026-02-06 02:16:16'),
(14, 'IT-NETWORK-PSY', '2026-02-06 02:49:30'),
(15, 'IT-SUPPORT-PSY', '2026-02-06 02:50:01'),
(16, 'IT-TELCO-PSY', '2026-02-06 02:50:24');

-- --------------------------------------------------------

--
-- Table structure for table `environments`
--

CREATE TABLE `environments` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `environment_type` varchar(50) DEFAULT NULL,
  `server_id` int(11) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'ACTIVE',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) NOT NULL,
  `asset_id` bigint(20) NOT NULL,
  `item_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`item_description`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `created_at`) VALUES
(1, 'Antonio Arnaiz, Pasay City', '2026-02-06 02:16:16'),
(2, 'JB 6 Apartment, Manila', '2026-02-06 02:16:16'),
(3, 'F.B Harrison 2219, Makati', '2026-02-06 02:16:16'),
(4, '119 (sp) bay boulevard barangay 76, Pasay city', '2026-02-06 02:16:16'),
(5, 'Unit 8 Buendia Shopping Plaza, Makati', '2026-02-06 02:16:16'),
(6, '2 ECOM-CENTER PASAY CITY', '2026-02-06 02:26:32');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peripherals`
--

CREATE TABLE `peripherals` (
  `id` bigint(20) NOT NULL,
  `asset_id` bigint(20) DEFAULT NULL,
  `peripheral_type_id` bigint(20) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `model_number` varchar(100) DEFAULT NULL,
  `serial_number` varchar(150) DEFAULT NULL,
  `device_image` varchar(255) DEFAULT NULL,
  `department_id` bigint(20) DEFAULT NULL,
  `location_id` bigint(20) DEFAULT NULL,
  `assigned_to_user_id` bigint(20) DEFAULT NULL,
  `workstation_id` bigint(20) DEFAULT NULL,
  `status` enum('available','in_use','standby','under_repair','retired','lost') DEFAULT 'available',
  `condition_status` enum('new','good','fair','damaged') DEFAULT 'good',
  `criticality` enum('low','medium','high') DEFAULT 'medium',
  `purchase_date` date DEFAULT NULL,
  `purchase_cost` decimal(10,2) DEFAULT NULL,
  `order_number` varchar(100) DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `qty` int(11) DEFAULT 1,
  `requestable` tinyint(1) DEFAULT 0,
  `byod` tinyint(1) DEFAULT 0,
  `warranty_expiry` date DEFAULT NULL,
  `vendor` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peripherals`
--

INSERT INTO `peripherals` (`id`, `asset_id`, `peripheral_type_id`, `brand`, `model`, `model_number`, `serial_number`, `device_image`, `department_id`, `location_id`, `assigned_to_user_id`, `workstation_id`, `status`, `condition_status`, `criticality`, `purchase_date`, `purchase_cost`, `order_number`, `supplier`, `qty`, `requestable`, `byod`, `warranty_expiry`, `vendor`, `created_at`, `updated_at`) VALUES
(14, NULL, 9, 'LENOVO', 'lenovo mouse', '12345', '09876', '1770708671_6cd51410e0c30e10e192.webp', 14, 6, 5, 1, 'under_repair', 'fair', 'medium', '2026-02-09', 100.00, 'PO-2021-0003', 'MOA_LENOVO', 1, 0, 0, '2026-02-11', 'LENOVO', '2026-02-10 23:31:11', '2026-02-11 22:29:41'),
(15, 14, 9, 'LENOVO', 'lenovo mouse', '123456', '098765', NULL, 14, 6, 5, 1, 'available', 'new', 'low', '2026-02-09', 100.00, 'PO-2021-0003', 'MOA_LENOVO', 1, 0, 0, '2026-02-11', 'LENOVO', '2026-02-11 22:33:29', '2026-02-11 22:33:29');

-- --------------------------------------------------------

--
-- Table structure for table `peripheral_types`
--

CREATE TABLE `peripheral_types` (
  `id` bigint(20) NOT NULL,
  `type_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peripheral_types`
--

INSERT INTO `peripheral_types` (`id`, `type_name`, `created_at`) VALUES
(3, 'Network Switch', '2026-02-06 02:16:16'),
(5, 'NAS / Backup Storage', '2026-02-06 02:16:16'),
(6, 'LAN Adapter', '2026-02-06 02:16:16'),
(8, 'keyboard', '2026-02-10 03:19:26'),
(9, 'mouse', '2026-02-10 03:19:31');

-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

CREATE TABLE `servers` (
  `id` int(11) NOT NULL,
  `server_name` varchar(100) NOT NULL,
  `server_type` varchar(50) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'ACTIVE',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `technologies`
--

CREATE TABLE `technologies` (
  `id` int(11) NOT NULL,
  `technology_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) NOT NULL,
  `unit_name` varchar(120) NOT NULL,
  `unit_type` enum('asset','peripheral','both') NOT NULL,
  `asset_id` bigint(20) DEFAULT NULL,
  `peripheral_id` bigint(20) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `unit_name`, `unit_type`, `asset_id`, `peripheral_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'BOX-003', 'asset', 3, NULL, 'next', '2026-02-06 08:12:42', '2026-02-06 08:12:42');

-- --------------------------------------------------------

--
-- Table structure for table `unit_assets`
--

CREATE TABLE `unit_assets` (
  `id` bigint(20) NOT NULL,
  `unit_id` bigint(20) NOT NULL,
  `asset_id` bigint(20) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unit_peripherals`
--

CREATE TABLE `unit_peripherals` (
  `id` bigint(20) NOT NULL,
  `unit_id` bigint(20) NOT NULL,
  `peripheral_id` bigint(20) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `usertype` enum('readonly','readandwrite','superadmin') NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `usertype`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'tesT001', 'test001@gmail.com', '$2y$10$10J.TtfCYnj6oOot./K3UOP3vZodFJjdoXIdYWCEAB8A6j82VSOTO', 'superadmin', '2026-02-05 02:51:15', '2026-02-05 02:53:33', NULL),
(2, 'joshua jay boncajes', 'jj@gmail.com', '$2y$10$c43GGU9U.arsdyZwJZHvLuggmyAb8kxUrRbYMm27SJ86nIbztRyQu', 'readandwrite', '2026-02-10 16:58:30', '2026-02-10 16:58:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workstations`
--

CREATE TABLE `workstations` (
  `id` bigint(20) NOT NULL,
  `workstation_code` varchar(50) NOT NULL,
  `location_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workstations`
--

INSERT INTO `workstations` (`id`, `workstation_code`, `location_id`, `created_at`) VALUES
(1, 'LBC-PSY-IT-001', 6, '2026-02-06 02:16:25'),
(2, 'LBC-PSY-IT-002', 6, '2026-02-06 02:16:25'),
(3, 'LBC-PSY-IT-003', 6, '2026-02-06 02:16:25'),
(4, 'LBC-PSY-IT-004', 6, '2026-02-06 02:16:25'),
(5, 'LBC-PSY-FIN-001', 6, '2026-02-06 02:16:25'),
(6, 'LBC-PSY-FIN-002', 6, '2026-02-06 02:16:25'),
(7, 'LBC-PSY-FIN-003', 6, '2026-02-06 02:16:25'),
(8, 'LBC-PSY-ACC-001', 6, '2026-02-06 02:16:25'),
(9, 'LBC-PSY-ACC-002', 6, '2026-02-06 02:16:25'),
(10, 'LBC-PSY-ACC-003', 6, '2026-02-06 02:16:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_code` (`app_code`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `application_contacts`
--
ALTER TABLE `application_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `application_logs`
--
ALTER TABLE `application_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `application_status`
--
ALTER TABLE `application_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `status_name` (`status_name`);

--
-- Indexes for table `application_technologies`
--
ALTER TABLE `application_technologies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `technology_id` (`technology_id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracking_number` (`asset_tag`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `fk_assets_department` (`department_id`),
  ADD KEY `fk_assets_location` (`location_id`),
  ADD KEY `fk_assets_workstation` (`workstation_id`),
  ADD KEY `fk_assets_assigned_user` (`assigned_to_user_id`);

--
-- Indexes for table `asset_history`
--
ALTER TABLE `asset_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asset_id` (`asset_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `asset_notes`
--
ALTER TABLE `asset_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asset_id` (`asset_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `assignable_users`
--
ALTER TABLE `assignable_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `full_name` (`full_name`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_name` (`department_name`);

--
-- Indexes for table `environments`
--
ALTER TABLE `environments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `server_id` (`server_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_items_asset` (`asset_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peripherals`
--
ALTER TABLE `peripherals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial_number` (`serial_number`),
  ADD KEY `fk_peripherals_asset` (`asset_id`);

--
-- Indexes for table `peripheral_types`
--
ALTER TABLE `peripheral_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- Indexes for table `servers`
--
ALTER TABLE `servers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `technologies`
--
ALTER TABLE `technologies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `technology_name` (`technology_name`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asset_id` (`asset_id`),
  ADD KEY `peripheral_id` (`peripheral_id`);

--
-- Indexes for table `unit_assets`
--
ALTER TABLE `unit_assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_unit_asset` (`unit_id`,`asset_id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `asset_id` (`asset_id`);

--
-- Indexes for table `unit_peripherals`
--
ALTER TABLE `unit_peripherals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_unit_peripheral` (`unit_id`,`peripheral_id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `peripheral_id` (`peripheral_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `workstations`
--
ALTER TABLE `workstations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `workstation_code` (`workstation_code`),
  ADD KEY `location_id` (`location_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `application_contacts`
--
ALTER TABLE `application_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `application_logs`
--
ALTER TABLE `application_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `application_status`
--
ALTER TABLE `application_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `application_technologies`
--
ALTER TABLE `application_technologies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `asset_history`
--
ALTER TABLE `asset_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `asset_notes`
--
ALTER TABLE `asset_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `assignable_users`
--
ALTER TABLE `assignable_users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `environments`
--
ALTER TABLE `environments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peripherals`
--
ALTER TABLE `peripherals`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `peripheral_types`
--
ALTER TABLE `peripheral_types`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `servers`
--
ALTER TABLE `servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `technologies`
--
ALTER TABLE `technologies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `unit_assets`
--
ALTER TABLE `unit_assets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unit_peripherals`
--
ALTER TABLE `unit_peripherals`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `workstations`
--
ALTER TABLE `workstations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `application_status` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `application_contacts`
--
ALTER TABLE `application_contacts`
  ADD CONSTRAINT `application_contacts_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `application_logs`
--
ALTER TABLE `application_logs`
  ADD CONSTRAINT `application_logs_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `application_technologies`
--
ALTER TABLE `application_technologies`
  ADD CONSTRAINT `application_technologies_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `application_technologies_ibfk_2` FOREIGN KEY (`technology_id`) REFERENCES `technologies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `fk_assets_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_assets_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_assets_workstation` FOREIGN KEY (`workstation_id`) REFERENCES `workstations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `asset_history`
--
ALTER TABLE `asset_history`
  ADD CONSTRAINT `fk_asset_history_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asset_history_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `environments`
--
ALTER TABLE `environments`
  ADD CONSTRAINT `environments_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `environments_ibfk_2` FOREIGN KEY (`server_id`) REFERENCES `servers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_items_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peripherals`
--
ALTER TABLE `peripherals`
  ADD CONSTRAINT `fk_peripherals_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `unit_assets`
--
ALTER TABLE `unit_assets`
  ADD CONSTRAINT `fk_unit_assets_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_unit_assets_unit` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `unit_peripherals`
--
ALTER TABLE `unit_peripherals`
  ADD CONSTRAINT `fk_unit_peripherals_peripheral` FOREIGN KEY (`peripheral_id`) REFERENCES `peripherals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_unit_peripherals_unit` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workstations`
--
ALTER TABLE `workstations`
  ADD CONSTRAINT `workstations_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
