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
`status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
`role` ENUM('user', 'admin') NOT NULL DEFAULT 'user',
`profile_image` VARCHAR(255) NULL,
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
`status` ENUM('active', 'deactive', 'deleted') NOT NULL DEFAULT 'active',
`discount_procent` INT NOT NULL DEFAULT 0,
PRIMARY KEY (`pk_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;


CREATE TABLE `cart_item` (
`pk_cart_item_id` INT(11) NOT NULL AUTO_INCREMENT,
`quantity` INT(11) NOT NULL DEFAULT 1,
`fk_product_id` INT(11) NOT NULL,
`fk_user_id` INT(11) NOT NULL,
PRIMARY KEY (`pk_cart_item_id`),
FOREIGN KEY (`fk_product_id`) REFERENCES product(`pk_product_id`),
FOREIGN KEY (`fk_user_id`) REFERENCES user(`pk_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

CREATE TABLE `purchase` (
`pk_purchase_id` INT(11) NOT NULL AUTO_INCREMENT,
`create_datetime` DATETIME NOT NULL,
`fk_user_id` INT(11) NOT NULL,
PRIMARY KEY (`pk_purchase_id`),
FOREIGN KEY (`fk_user_id`) REFERENCES user(`pk_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

CREATE TABLE `purchase_item` (
`pk_purchase_item_id` INT(11) NOT NULL AUTO_INCREMENT,
`quantity` INT(11) NOT NULL DEFAULT 1,
`sold` FLOAT NOT NULL,
`fk_product_id` INT(11) NOT NULL,
`fk_purchase_id` INT(11) NOT NULL,
PRIMARY KEY (`pk_purchase_item_id`),
FOREIGN KEY (`fk_product_id`) REFERENCES product(`pk_product_id`),
FOREIGN KEY (`fk_purchase_id`) REFERENCES purchase(`pk_purchase_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

CREATE TABLE `review` (
`pk_review_id` INT(11) NOT NULL AUTO_INCREMENT,  
`rating` INT(1) NOT NULL,
`title` VARCHAR(50) NOT NULL,
`comment` TEXT NOT NULL,
`create_datetime` DATETIME NOT NULL,
`fk_product_id` INT(11) NOT NULL,
`fk_user_id` INT(11) NOT NULL,
PRIMARY KEY (`pk_review_id`),
FOREIGN KEY (`fk_product_id`) REFERENCES product(`pk_product_id`),
FOREIGN KEY (`fk_user_id`) REFERENCES user(`pk_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

CREATE TABLE `question` (
`pk_question_id` INT(11) NOT NULL AUTO_INCREMENT,  
`question` TEXT NOT NULL,
`create_datetime` DATETIME NOT NULL,
`fk_product_id` INT(11) NOT NULL,
`fk_user_id` INT(11) NOT NULL,
PRIMARY KEY (`pk_question_id`),
FOREIGN KEY (`fk_product_id`) REFERENCES product(`pk_product_id`),
FOREIGN KEY (`fk_user_id`) REFERENCES user(`pk_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

CREATE TABLE `answer` (
`pk_answer_id` INT(11) NOT NULL AUTO_INCREMENT,  
`answer` TEXT NOT NULL,
`create_datetime` DATETIME NOT NULL,
`fk_question_id` INT(11) NOT NULL,
`fk_user_id` INT(11) NOT NULL,
PRIMARY KEY (`pk_answer_id`),
FOREIGN KEY (`fk_question_id`) REFERENCES question(`pk_question_id`),
FOREIGN KEY (`fk_user_id`) REFERENCES user(`pk_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;


CREATE TABLE `chat` (
`pk_chat_id` INT(11) NOT NULL AUTO_INCREMENT,  
`status` ENUM('open', 'closed') NOT NULL DEFAULT 'open',
`fk_user_id` INT(11) NOT NULL,
PRIMARY KEY (`pk_chat_id`),
FOREIGN KEY (`fk_user_id`) REFERENCES user(`pk_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;

CREATE TABLE `chat_message` (
`pk_chat_message_id` INT(11) NOT NULL AUTO_INCREMENT,
`message` TEXT NOT NULL,
`create_datetime` DATETIME NOT NULL,
`fk_user_id` INT(11) NOT NULL,
`fk_chat_id` INT(11) NOT NULL,
PRIMARY KEY (`pk_chat_message_id`),
FOREIGN KEY (`fk_user_id`) REFERENCES user(`pk_user_id`),
FOREIGN KEY (`fk_chat_id`) REFERENCES chat(`pk_chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;