-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 06-06-2024 a las 02:08:24
-- Versión del servidor: 8.0.31
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `controlacceso1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acciones`
--

DROP TABLE IF EXISTS `acciones`;
CREATE TABLE IF NOT EXISTS `acciones` (
  `idAcciones` int NOT NULL AUTO_INCREMENT,
  `Accion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idAcciones`),
  UNIQUE KEY `idAcciones_UNIQUE` (`idAcciones`),
  UNIQUE KEY `Accion_UNIQUE` (`Accion`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `acciones`
--

INSERT INTO `acciones` (`idAcciones`, `Accion`) VALUES
(2, 'Asar carnes rojas'),
(3, 'Preparar la carne'),
(4, 'Seleccionar personal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

DROP TABLE IF EXISTS `grupos`;
CREATE TABLE IF NOT EXISTS `grupos` (
  `idGrupos` int NOT NULL AUTO_INCREMENT,
  `Grupo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idGrupos`),
  UNIQUE KEY `idGrupos_UNIQUE` (`idGrupos`),
  UNIQUE KEY `Grupo_UNIQUE` (`Grupo`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`idGrupos`, `Grupo`) VALUES
(6, 'Boucher'),
(7, 'Chef '),
(1, 'Chef de Cuisine'),
(3, 'Cuisinier'),
(4, 'Garde Mangier'),
(5, 'Rotisseur');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

DROP TABLE IF EXISTS `permisos`;
CREATE TABLE IF NOT EXISTS `permisos` (
  `idPermisos` int NOT NULL AUTO_INCREMENT,
  `Grupos_idGrupos` int DEFAULT NULL,
  `Acciones_idAcciones` int DEFAULT NULL,
  PRIMARY KEY (`idPermisos`),
  UNIQUE KEY `idPermisos_UNIQUE` (`idPermisos`),
  UNIQUE KEY `Grupos_idGrupos_UNIQUE` (`Grupos_idGrupos`),
  UNIQUE KEY `Acciones_idAcciones_UNIQUE` (`Acciones_idAcciones`),
  UNIQUE KEY `claveCompuestaPermisos` (`Grupos_idGrupos`,`Acciones_idAcciones`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`idPermisos`, `Grupos_idGrupos`, `Acciones_idAcciones`) VALUES
(6, 5, 2),
(7, 6, 3),
(10, 7, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puestos`
--

DROP TABLE IF EXISTS `puestos`;
CREATE TABLE IF NOT EXISTS `puestos` (
  `idPuestos` int NOT NULL AUTO_INCREMENT,
  `Usuarios_idUsuarios` int NOT NULL,
  `Grupos_idGrupos` int NOT NULL,
  PRIMARY KEY (`idPuestos`),
  UNIQUE KEY `idPuestos_UNIQUE` (`idPuestos`),
  UNIQUE KEY `claveCompuestaPuestos` (`Usuarios_idUsuarios`,`Grupos_idGrupos`),
  KEY `idUsuarios_idx` (`Usuarios_idUsuarios`),
  KEY `idGrupos_idx` (`Grupos_idGrupos`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `puestos`
--

INSERT INTO `puestos` (`idPuestos`, `Usuarios_idUsuarios`, `Grupos_idGrupos`) VALUES
(1, 4, 5),
(6, 6, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `idUsuarios` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(45) NOT NULL,
  `password` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idUsuarios`),
  UNIQUE KEY `idUsuarios_UNIQUE` (`idUsuarios`),
  UNIQUE KEY `usuario_UNIQUE` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuarios`, `usuario`, `password`) VALUES
(2, 'Carlos ', '1234'),
(4, 'Jose', 'abcd'),
(6, 'Luis', 'a*12');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `Grupos_idGrupos` FOREIGN KEY (`Grupos_idGrupos`) REFERENCES `grupos` (`idGrupos`),
  ADD CONSTRAINT `idAcciones` FOREIGN KEY (`Acciones_idAcciones`) REFERENCES `acciones` (`idAcciones`);

--
-- Filtros para la tabla `puestos`
--
ALTER TABLE `puestos`
  ADD CONSTRAINT `idGrupos` FOREIGN KEY (`Grupos_idGrupos`) REFERENCES `grupos` (`idGrupos`),
  ADD CONSTRAINT `idUsuarios` FOREIGN KEY (`Usuarios_idUsuarios`) REFERENCES `usuarios` (`idUsuarios`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
