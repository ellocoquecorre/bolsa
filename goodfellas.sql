-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-03-2025 a las 21:41:49
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
(79, 1, 'ALUA', 100, 780.00, '2025-03-14', 1291.71);

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
(1, 1, 3982500.00),
(2, 2, 3891580.00),
(3, 3, 2982732.50);

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
(5, 1, 'GD35', '2025-03-15', 10, 81050.00, 1291.71);

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
(5, 1, 'AAPL', '2025-03-14', 10, 12900.00, 1291.71);

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
('el.bueno.de.harry@gmail.com', '$2y$10$7aQJdfZZGPUw5pGJMZgmT.v83vhG2iQ8cK4OaeGiGWHYCtyuwpr5O', 'Harry', 'Flashman', '123', 'Balanz', 'https://clientes.balanz.com/auth/login', 1),
('cafe.la.humedad@gmail.com', '$2y$10$s0gNWXGHERDw/aNdFc8kG.ovHC.zwfqvKV4ixSFlh8iaxGj90jOC6', 'Cacho', 'Castaña', '456', 'Allaria', 'https://allaria.com.ar', 2),
('24.de.nerca@gmail.com', '$2y$10$hoiodH8lYFVNpP/8YARR9ebT0RcPRZYWxhhXFe2FIeHodnCpS4Hdq', 'Rocco', 'Siffredi', '789', 'LEBSA', 'https://operar.winvest.ar', 3);

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
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acciones`
--
ALTER TABLE `acciones`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `usuario_id` (`cliente_id`);

--
-- Indices de la tabla `bonos`
--
ALTER TABLE `bonos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bonos_historial`
--
ALTER TABLE `bonos_historial`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cedear`
--
ALTER TABLE `cedear`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT de la tabla `acciones_historial`
--
ALTER TABLE `acciones_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `balance`
--
ALTER TABLE `balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `bonos`
--
ALTER TABLE `bonos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `bonos_historial`
--
ALTER TABLE `bonos_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `cedear`
--
ALTER TABLE `cedear`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `cedear_historial`
--
ALTER TABLE `cedear_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `cliente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `fondos`
--
ALTER TABLE `fondos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fondos_historial`
--
ALTER TABLE `fondos_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
