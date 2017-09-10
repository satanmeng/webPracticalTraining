/*
Navicat MySQL Data Transfer

Source Server         : lly
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : webshixun

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-09-10 10:57:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `access`
-- ----------------------------
DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `role_id` smallint(6) unsigned NOT NULL COMMENT '角色ID',
  `node_id` smallint(6) unsigned NOT NULL COMMENT '节点ID',
  `level` tinyint(1) NOT NULL COMMENT '级别',
  `pid` smallint(6) NOT NULL COMMENT '父级ID',
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

-- ----------------------------
-- Records of access
-- ----------------------------

-- ----------------------------
-- Table structure for `account`
-- ----------------------------
DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) DEFAULT NULL COMMENT '真实姓名',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `openid` varchar(128) DEFAULT NULL COMMENT '微信用户标识',
  `icon` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `idNumber` varchar(20) DEFAULT NULL COMMENT '身份证号',
  `phone` varchar(20) DEFAULT NULL COMMENT '绑定手机号',
  `cardNo` varchar(20) DEFAULT NULL COMMENT '会员卡号',
  `balance` varchar(20) DEFAULT NULL COMMENT '账户余额',
  `barcode` varchar(255) DEFAULT NULL COMMENT '条形码链接',
  `ctime` varchar(20) DEFAULT NULL COMMENT '注册时间',
  `updateTime` int(11) DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '用户状态 1-正常 0-禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='会员信息表';

-- ----------------------------
-- Records of account
-- ----------------------------
INSERT INTO `account` VALUES ('3', null, null, '111111111111111111', null, null, '15210635996', '8350100474421', null, null, '2017-03-07 15:59:00', null, '1');
INSERT INTO `account` VALUES ('4', null, null, 'tNvHSWWQxRUcShYvYzEV', null, null, '13681378960', '99999322100081638', null, null, '2017-03-07 16:07:13', null, '1');
INSERT INTO `account` VALUES ('5', null, null, 'IYSOzwSeGNDacBnoPuEM', null, null, '13552276693', '99999691100079949', null, null, '2017-03-07 16:19:34', null, '1');
INSERT INTO `account` VALUES ('6', null, null, 'TdBuBdfaAXONeBmikkXR', null, null, '15910666707', '8350100495800', null, null, '2017-03-07 16:20:54', null, '1');

-- ----------------------------
-- Table structure for `demo`
-- ----------------------------
DROP TABLE IF EXISTS `demo`;
CREATE TABLE `demo` (
  `nkey` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `dtid` int(11) NOT NULL,
  `pic` varchar(255) NOT NULL,
  PRIMARY KEY (`nkey`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of demo
-- ----------------------------

-- ----------------------------
-- Table structure for `dict`
-- ----------------------------
DROP TABLE IF EXISTS `dict`;
CREATE TABLE `dict` (
  `nkey` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL COMMENT '编号',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `keyword` varchar(100) NOT NULL DEFAULT '' COMMENT '关键字',
  `memo` varchar(2000) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1-启用 0-禁用',
  PRIMARY KEY (`nkey`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='字典分类';

-- ----------------------------
-- Records of dict
-- ----------------------------
INSERT INTO `dict` VALUES ('24', '100', '学历', 'DEGREE', '备注', '1');
INSERT INTO `dict` VALUES ('25', '101', '类型', 'TYPE', '', '1');

-- ----------------------------
-- Table structure for `dict_detail`
-- ----------------------------
DROP TABLE IF EXISTS `dict_detail`;
CREATE TABLE `dict_detail` (
  `nkey` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL COMMENT '编号',
  `keyword` varchar(100) NOT NULL DEFAULT '' COMMENT '关联关键字',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `key` varchar(100) NOT NULL DEFAULT '' COMMENT '字典名',
  `value` tinyint(3) NOT NULL DEFAULT '0' COMMENT '字典值',
  `sort` tinyint(3) NOT NULL DEFAULT '0' COMMENT '排序',
  `memo` varchar(2000) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1-启用 0-禁用',
  PRIMARY KEY (`nkey`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 COMMENT='字典详细';

-- ----------------------------
-- Records of dict_detail
-- ----------------------------
INSERT INTO `dict_detail` VALUES ('61', '100', 'DEGREE', '小学', 'XX', '1', '1', '', '1');
INSERT INTO `dict_detail` VALUES ('62', '101', 'DEGREE', '初中', 'CZ', '2', '2', '', '1');

-- ----------------------------
-- Table structure for `group`
-- ----------------------------
DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL COMMENT '名字',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1-启用 0-禁用',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `isShow` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示 1-显示 0-隐藏',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='组管理表';

-- ----------------------------
-- Records of group
-- ----------------------------
INSERT INTO `group` VALUES ('2', 'App', '应用中心', '1222841259', '0', '1', '999', '1');
INSERT INTO `group` VALUES ('6', 'System', '系统管理', '1463460282', '0', '1', '1', '1');
INSERT INTO `group` VALUES ('7', 'Trains', '列车管理', '1503969676', '0', '1', '0', '1');

-- ----------------------------
-- Table structure for `id`
-- ----------------------------
DROP TABLE IF EXISTS `id`;
CREATE TABLE `id` (
  `nkey` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL COMMENT '标题',
  `id` int(11) DEFAULT NULL COMMENT '编号',
  PRIMARY KEY (`nkey`)
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=utf8 COMMENT='编号表';

-- ----------------------------
-- Records of id
-- ----------------------------
INSERT INTO `id` VALUES ('231', 'DICT_DETAIL_ID', '101');
INSERT INTO `id` VALUES ('232', 'DICT_ID', '101');
INSERT INTO `id` VALUES ('233', 'DEMO_ID', '115');
INSERT INTO `id` VALUES ('234', 'TRAINS_ID', '104');

-- ----------------------------
-- Table structure for `node`
-- ----------------------------
DROP TABLE IF EXISTS `node`;
CREATE TABLE `node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '节点名称',
  `title` varchar(50) DEFAULT NULL COMMENT '节点标题',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 1-启用 0-禁用',
  `isShow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示 1-显示 0-隐藏',
  `remark` varchar(255) DEFAULT NULL COMMENT '标注',
  `sort` smallint(6) unsigned DEFAULT NULL COMMENT '排序',
  `pid` smallint(6) unsigned NOT NULL COMMENT '父级节点ID',
  `level` tinyint(1) unsigned NOT NULL COMMENT '级别',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned DEFAULT '0' COMMENT '分组ID',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8 COMMENT='节点管理表';

-- ----------------------------
-- Records of node
-- ----------------------------
INSERT INTO `node` VALUES ('1', 'Admin', '后台管理', '1', '0', '', null, '0', '1', '0', '0');
INSERT INTO `node` VALUES ('2', 'Node', '节点管理', '1', '1', '', '2', '1', '2', '0', '2');
INSERT INTO `node` VALUES ('6', 'Role', '角色管理', '1', '1', '', '3', '1', '2', '0', '2');
INSERT INTO `node` VALUES ('7', 'User', '后台用户', '1', '1', '', '4', '1', '2', '0', '2');
INSERT INTO `node` VALUES ('30', 'Public', '公共模块', '1', '1', '', '2', '1', '2', '0', '0');
INSERT INTO `node` VALUES ('31', 'add', '新增', '1', '0', '', null, '30', '3', '0', '0');
INSERT INTO `node` VALUES ('32', 'insert', '写入', '1', '0', '', null, '30', '3', '0', '0');
INSERT INTO `node` VALUES ('33', 'edit', '编辑', '1', '0', '', null, '30', '3', '0', '0');
INSERT INTO `node` VALUES ('34', 'update', '更新', '1', '0', '', null, '30', '3', '0', '0');
INSERT INTO `node` VALUES ('35', 'foreverdelete', '删除', '1', '0', '', null, '30', '3', '0', '0');
INSERT INTO `node` VALUES ('36', 'forbid', '禁用', '1', '0', '', null, '30', '3', '0', '0');
INSERT INTO `node` VALUES ('37', 'resume', '恢复', '1', '0', '', null, '30', '3', '0', '0');
INSERT INTO `node` VALUES ('39', 'index', '列表', '1', '0', '', null, '30', '3', '0', '0');
INSERT INTO `node` VALUES ('40', 'Index', '默认模块', '1', '1', '', '1', '1', '2', '0', '0');
INSERT INTO `node` VALUES ('49', 'read', '查看', '1', '0', '', null, '30', '3', '0', '0');
INSERT INTO `node` VALUES ('50', 'main', '空白首页', '1', '0', '', null, '40', '3', '0', '0');
INSERT INTO `node` VALUES ('84', 'Group', '分组管理', '1', '1', '', '1', '1', '2', '0', '2');
INSERT INTO `node` VALUES ('100', 'Dict', '字典配置', '1', '1', '', '1', '1', '2', '0', '6');
INSERT INTO `node` VALUES ('101', 'setShow', '隐藏/显示', '1', '1', '', '0', '30', '3', '0', '0');
INSERT INTO `node` VALUES ('102', 'lookup', '查找带回', '1', '1', '', '0', '30', '3', '0', '0');
INSERT INTO `node` VALUES ('103', 'Dict_detail', '字典详情', '1', '0', '', '2', '1', '2', '0', '6');
INSERT INTO `node` VALUES ('126', 'Demo', '文件上传\\查找带回实例', '1', '1', '', '2', '1', '2', '0', '6');
INSERT INTO `node` VALUES ('127', 'Trains', '列车管理', '1', '1', '', '1', '1', '2', '0', '7');
INSERT INTO `node` VALUES ('128', 'Userinfo', '用户管理', '1', '1', '', '0', '1', '2', '0', '7');
INSERT INTO `node` VALUES ('129', 'Order', '订单管理', '1', '1', '', '0', '1', '2', '0', '7');

-- ----------------------------
-- Table structure for `order`
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `nkey` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  PRIMARY KEY (`nkey`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of order
-- ----------------------------
INSERT INTO `order` VALUES ('10', '2', '1', null);
INSERT INTO `order` VALUES ('15', '4', '1', null);

-- ----------------------------
-- Table structure for `role`
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '标题',
  `pid` smallint(6) DEFAULT '0' COMMENT '父级ID',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态 1-启用 0-禁用',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `ename` varchar(5) DEFAULT NULL COMMENT '昵称',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `ename` (`ename`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='角色管理表';

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', '平台管理员', '0', '1', '', null, '1208784792', '1488159298');
INSERT INTO `role` VALUES ('2', '营销管理中心', '0', '1', '', null, '1488159022', '0');

-- ----------------------------
-- Table structure for `role_user`
-- ----------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `role_id` smallint(6) unsigned DEFAULT NULL COMMENT '角色ID',
  `user_id` smallint(6) unsigned DEFAULT NULL COMMENT '用户ID',
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户角色关联表';

-- ----------------------------
-- Records of role_user
-- ----------------------------
INSERT INTO `role_user` VALUES ('1', '2');
INSERT INTO `role_user` VALUES ('2', '3');
INSERT INTO `role_user` VALUES ('2', '4');

-- ----------------------------
-- Table structure for `trains`
-- ----------------------------
DROP TABLE IF EXISTS `trains`;
CREATE TABLE `trains` (
  `nkey` int(11) NOT NULL AUTO_INCREMENT,
  `trains` varchar(50) DEFAULT NULL,
  `start` varchar(50) DEFAULT NULL,
  `end` varchar(50) DEFAULT NULL,
  `stime` varchar(30) DEFAULT NULL COMMENT '始发时间',
  `ticket` int(255) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `id` int(11) DEFAULT NULL,
  `inprice` float(11,2) DEFAULT NULL,
  PRIMARY KEY (`nkey`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of trains
-- ----------------------------
INSERT INTO `trains` VALUES ('1', 'D1', '长春', '杭州', '2017年08月29日 15:13', '3', '1', null, '15.00');
INSERT INTO `trains` VALUES ('2', 'Z111', '长春', '黑河', '2017年08月29日 15:12', '100', '1', '100', '152.00');
INSERT INTO `trains` VALUES ('3', 'D23', '长春', '哈尔滨', '2017年08月31日 08:43', '65', '1', '101', '53.00');
INSERT INTO `trains` VALUES ('4', 'K1531', '长春', '厦门', '2017年08月31日 08:43', '153', '1', '102', '453.00');
INSERT INTO `trains` VALUES ('5', 'G1623', '长春', '大连', '2017年08月31日 08:44', '75', '1', '103', '223.00');
INSERT INTO `trains` VALUES ('6', 'T85', '长春', '大连', '2017年08月31日 08:45', '85', '1', '104', '93.00');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(64) NOT NULL COMMENT '用户名',
  `nickname` varchar(50) NOT NULL COMMENT '昵称',
  `password` char(32) NOT NULL COMMENT '密码',
  `bind_account` varchar(50) DEFAULT NULL,
  `last_login_time` int(11) unsigned DEFAULT '0' COMMENT '最近登录时间',
  `last_login_ip` varchar(40) DEFAULT NULL COMMENT '最近登录IP',
  `login_count` mediumint(8) unsigned DEFAULT '0' COMMENT '登录次数',
  `verify` varchar(32) DEFAULT NULL COMMENT '验证码',
  `email` varchar(50) DEFAULT NULL COMMENT '用户邮箱',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL COMMENT '修改时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 1-启用 0-禁用',
  `info` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户管理表';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admin', '管理员', '21232f297a57a5a743894a0e4a801fc3', null, '1504744968', '0.0.0.0', '1250', '1', null, '备注', '1222907803', '1239977420', '1', '');
INSERT INTO `user` VALUES ('2', 'com7', '李林霞', '81dc9bdb52d04dc20036dbd8313ed055', null, '0', null, '0', null, '', '', '1488158877', '0', '1', '');
INSERT INTO `user` VALUES ('3', 'TS35', '李馨妍', '29c3eea3f305d6b823f562ac4be35217', null, '0', null, '0', null, '', '', '1488159219', '0', '1', '');
INSERT INTO `user` VALUES ('4', 'TS25', '张峥', '48bc3ac0905f6763fbe9058ddb4dd1b9', null, '0', null, '0', null, '', '', '1488159393', '0', '1', '');

-- ----------------------------
-- Table structure for `userinfo`
-- ----------------------------
DROP TABLE IF EXISTS `userinfo`;
CREATE TABLE `userinfo` (
  `nkey` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `detail` varchar(100) DEFAULT NULL,
  `resume` varchar(100) DEFAULT NULL,
  `realname` varchar(20) DEFAULT NULL,
  `pic` varchar(255) DEFAULT '',
  `tel` int(20) DEFAULT NULL,
  `oid` int(30) DEFAULT NULL COMMENT '身份证号',
  `id` int(11) DEFAULT NULL,
  PRIMARY KEY (`nkey`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of userinfo
-- ----------------------------
INSERT INTO `userinfo` VALUES ('1', 'satan', '1111', 'he', '周三', '小明', '__PUBLIC__/data/2017/08/31/59a7695b06261.jpg', '1111', '2222', null);
INSERT INTO `userinfo` VALUES ('2', 'admin', 'admin', '范德萨', '', '小红', null, '2147483647', '2147483647', null);
