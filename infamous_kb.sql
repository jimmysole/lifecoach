-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2023 at 09:29 PM
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
  `password` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(3, 'admin1', '$2y$10$0cPTVZAnDvxEBr4kgLOU9uz62IKDmpijJhoAl3fLS8VpWPegpHh9y');

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
(1, 725, 'jimmysole', 'Anxiety', 'Battling Unbelief', 'Battlinggggg unbelieffffff by trusting in Christ.', '0000-00-00 00:00:00', 'anxiety.jpg'),
(2, 463, 'jimmysole', 'Fear', 'Facing Fear Through Faith', 'Battlinggggg unbelieffffff by trusting in Christ.', '2023-03-25 12:10:36', 'anxiety.jpg'),
(3, 139, 'jimmysole', 'Erasing fear through faith', 'Anxiety', 'Having faith in Christ to battle anxiety.', '2023-03-25 12:17:00', 'anxiety.jpg'),
(5, 838, 'jimmysole', 'Being Humble', 'humility', 'how to be humble like Christ?', '2023-03-26 04:01:36', 'pvh.jpg');

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
  `started` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `confirmed_sessions`
--

INSERT INTO `confirmed_sessions` (`id`, `user`, `counselor`, `message`, `submitted_date`, `approved_date`, `duration`, `started`) VALUES
(7, 'kevin', 'jimmysole', 'please help me you bofa', '2023-02-22 10:20:48', '2023-03-20 11:30:00', 30, 1),
(8, 'kevin', 'jimmysole', 'please help me you bofa', '2023-02-22 10:20:48', '2023-03-17 11:00:00', 45, 0),
(9, 'kevin', 'jimmysole', 'please help me you bofa', '2023-02-22 10:20:48', '2023-03-06 14:02:00', 45, 0),
(10, 'kevin', 'jimmysole', 'please help me you bofa', '2023-02-22 10:20:48', '2023-03-15 11:03:00', 26, 0),
(11, 'kevin', 'jimmysole', 'please help me you bofa', '2023-02-22 10:20:48', '2023-03-10 07:00:00', 30, 0),
(12, 'kevin', 'jimmysole', 'please help me you bofa', '2023-02-22 10:20:48', '2023-03-10 06:36:00', 30, 0);

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
('2023-03-26 15:57:40', 'foreach() argument must be of type array|obje');

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
  `zipcode` int(5) NOT NULL,
  `country` char(100) NOT NULL,
  `active` int(1) NOT NULL,
  `is_admin` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `first_name`, `last_name`, `address`, `city`, `state`, `zipcode`, `country`, `active`, `is_admin`) VALUES
(1, 'jimmysole', '$2y$10$vY5y0VDIJXMUMzgdWr76M.MqDk1NlweVac26wdnroc8bc80OIe8JG', 'jimmysole@gmail.com', 'James', 'Sole', '103 Birchwood Dr', 'North Kingstown', 'Ri', 2852, 'United States', 1, 1);

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
-- Indexes for table `confirmed_sessions`
--
ALTER TABLE `confirmed_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_users`
--
ALTER TABLE `pending_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `confirmed_sessions`
--
ALTER TABLE `confirmed_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pending_users`
--
ALTER TABLE `pending_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `requested_sessions`
--
ALTER TABLE `requested_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
