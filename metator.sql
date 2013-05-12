SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `attribute` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=470 ;

CREATE TABLE IF NOT EXISTS `attribute_option` (
  `attribute_id` int(5) NOT NULL,
  `name` varchar(25) NOT NULL,
  `flat_fee` float DEFAULT NULL,
  `percentage` int(3) DEFAULT NULL,
  UNIQUE KEY `attribute_id` (`attribute_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `attributes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `product_attribute` (
  `product_id` int(8) NOT NULL,
  `attribute_id` int(8) NOT NULL,
  UNIQUE KEY `product_id` (`product_id`,`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `attribute_option`
  ADD CONSTRAINT `attribute_option_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `attribute` (`id`) ON DELETE CASCADE;

ALTER TABLE `product_attribute`
  ADD CONSTRAINT `product_attribute_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;
