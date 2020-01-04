/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80017
 Source Host           : localhost:3306
 Source Schema         : sport

 Target Server Type    : MySQL
 Target Server Version : 80017
 File Encoding         : 65001

 Date: 04/01/2020 19:55:16
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for sp_new_data
-- ----------------------------
DROP TABLE IF EXISTS `sp_new_data`;
CREATE TABLE `sp_new_data` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `author` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '作者',
  `title` varchar(128) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'NULL' COMMENT '标题',
  `content` text COLLATE utf8mb4_general_ci COMMENT '内容',
  `cover` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '封面',
  `type` int(2) NOT NULL DEFAULT '1' COMMENT '类型 1-cba 2-nba 3-中国足球',
  `create_time` int(16) DEFAULT NULL COMMENT '创建时间',
  `cover1` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '封面2',
  `cover2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '封面3',
  `is_tj` int(1) NOT NULL DEFAULT '0' COMMENT '1-推荐。0-不推荐',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of sp_new_data
-- ----------------------------
BEGIN;
INSERT INTO `sp_new_data` VALUES (1, '1', '你好', '上帝哦好 i', NULL, 1, NULL, NULL, NULL, 0);
COMMIT;

-- ----------------------------
-- Table structure for sp_user
-- ----------------------------
DROP TABLE IF EXISTS `sp_user`;
CREATE TABLE `sp_user` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `login_user` varchar(16) COLLATE utf8mb4_general_ci NOT NULL COMMENT '登录账号',
  `login_pass` varchar(32) COLLATE utf8mb4_general_ci NOT NULL COMMENT '登录密码',
  `head` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT '头像',
  `last_login_time` int(16) DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '最后登录ip',
  `login_ip` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '登录ip',
  `integral` int(16) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `create_time` int(16) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`,`login_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SET FOREIGN_KEY_CHECKS = 1;
