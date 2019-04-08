-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  lun. 25 fév. 2019 à 18:41
-- Version du serveur :  5.7.11
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `prwb_1819_pg02`
--

-- --------------------------------------------------------
DROP DATABASE IF EXISTS `prwb_1819_pg02`;
CREATE DATABASE IF NOT EXISTS `prwb_1819_pg02` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `prwb_1819_pg02`;
--
-- Structure de la table `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `isbn` char(13) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `editor` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `nbCopies` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `book`
--

INSERT INTO `book` (`id`, `isbn`, `title`, `author`, `editor`, `picture`, `nbCopies`) VALUES
(1, '1125447785453', 'Harrys Potter à l Ecole des Sorciers', 'JK Rowlings', 'Pocket', "Harrys Potter a l'Ecole des Sorciers.jpg", 20),
(2, '4585214569858', 'Mobyz Dick', 'Jules Verges', 'Pocket', 'Mobyz Dick.jpg', 20),
(3, '4587456321454', 'Les Fleurs du Male', 'Beau De Lair', 'Pockets', 'Les Fleurs du Male.jpg', 20),
(4, '2587413698522', 'Madame Bovary', 'Gustave Flaubert', 'Pocket', 'Madame Bovary.jpg', 20),
(5, '3258741258960', 'Peter Pan', 'Linus Torvalds', 'Pocket', 'Peter Pan.jpg', 20),
(6, '9587465214582', 'A Brief History of Time', 'Stephen Hawking', 'Pocket', NULL, 20),
(7, '3215469875558', '1984', 'George Orwell', 'Pocket', NULL, 20),
(8, '3216549874126', 'To Kill a Mockingbird', 'Harper Lee', 'Pocket', 'To Kill a Mockingbird.jpg', 20),
(9, '9999999999994', 'Lord of the Flies', 'William Goldings', 'Pocket', NULL, 20),
(10, '1111111111116', 'La Danse de la Colere', 'Lerner', 'Pocket', 'La Danse de la Colere.jpg', 20),
(11, '2222222222222', 'Why Does He Do That', 'Lundy Bancroft', 'Pocket', NULL, 20),
(12, '3333333333338', 'The Stranger', 'Albert Camus', 'Pocket', 'The Stranger.jpg', 20),
(13, '4444444444444', 'Heart of Darkness', 'Joseph Conrad', 'Pocket', NULL, 20),
(14, '5555555555550', 'Men Without Women', 'Ernest Hemingway', 'Pocket', NULL, 20),
(15, '5214569875213', 'Women Without men', 'Ernest Hemingway', 'Pocket', NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `rental`
--

CREATE TABLE `rental` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `book` int(11) NOT NULL,
  `rentaldate` datetime DEFAULT NULL,
  `returndate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(64) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `role` enum('admin','manager','member') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `fullname`, `email`, `birthdate`, `role`) VALUES
(1, 'Admin', '903001ff9a17773d4a0b4cff3666f1e9', 'Chaffi', 'admin@epfc.com', NULL, 'admin'),
(2, 'Manager', '903001ff9a17773d4a0b4cff3666f1e9', 'Spyridon', 'manager@epfc.com', NULL, 'manager'),
(3, 'Member', '903001ff9a17773d4a0b4cff3666f1e9', 'Virginie', 'member@epfc.com', NULL, 'member'),
(4, 'Boris', '903001ff9a17773d4a0b4cff3666f1e9', 'Boris V.', 'boris@epfc.com', NULL, 'admin'),
(5, 'Bru', '903001ff9a17773d4a0b4cff3666f1e9', 'Bruno L.', 'bruno@epfc.com', NULL, 'manager'),
(6, 'Ben', '903001ff9a17773d4a0b4cff3666f1e9', 'Benoit P.', 'ben@epfc.com', NULL, 'member'),
(7, 'Yas', '903001ff9a17773d4a0b4cff3666f1e9', 'Yasmina L.', 'yas@epfc.com', NULL, 'member'),
(8, 'Rod', '903001ff9a17773d4a0b4cff3666f1e9', 'Rodolphe M.', 'rod@epfc.com', NULL, 'member');

--
-- Index pour les tables déchargées
--
INSERT INTO `rental` (`id`, `user`, `book`, `rentaldate`, `returndate`) VALUES
(1, 1, 1, '2019-03-01 19:06:50', NULL),
(2, 1, 2, '2019-03-28 19:06:50', NULL),
(3, 1, 4, '2019-04-07 19:06:50', NULL),
(4, 1, 14, '2019-03-25 19:06:50', NULL),
(5, 2, 1, '2019-02-03 19:07:47', NULL),
(6, 2, 4, '2019-04-01 19:07:47', NULL),
(7, 2, 10, '2019-04-07 19:07:47', NULL),
(8, 2, 6, '2019-02-28 19:07:47', NULL),
(9, 2, 7, '2019-03-19 19:07:47', NULL),
(10, 3, 1, '2019-04-07 19:08:11', NULL),
(11, 3, 2, '2019-04-07 19:08:11', NULL),
(12, 3, 3, '2019-04-07 19:08:11', NULL),
(13, 3, 4, '2019-04-07 19:08:11', NULL),
(14, 3, 5, '2019-04-07 19:08:11', NULL),
(15, 4, 9, '2019-02-28 19:08:39', NULL),
(16, 4, 14, '2019-04-07 19:08:39', NULL),
(17, 4, 12, '2019-03-27 19:08:39', NULL),
(18, 4, 7, '2019-04-07 19:08:39', NULL),
(19, 4, 6, '2019-04-07 19:08:39', NULL),
(20, 5, 3, '2019-04-01 19:09:16', NULL),
(21, 5, 5, '2019-04-07 19:09:16', NULL),
(22, 5, 6, '2019-01-16 19:09:16', NULL),
(23, 5, 13, '2019-03-07 19:09:16', NULL),
(24, 5, 11, '2019-03-07 19:09:16', NULL),
(25, 6, 14, '2019-04-07 19:09:52', NULL),
(26, 6, 8, '2019-04-07 19:09:52', NULL),
(27, 6, 2, '2019-04-07 19:09:52', NULL),
(28, 6, 9, '2019-04-07 19:09:52', NULL),
(29, 6, 12, '2019-04-07 19:09:52', NULL);

--
-- Index pour la table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn_UNIQUE` (`isbn`);

--
-- Index pour la table `rental`
--
ALTER TABLE `rental`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rentalitem_book1_idx` (`book`),
  ADD KEY `fk_rentalitem_user1_idx` (`user`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_unique` (`username`) USING BTREE,
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `rental`
--
ALTER TABLE `rental`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `rental`
--
ALTER TABLE `rental`
  ADD CONSTRAINT `fk_rentalitem_book` FOREIGN KEY (`book`) REFERENCES `book` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rentalitem_user1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
