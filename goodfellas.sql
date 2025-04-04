-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-04-2025 a las 23:33:09
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
-- Base de datos: `goodfellas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acciones`
--

CREATE TABLE `acciones` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `ticker` varchar(10) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `ccl_compra` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `acciones`
--

INSERT INTO `acciones` (`id`, `cliente_id`, `ticker`, `cantidad`, `precio`, `fecha`, `ccl_compra`) VALUES
(117, 1, 'GGAL', 63, 7450.00, '2025-03-19', 1291.86),
(118, 2, 'GGAL', 100, 7000.00, '2025-03-31', 1317.35);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acciones_historial`
--

CREATE TABLE `acciones_historial` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `ticker` varchar(10) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha_compra` date NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `ccl_compra` decimal(10,2) NOT NULL,
  `fecha_venta` date DEFAULT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `ccl_venta` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `acciones_historial`
--

INSERT INTO `acciones_historial` (`id`, `cliente_id`, `ticker`, `cantidad`, `fecha_compra`, `precio_compra`, `ccl_compra`, `fecha_venta`, `precio_venta`, `ccl_venta`) VALUES
(66, 1, 'BBAR', 20, '2024-06-07', 4326.48, 1305.21, '2024-07-27', 4092.00, 1345.70),
(67, 1, 'BBAR', 10, '2025-01-02', 8020.00, 1174.59, '2025-01-27', 8570.00, 1176.15),
(68, 1, 'BBAR', 16, '2025-01-30', 9200.00, 1180.70, '2025-02-11', 7900.00, 1189.68),
(76, 1, 'BMA', 16, '2024-06-12', 7674.02, 1306.04, '2024-07-08', 7150.00, 1386.59),
(77, 1, 'BMA', 18, '2024-09-11', 8110.00, 1254.39, '2024-09-26', 8020.00, 1227.58),
(78, 1, 'BMA', 26, '2024-10-28', 9490.00, 1154.77, '2024-12-12', 9502.31, 1082.00),
(79, 1, 'BMA', 12, '2025-01-30', 12100.00, 1180.70, '2025-01-30', 11000.00, 1189.68),
(80, 1, 'BYMA', 145, '2024-09-05', 340.00, 1271.60, '2024-09-26', 307.00, 1227.58),
(81, 1, 'CEPU', 72, '2024-11-22', 1545.00, 1106.07, '2024-12-23', 1645.00, 1166.99),
(82, 1, 'CEPU', 115, '2025-01-30', 1710.00, 1180.70, '2025-02-11', 1555.00, 1189.68),
(83, 1, 'EDN', 57, '2024-12-13', 2653.16, 1078.10, '2025-01-22', 2646.14, 1187.60),
(84, 1, 'GGAL', 50, '2024-07-02', 4257.89, 1307.08, '2024-07-08', 3956.05, 1386.59),
(85, 1, 'GGAL', 47, '2024-09-12', 5401.91, 1256.70, '2024-09-26', 5280.00, 1227.58),
(86, 1, 'GGAL', 77, '2024-10-28', 6400.00, 1154.77, '2024-12-30', 6916.10, 1146.64),
(87, 1, 'GGAL', 35, '2025-01-30', 8260.00, 1180.70, '2025-02-11', 7210.00, 1189.68),
(88, 1, 'METR', 111, '2024-11-13', 1780.00, 1155.92, '2025-01-27', 2555.00, 1176.15),
(89, 1, 'METR', 55, '2025-01-30', 2660.00, 1180.70, '2025-02-11', 2145.00, 1189.68),
(90, 1, 'PAMP', 17, '2024-09-12', 2921.18, 1270.91, '2024-09-26', 2855.00, 1227.58),
(91, 1, 'PAMP', 94, '2025-01-30', 3857.34, 1158.20, '2025-02-11', 3680.00, 1189.68),
(92, 1, 'TGSU2', 44, '2024-10-28', 5370.00, 1154.77, '2025-01-27', 6910.91, 1181.61),
(93, 1, 'TGSU2', 21, '2025-01-30', 6900.00, 1180.70, '2025-02-11', 6090.00, 1189.68);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(1, 'paimarino@gmail.com', '$2y$10$9xTgQDcSkpQBK9gNNIMWwufNMuOgQzzopkPZcdQ8.m9NOumkVgZh2'),
(2, 'manuellaguzzi@gmail.com', '$2y$10$TxUOKcwzcxcFRKEgqX/iOeZSzH64OAXGLLS6gNtb5wcK2h6ydcF7K');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `balance`
--

CREATE TABLE `balance` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `efectivo` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `balance`
--

INSERT INTO `balance` (`id`, `cliente_id`, `efectivo`) VALUES
(5, 1, 0.00),
(6, 2, 750000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bonos`
--

CREATE TABLE `bonos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `ticker_bonos` varchar(10) NOT NULL,
  `fecha_bonos` date NOT NULL,
  `cantidad_bonos` int(11) NOT NULL,
  `precio_bonos` decimal(10,2) NOT NULL,
  `ccl_compra` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bonos`
--

INSERT INTO `bonos` (`id`, `cliente_id`, `ticker_bonos`, `fecha_bonos`, `cantidad_bonos`, `precio_bonos`, `ccl_compra`) VALUES
(12, 1, 'GD35', '2025-01-30', 8997, 757.71, 1185.27),
(13, 1, 'GD41', '2025-01-30', 2480, 757.20, 1180.70),
(14, 2, 'GD35', '2025-03-31', 1012, 790.00, 1317.35);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bonos_historial`
--

CREATE TABLE `bonos_historial` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `ticker_bonos` varchar(10) NOT NULL,
  `cantidad_bonos` int(11) NOT NULL,
  `fecha_compra_bonos` date NOT NULL,
  `precio_compra_bonos` decimal(10,2) NOT NULL,
  `ccl_compra` decimal(10,2) NOT NULL,
  `fecha_venta_bonos` date DEFAULT NULL,
  `precio_venta_bonos` decimal(10,2) NOT NULL,
  `ccl_venta` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bonos_historial`
--

INSERT INTO `bonos_historial` (`id`, `cliente_id`, `ticker_bonos`, `cantidad_bonos`, `fecha_compra_bonos`, `precio_compra_bonos`, `ccl_compra`, `fecha_venta_bonos`, `precio_venta_bonos`, `ccl_venta`) VALUES
(8, 1, 'GD35', 7409, '2024-10-18', 650.59, 1194.62, '2025-01-09', 704.00, 1154.62),
(9, 1, 'GD35', 2029, '2025-01-30', 757.71, 1185.27, '2025-03-12', 807.00, 1221.30),
(10, 1, 'GD41', 1434, '2025-01-30', 757.20, 1180.70, '2025-03-31', 766.00, 1317.35);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cedear`
--

CREATE TABLE `cedear` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `ticker_cedear` varchar(10) NOT NULL,
  `fecha_cedear` date NOT NULL,
  `cantidad_cedear` int(11) NOT NULL,
  `precio_cedear` decimal(10,2) NOT NULL,
  `ccl_compra_cedear` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cedear`
--

INSERT INTO `cedear` (`id`, `cliente_id`, `ticker_cedear`, `fecha_cedear`, `cantidad_cedear`, `precio_cedear`, `ccl_compra_cedear`) VALUES
(40, 1, 'AAPL', '2025-02-27', 187, 14044.12, 1202.28),
(43, 1, 'AXP', '2025-01-30', 39, 25025.00, 1180.70),
(44, 1, 'ETHA', '2024-12-16', 8, 6690.00, 1122.06),
(45, 1, 'EWZ', '2025-01-30', 13, 14800.00, 1180.70),
(46, 1, 'GLD', '2025-02-04', 87, 6133.56, 1185.27),
(47, 1, 'IBIT', '2025-01-30', 6, 7090.00, 1180.70),
(50, 1, 'PLTR', '2025-02-12', 15, 20912.42, 1254.30),
(51, 1, 'SAP', '2025-02-14', 4, 58000.00, 1197.21),
(52, 1, 'WMT', '2025-01-31', 67, 5964.18, 1140.67),
(53, 1, 'NVDA', '2025-02-07', 79, 6267.70, 1237.54),
(54, 2, 'GLD', '2025-03-31', 10, 7400.00, 1317.35),
(55, 2, 'SAP', '2025-03-31', 10, 58000.00, 1317.35),
(56, 0, 'GLD', '2025-04-01', 0, 7530.00, NULL),
(57, 0, 'SAP', '2025-04-01', 0, 59100.00, NULL),
(58, 0, 'GLD', '2025-04-01', 0, 7540.00, NULL),
(59, 0, 'SAP', '2025-04-01', 0, 59100.00, NULL),
(60, 0, 'GLD', '2025-04-01', 0, 7540.00, NULL),
(61, 0, 'SAP', '2025-04-01', 0, 59100.00, NULL),
(62, 0, 'GLD', '2025-04-01', 0, 7540.00, NULL),
(63, 0, 'SAP', '2025-04-01', 0, 59100.00, NULL),
(64, 0, 'AAPL', '2025-04-01', 0, 14650.00, NULL),
(65, 0, 'AXP', '2025-04-01', 0, 23550.00, NULL),
(66, 0, 'ETHA', '2025-04-01', 0, 3785.00, NULL),
(67, 0, 'EWZ', '2025-04-01', 0, 17125.00, NULL),
(68, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(69, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(70, 0, 'PLTR', '2025-04-01', 0, 37000.00, NULL),
(71, 0, 'NVDA', '2025-04-01', 0, 6000.00, NULL),
(72, 0, 'AAPL', '2025-04-01', 0, 14650.00, NULL),
(73, 0, 'AXP', '2025-04-01', 0, 23550.00, NULL),
(74, 0, 'ETHA', '2025-04-01', 0, 3785.00, NULL),
(75, 0, 'EWZ', '2025-04-01', 0, 17125.00, NULL),
(76, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(77, 0, 'IBIT', '2025-04-01', 0, 6300.00, NULL),
(78, 0, 'PLTR', '2025-04-01', 0, 37000.00, NULL),
(79, 0, 'NVDA', '2025-04-01', 0, 6000.00, NULL),
(80, 0, 'AAPL', '2025-04-01', 0, 14650.00, NULL),
(81, 0, 'AXP', '2025-04-01', 0, 23550.00, NULL),
(82, 0, 'ETHA', '2025-04-01', 0, 3800.00, NULL),
(83, 0, 'EWZ', '2025-04-01', 0, 17125.00, NULL),
(84, 0, 'GLD', '2025-04-01', 0, 7560.00, NULL),
(85, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(86, 0, 'PLTR', '2025-04-01', 0, 36900.00, NULL),
(87, 0, 'NVDA', '2025-04-01', 0, 6000.00, NULL),
(88, 0, 'AAPL', '2025-04-01', 0, 14550.00, NULL),
(89, 0, 'AXP', '2025-04-01', 0, 23675.00, NULL),
(90, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(102, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(103, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(104, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(105, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(106, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(107, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(108, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(109, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(110, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(111, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(112, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(113, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(114, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(115, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(116, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(117, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(118, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(119, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(120, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(121, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(122, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(123, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(124, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(125, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(126, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(127, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(128, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(129, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(130, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(131, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(132, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(133, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(134, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(135, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(136, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(137, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(138, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(139, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(140, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(141, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(142, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(143, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(144, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(145, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(146, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(147, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(148, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(149, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(150, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(151, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(152, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(153, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(154, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(155, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(156, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(157, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(158, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(159, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(160, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(161, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(162, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(163, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(164, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(165, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(166, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(167, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(168, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(169, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(170, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(171, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(172, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(173, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(174, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(175, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(176, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(177, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(178, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(179, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(180, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(181, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(182, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(183, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(184, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(185, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(186, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(187, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(188, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(189, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(190, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(191, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(192, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(193, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(194, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(195, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(196, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(197, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(198, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(199, 0, 'SAP', '2025-04-01', 0, 58900.00, NULL),
(200, 0, 'WMT', '2025-04-01', 0, 6460.00, NULL),
(201, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(202, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(203, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(204, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(205, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(206, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(207, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(208, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(209, 0, 'SAP', '2025-04-01', 0, 58900.00, NULL),
(210, 0, 'WMT', '2025-04-01', 0, 6460.00, NULL),
(211, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(212, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(213, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(214, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(215, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(216, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(217, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(218, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(219, 0, 'SAP', '2025-04-01', 0, 58900.00, NULL),
(220, 0, 'WMT', '2025-04-01', 0, 6460.00, NULL),
(221, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(222, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(223, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(224, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(225, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(226, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(227, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(228, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(229, 0, 'SAP', '2025-04-01', 0, 58900.00, NULL),
(230, 0, 'WMT', '2025-04-01', 0, 6460.00, NULL),
(231, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(232, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(233, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(234, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(235, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(236, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(237, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(238, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(239, 0, 'SAP', '2025-04-01', 0, 58900.00, NULL),
(240, 0, 'WMT', '2025-04-01', 0, 6460.00, NULL),
(241, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(242, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(243, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(244, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(245, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(246, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(247, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(248, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(249, 0, 'SAP', '2025-04-01', 0, 58900.00, NULL),
(250, 0, 'WMT', '2025-04-01', 0, 6460.00, NULL),
(251, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(252, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(253, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(254, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(255, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(256, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(257, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(258, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(259, 0, 'SAP', '2025-04-01', 0, 58900.00, NULL),
(260, 0, 'WMT', '2025-04-01', 0, 6460.00, NULL),
(261, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(262, 0, 'AAPL', '2025-04-01', 0, 14600.00, NULL),
(263, 0, 'AXP', '2025-04-01', 0, 23600.00, NULL),
(264, 0, 'ETHA', '2025-04-01', 0, 3805.00, NULL),
(265, 0, 'EWZ', '2025-04-01', 0, 17175.00, NULL),
(266, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(267, 0, 'IBIT', '2025-04-01', 0, 6340.00, NULL),
(268, 0, 'PLTR', '2025-04-01', 0, 36950.00, NULL),
(269, 0, 'SAP', '2025-04-01', 0, 58900.00, NULL),
(270, 0, 'WMT', '2025-04-01', 0, 6460.00, NULL),
(271, 0, 'NVDA', '2025-04-01', 0, 5990.00, NULL),
(272, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(273, 0, 'SAP', '2025-04-01', 0, 58900.00, NULL),
(274, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(275, 0, 'SAP', '2025-04-01', 0, 58900.00, NULL),
(276, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(277, 0, 'SAP', '2025-04-01', 0, 58900.00, NULL),
(278, 0, 'GLD', '2025-04-01', 0, 7550.00, NULL),
(279, 0, 'SAP', '2025-04-01', 0, 58900.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cedear_historial`
--

CREATE TABLE `cedear_historial` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `ticker_cedear` varchar(10) NOT NULL,
  `cantidad_cedear` int(11) NOT NULL,
  `fecha_compra_cedear` date NOT NULL,
  `precio_compra_cedear` decimal(10,2) NOT NULL,
  `ccl_compra` decimal(10,2) NOT NULL,
  `fecha_venta_cedear` date DEFAULT NULL,
  `precio_venta_cedear` decimal(10,2) NOT NULL,
  `ccl_venta` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cedear_historial`
--

INSERT INTO `cedear_historial` (`id`, `cliente_id`, `ticker_cedear`, `cantidad_cedear`, `fecha_compra_cedear`, `precio_compra_cedear`, `ccl_compra`, `fecha_venta_cedear`, `precio_venta_cedear`, `ccl_venta`) VALUES
(32, 1, 'AAPL', 154, '2024-09-12', 13530.50, 1269.47, '2025-01-14', 13491.00, 1149.21),
(33, 1, 'AAPL', 35, '2025-02-27', 14044.12, 1202.28, '2025-03-19', 13925.00, 1291.86),
(34, 1, 'AMZN', 102, '2024-07-08', 1963.66, 1386.59, '2024-08-28', 1545.00, 1291.78),
(35, 1, 'AMZN', 140, '2024-09-23', 1578.57, 1255.22, '2024-10-18', 1560.00, 1194.62),
(36, 1, 'AXP', 6, '2024-06-05', 20360.50, 1304.57, '2024-06-27', 20387.50, 1345.70),
(37, 1, 'BRKB', 8, '2024-06-06', 24450.00, 1297.19, '2024-09-23', 25400.00, 1227.16),
(38, 1, 'COIN', 10, '2024-11-13', 13522.50, 1161.15, '2024-11-14', 11775.00, 1157.72),
(39, 1, 'GOOGL', 48, '2024-06-27', 4333.00, 1345.70, '2024-08-28', 3640.00, 1291.78),
(40, 1, 'MELI', 18, '2024-11-11', 17790.28, 1159.71, '2024-11-25', 19250.00, 1110.11),
(41, 1, 'META', 6, '2024-09-23', 28950.00, 1227.16, '2024-09-26', 29000.00, 1227.58),
(42, 1, 'MSFT', 33, '2024-09-11', 18148.94, 1275.72, '2024-10-03', 17100.00, 1232.44),
(43, 1, 'MSTR', 3, '2024-11-13', 21375.00, 1155.92, '2024-11-14', 18550.00, 1157.72),
(44, 1, 'MSTR', 5, '2024-11-19', 22900.00, 1106.27, '2025-01-03', 17750.00, 1186.04),
(47, 1, 'PLTR', 7, '2024-08-08', 12650.00, 1311.10, '2024-10-23', 16875.00, 1183.91),
(48, 1, 'PLTR', 2, '2025-01-30', 16351.04, 1266.87, '2025-02-04', 41925.00, 1199.70),
(49, 1, 'PLTR', 11, '2025-02-12', 20912.42, 1254.30, '2025-02-24', 37675.00, 1213.16),
(50, 1, 'SPY', 9, '2024-06-18', 34588.33, 1291.10, '2024-08-08', 34875.00, 1311.10),
(51, 1, 'TGT ', 56, '2024-11-25', 6050.00, 1110.11, '2024-12-26', 6680.00, 1187.62),
(52, 1, 'TSLA ', 12, '2024-09-12', 18860.42, 1251.63, '2024-10-18', 17625.00, 1194.62),
(53, 1, 'TSLA ', 3, '2024-12-23', 33725.00, 1166.99, '2025-01-02', 29725.00, 1174.59),
(54, 1, 'TSM ', 9, '2024-06-18', 24868.22, 1282.51, '2024-08-28', 24375.00, 1291.78),
(55, 1, 'TSM ', 12, '2024-09-12', 23950.00, 1259.83, '2024-10-23', 26450.00, 1183.91),
(56, 1, 'NVDA', 30, '2024-06-06', 6566.38, 1300.88, '2024-08-28', 6760.00, 1291.78),
(57, 1, 'NVDA', 102, '2024-09-12', 6125.43, 1260.26, '2024-09-18', 6010.00, 1233.11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `corredora` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `cliente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`email`, `password`, `nombre`, `apellido`, `telefono`, `corredora`, `url`, `cliente_id`) VALUES
('peilo@gmail.com', '$2y$10$PjhGqrQJX.Iut8D3kTRdTOVFMVcYKcwQK9B3psbg4AdhgquRwmRnS', 'Pablo', 'Marino', '2944-69-3756', 'Balanz', 'https://clientes.balanz.com/auth/login', 1),
('el.bueno.de.harry@gmail.com', '$2y$10$4jt1Ih//UUN0tVk7bGels.8ryHVlWAMizOoWdH2cBFDOaN5u7gXt2', 'Harry', 'Flashman', '123456', 'Balanz', 'https://clientes.balanz.com/auth/login', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fondos`
--

CREATE TABLE `fondos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `ticker_fondos` varchar(10) NOT NULL,
  `fecha_fondos` date NOT NULL,
  `cantidad_fondos` int(11) NOT NULL,
  `precio_fondos` decimal(10,2) NOT NULL,
  `ccl_compra` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fondos_historial`
--

CREATE TABLE `fondos_historial` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `ticker_fondos` varchar(10) NOT NULL,
  `cantidad_fondos` int(11) NOT NULL,
  `fecha_compra_fondos` date NOT NULL,
  `precio_compra_fondos` decimal(10,2) NOT NULL,
  `ccl_compra` decimal(10,2) NOT NULL,
  `fecha_venta_fondos` date DEFAULT NULL,
  `precio_venta_fondos` decimal(10,2) NOT NULL,
  `ccl_venta` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickers_acciones`
--

CREATE TABLE `tickers_acciones` (
  `id` int(11) NOT NULL,
  `ticker` varchar(10) NOT NULL,
  `company_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tickers_acciones`
--

INSERT INTO `tickers_acciones` (`id`, `ticker`, `company_name`) VALUES
(1, 'AGRO', 'Agrometal\r'),
(2, 'ALUA', 'Aluar Aluminio Argentino\r'),
(3, 'AUSO', 'Autopistas del Sol\r'),
(4, 'BBAR', 'Banco BBVA Argentina\r'),
(5, 'VALO', 'Banco de Valores\r'),
(6, 'BHIP', 'Banco Hipotecario\r'),
(7, 'BMA', 'Banco Macro\r'),
(8, 'BPAT', 'Banco Patagonia\r'),
(9, 'GAMI', 'B-Gaming\r'),
(10, 'BOLT', 'Boldt\r'),
(11, 'BYMA', 'Bolsas y Mercados Argentinos\r'),
(12, 'CVH', 'Cablevisión Holding\r\n'),
(13, 'CGPA2', 'Camuzzi Gas Pampeana\r'),
(14, 'CAPX', 'Capex\r'),
(15, 'CARC', 'Carboclor\r'),
(16, 'CADO', 'Carlos Casado\r'),
(17, 'CELU', 'Celulosa Argentina\r'),
(18, 'CECO2', 'Central Costanera\r'),
(19, 'CEPU', 'Central Puerto\r'),
(20, 'URBA', 'Central Urbana\r'),
(21, 'COMO', 'Compañía Argentina de Comodoro Rivadavia\r\n'),
(22, 'INTR', 'Compañía Introductora de Buenos Aires\r\n'),
(23, 'CTIO', 'Consultatio\r'),
(24, 'COUR', 'Continental Urbana\r'),
(25, 'CRES', 'Cresud\r'),
(26, 'DGCU2', 'Distribuidora de Gas Cuyana\r'),
(27, 'DGCE', 'Distribuidora de Gas del Centro\r'),
(28, 'DOME', 'Domec\r'),
(29, 'IEB', 'Dycasa\r'),
(30, 'EDSH', 'Edesa Holding\r'),
(31, 'EDLH', 'Edesal Holding\r'),
(32, 'EMAC', 'Electromac\r'),
(33, 'EDN', 'Edenor\r'),
(34, 'DSUR', 'Edesur\r'),
(35, 'FERR', 'Ferrum\r'),
(36, 'FIPL', 'Fiplasto\r'),
(37, 'REGE', 'Garcia Reguera\r'),
(38, 'GARO', 'Garovaglio y Zorraquin\r'),
(39, 'GCDI', 'Gcdi\r'),
(40, 'GRIM', 'Grimoldi\r'),
(41, 'GCLA', 'Grupo Clarin\r'),
(42, 'OEST', 'Grupo Concesionario del Oeste\r'),
(43, 'GGAL', 'Grupo Financiero Galicia\r'),
(44, 'SUPV', 'Grupo Supervielle\r'),
(45, 'HAVA', 'Havanna Holding\r'),
(46, 'HARG', 'Holcim (Argentina)\r'),
(47, 'HSAT', 'Holdsat\r'),
(48, 'HULI', 'Hulytego\r'),
(49, 'PATA', 'Importadora y Exportadora de la Patagonia\r'),
(50, 'ROSE', 'Instituto Rosenbusch\r'),
(51, 'INAG', 'Insumos Agroquimicos\r'),
(52, 'ECOG', 'Inversora de Gas del Centro\r'),
(53, 'IEBA', 'Inversora Electrica de Buenos Aires\r'),
(54, 'INVJ', 'Inversora Juramento\r'),
(55, 'IRSA', 'Irsa Inversiones y Representaciones\r'),
(56, 'RICH', 'Laboratorios Richmond\r'),
(57, 'LEDE', 'Ledesma\r'),
(58, 'LOMA', 'Loma Negra\r'),
(59, 'LONG', 'Longvie\r'),
(60, 'MTR', 'Matba Rofex\r'),
(61, 'METR', 'Metrogas\r'),
(62, 'MIRG', 'Mirgor\r'),
(63, 'MOLA', 'Molinos Agro\r'),
(64, 'SEMI', 'Molinos Juan Semino\r'),
(65, 'MOLI', 'Molinos Rio de la Plata\r'),
(66, 'MORI', 'Morixe Hermanos\r'),
(67, 'GBAN', 'Naturgy Ban\r'),
(68, 'NCON', 'Nuevo Continente\r'),
(69, 'OVOP', 'Ovoprot International\r'),
(70, 'PAMP', 'Pampa Energía\r\n'),
(71, 'PREN1', 'Papel Prensa\r'),
(72, 'PATR', 'Patricios\r'),
(73, 'POLL', 'Polledo\r'),
(74, 'RIGO', 'Rigolleau\r'),
(75, 'SAMI', 'S.A. San Miguel\r'),
(76, 'COME', 'Sociedad Comercial del Plata\r'),
(77, 'TECO2', 'Telecom Argentina\r'),
(78, 'TXAR', 'Ternium Argentina\r'),
(79, 'TRAN', 'Transener\r'),
(80, 'TGNO4', 'Transportadora de Gas del Norte\r'),
(81, 'TGSU2', 'Transportadora de Gas del Sur\r'),
(82, 'YPFD', 'YPF\r');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickers_cedears`
--

CREATE TABLE `tickers_cedears` (
  `id` int(11) NOT NULL,
  `ticker` varchar(10) NOT NULL,
  `company_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tickers_cedears`
--

INSERT INTO `tickers_cedears` (`id`, `ticker`, `company_name`) VALUES
(1, 'AAL', 'Americn Arlns Gp'),
(2, 'AAP', 'Advance Auto Parts Inc'),
(3, 'AAPL', 'Apple Inc'),
(4, 'ABBV', 'Abbvie Inc'),
(5, 'ABEV', 'Ambev'),
(6, 'ABNB', 'Airbnb Inc'),
(7, 'ABT', 'Abbott Labs'),
(8, 'ACN', 'Accenture PLC'),
(9, 'ADBE', 'Adobe Sys'),
(10, 'ADGO', 'Adecoagro'),
(11, 'ADI', 'Analog Devices'),
(12, 'ADP', 'Automatic Data'),
(13, 'AEG', 'Aegon'),
(14, 'AEM', 'Agnico Eagle Mines'),
(15, 'AIG', 'Amer Intl Group'),
(16, 'AMAT', 'Applied Material'),
(17, 'AMD', 'Adv Micro Device'),
(18, 'AMGN', 'Amgen Inc'),
(19, 'AMX', 'America Movil'),
(20, 'AMZN', 'Amazon'),
(21, 'ANF', 'Abercrombies'),
(22, 'ARCO', 'Arcos Dorados'),
(23, 'ARKK', 'Ark Innvtion Etf'),
(24, 'ASR', 'Grupo Aeroportuario del Sureste'),
(25, 'AVGO', 'Broadcom Inc'),
(26, 'AVY', 'Avery Dennison'),
(27, 'AXP', 'American Express'),
(28, 'AZN', 'AstraZeneca'),
(29, 'BA', 'Boeing'),
(30, 'BABA', 'Alibaba Group'),
(31, 'BAK', 'Braskem'),
(32, 'BAS', 'BASF'),
(33, 'BB', 'Research In Motion'),
(34, 'BBAS3', 'Banco do Brasil'),
(35, 'BBD', 'Banco Bradesco'),
(36, 'BBV', 'Banco Bilbao Vizcaya Argentaria'),
(37, 'BCS', 'Barclays'),
(38, 'BHP', 'BHP Billiton'),
(39, 'BIDU', 'Baidu'),
(40, 'BIIB', 'Biogen Inc'),
(41, 'BIOX', 'Bioceres S.A.'),
(42, 'BITF', 'Bitfarms Ltd'),
(43, 'BK', 'Bank Of New York'),
(44, 'BKNG', 'Booking Holdings'),
(45, 'BKR', 'Baker Hughes Co'),
(46, 'BMY', 'Bristol-Myers Squibb Co'),
(47, 'BNG', 'Bunge'),
(48, 'BP', 'BP'),
(49, 'BRFS', 'BRF S.A.'),
(50, 'BRKB', 'Berkshire Hathaway Inc'),
(51, 'BSBR', 'Banco Santander Brasil'),
(52, 'C', 'Citigroup'),
(53, 'CAAP', 'Corp America Airports Sa'),
(54, 'CAH', 'Cardinal Health'),
(55, 'CAR', 'Avis Budget Group'),
(56, 'CAT', 'Caterpillar'),
(57, 'CCL', 'Carnival Corp'),
(58, 'CDE', 'Coeur Mining Inc'),
(59, 'CL', 'Colgate'),
(60, 'COIN', 'Coinbs Gbl'),
(61, 'COST', 'Costco Wholesal'),
(62, 'CRM', 'Salesforce Coms'),
(63, 'CSCO', 'Cisco Systems'),
(64, 'CVS', 'CVS Health Corp'),
(65, 'CVX', 'Chevron Corp'),
(66, 'CX', 'Cemex'),
(67, 'DAL', 'Delta Air Lines Inc.'),
(68, 'DD', 'E I Du Pont De Nemours'),
(69, 'DE', 'Deere and Co'),
(70, 'DEO', 'Diageo'),
(71, 'DESP', 'Despegar'),
(72, 'DHR', 'Danaher Corp'),
(73, 'DIA', 'Spdr Dji Average'),
(74, 'DISN', 'Walt Disney'),
(75, 'DOCU', 'Docusign Inc'),
(76, 'DOW', 'Dow Inc'),
(77, 'DTEA', 'Deutsche Telekom'),
(78, 'E', 'ENI'),
(79, 'EA', 'Electronic Art'),
(80, 'EBAY', 'Ebay Inc'),
(81, 'EBR', 'Centrais Eletricas Brasileiras S.A.'),
(82, 'EEM', 'Ish Msci Em Mkt'),
(83, 'EFX', 'Equifax Inc'),
(84, 'ELP', 'Copel'),
(85, 'EOAN', 'E.ON SE'),
(86, 'ERIC', 'Telefonaktiebolaget Lm Ericsson'),
(87, 'ERJ', 'Embraer SA'),
(88, 'ETHA', 'iShares Ethereum Trust ETF'),
(89, 'ETSY', 'Etsy Inc'),
(90, 'EWZ', 'Ishs Msci Brazil'),
(91, 'FCX', 'Freeport Mcmoran'),
(92, 'FDX', 'Fedex'),
(93, 'FMCC', 'Freddie Mac'),
(94, 'FMX', 'Fomento Economico Mexicano'),
(95, 'FNMA', 'Fannie Mae'),
(96, 'FSLR', 'First Solar Inc'),
(97, 'FXI', 'iShares China Large-Cap ETF'),
(98, 'GE', 'General Electric'),
(99, 'GFI', 'Gold Fields'),
(100, 'GGB', 'Gerdau SA ADR'),
(101, 'GILD', 'Gilead Sci'),
(102, 'GLD', 'SPDR Gold Trust ETF'),
(103, 'GLOB', 'Globant'),
(104, 'GLW', 'Corning Inc'),
(105, 'GM', 'Electronic Data Systems'),
(106, 'GOLD', 'Barrick Gold Corp'),
(107, 'GOOGL', 'Alphabet Inc Cl A'),
(108, 'GPRK', 'Geopark Ltd'),
(109, 'GRMN', 'Garmin Ltds'),
(110, 'GS', 'Goldman Sachs'),
(111, 'GSK', 'Glaxosmithkline'),
(112, 'GT', 'Goodyear Tire & Rubber Co'),
(113, 'HAL', 'Halliburton Co'),
(114, 'HAPV3', 'Hapvida Par e Invest'),
(115, 'HD', 'Home Depot'),
(116, 'HDB', 'HDFC Bank'),
(117, 'HHPD', 'Hon Hai Precision Industry Co Ltd'),
(118, 'HL', 'Hecla Mining'),
(119, 'HMC', 'Honda Motor'),
(120, 'HMY', 'Harmony Gold Mining Company'),
(121, 'HOG', 'Harley Davidson'),
(122, 'HON', 'Honeywell Intl'),
(123, 'HPQ', 'HP Inc'),
(124, 'HSBC', 'HSBC Holdings'),
(125, 'HSY', 'Hershey Foods'),
(126, 'HUT', 'Hut 8 Mining Corp'),
(127, 'HWM', 'Howmet Arspc Inc'),
(128, 'IBB', 'iShares Nasdaq Biotechnology ETF'),
(129, 'IBIT', 'iShares Bitcoin Trust ETF'),
(130, 'IBM', 'International Business Machines'),
(131, 'IBN', 'ICICI Bank'),
(132, 'IEUR', 'iShares Core MSCI Europe ETF'),
(133, 'IFF', 'Intl Flav & Frag'),
(134, 'INFY', 'Infosys Technologies'),
(135, 'ING', 'Ing Groep 3 Rep 1'),
(136, 'INTC', 'Intel'),
(137, 'IP', 'Intl Paper'),
(138, 'ISRG', 'Intuitive Surgical, Inc.'),
(139, 'ITUB', 'Itau Unibanco Holding S.A.'),
(140, 'IVE', 'iShares S&P 500 Value ETF'),
(141, 'IVW', 'iShares S&P 500 Growth ETF'),
(142, 'IWM', 'Ish Rsl 2000'),
(143, 'JD', 'JD.Com Inc'),
(144, 'JMIA', 'Adr Jumia Technologies Ag'),
(145, 'JNJ', 'Johnson & Johnson'),
(146, 'JPM', 'JP Morgan Chase'),
(147, 'KB', 'Kookmin Bank'),
(148, 'KEP', 'Korea Electric Power Corp'),
(149, 'KGC', 'Kinross Gold Corp'),
(150, 'KMB', 'Kimberly Clark'),
(151, 'KO', 'Coca-Cola'),
(152, 'KOFM', 'Coca-Cola FEMSA'),
(153, 'LAAC', 'Lithium Americas (Argentina) Corp'),
(154, 'LAC', 'Lithium Americas Corp'),
(155, 'LLY', 'Eli Lilly and Co'),
(156, 'LMT', 'Lockheed Martin'),
(157, 'LND', 'Brasilagro Coms'),
(158, 'LRCX', 'Lam Research'),
(159, 'LREN3', 'Lojas Renner'),
(160, 'LVS', 'Las Vegas Sands Corp'),
(161, 'LYG', 'Lloyds Banking Group PLC'),
(162, 'MA', 'Mastercard Inc'),
(163, 'MBG', 'DaimlerChrysler'),
(164, 'MCD', 'McDonald\'s'),
(165, 'MDLZ', 'Mondelez Int Inc'),
(166, 'MDT', 'Medtronic Plc'),
(167, 'MELI', 'Mercado Libre Inc'),
(168, 'META', 'Meta Platforms Inc.'),
(169, 'MFG', 'Mizuho Financial Group'),
(170, 'MGLU3', 'Magazine Luiza'),
(171, 'MMC', 'Marsh & Mclennan'),
(172, 'MMM', '3M'),
(173, 'MO', 'Altria Group'),
(174, 'MOS', 'The Mosaic Co'),
(175, 'MRK', 'Merck'),
(176, 'MRNA', 'Moderna Inc.'),
(177, 'MRVL', 'Marvell Technology Ord'),
(178, 'MSFT', 'Microsoft Cp'),
(179, 'MSI', 'Motorola'),
(180, 'MSTR', 'Microstrategy Inc Cl A New'),
(181, 'MU', 'Micron Technology Inc'),
(182, 'MUFG', 'Mitsubishi Tokyo Financial Group'),
(183, 'MUX', 'Mcewen Mining Ord'),
(184, 'NEC1', 'NEC'),
(185, 'NEM', 'Newmont Goldcorp'),
(186, 'NFLX', 'Netflix Incs'),
(187, 'NG', 'Novagold Rss'),
(188, 'NIO', 'Nio Inc'),
(189, 'NKE', 'Nike Inc'),
(190, 'NMR', 'Nomura Holdings'),
(191, 'NOKA', 'Nokia'),
(192, 'NSAN', 'Nissan Motor'),
(193, 'NTES', 'NetEase Inc'),
(194, 'NU', 'Nu Holding Ltd Vayman Island Ord'),
(195, 'NUE', 'Nucor Corp'),
(196, 'NVDA', 'Nvidia Corporation'),
(197, 'NVS', 'Novartis'),
(198, 'NXE', 'NexGen Energy Ltd'),
(199, 'ORAN', 'Orange'),
(200, 'ORCL', 'Oracle Corp'),
(201, 'ORLY', 'O\'Reilly Automotive Inc'),
(202, 'OXY', 'Occidental Petroleum Corp'),
(203, 'PAAS', 'Pan American Silver Corp'),
(204, 'PAC', 'Grupo Aeroportuario del Pacifico'),
(205, 'PAGS', 'Pagseguro Digital Ltd Ord'),
(206, 'PANW', 'Palo Alto Networks Inc'),
(207, 'PBI', 'Pitney Bowes Inc'),
(208, 'PBR', 'Petrobras Ord Shs'),
(209, 'PCAR', 'Paccar'),
(210, 'PEP', 'Pepsico Inc'),
(211, 'PFE', 'Pfizer Rep 0.5'),
(212, 'PG', 'Procter & Gamble'),
(213, 'PHG', 'Koninklijke Philips Electronics'),
(214, 'PINS', 'Pinterest'),
(215, 'PKS', 'Posco'),
(216, 'PLTR', 'Palantir Technologies Ord'),
(217, 'PM', 'Philip Morris Int Inc'),
(218, 'PRIO3', 'Prio'),
(219, 'PSX', 'Phillips 66'),
(220, 'PYPL', 'Paypal Holdings Incs'),
(221, 'QCOM', 'Qualcomm Inc'),
(222, 'QQQ', 'Invesco Qqq S1'),
(223, 'RACE', 'FERRARI NV'),
(224, 'RBLX', 'Roblox Corp'),
(225, 'RENT3', 'Localiza Rent a Car'),
(226, 'RIO', 'Rio Tinto'),
(227, 'RIOT', 'Riot Platforms'),
(228, 'ROKU', 'Roku'),
(229, 'ROST', 'Rost Stones Inc'),
(230, 'RTX', 'Raytheon Technologies Corp'),
(231, 'SAN', 'Banco Santander Ord Shs'),
(232, 'SAP', 'Sap Se'),
(233, 'SATL', 'Satellogic Inc'),
(234, 'SBS', 'Basic Sant Cpy of the Sta Pul SBSP'),
(235, 'SBUX', 'Starbucks'),
(236, 'SCCO', 'Southern Copper Corp'),
(237, 'SCHW', 'Charles Schwab Corp.'),
(238, 'SDA', 'Suncar Technology Group Ord'),
(239, 'SE', 'Sea Limited'),
(240, 'SH', 'ProShares Short S&P500 ETF'),
(241, 'SHEL', 'Shell Plc'),
(242, 'SHOP', 'Shopify Inc'),
(243, 'SID', 'Companhia Siderurgica Nacional SA'),
(244, 'SLB', 'Schlumberger'),
(245, 'SNA', 'Snap On'),
(246, 'SNAP', 'Snap Inc'),
(247, 'SNOW', 'Snowflake Inc'),
(248, 'SONY', 'Sony Group Corporation'),
(249, 'SPCE', 'VIRGIN GALACTIC HOLD'),
(250, 'SPGI', 'S&P Global Inc'),
(251, 'SPOT', 'Spotify Technology Sa'),
(252, 'SPY', 'Spdr S&P 500'),
(253, 'SQ', 'Square Inc'),
(254, 'STLA', 'Stellantis NV'),
(255, 'STNE', 'Stoneco Ltd Ord'),
(256, 'SUZ', 'Suzano 1 American Depositary Shares Representing 1 Ord Shs'),
(257, 'SWKS', 'Skyworks Solutions'),
(258, 'SYY', 'Sysco Corp'),
(259, 'T', 'AT&T DRC'),
(260, 'TCOM', 'Ctrip.Com International, Ltd'),
(261, 'TEFO', 'Telefonica'),
(262, 'TEN', 'Tenaris Ord Shs'),
(263, 'TGT', 'Target Corp'),
(264, 'TIIAY', 'TIIAY'),
(265, 'TIMB', 'Tim Participacaoes S.A.'),
(266, 'TJX', 'TJX Companies Inc'),
(267, 'TM', 'Toyota Motor'),
(268, 'TMO', 'Thermo Fisher Sc Ord Shs'),
(269, 'TMUS', 'T MOBILE US'),
(270, 'TRIP', 'Trip Advisors'),
(271, 'TRVV', 'Travelers Co'),
(272, 'TSLA', 'Tesla Inc'),
(273, 'TSM', 'Taiwan Semiconductor Manufacturing'),
(274, 'TTE', 'TotalEnergies SA'),
(275, 'TV', 'Grupo Televisa'),
(276, 'TWLO', 'Twilio Inc'),
(277, 'TXN', 'Texas Instrument'),
(278, 'TXR', 'Ternium'),
(279, 'UAL', 'United Airlines Holdings Inc'),
(280, 'UBER', 'Uber Technologies Inc'),
(281, 'UGP', 'Ultrapar Participacoes SA'),
(282, 'UL', 'Unilever Rep 3'),
(283, 'UNH', 'Unitedhealth Group Inc'),
(284, 'UNP', 'Union Pacific Corp'),
(285, 'UPST', 'Upstart Hldgs Inc'),
(286, 'URBN', 'Urban Outfitterss'),
(287, 'USB', 'US Bancorp'),
(288, 'V', 'Visa Inc Ord Shs'),
(289, 'VALE', 'Companhia Vale do Rio Doce'),
(290, 'VEA', 'Vanguard FTSE Developed Markets ETF'),
(291, 'VIST', 'Vista Energy SAB de CV'),
(292, 'VIV', 'Telef?nica Brasil'),
(293, 'VOD', 'Vodafone Group'),
(294, 'VRSN', 'Verisign Inc'),
(295, 'VZ', 'Verizon Communications'),
(296, 'WBA', 'Walgreens Boots Alliance Inc'),
(297, 'WBO', 'Weibo Corp'),
(298, 'WFC', 'Wells Fargo & Co'),
(299, 'WMT', 'Wal Mart'),
(300, 'X', 'United States Steel Corp'),
(301, 'XLB', 'The Industrial Select Sector SPDR Fund'),
(302, 'XLC', 'The Communication Services Select Sector SPDR Fund'),
(303, 'XLE', 'Spdr Energy Sel'),
(304, 'XLF', 'Spdr Financl Sel'),
(305, 'XLI', 'The Technology Select Sector SPDR Fund'),
(306, 'XLK', 'The Health Care Select Sector SPDR Fund'),
(307, 'XLP', 'The Real Estate Select Sector SPDR Fund'),
(308, 'XLRE', 'The Consumer Discretionary Select Sector SPDR Fund'),
(309, 'XLV', 'The Consumer Staples Select Sector SPDR Fund'),
(310, 'XLY', 'The Materials Select Sector SPDR Fund'),
(311, 'XOM', 'Exxon Mobil'),
(312, 'XP', 'Xp Inc'),
(313, 'XROX', 'Xerox'),
(314, 'YELP', 'Yelp Inc'),
(315, 'YY', 'JOYY Inc'),
(316, 'YZCA', 'Yanzhou Coal Mining'),
(317, 'ZM', 'Zoom Video Communications Inc');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticker_bonos`
--

CREATE TABLE `ticker_bonos` (
  `id` int(11) NOT NULL,
  `ticker_bonos` varchar(10) NOT NULL,
  `company_name_bonos` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ticker_bonos`
--

INSERT INTO `ticker_bonos` (`id`, `ticker_bonos`, `company_name_bonos`) VALUES
(1, 'AE38', 'Bono Rep. Argentina USD Step Up 2038\r'),
(2, 'AE38C', 'Bono Rep. Argentina USD Step Up 2038\r'),
(3, 'AE38D', 'Bono Rep. Argentina USD Step Up 2038\r'),
(4, 'AL29', 'Bono Rep Argentina USD 1% 2029\r'),
(5, 'AL29C', 'Bono Rep Argentina USD 1% 2029\r'),
(6, 'AL29D', 'Bono Rep Argentina USD 1% 2029\r'),
(7, 'AL30', 'Bono Rep. Argentina USD Step Up 2030\r'),
(8, 'AL30C', 'Bono Rep. Argentina USD Step Up 2030\r'),
(9, 'AL30D', 'Bono Rep. Argentina USD Step Up 2030\r'),
(10, 'AL35', 'Bono Rep. Argentina USD Step Up 2035\r'),
(11, 'AL35C', 'Bono Rep. Argentina USD Step Up 2035\r'),
(12, 'AL35D', 'Bono Rep. Argentina USD Step Up 2035\r'),
(13, 'AL41', 'Bono Rep. Argentina USD Step Up 2041\r'),
(14, 'AL41C', 'Bono Rep. Argentina USD Step Up 2041\r'),
(15, 'AL41D', 'Bono Rep. Argentina USD Step Up 2041\r'),
(16, 'BA37D', 'Bono Pcia. Bs. As Regs New U$S 2037 A\r'),
(17, 'BA7DC', 'Bono Pcia. Bs. As Regs New U$S 2037 A\r'),
(18, 'BA7DD', 'Bono Pcia. Bs. As Regs New U$S 2037 A\r'),
(19, 'BAO25', 'Td Gar Muni Cordoba S1 $ V27/10/25 Cg\r'),
(20, 'BB37D', 'Bono Pcia. Bs. As. Regs New U$S 2037 B\r'),
(21, 'BB7DD', 'Bono Pcia. Bs. As. Regs New U$S 2037 B\r'),
(22, 'BC37D', 'Bono Pcia Bs As Regs New U$S 2037 C\r'),
(23, 'BC37E', 'Bono Pcia Bs As Regs New Eur 2037 C\r'),
(24, 'BDC28', 'Titulos De Deuda Publica Clase N&#17623 A Tasa Variable Con Vencimiento En 2028\r'),
(25, 'BNA26', 'Lt P Neuquen D1739/23 S1 Cl1 V19/04/26 U$S Cg\r'),
(26, 'BPA7C', 'Bopreal Serie 1A Vto. 31/10/27\r'),
(27, 'BPA7D', 'Bopreal Serie 1A Vto. 31/10/27\r'),
(28, 'BPB7C', 'Bopreal Serie 1B Vto. 31/10/27\r'),
(29, 'BPB7D', 'Bopreal Serie 1B Vto. 31/10/27\r'),
(30, 'BPC7C', 'Bopreal Serie 1C Vto 31/10/27\r'),
(31, 'BPC7D', 'Bopreal Serie 1C Vto 31/10/27\r'),
(32, 'BPD7C', 'Bopreal Serie 1D Vto. 31/10/27\r'),
(33, 'BPD7D', 'Bopreal Serie 1D Vto. 31/10/27\r'),
(34, 'BPJ25', 'Bopreal Serie 2 Vto. 30/06/25 U$S\r'),
(35, 'BPJ5C', 'Bopreal Serie 2 Vto. 30/06/25 U$S\r'),
(36, 'BPJ5D', 'Bopreal Serie 2 Vto. 30/06/25 U$S\r'),
(37, 'BPOA7', 'Bopreal Serie 1A Vto. 31/10/27\r'),
(38, 'BPOB7', 'Bopreal Serie 1B Vto. 31/10/27\r'),
(39, 'BPOC7', 'Bopreal Serie 1C Vto 31/10/27\r'),
(40, 'BPOD7', 'Bopreal Serie 1D Vto. 31/10/27\r'),
(41, 'BPY26', 'Bopreal Serie 3 Vto 31/05/26\r'),
(42, 'BPY6C', 'Bopreal Serie 3 Vto 31/05/26\r'),
(43, 'BPY6D', 'Bopreal Serie 3 Vto 31/05/26\r'),
(44, 'CO26', 'Bono Prov. De Cordoba USD V2026\r'),
(45, 'CO26D', 'Bono Prov. De Cordoba USD V2026\r'),
(46, 'CUAP', 'Bonos Cuasi Par $ 3,31% 2045 (Ley Arg)\r'),
(47, 'D16E6', 'Bono Rep Arg Vinc USD V 16/01/2026\r'),
(48, 'DICP', 'Bono Discount $ 2033 (Ley Arg)\r'),
(49, 'DIP0', 'Bono Discount $ + Cer 2033 (Ley Arg)\r'),
(50, 'EF25D', 'Bono Pcia Entre Rios Regs 8,75% Vto 08/02/25\r'),
(51, 'ERF25', 'Bono Pcia Entre Rios Regs 8,75% Vto 08/02/25\r'),
(52, 'GD29', 'Bonos Rep. Arg. U$S 1% Step Up V09/07/29\r'),
(53, 'GD29C', 'Bonos Rep. Arg. U$S 1% Step Up V09/07/29\r'),
(54, 'GD29D', 'Bonos Rep. Arg. U$S 1% Step Up V09/07/29\r'),
(55, 'GD30', 'Bonos Rep. Arg. U$S Step Up V.09/07/30\r'),
(56, 'GD30C', 'Bonos Rep. Arg. U$S Step Up V.09/07/30\r'),
(57, 'GD30D', 'Bonos Rep. Arg. U$S Step Up V.09/07/30\r'),
(58, 'GD35', 'Bonos Rep. Arg. U$S Step Up V.09/07/35\r'),
(59, 'GD35C', 'Bonos Rep. Arg. U$S Step Up V.09/07/35\r'),
(60, 'GD35D', 'Bonos Rep. Arg. U$S Step Up V.09/07/35\r'),
(61, 'GD38', 'Bonos Rep. Arg. U$S Step Up V.09/01/38\r'),
(62, 'GD38C', 'Bonos Rep. Arg. U$S Step Up V.09/01/38\r'),
(63, 'GD38D', 'Bonos Rep. Arg. U$S Step Up V.09/01/38\r'),
(64, 'GD41', 'Bonos Rep. Arg. U$S Step Up V.09/07/41\r'),
(65, 'GD41C', 'Bonos Rep. Arg. U$S Step Up V.09/07/41\r'),
(66, 'GD41D', 'Bonos Rep. Arg. U$S Step Up V.09/07/41\r'),
(67, 'GD46', 'Bonos Rep. Arg. U$S Step Up V.09/07/46\r'),
(68, 'GD46C', 'Bonos Rep. Arg. U$S Step Up V.09/07/46\r'),
(69, 'GD46D', 'Bonos Rep. Arg. U$S Step Up V.09/07/46\r'),
(70, 'NDT25', 'Bono Prov. Neuquen Regs Vt 27/10/2030\r'),
(71, 'NDT5D', 'Bono Prov. Neuquen Regs Vt 27/10/2030\r'),
(72, 'PAP0', 'Bonos Par Ar$ + Cer 2038 (Ley Arg)\r'),
(73, 'PARP', 'Bono Par $ (Ley Arg)\r'),
(74, 'PBA25', 'T.D. Pcia. Buenos Aires $ V. 12/04/25\r'),
(75, 'PBY26', 'T.D. Pcia. Buenos Aires T.V. Vto. 05/05/26 $\r'),
(76, 'PM29C', 'Bono Pcia.Mendoza Regs 2.75% V.19/11/29\r'),
(77, 'PM29D', 'Bono Pcia.Mendoza Regs 2.75% Vto 19/11/29\r'),
(78, 'PMM29', 'Bono Pcia.Mendoza Regs 2.75% Vto 19/11/29\r'),
(79, 'PR17', 'Bono Consolidacion $ Sr 10 V02/05/29\r'),
(80, 'PUL26', 'Bono Pcia. Chubut 7,75% V.26/07/2026\r'),
(81, 'SA24D', 'Bono Prov. Salta 9,125% V2024\r'),
(82, 'T13F6', 'Boncap 13F6 Vto 13/02/2026\r'),
(83, 'T15D5', 'Bono Tesoro Nacional Vto. 15/12/25 $\r'),
(84, 'T15E7', 'Bono Tesoro Nac Cap V.15/01/27  $ Cg\r'),
(85, 'T17O5', 'Boncap 17O5 Vto 17/10/2025\r'),
(86, 'T30E6', 'Bono Tesoro Naci Cap V30/01/26 $ Cg\r'),
(87, 'T30J6', 'Boncap T30J6 Vto 30/06/2026\r'),
(88, 'TC25P', 'Bono Tesoro Nacional $ Cer 4% 27/04/2025\r'),
(89, 'TFU27', 'Bono Tierra Del Fuego USD 8.95% V27\r'),
(90, 'TG25', 'Bonte Vto.23/08/2025 $ Cg\r'),
(91, 'TO26', 'Bonos Del Tesoro Nac. Ars 15,5% V2026\r'),
(92, 'TTD26', 'Bono Nacion Tasa Dual 15/12/26\r'),
(93, 'TTJ26', 'Bono Nacion Tasa Dual 30/06/26\r'),
(94, 'TTM26', 'Bono Nacion Tasa Dual16/03/26\r'),
(95, 'TTS26', 'Bono Nacion Tasa Dual15/09/26\r'),
(96, 'TV25', 'Bono Rep Arg Vinc USD V31/03/25\r'),
(97, 'TVPA', 'Cup?n Vinculado Al Pbi Us$ 2035 (Ley Arg)\r'),
(98, 'TVPE', 'Cup?n Vinculado Al Pbi Euros 2035\r'),
(99, 'TVPP', 'Cup?n Vinculado Al Pbi $ 2035 (Ley Arg)\r'),
(100, 'TVPY', 'Cup?n Vinculado Al Pbi Us$ 2035 (Ley Ny)\r'),
(101, 'TX25', 'Bono Del Tesoro Boncer 1,8% $ 2025\r'),
(102, 'TX26', 'Bono Del Tesoro Boncer 2% $ 2026\r'),
(103, 'TX26D', 'Bono Del Tesoro Boncer 2% $ 2026\r'),
(104, 'TX28', 'Bonos Del Tesoro Boncer 2.25% $ 2028\r'),
(105, 'TX28D', 'Bonos Del Tesoro Boncer 2.25% $ 2028\r'),
(106, 'TX31', 'Bono Tesoro $ Aj Cer 2,50% V.30/11/31\r'),
(107, 'TY27P', 'Bono Del Tesoro Nacional En Pesos Cer Vto. 23/05/2\r'),
(108, 'TZV25', 'Bono Rep Arg Vinc USD V 30/06/25\r'),
(109, 'TZV26', 'Bono Del Tesoro Nacional Vto. 30/06/26 U$S\r'),
(110, 'TZVD5', 'Bono Rep Arg Vinc USD V 15/12/25\r'),
(111, 'TZX25', 'Boncer $ Cupon Cero Vto. 30/06/2025\r'),
(112, 'TZX26', 'Bono Rep Arg Aj Cer V30/06/26 $ Cg\r'),
(113, 'TZX27', 'Bono Rep Arg Aj Cer Vto. 30/06/27\r'),
(114, 'TZX28', 'Bono Rep Arg Aj Cer V30/06/28\r'),
(115, 'TZX6D', 'Bono Rep Arg Aj Cer V30/06/26 $ Cg\r'),
(116, 'TZXD5', 'Bono Del Tesoro Boncer Vto 15/12/25\r'),
(117, 'TZXD6', 'Bono Del Tesoro Boncer Vto 15/12/26\r'),
(118, 'TZXD7', 'Bontes $ A Desc Aj Cer Vto. 15/12/27\r'),
(119, 'TZXM5', 'Bono Del Tesoro Nac. Aj Cer Vto 31/03/25 $\r'),
(120, 'TZXM6', 'Bono Del Tesoro Boncer Vto 31/03/2026\r'),
(121, 'TZXM7', 'Bono Tesoro Nac Aj Cer V31/03/27 $ Cg\r'),
(122, 'TZXO5', 'Bono Del Tesoro Boncer Vto 31/10/2025\r'),
(123, 'TZXO6', 'Bono Del Tesoro Boncer Vto 31/10/2026\r'),
(124, 'TZXY5', 'Bono Del Tesoro Boncer Vto 31/05/2025\r');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticker_fondos`
--

CREATE TABLE `ticker_fondos` (
  `id` int(11) NOT NULL,
  `ticker_fondos` varchar(10) NOT NULL,
  `company_name_fondos` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ticker_fondos`
--

INSERT INTO `ticker_fondos` (`id`, `ticker_fondos`, `company_name_fondos`) VALUES
(1, 'ADBAICA', 'ADCAP Cobertura - Clase A\r'),
(2, 'ADRDOB', 'ADCAP Ahorro Pesos - Clase B\r'),
(3, 'ADRDOLA', 'ADCAP Ahorro Pesos - Clase A \r'),
(4, 'ALADOFA', 'Allaria Dolar Ahorro - Clase A\r'),
(5, 'ALADOFB', 'Allaria Dolar Ahorro - Clase B\r'),
(6, 'ALADOLA', 'Allaria Dolar Dinamico - Clase A\r'),
(7, 'ALAHORA', 'Allaria Ahorro - Clase A\r'),
(8, 'ALAHORB', 'Allaria Ahorro - Clase B\r'),
(9, 'ALAHPLA', 'Allaria Ahorro Plus - Clase A\r'),
(10, 'ALAHPLB', 'Allaria Ahorro Plus - Clase B\r'),
(11, 'ALDIVEA', 'Allaria Diversificado - Clase A\r'),
(12, 'ALDIVEB', 'Allaria Diversificado - Clase B\r'),
(13, 'ALDIVEC', 'Allaria Diversificado - Clase C\r'),
(14, 'ALDOLPA', 'Allaria Dolar Ahorro Plus - Clase A\r'),
(15, 'ALDOLPB', 'Allaria Dolar Ahorro Plus - Clase B\r'),
(16, 'ALDOPLA', 'Allaria Dolar Global - Clase A\r'),
(17, 'ALDOPLB', 'Allaria Dolar Global - Clase B\r'),
(18, 'ALGIIIA', 'Allaria Agro - Clase A\r'),
(19, 'ALGIIIB', 'Allaria Agro - Clase B\r'),
(20, 'ALLAEQA', 'Allaria Equity Selection - Clase A\r'),
(21, 'ALLAEQB', 'Allaria Equity Selection - Clase B\r'),
(22, 'ALLARTA', 'Allaria Dolar Retorno Total - Clase A\r'),
(23, 'ALLRDLC', 'Allaria Renta Dolar Ley 27260 - Clase C\r'),
(24, 'ALRTAFA', 'Allaria Renta Fija - Clase A\r'),
(25, 'ALRTAFB', 'Allaria Renta Fija - Clase B\r'),
(26, 'ALRTAVA', 'Allaria Acciones - Clase A\r'),
(27, 'ALRTAVB', 'Allaria Acciones - Clase B\r'),
(28, 'ALRVIIA', 'Allaria Renta Mixta II - Clase A\r'),
(29, 'ALRVIIB', 'Allaria Renta Mixta II - Clase B\r'),
(30, 'AXAHORA', 'MAF Ahorro Pesos - Clase A\r'),
(31, 'AXRFCOA', 'MAF Renta Fija Cobertura - Clase A\r'),
(32, 'BAINGLA', 'Balanz Retorno Total - Clase A\r'),
(33, 'BALADOA', 'Balanz Ahorro en Dolares - Clase A\r'),
(34, 'BALADOB', 'Balanz Ahorro en Dolares - Clase B\r'),
(35, 'BALMMIC', 'Balanz Institucional - Clase A\r'),
(36, 'BALPE3DA', 'Balanz Performance III - Clase A\r'),
(37, 'BALPE3DB', 'Balanz Performance III - Clase B\r'),
(38, 'BALPIIA', 'Balanz Performance II - Clase A\r'),
(39, 'BCAHORA', 'Balanz Capital Ahorro - Clase A\r'),
(40, 'BCAHORB', 'Balanz Capital Ahorro - Clase B\r'),
(41, 'BCMMUSDA', 'Balanz Money Market Usd - Clase A\r'),
(42, 'BCPE06A', 'Balanz Soja - Clase A\r'),
(43, 'BCRFDOA', 'Balanz Capital Renta Fija en Dolares - Clase A\r'),
(44, 'BCRTAFA', 'Balanz Capital Renta Fija - Clase A\r'),
(45, 'BCRTAFB', 'Balanz Capital Renta Fija - Clase B\r'),
(46, 'BMACTAA', 'Bull Market Active Renta Fija Argentina - Clase A\r'),
(47, 'BMACTAI', 'Bull Market Active Renta Fija Argentina - Clase B\r'),
(48, 'BMFCBBA', 'Bull Market Smart Money Market - Clase B\r'),
(49, 'BMSCPBR', 'Bull Market Smart Corto Plazo FCI - Clase B\r'),
(50, 'BMSCPLA', 'Bull Market Smart Corto Plazo FCI - Clase A\r'),
(51, 'BMSMMAA', 'Bull Market Smart Money Market - Clase A\r'),
(52, 'BULMAAA', 'Bull Market Acciones Argentinas - Clase A\r'),
(53, 'BULMAAB', 'Bull Market Acciones Argentinas - Clase B\r'),
(54, 'BULMADA', 'Bull Market Ahorro Dolares FCI - Clase A\r'),
(55, 'BULMADB', 'Bull Market Ahorro Dolares FCI - Clase B\r'),
(56, 'BZCAAAA', 'Balanz Acciones - Clase A\r'),
(57, 'BZCAAAB', 'Balanz Acciones - Clase B\r'),
(58, 'CBESIDA', 'Compass Best Ideas - Clase A\r'),
(59, 'CBESIDB', 'Compass Best Ideas - Clase B\r'),
(60, 'CCREIIA', 'Compass Crecimiento II - Clase A\r'),
(61, 'CCREIIB', 'Compass Crecimiento II - Clase B\r'),
(62, 'CDEUARA', 'Consultatio Deuda Argentina - Clase A\r'),
(63, 'CMAPRFA', 'CMA Performance - Clase A\r'),
(64, 'CMAPRFB', 'CMA Performance - Clase B\r'),
(65, 'CMAPROB', 'CMA Proteccion - Clase B\r'),
(66, 'CMAPROT', 'CMA Proteccion - Clase A\r'),
(67, 'CNXPOPA', 'ADCAP Pesos Plus - Clase A\r'),
(68, 'COAHDOA', 'Compass Ahorro en Dolares - Clase A\r'),
(69, 'COAHDOB', 'Compass Ahorro en Dolares - Clase B\r'),
(70, 'COBALAT', 'Consultatio Balance Fund - Clase B\r'),
(71, 'COGRLMF', 'Consultatio Acciones Argentina - Clase B\r'),
(72, 'COHRFDA', 'Cohen Renta Fija Dolares - Clase A\r'),
(73, 'COHRFDB', 'Cohen Renta Fija Dolares - Clase B\r'),
(74, 'COIOLAC', 'ADCAP Acciones - Clase B\r'),
(75, 'COMCREA', 'Compass Crecimiento FCI- Clase A\r'),
(76, 'COMCREB', 'Compass Crecimiento FCI - Clase B\r'),
(77, 'COMFSAF', 'Consultatio Deuda Argentina - Clase B\r'),
(78, 'COMIAUS', 'Consultatio Multimercado III - Clase A\r'),
(79, 'COMIBUS', 'Consultatio Multimercado III - Clase B\r'),
(80, 'COMOPPA', 'Compass Opportunity - Clase A\r'),
(81, 'COMOPPB', 'Compass Opportunity - Clase B\r'),
(82, 'COMPEUA', 'Compass Ahorro - Clase A\r'),
(83, 'COMPEUB', 'Compass Ahorro - Clase B\r'),
(84, 'COMPLIA', 'Compass Liquidez - Clase A\r'),
(85, 'COMPLIB', 'Compass Liquidez - Clase B\r'),
(86, 'COMREFA', 'Compass Renta Fija - Clase A\r'),
(87, 'COMREFB', 'Compass Renta Fija - Clase B\r'),
(88, 'COMRF4A', 'Compass Renta Fija IV - Clase A\r'),
(89, 'COMRF4B', 'Compass Renta Fija IV - Clase B\r'),
(90, 'COMUSAA', 'Compass Renta Fija III - Clase A\r'),
(91, 'COMUSAB', 'Compass Renta Fija III - Clase B\r'),
(92, 'CONAAFA', 'Consultatio Acciones Argentina - Clase A\r'),
(93, 'CONAARA', 'Consultatio Renta Fija Argentina - Clase A\r'),
(94, 'CONAPAA', 'Consultatio Ahorro Plus Argentina - Clase A\r'),
(95, 'CONAPAB', 'Consultatio Ahorro Plus Argentina - Clase B\r'),
(96, 'CONBALA', 'Consultatio Balance Fund - Clase A\r'),
(97, 'CONESTC', 'Consultatio Estrategia - Clase C\r'),
(98, 'CONIOLA', 'ADCAP Acciones - Clase A\r'),
(99, 'CONPPLB', 'ADCAP Pesos Plus - Clase B\r'),
(100, 'CONRETB', 'ADCAP Moneda - Clase B\r'),
(101, 'CONRETO', 'ADCAP Moneda - Clase A\r'),
(102, 'CRTAFAA', 'ADCAP Renta Fija - Clase A\r'),
(103, 'CRTAFAB', 'ADCAP Renta Fija - Clase B\r'),
(104, 'CRTAFAI', 'Cohen Pesos - Clase B\r'),
(105, 'CRTAFAM', 'Cohen Pesos - Clase A\r'),
(106, 'CRTAFPA', 'Cohen Renta Fija Plus - Clase A\r'),
(107, 'CRTAFPB', 'Cohen Renta Fija Plus - Clase B\r'),
(108, 'CRTANAA', 'Consultatio Renta Nacional - Clase A\r'),
(109, 'CRTANAB', 'Consultatio Renta Nacional - Clase B\r'),
(110, 'DBSDMRA', 'MEGAQM Retorno Total - Clase A\r'),
(111, 'DELFEIA', 'Delta Federal I - Clase A\r'),
(112, 'DELFEIB', 'Delta Federal I - Clase B\r'),
(113, 'DELPAIA', 'Delta Patrimonio I - Clase A\r'),
(114, 'DELPAIB', 'Delta Patrimonio I - Clase B\r'),
(115, 'DFSACCA', 'CMA Acciones - Clase A\r'),
(116, 'DFSRPLA', 'CMA Renta Dolar - Clase A\r'),
(117, 'DFSRPLB', 'CMA Renta Dolar - Clase B\r'),
(118, 'DGEST8A', 'Delta Retorno Real - Clase A\r'),
(119, 'DGEST8B', 'Delta Retorno Real - Clase B\r'),
(120, 'FIACCA', 'FIMA Acciones - Clase A\r'),
(121, 'FIACCB', 'FIMA Acciones - Clase B \r'),
(122, 'FIAHPLA', 'FIMA Ahorro Plus - Clase A\r'),
(123, 'FIAHPLB', 'FIMA Ahorro Plus - Clase B\r'),
(124, 'FIAHPLC', 'FIMA Ahorro Plus - Clase C\r'),
(125, 'FIAPBA', 'FIMA PB Acciones - Clase A \r'),
(126, 'FIAPBB', 'FIMA PB Acciones - Clase B \r'),
(127, 'FIAPESA', 'FIMA Ahorro Pesos - Clase A\r'),
(128, 'FIAPESB', 'FIMA Ahorro Pesos - Clase B\r'),
(129, 'FIAPESC', 'FIMA Ahorro Pesos - Clase C\r'),
(130, 'FICAPLA', 'FIMA Capital Plus - Clase A\r'),
(131, 'FICAPLB', 'FIMA Capital Plus - Clase B\r'),
(132, 'FICAPLC', 'FIMA Capital Plus - Clase C\r'),
(133, 'FIMAMPA', 'FIMA Premium - Clase A \r'),
(134, 'FIMAMPB', 'FIMA Premium - Clase B\r'),
(135, 'FIMIXIA', 'FIMA Mix I - Clase A \r'),
(136, 'FIMIXIB', 'FIMA Mix I - Clase B \r'),
(137, 'FIMIXIC', 'FIMA Mix I - Clase C \r'),
(138, 'FIMREPA', 'FIMA Renta en Pesos - Clase A\r'),
(139, 'FIMREPB', 'FIMA Renta en Pesos - Clase B\r'),
(140, 'FIMREPC', 'FIMA Renta en Pesos - Clase C\r'),
(141, 'FMIXIIA', 'FIMA Mix II - Clase A\r'),
(142, 'FRTREDA', 'First Renta Dolares - Clase A\r'),
(143, 'FRTREDB', 'First Renta Dolares - Clase B\r'),
(144, 'FSTACCA', 'MEGAQM Acciones - Clase A\r'),
(145, 'FSTACCB', 'MEGAQM Acciones - Clase B\r'),
(146, 'FSTBALA', 'MEGAQM Balanceado - Clase A\r'),
(147, 'FSTBALB', 'MEGAQM Balanceado - Clase B\r'),
(148, 'FSTPESA', 'MEGAQM Pesos - Clase A\r'),
(149, 'FSTPESB', 'MEGAQM Pesos - Clase B\r'),
(150, 'FSTREMIA', 'First Renta Mixta I - Clase A\r'),
(151, 'FSTREMIB', 'First Renta Mixta I - Clase B\r'),
(152, 'FSTREMIIA', 'First Renta Mixta II - Clase A\r'),
(153, 'FSTREMIIB', 'First Renta Mixta II - Clase B\r'),
(154, 'FSTREPA', 'First Renta Pesos - Clase A\r'),
(155, 'FSTREPB', 'First Renta Pesos - Clase B\r'),
(156, 'FTSLFDN', 'SBS Latam - Clase A\r'),
(157, 'FTSLJDN', 'SBS Latam - Clase B\r'),
(158, 'GAALSGA', 'Galileo Sustentable - Clase A\r'),
(159, 'GAAPYMB', 'Galileo Abierto Pymes - Clase B\r'),
(160, 'GALACCI', 'Galileo Acciones - Clase A\r'),
(161, 'GALAHOR', 'Galileo Ahorro - Clase A\r'),
(162, 'GALGLBB', 'Galileo Ahorro Plus - Clase B\r'),
(163, 'GALGLOB', 'Galileo Ahorro Plus - Clase A\r'),
(164, 'GALIARA', 'Galileo Argentina - Clase A\r'),
(165, 'GALIRFI', 'Galileo Renta Fija - Clase A\r'),
(166, 'GALPESA', 'Galileo Pesos - Clase A\r'),
(167, 'GALPESB', 'Galileo Pesos - Clase B\r'),
(168, 'GAMMIIA', 'Galileo Multimercado II - Clase A\r'),
(169, 'GAMMIIB', 'Galileo Multimercado II - Clase B\r'),
(170, 'GAMULTIA', 'Galileo Multi-Strategy - Clase A\r'),
(171, 'GPSLATA', 'Quiron Latam - Clase A\r'),
(172, 'GPSSAVA', 'Quiron Savings - Clase A\r'),
(173, 'GPSSAVB', 'Quiron Savings - Clase B\r'),
(174, 'IAMAHPB', 'IAM Ahorro Pesos - Clase B\r'),
(175, 'IAMAHPE', 'IAM Ahorro Pesos - Clase A\r'),
(176, 'IAMRCAA', 'IAM Renta Capital - Clase A\r'),
(177, 'IAMRCAB', 'IAM Renta Capital - Clase B\r'),
(178, 'IAMRDOA', 'IAM Renta Dolares - Clase A\r'),
(179, 'IAMRDOB', 'IAM Renta Dolares - Clase B\r'),
(180, 'IAMRECB', 'IAM Renta Crecimiento - Clase B\r'),
(181, 'IAMRECR', 'IAM Renta Crecimiento - Clase A\r'),
(182, 'IAMREPB', 'IAM Renta Plus - Clase B\r'),
(183, 'IAMREPL', 'IAM Renta Plus - Clase A\r'),
(184, 'IEBAHPA', 'IEB Ahorro Plus - Clase A\r'),
(185, 'IEBAHPB', 'IEB Ahorro Plus - Clase B\r'),
(186, 'IEBMULA', 'IEB Multiestrategia - Clase A\r'),
(187, 'IEBMULB', 'IEB Multiestrategia - Clase B\r'),
(188, 'IEBRFDA', 'IEB Renta Fija Dolar - Clase A\r'),
(189, 'IEBRFDB', 'IEB Renta Fija Dolar - Clase B\r'),
(190, 'IEBRFJA', 'IEB Renta Fija - Clase A\r'),
(191, 'IEBRFJB', 'IEB Renta Fija - Clase B\r'),
(192, 'IEBVALA', 'IEB Value - Clase A\r'),
(193, 'IEIAAA', 'IEB Ahorro - Clase A\r'),
(194, 'IEIABAC', 'IEB Ahorro - Clase B\r'),
(195, 'INTACCA', 'Integrae Acciones - Clase A\r'),
(196, 'INTACCB', 'Integrae Acciones - Clase B\r'),
(197, 'MAFACCA', 'MAF Acciones Argentina - Clase A\r'),
(198, 'MAFACCB', 'MAF Acciones Argentina - Clase B\r'),
(199, 'MAFMMKA', 'MAF Money Market - Clase A\r'),
(200, 'MAFMMKB', 'MAF Money Market - Clase B\r'),
(201, 'MAFPPLA', 'MAF Pesos Plus - Clase A\r'),
(202, 'MAFPPLB', 'MAF Pesos Plus - Clase B\r'),
(203, 'MEGAAHA', 'MEGAQM Ahorro - Clase A\r'),
(204, 'MEGAAHB', 'MEGAQM Ahorro - Clase B\r'),
(205, 'MGCORA2', 'MEGAQM Latam Corporativo - Clase A\r'),
(206, 'MGRFUSA', 'MEGAQM Corporativo Dolares - Clase A\r'),
(207, 'MGRFUSB', 'MEGAQM Corporativo Dolares - Clase B\r'),
(208, 'MIDOALA', 'MEGAQM Liquidez Dolar - Clase A\r'),
(209, 'MIDOALB', 'MEGAQM Liquidez Dolar - Clase B\r'),
(210, 'MIFIPRO', 'Megainver Abierto Pymes - Clase B\r'),
(211, 'MILATMA', 'MEGAQM Renta Fija Latam - Clase A\r'),
(212, 'MILATMB', 'MEGAQM Renta Fija Latam - Clase B\r'),
(213, 'MIRTMXA', 'MEGAQM Renta Mixta - Clase A\r'),
(214, 'MIRTMXB', 'MEGAQM Renta Mixta- Clase B\r'),
(215, 'MRTAFCA', 'MEGAQM Cobertura - Clase A\r'),
(216, 'MRTAFCB', 'MEGAQM Cobertura - Clase B\r'),
(217, 'PKTCAPA', 'Parakeet Capital Plus - Clase A\r'),
(218, 'PKTCAPB', 'Parakeet Capital Plus - Clase B\r'),
(219, 'PKTINCA', 'Parakeet Income - Clase A\r'),
(220, 'PKTINCB', 'Parakeet Income - Clase B\r'),
(221, 'PKTPESA', 'Parakeet Pesos - Clase A\r'),
(222, 'PKTPESB', 'Parakeet Pesos - Clase B\r'),
(223, 'PKTRNTA', 'Parakeet Renta Pesos - Clase A\r'),
(224, 'PKTRNTB', 'Parakeet Renta Pesos - Clase B\r'),
(225, 'QTOTALA', 'MEGAQM Retorno Absoluto - Clase A\r'),
(226, 'QTOTALB', 'MEGAQM Retorno Absoluto - Clase B\r'),
(227, 'QUDEARA', 'MEGAQM Provincial - Clase A\r'),
(228, 'QUDEARB', 'MEGAQM Provincial - Clase B\r'),
(229, 'RJDAC2A', 'Delta Recursos Naturales - Clase A\r'),
(230, 'RJDAC2B', 'Delta Recursos Naturales - Clase B\r'),
(231, 'RJDAC3A', 'Delta Select - Clase A\r'),
(232, 'RJDAC3B', 'Delta Select - Clase B\r'),
(233, 'RJDAHOA', 'Delta Ahorro - Clase A\r'),
(234, 'RJDAHOB', 'Delta Ahorro - Clase B\r'),
(235, 'RJDBRAA', 'Delta Latinoamerica - Clase A\r'),
(236, 'RJDBRAB', 'Delta Latinoamerica - Clase B\r'),
(237, 'RJDELTA', 'Delta Acciones - Clase A\r'),
(238, 'RJDELTB', 'Delta Acciones - Clase B\r'),
(239, 'RJDEMAA', 'Delta Pyme - Clase A\r'),
(240, 'RJDEMAP', 'Delta Pyme - Clase B\r'),
(241, 'RJDGLOA', 'Delta Moneda - Clase A\r'),
(242, 'RJDGLOB', 'Delta Moneda - Clase B\r'),
(243, 'RJDMM3B', 'Delta Renta Dolares - Clase B\r'),
(244, 'RJDRT3A', 'Delta Pesos - Clase A\r'),
(245, 'RJDRT3B', 'Delta Pesos - Clase B\r'),
(246, 'RJDRTAA', 'Delta Renta - Clase A\r'),
(247, 'RJDRTAB', 'Delta Renta - Clase B\r'),
(248, 'RJDUSAA', 'Delta Internacional - Clase A\r'),
(249, 'RJDUSAB', 'Delta Internacional - Clase B\r'),
(250, 'RJMMIIA', 'Delta Performance - Clase A\r'),
(251, 'RJMMIIB', 'Delta Performance - Clase B\r'),
(252, 'RJMULIA', 'Delta Multimercado I - Clase A\r'),
(253, 'RJMULIB', 'Delta Multimercado I - Clase B\r'),
(254, 'RJRTA4A', 'Delta Ahorro Plus - Clase A\r'),
(255, 'RJRTA4B', 'Delta Ahorro Plus - Clase B\r'),
(256, 'RTAPLUA', 'FIMA Renta Plus - Clase A\r'),
(257, 'RTAPLUB', 'FIMA Renta Plus - Clase B\r'),
(258, 'RTAPLUC', 'FIMA Renta Plus - Clase C\r'),
(259, 'RTAVAAA', 'IAM Renta Variable - Clase A\r'),
(260, 'RTAVAAB', 'IAM Renta Variable - Clase B\r'),
(261, 'RTAVARA', 'Consultatio Renta Variable - Clase A\r'),
(262, 'RTAVARB', 'Consultatio Renta Variable - Clase B\r'),
(263, 'SBSACAB', 'SBS Acciones Argentina - Clase B\r'),
(264, 'SBSACAR', 'SBS Acciones Argentina - Clase A\r'),
(265, 'SBSAPEA', 'SBS Ahorro Pesos - Clase A\r'),
(266, 'SBSAPEB', 'SBS Ahorro Pesos - Clase B\r'),
(267, 'SBSBALA', 'SBS Balanceado - Clase A\r'),
(268, 'SBSBALB', 'SBS Balanceado - Clase B\r'),
(269, 'SBSCAPL', 'SBS Capital Plus - Clase A\r'),
(270, 'SBSESTA', 'SBS Estrategia - Clase A\r'),
(271, 'SBSESTB', 'SBS Estrategia - Clase B\r'),
(272, 'SBSGRFA', 'SBS Gestion Renta Fija - Clase A\r'),
(273, 'SBSGRFB', 'SBS Gestion Renta Fija - Clase B\r'),
(274, 'SBSPESA', 'SBS Pesos Plus - Clase A\r'),
(275, 'SBSPESB', 'SBS Pesos Plus - Clase B\r'),
(276, 'SBSRPEA', 'SBS Renta Pesos - Clase A\r'),
(277, 'SBSRTOA', 'SBS Retorno Total - Clase A\r'),
(278, 'SCHARGA', 'Schroder Argentina - Clase A\r'),
(279, 'SCHASIA', 'Schroder Infraestructura - Clase A\r'),
(280, 'SCHASIB', 'Schroder Infraestructura - Clase B\r'),
(281, 'SCHRTTA', 'Schroder Income - Clase A\r'),
(282, 'SCHRTTB', 'Schroder Income - Clase B\r'),
(283, 'SCRTAPA', 'Schroder Renta Plus - Clase A\r'),
(284, 'SCRTAPL', 'Schroder Renta Plus - Clase B\r'),
(285, 'SMIMRVA', 'Schroder Renta Variable - Clase A\r'),
(286, 'TANDERB', 'Tandem Pesos Ahorro Plus - Clase B\r'),
(287, 'TANDERI', 'Tandem Pesos Ahorro Plus - Clase A\r'),
(288, 'TORONTR', 'Toronto Trust - Clase A\r'),
(289, 'TORRTOA', 'Toronto Trust Retorno Total - Clase A\r'),
(290, 'TORRTOB', 'Toronto Trust Retorno Total - Clase B\r'),
(291, 'TOTCREA', 'Toronto Trust Crecimiento - Clase A\r'),
(292, 'TOTCREB', 'Toronto Trust Crecimiento - Clase B\r'),
(293, 'TRTINFA', 'Toronto Trust Global Capital - Clase A\r'),
(294, 'TRTRDFA', 'Toronto Trust Renta Dolar - Clase A\r'),
(295, 'TTAHORA', 'Toronto Trust Ahorro - Clase A\r'),
(296, 'TTAHORB', 'Toronto Trust Ahorro - Clase B\r'),
(297, 'TTARGAA', 'Toronto Trust Argentina 2021 - Clase A\r'),
(298, 'TTARGAB', 'Toronto Trust Argentina 2021 - Clase B\r'),
(299, 'TTMULTA', 'Toronto Trust Multimercado - Clase A\r'),
(300, 'TTMULTB', 'Toronto Trust Multimercado - Clase B\r'),
(301, 'TTRTFIA', 'Toronto Trust Renta Fija - Clase A\r'),
(302, 'TTRTFPA', 'Toronto Trust Renta Fija Plus - Clase A\r'),
(303, 'TTRTFPB', 'Toronto Trust Renta Fija Plus - Clase B\r'),
(304, 'TTRUSTB', 'Toronto Trust - Clase B\r');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acciones`
--
ALTER TABLE `acciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cliente_id_acciones` (`cliente_id`);

--
-- Indices de la tabla `acciones_historial`
--
ALTER TABLE `acciones_historial`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mail` (`email`);

--
-- Indices de la tabla `balance`
--
ALTER TABLE `balance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`cliente_id`),
  ADD KEY `idx_cliente_id_balance` (`cliente_id`);

--
-- Indices de la tabla `bonos`
--
ALTER TABLE `bonos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cliente_id_bonos` (`cliente_id`);

--
-- Indices de la tabla `bonos_historial`
--
ALTER TABLE `bonos_historial`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cedear`
--
ALTER TABLE `cedear`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cliente_id_cedear` (`cliente_id`);

--
-- Indices de la tabla `cedear_historial`
--
ALTER TABLE `cedear_historial`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`cliente_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `fondos`
--
ALTER TABLE `fondos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cliente_id_fondos` (`cliente_id`);

--
-- Indices de la tabla `fondos_historial`
--
ALTER TABLE `fondos_historial`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tickers_acciones`
--
ALTER TABLE `tickers_acciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tickers_cedears`
--
ALTER TABLE `tickers_cedears`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ticker_bonos`
--
ALTER TABLE `ticker_bonos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ticker_fondos`
--
ALTER TABLE `ticker_fondos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acciones`
--
ALTER TABLE `acciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT de la tabla `acciones_historial`
--
ALTER TABLE `acciones_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `balance`
--
ALTER TABLE `balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `bonos`
--
ALTER TABLE `bonos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `bonos_historial`
--
ALTER TABLE `bonos_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `cedear`
--
ALTER TABLE `cedear`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=280;

--
-- AUTO_INCREMENT de la tabla `cedear_historial`
--
ALTER TABLE `cedear_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `cliente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `fondos`
--
ALTER TABLE `fondos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `fondos_historial`
--
ALTER TABLE `fondos_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tickers_acciones`
--
ALTER TABLE `tickers_acciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT de la tabla `tickers_cedears`
--
ALTER TABLE `tickers_cedears`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=318;

--
-- AUTO_INCREMENT de la tabla `ticker_bonos`
--
ALTER TABLE `ticker_bonos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT de la tabla `ticker_fondos`
--
ALTER TABLE `ticker_fondos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=305;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
