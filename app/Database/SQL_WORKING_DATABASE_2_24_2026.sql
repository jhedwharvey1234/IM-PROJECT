-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2026 at 10:18 AM
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `app_category` varchar(100) DEFAULT NULL COMMENT 'Core System, Support, Reporting, Customer-facing, etc.',
  `app_type` varchar(100) DEFAULT NULL COMMENT 'Web, Mobile, Desktop, API, Batch, SaaS',
  `business_purpose` text DEFAULT NULL COMMENT 'Business Purpose',
  `lifecycle_stage` varchar(50) DEFAULT NULL COMMENT 'Development, Active, Maintenance, Sunset',
  `eol_date` date DEFAULT NULL COMMENT 'End-of-Life Date',
  `replacement_system` varchar(255) DEFAULT NULL COMMENT 'Replacement System Name/ID',
  `upgrade_roadmap` text DEFAULT NULL COMMENT 'Planned upgrades and timeline',
  `last_major_upgrade` date DEFAULT NULL COMMENT 'Last Major Upgrade Date',
  `data_classification` varchar(50) DEFAULT NULL COMMENT 'Public, Internal, Confidential, Sensitive',
  `personal_data_flag` tinyint(1) DEFAULT 0 COMMENT 'Contains Personal Data (0=No, 1=Yes)',
  `authentication_type` varchar(100) DEFAULT NULL COMMENT 'SSO, AD, OAuth, etc.',
  `encryption_enabled` tinyint(1) DEFAULT 0 COMMENT 'Encryption enabled (0=No, 1=Yes)',
  `last_security_review` date DEFAULT NULL COMMENT 'Last Security Review Date',
  `availability_sla` varchar(50) DEFAULT NULL COMMENT 'SLA percentage (e.g., 99.9%)',
  `business_impact` text DEFAULT NULL COMMENT 'Business Impact if Down',
  `peak_users` int(11) DEFAULT NULL COMMENT 'Peak Concurrent Users',
  `monitoring_tool` varchar(100) DEFAULT NULL COMMENT 'Tool used for monitoring',
  `annual_cost` decimal(12,2) DEFAULT NULL COMMENT 'Annual Cost/Budget',
  `license_type` varchar(100) DEFAULT NULL COMMENT 'License Type',
  `license_expiry` date DEFAULT NULL COMMENT 'License Expiry Date',
  `vendor_contract_ref` varchar(255) DEFAULT NULL COMMENT 'Vendor Contract Reference',
  `cloud_subscription_details` text DEFAULT NULL COMMENT 'Cloud Subscription Details (if SaaS)',
  `retirement_date` date DEFAULT NULL COMMENT 'Planned date to fully retire the application',
  `sunset_notification_date` date DEFAULT NULL COMMENT 'Date to notify stakeholders before retirement',
  `archive_date` date DEFAULT NULL COMMENT 'Application archive date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `app_code`, `app_name`, `description`, `department_id`, `owner_name`, `business_criticality`, `repository_url`, `production_url`, `version`, `status_id`, `date_created`, `last_updated`, `created_at`, `updated_at`, `app_category`, `app_type`, `business_purpose`, `lifecycle_stage`, `eol_date`, `replacement_system`, `upgrade_roadmap`, `last_major_upgrade`, `data_classification`, `personal_data_flag`, `authentication_type`, `encryption_enabled`, `last_security_review`, `availability_sla`, `business_impact`, `peak_users`, `monitoring_tool`, `annual_cost`, `license_type`, `license_expiry`, `vendor_contract_ref`, `cloud_subscription_details`, `retirement_date`, `sunset_notification_date`, `archive_date`) VALUES
(3, '123456', 'app01', 'test app', 9, 'test user', 'Low', 'https://example.com', 'https://example.com', '1.0.0', 1, '2026-02-12', '2026-02-12', '2026-02-12 08:27:21', '2026-02-12 08:35:33', 'inventory', 'Web', 'to track and manage assets', 'Development', '2026-03-14', 'example', 'example', '2026-02-12', 'Public', 0, 'Internal', 0, '2026-02-12', '99%', 'example', 1, 'example', 1000.00, 'example', '2026-03-14', 'example', 'example', NULL, NULL, NULL),
(4, 'APP-001', 'inventory001', 'this application is about inventory', 9, 'LBC- Jhed Wharvey ', 'Medium', 'https://example.com', 'https://example.com', '1.0.1', 3, '2026-02-18', '2026-02-18', '2026-02-18 05:13:53', '2026-02-18 05:13:53', 'core system', 'Web', 'LBC inventory', 'Development', '2030-01-01', 'TEST APP ', 'next week', '2026-02-18', 'Public', 0, 'Internal', 0, '2026-02-18', '99.9%', 'tracking', 20, 'dev tool', 3000.00, 'hosting', '2030-01-01', '00111', 'per year', NULL, NULL, NULL),
(5, 'APP-002', 'inventory002', 'app number 2', 9, 'LBC- Jhed Wharvey ', 'Medium', 'https://example.com', 'https://example.com', '1.0.2', 3, '2026-02-18', '2026-02-18', '2026-02-18 05:30:37', '2026-02-18 05:30:37', 'core system', 'Web', 'inv 2', 'Development', '2030-01-01', 'TEST APP ', 'test', '2026-02-18', 'Public', 1, 'Internal', 1, '2026-02-18', '99.9%', 'test', 20, 'dev tool', 3000.00, 'hosting', '2030-01-01', '00111', 'asd', '2030-01-04', '2026-02-18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `application_contacts`
--

CREATE TABLE `application_contacts` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `application_contacts_extended`
--

CREATE TABLE `application_contacts_extended` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL COMMENT 'Business Owner, Technical Owner, Support Team, Escalation Contact, etc.',
  `department` varchar(100) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `application_dependencies`
--

CREATE TABLE `application_dependencies` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `dependency_type` varchar(50) DEFAULT NULL COMMENT 'Upstream, Downstream, Integration',
  `dependent_system` varchar(150) DEFAULT NULL,
  `dependent_app_id` int(11) DEFAULT NULL,
  `api_endpoint` varchar(255) DEFAULT NULL,
  `integration_type` varchar(100) DEFAULT NULL COMMENT 'API, MQ, ETL, FILE, etc.',
  `is_critical` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `application_environments`
--

CREATE TABLE `application_environments` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `environment_type` varchar(50) DEFAULT NULL COMMENT 'Production, UAT, QA, Development, DR',
  `server_id` int(11) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `hostname` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  `data_center` varchar(100) DEFAULT NULL,
  `environment_status` varchar(20) DEFAULT 'ACTIVE',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
(4, 3, 'Application Created', 'System', '2026-02-12 08:27:22'),
(5, 3, 'Application Updated', 'System', '2026-02-12 08:35:33'),
(6, 4, 'Application Created', 'System', '2026-02-18 05:13:54'),
(7, 5, 'Application Created', 'System', '2026-02-18 05:30:37');

-- --------------------------------------------------------

--
-- Table structure for table `application_related_data`
--

CREATE TABLE `application_related_data` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `relation` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_related_data`
--

INSERT INTO `application_related_data` (`id`, `application_id`, `title`, `link`, `description`, `relation`, `created_at`, `updated_at`) VALUES
(1, 5, 'test', 'https://example.com', 'asd', 'api', '2026-02-18 06:26:24', '2026-02-18 06:26:24');

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

--
-- Dumping data for table `application_technologies`
--

INSERT INTO `application_technologies` (`id`, `application_id`, `technology_id`) VALUES
(2, 3, 1),
(3, 4, 1),
(4, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `application_vendors`
--

CREATE TABLE `application_vendors` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `vendor_name` varchar(150) DEFAULT NULL,
  `vendor_type` varchar(100) DEFAULT NULL COMMENT 'SaaS, Middleware, API Provider, On-Premise, etc.',
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `support_hours` varchar(100) DEFAULT NULL,
  `sla_details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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
-- Table structure for table `batch_jobs`
--

CREATE TABLE `batch_jobs` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `job_name` varchar(100) DEFAULT NULL,
  `job_description` text DEFAULT NULL,
  `schedule` varchar(100) DEFAULT NULL COMMENT 'Cron expression or frequency',
  `execution_duration` varchar(50) DEFAULT NULL,
  `last_run` datetime DEFAULT NULL,
  `next_run` datetime DEFAULT NULL,
  `job_status` varchar(50) DEFAULT 'ENABLED',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `compliance_security`
--

CREATE TABLE `compliance_security` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `data_classification` varchar(50) DEFAULT NULL,
  `personal_data_flag` tinyint(1) DEFAULT 0,
  `compliance_requirements` varchar(255) DEFAULT NULL COMMENT 'PCI, GDPR, HIPAA, ISO, etc.',
  `authentication_type` varchar(100) DEFAULT NULL,
  `encryption_enabled` tinyint(1) DEFAULT 0,
  `encryption_method` varchar(100) DEFAULT NULL,
  `vulnerability_scan_status` varchar(50) DEFAULT NULL COMMENT 'Pending, Completed, Failed',
  `last_vulnerability_scan` date DEFAULT NULL,
  `last_security_review` date DEFAULT NULL,
  `security_certifications` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `compliance_security`
--

INSERT INTO `compliance_security` (`id`, `application_id`, `data_classification`, `personal_data_flag`, `compliance_requirements`, `authentication_type`, `encryption_enabled`, `encryption_method`, `vulnerability_scan_status`, `last_vulnerability_scan`, `last_security_review`, `security_certifications`, `created_at`, `updated_at`) VALUES
(1, 3, 'Public', 0, 'PCI', 'Internal', 0, NULL, 'peding', NULL, '2026-02-12', NULL, '2026-02-12 08:27:21', '2026-02-12 08:44:52'),
(2, 4, 'Public', 0, 'none', 'Internal', 0, NULL, NULL, NULL, '2026-02-18', NULL, '2026-02-18 05:13:54', '2026-02-18 05:13:54'),
(3, 5, 'Public', 1, 'none', 'Internal', 1, 'aes-256', 'Pending', '2026-02-18', '2026-02-18', 'soc2', '2026-02-18 05:30:37', '2026-02-18 05:30:37');

-- --------------------------------------------------------

--
-- Table structure for table `dcfs`
--

CREATE TABLE `dcfs` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `department_id` bigint(20) DEFAULT NULL,
  `share_token` varchar(64) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dcfs`
--

INSERT INTO `dcfs` (`id`, `title`, `name`, `description`, `due_date`, `department_id`, `share_token`, `is_active`, `created_at`, `updated_at`) VALUES
(7, 'test DCF 3', 'test_dcf_3_1771834559', 'qwe', '2026-02-28', 14, '3f4545d9fff0b9821d1a9eb6f896689262c47fb7a7db0911eb685311625ee38d', 1, '2026-02-23 16:15:59', '2026-02-23 16:15:59'),
(8, 'TEST DCF', 'test_dcf_1771901544', 'this is to test the DCF answering sheets', '2026-02-27', 9, '8077c9d50e4cc40ad940f33f8562b6039a2d8bf6e88cdde8ae96eabf86c2b7de', 1, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(13, 'TEST DCF COMPLETE ANSWER TYPES', 'test_dcf_complete_answer_types_1771918290', 'TEST DCF COMPLETE ANSWER TYPES', '2026-02-28', 16, 'e5e154e526766fd85d70f112955978b8080d4c4256810c548c2f604f7028c446', 1, '2026-02-24 15:31:31', '2026-02-24 15:31:31'),
(17, 'test dcf im', 'test_dcf_im_1771921895', 'im', '2026-03-14', 16, 'da7d25dbbb8d3701c3f1f9b1c7fe6bcc6531cb7e6c231e7bd6541a3379c6aae0', 1, '2026-02-24 16:31:35', '2026-02-24 16:31:35');

-- --------------------------------------------------------

--
-- Table structure for table `dcf_parts`
--

CREATE TABLE `dcf_parts` (
  `id` int(10) UNSIGNED NOT NULL,
  `dcf_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dcf_parts`
--

INSERT INTO `dcf_parts` (`id`, `dcf_id`, `title`, `description`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 7, 'part 1', 'test 1', 1, '2026-02-23 16:15:59', '2026-02-23 16:15:59'),
(2, 7, 'part 2', 'test 2', 2, '2026-02-23 16:15:59', '2026-02-23 16:15:59'),
(3, 8, 'UI', 'this part is about UI for multiple choice, checkbox, dropdown', 1, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(4, 8, 'UI 2', 'paragraphs and short answers', 2, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(5, 8, 'rate me', 'rate me knob', 3, '2026-02-24 10:52:26', '2026-02-24 10:52:26'),
(10, 13, 'CHARTS', 'FOR CHARTS', 1, '2026-02-24 15:31:31', '2026-02-24 15:31:31'),
(11, 13, 'TEXTS', 'For texts', 2, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(12, 13, 'RATING', 'for rating', 3, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(16, 17, 'im', 'im', 1, '2026-02-24 16:31:35', '2026-02-24 16:31:35');

-- --------------------------------------------------------

--
-- Table structure for table `dcf_questions`
--

CREATE TABLE `dcf_questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `dcf_id` int(10) UNSIGNED NOT NULL,
  `part_id` int(10) UNSIGNED DEFAULT NULL,
  `question_text` text NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `answer_type` enum('multiple_choice','checkbox','dropdown','short_answer','paragraph','rate_me','wysiwyg','advance_checkbox') NOT NULL,
  `allow_multiple` tinyint(1) NOT NULL DEFAULT 0,
  `rate_min` int(11) DEFAULT NULL,
  `rate_max` int(11) DEFAULT NULL,
  `grid_rows` text DEFAULT NULL,
  `grid_columns` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dcf_questions`
--

INSERT INTO `dcf_questions` (`id`, `dcf_id`, `part_id`, `question_text`, `is_required`, `answer_type`, `allow_multiple`, `rate_min`, `rate_max`, `grid_rows`, `grid_columns`, `sort_order`, `created_at`, `updated_at`) VALUES
(11, 7, 1, 'q1', 0, 'rate_me', 0, NULL, NULL, NULL, NULL, 1, '2026-02-23 16:15:59', '2026-02-23 16:15:59'),
(12, 7, 2, 'q2', 0, 'short_answer', 0, NULL, NULL, NULL, NULL, 1, '2026-02-23 16:15:59', '2026-02-23 16:15:59'),
(13, 8, 3, 'multiple choice', 0, 'multiple_choice', 0, NULL, NULL, NULL, NULL, 1, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(14, 8, 3, 'checkbox', 0, 'checkbox', 0, NULL, NULL, NULL, NULL, 2, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(15, 8, 3, 'dropdown', 0, 'dropdown', 0, NULL, NULL, NULL, NULL, 3, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(16, 8, 4, 's1', 0, 'short_answer', 0, NULL, NULL, NULL, NULL, 1, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(17, 8, 4, 'p1', 0, 'paragraph', 0, NULL, NULL, NULL, NULL, 2, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(18, 8, 5, 'rate me 1', 0, 'rate_me', 0, 1, 10, NULL, NULL, 1, '2026-02-24 10:52:26', '2026-02-24 10:52:26'),
(23, 13, 10, 'Choose a letter', 1, 'multiple_choice', 0, NULL, NULL, NULL, NULL, 1, '2026-02-24 15:31:31', '2026-02-24 15:31:31'),
(24, 13, 10, 'Select a number', 0, 'checkbox', 0, NULL, NULL, NULL, NULL, 2, '2026-02-24 15:31:31', '2026-02-24 15:31:31'),
(25, 13, 10, 'Select a name', 0, 'checkbox', 0, NULL, NULL, NULL, NULL, 3, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(26, 13, 10, 'Select Station', 0, 'dropdown', 0, NULL, NULL, NULL, NULL, 4, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(27, 13, 10, 'check the box', 0, 'advance_checkbox', 0, NULL, NULL, '[\"the place\",\"the transpo\",\"the people\",\"the vibe\"]', '[\"bad\",\"good\",\"very good\"]', 5, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(28, 13, 11, 'tell me something short', 0, 'short_answer', 0, NULL, NULL, NULL, NULL, 1, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(29, 13, 11, 'explain how atm works', 0, 'paragraph', 0, NULL, NULL, NULL, NULL, 2, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(30, 13, 11, 'complete details about computer', 0, 'wysiwyg', 0, NULL, NULL, NULL, NULL, 3, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(31, 13, 12, 'is this helpful', 0, 'rate_me', 0, 1, 10, NULL, NULL, 1, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(35, 17, 16, 'im', 0, 'wysiwyg', 0, NULL, NULL, NULL, NULL, 1, '2026-02-24 16:31:35', '2026-02-24 16:31:35');

-- --------------------------------------------------------

--
-- Table structure for table `dcf_question_options`
--

CREATE TABLE `dcf_question_options` (
  `id` int(10) UNSIGNED NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `option_text` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dcf_question_options`
--

INSERT INTO `dcf_question_options` (`id`, `question_id`, `option_text`, `sort_order`, `created_at`, `updated_at`) VALUES
(19, 13, 'm 1', 1, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(20, 13, 'm 2', 2, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(21, 13, 'm 3', 3, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(22, 14, 'check 1', 1, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(23, 14, 'check 2', 2, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(24, 14, 'check 3', 3, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(25, 15, 'd1', 1, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(26, 15, 'd2', 2, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(27, 15, 'd3', 3, '2026-02-24 10:52:25', '2026-02-24 10:52:25'),
(28, 23, '1', 1, '2026-02-24 15:31:31', '2026-02-24 15:31:31'),
(29, 23, '2', 2, '2026-02-24 15:31:31', '2026-02-24 15:31:31'),
(30, 23, '3', 3, '2026-02-24 15:31:31', '2026-02-24 15:31:31'),
(31, 24, '1', 1, '2026-02-24 15:31:31', '2026-02-24 15:31:31'),
(32, 24, '2', 2, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(33, 24, '3', 3, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(34, 24, '4', 4, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(35, 25, 'jay', 1, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(36, 25, 'jo', 2, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(37, 25, 'jan', 3, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(38, 25, 'jen', 4, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(39, 26, 'pasay', 1, '2026-02-24 15:31:32', '2026-02-24 15:31:32'),
(40, 26, 'sucat', 2, '2026-02-24 15:31:32', '2026-02-24 15:31:32');

-- --------------------------------------------------------

--
-- Table structure for table `dcf_responses`
--

CREATE TABLE `dcf_responses` (
  `id` int(10) UNSIGNED NOT NULL,
  `dcf_id` int(10) UNSIGNED NOT NULL,
  `respondent_name` varchar(255) NOT NULL,
  `respondent_mobile` varchar(50) DEFAULT NULL,
  `respondent_email` varchar(255) DEFAULT NULL,
  `user_consent` tinyint(1) DEFAULT 0,
  `consent_timestamp` datetime DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dcf_responses`
--

INSERT INTO `dcf_responses` (`id`, `dcf_id`, `respondent_name`, `respondent_mobile`, `respondent_email`, `user_consent`, `consent_timestamp`, `submitted_at`, `ip_address`, `user_agent`) VALUES
(13, 8, 'jwe', '09123456788', 'jwe@gmail.com', 1, '2026-02-24 14:36:08', '2026-02-24 14:36:08', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0'),
(14, 8, 'jwer', '09123456787', 'jwer@gmail.com', 1, '2026-02-24 14:36:34', '2026-02-24 14:36:34', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0'),
(15, 8, 'jwert', '09123456786', 'jwert@gmail.com', 1, '2026-02-24 14:36:57', '2026-02-24 14:36:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0'),
(16, 13, 'jw', '09123456780', 'jw@gmail.com', 1, '2026-02-24 15:38:52', '2026-02-24 15:38:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0'),
(17, 13, 'jwe', '09123456787', 'jwe@gmail.com', 1, '2026-02-24 15:40:24', '2026-02-24 15:40:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0'),
(18, 13, 'jwer', '09123456781', 'jwer@gmail.com', 1, '2026-02-24 15:41:16', '2026-02-24 15:41:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0'),
(19, 13, 'jwert', '09123456782', 'jwert@gmail.com', 1, '2026-02-24 15:42:02', '2026-02-24 15:42:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0'),
(23, 17, 'jwert', '09123456782', 'jwert@gmail.com', 1, '2026-02-24 16:31:54', '2026-02-24 16:31:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0');

-- --------------------------------------------------------

--
-- Table structure for table `dcf_response_answers`
--

CREATE TABLE `dcf_response_answers` (
  `id` int(10) UNSIGNED NOT NULL,
  `response_id` int(10) UNSIGNED NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `answer_text` mediumtext DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dcf_response_answers`
--

INSERT INTO `dcf_response_answers` (`id`, `response_id`, `question_id`, `answer_text`, `created_at`) VALUES
(25, 13, 13, 'm 1', '2026-02-24 14:36:08'),
(26, 13, 14, '[\"check 1\"]', '2026-02-24 14:36:08'),
(27, 13, 15, 'd1', '2026-02-24 14:36:08'),
(28, 13, 16, 'test', '2026-02-24 14:36:08'),
(29, 13, 17, 'test', '2026-02-24 14:36:08'),
(30, 13, 18, '10', '2026-02-24 14:36:08'),
(31, 14, 13, 'm 2', '2026-02-24 14:36:34'),
(32, 14, 14, '[\"check 2\"]', '2026-02-24 14:36:34'),
(33, 14, 15, 'd2', '2026-02-24 14:36:34'),
(34, 14, 16, 'test 2', '2026-02-24 14:36:34'),
(35, 14, 17, 'test 2', '2026-02-24 14:36:34'),
(36, 14, 18, '9', '2026-02-24 14:36:34'),
(37, 15, 13, 'm 3', '2026-02-24 14:36:57'),
(38, 15, 14, '[\"check 3\"]', '2026-02-24 14:36:57'),
(39, 15, 15, 'd3', '2026-02-24 14:36:57'),
(40, 15, 16, 'test 3', '2026-02-24 14:36:57'),
(41, 15, 17, 'test 3', '2026-02-24 14:36:57'),
(42, 15, 18, '8', '2026-02-24 14:36:57'),
(43, 16, 23, '1', '2026-02-24 15:38:53'),
(44, 16, 24, '[\"1\"]', '2026-02-24 15:38:53'),
(45, 16, 25, '[\"jay\"]', '2026-02-24 15:38:53'),
(46, 16, 26, 'pasay', '2026-02-24 15:38:53'),
(47, 16, 27, '[[\"bad\"],[\"bad\"],[\"bad\"],[\"bad\"]]', '2026-02-24 15:38:53'),
(48, 16, 28, 'short', '2026-02-24 15:38:53'),
(49, 16, 29, 'ATMs (Automated Teller Machines) allow users to perform banking transactions by securely reading cards, verifying PINs, communicating with banks, and dispensing cash or processing deposits automatically.', '2026-02-24 15:38:53'),
(50, 16, 30, '<p>Today\'s&nbsp;<strong>computers</strong>&nbsp;are electronic devices that accept data (input), process that data, produce output, and store (storage) the results (IPOS). Computer overview. History of the&nbsp;<strong>computer</strong>. How are&nbsp;<strong>computers</strong>&nbsp;used today? What components make up a desktop&nbsp;<strong>computer</strong>?<img src=\"../../../uploads/dcf-images/1771918713_545f601349ff92086be1.jpg\" alt=\"\" width=\"2500\" height=\"2500\"></p>', '2026-02-24 15:38:53'),
(51, 16, 31, '1', '2026-02-24 15:38:53'),
(52, 17, 23, '1', '2026-02-24 15:40:24'),
(53, 17, 24, '[\"2\"]', '2026-02-24 15:40:24'),
(54, 17, 25, '[\"jan\"]', '2026-02-24 15:40:24'),
(55, 17, 26, 'sucat', '2026-02-24 15:40:24'),
(56, 17, 27, '[[\"good\"],[\"good\"],[\"good\"],[\"good\"]]', '2026-02-24 15:40:24'),
(57, 17, 28, 'this is short', '2026-02-24 15:40:24'),
(58, 17, 29, 'ATMs (Automated Teller Machines) allow users to perform banking transactions by securely reading cards, verifying PINs, communicating with banks, and dispensing cash or processing deposits automatically.', '2026-02-24 15:40:24'),
(59, 17, 30, '<p>asd<img src=\"../../../uploads/dcf-images/1771918818_4a8bc838a8aa766ba195.webp\" alt=\"\" width=\"222\" height=\"194\"></p>', '2026-02-24 15:40:24'),
(60, 17, 31, '10', '2026-02-24 15:40:24'),
(61, 18, 23, '2', '2026-02-24 15:41:16'),
(62, 18, 24, '[\"4\"]', '2026-02-24 15:41:16'),
(63, 18, 25, '[\"jen\"]', '2026-02-24 15:41:16'),
(64, 18, 26, 'pasay', '2026-02-24 15:41:16'),
(65, 18, 27, '[[\"good\"],[\"very good\"],[\"very good\"],[\"good\"]]', '2026-02-24 15:41:16'),
(66, 18, 28, 'sadasd', '2026-02-24 15:41:16'),
(67, 18, 29, 'asdwd', '2026-02-24 15:41:16'),
(68, 18, 30, '<p>hjkhjkhjk<img src=\"../../../uploads/dcf-images/1771918872_3b8b434156eceec48f40.webp\" alt=\"\" width=\"332\" height=\"130\"></p>', '2026-02-24 15:41:16'),
(69, 18, 31, '7', '2026-02-24 15:41:16'),
(70, 19, 23, '3', '2026-02-24 15:42:02'),
(71, 19, 24, '[\"4\"]', '2026-02-24 15:42:02'),
(72, 19, 25, '[\"jo\"]', '2026-02-24 15:42:02'),
(73, 19, 26, '', '2026-02-24 15:42:02'),
(74, 19, 27, '[[\"bad\"],[\"good\"],[\"very good\"],[\"bad\"]]', '2026-02-24 15:42:02'),
(75, 19, 28, 'shrottasdas d', '2026-02-24 15:42:02'),
(76, 19, 29, 'asd awead', '2026-02-24 15:42:02'),
(77, 19, 30, '<p><img src=\"../../../uploads/dcf-images/1771918918_510c3ad42c6f43072c40.png\" alt=\"\" width=\"380\" height=\"190\"></p>', '2026-02-24 15:42:02'),
(78, 19, 31, '5', '2026-02-24 15:42:02'),
(82, 23, 35, '<p>asd<img src=\"http://localhost/IM/uploads/dcf-images/1771921912_fa5458f0ae80edded5e4.webp\" alt=\"\" width=\"222\" height=\"194\"></p>', '2026-02-24 16:31:54');

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
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `document_category_id` int(10) UNSIGNED DEFAULT NULL,
  `document_type_id` int(10) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `details` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `title`, `subject`, `document_category_id`, `document_type_id`, `description`, `details`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'test doc', 'RA 1', NULL, NULL, 'testing documentation', NULL, 1, '2026-02-18 14:48:31', '2026-02-18 14:48:31'),
(2, 'test 002', 'ra2', NULL, NULL, 'eto ay kailangan mahanap', NULL, 1, '2026-02-18 15:16:14', '2026-02-18 15:16:14'),
(3, 'testing document', 'request report', NULL, NULL, 'testing document ', '<p style=\"text-align: center; padding-left: 40px;\">testing detail <span style=\"font-family: \'arial black\', sans-serif; font-size: 24pt;\">asd <img src=\"data:image/webp;base64,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\" alt=\"\" width=\"222\" height=\"194\"><span style=\"color: #2dc26b;\">asd</span></span></p>', 1, '2026-02-19 15:22:09', '2026-02-19 15:22:09'),
(4, 'test category and type', 'test category and type', 1, 1, ' teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset tesettesetteset teset teset teset teset teset tesetteset teset teset teset teset tesettesettesettesettesetteset teset teset teset teset teset teset teset tesetteset', '<p>&nbsp;teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset teset tesettesetteset teset teset teset teset teset tesetteset teset teset teset teset tesettesettesettesettesetteset teset teset teset teset teset teset teset tesetteset<img src=\"data:image/webp;base64,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\" alt=\"\" width=\"222\" height=\"194\"></p>', 1, '2026-02-19 16:26:00', '2026-02-19 16:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `document_alerts`
--

CREATE TABLE `document_alerts` (
  `id` int(10) UNSIGNED NOT NULL,
  `document_id` int(10) UNSIGNED NOT NULL,
  `alert_date` date NOT NULL,
  `alert_time` time DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_notified` tinyint(1) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_alerts`
--

INSERT INTO `document_alerts` (`id`, `document_id`, `alert_date`, `alert_time`, `description`, `is_notified`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 2, '2026-02-19', '09:00:00', 'test', 1, 1, '2026-02-18 15:51:53', '2026-02-19 13:52:56'),
(2, 2, '2026-02-19', '13:55:00', 'test agg', 1, 1, '2026-02-19 13:53:30', '2026-02-19 13:55:04');

-- --------------------------------------------------------

--
-- Table structure for table `document_categories`
--

CREATE TABLE `document_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_categories`
--

INSERT INTO `document_categories` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'IT', 'for IT', 1, '2026-02-19 16:24:11', '2026-02-19 16:24:11'),
(2, 'FINANCE', 'for finance', 1, '2026-02-19 17:10:59', '2026-02-19 17:10:59');

-- --------------------------------------------------------

--
-- Table structure for table `document_files`
--

CREATE TABLE `document_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `document_id` int(10) UNSIGNED NOT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `file_size` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_files`
--

INSERT INTO `document_files` (`id`, `document_id`, `uploaded_by`, `original_name`, `stored_name`, `file_path`, `mime_type`, `file_size`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'download.webp', '1771399060_a6643a327be230cf5136.webp', 'uploads/documents/1771399060_a6643a327be230cf5136.webp', 'image/webp', 2574, '2026-02-18 15:17:40', '2026-02-18 15:17:40'),
(2, 2, 1, 'OIP.webp', '1771399060_96909891d69f56afb110.webp', 'uploads/documents/1771399060_96909891d69f56afb110.webp', 'image/webp', 7688, '2026-02-18 15:17:40', '2026-02-18 15:17:40'),
(3, 2, 1, 'Lenovo_0809C6U_ThinkCentre_M70e_Small_Desktop_793128.jpg', '1771399061_12a720802cf74d73a4a0.jpg', 'uploads/documents/1771399061_12a720802cf74d73a4a0.jpg', 'image/jpeg', 442347, '2026-02-18 15:17:41', '2026-02-18 15:17:41');

-- --------------------------------------------------------

--
-- Table structure for table `document_notes`
--

CREATE TABLE `document_notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `document_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `note` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_notes`
--

INSERT INTO `document_notes` (`id`, `document_id`, `user_id`, `note`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'test', '2026-02-18 15:17:50', '2026-02-18 15:17:50');

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

CREATE TABLE `document_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `document_category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_types`
--

INSERT INTO `document_types` (`id`, `document_category_id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Contract', 'contract', 1, '2026-02-19 16:24:28', '2026-02-19 17:09:33'),
(2, 2, 'Contract', 'finance contract', 1, '2026-02-19 17:11:22', '2026-02-19 17:11:22'),
(3, 1, 'format', 'qwe', 1, '2026-02-23 10:54:54', '2026-02-23 10:54:54');

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

--
-- Dumping data for table `environments`
--

INSERT INTO `environments` (`id`, `application_id`, `environment_type`, `server_id`, `url`, `version`, `status`, `created_at`) VALUES
(2, 3, 'Development', 2, 'https://example.com', NULL, 'ACTIVE', '2026-02-18 02:51:30'),
(3, 5, 'Development', 2, 'https://example.com', NULL, 'ACTIVE', '2026-02-18 05:38:08');

-- --------------------------------------------------------

--
-- Table structure for table `infrastructure_details`
--

CREATE TABLE `infrastructure_details` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `server_type` varchar(100) DEFAULT NULL COMMENT 'VM, Physical, Container',
  `operating_system` varchar(100) DEFAULT NULL,
  `os_version` varchar(50) DEFAULT NULL,
  `cpu_cores` int(11) DEFAULT NULL,
  `memory_gb` int(11) DEFAULT NULL,
  `storage_gb` int(11) DEFAULT NULL,
  `database_server` varchar(100) DEFAULT NULL,
  `database_type` varchar(50) DEFAULT NULL COMMENT 'MySQL, Oracle, PostgreSQL, MSSQL',
  `storage_location` varchar(255) DEFAULT NULL,
  `backup_location` varchar(255) DEFAULT NULL,
  `dr_server_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `infrastructure_details`
--

INSERT INTO `infrastructure_details` (`id`, `application_id`, `server_type`, `operating_system`, `os_version`, `cpu_cores`, `memory_gb`, `storage_gb`, `database_server`, `database_type`, `storage_location`, `backup_location`, `dr_server_id`, `created_at`, `updated_at`) VALUES
(1, 3, 'VM', 'windowss', '20.00', 100, 16, 10000, 'localhost', 'MySQL', 'nas', 'git', NULL, '2026-02-12 08:27:21', '2026-02-12 08:43:21'),
(2, 4, 'VM', 'linux', '10.00', 64, 32, 500, 'localhost', NULL, 'nas', 'git', NULL, '2026-02-18 05:13:54', '2026-02-18 05:13:54'),
(3, 5, 'VM', 'windows', '1.00', 64, 32, 500, 'localhost', 'MySQL', 'nas', 'git', NULL, '2026-02-18 05:30:37', '2026-02-18 05:30:37');

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
-- Table structure for table `licensing_info`
--

CREATE TABLE `licensing_info` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `license_type` varchar(100) DEFAULT NULL,
  `license_key` varchar(255) DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `vendor_name` varchar(150) DEFAULT NULL,
  `vendor_contract_ref` varchar(255) DEFAULT NULL,
  `annual_cost` decimal(12,2) DEFAULT NULL,
  `cost_center` varchar(50) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `renewal_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `licensing_info`
--

INSERT INTO `licensing_info` (`id`, `application_id`, `license_type`, `license_key`, `license_expiry`, `vendor_name`, `vendor_contract_ref`, `annual_cost`, `cost_center`, `purchase_date`, `renewal_date`, `created_at`, `updated_at`) VALUES
(1, 3, 'example', NULL, '2026-03-14', 'example', 'example', 1000.00, 'example', NULL, NULL, '2026-02-12 08:27:21', '2026-02-12 08:35:33'),
(2, 4, 'hosting', NULL, '2030-01-01', 'hostinger', '00111', 3000.00, 'bank', NULL, NULL, '2026-02-18 05:13:54', '2026-02-18 05:13:54'),
(3, 5, 'hosting', NULL, '2030-01-01', 'hostinger', '00111', 3000.00, 'bank', '2026-02-18', '2027-02-18', '2026-02-18 05:30:37', '2026-02-18 05:30:37');

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
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `source_type` varchar(100) DEFAULT NULL,
  `source_id` int(10) UNSIGNED DEFAULT NULL,
  `related_url` varchar(500) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `title`, `message`, `source_type`, `source_id`, `related_url`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 'schedule_alert', 'Document Schedule Alert', 'Scheduled alert for \"test 002\" on Feb 19, 2026 at 09:00 AM. test', 'document_alert', 1, 'http://localhost/IM/documents/details/2', 1, '2026-02-19 13:52:56', '2026-02-19 13:52:59'),
(2, 'schedule_alert', 'Document Schedule Alert', 'Scheduled alert for \"test 002\" on Feb 19, 2026 at 01:55 PM. test agg', 'document_alert', 2, 'http://localhost/IM/documents/details/2', 1, '2026-02-19 13:55:04', '2026-02-19 13:55:04');

-- --------------------------------------------------------

--
-- Table structure for table `operational_metrics`
--

CREATE TABLE `operational_metrics` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `availability_sla` varchar(50) DEFAULT NULL COMMENT '99.9%, 99.99%, etc.',
  `criticality_level` varchar(50) DEFAULT NULL COMMENT 'High, Medium, Low',
  `business_impact` text DEFAULT NULL,
  `peak_users` int(11) DEFAULT NULL,
  `peak_transactions` int(11) DEFAULT NULL,
  `monitoring_tool` varchar(100) DEFAULT NULL,
  `incident_history` text DEFAULT NULL,
  `last_incident` date DEFAULT NULL,
  `mttr_target` int(11) DEFAULT NULL COMMENT 'Mean Time To Recover (minutes)',
  `rto_target` int(11) DEFAULT NULL COMMENT 'Recovery Time Objective (minutes)',
  `rpo_target` int(11) DEFAULT NULL COMMENT 'Recovery Point Objective (minutes)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `operational_metrics`
--

INSERT INTO `operational_metrics` (`id`, `application_id`, `availability_sla`, `criticality_level`, `business_impact`, `peak_users`, `peak_transactions`, `monitoring_tool`, `incident_history`, `last_incident`, `mttr_target`, `rto_target`, `rpo_target`, `created_at`, `updated_at`) VALUES
(1, 3, '99%', 'Low', 'example', 1, 1, 'example', NULL, NULL, NULL, NULL, NULL, '2026-02-12 08:27:22', '2026-02-12 08:35:33'),
(2, 4, '99.9%', 'Medium', 'tracking', 20, 10, 'dev tool', NULL, NULL, NULL, NULL, NULL, '2026-02-18 05:13:54', '2026-02-18 05:13:54'),
(3, 5, '99.9%', 'Medium', 'test', 20, 10, 'dev tool', NULL, '2026-02-04', 1, 1, 1, '2026-02-18 05:30:37', '2026-02-18 05:30:37');

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

--
-- Dumping data for table `servers`
--

INSERT INTO `servers` (`id`, `server_name`, `server_type`, `ip_address`, `location`, `status`, `created_at`) VALUES
(2, 'APP-SRV-01', 'Application', '192.168.1.10', NULL, 'ACTIVE', '2026-02-12 06:19:45');

-- --------------------------------------------------------

--
-- Table structure for table `technologies`
--

CREATE TABLE `technologies` (
  `id` int(11) NOT NULL,
  `technology_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `technologies`
--

INSERT INTO `technologies` (`id`, `technology_name`) VALUES
(1, 'java');

-- --------------------------------------------------------

--
-- Table structure for table `technology_details`
--

CREATE TABLE `technology_details` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `programming_language` varchar(100) DEFAULT NULL,
  `framework` varchar(100) DEFAULT NULL,
  `frontend_technology` varchar(100) DEFAULT NULL,
  `database_type` varchar(100) DEFAULT NULL,
  `middleware` varchar(255) DEFAULT NULL,
  `container_technology` varchar(100) DEFAULT NULL COMMENT 'Docker, Kubernetes, etc.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `technology_details`
--

INSERT INTO `technology_details` (`id`, `application_id`, `programming_language`, `framework`, `frontend_technology`, `database_type`, `middleware`, `container_technology`, `created_at`, `updated_at`) VALUES
(1, 3, 'php', 'codeigniter', 'bootstrap', 'example', 'example', 'example', '2026-02-12 08:27:21', '2026-02-12 08:43:55'),
(2, 4, 'php', 'codeigniter', 'bootstrap', 'MySQL', 'CI4', 'VSS', '2026-02-18 05:13:53', '2026-02-18 05:13:53'),
(3, 5, 'php', 'codeigniter', 'bootstrap', 'MySQL', 'CI4', 'VSS', '2026-02-18 05:30:37', '2026-02-18 05:30:37');

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
  ADD KEY `idx_app_status` (`status_id`),
  ADD KEY `idx_app_category` (`app_category`),
  ADD KEY `idx_app_type` (`app_type`),
  ADD KEY `idx_data_classification` (`data_classification`),
  ADD KEY `idx_lifecycle_stage` (`lifecycle_stage`);

--
-- Indexes for table `application_contacts`
--
ALTER TABLE `application_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `application_contacts_extended`
--
ALTER TABLE `application_contacts_extended`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`,`role`);

--
-- Indexes for table `application_dependencies`
--
ALTER TABLE `application_dependencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dependent_app_id` (`dependent_app_id`),
  ADD KEY `application_id` (`application_id`,`dependency_type`);

--
-- Indexes for table `application_environments`
--
ALTER TABLE `application_environments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `server_id` (`server_id`),
  ADD KEY `application_id` (`application_id`,`environment_type`);

--
-- Indexes for table `application_logs`
--
ALTER TABLE `application_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `application_related_data`
--
ALTER TABLE `application_related_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_related_app` (`application_id`),
  ADD KEY `idx_related_relation` (`relation`);

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
-- Indexes for table `application_vendors`
--
ALTER TABLE `application_vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

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
-- Indexes for table `batch_jobs`
--
ALTER TABLE `batch_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `compliance_security`
--
ALTER TABLE `compliance_security`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `dcfs`
--
ALTER TABLE `dcfs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_dcfs_name` (`name`),
  ADD UNIQUE KEY `share_token` (`share_token`),
  ADD KEY `idx_dcfs_is_active` (`is_active`),
  ADD KEY `idx_dcfs_due_date` (`due_date`),
  ADD KEY `idx_dcfs_department_id` (`department_id`),
  ADD KEY `idx_dcfs_share_token` (`share_token`);

--
-- Indexes for table `dcf_parts`
--
ALTER TABLE `dcf_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dcf_parts_dcf_id` (`dcf_id`),
  ADD KEY `idx_dcf_parts_sort_order` (`sort_order`);

--
-- Indexes for table `dcf_questions`
--
ALTER TABLE `dcf_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dcf_questions_dcf_id` (`dcf_id`),
  ADD KEY `idx_dcf_questions_sort_order` (`sort_order`),
  ADD KEY `idx_dcf_questions_part_id` (`part_id`);

--
-- Indexes for table `dcf_question_options`
--
ALTER TABLE `dcf_question_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dcf_question_options_question_id` (`question_id`),
  ADD KEY `idx_dcf_question_options_sort_order` (`sort_order`);

--
-- Indexes for table `dcf_responses`
--
ALTER TABLE `dcf_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dcf_responses_dcf_id` (`dcf_id`),
  ADD KEY `idx_dcf_responses_submitted_at` (`submitted_at`),
  ADD KEY `idx_dcf_responses_user_consent` (`user_consent`);

--
-- Indexes for table `dcf_response_answers`
--
ALTER TABLE `dcf_response_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dcf_response_answers_response_id` (`response_id`),
  ADD KEY `idx_dcf_response_answers_question_id` (`question_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_name` (`department_name`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_documents_created_by` (`created_by`),
  ADD KEY `fk_documents_category` (`document_category_id`),
  ADD KEY `fk_documents_type` (`document_type_id`);

--
-- Indexes for table `document_alerts`
--
ALTER TABLE `document_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_document_alerts_document` (`document_id`);

--
-- Indexes for table `document_categories`
--
ALTER TABLE `document_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_document_categories_name` (`name`);

--
-- Indexes for table `document_files`
--
ALTER TABLE `document_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_document_files_document` (`document_id`),
  ADD KEY `fk_document_files_user` (`uploaded_by`);

--
-- Indexes for table `document_notes`
--
ALTER TABLE `document_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_document_notes_document` (`document_id`),
  ADD KEY `fk_document_notes_user` (`user_id`);

--
-- Indexes for table `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_document_types_category_name` (`document_category_id`,`name`);

--
-- Indexes for table `environments`
--
ALTER TABLE `environments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `server_id` (`server_id`);

--
-- Indexes for table `infrastructure_details`
--
ALTER TABLE `infrastructure_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `dr_server_id` (`dr_server_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_items_asset` (`asset_id`);

--
-- Indexes for table `licensing_info`
--
ALTER TABLE `licensing_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_source_notification` (`source_type`,`source_id`),
  ADD KEY `idx_notifications_is_read` (`is_read`),
  ADD KEY `idx_notifications_created_at` (`created_at`);

--
-- Indexes for table `operational_metrics`
--
ALTER TABLE `operational_metrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

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
-- Indexes for table `technology_details`
--
ALTER TABLE `technology_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `application_contacts`
--
ALTER TABLE `application_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `application_contacts_extended`
--
ALTER TABLE `application_contacts_extended`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `application_dependencies`
--
ALTER TABLE `application_dependencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `application_environments`
--
ALTER TABLE `application_environments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `application_logs`
--
ALTER TABLE `application_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `application_related_data`
--
ALTER TABLE `application_related_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `application_status`
--
ALTER TABLE `application_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `application_technologies`
--
ALTER TABLE `application_technologies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `application_vendors`
--
ALTER TABLE `application_vendors`
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
-- AUTO_INCREMENT for table `batch_jobs`
--
ALTER TABLE `batch_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `compliance_security`
--
ALTER TABLE `compliance_security`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dcfs`
--
ALTER TABLE `dcfs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `dcf_parts`
--
ALTER TABLE `dcf_parts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `dcf_questions`
--
ALTER TABLE `dcf_questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `dcf_question_options`
--
ALTER TABLE `dcf_question_options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `dcf_responses`
--
ALTER TABLE `dcf_responses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `dcf_response_answers`
--
ALTER TABLE `dcf_response_answers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `document_alerts`
--
ALTER TABLE `document_alerts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `document_categories`
--
ALTER TABLE `document_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `document_files`
--
ALTER TABLE `document_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `document_notes`
--
ALTER TABLE `document_notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `document_types`
--
ALTER TABLE `document_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `environments`
--
ALTER TABLE `environments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `infrastructure_details`
--
ALTER TABLE `infrastructure_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `licensing_info`
--
ALTER TABLE `licensing_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `operational_metrics`
--
ALTER TABLE `operational_metrics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `technologies`
--
ALTER TABLE `technologies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `technology_details`
--
ALTER TABLE `technology_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- Constraints for table `application_contacts_extended`
--
ALTER TABLE `application_contacts_extended`
  ADD CONSTRAINT `application_contacts_extended_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `application_dependencies`
--
ALTER TABLE `application_dependencies`
  ADD CONSTRAINT `application_dependencies_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `application_dependencies_ibfk_2` FOREIGN KEY (`dependent_app_id`) REFERENCES `applications` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `application_environments`
--
ALTER TABLE `application_environments`
  ADD CONSTRAINT `application_environments_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `application_environments_ibfk_2` FOREIGN KEY (`server_id`) REFERENCES `servers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `application_logs`
--
ALTER TABLE `application_logs`
  ADD CONSTRAINT `application_logs_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `application_related_data`
--
ALTER TABLE `application_related_data`
  ADD CONSTRAINT `application_related_data_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `application_technologies`
--
ALTER TABLE `application_technologies`
  ADD CONSTRAINT `application_technologies_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `application_technologies_ibfk_2` FOREIGN KEY (`technology_id`) REFERENCES `technologies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `application_vendors`
--
ALTER TABLE `application_vendors`
  ADD CONSTRAINT `application_vendors_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `batch_jobs`
--
ALTER TABLE `batch_jobs`
  ADD CONSTRAINT `batch_jobs_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `compliance_security`
--
ALTER TABLE `compliance_security`
  ADD CONSTRAINT `compliance_security_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dcfs`
--
ALTER TABLE `dcfs`
  ADD CONSTRAINT `fk_dcfs_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `dcf_parts`
--
ALTER TABLE `dcf_parts`
  ADD CONSTRAINT `fk_dcf_parts_dcf` FOREIGN KEY (`dcf_id`) REFERENCES `dcfs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dcf_questions`
--
ALTER TABLE `dcf_questions`
  ADD CONSTRAINT `fk_dcf_questions_dcf` FOREIGN KEY (`dcf_id`) REFERENCES `dcfs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dcf_questions_part` FOREIGN KEY (`part_id`) REFERENCES `dcf_parts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dcf_question_options`
--
ALTER TABLE `dcf_question_options`
  ADD CONSTRAINT `fk_dcf_question_options_question` FOREIGN KEY (`question_id`) REFERENCES `dcf_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dcf_responses`
--
ALTER TABLE `dcf_responses`
  ADD CONSTRAINT `fk_dcf_responses_dcf` FOREIGN KEY (`dcf_id`) REFERENCES `dcfs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dcf_response_answers`
--
ALTER TABLE `dcf_response_answers`
  ADD CONSTRAINT `fk_dcf_response_answers_question` FOREIGN KEY (`question_id`) REFERENCES `dcf_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dcf_response_answers_response` FOREIGN KEY (`response_id`) REFERENCES `dcf_responses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `fk_documents_category` FOREIGN KEY (`document_category_id`) REFERENCES `document_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_documents_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_documents_type` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `document_alerts`
--
ALTER TABLE `document_alerts`
  ADD CONSTRAINT `fk_document_alerts_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `document_files`
--
ALTER TABLE `document_files`
  ADD CONSTRAINT `fk_document_files_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_document_files_user` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `document_notes`
--
ALTER TABLE `document_notes`
  ADD CONSTRAINT `fk_document_notes_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_document_notes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `document_types`
--
ALTER TABLE `document_types`
  ADD CONSTRAINT `fk_document_types_category` FOREIGN KEY (`document_category_id`) REFERENCES `document_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `environments`
--
ALTER TABLE `environments`
  ADD CONSTRAINT `environments_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `environments_ibfk_2` FOREIGN KEY (`server_id`) REFERENCES `servers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `infrastructure_details`
--
ALTER TABLE `infrastructure_details`
  ADD CONSTRAINT `infrastructure_details_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `infrastructure_details_ibfk_2` FOREIGN KEY (`dr_server_id`) REFERENCES `servers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_items_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `licensing_info`
--
ALTER TABLE `licensing_info`
  ADD CONSTRAINT `licensing_info_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `operational_metrics`
--
ALTER TABLE `operational_metrics`
  ADD CONSTRAINT `operational_metrics_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peripherals`
--
ALTER TABLE `peripherals`
  ADD CONSTRAINT `fk_peripherals_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `technology_details`
--
ALTER TABLE `technology_details`
  ADD CONSTRAINT `technology_details_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

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
