-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-12-2024 a las 22:38:29
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_l_colaborador_01` (IN `xcol_id` INT)   BEGIN
	SELECT * FROM tm_colaborador where col_id=xcol_id;
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
-- Estructura de tabla para la tabla `acta`
--

CREATE TABLE `acta` (
  `id_acta` int(11) NOT NULL,
  `tipo_acta` int(11) DEFAULT NULL COMMENT '1 es Acta de entrega Credencial , 2 es acata entrega Equipo.\r\n',
  `col_id` int(11) DEFAULT NULL,
  `equipo_id` int(11) DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `acta`
--

INSERT INTO `acta` (`id_acta`, `tipo_acta`, `col_id`, `equipo_id`, `fecha_entrega`) VALUES
(125, 1, 2, NULL, '2024-12-09 10:05:36');

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
  `col_cedula` varchar(10) NOT NULL,
  `empresa_id` int(11) DEFAULT NULL COMMENT '1 es CNEL Y 2 es Claro\r\n'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tm_colaborador`
--

INSERT INTO `tm_colaborador` (`col_id`, `col_nombre`, `col_cedula`, `empresa_id`) VALUES
(2, 'ADRIAN CARBO DARWIN ', '0920792306', 1),
(3, 'ALCIVAR CRUZ ISRAEL', '0930364781', 1),
(4, 'AMANCHA ALCIVAR JUAN', '0920960606', 1),
(5, 'ANDRADE NU¥EZ GABRIEL', '0911449866', 1),
(6, 'ASTUDILLO BARRERA KLEBER', '0943713172', 1),
(7, 'AZUA MOLINA PAUL', '0927344218', 1),
(8, 'BAJA¥A SALTOS JULIO', '0912271947', 1),
(9, 'BAUTISTA BALLESTERO SARA ', '0931782486', 1),
(10, 'BERMUDES CEDE¥O CARLOS', '0925835084', 1),
(11, 'BOHORQUEZ ESPA¥A GABRIEL', '0923587927', 1),
(12, 'BULGARIN MOREIRA EDISON', '0917787319', 1),
(13, 'BURGOS LITARDO GEORGE', '0913959565', 1),
(14, 'CAGUANA PAYE LEYSY', '0952200541', 1),
(15, 'CAMPOZANO BRAVO JHON', '0929847614', 1),
(16, 'CANTOS LOPEZ VICTOR', '0917700494', 1),
(17, 'CARVAJAL MONTES JOSE', '0924490063', 1),
(18, 'CEDE¥O CASTRO BYRON', '0932026370', 1),
(19, 'COELLO TORRES ALEXI', '0924042542', 1),
(20, 'DELGADO CARRASCO ELIAS', '0926811704', 1),
(21, 'DUMES INTRIAGO JANIRA', '0951707462', 1),
(22, 'ELIZALDE SANTOS DANIELA', '0923294623', 1),
(23, 'ESPINOZA ALVARADO LUIS', '0922358411', 1),
(24, 'ESPINOZA CHUQUI ALEX', '1250501937', 1),
(25, 'ESPINOZA CHUQUI MADELEN', '1250501895', 1),
(26, 'ESPINOZA GUARANDA ROBERTO', '0921508891', 1),
(27, 'FERNANDEZ CASTRO ALEX', '0925182529', 1),
(28, 'FLORES ANDRADE JUAN', '0914708144', 1),
(29, 'FLORES VILLACRES JEREMY', '0958774176', 1),
(30, 'FREIRE MACIAS DAVID', '0931196133', 1),
(31, 'GALEAS VIVANCO GUSTAVO', '0921056776', 1),
(32, 'GALLINO LASCANO WALTER', '0925181828', 1),
(33, 'GARCES MURILLO FRANCISCO', '0912079704', 1),
(34, 'GARCIA NU¥EZ JORGE', '0913564381', 1),
(35, 'GARCIA REYES HENRY', '0920917309', 1),
(36, 'GAVIDIA HOLGUIN EDWIN', '0932531189', 1),
(37, 'GOMEZ AGUIRRE RENZO', '0924803125', 1),
(38, 'GOMEZ CEDE¥O GERSON', '0951355718', 1),
(39, 'GOMEZ MEJIA ANGEL', '0940648199', 1),
(40, 'GONGORA MEZA WILSON', '1307265098', 1),
(41, 'GOROTIZA MORANTE MAURO', '0911346203', 1),
(42, 'GOVEA GUALLO JUAN', '0918367301', 1),
(43, 'GRANJA ALFONSO FRANCISCO', '0960502938', 1),
(44, 'GUAMA PRADO JAIRON', '0929224764', 1),
(45, 'GUERRERO DORADO ARNALDO', '0917355539', 1),
(46, 'GUEVARA SANDOVAL KEVIN', '0953216678', 1),
(47, 'GUZMAN BASTIDAS ROOSEVELT', '0914836622', 1),
(48, 'HERNANDEZ ARELLANO CARLOS', '0917547424', 1),
(49, 'HIDALGO FLORES ANGEL', '0920822475', 1),
(50, 'HIDALGO GARCIA ORLY', '0925101768', 1),
(51, 'LARA PARRAGA LUIS', '0950619254', 1),
(52, 'LEON ALVAREZ GEOVANNY', '0920323599', 1),
(53, 'LEON PARRALES CESAR', '0914537576', 1),
(54, 'LINDAO DOYLETH ELVIS', '0950494898', 1),
(55, 'LOZANO PLAZA BRAYAN', '1650133208', 1),
(56, 'MARCIAL INFANTE ROBERTO', '0920104247', 1),
(57, 'MARCILLO VASQUEZ CRISTIAN', '0920185352', 1),
(58, 'MARSHALL SANCHEZ JORGE', '0926904913', 1),
(59, 'MIRANDA TUMBACO JAHIR', '0950915942', 1),
(60, 'MONTOYA SEVILLANO JHONNY', '0911640951', 1),
(61, 'MORENO JORDAN ROLANDO', '0919441592', 1),
(62, 'MORENO ZAPATA RICARDO', '0909332835', 1),
(63, 'OLMEDO MERO LUIS', '0915059034', 1),
(64, 'O¥ATE VELIZ RONNY', '0923278220', 1),
(65, 'PALACIOS LOOR PAOLA', '1311233082', 1),
(66, 'PEREDO MALAVE KABIL', '0915693014', 1),
(67, 'PEREIRA ALVAREZ ROBERTO', '0917066821', 1),
(68, 'PEREZ MALDONADO STALIN', '0922426119', 1),
(69, 'PEREZ PEREZ JOSELIN', '0931784904', 1),
(70, 'PEREZ VIEJO CARLOS', '1754902508', 1),
(71, 'PILLIGUA PINCAY FABIAN', '0925730095', 1),
(72, 'PRECIADO VALENCIA JOSSELINE', '0954904249', 1),
(73, 'QUINTEROS AYOVI CARLOS', '0924064199', 1),
(74, 'QUINTEROS AYOVI FRANKLIN', '0915086862', 1),
(75, 'REYES MERO ORLANDO', '0922061163', 1),
(76, 'RIVERA DELGADO DARWIN', '0916892342', 1),
(77, 'RODRIGUEZ YAR JONATHAN', '0929303121', 1),
(78, 'SALAVARRIA TUTIVEN JORDY', '0950903484', 1),
(80, 'SERRAT GARCIA CARLOS', '0951657881', 1),
(81, 'TIGUA LUZARDO ROBERTO ', '0926640517', 1),
(82, 'TOBAR LASCANO WILLIAN', '0920900560', 1),
(83, 'UNDA VEINTIMILLA CARLOS', '0917500647', 1),
(84, 'UNDA VEINTIMILLA DANIEL', '0914826169', 1),
(85, 'VALENCIA PE¥A NESTOR', '0802486688', 1),
(86, 'VALVERDE CORDERO KEVIN', '0958982472', 1),
(87, 'VARELA AVENDA¥O KEIBIN', '0963451729', 1),
(88, 'VARGAS ASTUDILLO CARLOS', '0919980920', 1),
(89, 'VELIZ GUTIERREZ LUIS BRYAN', '0950580340', 1),
(90, 'VERA CONTRERAS ANGEL', '0919440545', 1),
(91, 'VERA INTRIAGO EDISON', '0952541837', 1),
(92, 'VILLAMAR MORAN PETTER', '0919422287', 1),
(93, 'YAGUAL CONTRERAS AXEL', '0957690514', 1),
(95, 'AGUILERA VELASQUEZ JUAN ', '1761555364', 2),
(96, 'ALARCON DOMINGUEZ RONALD', '0924343122', 2),
(97, 'AVILA ALVEAR NELSON', '0925845349', 2),
(98, 'BAQUE PLUA HENRY', '0950841775', 2),
(99, 'BASANTES VARGAS VERONICA', '1718314147', 2),
(100, 'BASTIDAS BASTIDAS CHRISTIAN', '1712867058', 2),
(101, 'BERNARDINO VEGA ANTHONY', '0931993489', 2),
(102, 'BOWEN ROMERO CRISTHEL', '0958153116', 2),
(103, 'CABEZAS CRUZ ESTEFANY', '1717366197', 2),
(104, 'CAICEDO MU¥OZ CRISTHIAN', '0925369076', 2),
(105, 'CANGAS PUSDA JEFFERSON', '1725058018', 2),
(106, 'CASTRO SUAREZ KEVIN', '1725627994', 2),
(107, 'CHASI MORALES DARWIN', '1722271333', 2),
(108, 'CHAVEZ MAILA EDWIN', '1717874174', 2),
(109, 'CHOEZ BAQUE EMILIO', '1309609707', 2),
(110, 'CONSTANTE TIBAN FERNANDA', '1727451633', 2),
(111, 'CORREA PILLAJO ANGEL', '1722575980', 2),
(112, 'COSTALES COSTALES ALBERTO', '0910373380', 2),
(113, 'CRESPIN MORENO REY', '0950848614', 2),
(114, 'CRUZATTI PESANTES MARIA', '0953220795', 2),
(115, 'ESPINALES GALARZA DANI', '0951766823', 2),
(116, 'GONZALEZ CHUINT STALIN', '1400753313', 2),
(118, 'HERNANDEZ SALAZAR BRYAN', '0933031304', 2),
(119, 'HERRERA CENTENO DARWIN', '1725545733', 2),
(120, 'HORMAZA RODRIGUEZ GENESIS', '0950504548', 2),
(122, 'LOPEZ QUIMIS ROLANDO', '1205774134', 2),
(123, 'LOZADA VILLAMAR CRISTHIAN', '0920678638', 2),
(124, 'MARTINEZ PROA¥O KATHERINE', '0930841176', 2),
(126, 'MERO ZAMBRANO ROBER', '0915495337', 2),
(127, 'MOLINA GIRALDO KLEBER', '0922435185', 2),
(128, 'MORTEA NUGRA JAZMIN', '1755982640', 2),
(129, 'NONURA AGUA ABRAHAM', '0954007969', 2),
(130, 'OBANDO MONTESDEOCA ANGELO', '0924708423', 2),
(131, 'OBANDO MONTESDEOCA DENNY', '0925965642', 2),
(133, 'ORTEGA QUIMBIULCO SARA', '1713393567', 2),
(134, 'PALACIOS ANA TERESA', '1756875298', 2),
(135, 'PAREDES CEDE¥O JUAN', '0920132644', 2),
(136, 'PARRALES MU¥OZ KENNIG', '0941452310', 2),
(137, 'PILAPANTA LLANO LUIS', '1712114444', 2),
(138, 'PINCAY ORTEGA FRANCISCO', '0922203732', 2),
(139, 'QUISHPE ALOMOTO MICHELLE', '1725401762', 2),
(140, 'RAMIREZ SIMBA¥A VALERIA', '1755598917', 2),
(141, 'RAMIREZ TUL CARLOS', '1720805025', 2),
(142, 'RAMOS CHANGO DANIELA', '1726005083', 2),
(143, 'RAMOS PINELA VICTOR', '0956662548', 2),
(144, 'REGALADO HERRERA ALISSON', '1725163552', 2),
(145, 'REINA ALVAREZ FABIOLA', '0958441735', 2),
(146, 'RIBERA CATUCUAGO JOSE', '1709401176', 2),
(147, 'RIBERA CUICHAN LUIS ', '1720352887', 2),
(148, 'RICO GONZALEZ ADRIANA SOPHIA', '0926972662', 2),
(149, 'RIVAS SAID MIGUEL', '1759672262', 2),
(150, 'RIZZO MORENO JESSICA NORALMA', '0918418732', 2),
(151, 'ROCAFUERTE ESPINOZA ORLY EFREN', '0912904257', 2),
(152, 'RODRIGUEZ VEGA EUGENIO DE JESUS', '0704989631', 2),
(153, 'RODRIGUEZ VELASQUEZ JORDAN ALEXANDER', '1727249144', 2),
(154, 'ROSALES CAGUASQUI EVELYN NATALY', '1722979935', 2),
(156, 'SOLIS CARABLI JEAN CARLOS', '0927646612', 2),
(157, 'TELLO RAMIREZ FAUSTO FABIAN', '2100170642', 2),
(158, 'TUFI¥O ALVARADO MARTHA ELIZABETH', '1718413378', 2),
(159, 'USHI¥A SUAREZ HECTOR EFRAIN', '1725155186', 2),
(160, 'VALDEZ RIVERA LEONARDO VICENTE', '0931619100', 2),
(161, 'VARAS BARRAGAN JHONNY GABRIEL', '0957253073', 2),
(162, 'VERDEZOTO CEDE¥O CRISTHIAN ENRIQUE', '0926038084', 2),
(163, 'VILLARREAL ROBLES ISRAEL ALEJANDRO', '1721932067', 2),
(164, 'ZAMBRANO BONE ROSA VALENTINA', '0955018791', 2),
(165, 'ZAMBRANO VELASCO JONATHAN DARIO', '2300466113', 2),
(167, 'VIRACUCHA QUISHPE SANTIAGO MAURICIO', '1719182774', 2),
(168, 'MINA SALAS FREDDY GIOVANNY', '1715074199', 2),
(169, 'TIPAN ITURRALDE MANUEL ALEJANDRO', '1757226673', 2),
(189, 'RAMIREZ BRAVO KEVIN KENNETH', '0926488099', 2),
(190, 'ABAD PALACIOS BYRON ', '0961978087', 1),
(191, 'SANTILLAN ALCIVAR VICTOR ', '0953511169', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_cuadrilla`
--

CREATE TABLE `tm_cuadrilla` (
  `cua_id` int(11) NOT NULL,
  `cua_nombre` varchar(256) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `cua_empresa` int(11) DEFAULT NULL COMMENT '1 es CLARO 2 Es CNEL\r\n',
  `cua_ciudad` int(11) DEFAULT NULL COMMENT '1 es Guayaquil 2 es Quito\r\n'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tm_cuadrilla`
--

INSERT INTO `tm_cuadrilla` (`cua_id`, `cua_nombre`, `cua_empresa`, `cua_ciudad`) VALUES
(1, 'HAGG07HFC - HAGGPON10', 1, 1),
(2, 'HAGG15HFC - HAGGPON15', 1, 1),
(3, 'HAGGPON01 - HAGG05HFC', 1, 1),
(4, 'HAGGPON02 - HAGG11HFC', 1, 1),
(5, 'HAGGPON04 - HAGG02HFC', 1, 1),
(6, 'HAGGPON06 - HAGG12HFC', 1, 1),
(7, 'HAGGPON08 - HAGG08HFC', 1, 1),
(8, 'HAGGPON12', 1, 1),
(9, 'HAGGPON13', 1, 1),
(10, 'HAGGPON14', 1, 1),
(11, 'HGGPON025', 1, 1),
(12, 'HGGPON029', 1, 1),
(112, 'GPON C189', 1, 2),
(113, 'HFC C449/GPON  C102', 1, 2),
(114, 'HFC C316/GPON  C103', 1, 2),
(115, 'GPON  C705', 1, 2),
(116, 'GPON  C451', 1, 2),
(117, 'GPON  C105', 1, 2),
(118, 'GPON  C415', 1, 2),
(120, '1500', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_cuadrilla_colaborador`
--

CREATE TABLE `tm_cuadrilla_colaborador` (
  `id` int(11) NOT NULL,
  `cua_id` int(11) NOT NULL,
  `col_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tm_cuadrilla_colaborador`
--

INSERT INTO `tm_cuadrilla_colaborador` (`id`, `cua_id`, `col_id`) VALUES
(4, 1, 157),
(5, 1, 112),
(6, 2, 97),
(7, 2, 160),
(8, 3, 138),
(9, 3, 98),
(10, 4, 109),
(11, 4, 156),
(12, 5, 122),
(13, 5, 101),
(14, 6, 96),
(15, 6, 136),
(16, 7, 131),
(17, 7, 113),
(18, 8, 126),
(19, 8, 115),
(20, 9, 130),
(21, 9, 189),
(22, 10, 151),
(23, 10, 143),
(24, 11, 127),
(25, 11, 135),
(26, 12, 150),
(27, 12, 123),
(28, 112, 168),
(29, 112, 169),
(30, 113, 146),
(31, 113, 147),
(34, 118, 95),
(35, 118, 141),
(36, 117, 165),
(37, 117, 119),
(38, 116, 163),
(39, 116, 105),
(40, 115, 111),
(41, 115, 167),
(42, 114, 159),
(43, 114, 107),
(46, 120, 190),
(49, 120, 191);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_cuadrilla_equipo`
--

CREATE TABLE `tm_cuadrilla_equipo` (
  `id` int(11) NOT NULL,
  `cua_id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_equipos`
--

CREATE TABLE `tm_equipos` (
  `equipo_id` int(11) NOT NULL,
  `nombre_equipo` varchar(255) NOT NULL,
  `marca` varchar(255) NOT NULL,
  `modelo` varchar(255) DEFAULT NULL,
  `serie` varchar(255) NOT NULL,
  `datos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tm_equipos`
--

INSERT INTO `tm_equipos` (`equipo_id`, `nombre_equipo`, `marca`, `modelo`, `serie`, `datos`) VALUES
(2, 'Monitor', 'Dell', 'E173FPb', 'CNOV4931-46633-587-3RDL', 0),
(3, 'Teclado', 'Generico', 'Generico', 'Generico', 0),
(4, 'Mouse', 'Generico', 'Generico', 'Generico', 0),
(5, 'Telefono', 'Grandstream', 'GXP1620', '20EZ1WDJC0F50DB9', 0),
(6, 'CPU', 'HP', 'SlimDesktop 290 - p0xxx', '8CG8330WJK', 0),
(7, 'Monitor', 'Lenovo', 'L193p', 'VLGTK08', 0),
(8, 'Teclado', 'Generico', 'Generico', 'Generico', 0),
(9, 'Mouse', 'Amazon Basics', 'MSU0939', '9G23600741B', 0),
(10, 'Impresora', 'EPSON', 'L3110', 'X644260208', 0),
(11, 'Impresora', 'Bematech', 'LR2000', '811080197000602', 0),
(12, 'Laptop', 'Dell', 'Latitude 5480', 'DW1NQN2', 0),
(13, 'Switch 5 puertos', 'Lite Wave', 'LS105G', '2222279003457', 0),
(14, 'Laptop', 'Lenovo', '20EV002JUS', 'PF0H9NUT', 0),
(15, 'Mouse', 'Generico', 'Generico', 'Generico', 0),
(16, 'Diadema', 'FEABASCK', 'EH07', 'X003S38X97', 0),
(17, 'Laptop', 'Dell', 'Latitude E6440', 'DTGLN12', 0),
(18, 'Mouse', 'Generico', 'Generico', 'Generico', 0),
(19, 'Laptop', 'Dell', 'Latitude 3340', 'GK2H612', 0),
(20, 'CPU', 'HP', 'EliteDesk 800 G1 SFF', 'MXL5180Z3L', 0),
(21, 'Monitor', 'Lenovo', 'LT1913p', 'V1L38037', 0),
(22, 'Teclado ', 'Amazon Basics', 'KU - 0833', '9G23600743B', 0),
(23, 'Mouse', 'Generico', 'Generico', 'Generico', 0),
(24, 'Diadema', 'Generico', 'Generico', 'Generico', 0),
(25, 'CPU', 'HP', 'EliteDesk 800 G1 SFF', 'MXL5200PHT', 0),
(26, 'Monitor', 'Lenovo', 'LT1913p', 'V3017HMT', 0),
(27, 'Teclado ', 'Amazon Basics', 'KU - 0833', '9G23600934B', 0),
(28, 'Mouse', 'Generico', 'Generico', 'Generico', 0),
(29, 'Diadema', 'Generico', 'Generico', 'Generico', 0),
(30, 'CPU', 'HP', 'EliteDesk 800 G1 SFF', 'MXL5461ZXJ', 0),
(31, 'Monitor', 'HP', 'L1706', 'CND6070GZN', 0),
(32, 'Teclado ', 'Amazon Basics', 'KU - 0833', '9G23600750B', 0),
(33, 'Diadema', 'FEABASCK', 'EH07', 'X003S38X97', 0),
(34, 'Laptop', 'Dell', 'Latitude 7390', 'BFZN4Q2', 0),
(35, 'Monitor', 'Lenovo', 'C20238FL0', 'UMB4FRBG', 0),
(36, 'Mouse ', 'Prologic', '21CL34', '3009537', 0),
(37, 'CPU', 'HP', 'SlimDesktop 290 - p0xxx', '8CG834178F', 0),
(38, 'Monitor', 'HP', 'L1706', 'CNC736QC19', 0),
(39, 'Teclado ', 'Amazon Basics', 'KU - 0833', '9G23600716B', 0),
(40, 'Mouse', 'Amazon Basics', 'MSU0939', '9G23600716B', 0),
(41, 'CPU', 'HP', 'Compaq Pro 6305 SFF', '2UA3100968', 0),
(42, 'Monitor', 'HP', 'LA1751g', 'CNC040RC3K', 0),
(43, 'Teclado ', 'Generico', 'Generico', 'Generico', 0),
(44, 'Mouse', 'Prologic', '21CL34', '3009537', 0),
(45, 'CPU', 'Lenovo', '11BD003HUS', 'MJ0D31AT', 0),
(46, 'Monitor', 'HP', 'L1755', 'CNK61121YD', 0),
(47, 'Teclado ', 'Amazon Basics', 'KU - 0833', '9G23600742B', 0),
(48, 'Mouse', 'Prologic', '21CL34', '3009537', 0),
(49, 'Laptop', 'Dell', 'Latitude 5480', 'B82M1N2', 0),
(50, 'Mouse', 'Prologic', '21CL34', '3009537', 0),
(51, 'Monitor', 'Lenovo', 'C2023FL0', 'URB5BCFH', 0),
(52, 'Adaptador de Red', 'LYNKSIS', 'USB3616V1', '1571S0S1B16845', 0),
(53, 'Telefono', 'Grandstream', 'GXP1620', '20EZ1WDJC0F50DBC', 0),
(54, 'Mouse', 'Prologic', '21CL34', '3009537', 0),
(55, 'Laptop', 'Dell', 'Latitude E5450', '9PRW162', 0),
(56, 'Monitor', 'Lenovo', 'LT1913p', 'V3006D4H', 0),
(57, 'Teclado ', 'Lenovo', 'SK - 8833', '8SSD50L21375AVCC09X1C9L', 0),
(58, 'Mouse', 'Lenovo', 'M0JUU0', '8SSM50L24505AVLC02S0TD5', 0),
(59, 'Telefono', 'Grandstream', 'GXP1620', '271FVFPH40B37B50', 0),
(60, 'Laptop', 'Lenovo', '20BFS0VE00', 'R901V1E1', 0),
(61, 'Mouse', 'Generico', 'Generico', 'Generico', 0),
(62, 'CPU', 'HP', 'Compaq Pro 6300 MT ', '2UA3390FXL', 0),
(63, 'Monitor', 'HP', 'L1750', 'CNC743PG5S', 0),
(64, 'Teclado ', 'Generico', 'Generico', 'Generico', 0),
(65, 'Mouse ', 'Prologic', '21CL34', '3009537', 0),
(66, 'CPU', 'HP', 'SlimDesktop 290 - p0xxx', '8C68330W6R', 0),
(67, 'Monitor', 'HP', 'LA1751g', '3CQ0422V7F', 0),
(68, 'Teclado ', 'Generico', 'Generico', 'Generico', 0),
(69, 'Mouse', 'Prologic', '21CL34', '3009537', 0),
(70, 'Pistola', 'Generico', 'Generico', 'Generico', 0),
(71, 'CPU', 'HP', 'Compaq Pro 6305 SFF', '2UA3070TN9', 0),
(72, 'Monitor', 'Samsung', '940BX', 'HA19HCGL902157Y', 0),
(73, 'Teclado ', 'Amazon Basics', 'KU - 0833', '9G23600939B', 0),
(74, 'Mouse', 'Generico', 'Generico', 'Generico', 0),
(75, 'Pistola', 'Generico', 'Generico', 'Generico', 0),
(76, 'Laptop', 'EVOO', 'EVC156-2', 'EVC126-2BK20J05960', 0),
(77, 'Monitor', 'Lenovo', 'LT1913p', 'V30137GP', 0),
(78, 'Teclado ', 'HP', 'SK-2025', 'Sin teclado', 0),
(79, 'Adaptador USB', 'Generico', 'Generico', 'Generico', 0),
(80, 'Adaptador de Red', 'Tp-Link', 'UE300', '221C1L2002823', 0),
(81, 'Telefono', 'CISCO', 'SPA303', 'CCQ16370S1B', 0),
(82, 'CPU', 'HP', 'SlimDesktop 290 - p0xxx', '8C6833WPR', 0),
(83, 'Monitor', 'Dell', 'E197FPt', 'CN-ONH319-72872-6BM-5AGS', 0),
(84, 'Teclado ', 'Amazon Basics', 'KU - 0833', '9G23600748B', 0),
(85, 'Mouse', 'Amazon Basics', 'MSU0939', '9G23600938B', 0),
(86, 'CPU', 'Dell', 'Optiplex 790', '53WCJQ1', 0),
(87, 'Monitor', 'Lenovo', 'LT1913p', 'V30137GV', 0),
(88, 'Teclado ', 'Amazon Basics', 'KU - 0833', '9G23600753B', 0),
(89, 'Mouse', 'Amazon Basics', 'MSU0939', '9G23600940B', 0),
(90, 'CPU', 'HP', 'EliteDesk 800 G1 SFF', 'MXL5212QVM', 0),
(91, 'Monitor', 'HP', 'LT1913p', 'V3006D8C', 0),
(92, 'Teclado ', 'Amazon Basics', 'KU - 0833', '9G23600930B', 0),
(93, 'Mouse', 'Amazon Basics', 'MSU0939', '9G23600936B', 0),
(94, 'CPU', 'HP', 'Compaq 6000 Pro MTPC', '20A04905YQ', 0),
(95, 'Monitor', 'HP', 'L1750', 'CNC743PGMR', 0),
(96, 'Teclado ', 'Generico', 'Generico', 'Generico', 0),
(97, 'Mouse', 'Generico', 'Generico', 'Generico', 0),
(98, 'CPU', 'HP', 'EliteDesk 800 G1 SFF', 'MXL5180Z09', 0),
(99, 'Monitor', 'Lenovo', 'LT1913p', 'V300KAKX', 0),
(100, 'Teclado ', 'Amazon Basics', 'KU - 0833', '9G23600749B', 0),
(101, 'Mouse', 'Prologic', '21CL34', '3009537', 0),
(102, 'CPU', 'HP', 'SlimDesktop 290 - p0xxx', '8C68331Q8D', 0),
(103, 'Monitor', 'Lenovo', 'LT1913p', 'V30062YX', 0),
(104, 'Teclado ', 'Generico', 'Generico', 'Generico', 0),
(105, 'Mouse', 'Generico', 'Generico', 'Generico', 0),
(106, 'CPU', 'HP', 'EliteDesk 800 G1 SFF', 'MXLS5129V9', 0),
(107, 'Monitor', 'Dell', '1908FPt', 'CN-OFP182-71618-72E-B330', 0),
(108, 'Teclado ', 'Amazon Basics', 'KU - 0833', '9G33600741B', 0),
(109, 'Mouse', 'Generico', 'Generico', 'Generico', 0),
(110, 'CPU', 'Dell', 'SlimDesktop 290 - p0xxx', '8CG83309LL', 0),
(111, 'Monitor', 'Dell', 'E176FPb', 'CN-0T9998-46633-625-93EM', 0),
(112, 'Teclado ', 'Amazon Basics', 'KU - 0833', '9G33600753B', 0),
(113, 'Mouse', 'Generico', 'Generico', 'Generico', 0),
(114, 'Laptop', 'Dell', 'Latitude 7390', 'DNTW4Q2', 0),
(115, '          Mouse', 'Prologic', '21CL34', '3009537', 0),
(116, 'Monitor', 'Lenovo', '111111', '111111111', 0),
(117, 'Laptop', 'EVOO', 'EVC156-2', 'EVC156-2BK20J03999', 0),
(118, 'Mouse', 'Prologic', '21CL34', '3009537', 0),
(119, 'Adaptador de Red', 'Startech', 'USB31000S', '220325005743', 0),
(120, 'Laptop', 'Lenovo', '81C4', 'PF15V6A9', 0),
(121, 'Monitor', 'Lenovo', 'P24H-10', 'VTH304B4', 0),
(122, 'Switch 4 puertos', 'Generico ', 'Generico', 'Generico', 0),
(123, 'Telefono', 'Grandstream', 'GXP1620', '20EZ1WDJCF5DBA', 0),
(124, 'Laptop', 'Lenovo', '81W0', 'PF23GBY3', 0),
(125, 'Adaptador de Red', 'Generico', 'Generico', 'Generico', 0),
(126, 'Mouse', 'Prologic', '21CL34', '3009537', 0),
(127, 'Laptop', 'Gateway', 'GTN141-4BL', '2ACPR-GWTN141-4', 0),
(128, 'Adaptador de Red', 'Generico', 'Generico', 'Generico', 0),
(129, 'Teclado', 'Amazon Basics', 'KU - 0833', '9G23600938B', 0),
(130, 'Mouse', 'Prologic', '21CL34', '3009537', 0),
(131, 'Laptop', 'Gateway', 'GWTN156-1GR', 'GWTN156-1GR20KBM00475', 0),
(132, 'Adaptador de Red', 'Generico', 'Generico', 'Generico', 0),
(133, 'Mouse', 'Amazon Basics', 'MSU0939', '9G23600753B', 0),
(134, 'Telefono', 'Grandstream', 'GXP1610', '20EZ1QHJ40D696CA', 0),
(135, 'Laptop', 'Prologic', 'Latitude 5480', 'BXX9P1N2', 0),
(136, 'Mouse', 'EVOO', '21CL34', '3009537', 0),
(137, 'Laptop', 'HP', '15-DK1036NR', 'CND-4412-6', 0),
(138, 'Monitor', 'Lenovo', 'T2454pA', 'V300A892', 0),
(139, 'CPU', 'Lenovo', '11AD0021US', 'MJ09W72R', 0),
(140, 'Monitor', 'Lenovo', 'D18225WT0', 'V9-03WXZX', 0),
(141, 'Monitor', 'Lenovo', 'T2054pC', 'VNA21XT1', 0),
(142, 'Teclado', 'Lenovo', 'KBRFBU71', '8SSKM0M84861AULC98901SG', 0),
(143, 'Mouse', 'Lenovo', 'M0RFKH0', '8SSKM0M84861AULC98901SG', 0),
(144, 'Impresora', 'Magicard Pronto', 'DT49XDUK', '63F8124PRONTOSTD3649-001', 0),
(145, 'Monitor', 'Samsung', '1.11111E+16', '1.11111E+14', 0),
(146, 'Telefono', 'CISCO', 'SPA303', 'CCQ1639OTS6', 0),
(147, 'Adaptador de Red', 'Tp-Link', 'UE300', '221C1L2002811', 0),
(267, 'CPU', 'Lenovo', '0830W36', 'MJCNZB2', 0);

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
(58, 1, 3, 'Problema al subir informacion al Drive 2', '<p>&nbsp;jffjdsfbjerg</p>', 'Abierto', '2024-11-05 11:45:53', 21, '2024-11-11 15:13:33', 1),
(59, 2, 4, 'Problema al subir informacion al Drive', '<p>Solicito el ingreso de una nueva laptop al departamento de Sistemas</p>', 'Abierto', '2024-11-06 11:10:58', 2, '2024-11-11 15:13:39', 1),
(60, 2, 4, 'Problema al subir informacion al Drive', '<p>Solicito el ingreso de una nueva laptop al departamento de Sistemas</p>', 'Abierto', '2024-11-06 11:11:08', NULL, NULL, 1),
(61, 18, 1, 'Problema al subir informacion al Drive', '<p>No se admite el envio de datos desde el onedrive parece una mala configuracion</p>', 'Abierto', '2024-11-06 11:34:55', NULL, NULL, 1),
(62, 2, 1, 'Problema al subir informacion al Drive Seccion3', '<p>Problema genera de red</p>', 'Abierto', '2024-11-06 14:23:35', NULL, NULL, 1),
(63, 2, 1, 'Problema al subir informacion al Drive Seccion3', '<p>Problema genera de red</p>', 'Cerrado', '2024-11-06 14:23:35', 24, '2024-11-27 17:05:00', 1),
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
(2, 'Soporte Ing. Robert', 'Rivera', 'rxrc1819@gmail.com', 'fcea920f7412b5da7be0cf42b8c93759', 2, 3, '2020-12-14 19:46:22', '2024-11-06 08:15:45', NULL, 1),
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
(14, 'Anderson', 'Rivera', 'default@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 1, 3, '2024-11-06 11:26:08', NULL, '2024-11-27 17:05:32', 0),
(15, 'Operador Claro', 'Operativo', 'operativoclaro@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 3, 2, '2024-11-06 11:26:29', NULL, '2024-11-06 11:27:47', 0),
(16, 'Operador Cnel01', 'Operativo', 'demo12@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 3, 1, '2024-11-06 11:27:07', NULL, '2024-11-06 11:27:43', 0),
(17, 'Operador Claro', 'Claro', 'operativoclaro@gmail.com', '123456', 1, 3, '2024-11-06 11:28:53', NULL, '2024-11-06 11:33:33', 0),
(18, 'Operador Claro', 'Default', 'default@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 1, 3, '2024-11-06 11:33:53', NULL, '2024-11-12 08:35:38', 0),
(19, 'Operador Claro', 'Claro', 'claro@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 3, 1, '2024-11-06 11:38:17', NULL, '2024-12-02 12:09:30', 0),
(20, 'Anderson', 'Operativo', 'operativoclaro@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 3, 2, '2024-11-06 12:43:56', NULL, '2024-11-06 12:44:02', 0),
(21, 'soporte', 'Rivera', 'soporte@gmail.com', 'Grupobm2018', 2, 3, '2024-11-06 16:01:07', NULL, '2024-11-22 09:35:11', 0),
(22, 'Adrian', 'Robalino', 'demo12@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 3, 2, '2024-11-07 16:50:46', NULL, '2024-12-02 12:09:34', 0),
(23, 'Soporte Robert003', 'Rivera', 'operativoclaro@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 1, 3, '2024-11-08 11:19:09', NULL, '2024-11-08 11:19:15', 0),
(24, 'Soporte Ing.Abrahm', 'Izurieta', 'soporte@corpbm.com', '012c5d6d1f7dee260b391264fb2f7b17', 2, 3, '2024-11-27 17:04:39', NULL, NULL, 1),
(25, 'Operador Claro', 'Claro', 'default@gmail.com', 'fcea920f7412b5da7be0cf42b8c93759', 1, 3, '2024-12-09 16:52:00', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_usuario_equipo`
--

CREATE TABLE `tm_usuario_equipo` (
  `id` int(11) NOT NULL,
  `usu_id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acta`
--
ALTER TABLE `acta`
  ADD PRIMARY KEY (`id_acta`),
  ADD KEY `acta_fkequipo` (`equipo_id`),
  ADD KEY `acta_ibfk_1` (`col_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `cuadrilla_fk_col_id` (`col_id`),
  ADD KEY `cuadrilla_fk_cua_id` (`cua_id`);

--
-- Indices de la tabla `tm_cuadrilla_equipo`
--
ALTER TABLE `tm_cuadrilla_equipo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cuadrilla` (`cua_id`),
  ADD KEY `fk_equipo` (`equipo_id`);

--
-- Indices de la tabla `tm_equipos`
--
ALTER TABLE `tm_equipos`
  ADD PRIMARY KEY (`equipo_id`);

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
-- Indices de la tabla `tm_usuario_equipo`
--
ALTER TABLE `tm_usuario_equipo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_fk_usu_id` (`usu_id`),
  ADD KEY `equipo_fk_equipo_id` (`equipo_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acta`
--
ALTER TABLE `acta`
  MODIFY `id_acta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

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
  MODIFY `col_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT de la tabla `tm_cuadrilla`
--
ALTER TABLE `tm_cuadrilla`
  MODIFY `cua_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT de la tabla `tm_cuadrilla_colaborador`
--
ALTER TABLE `tm_cuadrilla_colaborador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `tm_cuadrilla_equipo`
--
ALTER TABLE `tm_cuadrilla_equipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `tm_equipos`
--
ALTER TABLE `tm_equipos`
  MODIFY `equipo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT de la tabla `tm_ticket`
--
ALTER TABLE `tm_ticket`
  MODIFY `tick_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de la tabla `tm_usuario`
--
ALTER TABLE `tm_usuario`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `tm_usuario_equipo`
--
ALTER TABLE `tm_usuario_equipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `acta`
--
ALTER TABLE `acta`
  ADD CONSTRAINT `acta_fkequipo` FOREIGN KEY (`equipo_id`) REFERENCES `tm_equipos` (`equipo_id`),
  ADD CONSTRAINT `acta_ibfk_1` FOREIGN KEY (`col_id`) REFERENCES `tm_colaborador` (`col_id`);

--
-- Filtros para la tabla `tm_cuadrilla_colaborador`
--
ALTER TABLE `tm_cuadrilla_colaborador`
  ADD CONSTRAINT `cuadrilla_fk_col_id` FOREIGN KEY (`col_id`) REFERENCES `tm_colaborador` (`col_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `cuadrilla_fk_cua_id` FOREIGN KEY (`cua_id`) REFERENCES `tm_cuadrilla` (`cua_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tm_cuadrilla_equipo`
--
ALTER TABLE `tm_cuadrilla_equipo`
  ADD CONSTRAINT `fk_cuadrilla` FOREIGN KEY (`cua_id`) REFERENCES `tm_cuadrilla` (`cua_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_equipo` FOREIGN KEY (`equipo_id`) REFERENCES `tm_equipos` (`equipo_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tm_usuario_equipo`
--
ALTER TABLE `tm_usuario_equipo`
  ADD CONSTRAINT `equipo_fk_equipo_id` FOREIGN KEY (`equipo_id`) REFERENCES `tm_equipos` (`equipo_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_fk_usu_id` FOREIGN KEY (`usu_id`) REFERENCES `tm_usuario` (`usu_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
