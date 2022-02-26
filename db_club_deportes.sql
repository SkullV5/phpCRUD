-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-02-2022 a las 01:46:01
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_club_deportes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cancha`
--

CREATE TABLE `cancha` (
  `cod_cancha` int(11) NOT NULL,
  `nombre_cancha` varchar(200) NOT NULL,
  `estado_cancha` tinyint(4) NOT NULL,
  `obs_cancha` varchar(1000) DEFAULT NULL,
  `Disciplina_cod_disciplina` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cancha`
--

INSERT INTO `cancha` (`cod_cancha`, `nombre_cancha`, `estado_cancha`, `obs_cancha`, `Disciplina_cod_disciplina`) VALUES
(1, 'Futbol', 10, 'N/A', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `cod_cliente` int(11) NOT NULL,
  `ci_cliente` varchar(20) NOT NULL,
  `nombre_cliente` varchar(200) NOT NULL,
  `apellido_cliente` varchar(200) NOT NULL,
  `dir_cliente` varchar(1000) DEFAULT NULL,
  `tel_cliente` varchar(20) DEFAULT NULL,
  `correo_cliente` varchar(100) DEFAULT NULL,
  `genero_cliente` char(2) DEFAULT NULL,
  `fn_cliente` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cod_cliente`, `ci_cliente`, `nombre_cliente`, `apellido_cliente`, `dir_cliente`, `tel_cliente`, `correo_cliente`, `genero_cliente`, `fn_cliente`) VALUES
(1, '1243243112', 'Mr. A', 'A', 'aaa', '123434456', 'a@a.com', 'Ma', '2000-07-30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_reserva`
--

CREATE TABLE `detalle_reserva` (
  `cancha_cod_cancha` int(11) NOT NULL,
  `reserva_cod_reserva` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(8,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_reserva`
--

INSERT INTO `detalle_reserva` (`cancha_cod_cancha`, `reserva_cod_reserva`, `cantidad`, `precio`) VALUES
(1, 2, 2, '29.950');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disciplina`
--

CREATE TABLE `disciplina` (
  `cod_disciplina` int(11) NOT NULL,
  `nombre_disciplina` varchar(200) NOT NULL,
  `tiempo_juego` varchar(45) NOT NULL,
  `empresa_cod_empresa` int(11) NOT NULL,
  `precio_disciplina` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `disciplina`
--

INSERT INTO `disciplina` (`cod_disciplina`, `nombre_disciplina`, `tiempo_juego`, `empresa_cod_empresa`, `precio_disciplina`) VALUES
(1, 'Futbol', '1 hora', 2, '29.95');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `cod_empresa` int(11) NOT NULL,
  `nombre_empresa` varchar(45) NOT NULL,
  `ruc_empresa` varchar(45) NOT NULL,
  `sec_empresa` varchar(45) NOT NULL,
  `direccion_empresa` varchar(45) DEFAULT NULL,
  `telefono_empresa` varchar(45) DEFAULT NULL,
  `correo_empresa` varchar(45) DEFAULT NULL,
  `representante_empresa` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`cod_empresa`, `nombre_empresa`, `ruc_empresa`, `sec_empresa`, `direccion_empresa`, `telefono_empresa`, `correo_empresa`, `representante_empresa`) VALUES
(2, 'ABC', '1234567890', 'aaa', 'a', '123456', 'a@a.com', 'Mr. A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `cod_promocion` int(11) NOT NULL,
  `nombre_promocion` varchar(200) NOT NULL,
  `estado_promocion` tinyint(4) DEFAULT NULL,
  `fechai_promocion` date DEFAULT NULL,
  `fechaf_promocion` date DEFAULT NULL,
  `descuento_promocion` decimal(8,3) NOT NULL,
  `Disciplina_cod_disciplina` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `cod_reserva` int(11) NOT NULL,
  `fecha_reserva` datetime NOT NULL,
  `fecha_juego` date NOT NULL,
  `horario_juego` time NOT NULL,
  `estado_reserva` char(2) NOT NULL,
  `cliente_cod_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`cod_reserva`, `fecha_reserva`, `fecha_juego`, `horario_juego`, `estado_reserva`, `cliente_cod_cliente`) VALUES
(2, '1970-01-01 03:00:00', '2022-03-01', '07:30:00', 'Ac', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cancha`
--
ALTER TABLE `cancha`
  ADD PRIMARY KEY (`cod_cancha`),
  ADD KEY `fk_cancha_Disciplina1_idx` (`Disciplina_cod_disciplina`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cod_cliente`);

--
-- Indices de la tabla `detalle_reserva`
--
ALTER TABLE `detalle_reserva`
  ADD PRIMARY KEY (`cancha_cod_cancha`,`reserva_cod_reserva`),
  ADD KEY `fk_cancha_has_reserva_reserva1_idx` (`reserva_cod_reserva`),
  ADD KEY `fk_cancha_has_reserva_cancha1_idx` (`cancha_cod_cancha`);

--
-- Indices de la tabla `disciplina`
--
ALTER TABLE `disciplina`
  ADD PRIMARY KEY (`cod_disciplina`),
  ADD KEY `fk_Disciplinas_empresa_idx` (`empresa_cod_empresa`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`cod_empresa`);

--
-- Indices de la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD PRIMARY KEY (`cod_promocion`),
  ADD KEY `fk_promocion_Disciplina1_idx` (`Disciplina_cod_disciplina`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`cod_reserva`),
  ADD KEY `fk_reserva_cliente1_idx` (`cliente_cod_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cancha`
--
ALTER TABLE `cancha`
  MODIFY `cod_cancha` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cod_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `disciplina`
--
ALTER TABLE `disciplina`
  MODIFY `cod_disciplina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `cod_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `cod_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cancha`
--
ALTER TABLE `cancha`
  ADD CONSTRAINT `fk_cancha_Disciplina1` FOREIGN KEY (`Disciplina_cod_disciplina`) REFERENCES `disciplina` (`cod_disciplina`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_reserva`
--
ALTER TABLE `detalle_reserva`
  ADD CONSTRAINT `fk_cancha_has_reserva_cancha1` FOREIGN KEY (`cancha_cod_cancha`) REFERENCES `cancha` (`cod_cancha`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cancha_has_reserva_reserva1` FOREIGN KEY (`reserva_cod_reserva`) REFERENCES `reserva` (`cod_reserva`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `disciplina`
--
ALTER TABLE `disciplina`
  ADD CONSTRAINT `fk_Disciplinas_empresa` FOREIGN KEY (`empresa_cod_empresa`) REFERENCES `empresa` (`cod_empresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD CONSTRAINT `fk_promocion_Disciplina1` FOREIGN KEY (`Disciplina_cod_disciplina`) REFERENCES `disciplina` (`cod_disciplina`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_reserva_cliente1` FOREIGN KEY (`cliente_cod_cliente`) REFERENCES `cliente` (`cod_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
