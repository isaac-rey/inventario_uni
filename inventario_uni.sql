-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-10-2025 a las 22:20:35
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
(1, 'Biblioteca', '2025-08-31 16:25:35'),
(2, 'Secretaría', '2025-08-31 16:25:35'),
(3, 'Sala Informática', '2025-08-31 16:25:35');

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
(1, 7, 'Editó el usuario con ID 11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-04 13:00:54'),
(2, 7, 'Elimino el usuario con ID 11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-04 13:01:31'),
(3, 7, 'Registró al nuevo usuario con ID 12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-04 13:01:58'),
(9, 7, 'Editó los datos del estudiante ID 6 (prueba2 la prueba).', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 00:44:09'),
(10, 7, 'Reportó un fallo para el equipo ID 5 (TV 43 tokyo tffe3). Fallo: hv. Descripción: hc...', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 00:45:15'),
(11, 7, 'Registró el préstamo del equipo ID 6 (Proyector Epson algo) al estudiante Nathi Rotela (CI: 5695298).', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 01:36:02'),
(12, 7, 'Registró la devolución del equipo ID 6 (Proyector Epson algo). Responsable: Nathi. Devuelto por: Tercero: Pedro (CI: 87654321).', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 01:38:27'),
(13, 7, 'Registró el préstamo del equipo ID 7 (Proyector 2 Tokyo algo) al estudiante hola como estas (CI: 4567894).', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 15:56:37'),
(14, 7, 'Registró la devolución del equipo ID 7 (Proyector 2 Tokyo algo). Responsable: hola. Devuelto por: Tercero: gilberto (CI: 789654123).', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-09 15:57:11');

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
(1, 32, 2, 3, '2025-10-06 23:07:26', '2025-10-07 04:07:43', 'aceptada', NULL),
(3, 32, 3, 2, '2025-10-06 23:26:08', '2025-10-07 04:26:18', 'aceptada', NULL),
(4, 32, 2, 3, '2025-10-06 23:31:40', '2025-10-07 04:32:02', 'aceptada', NULL),
(34, 80, 2, 3, '2025-10-09 15:54:19', '2025-10-09 20:54:22', 'aceptada', NULL);

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
(2, '5695298', 'Nathalia', 'Prueba', 'nathirotela5@gmail.com', '$2y$10$k/hptHSGqW1wJ2CjG98H6uw3GJ3NwEmG7NPTqg724RhM8aV4mjOWy', '2025-10-07 00:25:34'),
(3, '123456', 'César', 'Algo', 'nathaliarotela5@gmail.com', '$2y$10$Oue8xWHlZgGZ6KugKEAwB.B7ZuTAPZFvhbdKfbmmfxin/3oL8uVL.', '2025-10-09 18:38:54');

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
(5, 1, 2, 'TV 43', 'tokyo', 'tffe3', NULL, '23b47cd5e388', 'bueno', 0, 0, NULL, '2025-09-29 13:22:56', '2025-10-09 05:21:47', 0),
(6, 1, 1, 'Proyector', 'Epson', 'algo', NULL, 'bdea30c2582c', 'bueno', 0, 0, NULL, '2025-10-08 02:50:38', '2025-10-09 18:54:47', 0),
(7, 3, 4, 'Proyector 2', 'Tokyo', 'algo', NULL, '58ad8b18cd04', 'disponible', 0, 0, NULL, '2025-10-08 22:39:36', '2025-10-09 18:57:11', 0);

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
(5, '4567894', 'hola', 'como estas', 'cualquiercosa@gmail.com', '$2y$10$fR2sLhhixQq/iOjzW4L68eGsbZ4IM/SLEwwxk/qCBQdibG1mcLB/C', '2025-09-29 13:20:38'),
(6, '7894561', 'prueba2', 'la prueba', 'prueba@gmail.com', '$2y$10$WapLtCIvGwlwrAYHPA8COeZiCRCU6LlF93acNLjwEQ7MpHjmaGhs.', '2025-09-29 13:32:03'),
(7, '789654123', 'gilberto', 'hola', 'hdjfikjsahdfkjha@gmail.com', '$2y$10$Zw2SMQJDYhm4O1DbOseuve7LLY4x3k/M49te/mEO.MdiPzw51s3Re', '2025-09-29 14:17:50'),
(8, '2345678', 'Luis', 'Riquelme', 'alexandergodeater@gmail.com', '$2y$10$D1cTd.BwtTFaoLDqIPaXyeWmxhQDMSwU1NaGP11VBvoKyMvOEvp4O', '2025-09-30 19:13:32'),
(9, '87654321', 'Pedro', 'Gonzalez', 'pedro@gmail.com', '$2y$10$AJxjd/5G42pNDiRqYfPE0.qD7//fWKSxvu5ePBaEmn1HrLfJYS6MS', '2025-09-30 21:49:40'),
(10, '5695298', 'Nathi', 'Rotela', 'nathirotela5@gmail.com', '$2y$10$VOIEQ4f8xKPAxS2opWVLCeuMEJBmDmW/FkzRLaPuo7TJt6Aziz0jS', '2025-10-07 03:05:31'),
(11, '123456', 'Alguien', 'De Pueba', 'nathaliartela5@gmail.com', '$2y$10$TQrPPVKULbAzV1gNPa785uQ.E0RunbMcKtGcI4iyvDLwX9EgT5tT2', '2025-10-07 03:42:12');

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
(4, 63, 2, 3, NULL, '2025-10-08 00:44:22'),
(5, 64, 2, 3, NULL, '2025-10-08 00:49:28'),
(6, 63, 3, 2, NULL, '2025-10-08 00:53:42'),
(7, 63, 2, 3, NULL, '2025-10-08 00:54:30'),
(15, 73, 2, 3, NULL, '2025-10-09 02:19:59'),
(16, 74, 3, 2, NULL, '2025-10-09 13:57:53'),
(17, 75, 3, 2, NULL, '2025-10-09 14:51:39'),
(18, 77, 3, 2, NULL, '2025-10-09 15:10:29'),
(19, 79, 3, 2, NULL, '2025-10-09 15:36:26'),
(20, 80, 2, 3, NULL, '2025-10-09 15:54:22');

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

--
-- Volcado de datos para la tabla `mantenimientos`
--

INSERT INTO `mantenimientos` (`id`, `equipo_id`, `usuario_id`, `reporte_id`, `destino`, `motivo`, `fecha_envio`, `fecha_devolucion`, `solucionado`, `observaciones`, `creado_en`) VALUES
(12, 7, 7, 7, 'algun lugar', 'sbdskh', '2025-10-09 00:00:00', '2025-10-08 00:00:00', 1, NULL, '2025-10-08 22:45:27');

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
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expires_at`, `used`, `created_at`) VALUES
(1, 5, '53f030eec6cede55ee5dd872f3bb762ff7cf33e0a9d5f8bf10a98833113e0fc9', '2025-09-14 18:32:58', 0, '2025-09-14 13:02:58'),
(2, 5, 'd1f7170edfbdd455fd0aa76dfb73a6be00498ff89d5abb387ffb9d69a5eccf15', '2025-09-14 18:45:54', 0, '2025-09-14 13:15:54'),
(3, 5, '75e3edaf10329b5974f052474bd8789324dd3a8a9fbfaa849bbf71441844a14b', '2025-09-15 22:36:46', 0, '2025-09-15 17:06:46'),
(4, 5, '99d1acacca092867d963f68ad18e9f0fb92e2e26cf8ef3a1195ac73be7f8b029', '2025-09-15 22:37:20', 0, '2025-09-15 17:07:20'),
(5, 5, 'd2b5ad2e7caa60cbcd0601dd65177db9654773257d4b4ba3038ebe4c179695ad', '2025-09-15 22:49:03', 0, '2025-09-15 17:19:03'),
(6, 5, 'f508175d9330cbe5cf753d52378c7bf5cbaf960002c830ea01831a1b684a7729', '2025-09-15 23:01:04', 0, '2025-09-15 17:31:04'),
(7, 5, '0bf21aa776032f264e476671a23d976a83e2d922c19d090e931351879836189b', '2025-09-15 23:04:37', 0, '2025-09-15 17:34:37'),
(8, 5, '5762ae3f704f96592271b366c73be7e1909a9145724325b20900925b6f159fb3', '2025-09-15 23:15:55', 0, '2025-09-15 17:45:55'),
(9, 5, 'cd69ad26c21cdbf7d1069347447ae04297201b1e777cc224079582e36e860905', '2025-09-15 23:16:02', 0, '2025-09-15 17:46:02'),
(14, 5, '77d60ec8e6e7a7104bf7d6a42b667a23cb9435d8defd85ffd0a394a74ba76a45', '2025-09-15 23:25:56', 0, '2025-09-15 17:55:56'),
(15, 5, '326b95673b3fd2d87180f4f0e58164f21015a8dd14f059b15ce2269b04f1466c', '2025-09-15 23:26:21', 0, '2025-09-15 17:56:21'),
(26, 7, '1651419053d5a8b5a846fa50fa3af9c92f97caff812d70759843e50ab782514f', '2025-09-29 17:01:20', 1, '2025-09-29 11:31:20');

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
(13, 5, 5, NULL, 2, '2025-09-29 10:25:22', '2025-09-29 10:25:40', 'devuelto', 'Sala de informática', '2025-09-29 13:25:22', NULL, NULL, NULL),
(14, 5, 5, NULL, 2, '2025-09-29 10:28:22', '2025-09-29 10:39:19', 'devuelto', '', '2025-09-29 13:28:22', NULL, NULL, NULL),
(15, 5, 8, NULL, 2, '2025-09-30 16:20:14', '2025-09-30 17:48:54', 'devuelto', 'Sala Informatica', '2025-09-30 19:20:14', NULL, NULL, NULL),
(16, 5, 8, NULL, 2, '2025-09-30 17:52:50', '2025-09-30 17:58:12', 'devuelto', '', '2025-09-30 20:52:50', NULL, NULL, NULL),
(17, 5, 8, NULL, 2, '2025-09-30 17:58:34', '2025-09-30 17:59:32', 'devuelto', '', '2025-09-30 20:58:34', NULL, NULL, NULL),
(18, 5, 8, NULL, 2, '2025-09-30 17:59:55', '2025-09-30 18:01:55', 'devuelto', 'Sala Informatica', '2025-09-30 20:59:55', NULL, NULL, NULL),
(19, 5, 8, NULL, 2, '2025-09-30 18:05:10', '2025-09-30 18:13:26', 'devuelto', 'Sala Informatica', '2025-09-30 21:05:10', NULL, NULL, NULL),
(20, 5, 8, NULL, 2, '2025-09-30 18:13:42', '2025-09-30 18:20:02', 'devuelto', '', '2025-09-30 21:13:42', NULL, NULL, NULL),
(21, 5, 8, NULL, 2, '2025-09-30 18:20:25', '2025-09-30 18:20:30', 'devuelto', 'Sala 205', '2025-09-30 21:20:25', NULL, NULL, NULL),
(22, 5, 8, NULL, 2, '2025-09-30 18:22:07', '2025-09-30 18:51:36', 'devuelto', '', '2025-09-30 21:22:07', NULL, NULL, NULL),
(23, 5, 9, NULL, 2, '2025-09-30 18:51:47', '2025-09-30 19:50:28', 'devuelto', '', '2025-09-30 21:51:47', NULL, NULL, NULL),
(24, 5, 9, 2, 2, '2025-09-30 19:54:12', '2025-09-30 20:11:05', 'devuelto', '', '2025-09-30 22:54:12', NULL, NULL, NULL),
(25, 5, 9, 2, 2, '2025-09-30 20:11:23', '2025-10-02 17:01:55', 'devuelto', '', '2025-09-30 23:11:23', NULL, NULL, NULL),
(26, 5, 8, NULL, 2, '2025-10-02 17:07:18', '2025-10-02 17:50:29', 'devuelto', 'Sala de Profesores', '2025-10-02 20:07:18', NULL, NULL, NULL),
(32, 5, NULL, 3, 3, '2025-10-06 22:32:27', '2025-10-06 23:36:00', 'devuelto', '', '2025-10-07 01:32:27', NULL, NULL, NULL),
(71, 6, 10, NULL, NULL, '2025-10-09 01:36:02', '2025-10-09 01:38:27', 'devuelto', 'Queria hacer una última prueba', '2025-10-09 04:36:02', NULL, 'Pedro', '87654321'),
(80, 6, NULL, 3, 3, '2025-10-09 15:49:18', '2025-10-09 15:54:47', 'devuelto', 'prueba fuego', '2025-10-09 18:46:08', NULL, NULL, NULL),
(81, 7, 5, NULL, NULL, '2025-10-09 15:56:37', '2025-10-09 15:57:11', 'devuelto', 'Para utilizar', '2025-10-09 18:56:37', NULL, 'gilberto', '789654123');

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

--
-- Volcado de datos para la tabla `reporte_fallos`
--

INSERT INTO `reporte_fallos` (`id`, `fecha`, `tipo_fallo`, `descripcion_fallo`, `id_equipo`, `nombre_usuario_reportante`) VALUES
(7, '0000-00-00', 'hsfhs', 'Está muy mal', 7, 'kevin');

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
(1, 1, 'Estantería A', NULL, '2025-08-31 16:25:36'),
(2, 1, 'Depósito Biblioteca', NULL, '2025-08-31 16:25:36'),
(3, 2, 'Mesa Principal', NULL, '2025-08-31 16:25:36'),
(4, 3, 'Laboratorio 1', NULL, '2025-08-31 16:25:36'),
(5, 3, 'Laboratorio 2', NULL, '2025-08-31 16:25:36');

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
(7, '7400254', 'kevinalegre181@gmail.com', 'kevin', '$2y$10$EfpsXdbiRfwzC1GA6D9NieNfBdXgsZbMwG4SKQsiVslUrjKJjP.ka', 1, '2025-09-19 18:55:41'),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `cesiones`
--
ALTER TABLE `cesiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `componentes`
--
ALTER TABLE `componentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `historial_cesiones`
--
ALTER TABLE `historial_cesiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT de la tabla `reporte_fallos`
--
ALTER TABLE `reporte_fallos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `salas`
--
ALTER TABLE `salas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- Filtros para la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

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
