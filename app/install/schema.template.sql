SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `{{DATABASE_NAME}}` COLLATE 'utf8mb4_czech_ci';

USE `{{DATABASE_NAME}}`;

CREATE TABLE `event` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`email_id` varchar(200) NOT NULL,
	`email_date` datetime NOT NULL,
	`email_subject` varchar(255) NOT NULL,
	`izscr_id` int(10) unsigned NOT NULL,
	`izscr_date` datetime DEFAULT NULL,
	`izscr_name` varchar(255) DEFAULT NULL,
	`title` varchar(255) NOT NULL,
	`city` varchar(255) DEFAULT NULL,
	`city_part` varchar(255) DEFAULT NULL,
	`street` varchar(255) DEFAULT NULL,
	`street_number` varchar(255) DEFAULT NULL,
	`region` varchar(255) DEFAULT NULL,
	`zip` varchar(255) DEFAULT NULL,
	`lat` float(10,7) DEFAULT NULL,
	`lng` float(10,7) DEFAULT NULL,
	`object` varchar(255) DEFAULT NULL,
	`clarification` varchar(255) DEFAULT NULL,
	`description` text DEFAULT NULL,
	`vehicles_local` text DEFAULT NULL,
	`vehicles_other` text DEFAULT NULL,
	`reporter_name` varchar(255) DEFAULT NULL,
	`reporter_phone` varchar(255) DEFAULT NULL,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `email_id` (`email_id`),
	KEY `created_at` (`created_at` DESC),
	KEY `email_date` (`email_date` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;
