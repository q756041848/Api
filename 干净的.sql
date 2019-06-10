/*
Navicat MySQL Data Transfer

Source Server         : 47.104.173.59
Source Server Version : 50626
Source Host           : 47.104.173.59:3306
Source Database       : zhuyou56

Target Server Type    : MYSQL
Target Server Version : 50626
File Encoding         : 65001

Date: 2018-11-23 13:22:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for zydz_admin
-- ----------------------------
DROP TABLE IF EXISTS `zydz_admin`;
CREATE TABLE `zydz_admin` (
  `a_id` int(4) NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `a_username` varchar(20) NOT NULL COMMENT '管理员用户名',
  `a_password` varchar(70) NOT NULL COMMENT '管理员登录密码',
  `a_gid` mediumint(8) DEFAULT NULL COMMENT '用户角色表外键',
  `a_email` varchar(30) NOT NULL COMMENT '邮箱',
  `a_realname` varchar(10) DEFAULT NULL COMMENT '真实姓名',
  `a_tel` varchar(30) DEFAULT NULL COMMENT '电话号码',
  `a_hits` int(8) NOT NULL DEFAULT '1' COMMENT '登陆次数',
  `a_ip` varchar(20) DEFAULT NULL COMMENT 'IP地址',
  `a_addtime` int(11) NOT NULL COMMENT '添加时间',
  `a_mdemail` varchar(50) NOT NULL DEFAULT '0' COMMENT '传递修改密码参数加密',
  `a_is_open` tinyint(2) DEFAULT '0' COMMENT '审核状态',
  PRIMARY KEY (`a_id`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of zydz_admin
-- ----------------------------
INSERT INTO `zydz_admin` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1', 'youtu@163.com', '超级管理员', '15588166221', '233', '127.0.0.1', '0', '0', '1');
INSERT INTO `zydz_admin` VALUES ('70', 'ccc', '827ccb0eea8a706c4c34a16891f84e7b', '1', '123@qq.com1', '测试', '15806904156', '2', '127.0.0.1', '1542865202', '0', '1');

-- ----------------------------
-- Table structure for zydz_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `zydz_auth_group`;
CREATE TABLE `zydz_auth_group` (
  `g_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `g_title` char(100) NOT NULL DEFAULT '' COMMENT '用户组名',
  `g_status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `g_rules` longtext COMMENT '规则(权限)',
  `g_addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`g_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户角色表';

-- ----------------------------
-- Records of zydz_auth_group
-- ----------------------------
INSERT INTO `zydz_auth_group` VALUES ('1', '超级管理员', '1', '318,319,1,2,6,105,15,16,119,144,120,146,17,149,115,116,150,117,118,151,148,147,181,18,108,152,114,113,112,109,110,111,3,5,126,127,128,4,129,', '1465114224');
INSERT INTO `zydz_auth_group` VALUES ('2', 'superStar', '1', '1,15,16,119,144,120,146,17,149,115,116,150,117,118,151,148,147,181,18,108,152,114,113,112,109,110,111,', '1542868067');

-- ----------------------------
-- Table structure for zydz_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `zydz_auth_rule`;
CREATE TABLE `zydz_auth_rule` (
  `r_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `r_name` char(80) NOT NULL DEFAULT '' COMMENT '控制器/方法',
  `r_title` char(20) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `r_type` tinyint(1) NOT NULL DEFAULT '1',
  `r_status` tinyint(1) NOT NULL DEFAULT '1',
  `r_authopen` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否验证权限，0.需要验证，1.无需验证',
  `r_css` varchar(20) DEFAULT NULL COMMENT '样式',
  `r_condition` char(100) DEFAULT '',
  `r_pid` int(5) NOT NULL DEFAULT '0' COMMENT '父栏目ID',
  `r_sort` int(11) DEFAULT '0' COMMENT '排序',
  `r_addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `r_zt` int(1) DEFAULT NULL,
  `r_menustatus` tinyint(1) DEFAULT '0' COMMENT '栏目菜单状态,0.隐藏，1.显示',
  PRIMARY KEY (`r_id`)
) ENGINE=MyISAM AUTO_INCREMENT=320 DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of zydz_auth_rule
-- ----------------------------
INSERT INTO `zydz_auth_rule` VALUES ('1', 'Sys', '系统设置', '1', '1', '0', 'fa-cogs', '', '0', '999', '1446535750', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('2', 'Sys/sys', '系统设置', '1', '1', '0', '', '', '1', '0', '1446535789', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('3', 'Sys/database', '数据库管理', '1', '1', '0', '', '', '1', '2', '1446535805', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('4', 'Sys/restore', '数据库还原', '1', '1', '0', '', '', '3', '10', '1446535750', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('5', 'Sys/database', '数据库管理', '1', '1', '0', '', '', '3', '1', '1446535834', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('6', 'Sys/sys', '站点设置', '1', '1', '0', '', '', '2', '0', '1446535843', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('15', 'Sys/adminList', '用户管理', '1', '1', '0', '', '', '1', '1', '1446535750', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('16', 'Sys/adminList', '用户列表', '1', '1', '0', '', '', '15', '0', '1446535750', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('17', 'Sys/adminGroup', '用户组列表', '1', '1', '0', '', '', '15', '1', '1446535750', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('18', 'Sys/adminRule', '权限管理', '1', '1', '0', '', '', '15', '1', '1446535750', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('23', 'Help/soft', '软件下载', '1', '1', '0', '', '', '22', '50', '1446711421', '0', '1');
INSERT INTO `zydz_auth_rule` VALUES ('36', 'We/we_menu', '自定义菜单', '1', '1', '0', '', '', '35', '50', '1447842477', '0', '1');
INSERT INTO `zydz_auth_rule` VALUES ('39', 'We/we_menu', '自定义菜单', '1', '1', '0', '', '', '36', '50', '1448501584', '0', '1');
INSERT INTO `zydz_auth_rule` VALUES ('105', 'Sys/runsys', '操作-保存', '1', '1', '0', '', '', '6', '50', '1461036331', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('106', 'Sys/runwesys', '操作-保存', '1', '1', '0', '', '', '10', '50', '1461037680', '0', '0');
INSERT INTO `zydz_auth_rule` VALUES ('107', 'Sys/runemail', '操作-保存', '1', '1', '0', '', '', '19', '50', '1461039346', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('108', 'Sys/ruleAdd', '操作-添加', '1', '1', '0', '', '', '18', '0', '1461550835', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('109', 'Sys/ruleState', '操作-状态', '1', '1', '0', '', '', '18', '5', '1461550949', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('110', 'Sys/ruleTz', '操作-验证', '1', '1', '0', '', '', '18', '6', '1461551129', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('111', 'Sys/ruleorder', '操作-排序', '1', '1', '0', '', '', '18', '7', '1461551263', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('112', 'Sys/ruleDel', '操作-删除', '1', '1', '0', '', '', '18', '4', '1461551536', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('113', 'Sys/ruleUpdate', '操作-改存', '1', '1', '0', '', '', '18', '3', '1461551855', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('114', 'Sys/ruleEdit', '操作-修改', '1', '1', '0', '', '', '18', '2', '1461551913', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('115', 'Sys/groupInsert', '操作-添存', '1', '1', '0', '', '', '17', '2', '1461552280', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('116', 'Sys/groupEdit', '操作-修改', '1', '1', '0', '', '', '17', '3', '1461552326', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('117', 'Sys/groupDel', '操作-删除', '1', '1', '0', '', '', '17', '30', '1461552349', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('118', 'Sys/groupAccess', '操作-权限', '1', '1', '0', '', '', '17', '40', '1461552404', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('119', 'Sys/adminAdd', '操作-添加', '1', '1', '0', '', '', '16', '0', '1461553162', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('120', 'Sys/adminEdit', '操作-修改', '1', '1', '0', '', '', '16', '2', '1461554130', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('122', 'Sys/source_runadd', '操作-添加', '1', '1', '0', '', '', '43', '10', '1461036331', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('123', 'Sys/source_order', '操作-排序', '1', '1', '0', '', '', '43', '50', '1461037680', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('124', 'Sys/source_runedit', '操作-改存', '1', '1', '0', '', '', '43', '30', '1461039346', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('125', 'Sys/source_del', '操作-删除', '1', '1', '0', '', '', '43', '40', '146103934', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('126', 'Sys/backup', '操作-备份', '1', '1', '0', '', '', '5', '1', '1461550835', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('127', 'Sys/optimize', '操作-优化', '1', '1', '0', '', '', '5', '1', '1461550835', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('128', 'Sys/repair', '操作-修复', '1', '1', '0', '', '', '5', '1', '1461550835', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('129', 'Sys/del', '操作-删除', '1', '1', '0', '', '', '4', '1', '1461550835', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('130', 'Sys/bxgs_state', '操作-状态', '1', '1', '0', '', '', '67', '5', '1461550835', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('131', 'Sys/bxgs_edit', '操作-修改', '1', '1', '0', '', '', '67', '1', '1461550835', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('132', 'Sys/bxgs_runedit', '操作-改存', '1', '1', '0', '', '', '67', '2', '1461550835', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('134', 'Sys/myinfo_runedit', '个人资料修改', '1', '1', '0', '', '', '68', '1', '1461550835', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('144', 'Sys/adminInsert', '操作-添存', '1', '1', '0', '', '', '16', '1', '1461550835', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('146', 'Sys/adminUpdate', '操作-改存', '1', '1', '0', '', '', '16', '3', '1461550835', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('147', 'Sys/groupInsert', '操作-添存', '1', '1', '0', '', '', '17', '50', '1461812136', '0', '0');
INSERT INTO `zydz_auth_rule` VALUES ('148', 'Sys/groupAdd', '操作-添加', '1', '1', '0', '', '', '17', '50', '1461812245', '0', '0');
INSERT INTO `zydz_auth_rule` VALUES ('149', 'Sys/groupAdd', '操作-添加', '1', '1', '0', '', '', '17', '1', '1461550835', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('150', 'Sys/groupUpdate', '操作-改存', '1', '1', '0', '', '', '17', '4', '1461550835', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('151', 'Sys/groupRunaccess', '操作-权存', '1', '1', '0', '', '', '17', '50', '1461550835', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('152', 'Sys/ruleAdmin', '操作-添存', '1', '1', '0', '', '', '18', '1', '1461550835', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('153', 'Sys/bxgs_runadd', '操作-添存', '1', '1', '0', '', '', '66', '1', '1461550835', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('180', 'Sys/source_edit', '操作-修改', '1', '1', '0', '', '', '43', '20', '1461832933', '1', '0');
INSERT INTO `zydz_auth_rule` VALUES ('181', 'Sys/groupState', '操作-状态', '1', '1', '0', '', '', '17', '50', '1461834340', '1', '1');
INSERT INTO `zydz_auth_rule` VALUES ('318', 'Company', '公司管理', '1', '1', '0', 'fa-calendar', '', '0', '50', '1542879742', null, '1');
INSERT INTO `zydz_auth_rule` VALUES ('319', 'Company/CompanyList', '公司列表', '1', '1', '0', '', '', '318', '50', '1542879785', null, '1');

-- ----------------------------
-- Table structure for zydz_sys
-- ----------------------------
DROP TABLE IF EXISTS `zydz_sys`;
CREATE TABLE `zydz_sys` (
  `s_id` int(36) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增',
  `s_name` char(36) NOT NULL DEFAULT '' COMMENT '站点名称',
  `s_url` varchar(36) NOT NULL DEFAULT '' COMMENT '站点网址',
  `s_title` varchar(200) NOT NULL COMMENT '首页SEO标题',
  `s_key` varchar(200) NOT NULL COMMENT '首页SEO关键字',
  `s_des` varchar(200) NOT NULL COMMENT '首页SEO描述',
  `s_email_open` tinyint(2) NOT NULL COMMENT '邮箱发送是否开启',
  `s_email_name` varchar(50) NOT NULL COMMENT '发送邮箱账号',
  `s_email_pwd` varchar(50) NOT NULL COMMENT '发送邮箱密码',
  `s_email_smtpname` varchar(50) NOT NULL COMMENT 'smtp服务器账号',
  `s_email_emname` varchar(30) NOT NULL COMMENT '邮件名',
  `s_email_rename` varchar(30) NOT NULL COMMENT '发件人姓名',
  `s_wesys_name` varchar(30) NOT NULL COMMENT '微信名称',
  `s_wesys_number` varchar(30) NOT NULL COMMENT '微信号',
  `s_wesys_id` varchar(20) NOT NULL COMMENT '微信原始ID',
  `s_wesys_type` tinyint(2) NOT NULL COMMENT '1=订阅号 2=服务号',
  `s_wesys_appid` varchar(30) NOT NULL COMMENT '微信appid',
  `s_wesys_appsecret` varchar(50) NOT NULL COMMENT '微信AppSecret',
  `s_wesys_token` varchar(50) NOT NULL COMMENT '微信token',
  `s_bah` varchar(50) DEFAULT NULL COMMENT '备案号',
  `s_copyright` varchar(30) DEFAULT NULL COMMENT 'copyright',
  `s_address` varchar(120) DEFAULT NULL COMMENT '公司地址',
  `s_tel` varchar(15) DEFAULT NULL COMMENT '公司电话',
  `s_email` varchar(50) DEFAULT NULL COMMENT '公司邮箱',
  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='系统-站点设置';

-- ----------------------------
-- Records of zydz_sys
-- ----------------------------
INSERT INTO `zydz_sys` VALUES ('1', '极速聘', 'http://www.linyidz.cn', '优途HR极速聘', '极速聘', '极速聘', '1', '876902658@qq.com', 'maggie198586', 'smtp.qq.com', '876902658', '网站管理员', 'chichu', 'chichu12345', '12231231231231111', '1', '12312312312', '123123123123123', 'weixin', '', '', '123', '', '');

-- ----------------------------
-- Table structure for zydz_user_login
-- ----------------------------
DROP TABLE IF EXISTS `zydz_user_login`;
CREATE TABLE `zydz_user_login` (
  `l_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '登录ID',
  `l_phone` varchar(20) DEFAULT NULL COMMENT '登录账号（手机号）',
  `l_password` varchar(150) DEFAULT NULL COMMENT '登录密码',
  `l_name` varchar(30) DEFAULT NULL COMMENT '用户名字',
  `l_addtime` int(20) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`l_id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8 COMMENT='普通用户登录表';

-- ----------------------------
-- Records of zydz_user_login
-- ----------------------------
INSERT INTO `zydz_user_login` VALUES ('80', '18306505051', '4297f44b13955235245b2497399d7a93', 'xiaoming', '1537620462');
INSERT INTO `zydz_user_login` VALUES ('82', '15806904158', 'e10adc3949ba59abbe56e057f20f883e', '普通', '1538272007');
INSERT INTO `zydz_user_login` VALUES ('84', '15806904151', 'e10adc3949ba59abbe56e057f20f883e', '123', '1538980538');
INSERT INTO `zydz_user_login` VALUES ('88', '15806904157', '827ccb0eea8a706c4c34a16891f84e7b', 'lalal', '1539069801');
