-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-11-2025 a las 02:58:44
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
  `tipo_accion` varchar(50) DEFAULT 'general',
  `ip_usuario` varchar(250) NOT NULL,
  `user_agent` varchar(250) NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id`, `usuario_id`, `accion`, `tipo_accion`, `ip_usuario`, `user_agent`, `fecha`) VALUES
(1, 7, 'Registró una nueva sala con ID 1 y Nombre: Biblioteca', 'general', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 14:53:41'),
(2, 7, 'Registró una nueva sala con ID 2 y Nombre: Laboratorio', 'general', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 14:57:59'),
(3, 7, 'Agregó el componente: Zapatilla   (bueno) al equipo ID 1 (Proyector Epson C0-W01).', 'general', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 15:07:02'),
(4, 7, 'Agregó el componente: Fuente   (bueno) al equipo ID 1 (Proyector Epson C0-W01).', 'general', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 15:07:14'),
(5, 7, 'Agregó el componente: HDMI   (bueno) al equipo ID 1 (Proyector Epson C0-W01).', 'general', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 15:07:23'),
(6, 7, 'Agregó el componente: Control remoto   (bueno) al equipo ID 1 (Proyector Epson C0-W01).', 'general', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 15:07:29'),
(7, 7, 'Registró un nuevo estudiante con ID 1 y Nombre: Joaquín Ayala', 'general', '::1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-10-27 16:17:19'),
(8, 7, 'Rechazó la solicitud de devolución del préstamo ID 4 para el equipo ID 1. Motivo: ', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:09:46'),
(9, 7, 'Cancelación de préstamo activo - Préstamo ID 4. Motivo: .', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:09:52'),
(10, 7, 'Registró un nuevo Docente ID 2: César Algo (CI: 123456).', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:40:47'),
(11, 7, 'Registró un nuevo Docente ID 3: Nathalia Rotela (CI: 5695298).', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:42:19'),
(12, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 18:44:02'),
(13, 7, 'Rechazo de solicitud de préstamo - Préstamo ID 8 del equipo ID 5 (Equipo sin descripción) al docente \'César Algo\'. Motivo: .', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 20:52:23'),
(14, 7, 'Rechazo de solicitud de préstamo - Préstamo ID 9 del equipo ID 5 (Equipo sin descripción) al docente \'César Algo\'. Motivo: .', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 20:55:13'),
(15, 7, 'Rechazo de solicitud de préstamo del equipo ID 5 (Tele) al docente \'César Algo\'. Motivo: .', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 20:56:14'),
(16, 7, 'Rechazo de solicitud de préstamo del equipo ID 5 (Tele) al docente \'César Algo\'.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 20:57:42'),
(17, 7, 'Aprobó y registró el préstamo del equipo ID 5 (Tele  ) al docente \'César Algo\'.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 20:59:58'),
(18, 7, 'Ha cancelado de préstamo activo del equipo ID 5 (Tele) al docente \'santiago caballero\'', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 21:02:40'),
(19, 7, 'Registró un nuevo Docente ID 4: Joaquin Profe (CI: 12345678).', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 21:34:23'),
(20, 7, 'Editó los datos del estudiante ID 1 (Joaquín Ayala).', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 21:34:46'),
(21, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W01) al docente \'Nathalia Rotela\'.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 21:55:27'),
(22, 7, 'Rechazó la solicitud de devolución del préstamo ID 13 para el equipo ID 1. Motivo: ', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 23:07:01'),
(23, 7, 'Ha cancelado de préstamo activo del equipo ID 1 (Proyector Epson C0-W01) al docente \'Nathalia Rotela\'', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 23:11:02'),
(24, 7, 'Ha rechazado la solicitud de préstamo del equipo ID 1 (Proyector Epson C0-W01) al docente \'Nathalia Rotela\'', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 23:13:53'),
(25, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W01) al docente \'Nathalia Rotela\'.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 23:14:35'),
(26, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al docente \'Nathalia Rotela\'.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 23:49:41'),
(27, 7, 'Ha cancelado de préstamo activo del equipo ID 2 (Monitor AOC) al docente \'César Algo\'', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 23:50:33'),
(28, 7, 'Ha cancelado de préstamo activo del equipo ID 1 (Proyector Epson C0-W01) al docente \'César Algo\'', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-27 23:50:39'),
(29, 7, 'Registró un nuevo Docente ID 5: Tomás Estigarribia (CI: 1234567).', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 01:44:09'),
(30, 7, 'Eliminó el Docente ID 5: Tomás Estigarribia (CI: 1234567).', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 01:47:31'),
(31, 7, 'Registró un nuevo estudiante con ID 2 y Nombre: Nathi Rotela.', 'registro_estudiante', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 01:48:03'),
(32, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 11:54:20'),
(33, 7, 'Aprobó el préstamo del equipo ID 5 (Tele  ) al docente \'Nathalia Rotela\'.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 12:03:28'),
(34, 7, 'Ha cancelado de préstamo activo del equipo ID 5 (Tele) al docente \'Nathalia Rotela\'', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 12:05:16'),
(35, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 13:28:12'),
(36, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 13:42:48'),
(37, 7, 'Registró el equipo ID #7: hyyd fhd htf con Serial: 93ce33f6844d.', 'acción_equipo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 13:49:48'),
(38, 7, 'Aprobó el préstamo del equipo ID 4 (Mouse SATE ) al docente \'Nathalia Rotela\'.', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 13:55:35'),
(39, 7, 'Aprobó la devolución del préstamo ID 18 para el equipo ID 4. El activo vuelve al inventario.', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 13:56:10'),
(40, 7, 'Reportó un fallo para el equipo ID 6 (Prueba nft dthd). Fallo: Le pasó algo. Descripción: Fallooooo...', 'reporte', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:12:03'),
(41, 7, 'Envió el Equipo ID 6 (Prueba nft (Serial: 0d465d361a89)) a mantenimiento. Destino: algun lugar. Motivo: si.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:13:14'),
(42, 7, 'Finalizó el mantenimiento del Equipo ID 6 (Serial: 0d465d361a89). Resultado: SOLUCIONADO y devuelto.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:14:09'),
(43, 7, 'Registró al nuevo usuario con ID 11', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:15:02'),
(44, 7, 'Editó el usuario con ID 11', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:15:51'),
(45, 7, 'Editó al usuario ID 11 y le asigó el rol de: \'bibliotecaria\'.', 'accion_usuario', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:29:18'),
(46, 7, 'Editó al usuario con nombre: Nathalia (CI: 5695298) y le asigó el rol de: \'admin\'.', 'accion_usuario', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:31:00'),
(47, 7, 'Eliminó al usuario \'Nathalia\' (C.I: 5695298) con ID 11', 'acción_usuario', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:36:26'),
(48, 7, 'Eliminó el Docente ID 4: Joaquin Profe (CI: 12345678).', 'acción_docentes', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:42:32'),
(49, 7, 'Registró un nuevo Docente ID 6: Joaquin Ayala (CI: 12345678).', 'acción_docentes', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:43:06'),
(50, 7, 'Editó datos de docente ID 6 (Joaquin Profe).', 'acción_docentes', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:43:47'),
(51, 7, 'Editó datos de docente ID 1 (Santiago Caballero (C.I: 2901786)).', 'acción_docentes', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:44:57'),
(52, 7, 'Registró al nuevo usuario \'Nathalia\' (CI: 5695298) con el rol: \'.', 'acción_usuario', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:49:14'),
(53, 7, 'Editó al usuario con nombre \'Nathalia\' (CI: 5695298) y le asigó el rol de: \'admin\'.', 'acción_usuario', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:49:30'),
(54, 12, 'Inicio de sesión exitoso. Usuario: Nathalia (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:49:52'),
(55, 12, 'Aprobó el préstamo del equipo ID 4 (Mouse SATE ) al docente \'César Algo\'.', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:50:40'),
(56, 12, 'Aprobó la devolución del préstamo ID 19 para el equipo ID 4. El activo vuelve al inventario.', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:50:56'),
(57, 12, 'Aprobó el préstamo del equipo ID 5 (Tele  ) al docente \'César Algo\'.', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:52:17'),
(58, 12, 'Aprobó la devolución del préstamo ID 20 para el equipo ID 5. El activo vuelve al inventario.', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:52:31'),
(59, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:53:53'),
(60, 7, 'Editó al usuario con nombre \'Nathi\' (CI: 5695298) y le asigó el rol de: \'admin\'.', 'acción_usuario', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:54:02'),
(61, 12, 'Inicio de sesión exitoso. Usuario: Nathi (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:54:22'),
(62, 12, 'Aprobó el préstamo del equipo ID 4 (Mouse SATE ) al docente \'César Algo (C.I: 123456)\'.', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:12:22'),
(63, 12, 'Ha cancelado de préstamo activo del equipo ID 4 (Mouse SATE) al docente \'César Algo\'', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:21:37'),
(64, 12, 'Aprobó el préstamo del equipo ID 4 (Mouse SATE ) al docente César Algo (C.I: 123456).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:21:54'),
(65, 12, 'Aprobó la devolución del préstamo del equipo ID 4 (), devuelto por el docente: \'César Algo\' (CI: 123456). El activo vuelve al inventario.', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:22:13'),
(66, 12, 'Aprobó el préstamo del equipo ID 4 (Mouse SATE ) al docente César Algo (C.I: 123456).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:24:45'),
(67, 12, 'Aprobó la devolución del préstamo del equipo ID 4 (Mouse ), devuelto por el docente: \'César Algo\' (CI: 123456).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:25:06'),
(68, 12, 'Aprobó el préstamo del equipo ID 4 (Mouse SATE ) al docente César Algo (C.I: 123456).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:27:15'),
(69, 12, 'Aprobó la devolución del préstamo del equipo ID 4 (Mouse SATE ), devuelto por el docente: \'César Algo\' (CI: 123456).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:27:28'),
(70, 12, 'Registró un nuevo estudiante con ID 3: Nombre: Alguien si (C.I: 12345678910).', 'acción_estudiante', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:34:11'),
(71, 12, 'Editó los datos del estudiante ID 3 \'Alguien si\' (C.I: 12345678910).', 'acción_estudiante', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:35:02'),
(72, 12, 'Editó los datos del estudiante ID 3 \'Alguien Si\' (C.I: 12345678910).', 'acción_estudiante', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:35:38'),
(73, 12, 'Eliminó al estudiante ID 3 (Alguien Si (C.I: 12345678910)).', 'acción_estudiante', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:35:55'),
(74, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin).', 'sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 17:25:54'),
(75, 7, 'Editó al usuario ID 12 (\'\'). Campos modificados: **Nombre (\'Nathi\' -> \'\'), CI (\'5695298\' -> \'\'), Rol (\'admin\' -> \'Desconocido\')**.', 'edicion_usuario', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 18:07:42'),
(76, 7, 'Editó al usuario ID 12 (\'Nathi\'). Campos modificados: **CI (\'5695298\' -> \'56952989\')**.', 'edicion_usuario', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 18:08:45'),
(77, 7, 'Editó al usuario ID 12 \'Nathi\'. Campos modificados: CI (\'56952989\' -> \'5695298\').', 'edicion_usuario', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 18:10:01'),
(78, 7, 'Editó al usuario ID 12 \'Nathi\'. Campos modificados: C.I. (\'5695298\' -> \'569529810\').', 'edicion_usuario', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 18:11:44'),
(79, 7, 'Editó al usuario ID 12 \'Nathi\' y le cambió el rol a \'bibliotecaria\'.', 'edicion_usuario', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 18:12:09'),
(80, 7, 'Editó al usuario ID 12 \'Nathi\'. Campos modificados: C.I. (\'569529810\' -> \'5695298\'), Rol (\'bibliotecaria\' -> \'admin\').', 'edicion_usuario', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 18:12:26'),
(81, 7, 'Registró la Sala ID 3: **Salón Auditorio** en el Área: **UP** (ID 2).', 'acción_sala', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:26:16'),
(82, 7, 'Registró la sala ID 4: \'si\' en el área: \'UP\' (ID 2).', 'acción_sala', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:31:07'),
(83, 7, 'Editó el equipo (ID 6) Prueba siuuuuu dthd.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:31:52'),
(84, 7, 'Editó el equipo ID 6 (**Prueba siuuuuu dthd**). Campos modificados: **Marca (\'siuuuuu\' -> \'nouuuuu\')**.', 'accion_equipo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:41:17'),
(85, 7, 'Editó el equipo ID 6 (**Prueba nouuuuu dthd**). Campos modificados: **Marca (\'nouuuuu\' -> \'Anibal malo\')**.', 'accion_equipo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:41:56'),
(86, 7, 'Editó el equipo ID 6 (Prueba Anibal malo dthd). Campos modificados: Modelo (\'dthd\' -> \'re malo\').', 'accion_equipo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:43:02'),
(87, 7, 'Eliminó el equipo ID 7 (Serial: 93ce33f6844d): hyyd fhd htf.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:43:33'),
(88, 7, 'Eliminó el equipo ID 6 (Serial: 0d465d361a89): \'Prueba Anibal malo re malo\'.', 'acción_equipo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:48:08'),
(89, 7, 'Editó el equipo ID 1 (Proyector Epson C0-W01). Campos modificados: Modelo (\'C0-W01\' -> \'C0-W012\').', 'accion_equipo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:52:28'),
(90, 7, 'Eliminó el equipo ID 3 (Serial: c410cf62a4b1): \'Teclado SATE\'.', 'acción_equipo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:52:39'),
(91, 7, 'Reportó un fallo para el equipo ID 5 (Tele  ). Fallo: slgo. Descripción: dfb...', 'reporte', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:53:25'),
(92, 7, 'Envió el Equipo ID 5 (Tele  (Serial: c597582e367c)) a mantenimiento. Destino: ai. Motivo: bgd.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:53:36'),
(93, 7, 'Finalizó el mantenimiento del Equipo ID 5 (Serial: c597582e367c). Resultado: SOLUCIONADO y devuelto.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:54:01'),
(94, 7, 'Reportó un fallo para el equipo ID 5 (Tele  ). Fallo: dhb. Descripción: dfbdfb...', 'reporte', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:54:38'),
(95, 7, 'Envió el Equipo ID 5 (Tele  (Serial: c597582e367c)) a mantenimiento. Destino: htrhet. Motivo: hdnhey.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:55:01'),
(96, 7, 'Finalizó el mantenimiento del Equipo ID 5 (Serial: c597582e367c). Resultado: DEVUELTO SIN SOLUCIONAR.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:55:20'),
(97, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W012) al docente Nathalia Rotela (C.I: 5695298).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:57:24'),
(98, 7, 'Aprobó la devolución del préstamo del equipo ID 1 (Proyector Epson C0-W012), devuelto por el docente: \'Nathalia Rotela\' (CI: 5695298).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 19:58:00'),
(99, 7, 'Registró el equipo ID 8: gvguu vjgvj  con Serial: c14283f2f360.', 'accion_equipo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 20:00:13'),
(100, 7, 'Aprobó el préstamo del equipo ID 8 (gvguu vjgvj ) al docente Nathalia Rotela (C.I: 5695298).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 20:00:57'),
(101, 7, 'Aprobó la devolución del préstamo del equipo ID 8 (gvguu vjgvj ), devuelto por el docente: \'Nathalia Rotela\' (CI: 5695298).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 20:01:18'),
(102, 7, 'Aprobó el préstamo del equipo ID 8 (gvguu vjgvj ) al docente Nathalia Rotela (C.I: 5695298).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 20:02:53'),
(103, 7, 'Aprobó la devolución del préstamo del equipo ID 8 (gvguu vjgvj ), devuelto por el docente: \'Nathalia Rotela\' (CI: 5695298).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 20:03:12'),
(104, 7, 'Reportó un fallo para el equipo ID 5 (Tele  ). Fallo: prueba. Descripción: gdb...', 'reporte', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 20:16:20'),
(105, 7, 'Envió el Equipo ID 5 (Tele  (Serial: c597582e367c)) a mantenimiento. Destino: a otro lugar. Motivo: si.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 20:16:42'),
(106, 7, 'Finalizó el mantenimiento del Equipo ID 5 (Serial: c597582e367c). Resultado: SOLUCIONADO y devuelto.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 20:17:34'),
(107, 7, 'Reportó un fallo para el equipo ID 5 (Tele  ). Fallo: gcjgcvj. Descripción: hbvjhv...', 'reporte', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 20:17:51'),
(108, 7, 'Envió el Equipo ID 5 (Tele  (Serial: c597582e367c)) a mantenimiento. Destino: bfndb. Motivo: bdfrbd.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 20:33:30'),
(109, 7, 'Finalizó el mantenimiento del Equipo ID 5 (Serial: c597582e367c). Resultado: SOLUCIONADO y devuelto.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 20:33:49'),
(110, 7, 'Reportó un fallo para el equipo ID 5 (Tele  ). Fallo: hdthnde. Descripción: ghfyj...', 'reporte', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 20:34:06'),
(111, 7, 'Envió el Equipo ID 5 (Tele  (Serial: c597582e367c)) a mantenimiento. Destino: nfcn. Motivo: dfgtf.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 20:34:20'),
(112, 7, 'Finalizó el mantenimiento del Equipo ID 5 (Serial: c597582e367c). Resultado: DEVUELTO SIN SOLUCIONAR.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 20:34:31'),
(113, 7, 'Reportó un fallo para el equipo ID 5 (Tele  ). Fallo: 15516. Descripción: rdhd...', 'reporte', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 21:38:44'),
(114, 7, 'Envió el Equipo ID 5 (Tele  (Serial: c597582e367c)) a mantenimiento. Destino: ftugj. Motivo: ftgtju.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 21:50:00'),
(115, 7, 'Finalizó el mantenimiento del Equipo ID 5 (Serial: c597582e367c). Resultado: DEVUELTO SIN SOLUCIONAR.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 21:50:32'),
(116, 7, 'Reportó un fallo para el equipo ID 5 (Tele  ). Fallo: hvjcj. Descripción: fviyfj...', 'reporte', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 22:02:59'),
(117, 7, 'Envió el Equipo ID 5 (Tele  (Serial: c597582e367c)) a mantenimiento. Destino: bdgb. Motivo: tebgd.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 22:05:06'),
(118, 7, 'Finalizó el mantenimiento del Equipo ID 5 (Serial: c597582e367c). Resultado: DEVUELTO SIN SOLUCIONAR.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 22:16:35'),
(119, 7, 'Reportó un fallo para el equipo ID 5 (Tele  ). Fallo: gtucgju. Descripción: gyui...', 'reporte', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 22:34:50'),
(120, 7, 'Envió el Equipo ID 5 (Tele  (Serial: c597582e367c)) a mantenimiento. Destino: gvjv. Motivo: hbyybh.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 22:35:20'),
(121, 7, 'Finalizó el mantenimiento del Equipo ID 5 (Serial: c597582e367c). Resultado: SOLUCIONADO y devuelto.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 22:35:35'),
(122, 7, 'Reportó un fallo para el equipo ID 5 (Tele  ). Fallo: vggj. Descripción: tfitju...', 'reporte', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 22:36:20'),
(123, 7, 'Envió el Equipo ID 5 (Tele  (Serial: c597582e367c)) a mantenimiento. Destino: hdtc. Motivo: cgnvc.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 22:36:39'),
(124, 7, 'Finalizó el mantenimiento del Equipo ID 5 (Serial: c597582e367c). Resultado: DEVUELTO SIN SOLUCIONAR.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 22:36:54'),
(125, 7, 'Finalizó el mantenimiento del Equipo ID 5 (Serial: c597582e367c). Resultado: SOLUCIONADO y devuelto', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:08:38'),
(126, 7, 'Reportó un fallo para el equipo ID 8 (gvguu vjgvj ). Fallo: falloooo. Descripción: si...', 'reporte', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:09:29'),
(127, 7, 'Envió el Equipo ID 8 (gvguu vjgvj (Serial: c14283f2f360)) a mantenimiento. Destino: algun lugar. Motivo: porque si.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:10:47'),
(128, 7, 'Reportó un fallo para el equipo ID 5 (Tele  ). Fallo: cgnd. Descripción: ncgn...', 'reporte', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:11:07'),
(129, 7, 'Envió el Equipo ID 5 (Tele  (Serial: c597582e367c)) a mantenimiento. Destino: hndxx. Motivo: cgnj.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:11:21'),
(130, 7, 'Finalizó el mantenimiento del Equipo ID 8 (Serial: c14283f2f360). Resultado: DEVUELTO SIN SOLUCIONAR', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:11:38'),
(131, 7, 'Finalizó el mantenimiento del Equipo ID 5 (Serial: c597582e367c). Resultado: SOLUCIONADO y devuelto', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:11:55'),
(132, 7, 'Envió el Equipo ID 8 (gvguu vjgvj (Serial: c14283f2f360)) a mantenimiento. Destino: gvuuguut. Motivo: fxdhdxfn.', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:12:15'),
(133, 7, 'Finalizó el mantenimiento del Equipo ID 8 (Serial: c14283f2f360). Resultado: SOLUCIONADO y devuelto', 'mantenimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:12:54'),
(134, 7, 'Registró la sala ID 5: \'Prueba\' en el área: \'UP\' (ID 2).', 'acción_sala', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:23:58'),
(135, 7, 'Editó la sala ID 4 (no) del área \'UP\'.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:28:55'),
(136, 7, 'Editó la sala ID 4 (nooooo) del área \'UP\'.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:30:08'),
(137, 7, 'Editó la sala ID 4 (si) del área \'UP\'.', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:30:52'),
(138, 7, 'Editó la sala ID 5 (Prueba) del área \'UP\'.', 'acción_sala', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:32:12'),
(139, 7, 'Eliminó la sala ID 4: \'si\' del área \'UP\'.', 'acción_sala', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:33:15'),
(140, 7, 'Eliminó la sala ID 5: \'Prueba\' del área \'UP\'.', 'acción_sala', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:34:48'),
(141, 7, 'Registró el equipo ID 9: bgdtt hbd dth con Serial: 13607aa0e833.', 'acción_equipo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 23:58:44'),
(142, 7, 'Agregó el componente: \'prueba sf dbg (bueno)\' al equipo ID 1 \'(Proyector Epson C0-W012\').', 'acción_componente', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 00:09:07'),
(143, 7, 'Agregó el componente: \'dcddddd  \' al equipo ID 1 (Proyector Epson C0-W012).', 'acción_componente', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 00:10:46'),
(144, 7, 'Editó el componente ID 6 (  ) del equipo ID 1.', 'acción_componente', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 00:11:06'),
(145, 7, 'Editó el componente ID 6 (**aseeeeiii dfdgdg ffd**). Cambios: Marca (\'\' -> \'dfdgdg\'), Modelo (\'\' -> \'ffd\'). Del equipo ID 1.', 'edicion_componente', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 00:17:14'),
(146, 7, 'Editó el componente ID 6 (a dfdgdg ffd). Cambios: Tipo (\'aseeeeiii\' -> \'a\'). Del equipo ID 1.', 'edicion_componente', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 00:18:33'),
(147, 7, 'Eliminó el componente ID 6 (a dfdgdg ffd) del equipo ID 1.', 'acción_componente', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 00:18:53'),
(148, 7, 'Eliminó el componente ID 5 (prueba sf dbg) del equipo ID 1.', 'acción_componente', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 00:19:08'),
(149, 7, 'Editó el componente ID 4 (Control remotos  ). Cambios: Tipo (\'Control remoto\' -> \'Control remotos\'). Del equipo ID 1.', 'acción_componente', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 00:20:45'),
(150, 7, 'Editó el componente ID 4 (Control remoto  ). Cambios: Tipo (\'Control remotos\' -> \'Control remoto\'). Del equipo ID 1.', 'acción_componente', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 00:21:29'),
(151, 7, 'Aprobó el préstamo del equipo ID 1 (Proyector Epson C0-W012) al docente Nathalia Rotela (C.I: 5695298).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 00:25:37'),
(152, 7, 'Aprobó la devolución del préstamo del equipo ID 1 (Proyector Epson C0-W012), devuelto por el docente: \'Nathalia Rotela\' (CI: 5695298).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 00:28:52'),
(153, 7, 'Aprobó el préstamo del equipo ID 2 (Monitor AOC ) al docente Nathalia Rotela (C.I: 5695298).', 'préstamo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 00:29:35'),
(230, 12, 'Solicitud de restablecimiento de contraseña para CI: 5695298', 'contra_restablecimiento', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 22:24:08'),
(231, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin)', 'inicio_sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 22:24:41'),
(233, 12, 'Inicio de sesión exitoso. Usuario: Nathi (Rol: admin)', 'inicio_sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 22:27:00'),
(234, 12, 'Cierre de sesión exitoso. Usuario: Nathi (Rol: admin)', 'cierre_sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 22:44:52'),
(235, 7, 'Inicio de sesión exitoso. Usuario: kevin (Rol: admin)', 'inicio_sesión', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 22:44:57'),
(236, 6, 'Cedió el equipo \'Proyector Epson C0-W012 (Serial: 1fd38f17793a)\' al Docente Nathalia Rotela (CI: 5695298)', 'cesión_docentes', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-04 22:45:45'),
(237, 7, 'Aprobó la devolución del préstamo del equipo ID 1 (Proyector Epson C0-W012), devuelto por el docente: \'Nathalia Rotela\' (CI: 5695298).', 'devolución', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 22:46:25');

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
(4, 16, 3, 2, '2025-10-27 23:50:03', '2025-10-28 03:50:23', 'aceptada', NULL),
(5, 31, 3, 2, '2025-11-03 22:59:50', NULL, 'pendiente', NULL),
(6, 32, 3, 6, '2025-11-03 23:05:22', '2025-11-04 03:05:29', 'aceptada', NULL),
(7, 32, 6, 3, '2025-11-03 23:10:31', '2025-11-04 03:10:41', 'aceptada', NULL),
(8, 32, 3, 6, '2025-11-03 23:11:04', '2025-11-04 03:11:38', 'aceptada', NULL),
(9, 32, 6, 3, '2025-11-03 23:25:29', '2025-11-04 03:25:37', 'aceptada', NULL),
(10, 33, 6, 3, '2025-11-04 12:26:53', '2025-11-04 16:27:00', 'aceptada', NULL),
(11, 35, 6, 3, '2025-11-04 12:41:54', '2025-11-04 16:42:05', 'aceptada', NULL),
(12, 35, 3, 6, '2025-11-04 13:41:26', '2025-11-04 17:41:41', 'aceptada', NULL),
(13, 35, 6, 3, '2025-11-04 13:41:49', '2025-11-04 17:46:52', 'aceptada', NULL),
(14, 35, 3, 6, '2025-11-04 13:48:12', '2025-11-04 17:48:17', 'aceptada', NULL),
(15, 35, 6, 3, '2025-11-04 13:53:55', '2025-11-04 17:54:00', 'aceptada', NULL),
(16, 35, 3, 6, '2025-11-04 13:54:27', '2025-11-04 17:54:45', 'aceptada', NULL),
(17, 35, 6, 3, '2025-11-04 13:55:26', NULL, 'pendiente', NULL),
(18, 36, 3, 6, '2025-11-04 13:56:03', '2025-11-04 17:56:11', 'aceptada', NULL),
(19, 37, 3, 6, '2025-11-04 13:57:37', '2025-11-04 17:57:45', 'rechazada', NULL),
(20, 38, 3, 6, '2025-11-04 13:58:20', '2025-11-04 17:58:32', 'rechazada', NULL),
(21, 38, 3, 6, '2025-11-04 13:59:03', '2025-11-04 17:59:09', 'aceptada', NULL),
(22, 39, 6, 3, '2025-11-04 17:17:57', '2025-11-04 21:18:06', 'aceptada', NULL),
(23, 40, 3, 6, '2025-11-04 17:23:06', '2025-11-04 21:23:15', 'aceptada', NULL),
(24, 41, 6, 3, '2025-11-04 17:27:06', NULL, 'pendiente', NULL),
(25, 42, 3, 6, '2025-11-04 17:29:35', '2025-11-04 21:30:12', 'aceptada', NULL),
(26, 42, 6, 3, '2025-11-04 17:31:08', '2025-11-04 21:31:32', 'aceptada', NULL),
(27, 42, 3, 6, '2025-11-04 17:32:32', NULL, 'pendiente', NULL),
(28, 43, 6, 3, '2025-11-04 17:43:22', '2025-11-04 21:43:26', 'aceptada', NULL),
(29, 44, 3, 6, '2025-11-04 18:06:54', '2025-11-04 22:06:58', 'aceptada', NULL),
(30, 45, 3, 6, '2025-11-04 18:16:41', '2025-11-04 22:16:48', 'aceptada', NULL),
(31, 46, 6, 3, '2025-11-04 18:24:53', '2025-11-04 22:24:59', 'aceptada', NULL),
(32, 47, 6, 3, '2025-11-04 18:31:31', '2025-11-04 22:31:35', 'aceptada', NULL),
(33, 48, 3, 6, '2025-11-04 18:42:01', '2025-11-04 22:42:06', 'aceptada', NULL),
(34, 49, 3, 6, '2025-11-04 18:57:06', '2025-11-04 22:57:11', 'aceptada', NULL),
(35, 49, 6, 3, '2025-11-04 19:07:00', '2025-11-04 23:12:22', 'aceptada', NULL),
(36, 49, 3, 6, '2025-11-04 19:12:42', '2025-11-04 23:12:47', 'aceptada', NULL),
(37, 49, 6, 3, '2025-11-04 19:14:37', '2025-11-04 23:15:06', 'aceptada', NULL),
(38, 49, 3, 6, '2025-11-04 19:18:27', '2025-11-04 23:18:33', 'aceptada', NULL),
(39, 49, 6, 3, '2025-11-04 19:28:49', '2025-11-04 23:29:03', 'aceptada', NULL),
(40, 49, 3, 6, '2025-11-04 19:29:26', '2025-11-04 23:29:29', 'aceptada', NULL),
(41, 51, 3, 6, '2025-11-04 19:52:00', '2025-11-04 23:52:09', 'aceptada', NULL),
(42, 51, 6, 3, '2025-11-04 20:13:38', '2025-11-05 00:13:44', 'aceptada', NULL),
(43, 51, 3, 6, '2025-11-04 21:15:01', '2025-11-05 01:15:07', 'aceptada', NULL),
(44, 52, 6, 3, '2025-11-04 21:17:52', '2025-11-05 01:17:56', 'aceptada', NULL),
(45, 52, 3, 6, '2025-11-04 21:21:23', '2025-11-05 01:21:39', 'aceptada', NULL),
(46, 52, 6, 3, '2025-11-04 21:23:51', '2025-11-05 01:24:11', 'aceptada', NULL),
(47, 52, 3, 6, '2025-11-04 21:28:19', '2025-11-05 01:42:40', 'rechazada', NULL),
(48, 52, 3, 6, '2025-11-04 21:42:59', '2025-11-05 01:44:40', 'aceptada', NULL),
(49, 53, 6, 3, '2025-11-04 22:45:37', '2025-11-05 02:45:45', 'aceptada', NULL);

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
(1, '2901786', 'Santiago', 'Caballero', 'caballerosantiago@gmail.com', '$2y$10$p.vhB4ytCvO5LALLbmJKdu3rqvg6d9ZHHnT5UOQkGpnL9.Ws.iUjO', '2025-10-28 17:44:57'),
(2, '123456', 'César', 'Algo', 'cesaralgo@gmail.com', '$2y$10$zn4t8THCF18JbcCeHKHcdedZiVsrcvVa7igkROFx.x8JWH/SAMb4O', '2025-10-27 21:40:47'),
(3, '5695298', 'Nathalia', 'Rotela', 'nathirotela5@gmail.com', '$2y$10$ucwpGwxmV1NzLmUV3Gevyuh/rgzgjoX.yJ1Y2WF8Pyv6lOFjFdLhO', '2025-10-27 21:42:19'),
(6, '12345678', 'Joaquin', 'Profe', 'joaquin@gmail.com', '$2y$10$UJaqLgentLmvdXjuVkILS.OgJBbAPgWVkc9F76MtCd6N96HC9EGP.', '2025-10-28 17:43:47');

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
(1, 2, 1, 'Proyector', 'Epson', 'C0-W012', NULL, '1fd38f17793a', 'Disponible', 0, 0, NULL, '2025-10-27 18:06:38', '2025-11-05 01:46:25', 0, 0),
(2, 2, 1, 'Monitor', 'AOC', '', NULL, 'c313dbed9f5d', 'bueno', 0, 0, NULL, '2025-10-27 18:07:51', '2025-11-04 17:00:24', 0, 0),
(4, 2, 1, 'Mouse', 'SATE', '', NULL, '6d13f0a478c9', 'bueno', 0, 0, NULL, '2025-10-27 18:08:26', '2025-11-04 21:06:26', 0, 0),
(5, 2, 1, 'Tele', '', '', NULL, 'c597582e367c', 'Disponible', 0, 0, NULL, '2025-10-27 21:35:24', '2025-11-04 00:46:01', 0, 0),
(8, 2, 3, 'gvguu', 'vjgvj', '', NULL, 'c14283f2f360', 'Disponible', 0, 0, NULL, '2025-10-28 23:00:13', '2025-10-29 02:12:54', 0, 0),
(9, 2, 3, 'bgdtt', 'hbd', 'dth', NULL, '13607aa0e833', 'Disponible', 0, 0, NULL, '2025-10-29 02:58:44', NULL, 0, 0);

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
(1, '5534142', 'Joaquín', 'Ayala', 'isrraesp19@gmail.com', '$2y$10$t0wXgPnizOJQ21N7STH1YODG0LCyGBqVMjVQTJH2Ru6iU0UGhZvH6', '2025-10-27 19:17:19'),
(2, '5695298', 'Nathi', 'Rotela', 'nathaliarotela5@gmail.com', '$2y$10$97nA8c48j7bEIvNUtjIFwOYP0R/vwJFcvUgDwxPXZX8KXwsrwKsxa', '2025-10-28 04:48:03');

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
(3, 16, 3, 2, NULL, '2025-10-27 23:50:23'),
(4, 32, 3, 6, NULL, '2025-11-03 23:05:29'),
(5, 32, 6, 3, NULL, '2025-11-03 23:10:41'),
(6, 32, 3, 6, NULL, '2025-11-03 23:11:38'),
(7, 32, 6, 3, NULL, '2025-11-03 23:25:37'),
(8, 33, 6, 3, NULL, '2025-11-04 12:27:00'),
(9, 35, 6, 3, NULL, '2025-11-04 12:42:05'),
(10, 35, 3, 6, NULL, '2025-11-04 13:41:41'),
(11, 35, 6, 3, NULL, '2025-11-04 13:46:52'),
(12, 35, 3, 6, NULL, '2025-11-04 13:48:17'),
(13, 35, 6, 3, NULL, '2025-11-04 13:54:00'),
(14, 35, 3, 6, NULL, '2025-11-04 13:54:45'),
(15, 36, 3, 6, NULL, '2025-11-04 13:56:11'),
(16, 38, 3, 6, NULL, '2025-11-04 13:59:09'),
(17, 39, 6, 3, NULL, '2025-11-04 17:18:06'),
(18, 40, 3, 6, NULL, '2025-11-04 17:23:15'),
(19, 42, 3, 6, NULL, '2025-11-04 17:30:12'),
(20, 42, 6, 3, NULL, '2025-11-04 17:31:32'),
(21, 43, 6, 3, NULL, '2025-11-04 17:43:26'),
(22, 44, 3, 6, NULL, '2025-11-04 18:06:58'),
(23, 45, 3, 6, NULL, '2025-11-04 18:16:48'),
(24, 46, 6, 3, NULL, '2025-11-04 18:24:59'),
(25, 47, 6, 3, NULL, '2025-11-04 18:31:35'),
(26, 48, 3, 6, NULL, '2025-11-04 18:42:06'),
(27, 49, 3, 6, NULL, '2025-11-04 18:57:11'),
(28, 49, 6, 3, NULL, '2025-11-04 19:12:22'),
(29, 49, 3, 6, NULL, '2025-11-04 19:12:47'),
(30, 49, 6, 3, NULL, '2025-11-04 19:15:06'),
(31, 49, 3, 6, NULL, '2025-11-04 19:18:33'),
(32, 49, 6, 3, NULL, '2025-11-04 19:29:03'),
(33, 49, 3, 6, NULL, '2025-11-04 19:29:29'),
(34, 51, 3, 6, NULL, '2025-11-04 19:52:09'),
(35, 51, 6, 3, NULL, '2025-11-04 20:13:44'),
(36, 51, 3, 6, NULL, '2025-11-04 21:15:07'),
(37, 52, 6, 3, NULL, '2025-11-04 21:17:56'),
(38, 52, 3, 6, NULL, '2025-11-04 21:21:39'),
(39, 52, 6, 3, NULL, '2025-11-04 21:24:11'),
(40, 52, 3, 6, NULL, '2025-11-04 21:44:40'),
(41, 53, 6, 3, NULL, '2025-11-04 22:45:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimientos`
--

CREATE TABLE `mantenimientos` (
  `id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `reporte_id` int(11) DEFAULT NULL,
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
(2, 5, 7, 2, 'ai', 'bgd', '2025-10-28 00:00:00', '2025-10-28 00:00:00', 1, NULL, '2025-10-28 22:53:36'),
(3, 5, 7, 3, 'htrhet', 'hdnhey', '2025-10-28 00:00:00', '2025-10-28 00:00:00', 0, NULL, '2025-10-28 22:55:01'),
(4, 5, 7, 4, 'a otro lugar', 'si', '2025-10-29 00:00:00', '2025-10-28 00:00:00', 1, NULL, '2025-10-28 23:16:42'),
(5, 5, 7, 5, 'bfndb', 'bdfrbd', '2025-10-29 00:00:00', '2025-10-28 00:00:00', 1, NULL, '2025-10-28 23:33:30'),
(6, 5, 7, 6, 'nfcn', 'dfgtf', '2025-10-29 00:00:00', '2025-10-28 00:00:00', 0, NULL, '2025-10-28 23:34:20'),
(7, 5, 7, 7, 'ftugj', 'ftgtju', '2025-10-29 00:00:00', '2025-10-28 00:00:00', 0, NULL, '2025-10-29 00:50:00'),
(8, 5, 7, 8, 'bdgb', 'tebgd', '2025-10-29 00:00:00', '2025-10-28 00:00:00', 0, NULL, '2025-10-29 01:05:06'),
(9, 5, 7, 9, 'gvjv', 'hbyybh', '2025-10-29 00:00:00', '2025-10-28 00:00:00', 1, NULL, '2025-10-29 01:35:20'),
(10, 5, 7, 10, 'hdtc', 'cgnvc', '2025-10-29 00:00:00', '2025-10-28 00:00:00', 0, NULL, '2025-10-29 01:36:39'),
(11, 5, 7, NULL, NULL, NULL, '2025-10-28 23:08:29', '2025-10-28 00:00:00', 1, NULL, '2025-10-29 02:08:29'),
(12, 8, 7, 11, 'algun lugar', 'porque si', '2025-10-29 00:00:00', '2025-10-28 00:00:00', 0, NULL, '2025-10-29 02:10:47'),
(13, 5, 7, 12, 'hndxx', 'cgnj', '2025-10-29 00:00:00', '2025-10-28 00:00:00', 1, NULL, '2025-10-29 02:11:21'),
(14, 8, 7, NULL, 'gvuuguut', 'fxdhdxfn', '2025-10-29 00:00:00', '2025-10-28 00:00:00', 1, NULL, '2025-10-29 02:12:15');

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
(3, 12, '892882bc020ac21c70eea18dacf817cab77b5eeb2075dc7498c07fe0da26bee6', '2025-11-05 02:34:18', 0, '2025-11-04 22:04:18', ''),
(4, 12, '4dc1a4a1e037b4dc4f8783b6a0186b0e95a09bbed721bef924ea420765e48b23', '2025-11-05 02:43:44', 0, '2025-11-04 22:13:44', ''),
(5, 12, '724d39ffa4f4dbfce76940d886ff0ef89152c8188c1fbabc2657d2b5d976dfd0', '2025-11-05 02:54:02', 0, '2025-11-04 22:24:02', '');

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
(16, 2, NULL, 2, 2, '2025-10-27 23:49:41', NULL, 'cancelado', '', '2025-10-28 02:49:31', NULL, NULL, NULL),
(17, 5, NULL, 3, 3, '2025-10-28 12:03:28', NULL, 'cancelado', '', '2025-10-28 15:03:09', NULL, NULL, NULL),
(18, 4, NULL, 3, 3, '2025-10-28 13:55:35', '2025-10-28 13:56:10', 'devuelto', '', '2025-10-28 16:55:29', NULL, NULL, NULL),
(19, 4, NULL, 2, 2, '2025-10-28 14:50:40', '2025-10-28 14:50:56', 'devuelto', '', '2025-10-28 17:50:33', NULL, NULL, NULL),
(20, 5, NULL, 2, 2, '2025-10-28 14:52:17', '2025-10-28 14:52:31', 'devuelto', '', '2025-10-28 17:52:11', NULL, NULL, NULL),
(21, 4, NULL, 2, 2, '2025-10-28 15:12:22', NULL, 'cancelado', '', '2025-10-28 18:12:18', NULL, NULL, NULL),
(22, 4, NULL, 2, 2, '2025-10-28 15:21:54', '2025-10-28 15:22:13', 'devuelto', '', '2025-10-28 18:21:50', NULL, NULL, NULL),
(23, 4, NULL, 2, 2, '2025-10-28 15:24:44', '2025-10-28 15:25:06', 'devuelto', '', '2025-10-28 18:24:41', NULL, NULL, NULL),
(24, 4, NULL, 2, 2, '2025-10-28 15:27:15', '2025-10-28 15:27:28', 'devuelto', '', '2025-10-28 18:27:10', NULL, NULL, NULL),
(25, 1, NULL, 3, 3, '2025-10-28 19:57:24', '2025-10-28 19:58:00', 'devuelto', '', '2025-10-28 22:57:18', NULL, NULL, NULL),
(26, 8, NULL, 3, 3, '2025-10-28 20:00:57', '2025-10-28 20:01:18', 'devuelto', '', '2025-10-28 23:00:51', NULL, NULL, NULL),
(27, 8, NULL, 3, 3, '2025-10-28 20:02:53', '2025-10-28 20:03:12', 'devuelto', '', '2025-10-28 23:02:47', NULL, NULL, NULL),
(28, 1, NULL, 3, 3, '2025-10-29 00:25:37', '2025-10-29 00:28:52', 'devuelto', '', '2025-10-29 03:25:33', NULL, NULL, NULL),
(29, 2, NULL, 3, 3, '2025-10-29 00:29:35', NULL, 'cancelado', '', '2025-10-29 03:29:32', NULL, NULL, NULL),
(30, 5, 2, NULL, 2, '2025-11-03 21:44:53', '2025-11-03 21:46:01', 'devuelto', '', '2025-11-04 00:44:43', NULL, NULL, NULL),
(31, 1, NULL, 3, 3, '2025-11-03 22:59:22', NULL, 'cancelado', '', '2025-11-04 01:59:02', NULL, NULL, NULL),
(32, 1, NULL, 3, 3, '2025-11-03 23:05:04', NULL, 'cancelado', '', '2025-11-04 02:05:01', NULL, NULL, NULL),
(33, 1, NULL, 3, 3, '2025-11-04 12:25:54', NULL, 'cancelado', '', '2025-11-04 15:25:50', NULL, NULL, NULL),
(34, 1, NULL, 6, 6, '2025-11-04 12:39:44', NULL, 'cancelado', '', '2025-11-04 15:39:44', NULL, NULL, NULL),
(35, 1, NULL, 6, 6, '2025-11-04 12:41:29', NULL, 'cancelado', '', '2025-11-04 15:41:24', NULL, NULL, NULL),
(36, 1, NULL, 6, 6, '2025-11-04 13:55:49', NULL, 'cancelado', '', '2025-11-04 16:55:42', NULL, NULL, NULL),
(37, 1, NULL, 3, 3, '2025-11-04 13:57:20', NULL, 'cancelado', '', '2025-11-04 16:57:12', NULL, NULL, NULL),
(38, 2, NULL, 6, 6, '2025-11-04 13:58:07', NULL, 'cancelado', '', '2025-11-04 16:57:59', NULL, NULL, NULL),
(39, 1, NULL, 3, 3, '2025-11-04 17:17:35', NULL, 'cancelado', '', '2025-11-04 20:17:32', NULL, NULL, NULL),
(40, 1, NULL, 6, 6, '2025-11-04 17:22:52', NULL, 'cancelado', '', '2025-11-04 20:22:48', NULL, NULL, NULL),
(41, 1, NULL, 6, 6, '2025-11-04 17:26:23', NULL, 'cancelado', '', '2025-11-04 20:26:19', NULL, NULL, NULL),
(42, 1, NULL, 3, 3, '2025-11-04 17:27:55', NULL, 'cancelado', '', '2025-11-04 20:27:50', NULL, NULL, NULL),
(43, 4, NULL, 3, 3, '2025-11-04 17:42:58', NULL, 'cancelado', '', '2025-11-04 20:42:52', NULL, NULL, NULL),
(44, 1, NULL, 6, 6, '2025-11-04 18:06:41', NULL, 'cancelado', '', '2025-11-04 21:06:37', NULL, NULL, NULL),
(45, 1, NULL, 6, 6, '2025-11-04 18:16:31', NULL, 'cancelado', '', '2025-11-04 21:16:26', NULL, NULL, NULL),
(46, 1, NULL, 3, 3, '2025-11-04 18:24:38', NULL, 'cancelado', '', '2025-11-04 21:24:35', NULL, NULL, NULL),
(47, 1, NULL, 3, 3, '2025-11-04 18:31:16', '2025-11-04 18:32:02', 'devuelto', '', '2025-11-04 21:31:13', NULL, NULL, NULL),
(48, 1, NULL, 6, 6, '2025-11-04 18:41:22', NULL, 'cancelado', '', '2025-11-04 21:41:17', NULL, NULL, NULL),
(49, 1, NULL, 6, 6, '2025-11-04 18:56:52', NULL, 'cancelado', '', '2025-11-04 21:56:47', NULL, NULL, NULL),
(50, 1, NULL, 6, 6, '2025-11-04 19:30:01', NULL, 'cancelado', '', '2025-11-04 22:29:53', NULL, NULL, NULL),
(51, 1, NULL, 6, 6, '2025-11-04 19:51:42', NULL, 'cancelado', '', '2025-11-04 22:51:35', NULL, NULL, NULL),
(52, 1, NULL, 6, 6, '2025-11-04 21:17:14', NULL, 'cancelado', '', '2025-11-05 00:17:10', NULL, NULL, NULL),
(53, 1, NULL, 3, 3, '2025-11-04 21:53:13', '2025-11-04 22:46:25', 'devuelto', '', '2025-11-05 00:53:10', NULL, NULL, NULL);

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
(2, '0000-00-00', 'slgo', 'dfb', 5, 'kevin'),
(3, '0000-00-00', 'dhb', 'dfbdfb', 5, 'kevin'),
(4, '0000-00-00', 'prueba', 'gdb', 5, 'kevin'),
(5, '0000-00-00', 'gcjgcvj', 'hbvjhv', 5, 'kevin'),
(6, '0000-00-00', 'hdthnde', 'ghfyj', 5, 'kevin'),
(7, '0000-00-00', '15516', 'rdhd', 5, 'kevin'),
(8, '0000-00-00', 'hvjcj', 'fviyfj', 5, 'kevin'),
(9, '0000-00-00', 'gtucgju', 'gyui', 5, 'kevin'),
(10, '0000-00-00', 'vggj', 'tfitju', 5, 'kevin'),
(11, '0000-00-00', 'falloooo', 'si', 8, 'kevin'),
(12, '0000-00-00', 'cgnd', 'ncgn', 5, 'kevin');

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
(2, 2, 'Laboratorio', '', '2025-10-27 17:57:59'),
(3, 2, 'Salón Auditorio', 'no', '2025-10-28 22:26:16');

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
(10, '5920912', 'perlaj34@gamil.com', 'richar', '$2y$10$ZSCq4gccx6biyaA1DIzR2eoWYX5MtoIjLAsiaqfBP6/uucowyKnBy', 2, '2025-09-29 17:43:29'),
(12, '5695298', 'nathaliarotela5@gmail.com', 'Nathi', '$2y$10$KrjgddfjprCWcA7YIANNeuc65ZsuCoShDRF0ZoxJQpAmcwd742jDa', 1, '2025-10-28 17:49:14');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=238;

--
-- AUTO_INCREMENT de la tabla `cesiones`
--
ALTER TABLE `cesiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `componentes`
--
ALTER TABLE `componentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `historial_cesiones`
--
ALTER TABLE `historial_cesiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `password_resets_estudiantes`
--
ALTER TABLE `password_resets_estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de la tabla `reporte_fallos`
--
ALTER TABLE `reporte_fallos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
