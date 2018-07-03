SET time_zone = "+00:00";

# Create DB
CREATE DATABASE IF NOT EXISTS `ducksell` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ducksell`;

/*!40101 SET NAMES utf8 */;

DROP TABLE IF EXISTS `analytics`;
CREATE TABLE `analytics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hour` int(2) NOT NULL,
  `day` int(2) NOT NULL,
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `site_id` int(11) DEFAULT NULL,
  `pageviews` int(11) DEFAULT NULL,
  `new_users` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index2` (`day`),
  KEY `index1` (`hour`),
  KEY `index3` (`month`),
  KEY `index4` (`year`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `downloads`;
CREATE TABLE `downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index2` (`user_id`),
  KEY `index3` (`ip_address`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_name_internal` varchar(255) NOT NULL,
  `description` text,
  `size` INT(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `message` text,
  `data` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`key`)
) DEFAULT CHARSET=utf8;

INSERT INTO `options` VALUES
('global.admin-mail','admin@example.com'),
('global.allow-certificate','0'),
('global.allow-download-pending','1'),
('global.allow-invoice','0'),
('global.base-url',''),
('global.company-details',''),
('global.company-name','My Company'),
('global.allow-direct-links','1'),
('global.default-manual-currency','USD'),
('global.invoice-footer','<p class=\"lead text-center\">Thank you for your business!</p>'),
('global.license-expiration','0'),
('global.min-password','6'),
('global.recaptcha-sitekey',''),
('global.recaptcha-secretkey',''),
('global.report-errors','1'),
('global.script-license',''),
('global.schema','3.1'),
('global.ssl-tracking','0'),
('global.tracking-started','0'),
('mail.driver',''),
('mail.encryption','ssl'),
('mail.from.address','noreply@example.com'),
('mail.from.name','Shop'),
('mail.host','smtp.example.com'),
('mail.password',''),
('mail.port','465'),
('mail.username','test@example.com'),
('mail.template.generic','Hi,\r\n\r\n{{ $content }}\r\n\r\nRegards,\r\nThe Team\r\n\r\n'),
('mail.template.thankyou','Hi,\r\n\r\nThank you for your purchase.\r\n\r\nYou can access your content here: {{ $direct_link }}\r\n\r\nAccount Details\r\n===============\r\nemail: {{ $user_email }}\r\npassword: {{ $user_password }}\r\n\r\nRegards,\r\nThe Team\r\n\r\n'),
('session.lifetime','131400');

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `plugins`;
CREATE TABLE `plugins` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `plugin_name` VARCHAR(255) NOT NULL,
  `key` VARCHAR(255) NOT NULL,
  `value` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `index2` (`plugin_name` ASC),
  INDEX `index3` (`key` ASC)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `product_metadata`;
CREATE TABLE `product_metadata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index2` (`product_id`)
) DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `product_transaction`;
CREATE TABLE `product_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `processor_amount` int(11) NOT NULL,
  `listed_amount` int(11) NOT NULL,
  `customer_amount` int(11) NOT NULL,
  `license_number` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `license_number_UNIQUE` (`license_number`),
  KEY `index2` (`product_id`),
  KEY `index3` (`transaction_id`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `external_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `external_id_UNIQUE` (`external_id`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` text COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`),
  KEY `last_activity_index` (`last_activity`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `sites`;
CREATE TABLE `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `transaction_metadata`;
CREATE TABLE `transaction_metadata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index2` (`transaction_id`)
) DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `transaction_updates`;
CREATE TABLE `transaction_updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `value` text NOT NULL,
  `raw_data` text NOT NULL,
  `updated_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index2` (`transaction_id`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_processor` varchar(45) NOT NULL,
  `external_sale_id` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `processor_currency` varchar(45) NOT NULL,
  `processor_amount` int(11) NOT NULL,
  `listed_currency` varchar(45) NOT NULL,
  `listed_amount` int(11) NOT NULL,
  `customer_currency` varchar(45) NOT NULL,
  `customer_amount` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash_UNIQUE` (`hash`),
  KEY `index2` (`status_id`),
  KEY `index3` (`external_sale_id`),
  KEY `index4` (`user_id`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user_metadata`;
CREATE TABLE `user_metadata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index2` (`user_id`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` int(11) NOT NULL DEFAULT 0,
  `password` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `index2` (`role`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE file_product (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `file_id` INT(11) NOT NULL,
  `product_id` INT(11) NULL,
  `weight` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index1` (`file_id` ASC, `product_id` ASC)
) DEFAULT CHARSET=utf8;

INSERT INTO `users` VALUES (1,'admin','admin@example.com',1,'$2y$10$qUZYtNbkuOt.u0LmZw1Xx.x5VaIhO7Zh3ByvkQ7plQ9DiDv36RzMO','','','2016-01-01 00:00:00','2016-01-01 00:00:00',NULL);
