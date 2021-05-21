-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2021 at 09:19 PM
-- Server version: 5.7.34
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `obermaye_wf-backend-5-ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `pk_answer_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `create_datetime` datetime NOT NULL,
  `fk_question_id` int(11) NOT NULL,
  `fk_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`pk_answer_id`, `answer`, `create_datetime`, `fk_question_id`, `fk_user_id`) VALUES
(10, 'aaaaaaaaa11111111111111', '2021-05-21 09:39:13', 15, 5),
(11, 'aaaaaaaaaa333333333333', '2021-05-21 09:40:53', 17, 5),
(12, 'aaaaaaaa111111111111122222222', '2021-05-21 09:41:34', 15, 5),
(13, '111111111111111111111111', '2021-05-21 10:04:41', 18, 5),
(14, '3333333333', '2021-05-21 10:04:51', 20, 5);

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `pk_cart_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `fk_product_id` int(11) NOT NULL,
  `fk_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`pk_cart_item_id`, `quantity`, `fk_product_id`, `fk_user_id`) VALUES
(1, 1, 4, 4),
(2, 1, 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `pk_chat_id` int(11) NOT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `fk_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `chat_message`
--

CREATE TABLE `chat_message` (
  `pk_chat_message_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `create_datetime` datetime NOT NULL,
  `fk_user_id` int(11) NOT NULL,
  `fk_chat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `pk_product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `brand` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `category` varchar(100) NOT NULL,
  `status` enum('active','deactive') NOT NULL DEFAULT 'active',
  `discount_procent` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`pk_product_id`, `name`, `description`, `brand`, `image`, `price`, `category`, `status`, `discount_procent`) VALUES
(1, 'Roi Jumpmaster 360', 'Performance skateboard made of corbon fiber...', 'Roi Sports Goods', 'pexels-photo-800627.jpeg', 320, 'Sport', 'active', 0),
(2, 'NoSweat - Bicycling Outdoor Outfit', 'Outdoor outfit to enhance your performance to the maximum!', 'Under Armour', 'pexels-photo-1302925.webp', 68, 'Fashion', 'active', 20),
(3, 'Vintage TV', 'Vintage TV from the 80s in color yellow. ', 'Yellow Submarine', 'default-product.jpg', 299, 'Electronics', 'active', 25),
(4, 'Black Spoons', 'It your soup in style with a black spoon. Comes in a set of three spoons.', 'Tastum', 'default-product.jpg', 30, 'Household', 'active', 50),
(6, 'Sport Shoes', 'blahblah', 'nike', 'img4.jpg', 200, 'Sport', 'active', 0),
(7, 'Omega Sport Watch', 'Blah blah blah', 'Qicksilver', 'img3.jpg', 25, 'Fashion', 'active', 0),
(11, 'Bicycle', 'This Mormac comes with reliable features that include mechanical Ultegra and reliably decelerating hydraulic disc brakes, durable DT P160 wheels and our popular power saddle.', 'Mormac', 'default-product.jpg', 349, 'Sport', 'active', 0),
(13, 'asdf', 'asdf', 'asdf', 'default-product.jpg', 23, 'asdf', 'active', 0),
(14, 'Something', 'fds', 'sdf', 'default-product.jpg', 84, 'Sport', 'active', 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `pk_purchase_id` int(11) NOT NULL,
  `create_datetime` datetime NOT NULL,
  `fk_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_item`
--

CREATE TABLE `purchase_item` (
  `pk_purchase_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `fk_product_id` int(11) NOT NULL,
  `fk_purchase_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `pk_question_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `create_datetime` datetime NOT NULL,
  `fk_product_id` int(11) NOT NULL,
  `fk_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`pk_question_id`, `question`, `create_datetime`, `fk_product_id`, `fk_user_id`) VALUES
(15, 'Test1111111', '2021-05-21 09:38:47', 3, 5),
(16, 'test22222222', '2021-05-21 09:38:55', 3, 5),
(17, 'test333333333333', '2021-05-21 09:39:04', 3, 5),
(18, '111111111111111111', '2021-05-21 10:04:23', 2, 5),
(19, '2222222222222222222222222', '2021-05-21 10:04:29', 2, 5),
(20, '3333333333333333333333333', '2021-05-21 10:04:34', 2, 5),
(21, '3333333333333', '2021-05-21 10:05:02', 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `pk_review_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `title` varchar(50) NOT NULL,
  `comment` text NOT NULL,
  `create_datetime` datetime NOT NULL,
  `fk_product_id` int(11) NOT NULL,
  `fk_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`pk_review_id`, `rating`, `title`, `comment`, `create_datetime`, `fk_product_id`, `fk_user_id`) VALUES
(1, 4, 'Test1', 'test test test', '2020-05-21 09:43:18', 1, 1),
(2, 3, 'Test2', 'Test test test', '2020-05-21 09:43:39', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `pk_user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(120) NOT NULL,
  `postcode` char(12) NOT NULL,
  `country` varchar(50) NOT NULL,
  `birthdate` date NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `profile_image` varchar(255) DEFAULT NULL,
  `banned_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`pk_user_id`, `email`, `password`, `first_name`, `last_name`, `address`, `city`, `postcode`, `country`, `birthdate`, `status`, `role`, `profile_image`, `banned_until`) VALUES
(1, 'sadmin@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Super', 'Admin', 'Superstreet 1', 'Supercity', '1234', 'Supercountry', '1990-11-11', 'active', 'admin', 'default-user.jpg', NULL),
(4, 'user@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Bernhardino', 'Sandhill', 'Fakestreet 4', 'Supercity', '12315', 'Fakecountry', '1990-11-11', 'active', 'user', 'default-user.jpg', NULL),
(5, 'admin@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Normal', 'Admin', 'adminstreet 1', 'damincity', '1234', 'admincountry', '1990-11-11', 'active', 'admin', 'default-user.jpg', NULL),
(6, 'mail@mail.com', '68d7baa0399aced7f0b2e1290b8f157a6880bbf67015ea7dae72232517d10fb8', 'ttzutzu', 'uzututt', 'u', 'u', 'u', 'u', '2021-04-27', 'active', 'user', 'default-image.jpg', NULL),
(17, 'dandy@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Dandy', 'Lewis', 'Wiesenstr 4', 'New Ork', '1234', 'USE', '1990-11-11', 'active', 'user', '60a7839eece48.jpeg', NULL),
(19, 'sandy@mail.com', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855', 'Sandy', 'Sandhill', 'Sandstreet 4', 'New Ork', '1234', 'USE', '1990-11-11', 'active', 'user', '60a7f9da9a5c1.jpeg', '2021-05-20 21:04:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`pk_answer_id`),
  ADD KEY `fk_question_id` (`fk_question_id`),
  ADD KEY `fk_user_id` (`fk_user_id`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`pk_cart_item_id`),
  ADD KEY `fk_product_id` (`fk_product_id`),
  ADD KEY `fk_user_id` (`fk_user_id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`pk_chat_id`),
  ADD KEY `fk_user_id` (`fk_user_id`);

--
-- Indexes for table `chat_message`
--
ALTER TABLE `chat_message`
  ADD PRIMARY KEY (`pk_chat_message_id`),
  ADD KEY `fk_user_id` (`fk_user_id`),
  ADD KEY `fk_chat_id` (`fk_chat_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pk_product_id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`pk_purchase_id`),
  ADD KEY `fk_user_id` (`fk_user_id`);

--
-- Indexes for table `purchase_item`
--
ALTER TABLE `purchase_item`
  ADD PRIMARY KEY (`pk_purchase_item_id`),
  ADD KEY `fk_product_id` (`fk_product_id`),
  ADD KEY `fk_purchase_id` (`fk_purchase_id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`pk_question_id`),
  ADD KEY `fk_product_id` (`fk_product_id`),
  ADD KEY `fk_user_id` (`fk_user_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`pk_review_id`),
  ADD KEY `fk_product_id` (`fk_product_id`),
  ADD KEY `fk_user_id` (`fk_user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`pk_user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `pk_answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `pk_cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `pk_chat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_message`
--
ALTER TABLE `chat_message`
  MODIFY `pk_chat_message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `pk_product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `pk_purchase_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_item`
--
ALTER TABLE `purchase_item`
  MODIFY `pk_purchase_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `pk_question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `pk_review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `pk_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`fk_question_id`) REFERENCES `question` (`pk_question_id`),
  ADD CONSTRAINT `answer_ibfk_2` FOREIGN KEY (`fk_user_id`) REFERENCES `user` (`pk_user_id`);

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`fk_product_id`) REFERENCES `product` (`pk_product_id`),
  ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`fk_user_id`) REFERENCES `user` (`pk_user_id`);

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`fk_user_id`) REFERENCES `user` (`pk_user_id`);

--
-- Constraints for table `chat_message`
--
ALTER TABLE `chat_message`
  ADD CONSTRAINT `chat_message_ibfk_1` FOREIGN KEY (`fk_user_id`) REFERENCES `user` (`pk_user_id`),
  ADD CONSTRAINT `chat_message_ibfk_2` FOREIGN KEY (`fk_chat_id`) REFERENCES `chat` (`pk_chat_id`);

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `purchase_ibfk_1` FOREIGN KEY (`fk_user_id`) REFERENCES `user` (`pk_user_id`);

--
-- Constraints for table `purchase_item`
--
ALTER TABLE `purchase_item`
  ADD CONSTRAINT `purchase_item_ibfk_1` FOREIGN KEY (`fk_product_id`) REFERENCES `product` (`pk_product_id`),
  ADD CONSTRAINT `purchase_item_ibfk_2` FOREIGN KEY (`fk_purchase_id`) REFERENCES `purchase` (`pk_purchase_id`);

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`fk_product_id`) REFERENCES `product` (`pk_product_id`),
  ADD CONSTRAINT `question_ibfk_2` FOREIGN KEY (`fk_user_id`) REFERENCES `user` (`pk_user_id`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`fk_product_id`) REFERENCES `product` (`pk_product_id`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`fk_user_id`) REFERENCES `user` (`pk_user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
