SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Структура таблицы `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` char(3) NOT NULL,
  `right` char(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Структура таблицы `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL,
  `child` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Структура таблицы `objects`
--

CREATE TABLE IF NOT EXISTS `objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT '',
  `ip` varchar(15) DEFAULT '',
  `equip_type` char(1) NOT NULL DEFAULT '1',
  `connect_type` char(1) NOT NULL DEFAULT '1',
  `web` char(1) NOT NULL DEFAULT '0',
  `ping` char(1) NOT NULL DEFAULT '0',
  `login` varchar(32) DEFAULT '',
  `password` varchar(32) DEFAULT '',
  `desc` text NULL,
  `posX` mediumint(5) NOT NULL,
  `posY` mediumint(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Структура таблицы `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` char(32) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_ip` varchar(15) NOT NULL,
  `last_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;