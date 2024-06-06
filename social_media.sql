-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2024 at 04:47 AM
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
-- Database: `social_media`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `user_id`, `content`, `created_at`, `updated_at`, `image_id`) VALUES
(1, 0, 1, 'Test1', '2024-05-31 19:03:39', '2024-05-31 19:03:39', 1),
(2, 0, 1, 'Test1', '2024-05-31 19:04:14', '2024-05-31 19:04:14', 1),
(3, 0, 1, 'Test1', '2024-05-31 19:06:10', '2024-05-31 19:06:10', 1),
(4, 0, 1, '1234', '2024-05-31 19:23:22', '2024-05-31 19:23:22', 1),
(5, 0, 2, 'gorgeous!!!!!!', '2024-05-31 19:39:11', '2024-05-31 19:39:11', 1),
(6, 0, 2, 'gorgeous!!!!!!', '2024-05-31 19:42:08', '2024-05-31 19:42:08', 1),
(7, 0, 2, 'gorgeous!!!!!!', '2024-05-31 19:42:14', '2024-05-31 19:42:14', 1),
(8, 0, 2, 'gorgeous!!!!!!', '2024-05-31 20:07:15', '2024-05-31 20:07:15', 1),
(9, 0, 2, 'gorgeous!!!!!!', '2024-05-31 20:10:36', '2024-05-31 20:10:36', 1),
(10, 1, 2, 'gorgeous!!!!!!', '2024-05-31 20:17:55', '2024-05-31 20:17:55', NULL),
(11, 1, 2, 'hi', '2024-05-31 20:18:10', '2024-05-31 20:18:10', NULL),
(13, 3, 1, 'wow\r\n', '2024-05-31 20:59:58', '2024-05-31 20:59:58', NULL),
(14, 3, 1, 'wow\r\n', '2024-05-31 21:00:26', '2024-05-31 21:00:26', NULL),
(15, 3, 1, 'wow\r\n', '2024-05-31 21:00:57', '2024-05-31 21:00:57', NULL),
(16, 3, 1, 'yess', '2024-05-31 21:01:05', '2024-05-31 21:01:05', NULL),
(20, 1, 1, 'hi', '2024-06-03 00:41:07', '2024-06-03 00:41:07', NULL),
(21, 1, 1, 'hi', '2024-06-03 00:47:32', '2024-06-03 00:47:32', NULL),
(24, 25, 3, 'hi', '2024-06-05 14:18:45', '2024-06-05 14:18:45', NULL),
(25, 1, 3, 'gorg!', '2024-06-05 22:23:32', '2024-06-05 22:23:32', NULL),
(26, 9, 1, 'wow!', '2024-06-06 02:07:14', '2024-06-06 02:07:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `user_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `follow_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `followed_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(10) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`follow_id`, `follower_id`, `followed_id`, `created_at`, `status`) VALUES
(2, 1, 3, '2024-06-05 11:30:08', 'active'),
(3, 1, 2, '2024-06-05 12:04:55', 'active'),
(4, 3, 1, '2024-06-05 12:31:26', 'active'),
(5, 3, 2, '2024-06-05 14:51:51', 'active'),
(6, 3, 4, '2024-06-05 14:51:52', 'active'),
(7, 1, 4, '2024-06-06 00:19:13', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `uodated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `name`, `description`, `created_at`, `uodated_at`, `updated_at`) VALUES
(7, '1', 'test', '2024-06-05 16:28:28', '2024-06-05 16:28:28', NULL),
(8, '1', 'test', '2024-06-05 16:38:59', '2024-06-05 16:38:59', NULL),
(9, 'jaz', 'chat for friends', '2024-06-05 18:05:36', '2024-06-05 18:05:36', NULL),
(10, 'jaz', 'chat for friends', '2024-06-05 18:08:34', '2024-06-05 18:08:34', NULL),
(11, 'wHATCHA', 'Randm', '2024-06-05 22:09:37', '2024-06-05 22:09:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `group_member_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` datetime NOT NULL DEFAULT current_timestamp(),
  `selected` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`group_member_id`, `group_id`, `user_id`, `joined_at`, `selected`) VALUES
(1, 7, 1, '2024-06-05 17:18:27', 0),
(2, 7, 1, '2024-06-05 17:19:48', 0),
(3, 7, 1, '2024-06-05 17:21:41', 0),
(4, 7, 2, '2024-06-05 17:21:41', 0),
(5, 7, 1, '2024-06-05 17:22:53', 0),
(6, 7, 2, '2024-06-05 17:22:53', 0),
(7, 7, 1, '2024-06-05 17:24:03', 0),
(8, 7, 2, '2024-06-05 17:24:03', 0),
(9, 7, 1, '2024-06-05 17:25:00', 0),
(10, 7, 2, '2024-06-05 17:25:00', 0),
(11, 7, 1, '2024-06-05 17:25:42', 0),
(12, 7, 2, '2024-06-05 17:25:42', 0),
(13, 7, 1, '2024-06-05 17:26:09', 0),
(14, 7, 2, '2024-06-05 17:26:09', 0),
(15, 7, 1, '2024-06-05 17:28:12', 0),
(16, 7, 2, '2024-06-05 17:28:12', 0),
(17, 7, 1, '2024-06-05 18:42:34', 0),
(18, 7, 2, '2024-06-05 18:42:34', 0),
(19, 7, 1, '2024-06-05 18:44:34', 0),
(20, 7, 2, '2024-06-05 18:44:34', 0),
(21, 8, 1, '2024-06-05 18:54:49', 0),
(22, 8, 2, '2024-06-05 18:54:49', 0),
(23, 8, 1, '2024-06-05 18:55:02', 0),
(24, 8, 2, '2024-06-05 18:55:02', 0),
(25, 8, 1, '2024-06-05 18:55:24', 0),
(26, 8, 2, '2024-06-05 18:55:24', 0),
(27, 8, 1, '2024-06-05 18:55:32', 0),
(28, 8, 2, '2024-06-05 18:55:32', 0),
(29, 8, 1, '2024-06-05 18:56:26', 0),
(30, 8, 2, '2024-06-05 18:56:26', 0),
(31, 8, 1, '2024-06-05 18:57:41', 0),
(32, 8, 2, '2024-06-05 18:57:41', 0),
(33, 8, 1, '2024-06-05 19:03:10', 0),
(34, 8, 2, '2024-06-05 19:03:10', 0),
(35, 8, 1, '2024-06-05 19:04:48', 0),
(36, 8, 2, '2024-06-05 19:04:48', 0),
(37, 8, 1, '2024-06-05 19:05:43', 0),
(38, 8, 2, '2024-06-05 19:05:43', 0),
(39, 8, 1, '2024-06-05 19:05:58', 0),
(40, 8, 2, '2024-06-05 19:05:58', 0),
(41, 8, 1, '2024-06-05 19:06:15', 0),
(42, 8, 2, '2024-06-05 19:06:15', 0),
(43, 8, 1, '2024-06-05 19:06:23', 0),
(44, 8, 2, '2024-06-05 19:06:23', 0),
(45, 8, 1, '2024-06-05 19:07:39', 0),
(46, 8, 2, '2024-06-05 19:07:39', 0),
(47, 8, 1, '2024-06-05 19:07:47', 0),
(48, 8, 2, '2024-06-05 19:07:47', 0),
(49, 8, 1, '2024-06-05 19:08:02', 0),
(50, 8, 2, '2024-06-05 19:08:02', 0),
(51, 8, 1, '2024-06-05 19:08:07', 0),
(52, 8, 2, '2024-06-05 19:08:07', 0),
(53, 8, 1, '2024-06-05 19:10:05', 0),
(54, 8, 2, '2024-06-05 19:10:05', 0),
(55, 8, 1, '2024-06-05 19:10:08', 0),
(56, 8, 2, '2024-06-05 19:10:08', 0),
(57, 8, 1, '2024-06-05 19:10:24', 0),
(58, 8, 2, '2024-06-05 19:10:24', 0),
(59, 7, 1, '2024-06-05 19:29:38', 0),
(60, 7, 2, '2024-06-05 19:29:38', 0),
(61, 8, 1, '2024-06-05 19:32:30', 0),
(62, 8, 2, '2024-06-05 19:32:30', 0),
(63, 7, 1, '2024-06-05 19:32:51', 0),
(64, 7, 2, '2024-06-05 19:32:51', 0),
(65, 7, 1, '2024-06-05 19:35:09', 0),
(66, 7, 2, '2024-06-05 19:35:09', 0),
(67, 7, 1, '2024-06-05 19:35:13', 0),
(68, 7, 2, '2024-06-05 19:35:13', 0),
(69, 7, 1, '2024-06-05 19:35:48', 0),
(70, 7, 2, '2024-06-05 19:35:48', 0),
(71, 7, 1, '2024-06-05 19:36:06', 0),
(72, 7, 2, '2024-06-05 19:36:06', 0),
(73, 7, 1, '2024-06-05 19:36:28', 0),
(74, 7, 2, '2024-06-05 19:36:28', 0),
(75, 7, 1, '2024-06-05 19:37:00', 0),
(76, 7, 2, '2024-06-05 19:37:00', 0),
(77, 7, 2, '2024-06-05 19:38:13', 0),
(78, 7, 4, '2024-06-05 19:38:13', 0),
(79, 7, 2, '2024-06-05 19:38:34', 0),
(80, 7, 4, '2024-06-05 19:38:34', 0),
(81, 7, 2, '2024-06-05 19:48:01', 0),
(82, 7, 4, '2024-06-05 19:48:01', 0),
(83, 7, 2, '2024-06-05 19:59:09', 0),
(84, 7, 4, '2024-06-05 19:59:09', 0),
(85, 7, 2, '2024-06-05 19:59:32', 0),
(86, 7, 4, '2024-06-05 19:59:32', 0),
(87, 7, 2, '2024-06-05 20:04:13', 0),
(88, 7, 4, '2024-06-05 20:04:13', 0),
(89, 7, 2, '2024-06-05 20:05:23', 0),
(90, 7, 4, '2024-06-05 20:05:23', 0),
(91, 7, 2, '2024-06-05 20:06:57', 0),
(92, 7, 4, '2024-06-05 20:06:57', 0),
(93, 7, 2, '2024-06-05 20:14:22', 0),
(94, 7, 4, '2024-06-05 20:14:22', 0),
(95, 7, 2, '2024-06-05 20:15:24', 0),
(96, 7, 4, '2024-06-05 20:15:24', 0),
(97, 7, 2, '2024-06-05 20:16:08', 0),
(98, 7, 4, '2024-06-05 20:16:08', 0),
(99, 7, 2, '2024-06-05 20:25:07', 0),
(100, 7, 4, '2024-06-05 20:25:07', 0),
(101, 7, 2, '2024-06-05 20:26:30', 0),
(102, 7, 4, '2024-06-05 20:26:30', 0),
(103, 7, 2, '2024-06-05 20:26:34', 0),
(104, 7, 4, '2024-06-05 20:26:34', 0),
(105, 7, 2, '2024-06-05 20:26:43', 0),
(106, 7, 4, '2024-06-05 20:26:43', 0),
(107, 7, 2, '2024-06-05 20:27:17', 0),
(108, 7, 4, '2024-06-05 20:27:17', 0),
(109, 7, 2, '2024-06-05 20:28:00', 0),
(110, 7, 4, '2024-06-05 20:28:00', 0),
(111, 7, 2, '2024-06-05 20:35:55', 0),
(112, 7, 4, '2024-06-05 20:35:55', 0),
(113, 7, 2, '2024-06-05 20:36:31', 0),
(114, 7, 4, '2024-06-05 20:36:31', 0),
(115, 11, 3, '2024-06-05 22:31:43', 0),
(116, 11, 4, '2024-06-05 22:31:43', 0),
(117, 11, 2, '2024-06-05 22:32:01', 0),
(118, 11, 3, '2024-06-05 22:32:01', 0),
(119, 11, 3, '2024-06-05 22:32:20', 0),
(120, 11, 4, '2024-06-05 22:32:20', 0);

-- --------------------------------------------------------

--
-- Table structure for table `group_messages`
--

CREATE TABLE `group_messages` (
  `message_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `filename`, `user_id`) VALUES
(1, 'Image1.jpg', NULL),
(2, 'Image2.jpg', NULL),
(3, 'Image3.jpg', NULL),
(4, 'Image4.jpg', NULL),
(5, 'Image5.jpg', NULL),
(6, 'Image6.jpg', NULL),
(7, 'Image8.jpg', NULL),
(8, 'Image8.jpg', NULL),
(9, 'Image9.jpg', NULL),
(10, 'Image10.jpg', NULL),
(11, 'Image11.jpg', NULL),
(12, 'Image12.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `imagesdata`
--

CREATE TABLE `imagesdata` (
  `image` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `post_id`, `user_id`, `created_at`) VALUES
(1, 1, 1, '2024-05-31 19:08:53'),
(2, 1, 1, '2024-05-31 19:08:55'),
(3, 1, 1, '2024-05-31 19:10:05'),
(4, 1, 1, '2024-05-31 19:15:50'),
(5, 1, 1, '2024-05-31 19:17:30'),
(6, 1, 1, '2024-05-31 19:17:33'),
(7, 1, 1, '2024-05-31 19:17:34'),
(9, 1, 1, '2024-05-31 19:19:57'),
(10, 1, 1, '2024-05-31 19:19:59'),
(11, 1, 1, '2024-05-31 19:20:09'),
(12, 1, 1, '2024-05-31 19:20:26'),
(13, 1, 1, '2024-05-31 19:20:44'),
(14, 1, 1, '2024-05-31 19:21:02'),
(15, 1, 1, '2024-05-31 19:23:08'),
(16, 1, 4, '2024-06-05 00:49:11'),
(17, 1, 3, '2024-06-05 22:23:37');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `received_id` int(11) NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `receiver_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `received_id`, `content`, `created_at`, `updated_at`, `receiver_id`, `is_read`, `group_id`) VALUES
(1, 1, 0, 'hi!', '2024-06-03 19:49:20', '2024-06-03 19:49:20', 2, 1, NULL),
(3, 1, 0, 'I\'m good you?', '2024-06-03 19:56:04', '2024-06-03 20:01:53', 2, 1, NULL),
(4, 1, 0, 'heyy', '2024-06-03 20:18:02', '2024-06-03 20:18:02', 2, 1, NULL),
(5, 3, 0, 'hi', '2024-06-03 23:24:39', '2024-06-03 23:24:39', 1, 1, NULL),
(6, 3, 0, 'hi', '2024-06-03 23:24:54', '2024-06-03 23:24:54', 1, 1, NULL),
(7, 3, 0, 'hi', '2024-06-04 00:12:42', '2024-06-04 00:12:42', 1, 1, NULL),
(8, 3, 0, 'Hii!!', '2024-06-04 00:12:59', '2024-06-04 00:12:59', 1, 1, NULL),
(9, 3, 0, 'hi', '2024-06-04 00:15:36', '2024-06-04 00:15:36', 1, 1, NULL),
(10, 3, 0, 'no', '2024-06-04 00:18:49', '2024-06-04 00:18:49', 1, 1, NULL),
(12, 2, 0, 'hi', '2024-06-04 00:29:26', '2024-06-04 00:29:26', 3, 1, NULL),
(13, 1, 0, 'bye', '2024-06-04 00:56:41', '2024-06-04 00:56:41', 2, 1, NULL),
(14, 1, 0, 'no', '2024-06-04 01:04:50', '2024-06-04 01:04:50', 2, 1, NULL),
(15, 2, 0, 'hi', '2024-06-04 01:06:10', '2024-06-04 01:06:10', 3, 1, NULL),
(18, 2, 0, 'bye', '2024-06-04 19:36:26', '2024-06-04 19:36:26', 1, 1, NULL),
(19, 2, 0, 'bye', '2024-06-04 19:47:10', '2024-06-04 19:47:10', 1, 1, NULL),
(20, 1, 0, 'bye', '2024-06-04 23:05:54', '2024-06-04 23:05:54', 2, 1, NULL),
(21, 3, 0, 'heyy', '2024-06-05 17:29:48', '2024-06-05 17:29:48', NULL, 0, 7),
(22, 3, 0, 'hi', '2024-06-05 19:12:49', '2024-06-05 19:12:49', 1, 1, NULL),
(23, 3, 0, 'heyy\r\n', '2024-06-05 22:24:35', '2024-06-05 22:24:35', 4, 1, NULL),
(24, 1, 0, 'hi', '2024-06-05 23:22:21', '2024-06-05 23:22:21', NULL, 0, 7),
(25, 1, 0, 'hi', '2024-06-05 23:23:52', '2024-06-05 23:23:52', NULL, 0, 11),
(26, 1, 0, 'does it work?\r\n', '2024-06-05 23:30:29', '2024-06-05 23:30:29', NULL, 0, 11),
(27, 1, 0, 'yes it does it saves in messages!!\r\n', '2024-06-05 23:31:15', '2024-06-05 23:31:15', NULL, 0, 11),
(28, 1, 0, 'hi', '2024-06-06 00:01:14', '2024-06-06 00:01:14', 2, 1, NULL),
(29, 1, 0, 'hi', '2024-06-06 00:01:24', '2024-06-06 00:01:24', 4, 1, NULL),
(31, 1, 0, 'bye', '2024-06-06 00:20:09', '2024-06-06 00:20:09', 3, 0, NULL),
(32, 1, 0, 'hi', '2024-06-06 00:37:46', '2024-06-06 00:37:46', NULL, 0, NULL),
(33, 1, 0, 'hi', '2024-06-06 00:38:37', '2024-06-06 00:38:37', NULL, 0, NULL),
(34, 1, 0, 'hi', '2024-06-06 00:38:37', '2024-06-06 00:38:37', NULL, 0, NULL),
(35, 1, 0, 'hi', '2024-06-06 00:38:37', '2024-06-06 00:38:37', NULL, 0, NULL),
(36, 1, 0, 'hi', '2024-06-06 00:38:43', '2024-06-06 00:38:43', NULL, 0, NULL),
(37, 1, 0, 'hey', '2024-06-06 00:48:44', '2024-06-06 00:48:44', 2, 1, NULL),
(38, 1, 0, 'ok?', '2024-06-06 00:52:52', '2024-06-06 00:52:52', 4, 1, NULL),
(39, 1, 0, 'hi', '2024-06-06 00:53:25', '2024-06-06 00:53:25', 0, 0, NULL),
(40, 1, 0, 'hi!', '2024-06-06 00:54:44', '2024-06-06 00:54:44', 0, 0, NULL),
(41, 1, 0, 'ok', '2024-06-06 01:01:44', '2024-06-06 01:01:44', 3, 0, NULL),
(42, 1, 0, 'hi', '2024-06-06 01:02:36', '2024-06-06 01:02:36', 0, 0, NULL),
(43, 1, 0, 'hi', '2024-06-06 01:06:42', '2024-06-06 01:06:42', 0, 0, NULL),
(44, 1, 0, 'hi', '2024-06-06 01:07:06', '2024-06-06 01:07:06', 0, 0, NULL),
(45, 1, 0, 'hi', '2024-06-06 01:07:20', '2024-06-06 01:07:20', 4, 1, NULL),
(46, 1, 0, 'hi', '2024-06-06 01:10:23', '2024-06-06 01:10:23', NULL, 0, NULL),
(47, 1, 0, 'hi', '2024-06-06 01:11:25', '2024-06-06 01:11:25', NULL, 0, NULL),
(48, 1, 0, 'hi', '2024-06-06 01:11:25', '2024-06-06 01:11:25', NULL, 0, NULL),
(49, 1, 0, 'hi', '2024-06-06 01:11:26', '2024-06-06 01:11:26', NULL, 0, NULL),
(50, 1, 0, 'hi', '2024-06-06 01:29:46', '2024-06-06 01:29:46', 7, 0, NULL),
(51, 1, 0, 'nice', '2024-06-06 01:39:41', '2024-06-06 01:39:41', 2, 1, NULL),
(52, 1, 0, 'hi', '2024-06-06 01:41:30', '2024-06-06 01:41:30', 7, 0, NULL),
(53, 1, 0, 'how are you?', '2024-06-06 01:45:56', '2024-06-06 01:45:56', 7, 0, NULL),
(54, 1, 0, 'how are you?', '2024-06-06 01:45:57', '2024-06-06 01:45:57', 7, 0, NULL),
(150, 1, 0, 'good', '2024-06-06 01:51:26', '2024-06-06 01:51:26', 7, 0, NULL),
(151, 1, 0, 'good', '2024-06-06 01:51:26', '2024-06-06 01:51:26', 7, 0, NULL),
(152, 1, 0, 'good', '2024-06-06 01:51:26', '2024-06-06 01:51:26', 7, 0, NULL),
(153, 1, 0, 'you?', '2024-06-06 01:54:11', '2024-06-06 01:54:11', 7, 0, NULL),
(355, 1, 0, 'hu', '2024-06-06 02:00:18', '2024-06-06 02:00:18', 7, 0, NULL),
(356, 1, 0, 'bye', '2024-06-06 02:02:56', '2024-06-06 02:02:56', 7, 0, NULL),
(357, 1, 0, 'bye', '2024-06-06 02:02:56', '2024-06-06 02:02:56', 7, 0, NULL),
(358, 1, 0, 'bye', '2024-06-06 02:02:56', '2024-06-06 02:02:56', 7, 0, NULL),
(359, 1, 0, 'bye', '2024-06-06 02:02:56', '2024-06-06 02:02:56', 7, 0, NULL),
(360, 1, 0, 'ok', '2024-06-06 02:05:17', '2024-06-06 02:05:17', 7, 0, NULL),
(361, 1, 0, 'ok', '2024-06-06 02:05:17', '2024-06-06 02:05:17', 7, 0, NULL),
(362, 1, 0, 'no', '2024-06-06 02:05:25', '2024-06-06 02:05:25', 7, 0, NULL),
(363, 1, 0, 'no', '2024-06-06 02:05:25', '2024-06-06 02:05:25', 7, 0, NULL),
(364, 1, 0, 'no', '2024-06-06 02:05:25', '2024-06-06 02:05:25', 7, 0, NULL),
(365, 1, 0, 'no', '2024-06-06 02:05:25', '2024-06-06 02:05:25', 7, 0, NULL),
(366, 1, 0, 'ok', '2024-06-06 02:06:39', '2024-06-06 02:06:39', 7, 0, NULL),
(367, 1, 0, 'ok', '2024-06-06 02:06:39', '2024-06-06 02:06:39', 7, 0, NULL),
(368, 1, 0, 'helo!', '2024-06-06 04:22:32', '2024-06-06 04:22:32', 4, 1, NULL),
(369, 4, 0, 'Hello!', '2024-06-06 04:27:14', '2024-06-06 04:27:14', 1, 1, NULL),
(370, 1, 0, 'let\'s go', '2024-06-06 04:32:12', '2024-06-06 04:32:12', 4, 1, NULL),
(371, 2, 0, 'bye', '2024-06-06 04:34:14', '2024-06-06 04:34:14', 4, 1, NULL),
(372, 4, 0, 'bye', '2024-06-06 04:38:12', '2024-06-06 04:38:12', 2, 1, NULL),
(373, 2, 0, 'hi', '2024-06-06 04:40:37', '2024-06-06 04:40:37', 4, 1, NULL),
(374, 2, 0, 'hey', '2024-06-06 04:43:51', '2024-06-06 04:43:51', 4, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 'You have received a new message.', 0, '2024-06-04 19:36:26'),
(2, 1, 'You have received a new message.', 0, '2024-06-04 19:47:10'),
(3, 2, 'You have received a new message.', 0, '2024-06-04 23:05:54'),
(4, 1, 'You have received a new message.', 0, '2024-06-05 19:12:49'),
(5, 4, 'You have received a new message.', 0, '2024-06-05 22:24:35'),
(6, 2, 'You have received a new message.', 0, '2024-06-06 00:01:14'),
(7, 4, 'You have received a new message.', 0, '2024-06-06 00:01:24'),
(8, 2, 'You have received a new message.', 0, '2024-06-06 00:19:57'),
(9, 3, 'You have received a new message.', 0, '2024-06-06 00:20:09'),
(10, 2, 'You have received a new message.', 0, '2024-06-06 00:48:44'),
(11, 4, 'You have received a new message.', 0, '2024-06-06 00:52:52'),
(12, 0, 'You have received a new message.', 0, '2024-06-06 00:53:25'),
(13, 0, 'You have received a new message.', 0, '2024-06-06 00:54:44'),
(14, 3, 'You have received a new message.', 0, '2024-06-06 01:01:44'),
(15, 0, 'You have received a new message.', 0, '2024-06-06 01:02:36'),
(16, 0, 'You have received a new message.', 0, '2024-06-06 01:06:42'),
(17, 0, 'You have received a new message.', 0, '2024-06-06 01:07:06'),
(18, 4, 'You have received a new message.', 0, '2024-06-06 01:07:20'),
(19, 7, 'You have received a new message.', 0, '2024-06-06 01:29:46'),
(20, 2, 'You have received a new message.', 0, '2024-06-06 01:39:41'),
(21, 7, 'You have received a new message.', 0, '2024-06-06 01:41:30'),
(22, 7, 'You have received a new message.', 0, '2024-06-06 01:45:56'),
(23, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(24, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(25, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(26, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(27, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(28, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(29, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(30, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(31, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(32, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(33, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(34, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(35, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(36, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(37, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(38, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(39, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(40, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(41, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(42, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(43, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(44, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(45, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(46, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(47, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(48, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(49, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(50, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(51, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(52, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(53, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(54, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(55, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(56, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(57, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(58, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(59, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(60, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(61, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(62, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(63, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(64, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(65, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(66, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(67, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(68, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(69, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(70, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(71, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(72, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(73, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(74, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(75, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(76, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(77, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(78, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(79, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(80, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(81, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(82, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(83, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(84, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(85, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(86, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(87, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(88, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(89, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(90, 7, 'You have received a new message.', 0, '2024-06-06 01:45:57'),
(91, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(92, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(93, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(94, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(95, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(96, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(97, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(98, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(99, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(100, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(101, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(102, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(103, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(104, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(105, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(106, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(107, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(108, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(109, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(110, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(111, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(112, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(113, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(114, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(115, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(116, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(117, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(118, 7, 'You have received a new message.', 0, '2024-06-06 01:45:58'),
(119, 7, 'You have received a new message.', 0, '2024-06-06 01:51:26'),
(120, 7, 'You have received a new message.', 0, '2024-06-06 01:51:26'),
(121, 7, 'You have received a new message.', 0, '2024-06-06 01:51:26'),
(122, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(123, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(124, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(125, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(126, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(127, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(128, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(129, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(130, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(131, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(132, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(133, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(134, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(135, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(136, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(137, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(138, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(139, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(140, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(141, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(142, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(143, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(144, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(145, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(146, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(147, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(148, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(149, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(150, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(151, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(152, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(153, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(154, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(155, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(156, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(157, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(158, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(159, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(160, 7, 'You have received a new message.', 0, '2024-06-06 01:54:11'),
(161, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(162, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(163, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(164, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(165, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(166, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(167, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(168, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(169, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(170, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(171, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(172, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(173, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(174, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(175, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(176, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(177, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(178, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(179, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(180, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(181, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(182, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(183, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(184, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(185, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(186, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(187, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(188, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(189, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(190, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(191, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(192, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(193, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(194, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(195, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(196, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(197, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(198, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(199, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(200, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(201, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(202, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(203, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(204, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(205, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(206, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(207, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(208, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(209, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(210, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(211, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(212, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(213, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(214, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(215, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(216, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(217, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(218, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(219, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(220, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(221, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(222, 7, 'You have received a new message.', 0, '2024-06-06 01:54:12'),
(223, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(224, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(225, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(226, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(227, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(228, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(229, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(230, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(231, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(232, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(233, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(234, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(235, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(236, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(237, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(238, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(239, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(240, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(241, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(242, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(243, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(244, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(245, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(246, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(247, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(248, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(249, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(250, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(251, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(252, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(253, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(254, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(255, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(256, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(257, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(258, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(259, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(260, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(261, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(262, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(263, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(264, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(265, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(266, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(267, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(268, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(269, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(270, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(271, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(272, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(273, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(274, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(275, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(276, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(277, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(278, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(279, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(280, 7, 'You have received a new message.', 0, '2024-06-06 01:54:13'),
(281, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(282, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(283, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(284, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(285, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(286, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(287, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(288, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(289, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(290, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(291, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(292, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(293, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(294, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(295, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(296, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(297, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(298, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(299, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(300, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(301, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(302, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(303, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(304, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(305, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(306, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(307, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(308, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(309, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(310, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(311, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(312, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(313, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(314, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(315, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(316, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(317, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(318, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(319, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(320, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(321, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(322, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(323, 7, 'You have received a new message.', 0, '2024-06-06 01:54:14'),
(324, 7, 'You have received a new message.', 0, '2024-06-06 02:00:18'),
(325, 7, 'You have received a new message.', 0, '2024-06-06 02:02:56'),
(326, 7, 'You have received a new message.', 0, '2024-06-06 02:02:56'),
(327, 7, 'You have received a new message.', 0, '2024-06-06 02:02:56'),
(328, 7, 'You have received a new message.', 0, '2024-06-06 02:02:56'),
(329, 7, 'You have received a new message.', 0, '2024-06-06 02:05:17'),
(330, 7, 'You have received a new message.', 0, '2024-06-06 02:05:17'),
(331, 7, 'You have received a new message.', 0, '2024-06-06 02:05:25'),
(332, 7, 'You have received a new message.', 0, '2024-06-06 02:05:25'),
(333, 7, 'You have received a new message.', 0, '2024-06-06 02:05:25'),
(334, 7, 'You have received a new message.', 0, '2024-06-06 02:05:25'),
(335, 7, 'You have received a new message.', 0, '2024-06-06 02:06:39'),
(336, 7, 'You have received a new message.', 0, '2024-06-06 02:06:39'),
(337, 4, 'You have received a new message.', 0, '2024-06-06 04:22:32'),
(338, 1, 'You have received a new message.', 0, '2024-06-06 04:27:14'),
(339, 4, 'You have received a new message.', 0, '2024-06-06 04:32:12'),
(340, 4, 'You have received a new message.', 0, '2024-06-06 04:34:14'),
(341, 2, 'You have received a new message.', 0, '2024-06-06 04:38:12'),
(342, 4, 'You have received a new message.', 0, '2024-06-06 04:40:37'),
(343, 4, 'You have received a new message.', 0, '2024-06-06 04:43:51');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `image_filename` varchar(255) DEFAULT NULL,
  `profile_image_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `content`, `created_at`, `updated_at`, `image`, `tagline`, `image_filename`, `profile_image_id`) VALUES
(9, 1, 'geez', '2024-06-03 00:15:36', '2024-06-03 00:15:36', 'post_image_665cef08b7060.jpg', NULL, NULL, NULL),
(10, 1, 'no', '2024-06-03 00:15:52', '2024-06-03 00:15:52', 'post_image_665cef18d8b8b.jpg', NULL, NULL, NULL),
(11, 1, '', '2024-06-03 00:27:01', '2024-06-03 00:27:01', 'post_image_665cf1b5bf380.jpg', NULL, NULL, NULL),
(16, 1, 'nice', '2024-06-03 01:27:48', '2024-06-03 01:27:48', 'post_image_665cfff4a8db7.jpg', NULL, NULL, NULL),
(19, 1, 'hi', '2024-06-03 13:06:33', '2024-06-03 13:06:33', 'post_image_665da3b91a134.jpg', NULL, NULL, NULL),
(20, 2, 'gorg', '2024-06-04 01:06:45', '2024-06-04 01:06:45', 'post_image_665e4c8517196.jpg', NULL, NULL, NULL),
(21, 1, 'wow', '2024-06-05 11:55:56', '2024-06-05 11:55:56', 'post_image_6660362cb9897.jpg', NULL, NULL, NULL),
(22, 1, '', '2024-06-05 12:13:16', '2024-06-05 12:13:16', 'post_image_66603a3c810e9.jpg', NULL, NULL, NULL),
(23, 1, '', '2024-06-05 12:16:00', '2024-06-05 12:16:00', 'post_image_66603ae080f0a.jpg', NULL, NULL, NULL),
(25, 3, '', '2024-06-05 14:08:12', '2024-06-05 14:08:12', 'post_image_6660552c29032.jpg', NULL, NULL, NULL),
(26, 3, 'yaas', '2024-06-05 14:52:20', '2024-06-05 14:52:20', 'post_image_66605f84d73f0.jpg', NULL, NULL, NULL),
(31, 1, '', '2024-06-06 03:52:04', '2024-06-06 03:52:04', 'post_image_666116443ac3c.jpg', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `privacy` enum('public','private','','') NOT NULL DEFAULT 'public',
  `receive_notifications` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `username` tinyint(1) DEFAULT NULL,
  `new_username` varchar(255) DEFAULT NULL,
  `email` tinyint(1) DEFAULT NULL,
  `new_email` varchar(255) DEFAULT NULL,
  `password` tinyint(1) DEFAULT NULL,
  `new_password_hash` varchar(255) DEFAULT NULL,
  `color_scheme` varchar(255) DEFAULT NULL,
  `notifications_enabled` tinyint(1) DEFAULT 0,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `user_id`, `privacy`, `receive_notifications`, `created_at`, `username`, `new_username`, `email`, `new_email`, `password`, `new_password_hash`, `color_scheme`, `notifications_enabled`, `updated_at`) VALUES
(65, 4, 'public', 0, '2024-06-05 00:34:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(66, 4, 'public', 0, '2024-06-05 00:34:18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(67, 4, 'public', 0, '2024-06-05 00:34:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(68, 4, 'public', 0, '2024-06-05 00:34:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(69, 4, 'public', 0, '2024-06-05 00:35:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(70, 4, 'public', 0, '2024-06-05 00:35:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(71, 4, 'public', NULL, '2024-06-05 00:42:21', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, '2024-06-05 00:42:21'),
(72, 4, 'public', NULL, '2024-06-05 00:47:19', NULL, NULL, NULL, NULL, NULL, NULL, 'light', 1, '2024-06-05 00:47:19'),
(73, 4, 'public', NULL, '2024-06-05 00:47:31', NULL, NULL, NULL, NULL, NULL, NULL, 'light', 0, '2024-06-05 00:47:31'),
(74, 4, 'public', NULL, '2024-06-05 00:53:44', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, '2024-06-05 00:53:44'),
(75, 4, 'public', NULL, '2024-06-05 00:56:39', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, '2024-06-05 00:56:39'),
(76, 4, 'public', NULL, '2024-06-05 00:56:39', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, '2024-06-05 00:56:39'),
(77, 4, 'public', NULL, '2024-06-05 00:56:40', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, '2024-06-05 00:56:40'),
(78, 4, 'public', NULL, '2024-06-05 00:56:44', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, '2024-06-05 00:56:44'),
(79, 4, 'public', NULL, '2024-06-05 00:56:46', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, '2024-06-05 00:56:46'),
(80, 4, 'public', 0, '2024-06-05 00:57:57', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(81, 4, 'public', 0, '2024-06-05 00:58:59', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(82, 4, 'public', 0, '2024-06-05 00:59:01', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(83, 4, 'public', 0, '2024-06-05 00:59:01', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(84, 4, 'public', 0, '2024-06-05 00:59:02', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(85, 4, 'public', 0, '2024-06-05 00:59:02', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(86, 4, 'public', 0, '2024-06-05 01:18:50', NULL, NULL, NULL, NULL, NULL, NULL, 'light', 0, NULL),
(87, 4, 'public', 0, '2024-06-05 01:18:54', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(88, 4, 'public', 0, '2024-06-05 01:26:53', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(89, 4, 'public', 0, '2024-06-05 01:31:36', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(90, 4, 'public', 0, '2024-06-05 01:33:37', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(91, 4, 'public', 0, '2024-06-05 01:33:38', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(92, 4, 'public', 0, '2024-06-05 01:35:03', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(93, 4, 'public', 0, '2024-06-05 01:35:03', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(94, 4, 'public', 0, '2024-06-05 01:35:03', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(95, 4, 'public', 0, '2024-06-05 01:35:28', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(97, 4, 'public', 0, '2024-06-05 01:36:51', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(98, 4, 'public', 0, '2024-06-05 01:37:39', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(99, 4, 'public', 0, '2024-06-05 01:37:45', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(100, 4, 'public', 0, '2024-06-05 01:40:17', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, NULL),
(101, 4, 'public', NULL, '2024-06-05 02:46:23', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 0, '2024-06-05 02:46:23'),
(102, 1, 'public', NULL, '2024-06-06 03:31:56', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 1, NULL),
(103, 1, 'public', NULL, '2024-06-06 03:38:06', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 1, NULL),
(104, 1, 'public', NULL, '2024-06-06 03:40:09', NULL, NULL, NULL, NULL, NULL, NULL, 'light', 1, NULL),
(105, 1, 'public', NULL, '2024-06-06 03:42:09', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 1, NULL),
(106, 1, 'public', NULL, '2024-06-06 03:50:05', NULL, NULL, NULL, NULL, NULL, NULL, 'light', 1, NULL),
(107, 1, 'public', NULL, '2024-06-06 03:56:09', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 1, NULL),
(108, 1, 'public', NULL, '2024-06-06 03:58:10', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 1, NULL),
(109, 1, 'public', NULL, '2024-06-06 03:59:51', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 1, NULL),
(110, 1, 'public', NULL, '2024-06-06 04:01:14', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 1, NULL),
(111, 1, 'public', NULL, '2024-06-06 04:01:57', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 1, NULL),
(112, 1, 'public', NULL, '2024-06-06 04:02:04', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 1, NULL),
(113, 1, 'public', NULL, '2024-06-06 04:04:43', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 1, NULL),
(114, 1, 'public', NULL, '2024-06-06 04:04:54', NULL, NULL, NULL, NULL, NULL, NULL, 'dark', 1, NULL),
(115, 1, 'public', NULL, '2024-06-06 04:21:27', NULL, NULL, NULL, NULL, NULL, NULL, 'light', 1, NULL),
(116, 4, 'public', NULL, '2024-06-06 04:23:12', NULL, NULL, NULL, NULL, NULL, NULL, 'light', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `userpreferences`
--

CREATE TABLE `userpreferences` (
  `preference_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `theme` varchar(10) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `receive_notifications` tinyint(1) NOT NULL DEFAULT 0,
  `profile_image_id` int(11) DEFAULT NULL,
  `notifications_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `bio`, `created_at`, `updated_at`, `receive_notifications`, `profile_image_id`, `notifications_count`) VALUES
(1, 'asd', 'mizzi@gmail.com', '123', '', '2024-05-31 10:50:14', '2024-06-05 00:20:53', 1, NULL, 0),
(2, 'mar', 'mar@gf', 'asd', '', '2024-05-31 19:24:54', '2024-06-04 23:12:24', 1, NULL, 0),
(3, 'mc', 'mark@mc', '147', '', '2024-06-03 23:18:29', '2024-06-03 23:18:29', 0, NULL, 0),
(4, 'marf', 'mar@ghml', '123', '', '2024-06-05 00:27:36', '2024-06-05 00:27:36', 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `user_id` int(11) NOT NULL,
  `color_scheme` enum('light','dark') NOT NULL DEFAULT 'light',
  `receive_notifications` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`user_id`, `color_scheme`, `receive_notifications`) VALUES
(1, 'light', 1),
(4, 'light', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`user_id`,`follower_id`),
  ADD KEY `follower_id` (`follower_id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`follow_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`group_member_id`);

--
-- Indexes for table `group_messages`
--
ALTER TABLE `group_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `imagesdata`
--
ALTER TABLE `imagesdata`
  ADD PRIMARY KEY (`image`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `userpreferences`
--
ALTER TABLE `userpreferences`
  ADD PRIMARY KEY (`preference_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `follow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `group_member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `group_messages`
--
ALTER TABLE `group_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `imagesdata`
--
ALTER TABLE `imagesdata`
  MODIFY `image` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=375;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=344;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `userpreferences`
--
ALTER TABLE `userpreferences`
  MODIFY `preference_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `followers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `followers_ibfk_2` FOREIGN KEY (`follower_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `imagesdata`
--
ALTER TABLE `imagesdata`
  ADD CONSTRAINT `imagesdata_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `userpreferences`
--
ALTER TABLE `userpreferences`
  ADD CONSTRAINT `userpreferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `user_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
