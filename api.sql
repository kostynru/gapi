/*
Navicat MySQL Data Transfer

Source Server         : api
Source Server Version : 50525
Source Host           : localhost:3306
Source Database       : api

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2012-12-10 13:45:04
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `api_info`
-- ----------------------------
CREATE TABLE `api_info` (
  `api_ver` varchar(250) NOT NULL,
  `api_creator` varchar(250) NOT NULL,
  `api_owner` varchar(250) NOT NULL,
  `copyrights` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

UPDATE api_info SET api_ver = 'Realise Version', api_creator = 'Shelko Kostya', api_owner = 'This server', copyrights = '2012';
-- ----------------------------
-- Table structure for `api_users`
-- ----------------------------
CREATE TABLE `api_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(250) NOT NULL,
  `pass` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  PRIMARY KEY (`id`,`login`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `apps`
-- ----------------------------
CREATE TABLE `apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `url` varchar(250) DEFAULT NULL,
  `owner_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `apps_access`
-- ----------------------------
CREATE TABLE `apps_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_` varchar(0) DEFAULT NULL,
  `private_key` varchar(250) NOT NULL,
  `app_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`private_key`,`app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `apps_response_type`
-- ----------------------------
CREATE TABLE `apps_response_type` (
  `id` int(11) NOT NULL,
  `response_type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`,`response_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `apps_sessions`
-- ----------------------------
CREATE TABLE `apps_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `datastamp` varchar(250) NOT NULL DEFAULT '',
  `app_token` varchar(250) NOT NULL,
  `response_type` int(1) NOT NULL,
  PRIMARY KEY (`id`,`app_id`,`app_token`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `s_procedures`
-- ----------------------------
CREATE TABLE `s_procedures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `method` varchar(25) NOT NULL,
  `token` varchar(25) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`,`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of s_procedures
-- ----------------------------