-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 07-06-2024 a las 02:06:24
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

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `ActualizarAccion`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ActualizarAccion` (IN `id` INT, IN `action` VARCHAR(255))   BEGIN
    UPDATE acciones SET Accion = action WHERE idAcciones = id;
END$$

DROP PROCEDURE IF EXISTS `actualizarGrupo`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizarGrupo` (IN `p_group` VARCHAR(255), IN `p_id` INT)   BEGIN
    UPDATE grupos
    SET Grupo = p_group
    WHERE idGrupos = p_id;
END$$

DROP PROCEDURE IF EXISTS `ActualizarPermiso`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ActualizarPermiso` (IN `permisoId` INT, IN `groupId` INT, IN `actionId` INT)   BEGIN
    UPDATE permisos
    SET Grupos_idGrupos = groupId, Acciones_idAcciones = actionId
    WHERE idPermisos = permisoId;
END$$

DROP PROCEDURE IF EXISTS `ActualizarPuesto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ActualizarPuesto` (IN `positionId` INT, IN `userId` INT, IN `groupId` INT)   BEGIN
    UPDATE puestos 
    SET Usuarios_idUsuarios = userId, Grupos_idGrupos = groupId 
    WHERE idPuestos = positionId;
END$$

DROP PROCEDURE IF EXISTS `actualizar_usuario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar_usuario` (IN `id_usuario` INT, IN `nuevo_nombre` VARCHAR(255), IN `nueva_password` VARCHAR(255))   BEGIN
    UPDATE usuarios SET usuario = nuevo_nombre, password = nueva_password WHERE idUsuarios = id_usuario;
END$$

DROP PROCEDURE IF EXISTS `ContarPermisoPorId`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ContarPermisoPorId` (IN `idpermiso` INT, OUT `count` INT)   BEGIN
    SELECT COUNT(*) INTO count
    FROM permisos
    WHERE idPermisos = idpermiso;
END$$

DROP PROCEDURE IF EXISTS `EliminarAccion`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarAccion` (IN `idaccion` INT)   BEGIN
    DELETE FROM acciones WHERE idAcciones = idaccion;
END$$

DROP PROCEDURE IF EXISTS `EliminarPermiso`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarPermiso` (IN `idpermiso` INT)   BEGIN
    DELETE FROM permisos WHERE idPermisos = idpermiso;
END$$

DROP PROCEDURE IF EXISTS `EliminarPuesto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `EliminarPuesto` (IN `idpuesto` INT)   BEGIN
    DELETE FROM puestos WHERE idPuestos = idpuesto;
END$$

DROP PROCEDURE IF EXISTS `eliminar_grupo`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `eliminar_grupo` (IN `p_idgrupo` INT)   BEGIN
    -- Eliminar registros dependientes de la tabla permisos
    DELETE FROM permisos WHERE Grupos_idGrupos = p_idgrupo;
    
    -- Eliminar el grupo
    DELETE FROM grupos WHERE idGrupos = p_idgrupo;
END$$

DROP PROCEDURE IF EXISTS `eliminar_usuario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `eliminar_usuario` (IN `id_usuario` INT)   BEGIN
    -- Eliminar registros relacionados en la tabla puestos
    DELETE FROM puestos WHERE Usuarios_idUsuarios = id_usuario;

    -- Eliminar usuario
    DELETE FROM usuarios WHERE idUsuarios = id_usuario;
END$$

DROP PROCEDURE IF EXISTS `InsertarAccion`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertarAccion` (IN `accion` VARCHAR(255))   BEGIN
    INSERT INTO acciones (Accion) VALUES (accion);
END$$

DROP PROCEDURE IF EXISTS `InsertarPermiso`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertarPermiso` (IN `grupo` INT, IN `accion` INT)   BEGIN
    INSERT INTO permisos (Grupos_idGrupos, Acciones_idAcciones)
    VALUES (grupo, accion);
END$$

DROP PROCEDURE IF EXISTS `InsertarPuesto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertarPuesto` (IN `usuario` INT, IN `grupo` INT)   BEGIN
    INSERT INTO puestos (Usuarios_idUsuarios, Grupos_idGrupos) VALUES (usuario, grupo);
END$$

DROP PROCEDURE IF EXISTS `insertar_grupo`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_grupo` (IN `p_grupo` VARCHAR(255))   BEGIN
    INSERT INTO grupos (grupo) VALUES (p_grupo);
END$$

DROP PROCEDURE IF EXISTS `insertar_usuario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_usuario` (IN `usuario_param` VARCHAR(255), IN `password_param` VARCHAR(255))   BEGIN
    INSERT INTO usuarios (usuario, password) VALUES (usuario_param, password_param);
END$$

DROP PROCEDURE IF EXISTS `obtener_grupos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_grupos` ()   BEGIN
    SELECT * FROM grupos;
END$$

DROP PROCEDURE IF EXISTS `obtener_grupo_por_id`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_grupo_por_id` (IN `p_id` INT)   BEGIN
    SELECT * FROM grupos WHERE idGrupos = p_id;
END$$

DROP PROCEDURE IF EXISTS `obtener_usuarios`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_usuarios` ()   BEGIN
    SELECT * FROM usuarios;
END$$

DROP PROCEDURE IF EXISTS `obtener_usuario_por_id`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_usuario_por_id` (IN `id_usuario` INT)   BEGIN
    SELECT * FROM usuarios WHERE idUsuarios = id_usuario;
END$$

DROP PROCEDURE IF EXISTS `SeleccionarAcciones`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SeleccionarAcciones` ()   BEGIN
    SELECT * FROM acciones;
END$$

DROP PROCEDURE IF EXISTS `SeleccionarAccionPermiso`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SeleccionarAccionPermiso` ()   BEGIN
    SELECT idAcciones, Accion FROM acciones;
END$$

DROP PROCEDURE IF EXISTS `SeleccionarAccionPorId`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SeleccionarAccionPorId` (IN `id` INT)   BEGIN
    SELECT * FROM acciones WHERE idAcciones = id;
END$$

DROP PROCEDURE IF EXISTS `SeleccionarGrupoPermiso`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SeleccionarGrupoPermiso` ()   BEGIN
    SELECT idGrupos, Grupo FROM grupos;
END$$

DROP PROCEDURE IF EXISTS `SeleccionarGrupos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SeleccionarGrupos` ()   BEGIN
    SELECT idGrupos, Grupo FROM grupos;
END$$

DROP PROCEDURE IF EXISTS `SeleccionarPermisoPorId`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SeleccionarPermisoPorId` (IN `permisoId` INT)   BEGIN
    SELECT permisos.idPermisos, grupos.Grupo, acciones.Accion
    FROM permisos
    INNER JOIN grupos ON permisos.Grupos_idGrupos = grupos.idGrupos
    INNER JOIN acciones ON permisos.Acciones_idAcciones = acciones.idAcciones
    WHERE permisos.idPermisos = permisoId;
END$$

DROP PROCEDURE IF EXISTS `SeleccionarPermisos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SeleccionarPermisos` ()   BEGIN
    SELECT permisos.idPermisos, acciones.Accion, grupos.Grupo
    FROM permisos
    INNER JOIN acciones ON permisos.Acciones_idAcciones = acciones.idAcciones
    INNER JOIN grupos ON permisos.Grupos_idGrupos = grupos.idGrupos;
END$$

DROP PROCEDURE IF EXISTS `SeleccionarPuestoPorId`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SeleccionarPuestoPorId` (IN `idpuesto` INT)   BEGIN
    SELECT puestos.idPuestos, usuarios.Usuario, grupos.Grupo
    FROM puestos
    INNER JOIN usuarios ON puestos.Usuarios_idUsuarios = usuarios.idUsuarios
    INNER JOIN grupos ON puestos.Grupos_idGrupos = grupos.idGrupos
    WHERE puestos.idPuestos = idpuesto;
END$$

DROP PROCEDURE IF EXISTS `SeleccionarPuestos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SeleccionarPuestos` ()   BEGIN
    SELECT puestos.idPuestos, usuarios.Usuario, grupos.Grupo 
    FROM puestos 
    INNER JOIN usuarios ON puestos.Usuarios_idUsuarios = usuarios.idUsuarios
    INNER JOIN grupos ON puestos.Grupos_idGrupos = grupos.idGrupos;
END$$

DROP PROCEDURE IF EXISTS `SeleccionarUsuarios`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SeleccionarUsuarios` ()   BEGIN
    SELECT idUsuarios, usuario FROM usuarios;
END$$

DROP PROCEDURE IF EXISTS `VerificarAccion`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `VerificarAccion` (IN `idaccion` INT, OUT `userExists` INT)   BEGIN
    SELECT COUNT(*) INTO userExists
    FROM acciones
    WHERE idAcciones = idaccion;
END$$

DROP PROCEDURE IF EXISTS `verificarGrupo`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `verificarGrupo` (IN `p_idgrupo` INT, OUT `p_userExists` INT)   BEGIN
    SELECT COUNT(*) INTO p_userExists FROM grupos WHERE idGrupos = p_idgrupo;
END$$

DROP PROCEDURE IF EXISTS `VerificarPuesto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `VerificarPuesto` (IN `idpuesto` INT, OUT `existe` INT)   BEGIN
    SELECT COUNT(*) INTO existe
    FROM puestos
    WHERE idPuestos = idpuesto;
END$$

DROP PROCEDURE IF EXISTS `verificar_existencia_usuario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `verificar_existencia_usuario` (IN `id_usuario` INT, OUT `existe` BOOLEAN)   BEGIN
    SELECT COUNT(*) > 0 INTO existe FROM usuarios WHERE idUsuarios = id_usuario;
END$$

DELIMITER ;

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `acciones`
--

INSERT INTO `acciones` (`idAcciones`, `Accion`) VALUES
(2, 'Asar carnes rojas'),
(5, 'Preparar Entradas '),
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`idGrupos`, `Grupo`) VALUES
(6, 'Boucher'),
(1, 'Chef de Cuisine'),
(3, 'Cuisinier'),
(10, 'Entremetier'),
(4, 'Garde Mangier'),
(5, 'Rotisseur'),
(8, 'Tournant');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`idPermisos`, `Grupos_idGrupos`, `Acciones_idAcciones`) VALUES
(13, 1, 4),
(6, 5, 2),
(12, 6, 3);

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `puestos`
--

INSERT INTO `puestos` (`idPuestos`, `Usuarios_idUsuarios`, `Grupos_idGrupos`) VALUES
(9, 2, 4),
(8, 2, 6),
(1, 4, 5);

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuarios`, `usuario`, `password`) VALUES
(2, 'Carlos ', '1234'),
(4, 'Jose Luis', 'abcd'),
(8, 'Mario', 'ttrr');

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
