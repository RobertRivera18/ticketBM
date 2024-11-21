-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-11-2024 a las 23:36:41
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
(2, 'ADRIAN CARBO DARWIN ARISTIDES', '0920792306', 1),
(3, 'ALCIVAR CRUZ ISRAEL PASCUAL', '0930364781', 1),
(4, 'AMANCHA ALCIVAR JUAN CARLOS', '0920960606', 1),
(5, 'ANDRADE NU¥EZ GABRIEL ISRAEL', '0911449866', 1),
(6, 'ASTUDILLO BARRERA KLEBER YASSER', '0943713172', 1),
(7, 'AZUA MOLINA PAUL ANDRES', '0927344218', 1),
(8, 'BAJA¥A SALTOS JULIO OMAR', '0912271947', 1),
(9, 'BAUTISTA BALLESTERO SARA RAQUEL', '0931782486', 1),
(10, 'BERMUDES CEDE¥O CARLOS ANDRES', '0925835084', 1),
(11, 'BOHORQUEZ ESPA¥A GABRIEL ELIAS', '0923587927', 1),
(12, 'BULGARIN MOREIRA EDISON GREGORIO', '0917787319', 1),
(13, 'BURGOS LITARDO GEORGE RAMON', '0913959565', 1),
(14, 'CAGUANA PAYE LEYSY ADRIANA', '0952200541', 1),
(15, 'CAMPOZANO BRAVO JHON LUIGI', '0929847614', 1),
(16, 'CANTOS LOPEZ VICTOR XAVIER', '0917700494', 1),
(17, 'CARVAJAL MONTES JOSE ENRIQUE', '0924490063', 1),
(18, 'CEDE¥O CASTRO BYRON JOSEPH', '0932026370', 1),
(19, 'COELLO TORRES ALEXI HUMBERTO', '0924042542', 1),
(20, 'DELGADO CARRASCO ELIAS ISMAEL', '0926811704', 1),
(21, 'DUMES INTRIAGO JANIRA JAMILETH', '0951707462', 1),
(22, 'ELIZALDE SANTOS DANIELA FERNANDA', '0923294623', 1),
(23, 'ESPINOZA ALVARADO LUIS ANGEL', '0922358411', 1),
(24, 'ESPINOZA CHUQUI ALEX HUGO', '1250501937', 1),
(25, 'ESPINOZA CHUQUI MADELEN GABRIELA', '1250501895', 1),
(26, 'ESPINOZA GUARANDA ROBERTO FABIAN', '0921508891', 1),
(27, 'FERNANDEZ CASTRO ALEX BOLIVAR', '0925182529', 1),
(28, 'FLORES ANDRADE JUAN JOSE', '0914708144', 1),
(29, 'FLORES VILLACRES JEREMY JOLAHOUS', '0958774176', 1),
(30, 'FREIRE MACIAS DAVID JESUS', '0931196133', 1),
(31, 'GALEAS VIVANCO GUSTAVO GERARDO', '0921056776', 1),
(32, 'GALLINO LASCANO WALTER GUSTAVO', '0925181828', 1),
(33, 'GARCES MURILLO FRANCISCO XAVIER', '0912079704', 1),
(34, 'GARCIA NU¥EZ JORGE OMAR', '0913564381', 1),
(35, 'GARCIA REYES HENRY ADOLFO', '0920917309', 1),
(36, 'GAVIDIA HOLGUIN EDWIN STEVEN', '0932531189', 1),
(37, 'GOMEZ AGUIRRE RENZO ADRIAN', '0924803125', 1),
(38, 'GOMEZ CEDE¥O GERSON GEOVANNY', '0951355718', 1),
(39, 'GOMEZ MEJIA ANGEL JONATHAN', '0940648199', 1),
(40, 'GONGORA MEZA WILSON OMAR', '1307265098', 1),
(41, 'GOROTIZA MORANTE MAURO GILBERTO', '0911346203', 1),
(42, 'GOVEA GUALLO JUAN ANDRES', '0918367301', 1),
(43, 'GRANJA ALFONSO FRANCISCO JAVIER', '0960502938', 1),
(44, 'GUAMA PRADO JAIRON JHON', '0929224764', 1),
(45, 'GUERRERO DORADO ARNALDO JESUS', '0917355539', 1),
(46, 'GUEVARA SANDOVAL KEVIN JOSE', '0953216678', 1),
(47, 'GUZMAN BASTIDAS ROOSEVELT STALIN', '0914836622', 1),
(48, 'HERNANDEZ ARELLANO CARLOS AURELIO', '0917547424', 1),
(49, 'HIDALGO FLORES ANGEL RAFAEL', '0920822475', 1),
(50, 'HIDALGO GARCIA ORLY RONALD', '0925101768', 1),
(51, 'LARA PARRAGA LUIS FELIPE', '0950619254', 1),
(52, 'LEON ALVAREZ GEOVANNY ALEXANDER', '0920323599', 1),
(53, 'LEON PARRALES CESAR ALEJANDRO', '0914537576', 1),
(54, 'LINDAO DOYLETH  ELVIS ANDRES ', '0950494898', 1),
(55, 'LOZANO PLAZA BRAYAN ADRIAN', '1650133208', 1),
(56, 'MARCIAL INFANTE ROBERTO CARLOS', '0920104247', 1),
(57, 'MARCILLO VASQUEZ CRISTIAN JAVIER', '0920185352', 1),
(58, 'MARSHALL SANCHEZ JORGE ENRIQUE', '0926904913', 1),
(59, 'MIRANDA TUMBACO JAHIR ALEJANDRO', '0950915942', 1),
(60, 'MONTOYA SEVILLANO JHONNY ALBERTO', '0911640951', 1),
(61, 'MORENO JORDAN ROLANDO MAURICIO', '0919441592', 1),
(62, 'MORENO ZAPATA RICARDO ROLANDO', '0909332835', 1),
(63, 'OLMEDO MERO LUIS ALBERTO', '0915059034', 1),
(64, 'O¥ATE VELIZ RONNY JONATHAN', '0923278220', 1),
(65, 'PALACIOS LOOR PAOLA LILIBETH', '1311233082', 1),
(66, 'PEREDO MALAVE KABIL MICHAEL', '0915693014', 1),
(67, 'PEREIRA ALVAREZ ROBERTO CARLOS', '0917066821', 1),
(68, 'PEREZ MALDONADO STALIN FRANCISCO', '0922426119', 1),
(69, 'PEREZ PEREZ JOSELIN JOSSETTY', '0931784904', 1),
(70, 'PEREZ VIEJO CARLOS LUIS', '1754902508', 1),
(71, 'PILLIGUA PINCAY FABIAN CLEMENTE', '0925730095', 1),
(72, 'PRECIADO VALENCIA JOSSELINE GENESIS', '0954904249', 1),
(73, 'QUINTEROS AYOVI CARLOS RAUL', '0924064199', 1),
(74, 'QUINTEROS AYOVI FRANKLIN RUBEN', '0915086862', 1),
(75, 'REYES MERO ORLANDO DANIEL', '0922061163', 1),
(76, 'RIVERA DELGADO DARWIN RICARDO', '0916892342', 1),
(77, 'RODRIGUEZ YAR JONATHAN STEVEN', '0929303121', 1),
(78, 'SALAVARRIA TUTIVEN JORDY ALEXANDER', '0950903484', 1),
(79, 'SANTILLAN ALCIVAR VICTOR STALIN', '0953511169', 1),
(80, 'SERRAT GARCIA CARLOS FERNANDO', '0951657881', 1),
(81, 'TIGUA LUZARDO ROBERTO CARLOS ', '0926640517', 1),
(82, 'TOBAR LASCANO WILLIAN HUMBERTO', '0920900560', 1),
(83, 'UNDA VEINTIMILLA CARLOS ALBERTO', '0917500647', 1),
(84, 'UNDA VEINTIMILLA DANIEL ENRIQUE', '0914826169', 1),
(85, 'VALENCIA PE¥A NESTOR FERNANDO', '0802486688', 1),
(86, 'VALVERDE CORDERO KEVIN STEEVEN', '0958982472', 1),
(87, 'VARELA AVENDA¥O KEIBIN JESUS', '0963451729', 1),
(88, 'VARGAS ASTUDILLO CARLOS DANIEL', '0919980920', 1),
(89, 'VELIZ GUTIERREZ LUIS BRYAN', '0950580340', 1),
(90, 'VERA CONTRERAS ANGEL DARIO', '0919440545', 1),
(91, 'VERA INTRIAGO EDISON ROBERTO', '0952541837', 1),
(92, 'VILLAMAR MOR?N PETTER CARLOS', '0919422287', 1),
(93, 'YAGUAL CONTRERAS AXEL ABRAHAM', '0957690514', 1),
(94, 'ADRIAN ALVARADO ANDERSON ADONIS', '0955159587', 2),
(95, 'AGUILERA VELASQUEZ JUAN RAFAEL', '1761555364', 2),
(96, 'ALARCON DOMINGUEZ RONALD JAVIER', '0924343122', 2),
(97, 'AVILA ALVEAR NELSON RODRIGO', '0925845349', 2),
(98, 'BAQUE PLUA HENRY NELSON', '0950841775', 2),
(99, 'BASANTES VARGAS VERONICA NATHALI', '1718314147', 2),
(100, 'BASTIDAS BASTIDAS CHRISTIAN HERNAN', '1712867058', 2),
(101, 'BERNARDINO VEGA ANTHONY WILSON', '0931993489', 2),
(102, 'BOWEN ROMERO CRISTHEL DANIELA', '0958153116', 2),
(103, 'CABEZAS CRUZ ESTEFANY NATALIA', '1717366197', 2),
(104, 'CAICEDO MU¥OZ CRISTHIAN ANDRES', '0925369076', 2),
(105, 'CANGAS PUSDA JEFFERSON DAVID', '1725058018', 2),
(106, 'CASTRO SUAREZ KEVIN STALIN', '1725627994', 2),
(107, 'CHASI MORALES DARWIN IVAN', '1722271333', 2),
(108, 'CHAVEZ MAILA EDWIN PATRICIO', '1717874174', 2),
(109, 'CHOEZ BAQUE EMILIO VIRGILIO', '1309609707', 2),
(110, 'CONSTANTE TIBAN FERNANDA MARCELA', '1727451633', 2),
(111, 'CORREA PILLAJO ANGEL FERNANDO', '1722575980', 2),
(112, 'COSTALES COSTALES ALBERTO BENIGNO ', '0910373380', 2),
(113, 'CRESPIN MORENO REY FRANCISCO', '0950848614', 2),
(114, 'CRUZATTI PESANTES MARIA PAULA', '0953220795', 2),
(115, 'ESPINALES GALARZA DANI JOSUE', '0951766823', 2),
(116, 'GONZALEZ CHUINT STALIN JOSE', '1400753313', 2),
(117, 'GUZMAN FIGUEROA JORDAN KENNY', '0930524566', 2),
(118, 'HERNANDEZ SALAZAR BRYAN DANIEL', '0933031304', 2),
(119, 'HERRERA CENTENO DARWIN ROBERTO', '1725545733', 2),
(120, 'HORMAZA RODRIGUEZ GENESIS DEL ROCIO', '0950504548', 2),
(121, 'INTRIAGO QUI¥ONEZ JEREMI SEBASTIAN', '0951564467', 2),
(122, 'LOPEZ QUIMIS ROLANDO RODOLFO', '1205774134', 2),
(123, 'LOZADA VILLAMAR CRISTHIAN VICENTE', '0920678638', 2),
(124, 'MARTINEZ PROA¥O KATHERINE PRISCILA', '0930841176', 2),
(125, 'MENDOZA BAJA¥A OMAR ROBERTO', '0922837471', 2),
(126, 'MERO ZAMBRANO ROBER OMAR', '0915495337', 2),
(127, 'MOLINA GIRALDO KLEBER ADRIAN', '0922435185', 2),
(128, 'MORTEA NUGRA JAZMIN GUADALUPE', '1755982640', 2),
(129, 'NONURA AGUA ABRAHAM MOISES', '0954007969', 2),
(130, 'OBANDO MONTESDEOCA ANGELO JESUS', '0924708423', 2),
(131, 'OBANDO MONTESDEOCA DENNY MARCELO', '0925965642', 2),
(132, 'ORTEGA CHILA TOMMY RAFAEL', '0950052993', 2),
(133, 'ORTEGA QUIMBIULCO SARA MONSERRATH', '1713393567', 2),
(134, 'PALACIOS ANA TERESA', '1756875298', 2),
(135, 'PAREDES CEDE¥O JUAN CARLOS', '0920132644', 2),
(136, 'PARRALES MU¥OZ KENNIG ANDY', '0941452310', 2),
(137, 'PILAPANTA LLANO LUIS ALBERTO', '1712114444', 2),
(138, 'PINCAY ORTEGA FRANCISCO JAVIER', '0922203732', 2),
(139, 'QUISHPE ALOMOTO MICHELLE ESTEFANIA', '1725401762', 2),
(140, 'RAMIREZ SIMBA¥A VALERIA ELIZABETH', '1755598917', 2),
(141, 'RAMIREZ TUL CARLOS EDUARDO', '1720805025', 2),
(142, 'RAMOS CHANGO DANIELA ALEJANDRA', '1726005083', 2),
(143, 'RAMOS PINELA VICTOR ANTONIO', '0956662548', 2),
(144, 'REGALADO HERRERA ALISSON CRISTINA', '1725163552', 2),
(145, 'REINA ALVAREZ FABIOLA YULEISY', '0958441735', 2),
(146, 'RIBERA CATUCUAGO JOSE GABRIEL', '1709401176', 2),
(147, 'RIBERA CUICHAN LUIS ARMANDO', '1720352887', 2),
(148, 'RICO GONZALEZ ADRIANA SOPHIA', '0926972662', 2),
(149, 'RIVAS SAID MIGUEL', '1759672262', 2),
(150, 'RIZZO MORENO JESSICA NORALMA', '0918418732', 2),
(151, 'ROCAFUERTE ESPINOZA ORLY EFREN', '0912904257', 2),
(152, 'RODRIGUEZ VEGA EUGENIO DE JESUS', '0704989631', 2),
(153, 'RODRIGUEZ VELASQUEZ JORDAN ALEXANDER', '1727249144', 2),
(154, 'ROSALES CAGUASQUI EVELYN NATALY', '1722979935', 2),
(155, 'SANTILLAN ZARATE GEORGE ANTHONY', '0928870302', 2),
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
(166, 'ZARAGURO MORENO JULIO CESAR', '0951416635', 2),
(167, 'VIRACUCHA QUISHPE SANTIAGO MAURICIO', '1719182774', 2),
(168, 'MINA SALAS FREDDY GIOVANNY', '1715074199', 2),
(169, 'TIPAN ITURRALDE MANUEL ALEJANDRO', '1757226673', 2),
(189, 'RAMIREZ BRAVO KEVIN KENNETH', '0926488099', 2);

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
(118, 'GPON  C415', 1, 2);

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
(43, 114, 107);

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
  `serie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tm_equipos`
--

INSERT INTO `tm_equipos` (`equipo_id`, `nombre_equipo`, `marca`, `serie`) VALUES
(1, 'CPU Idepad2010', 'Lenovo', 'FGBDHE4564'),
(2, 'Monitor', 'Samsung', 'FGBDHE4564877856HTRJTYJT');

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
(2, 'Claro1', 22),
(7, 'CNEL9', 0),
(8, 'ClaroEdicion1', 0),
(9, 'Telconet', 0),
(10, 'CNEL0002', 0);

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
(18, 'Operador Claro', 'Default', 'default@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 1, 3, '2024-11-06 11:33:53', NULL, '2024-11-12 08:35:38', 0),
(19, 'Operador Claro', 'Claro', 'claro@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 3, 1, '2024-11-06 11:38:17', NULL, NULL, 1),
(20, 'Anderson', 'Operativo', 'operativoclaro@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 3, 2, '2024-11-06 12:43:56', NULL, '2024-11-06 12:44:02', 0),
(21, 'soporte', 'Rivera', 'soporte@gmail.com', 'Grupobm2018', 2, 3, '2024-11-06 16:01:07', NULL, NULL, 1),
(22, 'Adrian', 'Robalino', 'demo12@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 3, 2, '2024-11-07 16:50:46', NULL, NULL, 1),
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `cuadrilla_fk_cua_id` (`cua_id`),
  ADD KEY `cuadrilla_fk_col_id` (`col_id`);

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
  MODIFY `col_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT de la tabla `tm_cuadrilla`
--
ALTER TABLE `tm_cuadrilla`
  MODIFY `cua_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT de la tabla `tm_cuadrilla_colaborador`
--
ALTER TABLE `tm_cuadrilla_colaborador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `tm_cuadrilla_equipo`
--
ALTER TABLE `tm_cuadrilla_equipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tm_equipos`
--
ALTER TABLE `tm_equipos`
  MODIFY `equipo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tm_grupo`
--
ALTER TABLE `tm_grupo`
  MODIFY `grupo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  ADD CONSTRAINT `cuadrilla_fk_col_id` FOREIGN KEY (`col_id`) REFERENCES `tm_colaborador` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `cuadrilla_fk_cua_id` FOREIGN KEY (`cua_id`) REFERENCES `tm_cuadrilla` (`cua_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tm_cuadrilla_equipo`
--
ALTER TABLE `tm_cuadrilla_equipo`
  ADD CONSTRAINT `fk_cuadrilla` FOREIGN KEY (`cua_id`) REFERENCES `tm_cuadrilla` (`cua_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_equipo` FOREIGN KEY (`equipo_id`) REFERENCES `tm_equipos` (`equipo_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
