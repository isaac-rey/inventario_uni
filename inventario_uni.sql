-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-10-2025 a las 03:58:23
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventario_uni`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`id`, `nombre`, `creado_en`) VALUES
(1, 'ALDEA', '2025-10-27 17:37:51'),
(2, 'UP', '2025-10-27 17:39:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `accion` text NOT NULL,
  `ip_usuario` varchar(250) NOT NULL,
  `user_agent` varchar(250) NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id`, `usuario_id`, `accion`, `ip_usuario`, `user_agent`, `fecha`) VALUES
(1, 7, 'Registró una nueva sala con ID 1 y Nombre: Biblioteca', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 14:53:41'),
(2, 7, 'Registró una nueva sala con ID 2 y Nombre: Laboratorio', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 14:57:59'),
(3, 7, 'Agregó el componente: Zapatilla   (bueno) al equipo ID 1 (Proyector Epson C0-W01).', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 15:07:02'),
(4, 7, 'Agregó el componente: Fuente   (bueno) al equipo ID 1 (Proyector Epson C0-W01).', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 15:07:14'),
(5, 7, 'Agregó el componente: HDMI   (bueno) al equipo ID 1 (Proyector Epson C0-W01).', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 15:07:23'),
(6, 7, 'Agregó el componente: Control remoto   (bueno) al equipo ID 1 (Proyector Epson C0-W01).', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 15:07:29'),
(7, 7, 'Registró un nuevo estudiante con ID 1 y Nombre: Joaquín Ayala', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 16:17:19'),
(8, 7, 'Rechazó la solicitud de devolución del préstamo ID 4 para el equipo ID 1. Motivo: ', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:09:46'),
(9, 7, 'Cancelación de préstamo activo - Préstamo ID 4. Motivo: .', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:09:52'),
(10, 7, 'Registró un nuevo Docente ID 2: César Algo (CI: 123456).', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:40:47'),
(11, 7, 'Registró un nuevo Docente ID 3: Nathalia Rotela (CI: 5695298).', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:42:19'),
(12, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:44:02'),
(13, 7, 'Rechazo de solicitud de préstamo - Préstamo ID 8 del equipo ID 5 (Equipo sin descripción) al docente \'César Algo\'. Motivo: .', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 20:52:23'),
(14, 7, 'Rechazo de solicitud de préstamo - Préstamo ID 9 del equipo ID 5 (Equipo sin descripción) al docente \'César Algo\'. Motivo: .', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 20:55:13'),
(15, 7, 'Rechazo de solicitud de préstamo del equipo ID 5 (Tele) al docente \'César Algo\'. Motivo: .', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 20:56:14'),
(16, 7, 'Rechazo de solicitud de préstamo del equipo ID 5 (Tele) al docente \'César Algo\'.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 20:57:42'),
(17, 7, 'Aprobó y registró el préstamo del equipo ID 5 (Tele  ) al docente \'César Algo\'.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 20:59:58'),
(18, 7, 'Ha cancelado de préstamo activo del equipo ID 5 (Tele) al docente \'santiago caballero\'', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 21:02:40'),
(19, 7, 'Registró un nuevo Docente ID 4: Joaquin Profe (CI: 12345678).', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 21:34:23'),
(20, 7, 'Editó los datos del estudiante ID 1 (Joaquín Ayala).', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 21:34:46'),
(21, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W01) al docente \'Nathalia Rotela\'.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 21:55:27'),
(22, 7, 'Rechazó la solicitud de devolución del préstamo ID 13 para el equipo ID 1. Motivo: ', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 23:07:01'),
(23, 7, 'Ha cancelado de préstamo activo del equipo ID 1 (Proyector Epson C0-W01) al docente \'Nathalia Rotela\'', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 23:11:02'),
(24, 7, 'Ha rechazado la solicitud de préstamo del equipo ID 1 (Proyector Epson C0-W01) al docente \'Nathalia Rotela\'', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 23:13:53'),
(25, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W01) al docente \'Nathalia Rotela\'.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 23:14:35'),
(26, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al docente \'Nathalia Rotela\'.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 23:49:41'),
(27, 7, 'Ha cancelado de préstamo activo del equipo ID 2 (Monitor AOC) al docente \'César Algo\'', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 23:50:33'),
(28, 7, 'Ha cancelado de préstamo activo del equipo ID 1 (Proyector Epson C0-W01) al docente \'César Algo\'', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 23:50:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cesiones`
--

CREATE TABLE `cesiones` (
  `id` int(11) NOT NULL,
  `prestamo_id` int(11) NOT NULL,
  `cedente_id` int(11) NOT NULL,
  `a_docente_id` int(11) NOT NULL,
  `fecha_solicitud` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_confirmacion` datetime DEFAULT NULL,
  `estado` enum('pendiente','aceptada','rechazada') NOT NULL DEFAULT 'pendiente',
  `observacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cesiones`
--

INSERT INTO `cesiones` (`id`, `prestamo_id`, `cedente_id`, `a_docente_id`, `fecha_solicitud`, `fecha_confirmacion`, `estado`, `observacion`) VALUES
(1, 12, 2, 1, '2025-10-27 21:00:48', '2025-10-28 01:02:00', 'aceptada', NULL),
(2, 13, 3, 2, '2025-10-27 22:08:50', NULL, 'pendiente', NULL),
(3, 15, 3, 2, '2025-10-27 23:14:59', '2025-10-28 03:15:04', 'aceptada', NULL),
(4, 16, 3, 2, '2025-10-27 23:50:03', '2025-10-28 03:50:23', 'aceptada', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `componentes`
--

CREATE TABLE `componentes` (
  `id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `tipo` varchar(80) NOT NULL,
  `marca` varchar(80) DEFAULT NULL,
  `modelo` varchar(120) DEFAULT NULL,
  `nro_serie` varchar(120) DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'bueno',
  `observacion` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `componentes`
--

INSERT INTO `componentes` (`id`, `equipo_id`, `tipo`, `marca`, `modelo`, `nro_serie`, `estado`, `observacion`, `creado_en`) VALUES
(1, 1, 'Zapatilla', '', '', NULL, 'bueno', '', '2025-10-27 18:07:02'),
(2, 1, 'Fuente', '', '', NULL, 'bueno', '', '2025-10-27 18:07:14'),
(3, 1, 'HDMI', '', '', NULL, 'bueno', '', '2025-10-27 18:07:23'),
(4, 1, 'Control remoto', '', '', NULL, 'bueno', '', '2025-10-27 18:07:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones`
--

CREATE TABLE `devoluciones` (
  `id` int(11) NOT NULL,
  `prestamo_id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `observacion` text DEFAULT NULL,
  `estado` enum('pendiente','aprobada','rechazada') NOT NULL DEFAULT 'pendiente',
  `creada_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `devoluciones`
--

INSERT INTO `devoluciones` (`id`, `prestamo_id`, `equipo_id`, `estudiante_id`, `observacion`, `estado`, `creada_en`) VALUES
(1, 3, 1, 1, '\nMotivo rechazo: ', 'rechazada', '2025-10-27 20:35:20'),
(2, 3, 1, 1, '\nMotivo rechazo: ', 'rechazada', '2025-10-27 20:35:39'),
(3, 3, 1, 1, '', 'aprobada', '2025-10-27 20:36:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id` int(11) NOT NULL,
  `ci` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id`, `ci`, `nombre`, `apellido`, `email`, `password_hash`, `creado_en`) VALUES
(1, '2901786', 'santiago', 'caballero', 'caballerosantiago@gmail.com', '$2y$10$p.vhB4ytCvO5LALLbmJKdu3rqvg6d9ZHHnT5UOQkGpnL9.Ws.iUjO', '2025-10-27 20:30:22'),
(2, '123456', 'César', 'Algo', 'cesaralgo@gmail.com', '$2y$10$zn4t8THCF18JbcCeHKHcdedZiVsrcvVa7igkROFx.x8JWH/SAMb4O', '2025-10-27 21:40:47'),
(3, '5695298', 'Nathalia', 'Rotela', 'nathirotela5@gmail.com', '$2y$10$ucwpGwxmV1NzLmUV3Gevyuh/rgzgjoX.yJ1Y2WF8Pyv6lOFjFdLhO', '2025-10-27 21:42:19'),
(4, '12345678', 'Joaquin', 'Profe', 'profejoaquin@gmail.com', '$2y$10$jPCRYlWnMSMJ18b4BrCmUeP.d77FhtcSxWyYEHSHKqR4DfY6fVILO', '2025-10-28 00:34:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `sala_id` int(11) DEFAULT NULL,
  `tipo` varchar(60) NOT NULL,
  `marca` varchar(80) DEFAULT NULL,
  `modelo` varchar(120) DEFAULT NULL,
  `nro_serie` varchar(120) DEFAULT NULL,
  `serial_interno` varchar(32) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'bueno',
  `prestado` tinyint(1) NOT NULL DEFAULT 0,
  `con_reporte` tinyint(1) NOT NULL DEFAULT 0,
  `detalles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detalles`)),
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `en_mantenimiento` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`id`, `area_id`, `sala_id`, `tipo`, `marca`, `modelo`, `nro_serie`, `serial_interno`, `estado`, `prestado`, `con_reporte`, `detalles`, `creado_en`, `actualizado_en`, `en_mantenimiento`) VALUES
(1, 2, 1, 'Proyector', 'Epson', 'C0-W01', NULL, '1fd38f17793a', 'bueno', 0, 0, NULL, '2025-10-27 18:06:38', '2025-10-28 02:50:39', 0),
(2, 2, 1, 'Monitor', 'AOC', '', NULL, 'c313dbed9f5d', 'bueno', 0, 0, NULL, '2025-10-27 18:07:51', '2025-10-28 02:50:33', 0),
(3, 2, 1, 'Teclado', 'SATE', '', NULL, 'c410cf62a4b1', 'Disponible', 0, 0, NULL, '2025-10-27 18:08:09', NULL, 0),
(4, 2, 1, 'Mouse', 'SATE', '', NULL, '6d13f0a478c9', 'Disponible', 0, 0, NULL, '2025-10-27 18:08:26', NULL, 0),
(5, 2, 1, 'Tele', '', '', NULL, 'c597582e367c', 'bueno', 0, 0, NULL, '2025-10-27 21:35:24', '2025-10-28 00:02:40', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` int(11) NOT NULL,
  `ci` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `ci`, `nombre`, `apellido`, `email`, `password_hash`, `creado_en`) VALUES
(1, '5534142', 'Joaquín', 'Ayala', 'isrraesp19@gmail.com', '$2y$10$t0wXgPnizOJQ21N7STH1YODG0LCyGBqVMjVQTJH2Ru6iU0UGhZvH6', '2025-10-27 19:17:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_cesiones`
--

CREATE TABLE `historial_cesiones` (
  `id` int(11) NOT NULL,
  `prestamo_id` int(11) NOT NULL,
  `de_docente_id` int(11) NOT NULL,
  `a_docente_id` int(11) NOT NULL,
  `observacion` text DEFAULT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_cesiones`
--

INSERT INTO `historial_cesiones` (`id`, `prestamo_id`, `de_docente_id`, `a_docente_id`, `observacion`, `fecha`) VALUES
(1, 12, 2, 1, NULL, '2025-10-27 21:02:00'),
(2, 15, 3, 2, NULL, '2025-10-27 23:15:04'),
(3, 16, 3, 2, NULL, '2025-10-27 23:50:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimientos`
--

CREATE TABLE `mantenimientos` (
  `id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `reporte_id` int(11) NOT NULL,
  `destino` varchar(255) DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `fecha_envio` datetime NOT NULL,
  `fecha_devolucion` datetime DEFAULT NULL,
  `solucionado` tinyint(1) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(128) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `table_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expires_at`, `used`, `created_at`, `table_name`) VALUES
(1, 1, '44cb5e7fbe8b743e88d8fd23ce474c796a0fe964d62dcc1ce23289e02974022b', '2025-10-27 20:50:35', 1, '2025-10-27 16:20:35', 'estudiantes'),
(2, 1, '0802ee066b5776f2ede6ba1a454fad05f45d2de347048490afbdb90b44c4ebb6', '2025-10-27 21:58:35', 1, '2025-10-27 17:28:35', 'docentes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets_estudiantes`
--

CREATE TABLE `password_resets_estudiantes` (
  `id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `estudiante_id` int(11) DEFAULT NULL,
  `docente_id` int(11) DEFAULT NULL,
  `usuario_actual_id` int(11) DEFAULT NULL,
  `fecha_entrega` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_devolucion` datetime DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'activo',
  `observacion` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `motivo_cancelacion` text DEFAULT NULL,
  `devuelto_por_tercero_nombre` varchar(120) DEFAULT NULL,
  `devuelto_por_tercero_ci` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`id`, `equipo_id`, `estudiante_id`, `docente_id`, `usuario_actual_id`, `fecha_entrega`, `fecha_devolucion`, `estado`, `observacion`, `creado_en`, `motivo_cancelacion`, `devuelto_por_tercero_nombre`, `devuelto_por_tercero_ci`) VALUES
(1, 1, 1, NULL, NULL, '2025-10-27 16:18:14', NULL, 'cancelado', '', '2025-10-27 19:18:14', NULL, NULL, NULL),
(2, 1, 1, NULL, NULL, '2025-10-27 17:32:39', NULL, 'cancelado', '', '2025-10-27 20:32:39', NULL, NULL, NULL),
(3, 1, 1, NULL, 1, '2025-10-27 17:33:23', '2025-10-27 17:36:11', 'devuelto', '', '2025-10-27 20:33:19', NULL, NULL, NULL),
(4, 1, 1, NULL, 1, '2025-10-27 17:54:32', NULL, 'cancelado', 'sala de informatica, con profe santiago\nRechazado: ', '2025-10-27 20:54:26', NULL, NULL, NULL),
(8, 5, NULL, 2, 2, '2025-10-27 18:43:47', NULL, 'cancelado', '', '2025-10-27 21:43:47', NULL, NULL, NULL),
(9, 5, NULL, 2, 2, '2025-10-27 20:55:07', NULL, 'cancelado', '', '2025-10-27 23:55:07', NULL, NULL, NULL),
(10, 5, NULL, 2, 2, '2025-10-27 20:56:10', NULL, 'cancelado', '', '2025-10-27 23:56:10', NULL, NULL, NULL),
(11, 5, NULL, 2, 2, '2025-10-27 20:57:36', NULL, 'cancelado', '', '2025-10-27 23:57:36', NULL, NULL, NULL),
(12, 5, NULL, 1, 1, '2025-10-27 20:59:58', NULL, 'cancelado', '', '2025-10-27 23:59:53', NULL, NULL, NULL),
(13, 1, NULL, 3, 3, '2025-10-27 21:55:27', NULL, 'cancelado', '\nRechazado: ', '2025-10-28 00:55:18', NULL, NULL, NULL),
(14, 1, NULL, 3, 3, '2025-10-27 23:13:43', NULL, 'cancelado', '', '2025-10-28 02:13:43', NULL, NULL, NULL),
(15, 1, NULL, 2, 2, '2025-10-27 23:14:35', NULL, 'cancelado', '', '2025-10-28 02:14:30', NULL, NULL, NULL),
(16, 2, NULL, 2, 2, '2025-10-27 23:49:41', NULL, 'cancelado', '', '2025-10-28 02:49:31', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_fallos`
--

CREATE TABLE `reporte_fallos` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipo_fallo` varchar(255) NOT NULL,
  `descripcion_fallo` text NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `nombre_usuario_reportante` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'admin'),
(2, 'bibliotecaria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salas`
--

CREATE TABLE `salas` (
  `id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salas`
--

INSERT INTO `salas` (`id`, `area_id`, `nombre`, `descripcion`, `creado_en`) VALUES
(1, 2, 'Biblioteca', '', '2025-10-27 17:53:41'),
(2, 2, 'Laboratorio', '', '2025-10-27 17:57:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `ci` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `ci`, `email`, `nombre`, `password_hash`, `role_id`, `creado_en`) VALUES
(5, '123456', 'isaacmiranda290@gmail.com', 'Isaac Miranda', '$2a$12$obdsmKZKP18niFoFF8iG6eV7y6APB2Q3GjQXPdC5dvb5rMKZkwyuu', 2, '2025-09-14 19:01:44'),
(7, '7400254', 'kevinalegre181@gmail.com', 'kevin', '$2y$10$uQL8gx.A7r.TgSjhHp9V1OernamyNR4kRtDKVZLX/WaenuS1eYRne', 1, '2025-09-19 18:55:41'),
(10, '5920912', 'perlaj34@gamil.com', 'richar', '$2y$10$ZSCq4gccx6biyaA1DIzR2eoWYX5MtoIjLAsiaqfBP6/uucowyKnBy', 2, '2025-09-29 17:43:29');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cesiones`
--
ALTER TABLE `cesiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prestamo_id` (`prestamo_id`),
  ADD KEY `de_estudiante_id` (`cedente_id`),
  ADD KEY `a_estudiante_id` (`a_docente_id`),
  ADD KEY `a_docente_id` (`a_docente_id`);

--
-- Indices de la tabla `componentes`
--
ALTER TABLE `componentes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_equipo_tipo` (`equipo_id`,`tipo`);

--
-- Indices de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_devoluciones_prestamo` (`prestamo_id`),
  ADD KEY `fk_devoluciones_equipo` (`equipo_id`),
  ADD KEY `fk_devoluciones_estudiante` (`estudiante_id`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ci` (`ci`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_serial_interno` (`serial_interno`),
  ADD KEY `fk_equipos_sala` (`sala_id`),
  ADD KEY `idx_tipo_estado` (`tipo`,`estado`),
  ADD KEY `idx_area_sala` (`area_id`,`sala_id`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ci` (`ci`);

--
-- Indices de la tabla `historial_cesiones`
--
ALTER TABLE `historial_cesiones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_equipo` (`equipo_id`),
  ADD KEY `fk_mantenimientos_reporte` (`reporte_id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `password_resets_estudiantes`
--
ALTER TABLE `password_resets_estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante_id` (`estudiante_id`),
  ADD KEY `token` (`token`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_equipo_estado` (`equipo_id`,`estado`),
  ADD KEY `idx_estudiante` (`estudiante_id`),
  ADD KEY `docente_id` (`docente_id`);

--
-- Indices de la tabla `reporte_fallos`
--
ALTER TABLE `reporte_fallos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_area_sala` (`area_id`,`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ci` (`ci`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `cesiones`
--
ALTER TABLE `cesiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `componentes`
--
ALTER TABLE `componentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `historial_cesiones`
--
ALTER TABLE `historial_cesiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `password_resets_estudiantes`
--
ALTER TABLE `password_resets_estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `reporte_fallos`
--
ALTER TABLE `reporte_fallos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `salas`
--
ALTER TABLE `salas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cesiones`
--
ALTER TABLE `cesiones`
  ADD CONSTRAINT `cesiones_ibfk_1` FOREIGN KEY (`prestamo_id`) REFERENCES `prestamos` (`id`),
  ADD CONSTRAINT `cesiones_ibfk_2` FOREIGN KEY (`cedente_id`) REFERENCES `docentes` (`id`),
  ADD CONSTRAINT `cesiones_ibfk_3` FOREIGN KEY (`a_docente_id`) REFERENCES `docentes` (`id`);

--
-- Filtros para la tabla `componentes`
--
ALTER TABLE `componentes`
  ADD CONSTRAINT `fk_componentes_equipo` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD CONSTRAINT `fk_devoluciones_equipo` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_devoluciones_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_devoluciones_prestamo` FOREIGN KEY (`prestamo_id`) REFERENCES `prestamos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `fk_equipos_area` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_equipos_sala` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD CONSTRAINT `fk_mantenimientos_equipo` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mantenimientos_reporte` FOREIGN KEY (`reporte_id`) REFERENCES `reporte_fallos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `password_resets_estudiantes`
--
ALTER TABLE `password_resets_estudiantes`
  ADD CONSTRAINT `password_resets_estudiantes_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `fk_prestamo_docente` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prestamo_equipo` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prestamo_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `reporte_fallos`
--
ALTER TABLE `reporte_fallos`
  ADD CONSTRAINT `reporte_fallos_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id`);

--
-- Filtros para la tabla `salas`
--
ALTER TABLE `salas`
  ADD CONSTRAINT `fk_salas_area` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `rol-usuario` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
