-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 16, 2018 at 02:47 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prwb_1819_pc06g02`
--
DROP DATABASE IF EXISTS `prwb_1819_pg02`;
CREATE DATABASE IF NOT EXISTS `prwb_1819_pg02` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `prwb_1819_pg02`;

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` char(13) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `editor` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `isbn_UNIQUE` (`isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
INSERT INTO `book` (`id`, `isbn`, `title`, `author`, `editor`, `picture`) VALUES 
(NULL, '1111111111111', 'Harry Potter à l Ecole des Sorciers', 'JK Rowlings', 'Pocket', NULL), 
(NULL, '2222222222222', 'Mobyz Dick', 'Jules Verge', 'Pocket', NULL), 
(NULL, '3333333333333', 'Les Fleurs du Male', 'Beau De Lair', 'Pocket', NULL), 
(NULL, '4444444444444', 'Madame Bovary', 'Gustave Flaubert', 'Pocket', NULL), 
(NULL, '5555555555555', 'Peter Pan', 'Linus Torvalds', 'Pocket', NULL),
(NULL, '6666666666666', 'A Brief History of Time', 'Stephen Hawking', 'Pocket', NULL), 
(NULL, '7777777777777', '1984', 'George Orwell', 'Pocket', NULL),
(NULL, '8888888888888', 'To Kill a Mockingbird', 'Harper Lee', 'Pocket', NULL),
(NULL, '9999999999999', 'Lord of the Flies', 'William Golding', 'Pocket', NULL),
(NULL, '1111111111110', 'La Danse de la Colere', 'Lerner', 'Pocket', NULL),
(NULL, '2222222222220', 'Why Does He Do That', 'Lundy Bancroft', 'Pocket', NULL),
(NULL, '3333333333330', 'The Stranger', 'Albert Camus', 'Pocket', NULL),
(NULL, '4444444444440', 'Heart of Darkness', 'Joseph Conrad', 'Pocket', NULL),
(NULL, '5555555555550', 'Men Without Women', 'Ernest Hemingway', 'Pocket', NULL);

--
-- Table structure for table `rental`
--

DROP TABLE IF EXISTS `rental`;
CREATE TABLE IF NOT EXISTS `rental` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `book` int(11) NOT NULL,
  `rentaldate` datetime DEFAULT NULL,
  `returndate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_rentalitem_book1_idx` (`book`),
  KEY `fk_rentalitem_user1_idx` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(64) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `role` enum('admin','manager','member') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_unique` (`username`) USING BTREE,
  UNIQUE KEY `email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--
INSERT INTO `user` (`id`, `username`, `password`, `fullname`, `email`, `birthdate`, `role`) VALUES 
(NULL, 'admin', '903001ff9a17773d4a0b4cff3666f1e9', 'chaffi', 'admin@epfc.com', NULL, 'admin'), 
(NULL, 'manager', '903001ff9a17773d4a0b4cff3666f1e9', 'Spyridon', 'manager@epfc.com', NULL, 'manager'), 
(NULL, 'member', '903001ff9a17773d4a0b4cff3666f1e9', 'virginie', 'member@epfc.com', NULL, 'member');
--
-- Constraints for table `rental`
--
ALTER TABLE `rental`
  ADD CONSTRAINT `fk_rentalitem_book` FOREIGN KEY (`book`) REFERENCES `book` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rentalitem_user1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
