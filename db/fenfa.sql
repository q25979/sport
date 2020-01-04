/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80017
 Source Host           : localhost:3306
 Source Schema         : fenfa

 Target Server Type    : MySQL
 Target Server Version : 80017
 File Encoding         : 65001

 Date: 24/12/2019 22:13:06
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ff_download
-- ----------------------------
DROP TABLE IF EXISTS `ff_download`;
CREATE TABLE `ff_download` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(8) NOT NULL COMMENT 'uid',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '项目状态 0-关。1-开',
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '应用名' COMMENT '项目名称',
  `size` int(32) DEFAULT '500' COMMENT '应用大小 kb',
  `android_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '安卓应用链接',
  `ios_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'ios应用链接',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'logo',
  `version` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '1.0.1' COMMENT '版本信息',
  `download_page` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '下载页面',
  `total_download` int(16) DEFAULT '0' COMMENT '总下载量',
  `user_download` int(16) DEFAULT '0' COMMENT '剩余下载量',
  `domain` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '域名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of ff_download
-- ----------------------------
BEGIN;
INSERT INTO `ff_download` VALUES (1, 1, 0, '这个是一个测试', 500, '/uploads//20191224/d43bd7082a2fe967661998b37430d255.apk', '/uploads//20191224/618ef85b1fb0907d0dd2184427147e0f.ipa', 'http://193.8.82.65/img/logo.png', '1.0.1', 'http://www.baidu.com', 77, 0, NULL);
COMMIT;

-- ----------------------------
-- Table structure for ff_permissions
-- ----------------------------
DROP TABLE IF EXISTS `ff_permissions`;
CREATE TABLE `ff_permissions` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `rule_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT '权限控制id，多个按'',''隔开',
  `name` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '权限名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of ff_permissions
-- ----------------------------
BEGIN;
INSERT INTO `ff_permissions` VALUES (1, 'all', '超级管理员');
INSERT INTO `ff_permissions` VALUES (2, '0', '用户');
COMMIT;

-- ----------------------------
-- Table structure for ff_rule
-- ----------------------------
DROP TABLE IF EXISTS `ff_rule`;
CREATE TABLE `ff_rule` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(8) COLLATE utf8mb4_general_ci NOT NULL COMMENT '名称',
  `parent_id` int(8) DEFAULT NULL COMMENT '父id',
  `module` varchar(16) COLLATE utf8mb4_general_ci NOT NULL COMMENT '模块',
  `controller` varchar(16) COLLATE utf8mb4_general_ci NOT NULL COMMENT '控制器',
  `methods` varchar(32) COLLATE utf8mb4_general_ci NOT NULL COMMENT '方法',
  `icon` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '图标，layui图标',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of ff_rule
-- ----------------------------
BEGIN;
INSERT INTO `ff_rule` VALUES (1, '系统主页', NULL, 'admin', 'index', 'home', 'layui-icon-home');
INSERT INTO `ff_rule` VALUES (2, '用户管理', NULL, 'admin', 'user', 'index', 'layui-icon-username');
INSERT INTO `ff_rule` VALUES (3, '用户列表', 2, 'admin', 'user', 'list', '');
COMMIT;

-- ----------------------------
-- Table structure for ff_user
-- ----------------------------
DROP TABLE IF EXISTS `ff_user`;
CREATE TABLE `ff_user` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `login_user` varchar(16) COLLATE utf8mb4_general_ci NOT NULL COMMENT '登录账号',
  `login_pass` varchar(32) COLLATE utf8mb4_general_ci NOT NULL COMMENT '登录密码',
  `user_name` varchar(8) COLLATE utf8mb4_general_ci NOT NULL COMMENT '名称',
  `permissions_id` int(2) NOT NULL DEFAULT '2' COMMENT '权限id',
  `register_ip` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '注册ip',
  `last_login_time` int(16) DEFAULT NULL,
  `last_login_ip` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '最后登录ip',
  `create_time` int(16) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(16) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(16) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`,`login_user`),
  UNIQUE KEY `id` (`id`) USING BTREE,
  UNIQUE KEY `login_user` (`login_user`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of ff_user
-- ----------------------------
BEGIN;
INSERT INTO `ff_user` VALUES (1, 'q25979', 'dcb8df0555b220e1b62716de81df0ed9', '用户178', 2, NULL, NULL, '127.0.0.1', 1577157987, 1577173131, NULL);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
