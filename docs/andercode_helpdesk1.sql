-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-11-2024 a las 23:07:18
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `andercode_helpdesk1`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_d_usuario_01` (IN `xusu_id` INT)   BEGIN
	UPDATE tm_usuario 
	SET 
		est='0',
		fech_elim = now() 
	where usu_id=xusu_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_i_ticketdetalle_01` (IN `xtick_id` INT, IN `xusu_id` INT)   BEGIN
	INSERT INTO td_ticketdetalle 
    (tickd_id,tick_id,usu_id,tickd_descrip,fech_crea,est) 
    VALUES 
    (NULL,xtick_id,xusu_id,'Ticket Cerrado...',now(),'1');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_l_usuario_01` ()   BEGIN
	SELECT * FROM tm_usuario where est='1';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_l_usuario_02` (IN `xusu_id` INT)   BEGIN
	SELECT * FROM tm_usuario where usu_id=xusu_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `td_documento`
--

CREATE TABLE `td_documento` (
  `doc_id` int(11) NOT NULL,
  `tick_id` int(11) NOT NULL,
  `doc_nom` varchar(400) NOT NULL,
  `fech_crea` datetime NOT NULL,
  `est` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `td_documento`
--

INSERT INTO `td_documento` (`doc_id`, `tick_id`, `doc_nom`, `fech_crea`, `est`) VALUES
(1, 34, 'LC04-0000000025.pdf', '2021-07-29 21:34:06', 1),
(2, 35, 'D:\\xampp\\tmp\\php8AC.tmp', '2021-07-29 21:37:09', 1),
(3, 36, 'F009-33 (1).pdf', '2021-07-29 21:38:17', 1),
(4, 37, 'F009-33 (1).pdf', '2021-07-29 21:39:03', 1),
(5, 37, 'F009-33.pdf', '2021-07-29 21:39:03', 1),
(6, 37, 'Cotizacion_gen-101008-563101.pdf', '2021-07-29 21:39:03', 1),
(7, 38, 'OBSERVACIONES SISTEMA SLI.docx', '2021-07-29 21:56:20', 1),
(8, 38, 'Observaciones sede sur 1003.docx', '2021-07-29 21:56:20', 1),
(9, 38, 'E001-145.pdf', '2021-07-29 21:56:20', 1),
(10, 38, 'Practice 1.4_PREPOSITIONS OF PLACE.pdf', '2021-07-29 21:56:20', 1),
(11, 54, 'ROL TECHNOQUO SEPT-24.pdf', '2024-11-05 11:06:21', 1),
(12, 55, 'CCO HAGGERSTON.pdf', '2024-11-05 11:26:33', 1),
(13, 56, 'CCO HAGGERSTON.pdf', '2024-11-05 11:33:30', 1),
(14, 56, 'planillaBiess (1).pdf', '2024-11-05 11:33:30', 1),
(15, 57, 'CCO HAGGERSTON.pdf', '2024-11-05 11:41:05', 1),
(16, 58, 'Sistema TicketConsultar Ticket.xlsx', '2024-11-05 11:45:53', 1),
(17, 60, 'grupo_bm_logo-Photoroom.png', '2024-11-06 11:11:08', 1),
(18, 61, 'ROL TECHNOQUO SEPT-24.pdf', '2024-11-06 11:34:55', 1),
(19, 70, 'andercode_helpdesk_53.sql', '2024-11-06 14:23:53', 1),
(20, 71, 'ROL TECHNOQUO SEPT-24.pdf', '2024-11-07 14:32:36', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `td_ticketdetalle`
--

CREATE TABLE `td_ticketdetalle` (
  `tickd_id` int(11) NOT NULL,
  `tick_id` int(11) NOT NULL,
  `usu_id` int(11) NOT NULL,
  `tickd_descrip` mediumtext NOT NULL,
  `fech_crea` datetime NOT NULL,
  `est` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `td_ticketdetalle`
--

INSERT INTO `td_ticketdetalle` (`tickd_id`, `tick_id`, `usu_id`, `tickd_descrip`, `fech_crea`, `est`) VALUES
(45, 56, 2, '<p>Envio de datos desde la web de soporte tecnico</p>', '2024-11-05 11:44:12', 1),
(46, 56, 1, '<p>no se soluciono el problema continuan los desfases de red</p>', '2024-11-05 12:11:27', 1),
(47, 56, 1, 'Ticket Cerrado...', '2024-11-05 12:42:46', 1),
(48, 61, 18, '<p>Necesito solventar este problema de forma urgente</p>', '2024-11-06 11:35:26', 1),
(49, 61, 18, '<p>Ayudenme lo mas pronto posible</p><p><br></p>', '2024-11-06 11:35:46', 1),
(50, 61, 2, 'Vuelva a iniciar sesión en el OneDrive y verifique la conectividad a Internet', '2024-11-06 11:36:58', 1),
(51, 65, 2, 'Ticket Cerrado...', '2024-11-06 14:24:12', 1),
(52, 64, 2, 'Ticket Cerrado...', '2024-11-06 14:24:21', 1),
(53, 63, 2, 'Ticket Cerrado...', '2024-11-06 14:24:36', 1),
(54, 56, 2, 'Ticket Re-Abierto...', '2024-11-06 16:08:39', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_categoria`
--

CREATE TABLE `tm_categoria` (
  `cat_id` int(11) NOT NULL,
  `cat_nom` varchar(150) NOT NULL,
  `est` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tm_categoria`
--

INSERT INTO `tm_categoria` (`cat_id`, `cat_nom`, `est`) VALUES
(1, 'Hardware', 1),
(2, 'Software', 1),
(3, 'Incidencia', 1),
(4, 'Petición de Servicio', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_colaborador`
--

CREATE TABLE `tm_colaborador` (
  `col_id` int(11) NOT NULL,
  `col_nombre` varchar(256) NOT NULL,
  `col_apellido` varchar(256) NOT NULL,
  `col_cedula` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tm_colaborador`
--

INSERT INTO `tm_colaborador` (`col_id`, `col_nombre`, `col_apellido`, `col_cedula`) VALUES
(22, 'Mario', 'Ochoa', '2400345678'),
(28, 'Robert Xavier', 'Rivera Cast', '0997433070'),
(29, 'Satelite', 'Rodriguez', '0928411990'),
(31, 'Cinthya Mariana', 'Hermenejildo Acosta', '0928411990');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_cuadrilla`
--

CREATE TABLE `tm_cuadrilla` (
  `cua_id` int(11) NOT NULL,
  `cua_nombre` varchar(256) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tm_cuadrilla`
--

INSERT INTO `tm_cuadrilla` (`cua_id`, `cua_nombre`) VALUES
(34, 'CNEL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_cuadrilla_colaborador`
--

CREATE TABLE `tm_cuadrilla_colaborador` (
  `cua_id` int(11) NOT NULL,
  `col_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_grupo`
--

CREATE TABLE `tm_grupo` (
  `grupo_id` int(11) NOT NULL,
  `grupo_nombre` varchar(255) NOT NULL,
  `col_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tm_grupo`
--

INSERT INTO `tm_grupo` (`grupo_id`, `grupo_nombre`, `col_id`) VALUES
(2, 'Claro', 0),
(6, 'GPON Cnel Group', 0),
(7, 'CNEL9', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_ticket`
--

CREATE TABLE `tm_ticket` (
  `tick_id` int(11) NOT NULL,
  `usu_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `tick_titulo` varchar(250) NOT NULL,
  `tick_descrip` mediumtext NOT NULL,
  `tick_estado` varchar(15) DEFAULT NULL,
  `fech_crea` datetime DEFAULT NULL,
  `usu_asig` int(11) DEFAULT NULL,
  `fech_asig` datetime DEFAULT NULL,
  `est` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tm_ticket`
--

INSERT INTO `tm_ticket` (`tick_id`, `usu_id`, `cat_id`, `tick_titulo`, `tick_descrip`, `tick_estado`, `fech_crea`, `usu_asig`, `fech_asig`, `est`) VALUES
(56, 1, 2, 'Problema al subir informacion al Drive', '<p>Haciendo Pruebas de subida de datos.</p>', 'Abierto', '2024-11-05 11:33:30', 3, '2024-11-05 11:44:58', 1),
(57, 1, 2, 'Problema al subir informacion al Drive', '<p>Prueba de envio de datos</p>', 'Abierto', '2024-11-05 11:41:05', 21, '2024-11-06 16:50:41', 1),
(58, 1, 3, 'Problema al subir informacion al Drive 2', '<p>&nbsp;jffjdsfbjerg</p>', 'Abierto', '2024-11-05 11:45:53', NULL, NULL, 1),
(59, 2, 4, 'Problema al subir informacion al Drive', '<p>Solicito el ingreso de una nueva laptop al departamento de Sistemas</p>', 'Abierto', '2024-11-06 11:10:58', NULL, NULL, 1),
(60, 2, 4, 'Problema al subir informacion al Drive', '<p>Solicito el ingreso de una nueva laptop al departamento de Sistemas</p>', 'Abierto', '2024-11-06 11:11:08', NULL, NULL, 1),
(61, 18, 1, 'Problema al subir informacion al Drive', '<p>No se admite el envio de datos desde el onedrive parece una mala configuracion</p>', 'Abierto', '2024-11-06 11:34:55', NULL, NULL, 1),
(62, 2, 1, 'Problema al subir informacion al Drive Seccion3', '<p>Problema genera de red</p>', 'Abierto', '2024-11-06 14:23:35', NULL, NULL, 1),
(63, 2, 1, 'Problema al subir informacion al Drive Seccion3', '<p>Problema genera de red</p>', 'Cerrado', '2024-11-06 14:23:35', NULL, NULL, 1),
(64, 2, 1, 'Problema al subir informacion al Drive Seccion3', '<p>Problema genera de red</p>', 'Cerrado', '2024-11-06 14:23:39', NULL, NULL, 1),
(65, 2, 1, 'Problema al subir informacion al Drive Seccion3', '<p>Problema genera de red</p>', 'Cerrado', '2024-11-06 14:23:39', NULL, NULL, 1),
(66, 2, 1, 'Problema al subir informacion al Drive Seccion3', '<p>Problema genera de red</p>', 'Abierto', '2024-11-06 14:23:39', NULL, NULL, 1),
(67, 2, 1, 'Problema al subir informacion al Drive Seccion3', '<p>Problema genera de red</p>', 'Abierto', '2024-11-06 14:23:39', NULL, NULL, 1),
(68, 2, 1, 'Problema al subir informacion al Drive Seccion3', '<p>Problema genera de red</p>', 'Abierto', '2024-11-06 14:23:40', NULL, NULL, 1),
(69, 2, 1, 'Problema al subir informacion al Drive Seccion3', '<p>Problema genera de red</p>', 'Abierto', '2024-11-06 14:23:40', NULL, NULL, 1),
(70, 2, 1, 'Problema al subir informacion al Drive Seccion3', '<p>Problema genera de red</p>', 'Abierto', '2024-11-06 14:23:53', NULL, NULL, 1),
(71, 2, 3, 'Problema CNT', '<p>fejbvuebveveve</p>', 'Abierto', '2024-11-07 14:32:36', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_usuario`
--

CREATE TABLE `tm_usuario` (
  `usu_id` int(11) NOT NULL,
  `usu_nom` varchar(150) DEFAULT NULL,
  `usu_ape` varchar(150) DEFAULT NULL,
  `usu_correo` varchar(150) NOT NULL,
  `usu_pass` varchar(150) NOT NULL,
  `rol_id` int(11) DEFAULT NULL COMMENT '1 es Usuario \r\n2 es Soporte \r\n 3 es operador\r\n',
  `empresa_id` int(11) DEFAULT NULL COMMENT '1 es Claro\r\n2 es CNEL\r\n3 Administrativo',
  `fech_crea` datetime DEFAULT NULL,
  `fech_modi` datetime DEFAULT NULL,
  `fech_elim` datetime DEFAULT NULL,
  `est` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla Mantenedor de Usuarios';

--
-- Volcado de datos para la tabla `tm_usuario`
--

INSERT INTO `tm_usuario` (`usu_id`, `usu_nom`, `usu_ape`, `usu_correo`, `usu_pass`, `rol_id`, `empresa_id`, `fech_crea`, `fech_modi`, `fech_elim`, `est`) VALUES
(1, 'Anderson', 'Default', 'default@gmail.com', '12345', 3, 1, '2020-12-14 19:46:22', NULL, '2024-11-06 11:27:37', 0),
(2, 'Soporte Robert', 'Rivera', 'rxrc1819@gmail.com', 'fcea920f7412b5da7be0cf42b8c93759', 2, 3, '2020-12-14 19:46:22', '2024-11-06 08:15:45', NULL, 1),
(3, 'Demo', 'Demo', 'demo12@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 2, 1, '2020-12-14 19:46:22', NULL, '2024-11-05 14:17:40', 0),
(4, 'qwqweqwe', 'qweqweqwe', 'qweqwe@a.com', 'e10adc3949ba59abbe56e057f20f883e', 1, 3, '2021-01-21 22:52:17', NULL, '2024-11-05 14:17:46', 0),
(5, 'Operador Claro', 'Claro', 'operativoclaro@gmail.com', '123456', 3, 1, '2021-01-21 22:52:53', NULL, '2024-11-06 11:10:15', 0),
(6, 'ASDASDA', 'ASDASD', 'ASASD@ASDOMC.COM', 'e10adc3949ba59abbe56e057f20f883e', 2, NULL, '2021-01-21 22:54:12', NULL, '2024-11-05 11:37:00', 0),
(7, 'asdasdasd', 'asdasdasd', 'asdasdasdasdasd@asdasdasd.com', 'e10adc3949ba59abbe56e057f20f883e', 1, NULL, '2021-01-21 22:55:12', NULL, '2021-02-05 22:23:09', 0),
(8, 'Test11111', 'Test2111111', 'test@test2.com.pe', 'e10adc3949ba59abbe56e057f20f883e', 1, NULL, '2021-02-05 22:22:31', NULL, '2021-02-08 21:09:58', 0),
(9, 'Usuario', 'Apellido', 'Correo@correo.com', 'e10adc3949ba59abbe56e057f20f883e', 1, NULL, '2021-06-13 19:09:17', NULL, '2024-11-05 11:37:08', 0),
(10, 'test1', 'test1', 'testw@correo.com', 'e10adc3949ba59abbe56e057f20f883e', 1, NULL, '2021-06-13 19:10:34', NULL, '2024-11-05 11:37:13', 0),
(11, 'Datos', 'Datos2', 'datos@datos.com', 'e10adc3949ba59abbe56e057f20f883e', 2, NULL, '2021-06-13 19:16:43', NULL, '2024-11-05 14:17:33', 0),
(12, 'Robert', 'Rivera', 'rxrc1819@gmail.com', '1234', 2, NULL, '2024-11-05 10:52:31', '2024-11-05 10:52:31', '2024-11-05 14:09:55', 0),
(13, 'user1', 'default', 'default@gmail.com', '25d55ad283aa400af464c76d713c07ad', 3, 1, '2024-11-05 14:22:16', NULL, '2024-11-06 11:10:07', 0),
(14, 'Anderson', 'Rivera', 'default@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 1, 3, '2024-11-06 11:26:08', NULL, NULL, 1),
(15, 'Operador Claro', 'Operativo', 'operativoclaro@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 3, 2, '2024-11-06 11:26:29', NULL, '2024-11-06 11:27:47', 0),
(16, 'Operador Cnel01', 'Operativo', 'demo12@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 3, 1, '2024-11-06 11:27:07', NULL, '2024-11-06 11:27:43', 0),
(17, 'Operador Claro', 'Claro', 'operativoclaro@gmail.com', '123456', 1, 3, '2024-11-06 11:28:53', NULL, '2024-11-06 11:33:33', 0),
(18, 'Operador Claro', 'Default', 'default@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 1, 3, '2024-11-06 11:33:53', NULL, NULL, 1),
(19, 'Operador Claro', 'Claro', 'claro@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 3, 1, '2024-11-06 11:38:17', NULL, NULL, 1),
(20, 'Anderson', 'Operativo', 'operativoclaro@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 3, 2, '2024-11-06 12:43:56', NULL, '2024-11-06 12:44:02', 0),
(21, 'soporte', '1', 'soporte@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 2, 3, '2024-11-06 16:01:07', NULL, NULL, 1),
(22, 'Adrian', 'Default', 'demo12@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 3, 2, '2024-11-07 16:50:46', NULL, NULL, 1),
(23, 'Soporte Robert003', 'Rivera', 'operativoclaro@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 1, 3, '2024-11-08 11:19:09', NULL, '2024-11-08 11:19:15', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `td_documento`
--
ALTER TABLE `td_documento`
  ADD PRIMARY KEY (`doc_id`);

--
-- Indices de la tabla `td_ticketdetalle`
--
ALTER TABLE `td_ticketdetalle`
  ADD PRIMARY KEY (`tickd_id`);

--
-- Indices de la tabla `tm_categoria`
--
ALTER TABLE `tm_categoria`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indices de la tabla `tm_colaborador`
--
ALTER TABLE `tm_colaborador`
  ADD PRIMARY KEY (`col_id`);

--
-- Indices de la tabla `tm_cuadrilla`
--
ALTER TABLE `tm_cuadrilla`
  ADD PRIMARY KEY (`cua_id`);

--
-- Indices de la tabla `tm_cuadrilla_colaborador`
--
ALTER TABLE `tm_cuadrilla_colaborador`
  ADD PRIMARY KEY (`cua_id`,`col_id`),
  ADD KEY `col_id` (`col_id`);

--
-- Indices de la tabla `tm_grupo`
--
ALTER TABLE `tm_grupo`
  ADD PRIMARY KEY (`grupo_id`);

--
-- Indices de la tabla `tm_ticket`
--
ALTER TABLE `tm_ticket`
  ADD PRIMARY KEY (`tick_id`);

--
-- Indices de la tabla `tm_usuario`
--
ALTER TABLE `tm_usuario`
  ADD PRIMARY KEY (`usu_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `td_documento`
--
ALTER TABLE `td_documento`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `td_ticketdetalle`
--
ALTER TABLE `td_ticketdetalle`
  MODIFY `tickd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `tm_categoria`
--
ALTER TABLE `tm_categoria`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tm_colaborador`
--
ALTER TABLE `tm_colaborador`
  MODIFY `col_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `tm_cuadrilla`
--
ALTER TABLE `tm_cuadrilla`
  MODIFY `cua_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `tm_grupo`
--
ALTER TABLE `tm_grupo`
  MODIFY `grupo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tm_ticket`
--
ALTER TABLE `tm_ticket`
  MODIFY `tick_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de la tabla `tm_usuario`
--
ALTER TABLE `tm_usuario`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tm_cuadrilla_colaborador`
--
ALTER TABLE `tm_cuadrilla_colaborador`
  ADD CONSTRAINT `tm_cuadrilla_colaborador_ibfk_1` FOREIGN KEY (`cua_id`) REFERENCES `tm_cuadrilla` (`cua_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tm_cuadrilla_colaborador_ibfk_2` FOREIGN KEY (`col_id`) REFERENCES `tm_colaborador` (`col_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
