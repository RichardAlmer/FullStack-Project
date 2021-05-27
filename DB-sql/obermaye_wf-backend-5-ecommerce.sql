-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 27, 2021 at 10:08 AM
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
CREATE DATABASE IF NOT EXISTS `obermaye_wf-backend-5-ecommerce` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `obermaye_wf-backend-5-ecommerce`;

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
(17, 'Not a problem to put it into water for a minute. But would not leave it a lot longer.', '2021-05-24 11:37:18', 27, 17),
(18, 'yes, lorem ipsum', '2021-05-25 12:04:32', 29, 5),
(20, '20 cm high', '2021-05-25 10:44:49', 32, 5),
(21, '20.1 to be precise ;)', '2021-05-26 11:43:51', 32, 1),
(22, '20.1 to be precise ;)', '2021-05-26 11:45:49', 32, 1),
(23, '20.1 to be precise ;)', '2021-05-26 11:46:04', 32, 1),
(24, '20.1 to be precise ;)', '2021-05-26 11:46:12', 32, 1),
(25, 'Yes, you can.', '2021-05-26 01:02:07', 37, 5);

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
(242, 1, 38, 4),
(243, 1, 38, 21),
(244, 1, 38, 21),
(279, 1, 38, 5),
(281, 1, 38, 5);

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
  `status` enum('active','deactive','deleted') NOT NULL DEFAULT 'active',
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
(6, 'Sport Shoes', 'blahblah', 'Nike', '60a811a1137f9.jpg', 210, 'Sport', 'active', 0),
(7, 'Omega Sport Watch', 'Blah blah blah', 'Qicksilver', 'img3.jpg', 25, 'Fashion', 'active', 0),
(11, 'Silva Bicycle', 'This Mormac comes with reliable features that include mechanical Ultegra and reliably decelerating hydraulic disc brakes, durable DT P160 wheels and our popular power saddle.', 'Mormac', 'default-product.jpg', 349, 'Sport', 'deactive', 0),
(14, 'Toya Box', 'Best birthday present ever. This box can be used as playhouse, as hiding spot or anything you can imagine.', 'Sandbox', '60a83b535822a.jpg', 35, 'Toy', 'active', 15),
(15, 'Vintage Coffee Grinder', 'Vintage style coffee grinder with wooden box.', 'Brandy', '60a8121635fe3.png', 80, 'Household', 'active', 50),
(16, '70s Trainers', 'Yellow, red, black trainers with white sole.', 'Groovy', '60a84b87d0fb3.jpg', 69, 'Fashion', 'active', 15),
(17, 'Truly Green Shoes', 'These green shoes come with two bunches of flowers and green socks.', 'Twisty', '60a811dbd3e5a.jpg', 79, 'Fashion', 'active', 25),
(18, 'Rubik Cube', 'Hours of fun (or frustration).', 'Cube', '60a8d9064875b.jpg', 14, 'Toy', 'active', 10),
(19, 'Programmer Figure', 'This unique hand-made figure is a popular little gift.', 'Cube', '60a820ebc9655.jpg', 25, 'Toy', 'active', 0),
(20, 'Dinosaur Figure', 'Wooden dinosaur figure perfect for placing on top of books.', 'Cube', '60ac04995c433.jpg', 9, 'Toy', 'active', 20),
(21, 'Stand Mixer', 'Multi-functional stand mixer is pro designed for your kitchen need.', 'Kitchen', '60a9a4b9afd93.jpg', 199, 'Household', 'active', 20),
(22, 'DGU Headphones', 'White headphones with a neat case.', 'Boom', '60ac0488a34f3.jpg', 20.99, 'Electronics', 'active', 0),
(31, 'XS4 Headphones', 'Black headphones super sound and look', 'AMG', 'default-product.jpg', 12, 'Electronics', 'deleted', 10),
(36, 'Chess', 'Chess is a recreational and competitive board game played between two players. It is sometimes called Western or international chess to distinguish it from related games such as xiangqi. The current form of the game emerged in Southern Europe during the second half of the 15th century after evolving from similar, much older games of Indian and Persian origin', 'Brainiac', '60ad5f145d2a2.jpg', 45, 'Toy', 'active', 10),
(37, 'Basketball', 'Basketball is a team sport in which two teams, most commonly of five players each, opposing one another on a rectangular court, compete with the primary objective of shooting a basketball.', 'Wilson', '60ad5ec7ef1ab.jpg', 120, 'Sport', 'active', 20),
(38, 'Laptop', 'A laptop, laptop computer, or notebook computer is a small, portable personal computer (PC) with a screen and alphanumeric keyboard. These typically have a form factor, typically having the screen mounted on the inside of the upper lid of the clamshell and the keyboard on the inside of the lower lid.', 'Lenovo', '60ad6029868c8.jpg', 1000, 'Computer', 'active', 15),
(39, 'Electric Guitar', 'Marshall Rocket Special Electric Guitar - Black', 'Marshall Rocket', '60ae16b1133a3.jpg', 392, 'Music', 'active', 10),
(40, 'Computer Chair', 'The same award-winning comfort. Now bigger. The TITAN XL retains the same unparalleled level of customization as the TITAN, and all the superb comfort, support, reliability that comes with the new 2020 Series.', 'Secretlab TITAN XL', '60ae32b0ac0db.jpg', 520, 'Computer', 'active', 10);

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
(92, '2026-05-21 02:04:16', 19),
(93, '2026-05-21 02:47:02', 19),
(94, '2026-05-21 03:14:56', 19),
(95, '2026-05-21 06:40:22', 21),
(96, '2026-05-21 10:11:13', 19),
(97, '2026-05-21 10:17:38', 19),
(98, '2026-05-21 10:18:35', 19),
(99, '2026-05-21 10:27:37', 19),
(100, '2027-05-21 12:05:35', 17),
(101, '2027-05-21 12:07:33', 33),
(102, '2027-05-21 12:09:50', 19),
(103, '2027-05-21 12:12:37', 28),
(104, '2027-05-21 12:13:27', 28),
(105, '2027-05-21 12:15:04', 34),
(106, '2027-05-21 12:15:30', 34),
(107, '2027-05-21 10:08:14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_item`
--

CREATE TABLE `purchase_item` (
  `pk_purchase_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `fk_product_id` int(11) NOT NULL,
  `fk_purchase_id` int(11) NOT NULL,
  `sold` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purchase_item`
--

INSERT INTO `purchase_item` (`pk_purchase_item_id`, `quantity`, `fk_product_id`, `fk_purchase_id`, `sold`) VALUES
(74, 1, 38, 92, 850),
(75, 1, 37, 92, 96),
(76, 1, 40, 93, 468),
(77, 2, 39, 93, 705.6),
(78, 1, 39, 94, 352.8),
(79, 1, 40, 94, 468),
(80, 1, 37, 94, 96),
(81, 1, 17, 95, 59.25),
(82, 1, 20, 95, 7.2),
(83, 2, 19, 95, 50),
(84, 1, 18, 95, 12.6),
(85, 1, 14, 95, 29.75),
(86, 1, 37, 96, 96),
(87, 1, 39, 96, 352.8),
(88, 1, 7, 96, 25),
(89, 1, 40, 97, 468),
(90, 1, 39, 98, 352.8),
(91, 2, 14, 99, 59.5),
(92, 1, 22, 100, 20.99),
(93, 2, 19, 100, 50),
(94, 1, 17, 100, 59.25),
(95, 1, 3, 100, 224.25),
(96, 1, 19, 101, 25),
(97, 3, 17, 101, 177.75),
(98, 1, 1, 102, 320),
(99, 1, 2, 102, 54.4),
(100, 1, 3, 102, 224.25),
(101, 1, 4, 102, 15),
(102, 1, 6, 102, 210),
(103, 2, 14, 102, 59.5),
(104, 1, 15, 102, 40),
(105, 1, 17, 102, 59.25),
(106, 1, 4, 103, 15),
(107, 2, 14, 103, 59.5),
(108, 1, 39, 104, 352.8),
(109, 1, 40, 104, 468),
(110, 3, 3, 104, 672.75),
(111, 1, 15, 105, 40),
(112, 1, 39, 105, 352.8),
(113, 1, 37, 105, 96),
(114, 6, 20, 105, 43.2),
(115, 1, 20, 106, 7.2),
(116, 3, 17, 107, 177.75);

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
(32, 'How big is this figure?', '2021-05-24 07:13:29', 19, 4),
(34, 'Are those headphones Bluetooths Headphones?', '2021-05-25 08:01:45', 22, 35),
(35, 'Do you have any other colors of this headphones?', '2021-05-25 08:02:39', 22, 35),
(36, 'How many Vintage Coffee Grinder do you have?', '2021-05-25 08:17:09', 15, 35),
(37, 'Can i upgrade the RAM on this Notebook?', '2021-05-25 10:58:06', 38, 41),
(38, 'does it come in other colors?', '2021-05-26 02:50:13', 39, 19);

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
(28, 4, 'Verry nice', 'Looks fine on my desk!', '2021-05-24 07:13:04', 19, 4),
(30, 5, 'Super', 'Super sneakers', '2021-05-25 07:37:25', 16, 4),
(31, 3, 'Super Headphones', 'SUUUUUUUUPPPPPPPPEEEEEEEEERRRRRRRRRRRRR', '2021-05-25 08:03:17', 22, 35),
(32, 5, 'super', 'super super', '2021-05-25 08:18:37', 15, 35),
(35, 5, 'Super fast notebook', 'Very good display ', '2021-05-25 10:57:35', 38, 41),
(36, 5, 'Great Chair', 'An amazing  chair, providing sublime fabric comfort... and excellent back support', '2021-05-26 01:25:14', 40, 41),
(37, 5, 'Amazin Guitar', 'Amazin Guitar, i have played this Guitar together with Hillary Clinton and the sound is great', '2021-05-26 01:31:43', 39, 42);

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
  `role` enum('user','admin','superadmin') NOT NULL DEFAULT 'user',
  `profile_image` varchar(255) DEFAULT NULL,
  `banned_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`pk_user_id`, `email`, `password`, `first_name`, `last_name`, `address`, `city`, `postcode`, `country`, `birthdate`, `status`, `role`, `profile_image`, `banned_until`) VALUES
(1, 'sadmin@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Super', 'Admin', 'Superstreet 1', 'Supercity', '1111A', 'Supercountry', '2021-05-25', 'active', 'superadmin', 'default-user.jpg', NULL),
(4, 'user@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Bernhardino', 'Sandhill', 'Fakestreet 2', 'Supercity', '12315', 'Fakecountry', '1990-11-11', 'active', 'user', '60ac018333d77.png', NULL),
(5, 'admin@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Normal', 'Admin', 'adminstreet1', 'damincity', '1234', 'admincountry', '1990-11-11', 'active', 'admin', '60ad57f4d16d4.jpg', NULL),
(6, 'mail@mail.com', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855', 'Norma', 'Everyhill', 'Nowhere Street', 'Everywhere', '0123', 'Normaland', '2021-04-27', 'active', 'user', '60aa476dbece5.png', NULL),
(17, 'dandy@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Dandy', 'Lion', 'Wiesenstr 4', 'New Ork', '1234', 'USE', '1990-02-05', 'active', 'user', '60a7839eece48.jpeg', NULL),
(19, 'sandy@trash-mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Sandy', 'Sandstone', 'Sandstreet 4', 'New Ork', '1235', 'USA', '1990-11-11', 'active', 'user', '60ae491b2371f.jpeg', NULL),
(20, 'frech@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Naughty', 'Frech', 'BlaustraÃŸe', 'Graz', '8254', 'Austria', '2004-01-07', 'inactive', 'user', '60a90142852a7.jpg', NULL),
(21, 'jane@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Jane', 'Smith', 'Tartisgasse 16', 'Grabendorf', '4578', 'Austria', '1996-06-20', 'active', 'user', '60ad5e19a08c5.png', NULL),
(28, 'marly@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Marly', 'Kentwood', 'Cube Street', 'London', 'SE15 3UI', 'UK', '2000-12-20', 'active', 'user', '60ac02fc91c5d.png', NULL),
(29, 'ireen@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Ireen', 'Newland', 'Blumengasse', 'Wien', '1010', 'Austria', '1999-01-05', 'inactive', 'user', '60ac038eec52d.png', '2021-05-24 22:00:00'),
(31, 'heini@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Heinrich', 'Bauer', 'GrÃ¼nstr 4', 'New Dew', '1234', 'WAS', '2021-05-24', 'inactive', 'user', '60ac05c66aa0c.jpeg', NULL),
(33, 'norbert@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Norbert', 'Miller', 'Normangasse 45', 'Berlin', '465798', 'Germany', '1992-06-17', 'active', 'user', '60ad2990c907d.png', NULL),
(34, 'nue@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Nue', 'Borleen', 'Minno Lane', 'Cardiff', 'CF36 7UG', 'UK', '1997-06-09', 'active', 'user', '60ad3e7e3974d.jpg', NULL),
(35, 'richie@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Richie', 'Rich', 'Richies House', 'Richies City', '1111', 'USA', '1992-01-01', 'active', 'user', '60ad367a33ac3.jpg', NULL),
(36, 'bruci@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Bruce', 'Wayne', 'Wayne Manor 1', 'Gotham City', '1011', 'USA', '2021-05-05', 'active', 'user', '60ad3aa8a3eb8.webp', NULL),
(41, 'johny@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Johnny', 'Knoxville', 'Johny Street', 'Johny City', 'Johny post', 'USA', '1971-03-11', 'active', 'user', '60ae0d403486f.webp', NULL),
(42, 'obama@mail.com', '96cae35ce8a9b0244178bf28e4966c2ce1b8385723a96a6b838858cdd6ca0a1e', 'Barack', 'Obama', 'Obama Street', 'Obama City', 'Obama ZIP', 'USA', '1961-08-04', 'active', 'user', '60ae30cc69f5c.jpg', NULL);

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
  MODIFY `pk_answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `pk_cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=285;

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
  MODIFY `pk_product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `pk_purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `purchase_item`
--
ALTER TABLE `purchase_item`
  MODIFY `pk_purchase_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `pk_question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `pk_review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `pk_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

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
