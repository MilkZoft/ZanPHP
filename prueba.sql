-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 29-07-2011 a las 04:46:42
-- Versión del servidor: 5.1.44
-- Versión de PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `prueba`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zan_contacts`
--

CREATE TABLE IF NOT EXISTS `zan_contacts` (
  `ID_Contact` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Phone` varchar(15) NOT NULL,
  PRIMARY KEY (`ID_Contact`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `zan_contacts`
--

INSERT INTO `zan_contacts` (`ID_Contact`, `Name`, `Email`, `Phone`) VALUES
(1, 'Carlos Santana', 'carlos@milkzoft.com', '3121444501'),
(2, 'Carlos Hugo González Castell', 'carlos.hugo@milkzoft.com', '3121338379');
