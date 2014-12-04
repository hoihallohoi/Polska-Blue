-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 18 nov 2014 om 17:39
-- Serverversie: 5.6.13
-- PHP-versie: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `product`
--
CREATE DATABASE IF NOT EXISTS `product` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `product`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `Productnummer` int(20) NOT NULL AUTO_INCREMENT,
  `Naam` varchar(25) COLLATE utf8_swedish_ci NOT NULL,
  `Categorie` varchar(25) COLLATE utf8_swedish_ci NOT NULL,
  `Omschrijving` text COLLATE utf8_swedish_ci NOT NULL,
  `Afmetingen` varchar(20) COLLATE utf8_swedish_ci NOT NULL,
  `Prijs` varchar(30) COLLATE utf8_swedish_ci NOT NULL,
  `Afbeelding` blob NOT NULL,
  PRIMARY KEY (`Productnummer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
