-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-11-2025 a las 02:11:30
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
  `tipo_accion` varchar(50) DEFAULT NULL,
  `ip_usuario` varchar(250) NOT NULL,
  `user_agent` varchar(250) NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id`, `usuario_id`, `accion`, `tipo_accion`, `ip_usuario`, `user_agent`, `fecha`) VALUES
(1, 7, 'Registró una nueva sala con ID 1 y Nombre: Biblioteca', NULL, '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 14:53:41'),
(2, 7, 'Registró una nueva sala con ID 2 y Nombre: Laboratorio', NULL, '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 14:57:59'),
(3, 7, 'Agregó el componente: Zapatilla   (bueno) al equipo ID 1 (Proyector Epson C0-W01).', NULL, '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 15:07:02'),
(4, 7, 'Agregó el componente: Fuente   (bueno) al equipo ID 1 (Proyector Epson C0-W01).', NULL, '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 15:07:14'),
(5, 7, 'Agregó el componente: HDMI   (bueno) al equipo ID 1 (Proyector Epson C0-W01).', NULL, '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 15:07:23'),
(6, 7, 'Agregó el componente: Control remoto   (bueno) al equipo ID 1 (Proyector Epson C0-W01).', NULL, '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 15:07:29'),
(7, 7, 'Registró un nuevo estudiante con ID 1 y Nombre: Joaquín Ayala', NULL, '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 16:17:19'),
(8, 7, 'Cancelación de préstamo activo - Préstamo ID 6. Motivo: .', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:24:28'),
(9, 7, 'Aprobó y registró el préstamo del equipo ID 2 (Monitor AOC ) al docente \'clan rotela\'.', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:24:44'),
(10, 7, 'Aprobó la devolución del préstamo ID 7 para el equipo ID 2. El activo vuelve al inventario.', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:25:08'),
(11, 7, 'Aprobó y registró el préstamo del equipo ID 2 (Monitor AOC ) al docente \'clan rotela\'.', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:25:33'),
(12, 7, 'Aprobó la devolución del préstamo ID 8 para el equipo ID 2. El activo vuelve al inventario.', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:26:35'),
(13, 7, 'Aprobó y registró el préstamo del equipo ID 1 (Proyector Epson C0-W01) al docente \'clan rotela\'.', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:50:38'),
(14, 7, 'Aprobó la devolución del préstamo ID 9 para el equipo ID 1. El activo vuelve al inventario.', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:52:15'),
(15, 7, 'Aprobó y registró el préstamo del equipo ID 1 (Proyector Epson C0-W01) al docente \'clan rotela\'.', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:52:39'),
(16, 7, 'Aprobó la devolución del préstamo ID 10 para el equipo ID 1. El activo vuelve al inventario.', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:54:29'),
(17, 7, 'Aprobó y registró el préstamo del equipo ID 1 (Proyector Epson C0-W01) al docente \'clan rotela\'.', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:55:56'),
(18, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 06:33:38'),
(19, 7, 'Ha cancelado de préstamo activo del equipo ID 1 (Proyector Epson C0-W01) al docente \'clan rotela\'', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 06:33:55'),
(20, 7, 'Reportó un fallo para el equipo ID 1 (Proyector Epson C0-W01). Fallo: problema al encender. Descripción: cuando se intenta encender, queda colgado...', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 06:42:13'),
(21, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 09:42:36'),
(22, 7, 'Registró un nuevo Docente ID 3: Cristhian Carrera (CI: 6118463).', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 09:53:10'),
(23, 7, 'Editó datos de Docente ID 3 (Cristhian Carrera).', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 10:06:19'),
(24, 7, 'Editó datos de Docente ID 3 (Cristhian Carrera).', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 10:06:31'),
(25, 7, 'Editó los datos del estudiante ID 1 (Joaquín Ayala).', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 10:06:55'),
(26, 7, 'Registró un nuevo estudiante con ID 2, Nombre: Pcc Comando da capital, CI: 4678905, Teléfono: 0983234665', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 10:09:30'),
(27, 7, 'Eliminó al estudiante ID 2 (Pcc Comando da capital).', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 10:09:46'),
(28, 7, 'Editó los datos del estudiante ID 1 (Joaquín Ayala).', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 10:10:21'),
(29, 7, 'Editó los datos del estudiante ID 1 (Joaquín Ayala).', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 10:10:25'),
(30, 7, 'Editó los datos del estudiante ID 1 (Joaquín Ayala).', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 10:10:30'),
(31, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 11:51:16'),
(32, 7, 'Editó datos de Docente ID 1 (santiago caballero).', 'general', '::1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', '2025-10-28 14:09:02'),
(33, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:26:44'),
(34, 7, 'Editó datos de Docente ID 2 (clan rotela).', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:27:14'),
(35, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 22:28:36'),
(36, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 22:29:30'),
(37, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 08:28:01'),
(38, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-29 09:12:12'),
(39, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 09:18:01'),
(40, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 09:34:56'),
(41, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 09:36:16'),
(42, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 09:49:49'),
(43, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Mobile Safari/537.36', '2025-10-29 09:55:51'),
(44, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36', '2025-10-29 09:56:06'),
(45, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 09:57:29'),
(46, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:05:29'),
(47, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:08:41'),
(48, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:19:25'),
(49, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W01) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:26:26'),
(50, 7, 'Ha cancelado de préstamo activo del equipo ID 1 (Proyector Epson C0-W01) al estudiante \'Joaquín Ayala\'', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:27:03'),
(51, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W01) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:27:46'),
(52, 7, ' del equipo ID 1', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:32:05'),
(53, 7, 'Ha cancelado de préstamo activo del equipo ID 1 (Proyector Epson C0-W01) al estudiante \'Joaquín Ayala\'', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:32:09'),
(54, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W01) al docente santiago caballero (C.I: 2901786).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:33:37'),
(55, 7, 'Aprobó la devolución del préstamo del equipo ID 1 (Proyector Epson C0-W01), devuelto por el docente: \'santiago caballero\' (CI: 2901786).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:33:58'),
(56, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W01) al docente santiago caballero (C.I: 2901786).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:36:35'),
(57, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:37:06'),
(58, 7, 'Aprobó el préstamo del equipo ID 3 (Teclado SATE ) al docente Nathalia rotela (C.I: 1234567).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:37:54'),
(59, 7, 'Editó datos de docente ID 3 \'Cristhian Carrera\' (C.I: 6118463) y la contraseña.', 'acción_docentes', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:39:01'),
(60, 7, ' del equipo ID 1. Motivo: no sirve.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:41:13'),
(61, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:57:12'),
(62, 7, 'Aprobó la devolución del préstamo del equipo ID 2 (Monitor AOC ), devuelto por el estudiante: \'Joaquín Ayala\' (CI: 5534142).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:59:22'),
(63, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 10:59:48'),
(64, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:03:46'),
(65, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:03:59'),
(66, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:10:55'),
(67, 7, 'Aprobó la devolución del préstamo del equipo ID 2 (Monitor AOC ), devuelto por el estudiante: \'Joaquín Ayala\' (CI: 5534142).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:11:05'),
(68, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:15:55'),
(69, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:16:05'),
(70, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:21:31'),
(71, 7, 'Aprobó la devolución del préstamo del equipo ID 2 (Monitor AOC ), devuelto por el estudiante: \'Joaquín Ayala\' (CI: 5534142).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:22:12'),
(72, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:22:23'),
(73, 7, ' del equipo ID 2', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:22:31'),
(74, 7, ' del equipo ID 2. Motivo: jkghujguyi.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:23:02'),
(75, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:28:24'),
(76, 7, ' del equipo ID 2. Motivo: no sirve luis, esta bien?.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:36:00'),
(77, 7, ' del equipo ID 2. Motivo: asdfgasg.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:36:45'),
(78, 7, ' del equipo ID 2. Motivo: eratert.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:38:16'),
(79, 7, ' del equipo ID 2. Motivo: hgdfhjgcv.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:48:13'),
(80, 7, ' del equipo ID 2. Motivo: por gay.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:49:43'),
(81, 7, ' del equipo ID 2. Motivo: prueba.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:50:40'),
(82, 7, ' del equipo ID 2. Motivo: lkjshadkjfksad.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 11:56:26'),
(83, 7, ' del equipo ID 2. Motivo: ghhj.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 12:43:13'),
(84, 7, ' del equipo ID 2. Motivo: xzvsdfaseff.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 12:52:18'),
(85, 7, ' del equipo ID 2. Motivo: nooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo lpm.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 12:52:45'),
(86, 7, 'Ha cancelado de préstamo activo del equipo ID 2 (Monitor AOC) al estudiante \'Joaquín Ayala\'', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:00:34'),
(87, 7, 'Envió el Equipo ID 1 (Proyector Epson (Serial: 1fd38f17793a)) a mantenimiento. Destino: compufacil. Motivo: ds.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:23:25'),
(88, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:32:59'),
(89, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:39:12'),
(90, 7, ' del equipo ID 2. Motivo: hora.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:40:18'),
(91, 7, 'Registró un nuevo estudiante con ID 3 - Nombre: richar balbuena (C.I: 5920912).', 'acción_estudiante', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:44:35'),
(92, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:50:16'),
(93, 7, ' del equipo ID 2. Motivo: Malardo.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:50:35'),
(94, 7, 'Aprobó la devolución del préstamo del equipo ID 2 (Monitor AOC ), devuelto por el estudiante: \'Joaquín Ayala\' (CI: 5534142).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:50:49'),
(95, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al docente santiago caballero (C.I: 2901786).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:58:49'),
(96, 7, ' del equipo ID 2. Motivo: Tarde.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:59:05'),
(97, 7, 'Aprobó la devolución del préstamo del equipo ID 2 (Monitor AOC ), devuelto por el docente: \'santiago caballero\' (CI: 2901786).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:59:22'),
(98, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:05:03'),
(99, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:06:32'),
(100, 7, 'Ha cancelado de préstamo activo del equipo ID 3 (Teclado SATE) al docente \'Nathalia rotela\'', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:08:15'),
(101, 7, 'Ha cancelado de préstamo activo del equipo ID 1 (Proyector Epson C0-W01) al docente \'Cristhian Carrera\'', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:08:20'),
(102, 7, 'Registró un nuevo docente ID 4: Joaquin Ayala (CI: 5534142).', 'acción_docentes', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:09:15'),
(103, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:10:05'),
(104, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al docente Joaquin Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:16:08'),
(105, 7, 'Aprobó la devolución del préstamo del equipo ID 2 (Monitor AOC ), devuelto por el docente: \'Joaquin Ayala\' (CI: 5534142).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:18:01'),
(106, 7, 'Editó datos de docente ID 2 \'Nathalia Rotela\' (C.I: 5695298) y la contraseña.', 'acción_docentes', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:19:33'),
(107, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al docente Joaquin Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:20:00'),
(108, 7, 'Editó datos de docente ID 2 \'Nathalia Rotela\' (C.I: 12345678).', 'acción_docentes', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:20:43'),
(109, 7, 'Aprobó el préstamo del equipo ID 3 (Teclado SATE ) al docente Nathalia Rotela (C.I: 12345678).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:25:05'),
(110, 7, 'Aprobó la devolución del préstamo del equipo ID 3 (Teclado SATE ), devuelto por el docente: \'Joaquin Ayala\' (CI: 5534142).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:25:56'),
(111, 7, 'Ha cancelado de préstamo activo del equipo ID 2 (Monitor AOC) al docente \'Nathalia Rotela\'. Motivo: No retiró.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:26:36'),
(112, 7, 'Aprobó el préstamo del equipo ID 3 (Teclado SATE ) al docente Joaquin Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:29:04'),
(113, 7, 'Aprobó la devolución del préstamo del equipo ID 3 (Teclado SATE ), devuelto por el docente: \'Joaquin Ayala\' (CI: 5534142).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:32:08'),
(114, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al docente santiago caballero (C.I: 2901786).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:34:46'),
(115, 7, ' del equipo ID 2. Motivo: qwer.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:35:01'),
(116, 7, 'Aprobó la devolución del préstamo del equipo ID 2 (Monitor AOC ), devuelto por el docente: \'santiago caballero\' (CI: 2901786).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:35:16'),
(117, 7, 'Aprobó el préstamo del equipo ID 3 (Teclado SATE ) al docente Nathalia Rotela (C.I: 12345678).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:56:50'),
(118, 7, 'Aprobó la devolución del préstamo del equipo ID 3 (Teclado SATE ), devuelto por el docente: \'Nathalia Rotela\' (CI: 12345678).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 18:58:37'),
(119, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 19:12:19'),
(120, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Avast/140.0.0.0', '2025-10-29 19:26:55'),
(121, 7, 'Registró un nuevo docente ID 5: Gastón Paredes (CI: 7443749).', 'acción_docentes', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Avast/140.0.0.0', '2025-10-29 19:28:13'),
(122, 7, 'Ha rechazado la solicitud de préstamo del equipo ID 2 (Monitor AOC) al docente \'Gastón Paredes\'. Motivo: Prueba de uso.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Avast/140.0.0.0', '2025-10-29 19:29:36'),
(123, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-30 10:19:31'),
(124, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-30 10:40:58'),
(125, 7, 'Aprobó la devolución del préstamo del equipo ID 2 (Monitor AOC ), devuelto por el estudiante: \'Joaquín Ayala\' (CI: 5534142).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-30 10:45:12'),
(126, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-01 16:25:09'),
(127, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W01) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-01 20:16:39'),
(128, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:02:34'),
(129, 7, ' del equipo ID 1', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:09:52'),
(130, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W01) al docente santiago caballero (C.I: 2901786).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:10:17'),
(131, 7, 'Aprobó la devolución del préstamo del equipo ID 1 (Proyector Epson C0-W01), devuelto por el docente: \'santiago caballero\' (CI: 2901786).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:10:32'),
(132, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W01) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:10:47'),
(133, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W01) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:17:06'),
(134, 7, 'Aprobó la devolución del préstamo del equipo ID 1 (Proyector Epson C0-W01), devuelto por el estudiante: \'Joaquín Ayala\' (CI: 5534142).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:17:10'),
(135, 7, 'Aprobó la devolución del préstamo del equipo ID 1 (Proyector Epson C0-W01), devuelto por el estudiante: \'Joaquín Ayala\' (CI: 5534142).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:17:19'),
(136, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W01) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:17:34'),
(137, 7, 'Aprobó la devolución del préstamo del equipo ID 1 (Proyector Epson C0-W01), devuelto por el estudiante: \'Joaquín Ayala\' (CI: 5534142).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:18:02'),
(138, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:20:34'),
(139, 7, 'Ha cancelado de préstamo activo del equipo ID 2 (Monitor AOC) al estudiante \'Joaquín Ayala\'', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:26:00'),
(140, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:36:04'),
(141, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:45:25'),
(142, 7, 'Aprobó la devolución del préstamo del equipo ID 2 (Monitor AOC ), devuelto por el estudiante: \'Joaquín Ayala\' (CI: 5534142).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:45:49'),
(143, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:48:16'),
(144, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al estudiante Joaquín Ayala (C.I: 5534142).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:48:21'),
(145, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:52:57'),
(146, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al docente santiago caballero (C.I: 2901786).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:54:05'),
(147, 7, 'Aprobó la devolución del préstamo del equipo ID 2 (Monitor AOC ), devuelto por el docente: \'santiago caballero\' (CI: 2901786).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:54:23'),
(148, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al estudiante richar balbuena (C.I: 5920912).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:54:33'),
(149, 7, 'Aprobó la devolución del préstamo del equipo ID 2 (Monitor AOC ), devuelto por el estudiante: \'richar balbuena\' (CI: 5920912).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:55:51'),
(150, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al docente santiago caballero (C.I: 2901786).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:58:08'),
(151, 7, 'Aprobó la devolución del préstamo del equipo ID 2 (Monitor AOC ), devuelto por el docente: \'santiago caballero\' (CI: 2901786).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:58:24'),
(152, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al estudiante richar balbuena (C.I: 5920912).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:58:38'),
(153, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 10:59:09'),
(154, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 11:00:24'),
(155, 7, 'Aprobó la devolución del préstamo del equipo ID 2 (Monitor AOC ), devuelto por el estudiante: \'richar balbuena\' (CI: 5920912).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 11:00:29'),
(156, 7, 'Editó datos de docente ID 4 \'Joaquin Ayala\' (C.I: 5534141).', 'acción_docentes', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 11:08:35'),
(157, 7, 'Eliminó al usuario \'richar\' (C.I: 5920912) con ID 10', 'acción_usuario', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 11:19:36'),
(158, 7, 'Registró al nuevo usuario \'panfilo\' (CI: 87654321) con el rol: \'.', 'acción_usuario', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 11:20:17'),
(159, 11, 'Inicio de sesión exitoso. Usuario: panfilo (Rol: bibliotecaria). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 11:20:35'),
(160, 11, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al docente santiago caballero (C.I: 2901786).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 12:04:16'),
(161, 11, 'Ha cancelado de préstamo activo del equipo ID 2 (Monitor AOC) al docente \'santiago caballero\'', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 12:04:26'),
(162, 11, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al docente santiago caballero (C.I: 2901786).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 12:04:56'),
(163, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 12:06:42'),
(164, 7, 'Aprobó la devolución del préstamo del equipo ID 2 (Monitor AOC ), devuelto por el docente: \'Joaquin Ayala\' (CI: 5534141).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 12:42:51'),
(165, 7, 'Editó datos de docente ID 1 \'santiago caballero\' (C.I: 2901786).', 'acción_docentes', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 12:46:34'),
(166, 7, 'Editó los datos del estudiante ID 3 \'richar balbuena\' (C.I: 5920912).', 'acción_estudiante', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 12:48:21'),
(167, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-11-03 15:56:14'),
(168, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-11-03 18:56:34'),
(169, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W01) al docente santiago caballero (C.I: 2901786).', 'préstamo', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-11-03 18:57:53'),
(170, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin). IP: ::1', 'sesión', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-11-03 18:59:25');

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
(1, 5, 2, 1, '2025-10-27 18:16:33', '2025-10-27 22:16:42', 'aceptada', NULL),
(2, 6, 2, 1, '2025-10-27 18:18:44', '2025-10-27 22:18:52', 'aceptada', NULL),
(3, 8, 2, 1, '2025-10-27 18:25:52', '2025-10-27 22:25:55', 'aceptada', NULL),
(4, 10, 2, 1, '2025-10-27 18:53:36', '2025-10-27 22:54:02', 'aceptada', NULL),
(5, 15, 1, 3, '2025-10-29 10:40:37', '2025-10-29 14:40:44', 'aceptada', NULL),
(6, 24, 4, 2, '2025-10-29 18:22:22', '2025-10-29 22:22:27', 'aceptada', NULL),
(7, 25, 2, 4, '2025-10-29 18:25:27', '2025-10-29 22:25:33', 'aceptada', NULL),
(8, 26, 4, 2, '2025-10-29 18:30:39', '2025-10-29 22:30:43', 'aceptada', NULL),
(9, 26, 2, 4, '2025-10-29 18:31:16', '2025-10-29 22:31:25', 'aceptada', NULL),
(10, 28, 2, 4, '2025-10-29 18:57:22', '2025-10-29 22:57:34', 'aceptada', NULL),
(11, 28, 4, 2, '2025-10-29 18:58:10', '2025-10-29 22:58:12', 'aceptada', NULL),
(12, 46, 1, 4, '2025-11-03 12:38:50', '2025-11-03 16:39:52', 'aceptada', NULL);

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
(2, 3, 1, 1, '\nMotivo rechazo: ', 'rechazada', '2025-10-27 20:35:39');

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
  `telefono` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id`, `ci`, `nombre`, `apellido`, `email`, `telefono`, `password_hash`, `creado_en`) VALUES
(1, '2901786', 'santiago', 'caballero', 'alegrek566@gmail.com', '566767687', '$2y$10$uGnkzgIse2CBcK50GO9INupKu4WYYPFegY6k7YyJ/DThxmyaD4L1a', '2025-11-03 15:47:43'),
(2, '12345678', 'Nathalia', 'Rotela', '123456@gmail.com', '6567767898', '$2y$10$KRoI2G5NSzaJaMFVkQzhyuJaY8m.109FYuLz5eZcngTG/qUH1QCIG', '2025-10-29 21:20:43'),
(3, '6118463', 'Cristhian', 'Carrera', 'cristhiancarreraasp7@gmail.com', '0981073092', '$2y$10$uXB60KhZO5dGsKsRW7vNR.63C4Yh.CgigvoV0YjEfdF5vqaA7EE9m', '2025-10-29 13:39:01'),
(4, '5534141', 'Joaquin', 'Ayala', 'isrraesp19@gmail.com', '', '$2y$10$1vBUTUORESmz1B4sx7HST.evH5aZnaRPVHIBu.nZ7EYNF1NSvYpCi', '2025-11-03 14:08:35'),
(5, '7443749', 'Gastón', 'Paredes', 'gaston.paredes@uc.edu.py', '', '$2y$10$VPv0oak8B4F3/gOej9spWO0rr1OZKUSA0WbeaN7kFzZsfYaC5tPNi', '2025-10-29 22:28:13');

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
  `en_mantenimiento` tinyint(1) DEFAULT 0,
  `con_fallos` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`id`, `area_id`, `sala_id`, `tipo`, `marca`, `modelo`, `nro_serie`, `serial_interno`, `estado`, `prestado`, `con_reporte`, `detalles`, `creado_en`, `actualizado_en`, `en_mantenimiento`, `con_fallos`) VALUES
(1, 2, 1, 'Proyector', 'Epson', 'C0-W01', NULL, '1fd38f17793a', 'En uso', 1, 1, NULL, '2025-10-27 18:06:38', '2025-11-03 21:57:53', 1, 0),
(2, 2, 1, 'Monitor', 'AOC', '', NULL, 'c313dbed9f5d', 'Disponible', 0, 0, NULL, '2025-10-27 18:07:51', '2025-11-03 15:42:51', 0, 0),
(3, 2, 1, 'Teclado', 'SATE', '', NULL, 'c410cf62a4b1', 'Disponible', 0, 0, NULL, '2025-10-27 18:08:09', '2025-10-29 21:58:37', 0, 0),
(4, 2, 1, 'Mouse', 'SATE', '', NULL, '6d13f0a478c9', 'Disponible', 0, 0, NULL, '2025-10-27 18:08:26', NULL, 0, 0);

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
  `telefono` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `ci`, `nombre`, `apellido`, `email`, `telefono`, `password_hash`, `creado_en`) VALUES
(1, '5534142', 'Joaquín', 'Ayala', 'isrraesp19@gmail.com', '0982344456', '$2y$10$rvqioYb3hXPX.Dv5iS05z.hjsjv4HVW.Dh8yJ.pcG.LZELL8J9LtS', '2025-10-27 19:17:19'),
(3, '5920912', 'richar', 'balbuena', 'alegrek566@gmail.com', '', '$2y$10$czXGWsO6eR33/1HqfXI2w.HXT7N/xPxMis0vcvsGodozUkmewJv/u', '2025-10-29 16:44:35');

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
(1, 5, 2, 1, NULL, '2025-10-27 18:16:42'),
(2, 6, 2, 1, NULL, '2025-10-27 18:18:52'),
(3, 8, 2, 1, NULL, '2025-10-27 18:25:55'),
(4, 10, 2, 1, NULL, '2025-10-27 18:54:02'),
(5, 15, 1, 3, NULL, '2025-10-29 10:40:44'),
(6, 24, 4, 2, NULL, '2025-10-29 18:22:27'),
(7, 25, 2, 4, NULL, '2025-10-29 18:25:33'),
(8, 26, 4, 2, NULL, '2025-10-29 18:30:43'),
(9, 26, 2, 4, NULL, '2025-10-29 18:31:25'),
(10, 28, 2, 4, NULL, '2025-10-29 18:57:34'),
(11, 28, 4, 2, NULL, '2025-10-29 18:58:12'),
(12, 46, 1, 4, NULL, '2025-11-03 12:39:52');

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
(1, 1, 7, 1, 'compufacil', 'ds', '2025-10-29 00:00:00', NULL, NULL, NULL, '2025-10-29 16:23:25');

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
(2, 1, '0802ee066b5776f2ede6ba1a454fad05f45d2de347048490afbdb90b44c4ebb6', '2025-10-27 21:58:35', 1, '2025-10-27 17:28:35', 'docentes'),
(3, 1, '50da02b391f560c965ef45dbb06cf8c230034bb960097d1306ff03aa3e46c5f9', '2025-10-29 14:31:57', 0, '2025-10-29 10:01:57', ''),
(4, 3, '6fdb6e7f5fdfd87ada7cc878bc9520a27f60926375425401f505ec6adddc7973', '2025-10-29 18:14:51', 0, '2025-10-29 13:44:51', ''),
(5, 3, '9968245a8f49d752ed2f6dd3b3cbe508c50f04f6bd9cf7399f5dcf5a227aab52', '2025-10-29 18:25:03', 0, '2025-10-29 13:55:03', ''),
(6, 3, '9421c87a85671386315772210ef192a3e01beed56dd171c85ab562f737ba689c', '2025-10-29 18:25:52', 0, '2025-10-29 13:55:52', ''),
(7, 3, 'c38a619384ef04cd36397df77a524b7404993c0a44302ea8fe20d29e25677465', '2025-10-29 18:26:10', 0, '2025-10-29 13:56:10', ''),
(8, 3, '570d232db1b8994bc82653b9afa035f106d03ca5af9a39f3baf51f2844cffefb', '2025-10-29 18:26:19', 0, '2025-10-29 13:56:19', ''),
(9, 3, '3ff4fff6f8ad242888b598c7d51fa58e43f54e4edf50094641fc130495a802de', '2025-10-29 18:34:04', 0, '2025-10-29 14:04:04', 'estudiantes'),
(10, 1, '872b15c9f72d3b014e2a9cd002a3359ddb089c5cd7c4f79a7e0a06d6ea2254e9', '2025-11-03 17:16:45', 1, '2025-11-03 12:46:45', 'docentes'),
(11, 1, '1fed337a861d6997965e4406c2b8c936ae50e0ddd5de1683aac249698fbeda58', '2025-11-03 17:18:26', 0, '2025-11-03 12:48:26', 'estudiantes'),
(12, 3, '935c0bbc9d826c93f93571668bcf6aafe7afc701fdd621905af2bb1b20a27204', '2025-11-03 17:20:47', 1, '2025-11-03 12:50:47', 'estudiantes');

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
(4, 1, 1, NULL, 1, '2025-10-27 17:54:32', NULL, 'devuelto', 'sala de informatica, con profe santiago', '2025-10-27 20:54:26', NULL, NULL, NULL),
(5, 2, NULL, 1, 1, '2025-10-27 18:16:07', NULL, 'devuelto', '', '2025-10-27 21:15:56', NULL, NULL, NULL),
(6, 2, NULL, 1, 1, '2025-10-27 18:18:21', NULL, 'cancelado', '', '2025-10-27 21:18:09', NULL, NULL, NULL),
(7, 2, NULL, 2, 2, '2025-10-27 18:24:44', '2025-10-27 18:25:08', 'devuelto', '', '2025-10-27 21:24:36', NULL, NULL, NULL),
(8, 2, NULL, 1, 1, '2025-10-27 18:25:33', '2025-10-27 18:26:35', 'devuelto', '', '2025-10-27 21:25:27', NULL, NULL, NULL),
(9, 1, NULL, 2, 2, '2025-10-27 18:50:38', '2025-10-27 18:52:15', 'devuelto', '', '2025-10-27 21:50:14', NULL, NULL, NULL),
(10, 1, NULL, 1, 1, '2025-10-27 18:52:39', '2025-10-27 18:54:29', 'devuelto', '', '2025-10-27 21:52:29', NULL, NULL, NULL),
(11, 1, NULL, 2, 2, '2025-10-27 18:55:56', NULL, 'cancelado', '', '2025-10-27 21:55:51', NULL, NULL, NULL),
(12, 1, 1, NULL, 1, '2025-10-29 10:26:26', NULL, 'cancelado', '', '2025-10-29 13:25:25', NULL, NULL, NULL),
(13, 1, 1, NULL, 1, '2025-10-29 10:27:46', NULL, 'cancelado', '\nRechazado: ', '2025-10-29 13:27:29', NULL, NULL, NULL),
(14, 1, NULL, 1, 1, '2025-10-29 10:33:37', '2025-10-29 10:33:57', 'devuelto', '', '2025-10-29 13:32:23', NULL, NULL, NULL),
(15, 1, NULL, 3, 3, '2025-10-29 10:36:35', NULL, 'cancelado', '\nRechazado: no sirve', '2025-10-29 13:35:42', NULL, NULL, NULL),
(16, 2, 1, NULL, 1, '2025-10-29 10:37:06', '2025-10-29 10:59:22', 'devuelto', '', '2025-10-29 13:37:03', NULL, NULL, NULL),
(17, 3, NULL, 2, 2, '2025-10-29 10:37:54', NULL, 'cancelado', '', '2025-10-29 13:37:44', NULL, NULL, NULL),
(18, 2, 1, NULL, 1, '2025-10-29 10:59:48', '2025-10-29 11:11:05', 'devuelto', '', '2025-10-29 13:59:38', NULL, NULL, NULL),
(19, 2, 1, NULL, 1, '2025-10-29 11:16:05', '2025-10-29 11:22:12', 'devuelto', '', '2025-10-29 14:15:36', NULL, NULL, NULL),
(20, 2, 1, NULL, 1, '2025-10-29 11:22:23', '2025-10-29 12:52:27', 'cancelado', '\nRechazado: nooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo lpm', '2025-10-29 14:22:18', NULL, NULL, NULL),
(21, 2, 1, NULL, 1, '2025-10-29 13:39:12', '2025-10-29 13:50:49', 'devuelto', '', '2025-10-29 16:38:46', NULL, NULL, NULL),
(22, 2, NULL, 1, 1, '2025-10-29 13:58:49', '2025-10-29 13:59:22', 'devuelto', '\nRechazado: Tarde', '2025-10-29 16:58:35', NULL, NULL, NULL),
(23, 2, NULL, 4, 4, '2025-10-29 18:16:08', '2025-10-29 18:18:01', 'devuelto', '', '2025-10-29 21:15:49', NULL, NULL, NULL),
(24, 2, NULL, 2, 2, '2025-10-29 18:20:00', NULL, 'cancelado', '', '2025-10-29 21:18:13', NULL, NULL, NULL),
(25, 3, NULL, 4, 4, '2025-10-29 18:25:05', '2025-10-29 18:25:56', 'devuelto', '', '2025-10-29 21:23:57', NULL, NULL, NULL),
(26, 3, NULL, 4, 4, '2025-10-29 18:29:04', '2025-10-29 18:32:08', 'devuelto', 'Sala de informática tercer curso', '2025-10-29 21:28:49', NULL, NULL, NULL),
(27, 2, NULL, 1, 1, '2025-10-29 18:34:46', '2025-10-29 18:35:16', 'devuelto', '\nRechazado: qwer', '2025-10-29 21:34:17', NULL, NULL, NULL),
(28, 3, NULL, 2, 2, '2025-10-29 18:56:50', '2025-10-29 18:58:37', 'devuelto', 'Para utilizar en la sala de informática, con el cuarto curso', '2025-10-29 21:56:31', NULL, NULL, NULL),
(29, 2, NULL, 5, 5, '2025-10-29 19:29:03', NULL, 'cancelado', '', '2025-10-29 22:29:03', NULL, NULL, NULL),
(30, 2, 1, NULL, 1, '2025-10-30 10:40:58', '2025-10-30 10:45:12', 'devuelto', '', '2025-10-30 13:40:40', NULL, NULL, NULL),
(31, 1, 1, NULL, 1, '2025-11-01 20:16:39', NULL, 'cancelado', '\nRechazado: ', '2025-11-01 23:16:17', NULL, NULL, NULL),
(32, 1, NULL, 1, 1, '2025-11-03 10:10:17', '2025-11-03 10:10:32', 'devuelto', '', '2025-11-03 13:10:12', NULL, NULL, NULL),
(33, 1, 1, NULL, 1, '2025-11-03 10:10:47', '2025-11-03 10:17:10', 'devuelto', '', '2025-11-03 13:10:36', NULL, NULL, NULL),
(34, 1, 1, NULL, 1, '2025-11-03 10:17:06', '2025-11-03 10:17:19', 'devuelto', '', '2025-11-03 13:16:57', NULL, NULL, NULL),
(35, 1, 1, NULL, 1, '2025-11-03 10:17:34', '2025-11-03 10:18:02', 'devuelto', '', '2025-11-03 13:17:25', NULL, NULL, NULL),
(36, 2, 1, NULL, 1, '2025-11-03 10:20:34', NULL, 'cancelado', '', '2025-11-03 13:20:23', NULL, NULL, NULL),
(37, 2, 1, NULL, 1, '2025-11-03 10:36:04', NULL, 'cancelado', '', '2025-11-03 13:35:59', NULL, NULL, NULL),
(38, 2, 1, NULL, 1, '2025-11-03 10:45:25', '2025-11-03 10:45:49', 'devuelto', '', '2025-11-03 13:45:15', NULL, NULL, NULL),
(39, 2, 1, NULL, 1, '2025-11-03 10:48:21', NULL, 'cancelado', '', '2025-11-03 13:45:56', NULL, NULL, NULL),
(40, 2, NULL, 1, 1, '2025-11-03 10:49:52', NULL, 'cancelado', '', '2025-11-03 13:49:52', NULL, NULL, NULL),
(41, 2, NULL, 1, 1, '2025-11-03 10:54:05', '2025-11-03 10:54:23', 'devuelto', '', '2025-11-03 13:53:59', NULL, NULL, NULL),
(42, 2, 3, NULL, 3, '2025-11-03 10:54:33', '2025-11-03 10:55:51', 'devuelto', '', '2025-11-03 13:54:27', NULL, NULL, NULL),
(43, 2, NULL, 1, 1, '2025-11-03 10:58:08', '2025-11-03 10:58:24', 'devuelto', '', '2025-11-03 13:58:01', NULL, NULL, NULL),
(44, 2, 3, NULL, 3, '2025-11-03 10:58:38', '2025-11-03 11:00:29', 'devuelto', '', '2025-11-03 13:58:34', NULL, NULL, NULL),
(45, 2, NULL, 1, 1, '2025-11-03 12:04:16', NULL, 'cancelado', '', '2025-11-03 15:04:05', NULL, NULL, NULL),
(46, 2, NULL, 4, 4, '2025-11-03 12:04:56', '2025-11-03 12:42:51', 'devuelto', '', '2025-11-03 15:04:53', NULL, NULL, NULL),
(47, 1, NULL, 1, 1, '2025-11-03 18:57:53', NULL, 'activo', '', '2025-11-03 21:57:37', NULL, NULL, NULL);

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
(1, '0000-00-00', 'problema al encender', 'cuando se intenta encender, queda colgado', 1, 'kevin');

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
(11, '87654321', 'panfi@gmail.com', 'panfilo', '$2y$10$ddGNldIByUho0jUbbsJlge9n45JC1A6argpZdafUFF.TDA5.v7jau', 2, '2025-11-03 14:20:17');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT de la tabla `cesiones`
--
ALTER TABLE `cesiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `componentes`
--
ALTER TABLE `componentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `historial_cesiones`
--
ALTER TABLE `historial_cesiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `password_resets_estudiantes`
--
ALTER TABLE `password_resets_estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `reporte_fallos`
--
ALTER TABLE `reporte_fallos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
