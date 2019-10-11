/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50727
Source Host           : localhost:3306
Source Database       : test_task

Target Server Type    : MYSQL
Target Server Version : 50727
File Encoding         : 65001

Date: 2019-10-11 10:58:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for comment
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text,
  `authors_name` varchar(1024) DEFAULT NULL,
  `news_id` int(11) NOT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `IDX_8A9483A7BE036FC` (`news_id`),
  CONSTRAINT `FK_8A9483A7BE036FC` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for likes
-- ----------------------------
DROP TABLE IF EXISTS `likes`;
CREATE TABLE `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(1024) DEFAULT NULL,
  `news_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_8A9483A2B099F37` (`news_id`),
  KEY `FK_8A9483A77219825` (`comment_id`),
  CONSTRAINT `FK_8A9483A2B099F37` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`),
  CONSTRAINT `FK_8A9483A77219825` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `header` varchar(1024) DEFAULT NULL,
  `short_description` varchar(2048) DEFAULT NULL,
  `text` text,
  `img` varchar(1024) DEFAULT NULL,
  `tags` varchar(1024) DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
