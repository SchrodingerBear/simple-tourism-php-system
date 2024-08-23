/*
SQLyog Community v13.2.0 (64 bit)
MySQL - 10.4.28-MariaDB : Database - tourism_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`tourism_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `tourism_db`;

/*Table structure for table `book_list` */

DROP TABLE IF EXISTS `book_list`;

CREATE TABLE `book_list` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `user_id` int(30) NOT NULL,
  `package_id` int(30) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=pending,1=confirm, 2=cancelled\r\n',
  `schedule` date DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `book_list` */

insert  into `book_list`(`id`,`user_id`,`package_id`,`status`,`schedule`,`date_created`) values 
(7,7,8,0,'2024-12-05','2024-12-05 20:56:50'),
(9,7,8,0,'2024-12-06','2024-12-05 20:57:06');

/*Table structure for table `inquiry` */

DROP TABLE IF EXISTS `inquiry`;

CREATE TABLE `inquiry` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `name` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `subject` varchar(250) NOT NULL,
  `message` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `inquiry` */

/*Table structure for table `packages` */

DROP TABLE IF EXISTS `packages`;

CREATE TABLE `packages` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `title` text DEFAULT NULL,
  `tour_location` text DEFAULT NULL,
  `cost` double NOT NULL,
  `description` text DEFAULT NULL,
  `upload_path` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 =active ,2 = Inactive',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `booking_limit` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `packages` */

insert  into `packages`(`id`,`title`,`tour_location`,`cost`,`description`,`upload_path`,`status`,`date_created`,`booking_limit`) values 
(1,'Sample Tour 101','Sample Location1, Sample Location2, and Sample Location3',2500,'&lt;p style=&quot;margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam vel molestie ante. Morbi volutpat vestibulum nibh, vitae tempor metus sodales ac. Praesent et ornare lorem. Nullam id sem sed dolor feugiat finibus eu imperdiet erat. Suspendisse sed est eu enim lacinia efficitur eu eget sem. Curabitur feugiat, ipsum vel eleifend tincidunt, lacus metus tristique sem, non vehicula purus ipsum eget magna. Phasellus feugiat molestie nibh, sit amet elementum nulla volutpat sit amet. Integer a consequat metus, eget consectetur urna. Phasellus sagittis tincidunt egestas.&lt;/p&gt;&lt;p style=&quot;margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif;&quot;&gt;Curabitur non elit blandit, vestibulum sem in, maximus lectus. Nam laoreet nulla nec est pulvinar sagittis. Maecenas sit amet vestibulum ligula. Donec sit amet scelerisque risus, id aliquet lectus. Cras id sagittis lorem. Vestibulum aliquam feugiat semper. Nulla egestas est vitae est fringilla, non pretium urna malesuada. Nulla ultricies ipsum vel metus volutpat dictum a a mauris. Ut eu justo id ante efficitur semper. Suspendisse potenti. In luctus, libero non dignissim sollicitudin, quam magna rhoncus urna, eu tempor dolor dolor sit amet lacus. Mauris sed libero ut nisl ornare congue facilisis vitae velit. Praesent suscipit sem bibendum fermentum cursus. Morbi in justo imperdiet, tristique ante at, sagittis ante. Ut nec mauris vitae nisl sodales facilisis. Etiam pharetra nisi congue, interdum neque vel, porta magna.&lt;/p&gt;&lt;p style=&quot;margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif;&quot;&gt;Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut ac magna sodales, bibendum ante ut, accumsan ante. Integer non consectetur augue. Donec eget neque varius, venenatis elit id, dapibus tellus. Nam libero nulla, blandit sit amet ligula eu, malesuada lobortis diam. Maecenas ut tellus eget leo tincidunt pulvinar. Aenean rutrum, risus a aliquam euismod, nunc nisl tempus tortor, vel pellentesque eros ex nec purus. In condimentum nulla non ipsum interdum efficitur. Mauris eget sapien nec justo dignissim pretium quis sit amet ex. Aenean fermentum, metus eget dignissim condimentum, dui metus placerat diam, vitae interdum eros ante eget nisl. Fusce at orci gravida, varius mi ac, fermentum justo.&lt;/p&gt;','uploads/package_1',0,'2021-06-18 10:31:03',0),
(7,'Sample 102','Philippines',500,'&lt;p&gt;&lt;span style=&quot;color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; text-align: justify;&quot;&gt;Curabitur non elit blandit, vestibulum sem in, maximus lectus. Nam laoreet nulla nec est pulvinar sagittis. Maecenas sit amet vestibulum ligula. Donec sit amet scelerisque risus, id aliquet lectus. Cras id sagittis lorem. Vestibulum aliquam feugiat semper. Nulla egestas est vitae est fringilla, non pretium urna malesuada. Nulla ultricies ipsum vel metus volutpat dictum a a mauris. Ut eu justo id ante efficitur semper. Suspendisse potenti. In luctus, libero non dignissim sollicitudin, quam magna rhoncus urna, eu tempor dolor dolor sit amet lacus. Mauris sed libero ut nisl ornare congue facilisis vitae velit. Praesent suscipit sem bibendum fermentum cursus. Morbi in justo imperdiet, tristique ante at, sagittis ante. Ut nec mauris vitae nisl sodales facilisis. Etiam pharetra nisi congue, interdum neque vel, porta magna.&lt;/span&gt;&lt;br&gt;&lt;/p&gt;','uploads/package_7',0,'2021-06-18 11:17:11',0),
(8,'Sample 103','Sample Location',3000,'&lt;p&gt;&lt;span style=&quot;color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; text-align: justify;&quot;&gt;Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut ac magna sodales, bibendum ante ut, accumsan ante. Integer non consectetur augue. Donec eget neque varius, venenatis elit id, dapibus tellus. Nam libero nulla, blandit sit amet ligula eu, malesuada lobortis diam. Maecenas ut tellus eget leo tincidunt pulvinar. Aenean rutrum, risus a aliquam euismod, nunc nisl tempus tortor, vel pellentesque eros ex nec purus. In condimentum nulla non ipsum interdum efficitur. Mauris eget sapien nec justo dignissim pretium quis sit amet ex. Aenean fermentum, metus eget dignissim condimentum, dui metus placerat diam, vitae interdum eros ante eget nisl. Fusce at orci gravida, varius mi ac, fermentum justo.&lt;/span&gt;&lt;br&gt;&lt;/p&gt;','uploads/package_8',1,'2021-06-18 13:34:08',2),
(9,'Sample 104','Sample',5000,'&lt;p style=&quot;margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam vel molestie ante. Morbi volutpat vestibulum nibh, vitae tempor metus sodales ac. Praesent et ornare lorem. Nullam id sem sed dolor feugiat finibus eu imperdiet erat. Suspendisse sed est eu enim lacinia efficitur eu eget sem. Curabitur feugiat, ipsum vel eleifend tincidunt, lacus metus tristique sem, non vehicula purus ipsum eget magna. Phasellus feugiat molestie nibh, sit amet elementum nulla volutpat sit amet. Integer a consequat metus, eget consectetur urna. Phasellus sagittis tincidunt egestas.&lt;/p&gt;&lt;p style=&quot;margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif;&quot;&gt;Curabitur non elit blandit, vestibulum sem in, maximus lectus. Nam laoreet nulla nec est pulvinar sagittis. Maecenas sit amet vestibulum ligula. Donec sit amet scelerisque risus, id aliquet lectus. Cras id sagittis lorem. Vestibulum aliquam feugiat semper. Nulla egestas est vitae est fringilla, non pretium urna malesuada. Nulla ultricies ipsum vel metus volutpat dictum a a mauris. Ut eu justo id ante efficitur semper. Suspendisse potenti. In luctus, libero non dignissim sollicitudin, quam magna rhoncus urna, eu tempor dolor dolor sit amet lacus. Mauris sed libero ut nisl ornare congue facilisis vitae velit. Praesent suscipit sem bibendum fermentum cursus. Morbi in justo imperdiet, tristique ante at, sagittis ante. Ut nec mauris vitae nisl sodales facilisis. Etiam pharetra nisi congue, interdum neque vel, porta magna.&lt;/p&gt;&lt;p style=&quot;margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif;&quot;&gt;Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut ac magna sodales, bibendum ante ut, accumsan ante. Integer non consectetur augue. Donec eget neque varius, venenatis elit id, dapibus tellus. Nam libero nulla, blandit sit amet ligula eu, malesuada lobortis diam. Maecenas ut tellus eget leo tincidunt pulvinar. Aenean rutrum, risus a aliquam euismod, nunc nisl tempus tortor, vel pellentesque eros ex nec purus. In condimentum nulla non ipsum interdum efficitur. Mauris eget sapien nec justo dignissim pretium quis sit amet ex. Aenean fermentum, metus eget dignissim condimentum, dui metus placerat diam, vitae interdum eros ante eget nisl. Fusce at orci gravida, varius mi ac, fermentum justo.&lt;/p&gt;','uploads/package_9',1,'2024-08-23 23:28:42',0);

/*Table structure for table `rate_review` */

DROP TABLE IF EXISTS `rate_review`;

CREATE TABLE `rate_review` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `user_id` int(30) NOT NULL,
  `package_id` int(30) NOT NULL,
  `rate` int(11) NOT NULL,
  `review` text DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `rate_review` */

/*Table structure for table `system_info` */

DROP TABLE IF EXISTS `system_info`;

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `system_info` */

insert  into `system_info`(`id`,`meta_field`,`meta_value`) values 
(1,'name','Human Explore Travel and Tours'),
(6,'short_name','Human Explore Travel & Tours'),
(11,'logo','uploads/1724424480_Hand-Luggage-Only-10-5.jpg'),
(13,'user_avatar','uploads/user_avatar.jpg'),
(14,'cover','uploads/1733403300_The-Philippines-And-Its-Breathtaking-Attraction--You-Should-Visit-For-Your-Holiday.webp');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`firstname`,`lastname`,`username`,`password`,`avatar`,`last_login`,`type`,`date_added`,`date_updated`) values 
(1,' Admin','Admin','admin','4fcd7df641ce8f19d1e6be60de2113f6','uploads/1724424180_Hand-Luggage-Only-10-5.jpg',NULL,1,'2021-01-20 14:02:37','2024-08-23 22:43:48'),
(2,'Paw','Paw','user','4297f44b13955235245b2497399d7a93',NULL,NULL,0,'2024-08-03 11:28:10','2024-08-11 23:22:42'),
(7,'Pao','Lo','pao','c20ad4d76fe97759aa27a0c99bff6710',NULL,NULL,0,'2024-12-05 20:56:32',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
