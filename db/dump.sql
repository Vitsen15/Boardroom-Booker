-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Июн 17 2018 г., 12:11
-- Версия сервера: 10.1.26-MariaDB-0+deb9u1
-- Версия PHP: 7.2.6-1+0~20180611145758.22+stretch~1.gbpe20e8b

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `boardroom_booker`
--

-- --------------------------------------------------------

--
-- Структура таблицы `appointment`
--

DROP TABLE IF EXISTS `appointment`;
CREATE TABLE `appointment` (
  `id` bigint(20) NOT NULL,
  `boardroom_id` bigint(20) DEFAULT NULL,
  `recurring_type_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `appointment`
--

TRUNCATE TABLE `appointment`;
--
-- Дамп данных таблицы `appointment`
--

INSERT INTO `appointment` (`id`, `boardroom_id`, `recurring_type_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, '2018-06-16 15:17:45', '0000-00-00 00:00:00'),
(2, 3, 2, '2018-06-11 11:53:28', '0000-00-00 00:00:00'),
(3, 4, 3, '2018-06-11 11:53:28', '0000-00-00 00:00:00'),
(133, 1, 1, '2018-06-16 15:52:16', '0000-00-00 00:00:00'),
(134, 3, 1, '2018-06-16 16:11:03', '0000-00-00 00:00:00'),
(135, 1, NULL, '2018-06-16 16:58:03', '0000-00-00 00:00:00'),
(136, 1, 1, '2018-06-16 17:08:59', '0000-00-00 00:00:00'),
(137, 1, NULL, '2018-06-16 17:10:19', '0000-00-00 00:00:00'),
(138, 1, NULL, '2018-06-16 17:11:30', '0000-00-00 00:00:00'),
(139, 1, 3, '2018-06-16 17:12:03', '0000-00-00 00:00:00'),
(140, 1, 3, '2018-06-16 17:12:22', '0000-00-00 00:00:00'),
(141, 1, 3, '2018-06-16 17:13:20', '0000-00-00 00:00:00'),
(142, 1, 3, '2018-06-16 17:14:16', '0000-00-00 00:00:00'),
(143, 1, 3, '2018-06-16 17:14:35', '0000-00-00 00:00:00'),
(144, 1, 3, '2018-06-16 17:19:38', '0000-00-00 00:00:00'),
(145, 1, 3, '2018-06-16 17:20:20', '0000-00-00 00:00:00'),
(146, 1, 1, '2018-06-17 07:40:31', '0000-00-00 00:00:00'),
(147, 1, 1, '2018-06-17 07:42:59', '0000-00-00 00:00:00'),
(148, 1, 3, '2018-06-17 08:11:39', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `appointment_date`
--

DROP TABLE IF EXISTS `appointment_date`;
CREATE TABLE `appointment_date` (
  `id` bigint(20) NOT NULL,
  `appointment_id` bigint(20) DEFAULT NULL,
  `employee_id` bigint(20) DEFAULT NULL,
  `notes` text,
  `date` date NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `appointment_date`
--

TRUNCATE TABLE `appointment_date`;
--
-- Дамп данных таблицы `appointment_date`
--

INSERT INTO `appointment_date` (`id`, `appointment_id`, `employee_id`, `notes`, `date`, `start_time`, `end_time`, `is_deleted`, `deleted_at`) VALUES
(2, 1, 1, 'test', '2018-06-11', '2018-06-11 09:00:00', '2018-06-11 10:00:00', 1, '2018-06-16 17:10:52'),
(3, 1, NULL, 'Test', '2018-06-05', '2018-06-05 14:00:00', '2018-06-05 15:22:00', 1, '2018-06-17 07:39:26'),
(4, 1, 1, 'test dates', '2018-06-16', '2018-06-16 12:00:00', '2018-06-16 14:00:00', 0, NULL),
(151, 146, 1, 'Test', '2018-06-17', '2018-06-16 22:00:00', '2018-06-17 04:00:00', 1, '2018-06-17 07:42:23'),
(152, 146, 1, 'Test', '2018-06-24', '2018-06-23 22:00:00', '2018-06-24 04:00:00', 1, '2018-06-17 07:42:23'),
(153, 146, 1, 'Test', '2018-07-01', '2018-06-30 22:00:00', '2018-07-01 04:00:00', 1, '2018-06-17 07:42:23'),
(154, 146, 1, 'Test', '2018-07-08', '2018-07-07 22:00:00', '2018-07-08 04:00:00', 1, '2018-06-17 07:42:23'),
(155, 147, 1, 'test', '2018-06-18', '2018-06-24 22:00:00', '2018-06-25 03:00:00', 1, '2018-06-17 07:43:32'),
(156, 147, 1, 'test', '2018-06-25', '2018-06-24 22:00:00', '2018-06-25 03:00:00', 0, NULL),
(157, 147, 1, 'test', '2018-07-02', '2018-06-24 22:00:00', '2018-06-25 03:00:00', 0, NULL),
(158, 147, 1, 'test', '2018-07-09', '2018-06-24 22:00:00', '2018-06-25 03:00:00', 0, NULL),
(159, 148, 1, 'totoroto', '2018-08-01', '2018-07-31 22:00:00', '2018-08-01 01:00:00', 0, NULL),
(160, 148, 1, 'totoroto', '2018-09-01', '2018-08-31 22:00:00', '2018-09-01 01:00:00', 0, NULL),
(161, 148, 1, 'totoroto', '2018-10-01', '2018-09-30 22:00:00', '2018-10-01 01:00:00', 0, NULL),
(162, 148, 1, 'totoroto', '2018-11-01', '2018-10-31 23:00:00', '2018-11-01 02:00:00', 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `boardroom`
--

DROP TABLE IF EXISTS `boardroom`;
CREATE TABLE `boardroom` (
  `id` bigint(20) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `boardroom`
--

TRUNCATE TABLE `boardroom`;
--
-- Дамп данных таблицы `boardroom`
--

INSERT INTO `boardroom` (`id`, `name`) VALUES
(1, 'Board room 1'),
(3, 'Board room 2'),
(4, 'Board room 3');

-- --------------------------------------------------------

--
-- Структура таблицы `employee`
--

DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee` (
  `id` bigint(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `employee`
--

TRUNCATE TABLE `employee`;
--
-- Дамп данных таблицы `employee`
--

INSERT INTO `employee` (`id`, `email`, `first_name`, `last_name`) VALUES
(1, 'john_cena@wwe.com', 'John', 'Cena'),
(2, 'randy_orton@wwe.com', 'Randy', 'Orton'),
(4, 'joht@djfdklf.com', 'John', 'Doe'),
(27, 'test@gmail.com', 'test', 'test');

-- --------------------------------------------------------

--
-- Структура таблицы `recurring_type`
--

DROP TABLE IF EXISTS `recurring_type`;
CREATE TABLE `recurring_type` (
  `id` bigint(20) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `recurring_type`
--

TRUNCATE TABLE `recurring_type`;
--
-- Дамп данных таблицы `recurring_type`
--

INSERT INTO `recurring_type` (`id`, `name`) VALUES
(2, 'bi-weekly'),
(3, 'monthly'),
(1, 'weekly');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` bigint(20) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `first_name` varchar(15) NOT NULL,
  `last_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Очистить таблицу перед добавлением данных `user`
--

TRUNCATE TABLE `user`;
--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `access_token`, `first_name`, `last_name`) VALUES
(12, 'john_cena', 'kdEb/.3k5oRUE', 'f1bd1bd0a0e27898d2b89a3dfef37fb8', 'john', 'cena');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `appointment_id_uindex` (`id`),
  ADD KEY `appointment_boardroom_id_fk` (`boardroom_id`),
  ADD KEY `appointment_recurring_type_id_fk` (`recurring_type_id`);

--
-- Индексы таблицы `appointment_date`
--
ALTER TABLE `appointment_date`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `recurring_date_id_uindex` (`id`),
  ADD KEY `appointment_date_appointment_id_fk` (`appointment_id`),
  ADD KEY `appointment_date_employee_id_fk` (`employee_id`);

--
-- Индексы таблицы `boardroom`
--
ALTER TABLE `boardroom`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `boardroom_boardroom_name_uindex` (`name`),
  ADD UNIQUE KEY `boardroom_id_uindex` (`id`);

--
-- Индексы таблицы `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id_uindex` (`id`),
  ADD UNIQUE KEY `employee_email_uindex` (`email`);

--
-- Индексы таблицы `recurring_type`
--
ALTER TABLE `recurring_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `recurring_type_id_uindex` (`id`),
  ADD UNIQUE KEY `recurring_type_name_uindex` (`name`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_uindex` (`id`),
  ADD UNIQUE KEY `user_username_uindex` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;
--
-- AUTO_INCREMENT для таблицы `appointment_date`
--
ALTER TABLE `appointment_date`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;
--
-- AUTO_INCREMENT для таблицы `boardroom`
--
ALTER TABLE `boardroom`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `employee`
--
ALTER TABLE `employee`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT для таблицы `recurring_type`
--
ALTER TABLE `recurring_type`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_boardroom_id_fk` FOREIGN KEY (`boardroom_id`) REFERENCES `boardroom` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `appointment_recurring_type_id_fk` FOREIGN KEY (`recurring_type_id`) REFERENCES `recurring_type` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `appointment_date`
--
ALTER TABLE `appointment_date`
  ADD CONSTRAINT `appointment_date_appointment_id_fk` FOREIGN KEY (`appointment_id`) REFERENCES `appointment` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `appointment_date_employee_id_fk` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
