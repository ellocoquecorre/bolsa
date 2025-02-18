-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-02-2025 a las 00:30:23
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
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `acciones`
--

INSERT INTO `acciones` (`id`, `cliente_id`, `ticker`, `cantidad`, `precio`, `fecha`) VALUES
(1, 1, 'AGRO', 33, 312.00, '2025-02-18'),
(2, 1, 'GAMI', 33, 312.00, '2025-02-18'),
(7, 2, 'LONG', 21, 21.00, '2025-02-18');

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
(1, 1, 1860000.00),
(2, 2, 9999994.00),
(3, 3, 50000000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
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

INSERT INTO `clientes` (`id`, `email`, `password`, `nombre`, `apellido`, `telefono`, `corredora`, `url`, `cliente_id`) VALUES
(1, 'el.bueno.de.harry@gmail.com', '$2y$10$7aQJdfZZGPUw5pGJMZgmT.v83vhG2iQ8cK4OaeGiGWHYCtyuwpr5O', 'Harry', 'Flashman', '123', 'Balanz', 'https://clientes.balanz.com/auth/login', 1),
(2, 'cafe.la.humedad@gmail.com', '$2y$10$s0gNWXGHERDw/aNdFc8kG.ovHC.zwfqvKV4ixSFlh8iaxGj90jOC6', 'Cacho', 'Castaña', '456', 'Allaria', 'https://allaria.com.ar', 2),
(3, '24.de.nerca@gmail.com', '$2y$10$hoiodH8lYFVNpP/8YARR9ebT0RcPRZYWxhhXFe2FIeHodnCpS4Hdq', 'Rocco', 'Siffredi', '789', 'LEBSA', 'https://operar.winvest.ar', 3);

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
(19, 'CEPU2', 'Central Puerto\r'),
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
  `company_name` varchar(255) NOT NULL,
  `ratio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tickers_cedears`
--

INSERT INTO `tickers_cedears` (`id`, `ticker`, `company_name`, `ratio`) VALUES
(1, 'AAL', 'Americn Arlns Gp', 2),
(2, 'AAP', 'Advance Auto Parts Inc', 14),
(3, 'AAPL', 'Apple Inc', 20),
(4, 'ABBV', 'Abbvie Inc', 10),
(5, 'ABEV', 'Ambev', 1),
(6, 'ABNB', 'Airbnb Inc', 15),
(7, 'ABT', 'Abbott Labs', 4),
(8, 'ACN', 'Accenture PLC', 75),
(9, 'ADBE', 'Adobe Sys', 44),
(10, 'ADGO', 'Adecoagro', 1),
(11, 'ADI', 'Analog Devices', 15),
(12, 'ADP', 'Automatic Data', 6),
(13, 'AEG', 'Aegon', 1),
(14, 'AEM', 'Agnico Eagle Mines', 6),
(15, 'AIG', 'Amer Intl Group', 5),
(16, 'AMAT', 'Applied Material', 5),
(17, 'AMD', 'Adv Micro Device', 10),
(18, 'AMGN', 'Amgen Inc', 30),
(19, 'AMX', 'America Movil', 1),
(20, 'AMZN', 'Amazon', 144),
(21, 'ANF', 'Abercrombies', 1),
(22, 'ARCO', 'Arcos Dorados', 1),
(23, 'ARKK', 'Ark Innvtion Etf', 10),
(24, 'ASR', 'Grupo Aeroportuario del Sureste', 20),
(25, 'AVGO', 'Broadcom Inc', 39),
(26, 'AVY', 'Avery Dennison', 18),
(27, 'AXP', 'American Express', 15),
(28, 'AZN', 'AstraZeneca', 2),
(29, 'BA', 'Boeing', 24),
(30, 'BABA', 'Alibaba Group', 9),
(31, 'BAK', 'Braskem', 2),
(32, 'BAS', 'BASF', 2),
(33, 'BB', 'Research In Motion', 3),
(34, 'BBAS3', 'Banco do Brasil', 2),
(35, 'BBD', 'Banco Bradesco', 1),
(36, 'BBV', 'Banco Bilbao Vizcaya Argentaria', 1),
(37, 'BCS', 'Barclays', 1),
(38, 'BHP', 'BHP Billiton', 2),
(39, 'BIDU', 'Baidu', 11),
(40, 'BIIB', 'Biogen Inc', 13),
(41, 'BIOX', 'Bioceres S.A.', 1),
(42, 'BITF', 'Bitfarms Ltd', 1),
(43, 'BK', 'Bank Of New York', 2),
(44, 'BKNG', 'Booking Holdings', 700),
(45, 'BKR', 'Baker Hughes Co', 7),
(46, 'BMY', 'Bristol-Myers Squibb Co', 3),
(47, 'BNG', 'Bunge', 5),
(48, 'BP', 'BP', 5),
(49, 'BRFS', 'BRF S.A.', 1),
(50, 'BRKB', 'Berkshire Hathaway Inc', 22),
(51, 'BSBR', 'Banco Santander Brasil', 1),
(52, 'C', 'Citigroup', 3),
(53, 'CAAP', 'Corp America Airports Sa', 1),
(54, 'CAH', 'Cardinal Health', 3),
(55, 'CAR', 'Avis Budget Group', 26),
(56, 'CAT', 'Caterpillar', 20),
(57, 'CCL', 'Carnival Corp', 3),
(58, 'CDE', 'Coeur Mining Inc', 1),
(59, 'CL', 'Colgate', 3),
(60, 'COIN', 'Coinbs Gbl', 27),
(61, 'COST', 'Costco Wholesal', 48),
(62, 'CRM', 'Salesforce Coms', 18),
(63, 'CSCO', 'Cisco Systems', 5),
(64, 'CVS', 'CVS Health Corp', 15),
(65, 'CVX', 'Chevron Corp', 16),
(66, 'CX', 'Cemex', 1),
(67, 'DAL', 'Delta Air Lines Inc.', 8),
(68, 'DD', 'E I Du Pont De Nemours', 5),
(69, 'DE', 'Deere and Co', 40),
(70, 'DEO', 'Diageo', 6),
(71, 'DESP', 'Despegar', 1),
(72, 'DHR', 'Danaher Corp', 54),
(73, 'DIA', 'Spdr Dji Average', 20),
(74, 'DISN', 'Walt Disney', 12),
(75, 'DOCU', 'Docusign Inc', 22),
(76, 'DOW', 'Dow Inc', 6),
(77, 'DTEA', 'Deutsche Telekom', 3),
(78, 'E', 'ENI', 4),
(79, 'EA', 'Electronic Art', 14),
(80, 'EBAY', 'Ebay Inc', 2),
(81, 'EBR', 'Centrais Eletricas Brasileiras S.A.', 1),
(82, 'EEM', 'Ish Msci Em Mkt', 5),
(83, 'EFX', 'Equifax Inc', 16),
(84, 'ELP', 'Copel', 1),
(85, 'EOAN', 'E.ON SE', 6),
(86, 'ERIC', 'Telefonaktiebolaget Lm Ericsson', 2),
(87, 'ERJ', 'Embraer SA', 1),
(88, 'ETHA', 'iShares Ethereum Trust ETF', 5),
(89, 'ETSY', 'Etsy Inc', 16),
(90, 'EWZ', 'Ishs Msci Brazil', 2),
(91, 'FCX', 'Freeport Mcmoran', 3),
(92, 'FDX', 'Fedex', 10),
(93, 'FMCC', 'Freddie Mac', 1),
(94, 'FMX', 'Fomento Economico Mexicano', 6),
(95, 'FNMA', 'Fannie Mae', 1),
(96, 'FSLR', 'First Solar Inc', 18),
(97, 'FXI', 'iShares China Large-Cap ETF', 5),
(98, 'GE', 'General Electric', 8),
(99, 'GFI', 'Gold Fields', 1),
(100, 'GGB', 'Gerdau SA ADR', 1),
(101, 'GILD', 'Gilead Sci', 4),
(102, 'GLD', 'SPDR Gold Trust ETF', 50),
(103, 'GLOB', 'Globant', 18),
(104, 'GLW', 'Corning Inc', 4),
(105, 'GM', 'Electronic Data Systems', 6),
(106, 'GOLD', 'Barrick Gold Corp', 2),
(107, 'GOOGL', 'Alphabet Inc Cl A', 58),
(108, 'GPRK', 'Geopark Ltd', 1),
(109, 'GRMN', 'Garmin Ltds', 3),
(110, 'GS', 'Goldman Sachs', 13),
(111, 'GSK', 'Glaxosmithkline', 4),
(112, 'GT', 'Goodyear Tire & Rubber Co', 2),
(113, 'HAL', 'Halliburton Co', 2),
(114, 'HAPV3', 'Hapvida Par e Invest', 1),
(115, 'HD', 'Home Depot', 32),
(116, 'HDB', 'HDFC Bank', 2),
(117, 'HHPD', 'Hon Hai Precision Industry Co Ltd', 2),
(118, 'HL', 'Hecla Mining', 1),
(119, 'HMC', 'Honda Motor', 1),
(120, 'HMY', 'Harmony Gold Mining Company', 1),
(121, 'HOG', 'Harley Davidson', 3),
(122, 'HON', 'Honeywell Intl', 8),
(123, 'HPQ', 'HP Inc', 1),
(124, 'HSBC', 'HSBC Holdings', 2),
(125, 'HSY', 'Hershey Foods', 21),
(126, 'HUT', 'Hut 8 Mining Corp', 1),
(127, 'HWM', 'Howmet Arspc Inc', 1),
(128, 'IBB', 'iShares Nasdaq Biotechnology ETF', 27),
(129, 'IBIT', 'iShares Bitcoin Trust ETF', 10),
(130, 'IBM', 'International Business Machines', 15),
(131, 'IBN', 'ICICI Bank', 1),
(132, 'IEUR', 'iShares Core MSCI Europe ETF', 11),
(133, 'IFF', 'Intl Flav & Frag', 12),
(134, 'INFY', 'Infosys Technologies', 1),
(135, 'ING', 'Ing Groep 3 Rep 1', 3),
(136, 'INTC', 'Intel', 5),
(137, 'IP', 'Intl Paper', 4),
(138, 'ISRG', 'Intuitive Surgical, Inc.', 90),
(139, 'ITUB', 'Itau Unibanco Holding S.A.', 1),
(140, 'IVE', 'iShares S&P 500 Value ETF', 40),
(141, 'IVW', 'iShares S&P 500 Growth ETF', 20),
(142, 'IWM', 'Ish Rsl 2000', 10),
(143, 'JD', 'JD.Com Inc', 4),
(144, 'JMIA', 'Adr Jumia Technologies Ag', 1),
(145, 'JNJ', 'Johnson & Johnson', 15),
(146, 'JPM', 'JP Morgan Chase', 15),
(147, 'KB', 'Kookmin Bank', 2),
(148, 'KEP', 'Korea Electric Power Corp', 1),
(149, 'KGC', 'Kinross Gold Corp', 1),
(150, 'KMB', 'Kimberly Clark', 6),
(151, 'KO', 'Coca-Cola', 5),
(152, 'KOFM', 'Coca-Cola FEMSA', 2),
(153, 'LAAC', 'Lithium Americas (Argentina) Corp', 1),
(154, 'LAC', 'Lithium Americas Corp', 1),
(155, 'LLY', 'Eli Lilly and Co', 56),
(156, 'LMT', 'Lockheed Martin', 20),
(157, 'LND', 'Brasilagro Coms', 1),
(158, 'LRCX', 'Lam Research', 56),
(159, 'LREN3', 'Lojas Renner', 1),
(160, 'LVS', 'Las Vegas Sands Corp', 2),
(161, 'LYG', 'Lloyds Banking Group PLC', 2),
(162, 'MA', 'Mastercard Inc', 33),
(163, 'MBG', 'DaimlerChrysler', 4),
(164, 'MCD', 'McDonald\'s', 24),
(165, 'MDLZ', 'Mondelez Int Inc', 15),
(166, 'MDT', 'Medtronic Plc', 4),
(167, 'MELI', 'Mercado Libre Inc', 120),
(168, 'META', 'Meta Platforms Inc.', 24),
(169, 'MFG', 'Mizuho Financial Group', 1),
(170, 'MGLU3', 'Magazine Luiza', 1),
(171, 'MMC', 'Marsh & Mclennan', 16),
(172, 'MMM', '3M', 10),
(173, 'MO', 'Altria Group', 4),
(174, 'MOS', 'The Mosaic Co', 5),
(175, 'MRK', 'Merck', 5),
(176, 'MRNA', 'Moderna Inc.', 19),
(177, 'MRVL', 'Marvell Technology Ord', 14),
(178, 'MSFT', 'Microsoft Cp', 30),
(179, 'MSI', 'Motorola', 20),
(180, 'MSTR', 'Microstrategy Inc Cl A New', 20),
(181, 'MU', 'Micron Technology Inc', 5),
(182, 'MUFG', 'Mitsubishi Tokyo Financial Group', 1),
(183, 'MUX', 'Mcewen Mining Ord', 2),
(184, 'NEC1', 'NEC', 1),
(185, 'NEM', 'Newmont Goldcorp', 3),
(186, 'NFLX', 'Netflix Incs', 48),
(187, 'NG', 'Novagold Rss', 1),
(188, 'NIO', 'Nio Inc', 4),
(189, 'NKE', 'Nike Inc', 12),
(190, 'NMR', 'Nomura Holdings', 1),
(191, 'NOKA', 'Nokia', 1),
(192, 'NSAN', 'Nissan Motor', 1),
(193, 'NTES', 'NetEase Inc', 14),
(194, 'NU', 'Nu Holding Ltd Vayman Island Ord', 2),
(195, 'NUE', 'Nucor Corp', 16),
(196, 'NVDA', 'Nvidia Corporation', 24),
(197, 'NVS', 'Novartis', 4),
(198, 'NXE', 'NexGen Energy Ltd', 1),
(199, 'ORAN', 'Orange', 1),
(200, 'ORCL', 'Oracle Corp', 3),
(201, 'ORLY', 'O\'Reilly Automotive Inc', 222),
(202, 'OXY', 'Occidental Petroleum Corp', 5),
(203, 'PAAS', 'Pan American Silver Corp', 3),
(204, 'PAC', 'Grupo Aeroportuario del Pacifico', 16),
(205, 'PAGS', 'Pagseguro Digital Ltd Ord', 3),
(206, 'PANW', 'Palo Alto Networks Inc', 50),
(207, 'PBI', 'Pitney Bowes Inc', 1),
(208, 'PBR', 'Petrobras Ord Shs', 1),
(209, 'PCAR', 'Paccar', 3),
(210, 'PEP', 'Pepsico Inc', 18),
(211, 'PFE', 'Pfizer Rep 0.5', 4),
(212, 'PG', 'Procter & Gamble', 15),
(213, 'PHG', 'Koninklijke Philips Electronics', 5),
(214, 'PINS', 'Pinterest', 7),
(215, 'PKS', 'Posco', 3),
(216, 'PLTR', 'Palantir Technologies Ord', 3),
(217, 'PM', 'Philip Morris Int Inc', 18),
(218, 'PRIO3', 'Prio', 2),
(219, 'PSX', 'Phillips 66', 6),
(220, 'PYPL', 'Paypal Holdings Incs', 8),
(221, 'QCOM', 'Qualcomm Inc', 11),
(222, 'QQQ', 'Invesco Qqq S1', 20),
(223, 'RACE', 'FERRARI NV', 83),
(224, 'RBLX', 'Roblox Corp', 2),
(225, 'RENT3', 'Localiza Rent a Car', 2),
(226, 'RIO', 'Rio Tinto', 8),
(227, 'RIOT', 'Riot Platforms', 3),
(228, 'ROKU', 'Roku', 13),
(229, 'ROST', 'Rost Stones Inc', 4),
(230, 'RTX', 'Raytheon Technologies Corp', 5),
(231, 'SAN', 'Banco Santander Ord Shs', 1),
(232, 'SAP', 'Sap Se', 6),
(233, 'SATL', 'Satellogic Inc', 1),
(234, 'SBS', 'Basic Sant Cpy of the Sta Pul SBSP', 1),
(235, 'SBUX', 'Starbucks', 12),
(236, 'SCCO', 'Southern Copper Corp', 2),
(237, 'SCHW', 'Charles Schwab Corp.', 13),
(238, 'SDA', 'Suncar Technology Group Ord', 2),
(239, 'SE', 'Sea Limited', 32),
(240, 'SH', 'ProShares Short S&P500 ETF', 8),
(241, 'SHEL', 'Shell Plc', 2),
(242, 'SHOP', 'Shopify Inc', 107),
(243, 'SID', 'Companhia Siderurgica Nacional SA', 1),
(244, 'SLB', 'Schlumberger', 3),
(245, 'SNA', 'Snap On', 6),
(246, 'SNAP', 'Snap Inc', 1),
(247, 'SNOW', 'Snowflake Inc', 30),
(248, 'SONY', 'Sony Group Corporation', 8),
(249, 'SPCE', 'VIRGIN GALACTIC HOLD', 1),
(250, 'SPGI', 'S&P Global Inc', 45),
(251, 'SPOT', 'Spotify Technology Sa', 28),
(252, 'SPY', 'Spdr S&P 500', 20),
(253, 'SQ', 'Square Inc', 20),
(254, 'STLA', 'Stellantis NV', 5),
(255, 'STNE', 'Stoneco Ltd Ord', 3),
(256, 'SUZ', 'Suzano 1 American Depositary Shares Representing 1 Ord Shs', 1),
(257, 'SWKS', 'Skyworks Solutions', 21),
(258, 'SYY', 'Sysco Corp', 8),
(259, 'T', 'AT&T DRC', 3),
(260, 'TCOM', 'Ctrip.Com International, Ltd', 2),
(261, 'TEFO', 'Telefonica', 8),
(262, 'TEN', 'Tenaris Ord Shs', 1),
(263, 'TGT', 'Target Corp', 24),
(264, 'TIIAY', 'TIIAY', 1),
(265, 'TIMB', 'Tim Participacaoes S.A.', 1),
(266, 'TJX', 'TJX Companies Inc', 22),
(267, 'TM', 'Toyota Motor', 15),
(268, 'TMO', 'Thermo Fisher Sc Ord Shs', 22),
(269, 'TMUS', 'T MOBILE US', 33),
(270, 'TRIP', 'Trip Advisors', 2),
(271, 'TRVV', 'Travelers Co', 6),
(272, 'TSLA', 'Tesla Inc', 15),
(273, 'TSM', 'Taiwan Semiconductor Manufacturing', 9),
(274, 'TTE', 'TotalEnergies SA', 3),
(275, 'TV', 'Grupo Televisa', 3),
(276, 'TWLO', 'Twilio Inc', 36),
(277, 'TXN', 'Texas Instrument', 5),
(278, 'TXR', 'Ternium', 4),
(279, 'UAL', 'United Airlines Holdings Inc', 5),
(280, 'UBER', 'Uber Technologies Inc', 2),
(281, 'UGP', 'Ultrapar Participacoes SA', 1),
(282, 'UL', 'Unilever Rep 3', 3),
(283, 'UNH', 'Unitedhealth Group Inc', 33),
(284, 'UNP', 'Union Pacific Corp', 20),
(285, 'UPST', 'Upstart Hldgs Inc', 5),
(286, 'URBN', 'Urban Outfitterss', 2),
(287, 'USB', 'US Bancorp', 5),
(288, 'V', 'Visa Inc Ord Shs', 18),
(289, 'VALE', 'Companhia Vale do Rio Doce', 2),
(290, 'VEA', 'Vanguard FTSE Developed Markets ETF', 10),
(291, 'VIST', 'Vista Energy SAB de CV', 3),
(292, 'VIV', 'Telef?nica Brasil', 1),
(293, 'VOD', 'Vodafone Group', 1),
(294, 'VRSN', 'Verisign Inc', 6),
(295, 'VZ', 'Verizon Communications', 4),
(296, 'WBA', 'Walgreens Boots Alliance Inc', 3),
(297, 'WBO', 'Weibo Corp', 6),
(298, 'WFC', 'Wells Fargo & Co', 5),
(299, 'WMT', 'Wal Mart', 18),
(300, 'X', 'United States Steel Corp', 3),
(301, 'XLB', 'The Industrial Select Sector SPDR Fund', 28),
(302, 'XLC', 'The Communication Services Select Sector SPDR Fund', 19),
(303, 'XLE', 'Spdr Energy Sel', 2),
(304, 'XLF', 'Spdr Financl Sel', 2),
(305, 'XLI', 'The Technology Select Sector SPDR Fund', 46),
(306, 'XLK', 'The Health Care Select Sector SPDR Fund', 29),
(307, 'XLP', 'The Real Estate Select Sector SPDR Fund', 9),
(308, 'XLRE', 'The Consumer Discretionary Select Sector SPDR Fund', 43),
(309, 'XLV', 'The Consumer Staples Select Sector SPDR Fund', 16),
(310, 'XLY', 'The Materials Select Sector SPDR Fund', 18),
(311, 'XOM', 'Exxon Mobil', 10),
(312, 'XP', 'Xp Inc', 4),
(313, 'XROX', 'Xerox', 1),
(314, 'YELP', 'Yelp Inc', 2),
(315, 'YY', 'JOYY Inc', 5),
(316, 'YZCA', 'Yanzhou Coal Mining', 2),
(317, 'ZM', 'Zoom Video Communications Inc', 47);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acciones`
--
ALTER TABLE `acciones`
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
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
