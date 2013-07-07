-- phpMyAdmin SQL Dump
-- version 3.5.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 06, 2013 at 12:28 AM
-- Server version: 5.5.31-0ubuntu0.12.04.2-log
-- PHP Version: 5.4.17RC1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `metator`
--

-- --------------------------------------------------------

--
-- Table structure for table `attribute`
--

CREATE TABLE IF NOT EXISTS `attribute` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=222 ;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_option`
--

CREATE TABLE IF NOT EXISTS `attribute_option` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(5) NOT NULL,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attribute_id` (`attribute_id`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=275 ;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

-- --------------------------------------------------------

--
-- Table structure for table `category_structure`
--

CREATE TABLE IF NOT EXISTS `category_structure` (
  `category_id` int(5) NOT NULL,
  `parent_id` int(5) NOT NULL,
  PRIMARY KEY (`category_id`,`parent_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phinxlog`
--

CREATE TABLE IF NOT EXISTS `phinxlog` (
  `version` bigint(14) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `phinxlog`
--

INSERT INTO `phinxlog` (`version`, `start_time`, `end_time`) VALUES
(1, '2013-07-06 00:18:50', '2013-07-06 00:18:50'),
(20130705225216, '2013-07-06 00:18:50', '2013-07-06 00:18:50');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sku` varchar(15) NOT NULL,
  `name` varchar(25) NOT NULL,
  `base_price` float NOT NULL,
  `attributes` varchar(255) DEFAULT NULL,
  `active` int(1) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=135 ;

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute`
--

CREATE TABLE IF NOT EXISTS `product_attribute` (
  `product_id` int(8) NOT NULL,
  `attribute_id` int(8) NOT NULL,
  UNIQUE KEY `product_id` (`product_id`,`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_pricemodifiers`
--

CREATE TABLE IF NOT EXISTS `product_attribute_pricemodifiers` (
  `product_id` int(10) NOT NULL,
  `attribute_id` int(10) NOT NULL,
  `attribute_option_id` int(15) NOT NULL,
  `flat_fee` float DEFAULT NULL,
  `percentage` float DEFAULT NULL,
  UNIQUE KEY `unique` (`product_id`,`attribute_id`,`attribute_option_id`),
  KEY `attribute_id` (`attribute_id`),
  KEY `attribute_option_id` (`attribute_option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE IF NOT EXISTS `product_categories` (
  `product_id` int(8) NOT NULL,
  `category_id` int(8) NOT NULL,
  PRIMARY KEY (`product_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attribute_option`
--
ALTER TABLE `attribute_option`
  ADD CONSTRAINT `attribute_option_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `attribute` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `category_structure`
--
ALTER TABLE `category_structure`
  ADD CONSTRAINT `category_structure_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_structure_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_attribute`
--
ALTER TABLE `product_attribute`
  ADD CONSTRAINT `product_attribute_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_attribute_pricemodifiers`
--
ALTER TABLE `product_attribute_pricemodifiers`
  ADD CONSTRAINT `product_attribute_pricemodifiers_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_attribute_pricemodifiers_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `attribute` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_attribute_pricemodifiers_ibfk_3` FOREIGN KEY (`attribute_option_id`) REFERENCES `attribute_option` (`id`) ON DELETE CASCADE;

CREATE TABLE IF NOT EXISTS `product_images` (
  `product_id` int(15) NOT NULL,
  `image_hash` varchar(40) NOT NULL,
  `default` int(1) NOT NULL,
  PRIMARY KEY (`product_id`,`image_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `address` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `email` varchar(150) NOT NULL,
  `address` varchar(150) NOT NULL,
  `address2` varchar(150) NOT NULL,
  `city` varchar(25) NOT NULL,
  `state` varchar(15) NOT NULL,
  `postal` varchar(15) NOT NULL,
  `country` varchar(15) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `fax` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `shipping` int(10) DEFAULT NULL,
  `billing` int(10) DEFAULT NULL,
  `cart_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `cart_item` (
  `cart_id` int(15) NOT NULL,
  `item_id` int(15) NOT NULL,
  `price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`cart_id`,`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE  `order` ADD  `cart_id` INT( 10 ) NOT NULL;