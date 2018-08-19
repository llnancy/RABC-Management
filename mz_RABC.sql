/*
Navicat MySQL Data Transfer

Source Server         : 本机
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : mz_admin

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-08-12 23:02:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL COMMENT '用户名',
  `password` varchar(50) NOT NULL COMMENT '密码',
  `true_name` varchar(10) NOT NULL COMMENT '真实姓名',
  `avatar` varchar(64) NOT NULL DEFAULT '/static/admin/avatar/default.jpg' COMMENT '管理员头像',
  `add_time` int(10) NOT NULL COMMENT '添加时间,存入int型时间戳',
  `rid` int(10) NOT NULL COMMENT '所属角色ID',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：0：禁用，1：正常',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', 'admin', 'admin', '木子', '/static/admin/avatar/default.jpg', '324242432', '1', '1');

-- ----------------------------
-- Table structure for admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu` (
  `mid` int(10) NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `sort_id` int(10) NOT NULL DEFAULT '0' COMMENT '排序，越小越靠前',
  `parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '父级菜单ID，默认值：0：一级菜单',
  `menu_name` varchar(10) NOT NULL COMMENT '菜单名称',
  `route_name` varchar(15) NOT NULL COMMENT '路由名称',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否隐藏，隐藏表示使用Ajax，1：显示，0：隐藏',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，是否启用：1：启用，0：禁用',
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_menu
-- ----------------------------
INSERT INTO `admin_menu` VALUES ('1', '0', '0', '管理员列表', 'admin_list', '1', '1');
INSERT INTO `admin_menu` VALUES ('2', '0', '0', '管理员添加', 'admin_edit', '0', '1');
INSERT INTO `admin_menu` VALUES ('3', '0', '0', '管理员修改', 'admin_edit', '0', '1');
INSERT INTO `admin_menu` VALUES ('4', '0', '0', '管理员保存', 'admin_save', '0', '1');
INSERT INTO `admin_menu` VALUES ('5', '0', '0', '权限管理', '', '1', '1');
INSERT INTO `admin_menu` VALUES ('6', '0', '5', '菜单管理', 'menu_list', '1', '1');
INSERT INTO `admin_menu` VALUES ('7', '0', '6', '菜单添加', 'menu_edit', '0', '1');
INSERT INTO `admin_menu` VALUES ('8', '0', '6', '菜单修改', 'menu_edit', '0', '1');
INSERT INTO `admin_menu` VALUES ('9', '0', '6', '菜单保存', 'menu_save', '0', '1');
INSERT INTO `admin_menu` VALUES ('10', '0', '5', '角色管理', 'role_list', '1', '1');
INSERT INTO `admin_menu` VALUES ('11', '0', '10', '角色添加', 'role_edit', '0', '1');
INSERT INTO `admin_menu` VALUES ('12', '0', '10', '角色修改', 'role_edit', '0', '1');
INSERT INTO `admin_menu` VALUES ('13', '0', '10', '角色保存', 'role_save', '0', '1');

-- ----------------------------
-- Table structure for admin_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role` (
  `rid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '角色名称',
  `permissions` text NOT NULL COMMENT '拥有权限，json字符串格式',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '角色状态：1：正常，0：禁用',
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_role
-- ----------------------------
INSERT INTO `admin_role` VALUES ('1', '超级管理员', '[1,2,3,4,5,6,7,8,9,10,11,12,13]', '1');
INSERT INTO `admin_role` VALUES ('2', '系统开发人员', '[1,2]', '1');
INSERT INTO `admin_role` VALUES ('3', '文章发布者', '', '1');
INSERT INTO `admin_role` VALUES ('4', '测试人员', '[1,6,10]', '1');
INSERT INTO `admin_role` VALUES ('5', '测试', '[1,5]', '0');
