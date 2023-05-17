-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+jammy2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Час створення: Трв 11 2023 р., 14:19
-- Версія сервера: 8.0.32-0ubuntu0.22.04.2
-- Версія PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `math_randomizer`
--

-- --------------------------------------------------------

--
-- Структура таблиці `students`
--

CREATE TABLE `students` (
  `id` int NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `teacher_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп даних таблиці `students`
--

INSERT INTO `students` (`id`, `full_name`, `email`, `password`, `teacher_id`) VALUES
(2, 'Dmytro Potapov', 'dmytro@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$S0tNbGgxTVcuMTNjbjVZQg$9ELyEICEM7U7+noVqpiNTIkMSb9EM5L4gEmRsCfvwtc', 2),
(3, 'Dmytro Potapov', 'mitya.potapov@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$SkdncXo4RmgzaTdKT2RkUQ$TjSqxuhanQJW9LF8MgqwW9axYXSunHZuc/5VIDV2KAY', 2),
(4, 'Ivan Ivanov', 'student@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$OE9BNVQzbldKQzB1UUR0NA$Jxc/egDRo7NHNTwhx/rPbZ+XtHg39fNtOFw+r9himYI', 2),
(5, 'dddddd ddddddddd', 'dddddd@ddd.ddd', '$argon2id$v=19$m=65536,t=4,p=1$UHdXRUt0dC9nc1hhMmxMdw$H021l9vAaoIoBh0XfkSYN0zymCtyIUpePNK06wgZItA', 2);

-- --------------------------------------------------------

--
-- Структура таблиці `teachers`
--

CREATE TABLE `teachers` (
  `id` int NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп даних таблиці `teachers`
--

INSERT INTO `teachers` (`id`, `full_name`, `email`, `password`) VALUES
(2, 'Best Teacher', 'teacher@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$aHJFRmxsTjFIVk1WVDZDWA$9YFJb6S+PEU8Kw48zjrVOCuQfZLxli7nIt6L3mtx788'),
(6, 'Worse Teacher', 'bad@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$RkQzRXlxUTJJYlgyUmdsNg$RGnf5X90MZxIqBWFpTUAQoakmtLtB3dm2vZZpPaTR7I'),
(7, 'ee ee', 'afk@vks.com', '$argon2id$v=19$m=65536,t=4,p=1$SWp6ZTg2UnhRZnQxSXdncg$xMm9fRiMLyDycNdWjy2TZhjncIsXTuZJGWEKLubUFik');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Індекси таблиці `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `students`
--
ALTER TABLE `students`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблиці `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
