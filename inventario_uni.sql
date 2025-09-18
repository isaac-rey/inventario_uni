-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-09-2025 a las 21:59:23
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
(1, 1, 'HDMI', '', '', NULL, 'bueno', '', '2025-08-31 16:39:43'),
(2, 1, 'Cable de alimentacion', '', '', NULL, 'bueno', '', '2025-08-31 16:40:08'),
(3, 2, 'cable de alimentacion', '', '', NULL, 'bueno', '', '2025-09-01 20:08:17'),
(4, 2, 'control', '', '', NULL, 'bueno', '', '2025-09-01 20:08:22');

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
  `detalles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detalles`)),
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`id`, `area_id`, `sala_id`, `tipo`, `marca`, `modelo`, `nro_serie`, `serial_interno`, `estado`, `prestado`, `detalles`, `creado_en`, `actualizado_en`) VALUES
(1, 1, NULL, 'Proyector', 'Epson', 'C0-W02', NULL, 'dc156bba7ec4', 'bueno', 0, NULL, '2025-08-31 16:34:52', '2025-09-01 19:57:56'),
(2, 1, NULL, 'TV 43', 'tokyo', '', NULL, '16338c34276c', 'bueno', 0, NULL, '2025-09-01 20:07:57', '2025-09-01 20:09:32');

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
(1, '1261571', 'Robin', 'De Jesus', '', '$2y$10$X95Ku7mUa9SdZSGov.6WieKdAMiRch5nJYw3o4qwokDICksaqSCMm', '2025-09-01 05:53:38'),
(2, '4659300', 'isaac', 'miranda', 'isaacmiranda290@gmail.com', '$2y$10$geB.Bipw7cW328EiLOD6d.h1VGTpEjpyeBEu1NMaq5zhTcd/5rDlq', '2025-09-15 22:04:29');

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
(10, 6, '10fc39ae992bdd86c41ead53600cbf4c6628c68502ad62e18f9edc2899bd91d1', '2025-09-15 23:21:56', 0, '2025-09-15 17:51:56'),
(11, 6, '77c9dcb916686e679881ba4693e25ef56dd2dae017867cde04513fb91eb0b486', '2025-09-15 23:24:19', 0, '2025-09-15 17:54:19'),
(12, 6, '72349c9cac679798f59858b94dc9d329b263c6cc0307308b9725f2bfcba30d32', '2025-09-15 23:24:28', 0, '2025-09-15 17:54:28'),
(13, 6, '9875ee4e3621545560767146280899423a5525c18894fdfca3a6d6a86e26fde4', '2025-09-15 23:25:48', 0, '2025-09-15 17:55:48'),
(14, 5, '77d60ec8e6e7a7104bf7d6a42b667a23cb9435d8defd85ffd0a394a74ba76a45', '2025-09-15 23:25:56', 0, '2025-09-15 17:55:56'),
(15, 5, '326b95673b3fd2d87180f4f0e58164f21015a8dd14f059b15ce2269b04f1466c', '2025-09-15 23:26:21', 0, '2025-09-15 17:56:21'),
(16, 6, '2a59713f94f458ef94104a23c71c75365e79f51fe4abd049714bfcec3dce4b2c', '2025-09-15 23:26:36', 1, '2025-09-15 17:56:36'),
(17, 6, '4f8489c226758041345401a8235229c8504e6937b7b0236f6a3579ac1a9398d2', '2025-09-15 23:30:30', 0, '2025-09-15 18:00:31'),
(18, 6, 'de08e2a6279ca601a7423fd028c14cd8e993f7557383ff67ecf1ae701b3e40b8', '2025-09-15 23:30:44', 1, '2025-09-15 18:00:44'),
(19, 6, 'f01883e458437840d01b6aa86d37ea249e8ecc3292e033b61c0cb93de29e86d7', '2025-09-15 23:48:33', 0, '2025-09-15 18:18:33'),
(20, 6, '3487c3cb286b0d7722a4431af5daade68bdabc068c11dc7da0964b1fd791f2c0', '2025-09-15 23:56:39', 0, '2025-09-15 18:26:39'),
(21, 2, '31bc6bf1c6c05edcca5e636a70946393dcc2ecca62b45bdb3a98099e4a8268aa', '2025-09-16 00:35:14', 0, '2025-09-15 19:05:14'),
(22, 2, 'e69753d05fcebeaa8e012f324754d70d463852f42a585beae73264857a294916', '2025-09-16 00:39:08', 0, '2025-09-15 19:09:08'),
(23, 2, '77935b4f462ab7d08b995eb9b843fba039ed7a7b12e8f60f5e2814b48b77435e', '2025-09-16 00:49:06', 0, '2025-09-15 19:19:06'),
(24, 2, '0122de51dc3f67099b05396122558558b9047389947135c1952798f352681607', '2025-09-16 00:49:17', 0, '2025-09-15 19:19:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `fecha_entrega` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_devolucion` datetime DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'activo',
  `observacion` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`id`, `equipo_id`, `estudiante_id`, `fecha_entrega`, `fecha_devolucion`, `estado`, `observacion`, `creado_en`) VALUES
(1, 1, 1, '2025-09-01 03:17:11', '2025-09-01 03:18:15', 'devuelto', 'responsable llevo a sala del segundo año de ingeneria informatica', '2025-09-01 06:17:11'),
(2, 1, 1, '2025-09-01 03:52:31', '2025-09-01 03:53:17', 'devuelto', 'clase segundo año de informática', '2025-09-01 06:52:31'),
(3, 1, 1, '2025-09-01 16:56:51', '2025-09-01 16:57:56', 'devuelto', 'Segundo año de informática', '2025-09-01 19:56:51'),
(4, 2, 1, '2025-09-01 17:09:19', '2025-09-01 17:09:32', 'devuelto', '', '2025-09-01 20:09:19');

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
  `ci` varchar(20) NOT NULL,
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
(1, '1234567', '', 'Admin Universidad', '$2y$10$sxagWvDotcTTAFgZiHzFfu//oVYAN5d7VzmGA8IY2TWFE2leciP7.', 1, '2025-08-31 16:12:00'),
(2, '8697131', '', 'Bibliotecaria Test', '$2y$10$sxagWvDotcTTAFgZiHzFfu//oVYAN5d7VzmGA8IY2TWFE2leciP7.', 1, '2025-08-31 16:16:56'),
(3, '4659300', '', 'isaac', '$2y$10$GJFx2kXehsvYOyKh.wlgUuhuNY5fIMj.QYE7MDHH/FLbb.jLmnYQC', 1, '2025-09-03 22:44:54'),
(5, '123456', 'isaacmiranda290@gmail.com', 'Isaac Miranda', '$2y$10$qMuXkE8eVvBxiMHZtXe09.PiJ1jnteePzzUBr9LcsCSpR.YXP6Kxm', 2, '2025-09-14 16:01:44'),
(6, '1234', 'pmalo2570@gmail.com', 'Hola', '$2y$10$AlYKISm0/LPmhgJdH/ihsOyTYu9LGVEVbr0xCHj392fXk.fg.v68.', 2, '2025-09-15 20:51:37');

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
-- Indices de la tabla `componentes`
--
ALTER TABLE `componentes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_equipo_tipo` (`equipo_id`,`tipo`);

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
  ADD KEY `idx_estudiante` (`estudiante_id`);

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
-- AUTO_INCREMENT de la tabla `componentes`
--
ALTER TABLE `componentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

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
-- Filtros para la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `fk_prestamo_equipo` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_prestamo_est` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `salas`
--
ALTER TABLE `salas`
  ADD CONSTRAINT `fk_salas_area` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
