-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-02-2021 a las 14:18:13
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tp_utn`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vitacorasempleados`
--

CREATE TABLE `vitacorasempleados` (
  `id_vitacora` int(10) NOT NULL,
  `id_empleado` int(10) NOT NULL,
  `fecha_logueo` date NOT NULL,
  `hora_logueo` time NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `vitacorasempleados`
--

INSERT INTO `vitacorasempleados` (`id_vitacora`, `id_empleado`, `fecha_logueo`, `hora_logueo`, `created_at`, `updated_at`) VALUES
(1, 5, '2021-02-01', '00:00:00', '2021-02-01', '2021-02-01'),
(2, 5, '2021-02-01', '00:00:02', '2021-02-01', '2021-02-01'),
(3, 1, '2021-02-01', '02:29:25', '2021-02-01', '2021-02-01'),
(4, 2, '2021-02-01', '02:35:47', '2021-02-01', '2021-02-01'),
(5, 3, '2021-02-01', '50:51:33', '2021-02-01', '2021-02-01'),
(6, 4, '2021-02-01', '50:54:45', '2021-02-01', '2021-02-01'),
(7, 5, '2021-01-31', '23:16:59', '2021-01-31', '2021-01-31'),
(8, 5, '2021-01-31', '23:18:49', '2021-01-31', '2021-01-31');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `vitacorasempleados`
--
ALTER TABLE `vitacorasempleados`
  ADD PRIMARY KEY (`id_vitacora`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
