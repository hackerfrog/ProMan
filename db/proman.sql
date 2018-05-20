-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2018 at 04:54 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `proman`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `detail` varchar(512) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `performed_by` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`id`, `project_id`, `detail`, `created_on`, `performed_by`) VALUES
(1, 1, 'Project created by <a id="user" href="profile.php?id=1"><i id="1"></i>Alfa Test Account</a>', '2018-05-19 16:55:19', 1),
(2, 1, '<b>ToDo</b> board created by <a id="user" href="profile.php?id=1"><i id="1"></i>Alfa Test Account</a>', '2018-05-19 16:56:03', 1),
(3, 1, '<b>Backlog</b> board created by <a id="user" href="profile.php?id=1"><i id="1"></i>Alfa Test Account</a>', '2018-05-19 17:08:32', 1),
(4, 1, '<b>Progress</b> board created by <a id="user" href="profile.php?id=1"><i id="1"></i>Alfa Test Account</a>', '2018-05-19 17:22:54', 1),
(5, 1, '<b>Done</b> board created by <a id="user" href="profile.php?id=1"><i id="1"></i>Alfa Test Account</a>', '2018-05-19 17:32:26', 1),
(6, 1, '<b>Done</b> board created by <a id="user" href="profile.php?id=1"><i id="1"></i>Alfa Test Account</a>', '2018-05-19 17:32:26', 1),
(7, 1, 'Card <b>#1</b> created under <b></b> by <a id="user" href="profile.php?id=1"><i id="1"></i>Alfa Test Account</a>', '2018-05-20 01:26:04', 1),
(8, 1, 'Card <b>#2</b> created under <b>ToDo</b> by <a id="user" href="profile.php?id=1"><i id="1"></i>Alfa Test Account</a>', '2018-05-20 01:27:50', 1),
(9, 1, 'Card <b>#3</b> created under <b>Backlog</b> by <a id="user" href="profile.php?id=1"><i id="1"></i>Alfa Test Account</a>', '2018-05-20 01:28:19', 1),
(10, 1, 'Card <b>#4</b> created under <b>Backlog</b> by <a id="user" href="profile.php?id=1"><i id="1"></i>Alfa Test Account</a>', '2018-05-20 01:28:19', 1),
(11, 1, 'Card <b>#5</b> created under <b>Progress</b> by <a id="user" href="profile.php?id=1"><i id="1"></i>Alfa Test Account</a>', '2018-05-20 01:31:56', 1),
(12, 1, '<b>Progress</b> board created by <a id="user" href="profile.php?id=1"><i id="1"></i>Alfa Test Account</a>', '2018-05-20 01:48:15', 1),
(13, 1, 'Card <b>#6</b> created under <b>Done</b> by <a id="user" href="profile.php?id=1"><i id="1"></i>Alfa Test Account</a>', '2018-05-20 01:53:15', 1),
(14, 1, 'Card <b>#7</b> created under <b>Progress</b> by <a id="user" href="profile.php?id=1"><i id="1"></i>Alfa Test Account</a>', '2018-05-20 01:59:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `boards`
--

CREATE TABLE IF NOT EXISTS `boards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `project_id` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `boards`
--

INSERT INTO `boards` (`id`, `name`, `project_id`, `created_on`) VALUES
(1, 'ToDo', 1, '2018-05-19 16:56:02'),
(2, 'Backlog', 1, '2018-05-19 17:08:31'),
(3, 'Progress', 1, '2018-05-19 17:22:53'),
(4, 'Done', 1, '2018-05-19 17:32:26'),
(6, 'Progress', 1, '2018-05-20 01:48:14');

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE IF NOT EXISTS `cards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(512) NOT NULL,
  `body` text NOT NULL,
  `board_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(32) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `title`, `body`, `board_id`, `project_id`, `created_by`, `created_on`, `status`) VALUES
(1, 'Test Card', 'Card', 1, 1, 1, '2018-05-20 01:26:04', 'active'),
(2, 'Card 2', 'LOL', 1, 1, 1, '2018-05-20 01:27:50', 'active'),
(3, 'Card 3', 'third card', 2, 1, 1, '2018-05-20 01:28:18', 'active'),
(4, 'Card 3', 'third card', 2, 1, 1, '2018-05-20 01:28:19', 'active'),
(5, 'Show cards on Board', 'Hello', 3, 1, 1, '2018-05-20 01:31:56', 'active'),
(6, 'Boards', '', 4, 1, 1, '2018-05-20 01:53:15', 'active'),
(7, 'This Long Long Title of a card lets see what happen to it.', '', 3, 1, 1, '2018-05-20 01:59:18', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `detail` varchar(512) NOT NULL,
  `own` int(10) unsigned NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(8) NOT NULL DEFAULT 'pub',
  `team` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `detail`, `own`, `created_on`, `type`, `team`) VALUES
(1, 'Test Project', 'Test', 1, '2018-05-19 16:55:19', 'pub', '["1"]');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `fullname` varchar(128) NOT NULL,
  `doj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `hash` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `password`, `email`, `fullname`, `doj`, `status`, `hash`) VALUES
(1, 'pinpin', 'alfa@testrun.com', 'Alfa Test Account', '2018-05-08 17:23:32', 1, '6d7030606a532c46de3356b64e2a6823'),
(2, 'pinpin', 'beta@testrun.com', 'Beta Test Account', '2018-05-09 17:45:43', 1, 'd6e17297d00d8c71c8997599e94bb7aa');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
