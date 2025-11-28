-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-11-2025 a las 13:27:08
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
-- Base de datos: `carniceria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre_corte` varchar(100) NOT NULL,
  `precio_kilo` decimal(10,2) NOT NULL,
  `stock_kg` decimal(10,3) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `id_tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre_corte`, `precio_kilo`, `stock_kg`, `descripcion`, `imagen`, `id_tipo`) VALUES
(1, 'Lomo Fino', 45000.00, 15.500, 'Corte tierno y magro, ideal para asar o a la plancha.', '69298a060ff3b_lomo_fino.jpg', 1),
(2, 'Costilla de Cerdo', 28000.00, 20.000, 'Costilla carnuda perfecta para BBQ o guisos.', '692989f335a30_costilla_de_cerdo.jpg', 2),
(3, 'Pechuga Entera', 18500.00, 30.000, 'Pechuga de pollo fresca sin piel.', '692989c713476_pechuga_entera.jpg', 3),
(4, 'Chorizo Santarrosano', 22000.00, 10.000, 'Chorizo artesanal con especias naturales.', '692989b6c24f2_chorizo_santarrosano.png', 4),
(5, 'Punta de Anca', 38000.00, 12.000, 'Corte con capa de grasa externa que da gran sabor.', '69298826d010a_punta_de_anca.jpg', 1),
(7, 'solomillo', 5.00, 40.000, 'corte para sudado&#13;&#10;', '69298974a6c8f_solomillo.jpg', 1),
(8, 'Churrasco', 35000.00, 10.000, 'Corte de res ideal para asar, jugoso y con buen sabor.', '6929940668de3_churrasco.jpg', 1),
(9, 'Chuleta', 22000.00, 15.000, 'Corte de cerdo para freír, típico de la región.', '692993e0c4b74_chuleta.jpg', 2),
(10, 'Alas de Pollo', 16000.00, 25.000, 'Alitas de pollo, listas para asar o freír.', '6929939b2c08b_alas_de_pollo.jpg', 3),
(11, 'Longaniza', 18000.00, 8.000, 'Embutido tradicional, perfecto para acompañar asados.', '69299374e5b64_longaniza.jpg', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_productos`
--

CREATE TABLE `tipos_productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_productos`
--

INSERT INTO `tipos_productos` (`id`, `nombre`) VALUES
(2, 'Cerdo'),
(4, 'Embutidos'),
(3, 'Pollo'),
(1, 'Res');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`) VALUES
(1, 'Administrador', 'admin@carniceria.com', '$2y$10$M/2TS0CaZcD41msTCOphPueiaEfmNqL4buxCanDRr/Y56rHnlSLne', 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_productos_tipo` (`id_tipo`);

--
-- Indices de la tabla `tipos_productos`
--
ALTER TABLE `tipos_productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `tipos_productos`
--
ALTER TABLE `tipos_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_tipo` FOREIGN KEY (`id_tipo`) REFERENCES `tipos_productos` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
