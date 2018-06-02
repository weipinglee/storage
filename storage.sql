/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : storage

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2018-06-02 09:08:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `st_admin`
-- ----------------------------
DROP TABLE IF EXISTS `st_admin`;
CREATE TABLE `st_admin` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `adminname` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `del` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1:删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of st_admin
-- ----------------------------
INSERT INTO `st_admin` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '0');
INSERT INTO `st_admin` VALUES ('2', 'ce', '123qweqweqwe', '0');
INSERT INTO `st_admin` VALUES ('8', '委任为', 'WERWERWER', '1');
INSERT INTO `st_admin` VALUES ('9', '而我认为让人', 'werwerwer', '1');
INSERT INTO `st_admin` VALUES ('10', 'werwerwer', 'werwerwer', '1');
INSERT INTO `st_admin` VALUES ('11', 'dfgdfgdfg', 'sdfsdfsdf', '1');
INSERT INTO `st_admin` VALUES ('12', 'admin123', '123456', '1');
INSERT INTO `st_admin` VALUES ('13', 'admin987', 'qwe123', '0');
INSERT INTO `st_admin` VALUES ('14', 'sdfsdf', 'sadfsdf', '0');
INSERT INTO `st_admin` VALUES ('15', 'adminwer', '123456', '0');
INSERT INTO `st_admin` VALUES ('16', 'admin123456', 'f5bb0c8de146c67b44babbf4e6584cc0', '1');
INSERT INTO `st_admin` VALUES ('17', 'admin567678', '32cca365ba670949757e8dc45df76b04', '0');
INSERT INTO `st_admin` VALUES ('18', 'admin豆腐干豆腐干', '123456', '1');
INSERT INTO `st_admin` VALUES ('19', 'adminxcc', '6b5a96826b776da888fcda0cac043794', '1');
INSERT INTO `st_admin` VALUES ('20', 'weipinglee', '3ae5e9658fbd7d4048bd40820b7d227d', '1');
INSERT INTO `st_admin` VALUES ('21', 'weilll', '86fd1d1bc65eae484ebafce47e698b48', '1');
INSERT INTO `st_admin` VALUES ('22', 'adminfdgfg', 'e10adc3949ba59abbe56e057f20f883e', '1');

-- ----------------------------
-- Table structure for `st_admin_role`
-- ----------------------------
DROP TABLE IF EXISTS `st_admin_role`;
CREATE TABLE `st_admin_role` (
  `admin_id` mediumint(9) NOT NULL COMMENT '管理员id',
  `role_id` mediumint(9) NOT NULL COMMENT '角色id',
  KEY `admin_id` (`admin_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of st_admin_role
-- ----------------------------

-- ----------------------------
-- Table structure for `st_config`
-- ----------------------------
DROP TABLE IF EXISTS `st_config`;
CREATE TABLE `st_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `overdue_days` int(11) NOT NULL DEFAULT '1' COMMENT '超期提醒天数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of st_config
-- ----------------------------
INSERT INTO `st_config` VALUES ('1', '30');

-- ----------------------------
-- Table structure for `st_loan`
-- ----------------------------
DROP TABLE IF EXISTS `st_loan`;
CREATE TABLE `st_loan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `begin_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `exp_income` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '预计收益',
  `loan_amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '借贷金额',
  `period` enum('年','月','日') NOT NULL,
  `rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `rec_person` varchar(20) NOT NULL DEFAULT '',
  `rec_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `exp_final_income` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '最终收益，预期收益减去推荐人获利',
  `del` tinyint(1) NOT NULL DEFAULT '0',
  `real_end_date` date DEFAULT NULL COMMENT '实际结束时间',
  `real_final_income` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '实际最终收益，实际收益减去推荐人获利',
  `real_income` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '实际收益',
  `manual_over_time` datetime DEFAULT NULL COMMENT '手动结束时间',
  `status` enum('已结束','已提交','已保存') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of st_loan
-- ----------------------------
INSERT INTO `st_loan` VALUES ('1', '1', null, null, '0.00', '0.00', '日', '0.00', '0', '0.00', '0.00', '0', null, '0.00', '0.00', null, '已结束');
INSERT INTO `st_loan` VALUES ('2', '2', '2018-06-01', '2018-06-28', '0.00', '10000.00', '日', '1.00', '1', '0.00', '0.00', '1', null, '0.00', '0.00', null, '已保存');
INSERT INTO `st_loan` VALUES ('3', '1', '2018-06-01', '2018-06-15', '0.00', '13333.00', '日', '1.00', '1', '0.00', '0.00', '0', null, '0.00', '0.00', null, '已提交');
INSERT INTO `st_loan` VALUES ('4', '2', '2018-06-01', '2018-05-30', '0.67', '1555.00', '月', '1.00', '二万人', '3.00', '0.65', '0', '2018-06-30', '1.46', '1.50', '2018-06-01 15:56:36', '已结束');
INSERT INTO `st_loan` VALUES ('5', '1', '2018-06-01', '2018-06-22', '326.65', '15555.00', '日', '1.00', '0', '0.00', '326.65', '0', '2018-06-29', '435.54', '435.54', '2018-06-01 11:50:37', '已结束');

-- ----------------------------
-- Table structure for `st_person`
-- ----------------------------
DROP TABLE IF EXISTS `st_person`;
CREATE TABLE `st_person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '姓名',
  `mobile` varchar(15) NOT NULL COMMENT '电话',
  `sex` enum('女','男') NOT NULL COMMENT '性别',
  `zu` varchar(100) NOT NULL DEFAULT '',
  `birth` varchar(12) NOT NULL DEFAULT '' COMMENT '生日',
  `work_years` varchar(20) NOT NULL DEFAULT '' COMMENT '工作年限',
  `shenfenzheng` varchar(25) NOT NULL DEFAULT '' COMMENT '身份证号',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT 'email',
  `is_marry` enum('已婚','离异','未婚') NOT NULL COMMENT '、',
  `mate_name` varchar(30) NOT NULL DEFAULT '' COMMENT '配偶姓名',
  `mate_phone` varchar(15) NOT NULL DEFAULT '' COMMENT '配偶电话',
  `emeg_cont_phone` varchar(15) NOT NULL DEFAULT '' COMMENT '紧急联系电话',
  `emeg_cont_name` varchar(30) NOT NULL DEFAULT '' COMMENT '紧急联系人',
  `edu` varchar(20) NOT NULL DEFAULT '' COMMENT '学历',
  `biye` varchar(100) NOT NULL DEFAULT '' COMMENT '毕业院校',
  `del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:删除',
  `place` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of st_person
-- ----------------------------
INSERT INTO `st_person` VALUES ('1', '电风扇', '15234343434', '男', '', '2018-05-28', '', '123456788876654443', '', '未婚', '', '', '', '', '', '', '0', '');
INSERT INTO `st_person` VALUES ('2', '电子计算机', '167878787878', '男', '', '2018-05-28', '', '', '', '未婚', '', '', '', '', '', '', '0', '');
INSERT INTO `st_person` VALUES ('3', '电动车', '15234343434', '男', '', '', '', '', '', '未婚', '', '', '', '', '', '', '0', '');
INSERT INTO `st_person` VALUES ('4', '手动阀手动阀', '15234343434', '女', '汉族', '2018-05-15', '4年', '156343233434344545', 'sdfdf@153.com', '未婚', 'kjkj ', '16788989898', '16334343434', '1erewr', '本科', '微软微软', '0', '撒旦发射点');
INSERT INTO `st_person` VALUES ('5', '手动阀手动阀', '15234343434', '男', '', '', '', '234356345', '', '未婚', '', '', '', '', '', '', '0', '');
INSERT INTO `st_person` VALUES ('6', '手动阀手动阀', '15234343434', '男', '', '', '', '', '', '未婚', '', '', '', '', '', '', '0', '');
INSERT INTO `st_person` VALUES ('7', '手动阀手动阀', '15234343434', '男', '', '', '', '', '', '已婚', '', '', '', '', '', '', '0', '');
INSERT INTO `st_person` VALUES ('8', '电脑', '15234343434', '男', '', '', '', '', '', '已婚', '', '', '', '', '', '', '0', '');
INSERT INTO `st_person` VALUES ('9', '手动阀手动阀', '15234343434', '男', '', '', '', '456456456', '', '未婚', '', '', '', '', '', '', '0', '');
INSERT INTO `st_person` VALUES ('10', '手动阀手动阀', '15234343434', '男', '', '2010-05-10', '', '', 'sdfdf@153.com', '未婚', 'dhfghfgh', '16788989898', '', '', '本科', '清华', '0', '');
INSERT INTO `st_person` VALUES ('11', '手动阀手动阀', '15234343434', '男', '', '2018-05-28', '', '', '', '已婚', '', '', '', '', '', '', '0', '');
INSERT INTO `st_person` VALUES ('12', '手动阀手动阀', '15234343434', '男', '', '', '', '', '', '未婚', '', '', '', '', '', '', '0', '');
INSERT INTO `st_person` VALUES ('13', '手动阀手动阀', '15234343434', '男', '', '', '', '', '', '未婚', '', '', '', '', '', '', '0', '');
INSERT INTO `st_person` VALUES ('14', '手动阀手动阀', '15234343434', '男', '', '', '', '', '', '未婚', '', '', '', '', '', '', '0', '');
INSERT INTO `st_person` VALUES ('15', '豆腐干豆腐干', '15234343434', '男', '', '', '', '', '', '未婚', '', '', '', '', '', '', '0', '');
INSERT INTO `st_person` VALUES ('16', 'yagnlili', '16334343434', '男', '蒙古', '2018-05-23', '', '', '', '未婚', '', '', '', '', '', '', '0', '内蒙古');

-- ----------------------------
-- Table structure for `st_person_bank`
-- ----------------------------
DROP TABLE IF EXISTS `st_person_bank`;
CREATE TABLE `st_person_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `bank_name` varchar(100) NOT NULL DEFAULT '',
  `bank_acc` varchar(30) NOT NULL DEFAULT '' COMMENT '银行账号',
  `del` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of st_person_bank
-- ----------------------------
INSERT INTO `st_person_bank` VALUES ('1', '6', '二哥', '324234234234', '0');
INSERT INTO `st_person_bank` VALUES ('2', '4', '建设银行', '32423423567567', '0');
INSERT INTO `st_person_bank` VALUES ('3', '9', '中国银行', '32423423425345345', '0');
INSERT INTO `st_person_bank` VALUES ('4', '9', '二哥', '324234234234', '0');
INSERT INTO `st_person_bank` VALUES ('5', '4', '中国银行sdf', '56674535434545456', '0');
INSERT INTO `st_person_bank` VALUES ('6', '4', '建设银行456456456', '67567676789990', '0');
INSERT INTO `st_person_bank` VALUES ('7', '10', 'nongye分支', '234456456464567567567', '0');
INSERT INTO `st_person_bank` VALUES ('8', '4', '中国银行', '36545657567', '0');

-- ----------------------------
-- Table structure for `st_privilege`
-- ----------------------------
DROP TABLE IF EXISTS `st_privilege`;
CREATE TABLE `st_privilege` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pri_name` varchar(30) NOT NULL COMMENT '权限名称',
  `module_name` varchar(30) NOT NULL DEFAULT '' COMMENT '权限名称',
  `controller` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器名称',
  `action_name` varchar(30) NOT NULL DEFAULT '' COMMENT '方法名称',
  `parent_id` int(8) unsigned DEFAULT '0' COMMENT '父类Id',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0不可用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8 COMMENT='权限';

-- ----------------------------
-- Records of st_privilege
-- ----------------------------
INSERT INTO `st_privilege` VALUES ('1', '权限管理', '', '', '', '0', '1');
INSERT INTO `st_privilege` VALUES ('2', '权限列表', 'Admin', 'Privilege', 'lst', '1', '0');
INSERT INTO `st_privilege` VALUES ('3', '添加权限', 'Privilege', 'Admin', 'add', '2', '1');
INSERT INTO `st_privilege` VALUES ('4', '修改权限', 'Admin', 'Privilege', 'edit', '2', '1');
INSERT INTO `st_privilege` VALUES ('5', '删除权限', 'Admin', 'Privilege', 'delete', '2', '1');
INSERT INTO `st_privilege` VALUES ('6', '角色列表', 'Admin', 'Role', 'lst', '1', '0');
INSERT INTO `st_privilege` VALUES ('7', '添加角色', 'Admin', 'Role', 'add', '6', '1');
INSERT INTO `st_privilege` VALUES ('8', '修改角色', 'Admin', 'Role', 'edit', '6', '1');
INSERT INTO `st_privilege` VALUES ('9', '删除角色', 'Admin', 'Role', 'delete', '6', '1');
INSERT INTO `st_privilege` VALUES ('10', '管理员列表', 'Admin', 'Admin', 'lst', '1', '1');
INSERT INTO `st_privilege` VALUES ('11', '添加管理员', 'Admin', 'Admin', 'add', '10', '1');
INSERT INTO `st_privilege` VALUES ('12', '修改管理员', 'Admin', 'Admin', 'edit', '10', '1');
INSERT INTO `st_privilege` VALUES ('13', '删除管理员', 'Admin', 'Admin', 'delete', '10', '1');
INSERT INTO `st_privilege` VALUES ('20', '人员管理', '', '', '', '0', '1');
INSERT INTO `st_privilege` VALUES ('114', '人员列表', 'Admin', 'Person', 'lst', '20', '1');
INSERT INTO `st_privilege` VALUES ('115', '人员添加', 'Admin', 'Person', 'add', '114', '1');
INSERT INTO `st_privilege` VALUES ('116', '人员删除', 'Admin', 'Person', 'delete', '114', '1');
INSERT INTO `st_privilege` VALUES ('117', '人员编辑', 'Admin', 'Person', 'edit', '114', '1');
INSERT INTO `st_privilege` VALUES ('118', '借贷列表', 'Admin', 'Loan', 'lst', '20', '1');
INSERT INTO `st_privilege` VALUES ('119', '借贷添加', 'Admin', 'Loan', 'add', '118', '1');
INSERT INTO `st_privilege` VALUES ('120', '借贷编辑', 'Admin', 'Loan', 'edit', '118', '1');
INSERT INTO `st_privilege` VALUES ('121', '入仓列表', 'Admin', 'Storage', 'lst', '20', '1');
INSERT INTO `st_privilege` VALUES ('122', '入仓添加', 'Admin', 'Storage', 'add', '121', '1');
INSERT INTO `st_privilege` VALUES ('123', '入仓编辑', 'Admin', 'Storage', 'edit', '121', '1');
INSERT INTO `st_privilege` VALUES ('124', '入仓删除', 'Admin', 'Storage', 'delete', '121', '1');
INSERT INTO `st_privilege` VALUES ('125', '借贷删除', 'Admin', 'Loan', 'delete', '118', '1');

-- ----------------------------
-- Table structure for `st_role`
-- ----------------------------
DROP TABLE IF EXISTS `st_role`;
CREATE TABLE `st_role` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `role_name` varchar(30) NOT NULL COMMENT '角色名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色';

-- ----------------------------
-- Records of st_role
-- ----------------------------

-- ----------------------------
-- Table structure for `st_role_pri`
-- ----------------------------
DROP TABLE IF EXISTS `st_role_pri`;
CREATE TABLE `st_role_pri` (
  `pri_id` mediumint(9) NOT NULL COMMENT '权限id',
  `role_id` mediumint(9) NOT NULL COMMENT '角色id',
  KEY `pri_id` (`pri_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of st_role_pri
-- ----------------------------

-- ----------------------------
-- Table structure for `st_storage`
-- ----------------------------
DROP TABLE IF EXISTS `st_storage`;
CREATE TABLE `st_storage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `begin_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `exp_income` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '预计收益',
  `loan_amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '借贷金额',
  `period` enum('年','月','日') NOT NULL,
  `rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `rec_person` varchar(20) NOT NULL DEFAULT '',
  `rec_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `exp_final_income` decimal(12,2) NOT NULL DEFAULT '0.00',
  `del` tinyint(1) NOT NULL DEFAULT '0',
  `real_end_date` date DEFAULT NULL COMMENT '实际结束时间',
  `real_final_income` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '实际最终收益',
  `real_income` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '实际收益',
  `manual_over_time` datetime DEFAULT NULL COMMENT '手动结束时间',
  `status` enum('已结束','已提交','已保存') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of st_storage
-- ----------------------------
INSERT INTO `st_storage` VALUES ('5', '1', '2018-06-01', '2018-05-26', '493.76', '11222.00', '日', '2.00', '', '0.00', '0.00', '0', '2019-07-26', '0.00', '9269.37', '2018-06-01 15:34:23', '已结束');

-- ----------------------------
-- Table structure for `st_test`
-- ----------------------------
DROP TABLE IF EXISTS `st_test`;
CREATE TABLE `st_test` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `in_out_code` text COMMENT '出入库单号',
  `test_code` text NOT NULL COMMENT '质检单号',
  `mer_id` mediumint(8) unsigned NOT NULL COMMENT '商户id',
  `pro_id` mediumint(8) unsigned NOT NULL COMMENT '产品id',
  `test_type` tinyint(2) unsigned NOT NULL COMMENT '质检类型：0：入库；1：出库',
  `add_time` varchar(30) NOT NULL COMMENT '质检时间',
  `request_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '质检申请状态：0：未处理；1：已处理；-1：用户取消',
  `test_staff` varchar(30) NOT NULL COMMENT '质检人员',
  `over_time` varchar(30) DEFAULT NULL COMMENT '修改时间',
  `is_test` tinyint(2) unsigned DEFAULT '0' COMMENT '是否质检：0，质检中；1，已质检',
  `level_id` tinyint(2) unsigned DEFAULT NULL COMMENT '质检结果，产品等级id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='质检';

-- ----------------------------
-- Records of st_test
-- ----------------------------
