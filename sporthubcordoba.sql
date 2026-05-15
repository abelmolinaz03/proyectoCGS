-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 14-05-2026 a las 13:17:59
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sporthubcordoba`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deportes`
--

CREATE TABLE `deportes` (
  `id_deporte` int(11) NOT NULL,
  `nombre_deporte` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicios_rutina`
--

CREATE TABLE `ejercicios_rutina` (
  `id_ejercicio` int(11) NOT NULL,
  `id_rutina` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `series` int(11) DEFAULT NULL,
  `repeticiones` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descanso_segundos` int(11) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `ejercicios_rutina`
--

INSERT INTO `ejercicios_rutina` (`id_ejercicio`, `id_rutina`, `nombre`, `series`, `repeticiones`, `descanso_segundos`, `orden`) VALUES
(1, 1, 'Balón medicinal 5 KG', 4, '10', 60, 1),
(2, 1, 'Volteos', 3, '4', 0, 2),
(3, 1, 'Giros', 3, '5', 0, 3),
(4, 1, 'Lanzamiento en campo de fútbol', 3, 'Las necesarias', 60, 4),
(5, 1, 'Lanzamiento en jaula', 9, 'de 2 a 3 giros', 0, 5),
(6, 2, 'maullar', 4, '12', 60, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas_deportivas`
--

CREATE TABLE `marcas_deportivas` (
  `id_marca` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `deporte` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `tiempo_o_puntuacion` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_registro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `marcas_deportivas`
--

INSERT INTO `marcas_deportivas` (`id_marca`, `id_usuario`, `deporte`, `tiempo_o_puntuacion`, `fecha_registro`) VALUES
(4, 3, 'Atletismo', '10.989 segundos, 100', '2026-05-09'),
(5, 1, 'Atletismo', '50.20 m, Lanzamiento', '2026-05-04'),
(6, 1, 'Atletismo', 'prueba', '2026-05-07'),
(7, 1, 'Atletismo', 'prueba1', '2026-05-15'),
(8, 1, 'Atletismo', '10.989 segundos, 100', '2026-04-30'),
(9, 1, 'Atletismo', '3 sets ganados', '2026-05-24'),
(10, 2, 'Tenis', 'miau', '2026-05-11'),
(12, 2, 'Tenis', '3 sets ganados', '2026-05-13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pistas`
--

CREATE TABLE `pistas` (
  `id_pista` int(11) NOT NULL,
  `nombre_pista` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `deporte_asociado` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_pista` int(11) NOT NULL,
  `fecha_reserva` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutinas`
--

CREATE TABLE `rutinas` (
  `id_rutina` int(11) NOT NULL,
  `titulo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `deporte` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `tipo` enum('oficial','personal') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'personal',
  `id_usuario` int(11) DEFAULT NULL,
  `dificultad` enum('Principiante','Intermedio','Avanzado') COLLATE utf8_spanish_ci NOT NULL,
  `duracion_minutos` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `rutinas`
--

INSERT INTO `rutinas` (`id_rutina`, `titulo`, `descripcion`, `deporte`, `tipo`, `id_usuario`, `dificultad`, `duracion_minutos`, `fecha_creacion`) VALUES
(1, 'Entrenamiento Lanzamiento de Martillo', 'Preparación para lanzamiento de martillo en el campo de futbol de carcabuey', 'Atletismo', 'personal', 1, 'Intermedio', 60, '2026-05-13 20:04:47'),
(2, 'Entrenamiento de tenis', 'Tenis, miaaaauuuu eloisa hola guapa', 'Tenis', 'personal', 2, 'Avanzado', 50, '2026-05-13 21:42:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `deporte_principal` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellidos`, `email`, `password`, `deporte_principal`) VALUES
(1, 'Abel', 'Molina Zamorano', 'abel@gmail.com', '$2y$10$qluRaqE8Dpr1oe5AoUk43uSvmSp.eQFlPAbxBgq/fOCoy1yYMmsN2', 'Atletismo'),
(2, 'Eloisa', 'Peñaloza Mendoza', 'eloisa@gmail.com', '$2y$10$q9XuYzWsR3ozwIN2fa.MwOKjwqENYaobj9Z5Zd/nXuNUhPnUzWLYG', 'Tenis'),
(3, 'usuarioPrueba', 'prueba', 'prueba@gmail.com', '$2y$10$bqIFwu38JQ866KuZlZPolewremtKaoT7GHRVJrBkE1tkjAND03XJq', 'Atletismo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `deportes`
--
ALTER TABLE `deportes`
  ADD PRIMARY KEY (`id_deporte`),
  ADD UNIQUE KEY `nombre_deporte` (`nombre_deporte`);

--
-- Indices de la tabla `ejercicios_rutina`
--
ALTER TABLE `ejercicios_rutina`
  ADD PRIMARY KEY (`id_ejercicio`),
  ADD KEY `id_rutina` (`id_rutina`);

--
-- Indices de la tabla `marcas_deportivas`
--
ALTER TABLE `marcas_deportivas`
  ADD PRIMARY KEY (`id_marca`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pistas`
--
ALTER TABLE `pistas`
  ADD PRIMARY KEY (`id_pista`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_pista` (`id_pista`);

--
-- Indices de la tabla `rutinas`
--
ALTER TABLE `rutinas`
  ADD PRIMARY KEY (`id_rutina`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `deportes`
--
ALTER TABLE `deportes`
  MODIFY `id_deporte` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ejercicios_rutina`
--
ALTER TABLE `ejercicios_rutina`
  MODIFY `id_ejercicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `marcas_deportivas`
--
ALTER TABLE `marcas_deportivas`
  MODIFY `id_marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `pistas`
--
ALTER TABLE `pistas`
  MODIFY `id_pista` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rutinas`
--
ALTER TABLE `rutinas`
  MODIFY `id_rutina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ejercicios_rutina`
--
ALTER TABLE `ejercicios_rutina`
  ADD CONSTRAINT `ejercicios_rutina_ibfk_1` FOREIGN KEY (`id_rutina`) REFERENCES `rutinas` (`id_rutina`) ON DELETE CASCADE;

--
-- Filtros para la tabla `marcas_deportivas`
--
ALTER TABLE `marcas_deportivas`
  ADD CONSTRAINT `marcas_deportivas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_pista`) REFERENCES `pistas` (`id_pista`) ON DELETE CASCADE;

--
-- Filtros para la tabla `rutinas`
--
ALTER TABLE `rutinas`
  ADD CONSTRAINT `rutinas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
