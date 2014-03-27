-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 27, 2014 at 10:30 AM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `asdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `bit_cdr`
--

DROP TABLE IF EXISTS `bit_cdr`;
CREATE TABLE IF NOT EXISTS `bit_cdr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cdr`
--

DROP TABLE IF EXISTS `cdr`;
CREATE TABLE IF NOT EXISTS `cdr` (
  `calldate` date NOT NULL DEFAULT '0000-00-00',
  `clid` int(11) NOT NULL AUTO_INCREMENT,
  `src` varchar(80) NOT NULL DEFAULT '',
  `dst` varchar(80) NOT NULL DEFAULT '',
  `dcontext` varchar(80) NOT NULL DEFAULT '',
  `channel` varchar(80) NOT NULL DEFAULT '',
  `dstchannel` varchar(80) NOT NULL DEFAULT '',
  `lastapp` varchar(80) NOT NULL DEFAULT '',
  `lastdata` varchar(80) NOT NULL DEFAULT '',
  `duration` int(11) NOT NULL DEFAULT '0',
  `billsec` int(11) NOT NULL DEFAULT '0',
  `disposition` varchar(45) NOT NULL DEFAULT '',
  `amaflags` int(11) NOT NULL DEFAULT '0',
  `accountcode` varchar(20) NOT NULL DEFAULT '',
  `userfield` varchar(255) NOT NULL DEFAULT '',
  `uniqueid` varchar(32) NOT NULL DEFAULT '',
  `linkedid` varchar(32) NOT NULL DEFAULT '',
  `sequence` varchar(32) NOT NULL DEFAULT '',
  `peeraccount` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`clid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cdr`
--

INSERT INTO `cdr` (`calldate`, `clid`, `src`, `dst`, `dcontext`, `channel`, `dstchannel`, `lastapp`, `lastdata`, `duration`, `billsec`, `disposition`, `amaflags`, `accountcode`, `userfield`, `uniqueid`, `linkedid`, `sequence`, `peeraccount`) VALUES
('2014-03-23', 1, 'http://google.com', '12', '21322', '12', '121', '12', '123', 0, 0, '', 0, '12', '12', '1', '4', '4', '5'),
('2014-03-19', 2, 'http://google.com', '12', '21322', '12', '121', '12', '123', 12, 1, 'dasdsa', 12, '12', '12', '1', '4', '4', '5'),
('2014-03-29', 3, 'http://google.com', '12', '21322', '12', '121', '12', '123', 0, 0, 'dasdsa', 0, '12', '12', '1', '4', '4', '5');

-- --------------------------------------------------------

--
-- Table structure for table `ci_cookies`
--

DROP TABLE IF EXISTS `ci_cookies`;
CREATE TABLE IF NOT EXISTS `ci_cookies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cookie_id` varchar(255) DEFAULT NULL,
  `netid` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `orig_page_requested` varchar(120) DEFAULT NULL,
  `php_session_id` varchar(40) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('7c7ba08ac1e5d9fdcf5351c85b1d48db', '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36', 1395818137, 'a:3:{s:9:"user_data";s:0:"";s:9:"user_name";s:5:"admin";s:12:"is_logged_in";b:1;}'),
('ea7707d47b40514fc42eec27e3916498', '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0', 1395738376, 'a:3:{s:9:"user_data";s:0:"";s:9:"user_name";s:5:"admin";s:12:"is_logged_in";b:1;}'),
('ccb79d5bbe5a966d51a3aefdecdee211', '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36', 1395745110, 'a:3:{s:9:"user_data";s:0:"";s:9:"user_name";s:5:"admin";s:12:"is_logged_in";b:1;}'),
('13b531f92ac7fc4aa5f3f3327f6f79e3', '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36', 1395797465, 'a:3:{s:9:"user_data";s:0:"";s:9:"user_name";s:5:"admin";s:12:"is_logged_in";b:1;}'),
('48c3d718ff9a1513a17ff979438548b6', '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36', 1395739209, 'a:3:{s:9:"user_data";s:0:"";s:9:"user_name";s:5:"admin";s:12:"is_logged_in";b:1;}'),
('73a9dc6196f225fb3becb57cbe012d46', '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36', 1395743768, 'a:3:{s:9:"user_data";s:0:"";s:9:"user_name";s:5:"admin";s:12:"is_logged_in";b:1;}'),
('6cef4dc790ee4a3d2284887387938054', '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0', 1395743163, 'a:3:{s:9:"user_data";s:0:"";s:9:"user_name";s:5:"admin";s:12:"is_logged_in";b:1;}');

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
CREATE TABLE IF NOT EXISTS `leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phonenumber` varchar(50) NOT NULL,
  `datecreate` date NOT NULL,
  `lastupdate` date NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=297 ;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `name`, `email`, `phonenumber`, `datecreate`, `lastupdate`, `is_active`) VALUES
(247, 'viendasd', 'albgdfgo@gmail.com', '1000000', '1992-12-01', '1992-12-26', 0),
(248, 'vidasdaen', 'albgffgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(250, 'djsak', 'albgfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(251, 'viden', 'albagfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(252, 'vidasden', 'albgdfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(253, 'vidasdaen', 'albgffgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(254, 'vidasdasen', 'albagfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(257, 'vidasden', 'albgdfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(258, 'vidasadsdaen', 'albgffgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(260, 'djsak', 'albgfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(261, 'viden', 'albagfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(262, 'vidasden', 'albgdfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(263, 'vidsadasdaen', 'albgffgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(264, 'vidasdasen', 'albagfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(265, 'djsadsak', 'albgfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(266, 'viden', 'albagfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(267, 'vidsadasden', 'albgdfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(268, 'vidasdaen', 'albgffgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 0),
(269, 'vidsadasdasen', 'albagfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 0),
(272, 'kdasjk', 'vienpn@dsad.com', '09121290192', '2014-03-11', '2014-03-29', 1),
(273, 'djsak', 'albgfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(274, 'viden', 'albagfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(275, 'vidasden', 'albgdfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(276, 'vidasdaen', 'albgffgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(277, 'vidasdasen', 'albagfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(278, 'djsak', 'albgfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(279, 'viddasen', 'albagfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(280, 'vidasden', 'albgdfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(281, 'vidasadsdaen', 'albgffgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(282, 'vidasdasen', 'albagfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(283, 'djsak', 'albgfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(284, 'viden', 'albagfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(285, 'vidasden', 'albgdfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(286, 'vidsadasdaen', 'albgffgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(287, 'vidasdasen', 'albagfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(288, 'djsadsak', 'albgfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(289, 'viden', 'albagfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(290, 'vidsadasden', 'albgdfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(291, 'vidasdaen', 'albgffgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(292, 'vidsadasdasen', 'albagfgo@gmail.com', '1212213', '1992-12-01', '1992-12-01', 1),
(293, 'Johnny', 'vienpn@dsad.com', '13899289382', '2014-03-13', '2014-03-29', 1),
(294, '12', 'vienpn@dsad.com', '213213901293', '2014-03-22', '2014-03-29', 0),
(295, 'kdasjk', 'vienpn@dsad.com', '09121290192', '2014-03-07', '2014-03-29', 1),
(296, 'Johnny', 'vienpn@dsad.com', '11111111111', '2014-03-07', '2014-03-29', 0);

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

DROP TABLE IF EXISTS `manufacturers`;
CREATE TABLE IF NOT EXISTS `manufacturers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(40) DEFAULT NULL,
  `stock` double DEFAULT NULL,
  `cost_price` double DEFAULT NULL,
  `sell_price` double DEFAULT NULL,
  `manufacture_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `pass_word` varchar(32) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_actived` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email_address`, `user_name`, `pass_word`, `is_admin`, `is_actived`) VALUES
(1, 'A', 'AA', 'vsaden@adas.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 1),
(3, 'Pham Ngoc Vien', 'Pham', 'vien.phamngoc@gmail.com', 'johpha3', 'f5bb0c8de146c67b44babbf4e6584cc0', 0, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
