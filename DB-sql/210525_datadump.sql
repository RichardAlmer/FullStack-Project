-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 25, 2021 at 09:48 AM
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
(17, 'Not a problem to put it into water for a minute. But would not leave it a lot longer.', '2021-05-24 11:37:18', 27, 17);

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
(40, 1, 21, 5),
(47, 1, 21, 5);

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
(3, 'Vintage TV', 'Vintage TV from the 80s in color yellow. ', 'Yellow Submarine', '60a811388eedb.png', 299, 'Electronics', 'active', 25),
(4, 'Black Spoons', 'It your soup in style with a black spoon. Comes in a set of three spoons.', 'Tastum', '60a8d8f5d9f4c.jpg', 30, 'Household', 'active', 50),
(6, 'Sport Shoes', 'blahblah', 'Nike', '60a811a1137f9.jpg', 200, 'Sport', 'active', 0),
(7, 'Omega Sport Watch', 'Blah blah blah', 'Qicksilver', 'img3.jpg', 25, 'Fashion', 'active', 0),
(11, 'Silva Bicycle', 'This Mormac comes with reliable features that include mechanical Ultegra and reliably decelerating hydraulic disc brakes, durable DT P160 wheels and our popular power saddle.', 'Mormac', 'default-product.jpg', 349, 'Sport', 'deactive', 0),
(14, 'Toya Box', 'Best birthday present ever. This box can be used as playhouse, as hiding spot or anything you can imagine.', 'Sandbox', '60a83b535822a.jpg', 35, 'Toy', 'active', 15),
(15, 'Vintage Coffee Grinder', 'Vintage style coffee grinder with wooden box.', 'Brandy', '60a8121635fe3.png', 80, 'Household', 'active', 50),
(16, '70s Trainers', 'Yellow, red, black trainers with white sole.', 'Groovy', '60a84b87d0fb3.jpg', 69, 'Fashion', 'active', 15),
(17, 'Truly Green Shoes', 'These green shoes come with two bunches of flowers and green socks.', 'Twisty', '60a811dbd3e5a.jpg', 79, 'Fashion', 'active', 25),
(18, 'Rubik Cube', 'Hours of fun (or frustration).', 'Cube', '60a8d9064875b.jpg', 14, 'Toy', 'active', 10),
(19, 'Programmer Figure', 'This unique hand-made figure is a popular little gift.', 'Cube', '60a820ebc9655.jpg', 25, 'Toy', 'active', 0),
(20, 'Dinosaur Figure', 'Wooden dinosaur figure perfect for placing on top of books.', 'Cube', '60ac04995c433.jpg', 9, 'Toy', 'active', 10),
(21, 'Stand Mixer', 'Multi-functional stand mixer is pro designed for your kitchen need.', 'Kitchen', '60a9a4b9afd93.jpg', 199, 'Household', 'active', 20),
(22, 'DGU Headphones', 'White headphones with a neat case.', 'Boom', '60ac0488a34f3.jpg', 20.99, 'Electronics', 'active', 0),
(23, 'sdf', 'sdf', 'sdf', 'default-product.jpg', 34, 'sdf', 'deactive', 20),
(24, 'LSkdjf lk', 'asdf', 'sdf', '60ac08a075e58.jpg', 15, 'Sport', 'active', 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `pk_purchase_id` int(11) NOT NULL,
  `create_datetime` datetime NOT NULL,
  `fk_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`pk_purchase_id`, `create_datetime`, `fk_user_id`) VALUES
(1, '2021-05-21 09:53:21', 19),
(2, '2021-05-18 09:53:21', 19),
(3, '2022-05-21 06:34:53', 4),
(4, '2022-05-21 06:36:51', 4),
(5, '2022-05-21 06:38:07', 4),
(6, '2022-05-21 06:44:12', 4),
(7, '2022-05-21 06:45:23', 4),
(8, '2022-05-21 06:45:40', 4),
(9, '2022-05-21 06:50:54', 4),
(10, '2022-05-21 06:51:42', 4),
(11, '2022-05-21 06:56:10', 4),
(12, '2022-05-21 07:02:46', 4),
(13, '2022-05-21 07:04:19', 4),
(14, '2022-05-21 07:06:09', 4),
(15, '2022-05-21 07:09:04', 4),
(16, '2022-05-21 07:10:53', 4),
(17, '2022-05-21 07:16:15', 4),
(18, '2022-05-21 07:20:37', 4),
(19, '2022-05-21 07:24:00', 4),
(20, '2022-05-21 07:26:37', 4),
(21, '2022-05-21 07:33:31', 4),
(22, '2022-05-21 10:10:02', 4),
(23, '2022-05-21 10:13:42', 4),
(24, '2024-05-21 06:45:57', 19),
(25, '2024-05-21 07:20:50', 19),
(26, '2024-05-21 07:29:32', 19),
(27, '2024-05-21 07:30:50', 19),
(28, '2024-05-21 07:32:44', 19),
(29, '2024-05-21 07:34:13', 19),
(30, '2024-05-21 07:35:53', 19),
(31, '2024-05-21 07:36:23', 19),
(32, '2024-05-21 07:46:52', 19),
(33, '2024-05-21 07:48:46', 19),
(34, '2024-05-21 08:08:53', 19),
(35, '2024-05-21 08:11:20', 19),
(36, '2024-05-21 08:17:34', 19),
(37, '2024-05-21 08:31:24', 19),
(38, '2024-05-21 08:37:28', 19),
(39, '2024-05-21 08:42:30', 19),
(40, '2024-05-21 08:50:04', 19),
(41, '2024-05-21 02:32:13', 4),
(42, '2024-05-21 07:14:06', 4),
(43, '2024-05-21 10:31:15', 21);

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

--
-- Dumping data for table `purchase_item`
--

INSERT INTO `purchase_item` (`pk_purchase_item_id`, `quantity`, `fk_product_id`, `fk_purchase_id`) VALUES
(1, 2, 1, 1),
(2, 2, 7, 1),
(3, 1, 20, 15),
(4, 1, 20, 16),
(5, 1, 21, 18),
(6, 1, 19, 19),
(7, 1, 20, 20),
(8, 1, 19, 21),
(9, 1, 22, 22),
(10, 1, 22, 23),
(11, 2, 20, 24),
(12, 1, 16, 25),
(13, 1, 18, 26),
(14, 1, 19, 27),
(15, 1, 17, 29),
(16, 1, 16, 30),
(17, 1, 22, 37),
(18, 4, 22, 38),
(19, 1, 21, 39),
(20, 1, 14, 40),
(21, 2, 20, 41),
(22, 1, 19, 42),
(23, 1, 14, 43);

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
(27, 'Is it waterproof?', '2021-05-24 11:36:06', 20, 21),
(29, 'Does the mixing attachment rotate while it mixes?', '2021-05-24 11:42:57', 21, 17),
(31, 'Can the cube be also taken apart?', '2021-05-24 12:01:31', 18, 5),
(32, 'How big is this figure?', '2021-05-24 07:13:29', 19, 4);

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
(7, 5, 'Awesome', 'This is most beautiful thing I ever saw.', '2021-05-24 11:34:53', 20, 17),
(8, 4, 'Challenging', 'Kept me busy for hours. But never manage to solve it fully.', '2021-05-24 11:53:10', 18, 17),
(9, 3, 'is ok', 'could have more colors', '2021-05-24 12:00:45', 20, 5),
(10, 4, 'Works ok', 'Does the job, but could be better.', '2021-05-24 12:02:33', 21, 17),
(11, 5, 'great for making cake', 'Love this mixer! Has an 800 w motor so all the needed power. I had tried a more expensive one and was good but returned it when I saw I could buy for of these for the same price. ', '2021-05-24 12:04:09', 21, 21),
(12, 1, 'just a box', 'I was expecting some fun toy box. But all I got is just a simple cardboard box.', '2021-05-24 12:05:27', 14, 21),
(28, 4, 'Verry nice', 'Looks fine on my desk!', '2021-05-24 07:13:04', 19, 4);

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
(4, 'user@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Bernhardino', 'Sandhill', 'Fakestreet 4', 'Supercity', '12315', 'Fakecountry', '1990-11-11', 'active', 'user', '60ac018333d77.png', NULL),
(5, 'admin@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Normal', 'Admin', 'adminstreet 1', 'damincity', '1234', 'admincountry', '1990-11-11', 'active', 'admin', 'default-user.jpg', NULL),
(6, 'mail@mail.com', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855', 'ttzutzu', 'uzututt', 'u', 'u', 'u', 'u', '2021-04-27', 'active', 'user', '60aa476dbece5.png', NULL),
(17, 'dandy@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Dandy', 'Lion', 'Wiesenstr 4', 'New Ork', '1234', 'USE', '1990-02-05', 'active', 'user', '60a7839eece48.jpeg', NULL),
(19, 'sandy@trash-mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Sandy', 'Sandstone', 'Sandstreet 4', 'New Ork', '1235', 'USA', '1990-11-11', 'active', 'user', '60ab821e0eee2.jpeg', NULL),
(20, 'frech@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Naughty', 'Frech', 'BlaustraÃŸe', 'Graz', '8254', 'Austria', '2004-01-07', 'inactive', 'user', '60a90142852a7.jpg', NULL),
(21, 'jane@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Jane', 'Smith', 'Tartisgasse 16', 'Grabendorf', '4578', 'Austria', '1996-06-20', 'active', 'user', '60abd3b1721f5.jpeg', NULL),
(28, 'marly@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Marly', 'Kentwood', 'Cube Street', 'London', 'SE15 3UI', 'UK', '2000-12-20', 'active', 'user', '60ac02fc91c5d.png', NULL),
(29, 'ireen@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Ireen', 'Newland', 'Blumengasse', 'Wien', '1010', 'Austria', '1999-01-05', 'inactive', 'user', '60ac038eec52d.png', '2021-05-24 22:00:00'),
(31, 'heini@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Heinrich', 'Bauer', 'GrÃ¼nstr 4', 'New Dew', '1234', 'WAS', '2021-05-24', 'active', 'user', '60ac05c66aa0c.jpeg', NULL);

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
  MODIFY `pk_answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `pk_cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

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
  MODIFY `pk_product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `pk_purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `purchase_item`
--
ALTER TABLE `purchase_item`
  MODIFY `pk_purchase_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `pk_question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `pk_review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `pk_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
