SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `attribute` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `attribute_option` (
  `attribute_id` int(5) NOT NULL,
  `name` varchar(25) NOT NULL,
  `flat_fee` float NOT NULL,
  `percentage` int(3) NOT NULL,
  UNIQUE KEY `attribute_id` (`attribute_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `attribute_option`
  ADD CONSTRAINT `attribute_option_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `attribute` (`id`);