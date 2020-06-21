-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 23, 2020 at 10:04 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aup_uci_helper`
--
CREATE DATABASE IF NOT EXISTS `aup_uci_helper` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `aup_uci_helper`;

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `group_code` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`, `group_code`) VALUES
('/*', 3, NULL, NULL, NULL, 1584993493, 1584993493, NULL),
('//*', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('//controller', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('//crud', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('//extension', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('//form', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('//index', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('//model', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('//module', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/asset/*', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/asset/compress', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/asset/template', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/cache/*', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/cache/flush', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/cache/flush-all', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/cache/flush-schema', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/cache/index', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/fixture/*', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/fixture/load', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/fixture/unload', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/gii/*', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/gii/default/*', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/gii/default/action', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/gii/default/diff', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/gii/default/index', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/gii/default/preview', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/gii/default/view', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/help/*', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/help/index', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/help/list', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/help/list-action-options', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/help/usage', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/message/*', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/message/config', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/message/config-template', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/message/extract', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/migrate/*', 3, NULL, NULL, NULL, 1584993493, 1584993493, NULL),
('/migrate/create', 3, NULL, NULL, NULL, 1584993493, 1584993493, NULL),
('/migrate/down', 3, NULL, NULL, NULL, 1584993493, 1584993493, NULL),
('/migrate/fresh', 3, NULL, NULL, NULL, 1584993493, 1584993493, NULL),
('/migrate/history', 3, NULL, NULL, NULL, 1584993493, 1584993493, NULL),
('/migrate/mark', 3, NULL, NULL, NULL, 1584993493, 1584993493, NULL),
('/migrate/new', 3, NULL, NULL, NULL, 1584993493, 1584993493, NULL),
('/migrate/redo', 3, NULL, NULL, NULL, 1584993493, 1584993493, NULL),
('/migrate/to', 3, NULL, NULL, NULL, 1584993493, 1584993493, NULL),
('/migrate/up', 3, NULL, NULL, NULL, 1584993493, 1584993493, NULL),
('/serve/*', 3, NULL, NULL, NULL, 1584993493, 1584993493, NULL),
('/serve/index', 3, NULL, NULL, NULL, 1584993493, 1584993493, NULL),
('/user-management/*', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/user-management/auth/change-own-password', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/user-management/user-permission/set', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/user-management/user-permission/set-roles', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/user-management/user/bulk-activate', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/user-management/user/bulk-deactivate', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/user-management/user/bulk-delete', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/user-management/user/change-password', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/user-management/user/create', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/user-management/user/delete', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/user-management/user/grid-page-size', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/user-management/user/index', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/user-management/user/update', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('/user-management/user/view', 3, NULL, NULL, NULL, 1584993494, 1584993494, NULL),
('Admin', 1, 'Admin', NULL, NULL, 1584993494, 1584993494, NULL),
('assignRolesToUsers', 2, 'Assign roles to users', NULL, NULL, 1584993494, 1584993494, 'userManagement'),
('bindUserToIp', 2, 'Bind user to IP', NULL, NULL, 1584993494, 1584993494, 'userManagement'),
('changeOwnPassword', 2, 'Change own password', NULL, NULL, 1584993494, 1584993494, 'userCommonPermissions'),
('changeUserPassword', 2, 'Change user password', NULL, NULL, 1584993494, 1584993494, 'userManagement'),
('commonPermission', 2, 'Common permission', NULL, NULL, 1584993493, 1584993493, NULL),
('createUsers', 2, 'Create users', NULL, NULL, 1584993494, 1584993494, 'userManagement'),
('deleteUsers', 2, 'Delete users', NULL, NULL, 1584993494, 1584993494, 'userManagement'),
('editUserEmail', 2, 'Edit user email', NULL, NULL, 1584993494, 1584993494, 'userManagement'),
('editUsers', 2, 'Edit users', NULL, NULL, 1584993494, 1584993494, 'userManagement'),
('viewRegistrationIp', 2, 'View registration IP', NULL, NULL, 1584993494, 1584993494, 'userManagement'),
('viewUserEmail', 2, 'View user email', NULL, NULL, 1584993494, 1584993494, 'userManagement'),
('viewUserRoles', 2, 'View user roles', NULL, NULL, 1584993494, 1584993494, 'userManagement'),
('viewUsers', 2, 'View users', NULL, NULL, 1584993494, 1584993494, 'userManagement'),
('viewVisitLog', 2, 'View visit log', NULL, NULL, 1584993494, 1584993494, 'userManagement');

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('Admin', 'assignRolesToUsers'),
('Admin', 'changeOwnPassword'),
('Admin', 'changeUserPassword'),
('Admin', 'createUsers'),
('Admin', 'deleteUsers'),
('Admin', 'editUsers'),
('Admin', 'viewUsers'),
('assignRolesToUsers', '/user-management/user-permission/set'),
('assignRolesToUsers', '/user-management/user-permission/set-roles'),
('assignRolesToUsers', 'viewUserRoles'),
('assignRolesToUsers', 'viewUsers'),
('changeOwnPassword', '/user-management/auth/change-own-password'),
('changeUserPassword', '/user-management/user/change-password'),
('changeUserPassword', 'viewUsers'),
('createUsers', '/user-management/user/create'),
('createUsers', 'viewUsers'),
('deleteUsers', '/user-management/user/bulk-delete'),
('deleteUsers', '/user-management/user/delete'),
('deleteUsers', 'viewUsers'),
('editUserEmail', 'viewUserEmail'),
('editUsers', '/user-management/user/bulk-activate'),
('editUsers', '/user-management/user/bulk-deactivate'),
('editUsers', '/user-management/user/update'),
('editUsers', 'viewUsers'),
('viewUsers', '/user-management/user/grid-page-size'),
('viewUsers', '/user-management/user/index'),
('viewUsers', '/user-management/user/view');

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_group`
--

CREATE TABLE `auth_item_group` (
  `code` varchar(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_item_group`
--

INSERT INTO `auth_item_group` (`code`, `name`, `created_at`, `updated_at`) VALUES
('userCommonPermissions', 'User common permission', 1584993494, 1584993494),
('userManagement', 'User management', 1584993494, 1584993494);

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1584993490),
('m130524_201442_init', 1584993510),
('m140608_173539_create_user_table', 1584993492),
('m140611_133903_init_rbac', 1584993492),
('m140808_073114_create_auth_item_group_table', 1584993492),
('m140809_072112_insert_superadmin_to_user', 1584993493),
('m140809_073114_insert_common_permisison_to_auth_item', 1584993493),
('m141023_141535_create_user_visit_log', 1584993493),
('m141116_115804_add_bind_to_ip_and_registration_ip_to_user', 1584993493),
('m141121_194858_split_browser_and_os_column', 1584993493),
('m141201_220516_add_email_and_email_confirmed_to_user', 1584993493),
('m141207_001649_create_basic_user_permissions', 1584993494),
('m190814_230850_create_setting', 1584993510),
('m190814_230903_init_setting_values', 1584993510),
('m190815_140413_create_user_profile', 1584993510),
('m190815_140430_create_system_config', 1584993510);

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `main_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `header_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mini_header_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `name`, `seo_keywords`, `description`, `main_logo`, `header_logo`, `mini_header_logo`, `phone`, `address`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'AUP vUCI', 'AUP-UCI, Gestión de Proyectos, Artefactos Ingenieriles', '<p>Sistema para el apoyo a la Gesti&oacute;n de Proyectos utilizando la Metodolog&iacute;a AUP vUCI. Puedes consultar el escenario a utilizar en la gesti&oacute;n de tu software aplicando nuestro algoritmo para la determinaci&oacute;n de escenarios a partir de las caracter&iacute;sticas del proyecto en cuesti&oacute;n.</p>\r\n', 'main_logo_es.png', 'header_logo_es.png', 'mini_header_logo_es.png', '+53 8888888', 'Carretera a San Antonio de los Baños km 1(1/2), Torrens, La Habana, Cuba.', 'ecperez@estudiantes.uci.cu', 1, '2020-03-23 23:58:30', '2020-03-24 01:30:03');

-- --------------------------------------------------------

--
-- Table structure for table `system_config`
--

CREATE TABLE `system_config` (
  `id` int(11) NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `confirmation_token` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `superadmin` smallint(6) DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `registration_ip` varchar(15) DEFAULT NULL,
  `bind_to_ip` varchar(255) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `email_confirmed` smallint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `confirmation_token`, `status`, `superadmin`, `created_at`, `updated_at`, `registration_ip`, `bind_to_ip`, `email`, `email_confirmed`) VALUES
(1, 'superadmin', 'fkSNhEiOj2a6QYeI8s-pRszjFf9zsj4Y', '$2y$13$cY.Lo1kTq3mWBLWkbvVsBuHFUpiBuW/J0.DiTVZe9n4dDW3Ym/saa', NULL, 1, 1, 1584993493, 1584993493, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_visit_log`
--

CREATE TABLE `user_visit_log` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `language` char(2) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `visit_time` int(11) NOT NULL,
  `browser` varchar(30) DEFAULT NULL,
  `os` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_visit_log`
--

INSERT INTO `user_visit_log` (`id`, `token`, `ip`, `language`, `user_agent`, `user_id`, `visit_time`, `browser`, `os`) VALUES
(1, '5e791b053d38e', '127.0.0.1', 'en', 'Mozilla/5.0 (X11; Linux x86_64; rv:68.0) Gecko/20100101 Firefox/68.0', 1, 1584995077, 'Firefox', 'Linux');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`),
  ADD KEY `fk_auth_item_group_code` (`group_code`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `auth_item_group`
--
ALTER TABLE `auth_item_group`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_config`
--
ALTER TABLE `system_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_profile_user` (`user_id`);

--
-- Indexes for table `user_visit_log`
--
ALTER TABLE `user_visit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_config`
--
ALTER TABLE `system_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_visit_log`
--
ALTER TABLE `user_visit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_assignment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_auth_item_group_code` FOREIGN KEY (`group_code`) REFERENCES `auth_item_group` (`code`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `fk_user_profile_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_visit_log`
--
ALTER TABLE `user_visit_log`
  ADD CONSTRAINT `user_visit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
