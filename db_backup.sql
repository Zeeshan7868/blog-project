/*
SQLyog Ultimate v12.5.0 (64 bit)
MySQL - 10.4.27-MariaDB : Database - 21428_zeeshan_ali_mallah_notes_over_flow
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`21428_zeeshan_ali_mallah_notes_over_flow` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `21428_zeeshan_ali_mallah_notes_over_flow`;

/*Table structure for table `blog` */

DROP TABLE IF EXISTS `blog`;

CREATE TABLE `blog` (
  `blog_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `blog_title` varchar(200) DEFAULT NULL,
  `post_per_page` int(11) DEFAULT NULL,
  `blog_background_image` text DEFAULT NULL,
  `blog_status` enum('Active','InActive') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`blog_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `blog` */

insert  into `blog`(`blog_id`,`user_id`,`blog_title`,`post_per_page`,`blog_background_image`,`blog_status`,`created_at`,`updated_at`) values 
(16,1,'Space Science',8,'BlogImages/1748242931_arnold-francisca-f77Bh3inUpE-unsplash.jpg','Active','2025-05-26 10:32:38',NULL),
(17,1,'Zoology',10,'BlogImages/1748242931_arnold-francisca-f77Bh3inUpE-unsplash.jpg','Active','2025-05-26 10:33:01',NULL),
(18,1,'coding',10,'BlogImages/1748242931_arnold-francisca-f77Bh3inUpE-unsplash.jpg','Active','2025-05-26 12:02:11',NULL);

/*Table structure for table `category` */

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_title` varchar(100) DEFAULT NULL,
  `category_description` text DEFAULT NULL,
  `category_status` enum('Active','InActive') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `category` */

insert  into `category`(`category_id`,`category_title`,`category_description`,`category_status`,`created_at`,`updated_at`) values 
(13,'Science','unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo ','Active','2025-05-26 10:30:20',NULL),
(14,'History','unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo ','Active','2025-05-26 10:30:30',NULL),
(15,'Geography','unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo ','Active','2025-05-26 10:30:41',NULL),
(16,'Space Science','unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo ','Active','2025-05-26 10:31:20',NULL),
(17,'Artificial Inteligence','unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo ','Active','2025-05-26 10:32:13',NULL),
(18,'programming languages','Programming related posts are here','Active','2025-05-26 12:14:24',NULL);

/*Table structure for table `following_blog` */

DROP TABLE IF EXISTS `following_blog`;

CREATE TABLE `following_blog` (
  `follow_id` int(11) NOT NULL AUTO_INCREMENT,
  `follower_id` int(11) DEFAULT NULL,
  `blog_following_id` int(11) DEFAULT NULL,
  `status` enum('Followed','Unfollowed') DEFAULT 'Followed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`follow_id`),
  KEY `blog_following_id` (`blog_following_id`),
  KEY `follower_id` (`follower_id`),
  CONSTRAINT `following_blog_ibfk_1` FOREIGN KEY (`blog_following_id`) REFERENCES `blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `following_blog_ibfk_2` FOREIGN KEY (`follower_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `following_blog` */

/*Table structure for table `post` */

DROP TABLE IF EXISTS `post`;

CREATE TABLE `post` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) DEFAULT NULL,
  `post_title` varchar(200) NOT NULL,
  `post_summary` text NOT NULL,
  `post_description` longtext NOT NULL,
  `featured_image` text DEFAULT NULL,
  `post_status` enum('Active','InActive') DEFAULT NULL,
  `is_comment_allowed` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_id`),
  KEY `blog_id` (`blog_id`),
  CONSTRAINT `post_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`blog_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `post` */

insert  into `post`(`post_id`,`blog_id`,`post_title`,`post_summary`,`post_description`,`featured_image`,`post_status`,`is_comment_allowed`,`created_at`,`updated_at`) values 
(24,16,'PM Shehbaz in Turkiye to ‘elevate strategic partnership','Shehbaz meets Erdogan, expresses gratitude for Ankara’s support during tensions with India\r\n• Both sides reaffirm principled support for ‘core concerns’, including Kashmir dispute; call for Gaza ceasefire\r\n• Premier advocates investments in renewable energy, IT, defence production, agriculture','ISLAMABAD: In the first leg of his four-country tour, Prime Minister Shehbaz Sharif on Sunday arrived in Turkiye, where he met President Recep Tayyip Erdogan to not only thank Ankara for its support during recent tensions with India but also cement bilateral economic cooperation.\r\n\r\n“Both leaders reaffirmed their principled support for each other’s core concerns, including the Jammu and Kashmir dispute,” said an official press release issued by the Prime Minister’s Office on Sunday.\r\n\r\nIn his meeting with President Erdogan, the PM advocated joint ventures and enhanced bilateral investment, highlighting key sectors, including renewable energy, information technology, defence production, infrastructure development, and agriculture as potential areas of mutual interest.','post_images/1748242655_chris-ried-ieic5Tq8YMk-unsplash.jpg','Active',1,'2025-05-26 11:57:35','2025-05-26 12:01:35'),
(25,18,'This C++ tutorial is designed to provide a guide for easy and efficient learning ','This C++ tutorial is designed to provide a guide for easy and efficient learning of both core and advanced concepts of C++. Each concept is explained with simple illustrations and practical code examples that can be executed easily. This tutorial also provides quizzes in each section to test your understanding. Let\'s start!\r\nC++ Fundamentals\r\n\r\nThis section guides you through the basic concepts of C++ programming. It covers topics that teaches you to write your first program, manage data, perform different operations and control the flow of the program.','This C++ tutorial is designed to provide a guide for easy and efficient learning of both core and advanced concepts of C++. Each concept is explained with simple illustrations and practical code examples that can be executed easily. This tutorial also provides quizzes in each section to test your understanding. Let\'s start!\r\nC++ Fundamentals\r\n\r\nThis section guides you through the basic concepts of C++ programming. It covers topics that teaches you to write your first program, manage data, perform different operations and control the flow of the program.This C++ tutorial is designed to provide a guide for easy and efficient learning of both core and advanced concepts of C++. Each concept is explained with simple illustrations and practical code examples that can be executed easily. This tutorial also provides quizzes in each section to test your understanding. Let\'s start!\r\nC++ Fundamentals\r\n\r\nThis section guides you through the basic concepts of C++ programming. It covers topics that teaches you to write your first program, manage data, perform different operations and control the flow of the program.This C++ tutorial is designed to provide a guide for easy and efficient learning of both core and advanced concepts of C++. Each concept is explained with simple illustrations and practical code examples that can be executed easily. This tutorial also provides quizzes in each section to test your understanding. Let\'s start!\r\nC++ Fundamentals\r\n\r\nThis section guides you through the basic concepts of C++ programming. It covers topics that teaches you to write your first program, manage data, perform different operations and control the flow of the program.','post_images/1748243115_luca-bravo-XJXWbfSo2f0-unsplash.jpg','Active',1,'2025-05-26 12:05:15',NULL),
(26,18,'The mysqli_real_escape_string() function','The mysqli_real_escape_string() function is an inbuilt function in PHP which is used to escape all special characters for use in an SQL query. It is used before inserting a string in a database, as it removes any special characters that may interfere with the query operations. ','The mysqli_real_escape_string() function is an inbuilt function in PHP which is used to escape all special characters for use in an SQL query. It is used before inserting a string in a database, as it removes any special characters that may interfere with the query operations. The mysqli_real_escape_string() function is an inbuilt function in PHP which is used to escape all special characters for use in an SQL query. It is used before inserting a string in a database, as it removes any special characters that may interfere with the query operations. The mysqli_real_escape_string() function is an inbuilt function in PHP which is used to escape all special characters for use in an SQL query. It is used before inserting a string in a database, as it removes any special characters that may interfere with the query operations. The mysqli_real_escape_string() function is an inbuilt function in PHP which is used to escape all special characters for use in an SQL query. It is used before inserting a string in a database, as it removes any special characters that may interfere with the query operations. ','post_images/1748243172_buddy-photo-omxwwtNse3k-unsplash.jpg','Active',1,'2025-05-26 12:06:12',NULL),
(27,18,' PHP String Functions Complete Reference','Strings are a collection of characters. For example, \'G\' is the character and \'GeeksforGeeks\' is the string. \r\n\r\nInstallation: These functions are not required any installation. These are the part of PHP core. The complete list of PHP string functions are given below:','Strings are a collection of characters. For example, \'G\' is the character and \'GeeksforGeeks\' is the string. \r\n\r\nInstallation: These functions are not required any installation. These are the part of PHP core. The complete list of PHP string functions are given below:Strings are a collection of characters. For example, \'G\' is the character and \'GeeksforGeeks\' is the string. \r\n\r\nInstallation: These functions are not required any installation. These are the part of PHP core. The complete list of PHP string functions are given below:Strings are a collection of characters. For example, \'G\' is the character and \'GeeksforGeeks\' is the string. \r\n\r\nInstallation: These functions are not required any installation. These are the part of PHP core. The complete list of PHP string functions are given below:','post_images/1748243747_mohammad-rahmani-oXlXu2qukGE-unsplash.jpg','Active',1,'2025-05-26 12:15:47',NULL),
(29,18,'PHP Image Processing and GD Functions Complete Reference','Image processing and GD functions are used to create and manipulate image files in different image formats including GIF, PNG, JPEG, WBMP, and XPM. PHP can deliver the output image directly to the browser. The image processing and GD functions are used to compile the image functions for this work. It can also require other libraries, depending on the image formats. ','Image processing and GD functions are used to create and manipulate image files in different image formats including GIF, PNG, JPEG, WBMP, and XPM. PHP can deliver the output image directly to the browser. The image processing and GD functions are used to compile the image functions for this work. It can also require other libraries, depending on the image formats. Image processing and GD functions are used to create and manipulate image files in different image formats including GIF, PNG, JPEG, WBMP, and XPM. PHP can deliver the output image directly to the browser. The image processing and GD functions are used to compile the image functions for this work. It can also require other libraries, depending on the image formats. Image processing and GD functions are used to create and manipulate image files in different image formats including GIF, PNG, JPEG, WBMP, and XPM. PHP can deliver the output image directly to the browser. The image processing and GD functions are used to compile the image functions for this work. It can also require other libraries, depending on the image formats. ','post_images/1748244053_luca-bravo-XJXWbfSo2f0-unsplash.jpg','Active',1,'2025-05-26 12:20:53',NULL),
(30,18,'PHP Array Functions','Arrays are one of the fundamental data structures in PHP. They are widely used to store multiple values in a single variable and can store different types of data, such as strings, integers, and even other arrays. PHP offers a large set of built-in functions to perform various operations on arrays. These functions make array manipulation in PHP easier and more efficient.','Arrays are one of the fundamental data structures in PHP. They are widely used to store multiple values in a single variable and can store different types of data, such as strings, integers, and even other arrays. PHP offers a large set of built-in functions to perform various operations on arrays. These functions make array manipulation in PHP easier and more efficient.Arrays are one of the fundamental data structures in PHP. They are widely used to store multiple values in a single variable and can store different types of data, such as strings, integers, and even other arrays. PHP offers a large set of built-in functions to perform various operations on arrays. These functions make array manipulation in PHP easier and more efficient.Arrays are one of the fundamental data structures in PHP. They are widely used to store multiple values in a single variable and can store different types of data, such as strings, integers, and even other arrays. PHP offers a large set of built-in functions to perform various operations on arrays. These functions make array manipulation in PHP easier and more efficient.','post_images/1748244134_daniil-silantev--cY_x-urW6s-unsplash.jpg','Active',1,'2025-05-26 12:22:14',NULL),
(31,16,'PHP current() Function','The current() function is an inbuilt function in PHP.\r\n\r\n    It is used to return the value of the element in an array which the internal pointer is currently pointing to.\r\n    The current() function does not increment or decrement the internal pointer after returning the value.\r\n    In PHP, all arrays have an internal pointer. This internal pointer points to some element in that array which is called as the current element of the array.\r\n    Usually, the current element is the f','The current() function is an inbuilt function in PHP.\r\n\r\n    It is used to return the value of the element in an array which the internal pointer is currently pointing to.\r\n    The current() function does not increment or decrement the internal pointer after returning the value.\r\n    In PHP, all arrays have an internal pointer. This internal pointer points to some element in that array which is called as the current element of the array.\r\n    Usually, the current element is the fThe current() function is an inbuilt function in PHP.\r\n\r\n    It is used to return the value of the element in an array which the internal pointer is currently pointing to.\r\n    The current() function does not increment or decrement the internal pointer after returning the value.\r\n    In PHP, all arrays have an internal pointer. This internal pointer points to some element in that array which is called as the current element of the array.\r\n    Usually, the current element is the f','post_images/1748244297_daniil-silantev--cY_x-urW6s-unsplash.jpg','Active',1,'2025-05-26 12:24:57',NULL),
(32,16,'PHP Programs','PHP Programs is a collection of coding examples and practical exercises designed to help beginners and experienced developers. This collection covers a wide range of questions based on Array, Stirng, Date, Files, ..., etc. Each programming example includes multiple approaches to solve the problem','PHP Programs is a collection of coding examples and practical exercises designed to help beginners and experienced developers. This collection covers a wide range of questions based on Array, Stirng, Date, Files, ..., etc. Each programming example includes multiple approaches to solve the problemPHP Programs is a collection of coding examples and practical exercises designed to help beginners and experienced developers. This collection covers a wide range of questions based on Array, Stirng, Date, Files, ..., etc. Each programming example includes multiple approaches to solve the problemPHP Programs is a collection of coding examples and practical exercises designed to help beginners and experienced developers. This collection covers a wide range of questions based on Array, Stirng, Date, Files, ..., etc. Each programming example includes multiple approaches to solve the problem','post_images/1748244377_florian-olivo-4hbJ-eymZ1o-unsplash.jpg','Active',1,'2025-05-26 12:26:17',NULL),
(33,18,'PHP String Programs','Strings can be seen as a stream of characters.','internal pointer is currently pointing to. The current() function does not increment or decrement the internal pointer after returning the value. In PHP, all arrays have an internal pointer. This internal pointer points to some element in that array which is called as the current element of the array. Usually, the current element is the fThe current() function is an inbuilt function in PHP. It is used to return the value of the element in an array which the internal pointer is currently pointing to. The current() function does not increment or decrement the internal pointer after returning the value. In PHP, all arrays have an internal pointer. This internal pointer points to some element in that array which is called as the current element of the array. Usually, the current element is the finternal pointer is currently pointing to. The current() function does not increment or decrement the internal pointer after returning the value. In PHP, all arrays have an internal pointer. This internal pointer points to some element in that array which is called as the current element of the array. Usually, the current element is the fThe current() function is an inbuilt function in PHP. It is used to return the value of the element in an array which the internal pointer is currently pointing to. The current() function does not increment or decrement the internal pointer after returning the value. In PHP, all arrays have an internal pointer. This internal pointer points to some element in that array which is called as the current element of the array. Usually, the current element is the f','post_images/1748244469_mohammad-rahmani-oXlXu2qukGE-unsplash.jpg','Active',1,'2025-05-26 12:27:49',NULL);

/*Table structure for table `post_atachment` */

DROP TABLE IF EXISTS `post_atachment`;

CREATE TABLE `post_atachment` (
  `post_atachment_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `post_attachment_title` varchar(200) DEFAULT NULL,
  `post_attachment_path` text DEFAULT NULL,
  `is_active` enum('Active','InActive') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_atachment_id`),
  KEY `fk1` (`post_id`),
  CONSTRAINT `fk1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `post_atachment` */

insert  into `post_atachment`(`post_atachment_id`,`post_id`,`post_attachment_title`,`post_attachment_path`,`is_active`,`created_at`,`updated_at`) values 
(32,24,'attachment 1','post_attachments/1748242655_luca-bravo-XJXWbfSo2f0-unsplash.jpg','Active','2025-05-26 11:57:35',NULL),
(33,24,'attachment 2','post_attachments/1748242895_florian-olivo-4hbJ-eymZ1o-unsplash.jpg','Active','2025-05-26 12:01:35',NULL),
(34,27,'coding attachment','post_attachments/1748243747_1747893070_erd.xlsx','Active','2025-05-26 12:15:47',NULL),
(35,30,'attachment 3','post_attachments/1748244134_florian-olivo-4hbJ-eymZ1o-unsplash.jpg','Active','2025-05-26 12:22:14',NULL),
(36,31,'php attachment','post_attachments/1748244297_Online_Blogging_Application_ERD.bmp','Active','2025-05-26 12:24:57',NULL);

/*Table structure for table `post_category` */

DROP TABLE IF EXISTS `post_category`;

CREATE TABLE `post_category` (
  `post_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_category_id`),
  KEY `post_id` (`post_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `post_category_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `post_category` */

insert  into `post_category`(`post_category_id`,`post_id`,`category_id`,`created_at`,`updated_at`) values 
(40,24,15,'2025-05-26 12:01:35',NULL),
(41,24,17,'2025-05-26 12:01:35',NULL),
(42,25,13,'2025-05-26 12:05:15',NULL),
(43,25,17,'2025-05-26 12:05:15',NULL),
(44,26,13,'2025-05-26 12:06:12',NULL),
(45,26,17,'2025-05-26 12:06:12',NULL),
(46,27,17,'2025-05-26 12:15:47',NULL),
(47,27,18,'2025-05-26 12:15:47',NULL),
(48,29,18,'2025-05-26 12:20:53',NULL),
(49,30,15,'2025-05-26 12:22:14',NULL),
(50,30,17,'2025-05-26 12:22:14',NULL),
(51,31,17,'2025-05-26 12:24:57',NULL),
(52,31,18,'2025-05-26 12:24:57',NULL),
(53,32,18,'2025-05-26 12:26:17',NULL),
(54,33,18,'2025-05-26 12:27:49',NULL);

/*Table structure for table `post_comment` */

DROP TABLE IF EXISTS `post_comment`;

CREATE TABLE `post_comment` (
  `post_comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `is_active` enum('Active','InActive') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`post_comment_id`),
  KEY `user_id` (`user_id`),
  KEY `post_id` (`post_id`),
  CONSTRAINT `post_comment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_comment_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `post_comment` */

insert  into `post_comment`(`post_comment_id`,`post_id`,`user_id`,`comment`,`is_active`,`created_at`) values 
(33,26,1,'Hello','Active','2025-05-26 12:06:52');

/*Table structure for table `role` */

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_type` varchar(50) NOT NULL,
  `is_active` enum('Active','InActive') DEFAULT 'Active',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `role` */

insert  into `role`(`role_id`,`role_type`,`is_active`) values 
(1,'admin','Active'),
(2,'user','Active');

/*Table structure for table `setting` */

DROP TABLE IF EXISTS `setting`;

CREATE TABLE `setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `setting_key` varchar(100) DEFAULT NULL,
  `setting_value` varchar(100) DEFAULT NULL,
  `setting_status` enum('Active','InActive') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`setting_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `setting_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `setting` */

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` text NOT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `user_image` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_approved` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `is_active` enum('Active','InActive') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `user` */

insert  into `user`(`user_id`,`role_id`,`first_name`,`last_name`,`email`,`password`,`gender`,`date_of_birth`,`user_image`,`address`,`is_approved`,`is_active`,`created_at`,`updated_at`) values 
(1,1,'Zeeshan','Ali','74shanali@gmail.com','Zeeshan#786','Male','2025-05-28','MyFiles/default.png','Zulfiqar Colony, Muhammad Shah Muhallah, Tehsil and District Dadu','Approved','Active','2025-05-18 17:27:55','2025-05-25 16:38:51');

/*Table structure for table `user_feedback` */

DROP TABLE IF EXISTS `user_feedback`;

CREATE TABLE `user_feedback` (
  `feedback_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`feedback_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `user_feedback` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
