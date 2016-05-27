-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: 172.17.0.2:3306
-- Generation Time: May 22, 2016 at 02:49 PM
-- Server version: 5.5.49
-- PHP Version: 5.6.9-1+deb.sury.org~trusty+2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `camagru`
--

-- --------------------------------------------------------

--
-- Table structure for table `ca_comment`
--

CREATE TABLE IF NOT EXISTS `ca_comment` (
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `content` text NOT NULL,
  `date_comment` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `id_post` (`id_post`,`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ca_forgetPass`
--

CREATE TABLE IF NOT EXISTS `ca_forgetPass` (
  `id _forgetPass` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `date_mail` date NOT NULL,
  `token` varchar(255) NOT NULL,
  PRIMARY KEY (`id _forgetPass`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ca_like`
--

CREATE TABLE IF NOT EXISTS `ca_like` (
  `id_image` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date_like` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_image`,`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ca_membres`
--

CREATE TABLE IF NOT EXISTS `ca_membres` (
  `id_membre` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` text NOT NULL,
  `date_inscrip` date NOT NULL,
  `validate` int(11) NOT NULL,
  `token` varchar(30) NOT NULL,
  PRIMARY KEY (`id_membre`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ca_overPictures`
--

CREATE TABLE IF NOT EXISTS `ca_overPictures` (
  `id_overPicture` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id_overPicture`),
  UNIQUE KEY `file_name` (`file_name`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ca_pictures`
--

CREATE TABLE IF NOT EXISTS `ca_pictures` (
  `id_picture` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `file_name` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  `name_picture` varchar(255) NOT NULL,
  `delete` int(11) NOT NULL,
  PRIMARY KEY (`id_picture`),
  UNIQUE KEY `file_name` (`file_name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
