<?php
require_once "maincore.php";
mysql_unbuffered_query("DROP DATABASE IF EXISTS `".$db_name."`;");

mysql_unbuffered_query("CREATE DATABASE IF NOT EXISTS `".$db_name."` DEFAULT CHARSET=utf8;");

mysql_unbuffered_query("CREATE TABLE IF NOT EXISTS `".$db_name."`.`admins` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`name` VARCHAR(32) NOT NULL,
`password` VARCHAR(32) NOT NULL,
`salt` CHAR(".$salt_len.") NOT NULL,
`right` CHAR(3) NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;");

$salt = char_gen($salt_len);
mysql_unbuffered_query("INSERT INTO `".$db_name."`.`admins`
(`id`, `name`, `password`, `salt`, `right`)
VALUES (NULL, '".mysql_real_escape_string($admin_name)."', '".md5_salt($admin_pass, $salt)."', '".$salt."', '777');");

mysql_unbuffered_query("CREATE TABLE `".$db_name."`.`sessions` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`session` CHAR(32) NOT NULL,
`user_id` INT(11) NOT NULL,
`user_ip` VARCHAR(15) NOT NULL,
`last_time` INT(10) NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;");

mysql_unbuffered_query("CREATE TABLE IF NOT EXISTS `".$db_name."`.`links` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`parent` INT(11) NOT NULL,
`child` INT(11) NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;");

mysql_unbuffered_query("CREATE TABLE IF NOT EXISTS `".$db_name."`.`objects` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`title` VARCHAR(50) NOT NULL,
`ip` VARCHAR(32) DEFAULT NULL,
`equip_type` CHAR(1) NOT NULL DEFAULT '1',
`connect_type` CHAR(1) NOT NULL DEFAULT '1',
`web` CHAR(1) NOT NULL DEFAULT '0',
`ping` CHAR(1) NOT NULL DEFAULT '0',
`login` VARCHAR(32) DEFAULT NULL,
`password` VARCHAR(32) DEFAULT NULL,
`desc` TEXT NULL,
`posX` MEDIUMINT(5) NOT NULL,
`posY` MEDIUMINT(5) NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;");
?>