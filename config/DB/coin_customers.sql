/*
Navicat MySQL Data Transfer

Source Server         : mysql
Source Server Version : 100131
Source Host           : localhost:3306
Source Database       : tippzi

Target Server Type    : MYSQL
Target Server Version : 100131
File Encoding         : 65001

Date: 2018-08-05 17:21:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for coin_customers
-- ----------------------------
DROP TABLE IF EXISTS `coin_customers`;
CREATE TABLE `coin_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `coin_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for coin_groups
-- ----------------------------
DROP TABLE IF EXISTS `coin_groups`;
CREATE TABLE `coin_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` int(255) DEFAULT NULL,
  `group_latitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_longitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_coins` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for coin_pos
-- ----------------------------
DROP TABLE IF EXISTS `coin_pos`;
CREATE TABLE `coin_pos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `latitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET FOREIGN_KEY_CHECKS=1;
