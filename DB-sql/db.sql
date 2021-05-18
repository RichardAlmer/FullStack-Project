CREATE DATABASE `wf-backend-5-ecommerce`;

CREATE TABLE `user` (
`pk_user_id` INT(11) NOT NULL AUTO_INCREMENT,
`email` VARCHAR(100) NOT NULL UNIQUE,
`password` VARCHAR (255) NOT NULL,
`first_name` VARCHAR(100) NOT NULL,
`last_name` VARCHAR(100) NOT NULL,
`address` VARCHAR(255) NOT NULL,
`city` VARCHAR(120) NOT NULL,
`postcode` CHAR(12) NOT NULL,
`country` VARCHAR(50) NOT NULL,
`birthdate` DATE NOT NULL,
`status` ENUM('active', 'banned') NOT NULL DEFAULT 'active',
`role` ENUM('user', 'admin') NOT NULL DEFAULT 'user',
`profile_image` VARCHAR NULL (255),
`banned_until` DATETIME NULL,
PRIMARY KEY (`pk_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

CREATE TABLE `product` (
`pk_product_id` INT(11) NOT NULL AUTO_INCREMENT,
`name` VARCHAR(100) NOT NULL,
`description` TEXT NOT NULL,
`brand` VARCHAR(100) NOT NULL,
`image` VARCHAR (255) NOT NULL,
`price` FLOAT NOT NULL,
`category` VARCHAR (100) NOT NULL,
`status` ENUM('active', 'deactive') NOT NULL DEFAULT 'active',
`discount_procent` INT NOT NULL DEFAULT 100,
PRIMARY KEY (`pk_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;