-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Čtv 19. led 2023, 17:23
-- Verze serveru: 10.4.25-MariaDB
-- Verze PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `db_of_clients`
--
CREATE DATABASE IF NOT EXISTS `db_of_clients` DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci;
USE `db_of_clients`;

-- --------------------------------------------------------

--
-- Struktura tabulky `dbc_addresses`
--

CREATE TABLE `dbc_addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `street` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `city` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `zip` int(10) UNSIGNED NOT NULL,
  `type` enum('contact','corresponding') COLLATE utf8_czech_ci NOT NULL DEFAULT 'contact',
  `created` int(11) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `dbc_addresses`
--

INSERT INTO `dbc_addresses` (`id`, `user_id`, `street`, `city`, `zip`, `type`, `created`) VALUES
(24, 1, 'Ulice', 'Město', 12314, 'contact', 2147483647),
(25, 2, 'Ulice', 'Město', 123, 'contact', 2147483647),
(26, 3, 'Ulice', 'Město', 123456, 'contact', 2147483647),
(27, 4, '', '', 0, 'contact', 2147483647),
(28, 5, '', '', 0, 'contact', 2147483647),
(29, 5, '', '', 0, 'contact', 2147483647),
(30, 5, '', '', 0, 'contact', 2147483647),
(31, 5, '', '', 0, 'contact', 2147483647),
(32, 5, '', '', 0, 'contact', 2147483647),
(33, 5, '', '', 0, 'contact', 2147483647),
(34, 1, 'Ulice', 'Město', 12314, 'contact', 2147483647),
(35, 1, 'Ulice', 'Město', 12314, 'contact', 2147483647),
(36, 5, '', '', 0, 'contact', 2147483647),
(37, 5, '', '', 0, 'contact', 2147483647),
(38, 5, '', '', 0, 'contact', 2147483647),
(39, 3, 'Ulice', 'Město', 123456, 'contact', 2147483647),
(40, 3, 'Ulice', 'Město', 123456, 'contact', 2147483647),
(41, 5, '', '', 0, 'contact', 2147483647),
(42, 4, '', '', 0, 'contact', 2147483647),
(43, 5, '', '', 0, 'contact', 2147483647),
(44, 7, '', '', 0, 'contact', 2147483647),
(45, 6, '', '', 0, 'contact', 2147483647),
(46, 1, 'Ulice', 'Město', 12314, 'contact', 2147483647);

-- --------------------------------------------------------

--
-- Struktura tabulky `dbc_menu`
--

CREATE TABLE `dbc_menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `url` varchar(50) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `dbc_menu`
--

INSERT INTO `dbc_menu` (`id`, `role_id`, `label`, `url`) VALUES
(1, 5, 'Domů', 'home'),
(2, 5, 'Změna údajů', 'profile'),
(3, 3, 'Seznam klientů', 'clients'),
(4, 4, 'Správa událostí', 'events'),
(5, 3, 'Správa produktů', 'products'),
(6, 3, 'Nový klient', 'newClient'),
(7, 2, 'Správa rolí', 'roles'),
(8, 5, 'Odhlásit', 'logout'),
(9, 1, 'Testovací stránka', 'test');

-- --------------------------------------------------------

--
-- Struktura tabulky `dbc_products`
--

CREATE TABLE `dbc_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `description` text COLLATE utf8_czech_ci NOT NULL,
  `status` enum('active','pending','on hold','retired','cancelled','plan') COLLATE utf8_czech_ci NOT NULL DEFAULT 'active',
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `dbc_products`
--

INSERT INTO `dbc_products` (`id`, `name`, `description`, `status`, `created`) VALUES
(1, 'Pojištění nemovitosti', 'Dům, zahrada.\r\nPokrývá pád stromu a letadla.\r\nNepokrývá povodeň a krupobití.', 'active', '2022-12-17 18:11:58'),
(2, 'Pojištění příjmu', 'TBD', 'plan', '2022-12-17 18:12:24'),
(3, 'Havarijní pojištění automobilu', 'Do 3,5T.Pokrývá nezaviněnou nehodu.Nepokrývá srážku se zvěří a živly.', 'active', '2022-12-17 18:13:23'),
(4, 'jijij', 'ijiji', 'retired', '2022-12-22 14:15:07'),
(6, 'koko', 'koko', 'retired', '2022-12-22 14:17:04'),
(8, 'Promo akce', 'Pojištění všeho za polovic', 'retired', '2022-12-28 12:19:26'),
(10, 'kokokoko', 'oooo', 'retired', '2022-12-28 12:33:38'),
(11, 'Promo produkt', 'Pojištění všeho za polovic', 'active', '2022-12-28 13:30:03');

-- --------------------------------------------------------

--
-- Struktura tabulky `dbc_roles`
--

CREATE TABLE `dbc_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `dbc_roles`
--

INSERT INTO `dbc_roles` (`id`, `name`, `description`) VALUES
(1, 'Administrátor', 'Může přidat manažera'),
(2, 'Manažer', 'Přiřazuje role 3-5'),
(3, 'Agent', 'Přiřazuje produkty'),
(4, 'Likvidátor', 'Spravuje události'),
(5, 'Klient', 'Spravuje osobní údaje');

-- --------------------------------------------------------

--
-- Struktura tabulky `dbc_users`
--

CREATE TABLE `dbc_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `password` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL DEFAULT 5,
  `email` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `fname` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `lname` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_czech_ci DEFAULT NULL,
  `status` enum('new','active','inactive','locked') COLLATE utf8_czech_ci NOT NULL DEFAULT 'new',
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `dbc_users`
--

INSERT INTO `dbc_users` (`id`, `password`, `role_id`, `email`, `fname`, `lname`, `phone`, `status`, `created`) VALUES
(1, '$2y$10$4rfXxufTcVztydn9TjhLSete7ujJi2xJFVSzwGW78PelVGdhwVDDi', 1, 'admin@admin.cz', 'Jméno Administrátora', 'Příjmení administrátora', '+420 123 123 123', 'active', '2022-12-18 14:26:36'),
(2, 'pokus', 5, 'test@t.c', 'Jméno', 'Příjmení', '123', 'active', '2022-12-18 14:35:01'),
(3, '$2y$10$HbmY7FSHpMmlAzU.mU2wk.DBSapgC3uSXj7FstvI9qp00heuhyzpy', 5, 'user@email.cz', 'Uživatel', 'Základní', '', 'active', '2022-12-22 09:54:03'),
(4, '$2y$10$KmT9TOx58bEMdeOjM7T1bOF6EtD94rl/Ntt05Fm1H9OaYMSWkjdKO', 4, 'likvidator@pojistovna.cz', 'Pojišťovák', 'Likvidátor', '', 'active', '2022-12-22 09:55:49'),
(5, '$2y$10$ZGW9g.WAjhD5OE9mWs5hc.52ySvWC63wyxgs0kIlEtj2zNjj0D8RO', 3, 'agent@pojistovna.cz', 'Pojišťovák', 'Agent', '987654', 'active', '2022-12-22 09:59:26'),
(6, '$2y$10$lbk4btsQn/mqhKOa9FchteStyxG343mS6nH2KIjhO/vqE9euNmFEi', 2, 'manager@pojistovna.cz', 'Vedoucí', 'Manažer', '', 'active', '2022-12-22 10:00:20'),
(7, '$2y$10$xe6FiG4NCcRf6qw55PpTce0Q/RXGN38FFjacAhVD8xgm4jTRs9Yvq', 5, 'klient@mail.cz', 'Já', 'Jménem', '123456789', 'active', '2022-12-28 13:30:26');

-- --------------------------------------------------------

--
-- Struktura tabulky `dbc_user_event`
--

CREATE TABLE `dbc_user_event` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `author_id` int(10) UNSIGNED NOT NULL,
  `comment` text COLLATE utf8_czech_ci NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `dbc_user_event`
--

INSERT INTO `dbc_user_event` (`user_id`, `product_id`, `author_id`, `comment`, `created`) VALUES
(7, 11, 6, 'upadl na ledu za 1000Kč', '2022-12-28 13:32:00');

-- --------------------------------------------------------

--
-- Struktura tabulky `dbc_user_product`
--

CREATE TABLE `dbc_user_product` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `value` int(10) UNSIGNED NOT NULL,
  `valid_from` date NOT NULL,
  `valid_to` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `dbc_user_product`
--

INSERT INTO `dbc_user_product` (`user_id`, `product_id`, `subject`, `value`, `valid_from`, `valid_to`) VALUES
(7, 11, 'pojištění zdraví', 100000, '2022-12-28', '2023-12-28');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `dbc_addresses`
--
ALTER TABLE `dbc_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_address` (`user_id`);

--
-- Indexy pro tabulku `dbc_menu`
--
ALTER TABLE `dbc_menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menu_name` (`label`),
  ADD KEY `menu_role` (`role_id`);

--
-- Indexy pro tabulku `dbc_products`
--
ALTER TABLE `dbc_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_name` (`name`);

--
-- Indexy pro tabulku `dbc_roles`
--
ALTER TABLE `dbc_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`name`);

--
-- Indexy pro tabulku `dbc_users`
--
ALTER TABLE `dbc_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email` (`email`),
  ADD KEY `user_role` (`role_id`);

--
-- Indexy pro tabulku `dbc_user_event`
--
ALTER TABLE `dbc_user_event`
  ADD KEY `user_event` (`user_id`),
  ADD KEY `event_product` (`product_id`),
  ADD KEY `author_event` (`author_id`);

--
-- Indexy pro tabulku `dbc_user_product`
--
ALTER TABLE `dbc_user_product`
  ADD KEY `user_product` (`user_id`),
  ADD KEY `product_products` (`product_id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `dbc_addresses`
--
ALTER TABLE `dbc_addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pro tabulku `dbc_products`
--
ALTER TABLE `dbc_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pro tabulku `dbc_users`
--
ALTER TABLE `dbc_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `dbc_addresses`
--
ALTER TABLE `dbc_addresses`
  ADD CONSTRAINT `user_address` FOREIGN KEY (`user_id`) REFERENCES `dbc_users` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `dbc_menu`
--
ALTER TABLE `dbc_menu`
  ADD CONSTRAINT `menu_role` FOREIGN KEY (`role_id`) REFERENCES `dbc_roles` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `dbc_users`
--
ALTER TABLE `dbc_users`
  ADD CONSTRAINT `user_role` FOREIGN KEY (`role_id`) REFERENCES `dbc_roles` (`id`) ON DELETE NO ACTION;

--
-- Omezení pro tabulku `dbc_user_event`
--
ALTER TABLE `dbc_user_event`
  ADD CONSTRAINT `author_event` FOREIGN KEY (`author_id`) REFERENCES `dbc_users` (`id`),
  ADD CONSTRAINT `event_product` FOREIGN KEY (`product_id`) REFERENCES `dbc_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_event` FOREIGN KEY (`user_id`) REFERENCES `dbc_users` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `dbc_user_product`
--
ALTER TABLE `dbc_user_product`
  ADD CONSTRAINT `product_products` FOREIGN KEY (`product_id`) REFERENCES `dbc_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_product` FOREIGN KEY (`user_id`) REFERENCES `dbc_users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
