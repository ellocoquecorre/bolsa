-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-03-2025 a las 16:09:43
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
(27, 1, 'BBAR', 10, 7540.00, '2025-02-26', 1219.99),
(28, 2, 'BMA', 10, 10375.00, '2025-02-12', 1185.19),
(29, 2, 'BYMA', 10, 467.00, '2025-02-26', 1219.99),
(30, 3, 'CEPU', 10, 1545.00, '2025-02-12', 1185.19),
(31, 3, 'COME', 10, 181.75, '2025-02-26', 1219.99),
(51, 1, 'ALUA', 10, 794.00, '2025-02-12', 1185.19),
(66, 1, 'TRAN', 432, 2315.00, '2025-02-28', 1223.35);

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
(1, 1, 'EDN', 10, '2024-11-01', 1820.00, 1177.29, '2025-01-31', 2305.00, 1180.70),
(2, 1, 'IRSA', 10, '2024-11-01', 1545.00, 1177.29, '2025-01-31', 1775.00, 1180.70),
(3, 2, 'EDN2', 10, '2024-11-01', 1820.00, 1177.29, '2025-01-31', 2305.00, 1180.70),
(4, 2, 'IRSA2', 10, '2024-11-01', 1545.00, 1177.29, '2025-01-31', 1775.00, 1180.70),
(5, 3, 'EDN3', 10, '2024-11-01', 1820.00, 1177.29, '2025-01-31', 2305.00, 1180.70),
(6, 3, 'IRSA3', 10, '2024-11-01', 1545.00, 1177.29, '2025-01-31', 1775.00, 1180.70);

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
(2, 'm_laguzzi@gmail.com', '$2y$10$TxUOKcwzcxcFRKEgqX/iOeZSzH64OAXGLLS6gNtb5wcK2h6ydcF7K');

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
(1, 1, 3916580.00),
(2, 2, 3891580.00),
(3, 3, 2982732.50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cedear`
--

CREATE TABLE `cedear` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `ticker` varchar(10) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cedear`
--

INSERT INTO `cedear` (`id`, `cliente_id`, `ticker`, `cantidad`, `precio`, `fecha`) VALUES
(1, 1, 'AAPL', 10, 14850.00, '2025-02-21'),
(2, 1, 'PLTR', 10, 41450.00, '2025-02-21');

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
-- Indices de la tabla `cedear`
--
ALTER TABLE `cedear`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`cliente_id`),
  ADD UNIQUE KEY `email` (`email`);

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
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acciones`
--
ALTER TABLE `acciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de la tabla `acciones_historial`
--
ALTER TABLE `acciones_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
-- AUTO_INCREMENT de la tabla `cedear`
--
ALTER TABLE `cedear`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `cliente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
