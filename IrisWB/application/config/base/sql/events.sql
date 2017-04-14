-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 02 Août 2016 à 10:35
-- Version du serveur: 10.1.16-MariaDB-1~trusty
-- Version de PHP: 5.5.9-1ubuntu4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `wb_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Description` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Moment` datetime DEFAULT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`,`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Contenu de la table `events`
--

INSERT INTO `events` (`id`, `Description`, `Moment`, `invoice_id`, `product_id`) VALUES
(1, 'Order to wholesaler', '2011-12-27 12:00:00', 1, 1),
(2, 'Shipment', '2012-01-05 08:00:00', 1, 1),
(3, 'Shipment', '2012-01-05 07:30:00', 1, 2),
(4, 'Shipment', '2012-01-05 08:00:00', 1, 3),
(5, 'Shipment', '2012-01-05 09:00:00', 2, 2),
(6, 'Shipment', '2012-01-05 09:05:00', 3, 3),
(7, 'Shipment', '2012-01-05 09:10:00', 3, 2),
(8, 'Shipment', '2012-02-13 11:00:00', 4, 1),
(9, 'Shipment', '2012-02-13 11:00:00', 4, 2),
(10, 'Order to wholesaler', '2012-01-18 13:00:00', 5, 1),
(11, 'Shipment', '2012-02-21 14:00:00', 5, 1),
(12, 'Shipment', '2012-02-21 15:00:00', 5, 3),
(13, 'Shipment', '2012-03-04 23:00:00', 6, 3);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`invoice_id`, `product_id`) REFERENCES `orders` (`invoice_id`, `product_id`);

