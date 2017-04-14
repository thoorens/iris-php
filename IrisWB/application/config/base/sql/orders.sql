-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 02 Août 2016 à 10:47
-- Version du serveur: 10.1.16-MariaDB-1~trusty
-- Version de PHP: 5.5.9-1ubuntu4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `wb_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `UnitPrice` float DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`invoice_id`,`product_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `orders`
--
INSERT INTO `orders` (`invoice_id`, `product_id`, `UnitPrice`, `Quantity`) VALUES
(1, 1, 0.5, 1),
(1, 2, 0.6, 1),
(1, 3, 0.3, 2),
(2, 2, 0.6, 1),
(3, 2, 0.6, 2),
(3, 3, 0.3, 1),
(4, 1, 0.5, 3),
(4, 2, 0.6, 1),
(5, 1, 0.5, 5),
(5, 3, 0.3, 2),
(6, 3, 0.3, 1);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

