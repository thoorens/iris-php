-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 02 Août 2016 à 10:45
-- Version du serveur: 10.1.16-MariaDB-1~trusty
-- Version de PHP: 5.5.9-1ubuntu4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `wb_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `invoices`
--

CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `InvoiceDate` date DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `Amount` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Contenu de la table `invoices`
--

INSERT INTO `invoices` (`id`, `InvoiceDate`, `customer_id`, `Amount`) VALUES
(1, '2012-01-05', 1, NULL),
(2, '2012-01-05', 2, NULL),
(3, '2012-01-05', 3, NULL),
(4, '2012-02-13', 1, NULL),
(5, '2012-02-21', 1, NULL),
(6, '2012-03-05', 3, NULL);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

