-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 17 Février 2017 à 14:31
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `epidor`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`) VALUES
(1, 'BOULANGERIE', 1),
(6, 'PATISSERIE', 1),
(7, 'MAC EPI', 1),
(8, 'CAMPAGNE', 1),
(9, 'SANDWICH', 1);

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--


CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access` enum('A','SA') NOT NULL DEFAULT 'A',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `firstName`, `lastName`, `username`, `password`, `access`, `status`) VALUES
(19, 'Thierry', 'ATTIE', 'thierry.attie', '01b307acba4f54f55aafc33bb06bbbf6ca803e9a', 'SA', 1),
(24, 'Sebastien', 'VASTEY', 'sebastien.vastey', '01b307acba4f54f55aafc33bb06bbbf6ca803e9a', 'A', 1);

--
-- Structure de la table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `NIF` int(11) NOT NULL,
  `telephone` int(11) NOT NULL,
  `plafond` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `NIF` (`NIF`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `clients`
--

INSERT INTO `clients` (`id`, `firstname`, `lastname`, `NIF`, `telephone`, `plafond`, `status`) VALUES
(10, 'ThÃ©lÃ©marques', 'CAMITHA', 12345678, 34444444, 10000, 0),
(11, 'Bernatiste', 'CIVIL', 21345678, 54444444, 15000, 1),
(12, 'Josette', 'ALCIDE', 12345657, 23333333, 5000, 1),
(13, 'Elvariste', 'JACQUES', 34332334, 54443334, 10000, 1),
(14, 'Marie-Odette', 'CHARLES', 54545654, 57877777, 10000, 1),
(15, 'Irlande', 'COTHIERE', 45455678, 35463738, 10000, 1);

-- --------------------------------------------------------


--
-- Structure de la table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Contenu de la table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category_id`, `status`) VALUES
(9, 'BAGUETTE', 35, 9, 1),
(10, 'BATARD', 30, 9, 1),
(12, 'CAMPAGNE GROS', 100, 8, 1),
(13, 'CAMPAGNE PETIT', 75, 8, 1),
(14, 'PETIT PAIN L', 20, 8, 1),
(15, 'PAIN BOULE', 200, 8, 1),
(16, 'PAIN BOBOT', 200, 1, 1),
(17, 'PAIN DOLLARS LONG', 150, 1, 1),
(18, 'PAIN FROTTE', 150, 1, 1),
(19, 'HAMBURGER CLEAR', 50, 7, 1),
(20, 'PAIN COMPLET', 75, 1, 1),
(21, 'HAMBURGER BIG', 55, 7, 0),
(22, 'PAIN MIE COMPLET', 70, 8, 1),
(23, 'HAMBURGER CLEAR COMPLET', 60, 7, 1),
(24, 'MULTI CEREALE', 100, 9, 1),
(25, 'LAKAY JAUNE', 75, 1, 1),
(26, 'CUBANO', 22, 9, 1);

-- --------------------------------------------------------



CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `date` timestamp NOT NULL,
  `transaction_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `utilisateur_id` (`utilisateur_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Contenu de la table `transactions`
--

INSERT INTO `transactions` (`id`, `client_id`, `utilisateur_id`, `date`, `transaction_id`) VALUES
(42, 12, 19, '2017-02-16 12:47:43', 853846774),
(43, 12, 19, '2017-02-16 12:49:17', 186610462),
(44, 12, 19, '2017-02-16 12:54:33', 520634314),
(45, 12, 19, '2017-02-16 13:01:02', 140043391),
(46, 12, 19, '2017-02-16 13:01:47', 384450881);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--
--
-- Structure de la table `credit`
--

CREATE TABLE IF NOT EXISTS `credit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Contenu de la table `credit`
--

INSERT INTO `credit` (`id`, `transaction_id`, `product_id`, `quantity`, `price`) VALUES
(9, 42, 23, 1, 60),
(10, 42, 13, 1, 75),
(11, 42, 15, 18, 200),
(12, 43, 17, 1, 150),
(13, 43, 20, 1, 75),
(14, 43, 16, 1, 200),
(15, 43, 19, 1, 50),
(16, 44, 14, 1, 20),
(17, 44, 15, 1, 200),
(18, 44, 10, 1, 30);

-- --------------------------------------------------------

--
-- Structure de la table `debit`
--

CREATE TABLE IF NOT EXISTS `debit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(10) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` float NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_id_2` (`transaction_id`),
  KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `debit`
--

INSERT INTO `debit` (`id`, `transaction_id`, `description`, `amount`) VALUES
(2, 45, 'PremiÃ¨re transaction de Josette', 10),
(3, 46, 'DeuxiÃ¨me transaction de Josette', 25);

-- --------------------------------------------------------



--
-- Doublure de structure pour la vue `synthese`
--
CREATE TABLE IF NOT EXISTS `synthese` (
`id` int(11)
,`client_id` int(11)
,`utilisateur_id` int(11)
,`date` timestamp
,`transaction_id` int(10)
,`debit` float
,`description` varchar(255)
,`total` decimal(42,0)
,`comission` decimal(48,4)
,`client_firstname` varchar(255)
,`client_lastname` varchar(255)
,`user_firstname` varchar(255)
,`user_lastname` varchar(255)
);
-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Structure de la vue `synthese`
--
DROP TABLE IF EXISTS `synthese`;

CREATE VIEW `synthese` AS (select `t`.`id` AS `id`,`t`.`client_id` AS `client_id`,`t`.`utilisateur_id` AS `utilisateur_id`,`t`.`date` AS `date`,`t`.`transaction_id` AS `transaction_id`,`d`.`amount` AS `debit`,`d`.`description` AS `description`,sum((`c`.`price` * `c`.`quantity`)) AS `total`,((sum((`c`.`price` * `c`.`quantity`)) * 15) / 100) AS `comission`,`cl`.`firstname` AS `client_firstname`,`cl`.`lastname` AS `client_lastname`,`u`.`firstName` AS `user_firstname`,`u`.`lastName` AS `user_lastname` from ((((`transactions` `t` left join `debit` `d` on((`d`.`transaction_id` = `t`.`id`))) left join `credit` `c` on((`c`.`transaction_id` = `t`.`id`))) left join `utilisateurs` `u` on((`u`.`id` = `t`.`utilisateur_id`))) left join `clients` `cl` on((`cl`.`id` = `t`.`client_id`))) group by `c`.`transaction_id`,`d`.`transaction_id`);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `credit`
--
ALTER TABLE `credit`
  ADD CONSTRAINT `credit_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `debit`
--
ALTER TABLE `debit`
  ADD CONSTRAINT `debit_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
