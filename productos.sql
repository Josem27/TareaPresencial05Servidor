-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 18-04-2024 a las 17:54:36
-- Versión del servidor: 8.2.0
-- Versión de PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bdproductos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `codprod` int NOT NULL,
  `nombre` varchar(15) DEFAULT NULL,
  `categoria` int DEFAULT NULL,
  `pvp` float DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `imagen` varchar(40) DEFAULT NULL,
  `observaciones` text,
  PRIMARY KEY (`codprod`),
  KEY `categoria` (`categoria`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`codprod`, `nombre`, `categoria`, `pvp`, `stock`, `imagen`, `observaciones`) VALUES
(1, 'Manzana', 0, 7.5, 10, 'images/manzana.png', 'Fruta verde. Duradera. 3-5 días'),
(2, 'Queso', 0, 12, 10, 'images/queso.png', 'Producto duradera. 1.5 meses'),
(3, 'CocaCola', 0, 1.5, 20, 'images/cola.png', 'Bebida duradera. Editado'),
(4, 'Guisantes', 0, 0.85, 5, 'images/guisantes.png', 'Legumbre verde. Poco duradera. 1-2 días'),
(0, 'ewae', 1, 1, 1, '1713462858-manzana.png', 'adae');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
