-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 02 Août 2016 à 10:40
-- Version du serveur: 10.1.16-MariaDB-1~trusty
-- Version de PHP: 5.5.9-1ubuntu4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `wb_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Contenu de la table `customers`
--

INSERT INTO `customers` (`id`, `Name`, `Address`, `Email`) VALUES
(1, 'Jacques Thoorens', 'rue Villette', 'irisphp@thoorens.net'),
(2, 'John Smith', 'Bourbon street', 'john@smith.eu'),
(3, 'Antonio Sanchez', 'Gran Via', NULL);
