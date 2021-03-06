<?php

use Phinx\Migration\AbstractMigration;

class Install extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute('SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `attribute` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=222 ;

CREATE TABLE IF NOT EXISTS `attribute_option` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(5) NOT NULL,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attribute_id` (`attribute_id`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=275 ;

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

CREATE TABLE IF NOT EXISTS `category_structure` (
  `category_id` int(5) NOT NULL,
  `parent_id` int(5) NOT NULL,
  PRIMARY KEY (`category_id`,`parent_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sku` varchar(15) NOT NULL,
  `name` varchar(25) NOT NULL,
  `attributes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=135 ;
ALTER TABLE  `product` ADD  `base_price` FLOAT NOT NULL AFTER  `name`;

CREATE TABLE IF NOT EXISTS `product_attribute` (
  `product_id` int(8) NOT NULL,
  `attribute_id` int(8) NOT NULL,
  UNIQUE KEY `product_id` (`product_id`,`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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


ALTER TABLE `attribute_option`
  ADD CONSTRAINT `attribute_option_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `attribute` (`id`) ON DELETE CASCADE;

ALTER TABLE `category_structure`
  ADD CONSTRAINT `category_structure_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_structure_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE;

ALTER TABLE `product_attribute`
  ADD CONSTRAINT `product_attribute_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

ALTER TABLE `product_attribute_pricemodifiers`
  ADD CONSTRAINT `product_attribute_pricemodifiers_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_attribute_pricemodifiers_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `attribute` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_attribute_pricemodifiers_ibfk_3` FOREIGN KEY (`attribute_option_id`) REFERENCES `attribute_option` (`id`) ON DELETE CASCADE;


CREATE TABLE IF NOT EXISTS `product_categories` (
  `product_id` int(8) NOT NULL,
  `category_id` int(8) NOT NULL,
  PRIMARY KEY (`product_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE  `product` ADD  `active` INT( 1 ) NOT NULL;');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        throw new Exception('cant reverse the install');
    }
}