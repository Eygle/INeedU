-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 18, 2016 at 05:31 PM
-- Server version: 5.5.50-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ineedu`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userId` varchar(23) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `userName` varchar(32) COLLATE utf8_bin NOT NULL,
  `password` blob NOT NULL,
  `email` varchar(256) COLLATE utf8_bin NOT NULL,
  `picture` varchar(256) COLLATE utf8_bin NOT NULL,
  `info` varchar(256) COLLATE utf8_bin NOT NULL,
  `key` varchar(53) CHARACTER SET ascii COLLATE ascii_bin NOT NULLX
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;