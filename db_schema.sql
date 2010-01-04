-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 04, 2010 at 01:12 PM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pluspanda`
--

-- --------------------------------------------------------

--
-- Table structure for table `forum_cats`
--

CREATE TABLE IF NOT EXISTS `forum_cats` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(60) NOT NULL,
  `name` varchar(60) NOT NULL,
  `position` int(4) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forum_cat_posts`
--

CREATE TABLE IF NOT EXISTS `forum_cat_posts` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `forum_cat_id` int(9) unsigned NOT NULL,
  `title` varchar(72) NOT NULL,
  `forum_cat_post_comment_id` int(7) NOT NULL,
  `comment_count` int(7) NOT NULL DEFAULT '0',
  `last_active` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forum_cat_post_comments`
--

CREATE TABLE IF NOT EXISTS `forum_cat_post_comments` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `forum_cat_post_id` int(9) unsigned NOT NULL,
  `owner_id` int(9) unsigned NOT NULL,
  `body` text NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `vote_count` int(9) NOT NULL,
  `is_post` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forum_comment_votes`
--

CREATE TABLE IF NOT EXISTS `forum_comment_votes` (
  `owner_id` int(9) NOT NULL,
  `forum_cat_post_comment_id` int(9) NOT NULL,
  KEY `author_id` (`owner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
