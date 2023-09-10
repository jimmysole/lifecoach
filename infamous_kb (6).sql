-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2023 at 02:17 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `infamous_kb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` char(30) NOT NULL,
  `password` varchar(256) NOT NULL,
  `role` int(11) NOT NULL,
  `perms_file` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `role`, `perms_file`) VALUES
(3, 'admin1', '$2y$10$0cPTVZAnDvxEBr4kgLOU9uz62IKDmpijJhoAl3fLS8VpWPegpHh9y', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `author` char(50) NOT NULL,
  `title` char(150) NOT NULL,
  `subject` char(150) NOT NULL,
  `body` text NOT NULL,
  `date_written` datetime NOT NULL,
  `image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `article_id`, `author`, `title`, `subject`, `body`, `date_written`, `image`) VALUES
(2, 463, 'jimmysole', 'Battling Fear', 'Fear', 'Battling unbelief by trusting in Christ.', '2023-03-25 12:10:36', 'anxiety.jpg'),
(3, 139, 'jimmysole', 'Erasing fear through faith', 'Anxiety', 'Having faith in Christ to battle anxiety.', '2023-03-25 12:17:00', 'anxiety.jpg'),
(5, 838, 'jimmysole', 'Being Humble', 'humility', 'how to be humble like Christ?', '2023-03-26 04:01:36', 'pvh.jpg'),
(6, 75, 'jimmysole', 'Kevin is checking out the site', 'Kevin', 'bofaa', '2023-04-08 05:42:52', 'rawr.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `boards`
--

CREATE TABLE `boards` (
  `id` int(10) UNSIGNED NOT NULL,
  `board_name` char(100) NOT NULL,
  `board_moderators` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `boards`
--

INSERT INTO `boards` (`id`, `board_name`, `board_moderators`) VALUES
(1, 'PHP', 'Jimmy, Kevin'),
(2, 'test board 2', 'Jimmy, Kevin, Bofa');

-- --------------------------------------------------------

--
-- Table structure for table `board_posts`
--

CREATE TABLE `board_posts` (
  `board_id` int(10) UNSIGNED NOT NULL,
  `topic_id` int(10) NOT NULL,
  `author` char(30) NOT NULL,
  `topic` char(150) NOT NULL,
  `posts` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `board_posts`
--

INSERT INTO `board_posts` (`board_id`, `topic_id`, `author`, `topic`, `posts`) VALUES
(1, 1, 'jimmysole', 'PHP is cool', 'Who doesn\'t like PHP?'),
(1, 0, 'jimmysole', 'PHP 8.1 is such an improvement!', 'I absolutely love PHP 8.1. I can\'t wait to see what future releases will do!'),
(1, 0, 'jimmysole', 'PHP 9 is going to be sweet', 'I wonder what PHP 9 will be like.'),
(1, 0, 'jimmysole', 'PHP 10 will be C# and Java', 'I think PHP 10 will be like java and c# even more');

-- --------------------------------------------------------

--
-- Table structure for table `board_posts_responses`
--

CREATE TABLE `board_posts_responses` (
  `board_id` int(10) UNSIGNED NOT NULL,
  `topic_id` int(10) NOT NULL,
  `response` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `board_posts_responses`
--

INSERT INTO `board_posts_responses` (`board_id`, `topic_id`, `response`) VALUES
(1, 1, 'Only the boring old school guys.');

-- --------------------------------------------------------

--
-- Table structure for table `board_subscriptions`
--

CREATE TABLE `board_subscriptions` (
  `board_id` int(10) UNSIGNED NOT NULL,
  `board_subscribers` text NOT NULL,
  `board_notifications` int(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `board_subscriptions`
--

INSERT INTO `board_subscriptions` (`board_id`, `board_subscribers`, `board_notifications`) VALUES
(1, 'jimmysole', 1),
(1, 'jimmysole', 1),
(1, 'jimmysole', 1);

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `room_title` char(50) NOT NULL,
  `room_members` varchar(256) NOT NULL,
  `room_moderators` varchar(256) NOT NULL,
  `room_transcript` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `room_title`, `room_members`, `room_moderators`, `room_transcript`) VALUES
(1, 'Test Chat Room', 'jimmysole, kevin benitez', 'jimmysole', 'Test transcript'),
(4, 'Chat Room', 'jimmysole, jimmysole', 'jimmysole, jimmysole', 'hi');

-- --------------------------------------------------------

--
-- Table structure for table `conferences`
--

CREATE TABLE `conferences` (
  `id` int(11) NOT NULL,
  `user` char(30) NOT NULL,
  `appt_time` datetime NOT NULL,
  `title` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `confirmed_sessions`
--

CREATE TABLE `confirmed_sessions` (
  `id` int(11) NOT NULL,
  `user` char(30) NOT NULL,
  `counselor` char(30) NOT NULL,
  `message` text NOT NULL,
  `submitted_date` datetime NOT NULL,
  `approved_date` datetime NOT NULL,
  `duration` int(11) NOT NULL,
  `started` int(1) NOT NULL,
  `scheduled` int(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `confirmed_sessions`
--

INSERT INTO `confirmed_sessions` (`id`, `user`, `counselor`, `message`, `submitted_date`, `approved_date`, `duration`, `started`, `scheduled`) VALUES
(7, 'kevin', 'jimmysole', 'please help me you bofa', '2023-02-22 10:20:48', '2023-03-20 11:30:00', 30, 1, 0),
(9, 'kevin', 'jimmysole', 'please help me you bofa', '2023-02-22 10:20:48', '2023-04-10 14:00:00', 45, 1, 0),
(10, 'kevin', 'jimmysole', 'please help me you bofa', '2023-02-22 10:20:48', '2023-03-15 11:03:00', 26, 0, 0),
(11, 'kevin', 'jimmysole', 'please help me you bofa', '2023-02-22 10:20:48', '2023-03-10 07:00:00', 30, 0, 0),
(12, 'kevin', 'jimmysole', 'please help me you bofa', '2023-02-22 10:20:48', '2023-03-10 06:36:00', 30, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `deleted_boards`
--

CREATE TABLE `deleted_boards` (
  `board_id` int(10) UNSIGNED NOT NULL,
  `board_name` char(100) NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deleted_boards_posts`
--

CREATE TABLE `deleted_boards_posts` (
  `board_id` int(10) UNSIGNED NOT NULL,
  `board_name` char(100) NOT NULL,
  `board_post` text NOT NULL,
  `board_post_id` int(10) UNSIGNED NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deleted_hot_topics`
--

CREATE TABLE `deleted_hot_topics` (
  `board_id` int(10) UNSIGNED NOT NULL,
  `topic` char(200) NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `errors`
--

CREATE TABLE `errors` (
  `timestamp` datetime NOT NULL,
  `error_msg` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `errors`
--

INSERT INTO `errors` (`timestamp`, `error_msg`) VALUES
('2023-02-06 19:47:52', 'Laminas\\Mail\\Transport\\Exception\\RuntimeExcep'),
('2023-02-06 19:47:52', 'foreach() argument must be of type array|obje'),
('2023-02-06 19:48:15', 'TypeError: Application\\Classes\\Login::verifyC'),
('2023-02-06 19:48:15', 'foreach() argument must be of type array|obje'),
('2023-02-06 19:48:28', 'TypeError: Application\\Classes\\Login::verifyC'),
('2023-02-06 19:48:28', 'foreach() argument must be of type array|obje'),
('2023-02-06 19:51:05', 'Laminas\\Authentication\\Adapter\\DbTable\\Except'),
('2023-02-06 19:51:05', 'foreach() argument must be of type array|obje'),
('2023-02-07 18:11:05', 'TypeError: Application\\Classes\\User::getStore'),
('2023-02-07 18:11:05', 'foreach() argument must be of type array|obje'),
('2023-02-07 18:13:13', 'TypeError: Application\\Classes\\User::getStore'),
('2023-02-07 18:13:14', 'foreach() argument must be of type array|obje'),
('2023-02-08 23:02:35', 'Error: Typed property Application\\Model\\Filte'),
('2023-02-08 23:02:35', 'foreach() argument must be of type array|obje'),
('2023-02-08 23:02:48', 'Error: Typed property Application\\Model\\Filte'),
('2023-02-08 23:02:48', 'foreach() argument must be of type array|obje'),
('2023-02-08 23:03:08', 'Error: Typed property Application\\Model\\Filte'),
('2023-02-08 23:03:08', 'foreach() argument must be of type array|obje'),
('2023-02-08 23:05:06', 'Error: Typed property Application\\Model\\Filte'),
('2023-02-08 23:05:06', 'foreach() argument must be of type array|obje'),
('2023-02-08 23:06:11', 'TypeError: Cannot assign null to property App'),
('2023-02-08 23:06:11', 'foreach() argument must be of type array|obje'),
('2023-02-09 01:30:16', 'TypeError: Application\\Classes\\User::getStore'),
('2023-02-09 01:30:16', 'foreach() argument must be of type array|obje'),
('2023-02-09 16:26:30', 'ArgumentCountError: Too few arguments to func'),
('2023-02-09 16:26:30', 'foreach() argument must be of type array|obje'),
('2023-02-09 16:27:37', 'ArgumentCountError: Too few arguments to func'),
('2023-02-09 16:27:37', 'foreach() argument must be of type array|obje'),
('2023-02-09 16:27:38', 'ArgumentCountError: Too few arguments to func'),
('2023-02-09 16:27:38', 'foreach() argument must be of type array|obje'),
('2023-02-09 16:28:12', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-09 16:28:12', 'foreach() argument must be of type array|obje'),
('2023-02-09 16:42:32', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-09 16:42:32', 'foreach() argument must be of type array|obje'),
('2023-02-09 16:42:32', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-09 16:42:32', 'foreach() argument must be of type array|obje'),
('2023-02-09 16:43:04', 'TypeError: Application\\Classes\\User::getStore'),
('2023-02-09 16:43:04', 'foreach() argument must be of type array|obje'),
('2023-02-09 16:44:06', 'TypeError: Application\\Model\\LogoutModel::__c'),
('2023-02-09 16:44:06', 'foreach() argument must be of type array|obje'),
('2023-02-09 16:45:44', 'TypeError: Application\\Model\\LogoutModel::__c'),
('2023-02-09 16:45:44', 'foreach() argument must be of type array|obje'),
('2023-02-09 16:47:01', 'Error: Class \"Lamminas\\Db\\Sql\\Sql\" not found '),
('2023-02-09 16:47:01', 'foreach() argument must be of type array|obje'),
('2023-02-09 16:47:03', 'Error: Class \"Lamminas\\Db\\Sql\\Sql\" not found '),
('2023-02-09 16:47:03', 'foreach() argument must be of type array|obje'),
('2023-02-09 17:08:49', 'TypeError: Cannot assign ArrayObject to prope'),
('2023-02-09 17:08:49', 'foreach() argument must be of type array|obje'),
('2023-02-09 17:14:51', 'Laminas\\ServiceManager\\Exception\\ServiceNotFo'),
('2023-02-09 17:19:47', 'Laminas\\Authentication\\Adapter\\DbTable\\Except'),
('2023-02-09 17:19:47', 'Laminas\\Authentication\\Adapter\\DbTable\\Except'),
('2023-02-09 17:19:48', 'foreach() argument must be of type array|obje'),
('2023-02-09 19:44:09', 'Laminas\\Authentication\\Adapter\\DbTable\\Except'),
('2023-02-09 19:44:09', 'Laminas\\Authentication\\Adapter\\DbTable\\Except'),
('2023-02-09 19:44:09', 'foreach() argument must be of type array|obje'),
('2023-02-09 19:45:47', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-09 19:45:47', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-09 19:45:47', 'foreach() argument must be of type array|obje'),
('2023-02-09 19:45:47', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-09 19:45:47', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-09 19:45:48', 'foreach() argument must be of type array|obje'),
('2023-02-09 19:46:42', 'ArgumentCountError: Too few arguments to func'),
('2023-02-09 19:46:42', 'ArgumentCountError: Too few arguments to func'),
('2023-02-09 19:46:42', 'foreach() argument must be of type array|obje'),
('2023-02-12 13:58:14', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 13:58:14', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 13:58:15', 'foreach() argument must be of type array|obje'),
('2023-02-12 19:43:03', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 19:43:03', 'foreach() argument must be of type array|obje'),
('2023-02-12 19:43:04', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 19:43:04', 'foreach() argument must be of type array|obje'),
('2023-02-12 19:43:07', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 19:43:07', 'foreach() argument must be of type array|obje'),
('2023-02-12 19:44:35', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 19:44:35', 'foreach() argument must be of type array|obje'),
('2023-02-12 19:46:46', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 19:46:46', 'foreach() argument must be of type array|obje'),
('2023-02-12 19:47:44', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 19:47:44', 'foreach() argument must be of type array|obje'),
('2023-02-12 19:47:45', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 19:47:45', 'foreach() argument must be of type array|obje'),
('2023-02-12 19:50:45', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 19:50:45', 'foreach() argument must be of type array|obje'),
('2023-02-12 19:50:52', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 19:50:52', 'foreach() argument must be of type array|obje'),
('2023-02-12 20:01:53', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 20:01:53', 'foreach() argument must be of type array|obje'),
('2023-02-12 20:04:13', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 20:04:32', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 20:04:32', 'foreach() argument must be of type array|obje'),
('2023-02-12 20:06:18', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 20:06:18', 'foreach() argument must be of type array|obje'),
('2023-02-12 20:07:13', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 20:07:13', 'foreach() argument must be of type array|obje'),
('2023-02-12 22:19:21', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 22:19:21', 'foreach() argument must be of type array|obje'),
('2023-02-12 22:19:41', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-02-12 22:19:41', 'foreach() argument must be of type array|obje'),
('2023-02-13 15:54:53', 'Laminas\\Authentication\\Adapter\\DbTable\\Except'),
('2023-02-13 15:54:53', 'foreach() argument must be of type array|obje'),
('2023-02-13 15:56:15', 'Laminas\\Authentication\\Adapter\\DbTable\\Except'),
('2023-02-13 15:56:15', 'foreach() argument must be of type array|obje'),
('2023-02-13 15:57:05', 'Laminas\\Authentication\\Adapter\\DbTable\\Except'),
('2023-02-13 15:57:05', 'foreach() argument must be of type array|obje'),
('2023-02-13 15:58:19', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 15:58:19', 'foreach() argument must be of type array|obje'),
('2023-02-13 16:19:33', 'Laminas\\ServiceManager\\Exception\\ServiceNotFo'),
('2023-02-13 16:19:33', 'foreach() argument must be of type array|obje'),
('2023-02-13 16:21:29', 'Laminas\\ServiceManager\\Exception\\ServiceNotFo'),
('2023-02-13 16:21:29', 'foreach() argument must be of type array|obje'),
('2023-02-13 16:21:33', 'Laminas\\ServiceManager\\Exception\\ServiceNotFo'),
('2023-02-13 16:21:33', 'foreach() argument must be of type array|obje'),
('2023-02-13 16:21:48', 'Laminas\\ServiceManager\\Exception\\ServiceNotFo'),
('2023-02-13 16:21:48', 'foreach() argument must be of type array|obje'),
('2023-02-13 16:25:51', 'Laminas\\ServiceManager\\Exception\\ServiceNotFo'),
('2023-02-13 16:25:51', 'foreach() argument must be of type array|obje'),
('2023-02-13 16:28:23', 'Laminas\\ServiceManager\\Exception\\ServiceNotFo'),
('2023-02-13 16:28:23', 'foreach() argument must be of type array|obje'),
('2023-02-13 16:30:49', 'Error: Typed property Application\\Controller\\'),
('2023-02-13 16:30:49', 'foreach() argument must be of type array|obje'),
('2023-02-13 16:31:09', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 16:31:09', 'foreach() argument must be of type array|obje'),
('2023-02-13 16:34:49', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 16:34:49', 'foreach() argument must be of type array|obje'),
('2023-02-13 16:35:24', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 16:35:24', 'foreach() argument must be of type array|obje'),
('2023-02-13 16:35:51', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 16:36:18', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 16:36:34', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 16:37:11', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 16:38:06', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 16:38:08', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 16:38:45', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 16:39:37', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 16:45:29', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 16:49:28', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 17:35:39', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 17:36:14', 'TypeError: Application\\Model\\UserModel::__con'),
('2023-02-13 17:36:14', 'foreach() argument must be of type array|obje'),
('2023-02-13 17:36:37', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 17:36:37', 'foreach() argument must be of type array|obje'),
('2023-02-13 17:37:30', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 17:37:30', 'foreach() argument must be of type array|obje'),
('2023-02-13 17:37:33', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 17:37:33', 'foreach() argument must be of type array|obje'),
('2023-02-13 17:37:49', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 17:37:49', 'foreach() argument must be of type array|obje'),
('2023-02-13 17:38:31', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 17:38:31', 'foreach() argument must be of type array|obje'),
('2023-02-13 17:38:58', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 17:38:58', 'foreach() argument must be of type array|obje'),
('2023-02-13 17:52:51', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 17:52:51', 'foreach() argument must be of type array|obje'),
('2023-02-13 18:00:17', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 18:00:17', 'foreach() argument must be of type array|obje'),
('2023-02-13 18:00:30', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 18:00:30', 'foreach() argument must be of type array|obje'),
('2023-02-13 18:00:37', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 18:00:38', 'foreach() argument must be of type array|obje'),
('2023-02-13 18:01:10', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 18:01:10', 'foreach() argument must be of type array|obje'),
('2023-02-13 18:01:22', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 18:01:22', 'foreach() argument must be of type array|obje'),
('2023-02-13 18:02:11', 'TypeError: Cannot access offset of type strin'),
('2023-02-13 18:02:11', 'foreach() argument must be of type array|obje'),
('2023-02-13 18:02:26', 'TypeError: Cannot access offset of type strin'),
('2023-02-13 18:02:26', 'foreach() argument must be of type array|obje'),
('2023-02-13 18:02:40', 'TypeError: Cannot access offset of type strin'),
('2023-02-13 18:02:40', 'foreach() argument must be of type array|obje'),
('2023-02-13 18:02:52', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 18:02:52', 'foreach() argument must be of type array|obje'),
('2023-02-13 18:03:01', 'TypeError: Application\\Model\\UserModel::check'),
('2023-02-13 18:03:01', 'foreach() argument must be of type array|obje'),
('2023-02-22 17:50:22', 'PDOException: SQLSTATE[42S22]: Column not fou'),
('2023-02-22 17:50:22', 'foreach() argument must be of type array|obje'),
('2023-02-22 17:51:28', 'PDOException: SQLSTATE[42S22]: Column not fou'),
('2023-02-22 17:51:29', 'foreach() argument must be of type array|obje'),
('2023-02-22 17:56:14', 'PDOException: SQLSTATE[42S22]: Column not fou'),
('2023-02-22 17:56:14', 'foreach() argument must be of type array|obje'),
('2023-02-27 17:53:21', 'TypeError: explode(): Argument #2 ($string) m'),
('2023-02-27 17:53:21', 'foreach() argument must be of type array|obje'),
('2023-02-27 22:17:10', 'Error: Typed property Application\\Controller\\'),
('2023-02-27 22:17:10', 'foreach() argument must be of type array|obje'),
('2023-02-27 22:21:12', 'ValueError: array_combine(): Argument #1 ($ke'),
('2023-02-27 22:21:12', 'foreach() argument must be of type array|obje'),
('2023-02-27 22:24:37', 'PDOException: SQLSTATE[23000]: Integrity cons'),
('2023-02-27 22:24:37', 'foreach() argument must be of type array|obje'),
('2023-03-11 00:16:53', 'TypeError: Application\\Model\\UserModel::cance'),
('2023-03-11 00:16:54', 'foreach() argument must be of type array|obje'),
('2023-03-11 00:39:17', 'ValueError: array_combine(): Argument #1 ($ke'),
('2023-03-11 00:39:17', 'foreach() argument must be of type array|obje'),
('2023-03-11 00:46:51', 'TypeError: count(): Argument #1 ($value) must'),
('2023-03-11 00:46:51', 'foreach() argument must be of type array|obje'),
('2023-03-11 00:46:51', 'ValueError: array_combine(): Argument #1 ($ke'),
('2023-03-11 00:46:52', 'foreach() argument must be of type array|obje'),
('2023-03-11 00:47:43', 'ValueError: array_combine(): Argument #1 ($ke'),
('2023-03-11 00:47:44', 'foreach() argument must be of type array|obje'),
('2023-03-11 17:35:24', 'ValueError: array_combine(): Argument #1 ($ke'),
('2023-03-11 17:35:25', 'foreach() argument must be of type array|obje'),
('2023-03-16 21:22:18', 'TypeError: Application\\Model\\UserModel::resch'),
('2023-03-16 21:22:18', 'foreach() argument must be of type array|obje'),
('2023-03-16 21:39:51', 'TypeError: array_values(): Argument #1 ($arra'),
('2023-03-16 21:39:52', 'foreach() argument must be of type array|obje'),
('2023-03-16 21:42:15', 'PDOException: SQLSTATE[42S02]: Base table or '),
('2023-03-16 21:42:15', 'foreach() argument must be of type array|obje'),
('2023-03-20 22:09:59', 'PDOException: SQLSTATE[42S22]: Column not fou'),
('2023-03-20 22:09:59', 'foreach() argument must be of type array|obje'),
('2023-03-24 17:26:05', 'ParseError: syntax error, unexpected token \"}'),
('2023-03-24 17:26:05', 'foreach() argument must be of type array|obje'),
('2023-03-25 00:07:35', 'PDOException: SQLSTATE[42S22]: Column not fou'),
('2023-03-25 00:07:35', 'foreach() argument must be of type array|obje'),
('2023-03-25 18:23:43', 'TypeError: Cannot assign Application\\Classes\\'),
('2023-03-25 18:23:43', 'foreach() argument must be of type array|obje'),
('2023-03-25 18:24:38', 'TypeError: Cannot assign Application\\Classes\\'),
('2023-03-25 18:24:38', 'foreach() argument must be of type array|obje'),
('2023-03-25 18:27:31', 'Error: Typed property Application\\Model\\Artic'),
('2023-03-25 18:27:31', 'foreach() argument must be of type array|obje'),
('2023-03-25 18:28:17', 'TypeError: Cannot assign Application\\Classes\\'),
('2023-03-25 18:28:17', 'foreach() argument must be of type array|obje'),
('2023-03-25 18:28:36', 'TypeError: Cannot assign Application\\Classes\\'),
('2023-03-25 18:28:36', 'foreach() argument must be of type array|obje'),
('2023-03-25 23:57:15', 'ArgumentCountError: Too few arguments to func'),
('2023-03-25 23:57:15', 'foreach() argument must be of type array|obje'),
('2023-03-26 00:03:21', 'TypeError: Application\\Model\\UserModel::postA'),
('2023-03-26 00:03:21', 'foreach() argument must be of type array|obje'),
('2023-03-26 00:36:10', 'TypeError: Application\\Model\\UserModel::postA'),
('2023-03-26 00:36:10', 'foreach() argument must be of type array|obje'),
('2023-03-26 15:22:08', 'TypeError: Application\\Model\\UserModel::postA'),
('2023-03-26 15:22:08', 'foreach() argument must be of type array|obje'),
('2023-03-26 15:25:17', 'TypeError: Application\\Model\\UserModel::postA'),
('2023-03-26 15:25:17', 'foreach() argument must be of type array|obje'),
('2023-03-26 15:35:53', 'PDOException: SQLSTATE[23000]: Integrity cons'),
('2023-03-26 15:35:53', 'foreach() argument must be of type array|obje'),
('2023-03-26 15:52:15', 'TypeError: Application\\Model\\UserModel::postA'),
('2023-03-26 15:52:15', 'foreach() argument must be of type array|obje'),
('2023-03-26 15:53:15', 'PDOException: SQLSTATE[23000]: Integrity cons'),
('2023-03-26 15:53:15', 'foreach() argument must be of type array|obje'),
('2023-03-26 15:56:40', 'TypeError: Application\\Model\\UserModel::postA'),
('2023-03-26 15:56:40', 'foreach() argument must be of type array|obje'),
('2023-03-26 15:57:40', 'TypeError: Application\\Model\\UserModel::postA'),
('2023-03-26 15:57:40', 'foreach() argument must be of type array|obje'),
('2023-04-01 21:55:35', 'Error: Call to a member function setVariable('),
('2023-04-01 21:55:35', 'foreach() argument must be of type array|obje'),
('2023-04-01 21:58:57', 'Error: Typed property Application\\Controller\\'),
('2023-04-01 21:58:57', 'foreach() argument must be of type array|obje'),
('2023-04-06 21:35:09', 'TypeError: Application\\Model\\SocialModel::__c'),
('2023-04-06 21:35:09', 'foreach() argument must be of type array|obje'),
('2023-04-06 21:35:21', 'TypeError: Application\\Model\\SocialModel::__c'),
('2023-04-06 21:35:21', 'foreach() argument must be of type array|obje'),
('2023-04-06 22:04:54', 'TypeError: Application\\Model\\SocialModel::__c'),
('2023-04-06 22:04:54', 'foreach() argument must be of type array|obje'),
('2023-04-09 19:26:31', 'PDOException: SQLSTATE[23000]: Integrity cons'),
('2023-04-09 19:26:31', 'foreach() argument must be of type array|obje'),
('2023-04-10 23:00:39', 'TypeError: Application\\Model\\UserModel::check'),
('2023-04-10 23:00:39', 'foreach() argument must be of type array|obje'),
('2023-04-10 23:00:53', 'TypeError: Application\\Model\\UserModel::check'),
('2023-04-10 23:00:53', 'foreach() argument must be of type array|obje'),
('2023-04-10 23:01:14', 'TypeError: Application\\Model\\UserModel::check'),
('2023-04-10 23:01:14', 'foreach() argument must be of type array|obje'),
('2023-04-10 23:02:20', 'TypeError: Application\\Model\\UserModel::check'),
('2023-04-10 23:02:20', 'foreach() argument must be of type array|obje'),
('2023-04-10 23:02:25', 'TypeError: Application\\Model\\UserModel::check'),
('2023-04-10 23:02:25', 'foreach() argument must be of type array|obje'),
('2023-04-10 23:02:53', 'TypeError: Application\\Model\\UserModel::check'),
('2023-04-10 23:02:53', 'foreach() argument must be of type array|obje'),
('2023-04-10 23:03:07', 'TypeError: Application\\Model\\UserModel::check'),
('2023-04-10 23:03:07', 'foreach() argument must be of type array|obje'),
('2023-04-10 23:03:57', 'TypeError: Application\\Model\\UserModel::check'),
('2023-04-10 23:03:57', 'foreach() argument must be of type array|obje'),
('2023-04-10 23:04:36', 'TypeError: Application\\Model\\UserModel::check'),
('2023-04-10 23:04:37', 'foreach() argument must be of type array|obje'),
('2023-04-10 23:04:39', 'TypeError: Application\\Model\\UserModel::check'),
('2023-04-10 23:04:39', 'foreach() argument must be of type array|obje'),
('2023-04-16 15:25:46', 'PDOException: SQLSTATE[23000]: Integrity cons'),
('2023-04-16 15:25:46', 'foreach() argument must be of type array|obje'),
('2023-04-16 15:26:24', 'PDOException: SQLSTATE[23000]: Integrity cons'),
('2023-04-16 15:26:24', 'foreach() argument must be of type array|obje'),
('2023-04-16 15:26:46', 'PDOException: SQLSTATE[23000]: Integrity cons'),
('2023-04-16 15:26:46', 'foreach() argument must be of type array|obje'),
('2023-04-16 15:28:50', 'TypeError: array_values(): Argument #1 ($arra'),
('2023-04-16 15:28:50', 'foreach() argument must be of type array|obje'),
('2023-04-16 15:30:27', 'PDOException: SQLSTATE[23000]: Integrity cons'),
('2023-04-16 15:30:27', 'foreach() argument must be of type array|obje'),
('2023-04-18 21:59:43', 'PDOException: SQLSTATE[42S02]: Base table or '),
('2023-04-18 21:59:43', 'foreach() argument must be of type array|obje'),
('2023-05-16 16:53:43', 'Laminas\\Di\\Exception\\MissingPropertyException'),
('2023-05-16 16:53:43', 'foreach() argument must be of type array|obje'),
('2023-05-16 17:07:15', 'ParseError: syntax error, unexpected variable'),
('2023-05-16 17:07:15', 'foreach() argument must be of type array|obje'),
('2023-05-17 21:11:44', 'TypeError: Application\\Model\\ForumModel::__co'),
('2023-05-17 21:11:45', 'foreach() argument must be of type array|obje'),
('2023-05-17 21:27:15', 'TypeError: Application\\Model\\ForumModel::__co'),
('2023-05-17 21:27:16', 'foreach() argument must be of type array|obje'),
('2023-05-17 21:44:33', 'PDOException: SQLSTATE[42S22]: Column not fou'),
('2023-05-17 21:44:34', 'foreach() argument must be of type array|obje'),
('2023-05-22 03:26:50', 'TypeError: Application\\Model\\ForumModel::__co'),
('2023-05-22 03:26:51', 'foreach() argument must be of type array|obje'),
('2023-05-22 03:27:03', 'TypeError: Application\\Model\\ForumModel::__co'),
('2023-05-22 03:27:03', 'foreach() argument must be of type array|obje'),
('2023-05-22 03:27:46', 'TypeError: Application\\Model\\ForumModel::post'),
('2023-05-22 03:27:46', 'foreach() argument must be of type array|obje'),
('2023-05-22 03:35:09', 'TypeError: Application\\Model\\ForumModel::post'),
('2023-05-22 03:35:09', 'foreach() argument must be of type array|obje'),
('2023-05-22 03:35:42', 'TypeError: Application\\Model\\ForumModel::post'),
('2023-05-22 03:35:42', 'foreach() argument must be of type array|obje'),
('2023-05-22 03:36:42', 'PDOException: SQLSTATE[23000]: Integrity cons'),
('2023-05-22 03:36:42', 'foreach() argument must be of type array|obje'),
('2023-05-22 03:38:01', 'PDOException: SQLSTATE[23000]: Integrity cons'),
('2023-05-22 03:38:01', 'foreach() argument must be of type array|obje'),
('2023-05-22 03:48:57', 'PDOException: SQLSTATE[42S02]: Base table or '),
('2023-05-22 03:48:57', 'foreach() argument must be of type array|obje'),
('2023-05-22 17:16:25', 'PDOException: SQLSTATE[23000]: Integrity cons'),
('2023-05-22 17:16:25', 'foreach() argument must be of type array|obje'),
('2023-05-22 22:43:40', 'PDOException: SQLSTATE[23000]: Integrity cons'),
('2023-05-22 22:43:40', 'foreach() argument must be of type array|obje'),
('2023-05-22 22:44:40', 'PDOException: SQLSTATE[23000]: Integrity cons'),
('2023-05-22 22:44:40', 'foreach() argument must be of type array|obje'),
('2023-05-22 23:53:02', 'PDOException: SQLSTATE[42S22]: Column not fou'),
('2023-05-22 23:53:02', 'foreach() argument must be of type array|obje'),
('2023-05-27 02:42:16', 'Error: Call to a member function setTerminal('),
('2023-05-27 02:42:16', 'foreach() argument must be of type array|obje'),
('2023-05-27 02:43:16', 'TypeError: Application\\Model\\SocialModel::vie'),
('2023-05-27 02:43:16', 'foreach() argument must be of type array|obje'),
('2023-05-27 02:43:46', 'TypeError: Application\\Model\\SocialModel::vie'),
('2023-05-27 02:43:46', 'foreach() argument must be of type array|obje'),
('2023-05-27 02:52:05', 'ArgumentCountError: Too few arguments to func'),
('2023-05-27 02:52:05', 'foreach() argument must be of type array|obje'),
('2023-05-27 02:58:09', 'ArgumentCountError: Too few arguments to func'),
('2023-05-27 02:58:09', 'foreach() argument must be of type array|obje'),
('2023-06-01 01:11:53', 'TypeError: Application\\Model\\ForumModel::__co'),
('2023-06-01 01:11:53', 'foreach() argument must be of type array|obje'),
('2023-06-01 01:27:13', 'TypeError: Application\\Model\\ForumModel::__co'),
('2023-06-01 01:27:13', 'foreach() argument must be of type array|obje'),
('2023-06-01 22:23:41', 'PDOException: SQLSTATE[42S02]: Base table or '),
('2023-06-01 22:23:41', 'foreach() argument must be of type array|obje'),
('2023-06-06 17:29:09', 'ParseError: syntax error, unexpected variable'),
('2023-06-06 17:29:09', 'foreach() argument must be of type array|obje'),
('2023-07-05 22:04:50', 'Error: Call to undefined method Laminas\\Mvc\\C'),
('2023-07-05 22:04:50', 'foreach() argument must be of type array|obje'),
('2023-07-05 22:06:31', 'TypeError: Cannot access offset of type strin'),
('2023-07-05 22:06:31', 'foreach() argument must be of type array|obje'),
('2023-07-05 22:08:58', 'PDOException: SQLSTATE[23000]: Integrity cons'),
('2023-07-05 22:08:58', 'foreach() argument must be of type array|obje'),
('2023-07-28 11:02:28', 'TypeError: Application\\Model\\SocialModel::__c'),
('2023-07-28 11:02:28', 'foreach() argument must be of type array|obje'),
('2023-07-28 11:02:28', 'TypeError: Application\\Model\\UserModel::__con'),
('2023-07-28 11:02:28', 'foreach() argument must be of type array|obje');

-- --------------------------------------------------------

--
-- Table structure for table `forum_users`
--

CREATE TABLE `forum_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` char(30) NOT NULL,
  `bio` text NOT NULL,
  `active` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hot_topics`
--

CREATE TABLE `hot_topics` (
  `id` int(10) UNSIGNED NOT NULL,
  `board_id` int(10) UNSIGNED NOT NULL,
  `topic` char(200) NOT NULL,
  `views` int(10) UNSIGNED NOT NULL,
  `posts` int(10) UNSIGNED NOT NULL,
  `subscribers` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `from` char(30) NOT NULL,
  `to` char(30) NOT NULL,
  `message` text NOT NULL,
  `date_sent` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pending_chat_requests`
--

CREATE TABLE `pending_chat_requests` (
  `id` int(10) NOT NULL,
  `recipient` char(30) NOT NULL,
  `sent_by` char(30) NOT NULL,
  `message` text NOT NULL,
  `date_sent` datetime NOT NULL,
  `chat_accepted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_chat_requests`
--

INSERT INTO `pending_chat_requests` (`id`, `recipient`, `sent_by`, `message`, `date_sent`, `chat_accepted`) VALUES
(2, 'jimmysole', 'jimmysole', 'hi how are you?', '2023-04-09 19:27:31', 2);

-- --------------------------------------------------------

--
-- Table structure for table `pending_users`
--

CREATE TABLE `pending_users` (
  `id` int(11) NOT NULL,
  `username` char(30) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` char(75) NOT NULL,
  `first_name` char(10) NOT NULL,
  `last_name` char(30) NOT NULL,
  `address` char(150) NOT NULL,
  `city` char(50) NOT NULL,
  `state` char(2) NOT NULL,
  `zipcode` int(5) NOT NULL,
  `country` char(100) NOT NULL,
  `registration_code` char(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(10) NOT NULL,
  `username` char(30) NOT NULL,
  `real_name` char(50) NOT NULL,
  `location` char(100) NOT NULL,
  `avatar` char(120) NOT NULL,
  `bio` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `username`, `real_name`, `location`, `avatar`, `bio`) VALUES
(6, 'jimmysole', 'Jimmy Sole', 'North Kingstown', '247846974_104111275430532_1317965216079313999_n.jpg', 'Hey I\'m Jim and I love programming. ');

-- --------------------------------------------------------

--
-- Table structure for table `requested_sessions`
--

CREATE TABLE `requested_sessions` (
  `id` int(11) NOT NULL,
  `user` char(30) NOT NULL,
  `counselor` char(30) NOT NULL,
  `message` text NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `username` char(30) NOT NULL,
  `password` varchar(256) NOT NULL,
  `active` int(1) NOT NULL,
  `session_id` char(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` char(30) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` char(75) NOT NULL,
  `first_name` char(10) NOT NULL,
  `last_name` char(30) NOT NULL,
  `address` char(150) NOT NULL,
  `city` char(50) NOT NULL,
  `state` char(2) NOT NULL,
  `zipcode` int(5) UNSIGNED ZEROFILL NOT NULL,
  `country` char(100) NOT NULL,
  `active` int(1) NOT NULL,
  `is_admin` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `first_name`, `last_name`, `address`, `city`, `state`, `zipcode`, `country`, `active`, `is_admin`) VALUES
(1, 'jimmysole', '$2y$10$vY5y0VDIJXMUMzgdWr76M.MqDk1NlweVac26wdnroc8bc80OIe8JG', 'jimmysole@gmail.com', 'James', 'Sole', '103 Birchwood Dr', 'North Kingstown', 'RI', 02852, 'United States', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `article_id_UNIQUE` (`article_id`);

--
-- Indexes for table `boards`
--
ALTER TABLE `boards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `board_name` (`board_name`);

--
-- Indexes for table `board_posts`
--
ALTER TABLE `board_posts`
  ADD KEY `board_id` (`board_id`);

--
-- Indexes for table `board_posts_responses`
--
ALTER TABLE `board_posts_responses`
  ADD KEY `board_topic_response_idx` (`board_id`);

--
-- Indexes for table `board_subscriptions`
--
ALTER TABLE `board_subscriptions`
  ADD KEY `board_sub_fk_idx` (`board_id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_title_UNIQUE` (`room_title`);

--
-- Indexes for table `conferences`
--
ALTER TABLE `conferences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `confirmed_sessions`
--
ALTER TABLE `confirmed_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deleted_boards`
--
ALTER TABLE `deleted_boards`
  ADD PRIMARY KEY (`board_id`),
  ADD UNIQUE KEY `board_name` (`board_name`);

--
-- Indexes for table `deleted_boards_posts`
--
ALTER TABLE `deleted_boards_posts`
  ADD PRIMARY KEY (`board_id`),
  ADD UNIQUE KEY `board_name` (`board_name`);

--
-- Indexes for table `deleted_hot_topics`
--
ALTER TABLE `deleted_hot_topics`
  ADD PRIMARY KEY (`board_id`);

--
-- Indexes for table `forum_users`
--
ALTER TABLE `forum_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `hot_topics`
--
ALTER TABLE `hot_topics`
  ADD PRIMARY KEY (`id`,`board_id`),
  ADD KEY `board_id` (`board_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_chat_requests`
--
ALTER TABLE `pending_chat_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_users`
--
ALTER TABLE `pending_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `requested_sessions`
--
ALTER TABLE `requested_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `boards`
--
ALTER TABLE `boards`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `conferences`
--
ALTER TABLE `conferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `confirmed_sessions`
--
ALTER TABLE `confirmed_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `forum_users`
--
ALTER TABLE `forum_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hot_topics`
--
ALTER TABLE `hot_topics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pending_chat_requests`
--
ALTER TABLE `pending_chat_requests`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pending_users`
--
ALTER TABLE `pending_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `requested_sessions`
--
ALTER TABLE `requested_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `board_posts`
--
ALTER TABLE `board_posts`
  ADD CONSTRAINT `board_posts_ibfk_1` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `board_posts_responses`
--
ALTER TABLE `board_posts_responses`
  ADD CONSTRAINT `board_topic_response` FOREIGN KEY (`board_id`) REFERENCES `board_posts` (`board_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `board_subscriptions`
--
ALTER TABLE `board_subscriptions`
  ADD CONSTRAINT `board_sub_fk` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hot_topics`
--
ALTER TABLE `hot_topics`
  ADD CONSTRAINT `hot_topics_ibfk_1` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `username` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
