-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 04, 2018 at 10:42 AM
-- Server version: 5.7.21
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `supfile`
--

-- --------------------------------------------------------

--
-- Table structure for table `connected`
--

DROP TABLE IF EXISTS `connected`;
CREATE TABLE IF NOT EXISTS `connected` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `connected` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `connected`
--

INSERT INTO `connected` (`id`, `id_user`, `connected`) VALUES
(16, 20, 1),
(15, 19, 1),
(14, 18, 1),
(17, 21, 1),
(18, 22, 1),
(19, 23, 1),
(20, 24, 1),
(21, 26, 1),
(22, 27, 1),
(23, 25, 1),
(24, 28, 1),
(25, 29, 1),
(26, 30, 1),
(27, 31, 1),
(28, 32, 1),
(29, 33, 1),
(30, 34, 1),
(31, 35, 1),
(32, 36, 1),
(33, 37, 1),
(34, 38, 1),
(35, 39, 1),
(36, 40, 1),
(37, 41, 1),
(38, 42, 1),
(39, 43, 1),
(40, 44, 1),
(41, 45, 1),
(42, 46, 1),
(43, 47, 1),
(44, 48, 1);

-- --------------------------------------------------------

--
-- Table structure for table `datafile`
--

DROP TABLE IF EXISTS `datafile`;
CREATE TABLE IF NOT EXISTS `datafile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_folder` int(11) NOT NULL,
  `name` text NOT NULL,
  `link` text NOT NULL,
  `code` text NOT NULL,
  `ext` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `datafile`
--

INSERT INTO `datafile` (`id`, `id_folder`, `name`, `link`, `code`, `ext`) VALUES
(51, 124, 'WIN_20180530_10_54_39_Pro', 'http://localhost/supFile/application/dataClients/47/files/ggkgg8c0sk8s0w0ccs0wgwg48wk8owowco0sww4k.jpg', 'ggkgg8c0sk8s0w0ccs0wgwg48wk8owowco0sww4k', 'jpg');

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

DROP TABLE IF EXISTS `folders`;
CREATE TABLE IF NOT EXISTS `folders` (
  `id_folder` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `name` text NOT NULL,
  `path` text NOT NULL,
  `locate` int(11) NOT NULL,
  PRIMARY KEY (`id_folder`)
) ENGINE=MyISAM AUTO_INCREMENT=138 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`id_folder`, `id_user`, `name`, `path`, `locate`) VALUES
(137, 47, 'titi', '8cs8w8s44sk88wks0wckswgc88c48gcc4sg0woos', 136),
(136, 47, 'maman', 's4kogowo0g4o8844w0k80cwkskgo8oc0osos8s88', 124),
(135, 48, 'mama', '0cg4s8s44sgksws0so8ww004cws88o48sw40kgs8', 133),
(133, 48, 'tata', 'k8k88ccww4s8gcg0kgkscw0o4kks8o8swwog400w', 131),
(134, 48, 'fati', 'sk0wo888kwgk8w8o8cs8kgk48w8cos4gsc0o80kw', 133),
(131, 48, 'home', 'home', 0),
(132, 48, 'sofiane', 'k8swso880oswg0c8gc8cc4c40k4480gwkswskcc8', 131),
(130, 47, 'yassine', '4ckoocogsg80co8w0gosgck8kkccccgkwwcwkswc', 129),
(129, 47, 'titi', 'w4sw8kosksgsoo444c84g4c88w08csss0s04s8g8', 126),
(128, 47, 'tata', 'oosss0cg0g8s8k40ckookcwckk48w088cc4cgwgc', 126),
(126, 47, 'tata', 'cwk04k0s04ckk08skg80w8sokkkco8wcw4g4swk4', 124),
(127, 47, 'salut', 'cs84wok8ss0ksw4c08ck84wsss8wgwgccgo0kwc4', 124),
(124, 47, 'home', 'home', 0);

-- --------------------------------------------------------

--
-- Table structure for table `keys`
--

DROP TABLE IF EXISTS `keys`;
CREATE TABLE IF NOT EXISTS `keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
  `ip_addresses` text,
  `date_created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `keys`
--

INSERT INTO `keys` (`id`, `id_user`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES
(42, 47, 'w0wg0o08sswwc8g4g84gcksgk48ggc88g84co840', 1, 1, 0, NULL, 1527934394),
(43, 48, 's4wck0o4g8okks88okkw4gswgko0csgo0kg0go04', 1, 1, 0, NULL, 1527935574);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(70) NOT NULL,
  `lastname` varchar(70) NOT NULL,
  `mail` text NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `firstname`, `lastname`, `mail`, `password`) VALUES
(47, 'Yassine', 'Zitouni', '224096@supinfo.com', '07123e1f482356c415f684407a3b8723e10b2cbbc0b8fcd6282c49d37c9c1abc'),
(48, 'Sofiane', 'Zitouni', 'soso@soso.com', '07123e1f482356c415f684407a3b8723e10b2cbbc0b8fcd6282c49d37c9c1abc');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
