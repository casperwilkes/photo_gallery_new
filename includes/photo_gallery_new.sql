-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 03, 2015 at 01:08 PM
-- Server version: 5.5.46-0ubuntu0.14.04.2
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `photo_gallery_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `photograph_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `body` text NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `photograph_id` (`photograph_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `photograph_id`, `user_id`, `body`, `created_at`, `updated_at`) VALUES
(2, 9, 12, 'Very pretty', 1448770288, NULL),
(4, 8, 11, 'I''ve seen em before\r\nsomewhere\r\ni think', 1448904004, NULL),
(6, 8, 12, 'I''m pretty sure i have seen them though', 1449023050, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `type` varchar(25) NOT NULL,
  `name` varchar(50) NOT NULL,
  `migration` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`type`, `name`, `migration`) VALUES
('app', 'default', '001_create_comments'),
('app', 'default', '002_create_photographs'),
('app', 'default', '003_create_users'),
('app', 'default', '004_rename_field_pforile_fields_to_profile_fields_in_users'),
('app', 'default', '005_alter_field_username_in_users'),
('app', 'default', '006_rename_field_author_to_user_id_in_comments');

-- --------------------------------------------------------

--
-- Table structure for table `photographs`
--

CREATE TABLE IF NOT EXISTS `photographs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `photographs`
--

INSERT INTO `photographs` (`id`, `filename`, `type`, `size`, `caption`, `created_at`, `updated_at`) VALUES
(8, 'fb4183d839.jpg', 'image/jpeg', 455568, 'Bamboo', 1448751749, 1449110123),
(9, '1acc39bb1f.jpg', 'image/jpeg', 664947, 'Flowers', 1448751762, 1448751762),
(10, '149b691039.jpg', 'image/jpeg', 524574, 'Thatched Roof', 1448751775, 1448751775),
(11, '0931a1b470.jpg', 'image/jpeg', 607118, 'Jar wall', 1448778444, 1448778444),
(12, '82a3b1b9b4.jpg', 'image/jpeg', 564389, 'Wood Stack', 1448778459, 1448778459);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `group` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `last_login` varchar(255) NOT NULL,
  `login_hash` varchar(255) NOT NULL,
  `profile_fields` varchar(255) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `group`, `email`, `last_login`, `login_hash`, `profile_fields`, `created_at`, `updated_at`) VALUES
(6, 'casper', 'AwS5Hlmv1nq0GgARdx9YAFdS8DpcqwHm1Alep1HTA3A=', 1, 'casper@photogallery.com', '1449117227', 'ae9d63d114a665e38079dbe67dd2b150e3a639d2', 'a:2:{s:3:"bio";s:0:"";s:5:"image";s:9:"noimg.jpg";}', 1421204677, 1449117188),
(11, 'dave_thomas', '3BrQctpr2qmj/i2Vo08WIwSyyGDZADpEVeq4IFqfDYo=', 2, 'dave_thomas@wendys.com', '1449117108', '39944e771d24010c773d2e5ebf104151ee3a18e6', 'a:2:{s:3:"bio";s:14:"I smile a lot.";s:5:"image";s:9:"noimg.jpg";}', 1448416082, 1449117134),
(12, 'wendy', '3BrQctpr2qmj/i2Vo08WIwSyyGDZADpEVeq4IFqfDYo=', 2, 'wendy@wendys.com', '1449117043', 'b558d75a56be1c954488927478963a26d2d7de15', 'a:2:{s:3:"bio";s:18:"Try the Baconator.";s:5:"image";s:14:"400b63e202.jpg";}', 1448751419, 1449117079),
(14, 'ronald', '3BrQctpr2qmj/i2Vo08WIwSyyGDZADpEVeq4IFqfDYo=', 0, 'ronald@mcdonalds.com', '1449116929', '95bd9432b1d6ed0f93006e51655671ddddb4b463', 'a:2:{s:3:"bio";s:0:"";s:5:"image";s:9:"noimg.jpg";}', 1449116929, 1449117017),
(15, 'admin', '3BrQctpr2qmj/i2Vo08WIwSyyGDZADpEVeq4IFqfDYo=', 1, 'admin@photogallery.com', '1449117260', '59527681b16f62eb386591f74275a409f3a758df', 'a:2:{s:3:"bio";s:0:"";s:5:"image";s:9:"noimg.jpg";}', 1449117215, 1449117248);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`photograph_id`) REFERENCES `photographs` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
