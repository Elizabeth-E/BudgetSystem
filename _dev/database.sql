-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2020 at 10:07 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `budget_system_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `type` enum('DEBIT','CREDITCARD','SAVINGS') NOT NULL,
  `amount` float NOT NULL,
  `accountname` varchar(50) NOT NULL,
  `accountnum` char(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `accounts`
--

TRUNCATE TABLE `accounts`;
--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `type`, `amount`, `accountname`, `accountnum`) VALUES
(15, 'CREDITCARD', 965.4, 'testaccountqwef', NULL),
(16, 'DEBIT', 12854.3, 'rabo debit', NULL),
(18, 'SAVINGS', 965.4, 'Kilo\'s pocket money', NULL),
(20, 'SAVINGS', 0, 'mitchel', NULL),
(21, 'DEBIT', 524.02, 'asdf', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `amount` float NOT NULL,
  `date` date NOT NULL,
  `frequency` enum('DAILY','WEEKLY','MONTHLY','YEARLY') DEFAULT NULL,
  `accounts_id` int(11) NOT NULL,
  `bill_categories_id` int(11) NOT NULL,
  `paid` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `bills`
--

TRUNCATE TABLE `bills`;
--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `name`, `amount`, `date`, `frequency`, `accounts_id`, `bill_categories_id`, `paid`) VALUES
(4, 'ccz', 280.9, '2020-04-29', 'MONTHLY', 15, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bill_categories`
--

CREATE TABLE `bill_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `bill_categories`
--

TRUNCATE TABLE `bill_categories`;
--
-- Dumping data for table `bill_categories`
--

INSERT INTO `bill_categories` (`id`, `name`) VALUES
(1, 'household'),
(2, 'health'),
(3, 'none'),
(4, 'subscriptions'),
(5, 'food'),
(6, 'going out'),
(7, 'emergency costs'),
(8, 'utilities'),
(9, 'taxes'),
(10, 'pets'),
(11, 'minions'),
(12, 'hunger games'),
(13, 'hogwarts');

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE `pictures` (
  `id` int(11) NOT NULL,
  `path` varchar(200) NOT NULL,
  `users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `pictures`
--

TRUNCATE TABLE `pictures`;
--
-- Dumping data for table `pictures`
--

INSERT INTO `pictures` (`id`, `path`, `users_id`) VALUES
(1, '/webroot/img/pic.jpg', 15),
(2, '/webroot/img/phonestuff0822019 215.jpg', 15),
(3, '/webroot/img/phonestuff0822019 012.jpg', 15),
(4, '/webroot/img/phonestuff0822019 012.jpg/phonestuff0822019 013.jpg', 15);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `amount` float DEFAULT 0,
  `accounts_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `transactions`
--

TRUNCATE TABLE `transactions`;
--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `date`, `name`, `description`, `amount`, `accounts_id`) VALUES
(3, '2020-04-12', 'disneyplus', 'disney plus subscription', 10.99, 15),
(4, '2020-04-12', 'amazon', 'amazon prime subscription', 3.99, 15);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(75) NOT NULL,
  `password` varchar(75) NOT NULL,
  `email` varchar(100) NOT NULL,
  `firstname` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `validation_token` varchar(32) DEFAULT NULL,
  `activation_status` int(1) DEFAULT 0,
  `user_roles_id` int(11) NOT NULL DEFAULT 3,
  `registration_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `firstname`, `lastname`, `birthdate`, `validation_token`, `activation_status`, `user_roles_id`, `registration_date`) VALUES
(12, 'kilo', '638abdcc7fa03ce6179133f2cb6b2634f8acdc01', 'mitchelkoster@gmail.com', 'Mitchel', 'Erickson', '2018-11-08', 'a544eeda640b6d2a9550e20b1a6b03c7', 0, 3, '2018-11-25 21:36:53'),
(15, 'Liz', 'c97d4d9bbbf4f1b3b9bec91a0d6259dfffe51452', 'elizabeth.erickson21@gmail.com', 'Elizabeth', 'Erickson', '2017-11-23', '3e99c3fd1aea32a795add79397439f1a', 1, 3, '2019-09-18 10:33:34');

-- --------------------------------------------------------

--
-- Table structure for table `users_has_accounts`
--

CREATE TABLE `users_has_accounts` (
  `users_id` int(11) NOT NULL,
  `accounts_id` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `users_has_accounts`
--

TRUNCATE TABLE `users_has_accounts`;
--
-- Dumping data for table `users_has_accounts`
--

INSERT INTO `users_has_accounts` (`users_id`, `accounts_id`, `id`) VALUES
(15, 15, 4),
(15, 16, 5),
(15, 18, 6),
(15, 20, 8),
(15, 21, 9);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `role` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `user_roles`
--

TRUNCATE TABLE `user_roles`;
--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `role`) VALUES
(1, 'super_admin'),
(2, 'admin'),
(3, 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bills_accounts1_idx` (`accounts_id`),
  ADD KEY `fk_bills_bill_categories1_idx` (`bill_categories_id`);

--
-- Indexes for table `bill_categories`
--
ALTER TABLE `bill_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transcations_accounts1_idx` (`accounts_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_user_roles1_idx` (`user_roles_id`);

--
-- Indexes for table `users_has_accounts`
--
ALTER TABLE `users_has_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_has_accounts_accounts1_idx` (`accounts_id`),
  ADD KEY `fk_users_has_accounts_users1_idx` (`users_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `bill_categories`
--
ALTER TABLE `bill_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pictures`
--
ALTER TABLE `pictures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users_has_accounts`
--
ALTER TABLE `users_has_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `fk_bills_accounts1` FOREIGN KEY (`accounts_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_bills_bill_categories1` FOREIGN KEY (`bill_categories_id`) REFERENCES `bill_categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pictures`
--
ALTER TABLE `pictures`
  ADD CONSTRAINT `pictures_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transcations_accounts1` FOREIGN KEY (`accounts_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_user_roles1` FOREIGN KEY (`user_roles_id`) REFERENCES `user_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `users_has_accounts`
--
ALTER TABLE `users_has_accounts`
  ADD CONSTRAINT `fk_users_has_accounts_accounts1` FOREIGN KEY (`accounts_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_has_accounts_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
