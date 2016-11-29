/*
Navicat MySQL Data Transfer

Source Server         : 61.160.196.109(shumaH5)
Source Server Version : 50169
Source Host           : 61.160.196.109:3306
Source Database       : shumaH5

Target Server Type    : MYSQL
Target Server Version : 50169
File Encoding         : 65001

Date: 2016-11-29 15:53:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `h5_ad`
-- ----------------------------
DROP TABLE IF EXISTS `h5_ad`;
CREATE TABLE `h5_ad` (
  `ad_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `position_id` smallint(5) NOT NULL,
  `ad_name` varchar(255) NOT NULL,
  `ad_link` varchar(255) DEFAULT '' COMMENT '广告链接',
  `ad_img` varchar(255) DEFAULT NULL COMMENT '图片路径',
  `ad_desc` text,
  `is_show` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of h5_ad
-- ----------------------------
INSERT INTO `h5_ad` VALUES ('14', '8', '商品详情页面广告', 'http://1322.com', 'uploads/ad/20161123/1dfd35db912dcdf953888ea0ff124f89.jpg', '商品详情页面广告', '1');
INSERT INTO `h5_ad` VALUES ('9', '3', '首页广告1', 'http://mt.189china.cn/home', 'uploads/ad/20161123/2f20cb2e4794d8da855ac21a57da9360.jpg', '', '1');
INSERT INTO `h5_ad` VALUES ('10', '5', '首页广告2', 'http://mt.189china.cn/home', 'uploads/ad/20161123/078eae0e2f29c25df1ad43069ab8e005.jpg', '', '1');
INSERT INTO `h5_ad` VALUES ('11', '6', '首页广告3', 'http://mt.189china.cn/home', 'uploads/ad/20161123/4692c5f5e47da0788e425b4770389769.jpg', '', '1');
INSERT INTO `h5_ad` VALUES ('12', '7', '商品列表页广告', 'http://mt.189china.cn/home', 'uploads/ad/20161123/e4817460748cc4a09a89b1b8d403c72a.jpg', '', '1');
INSERT INTO `h5_ad` VALUES ('15', '9', '客服广告', 'http://1322.com', 'uploads/ad/20161119/ff5649d6bb585f30eff80e68dce24521.jpg', '', '1');

-- ----------------------------
-- Table structure for `h5_ad_position`
-- ----------------------------
DROP TABLE IF EXISTS `h5_ad_position`;
CREATE TABLE `h5_ad_position` (
  `position_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `position_name` varchar(255) NOT NULL,
  `ad_width` smallint(5) NOT NULL,
  `ad_height` smallint(5) NOT NULL,
  `position_desc` text,
  PRIMARY KEY (`position_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of h5_ad_position
-- ----------------------------
INSERT INTO `h5_ad_position` VALUES ('5', '首页广告位（小右）', '375', '218', '首页广告位（小右）');
INSERT INTO `h5_ad_position` VALUES ('3', '首页广告位（小左）', '375', '218', '首页广告位（小左）');
INSERT INTO `h5_ad_position` VALUES ('6', '首页广告位（大）', '750', '256', '首页广告位（大）');
INSERT INTO `h5_ad_position` VALUES ('7', '商品列表页广告位', '750', '340', '商品列表页广告位');
INSERT INTO `h5_ad_position` VALUES ('8', '商品详情页面广告', '700', '300', '商品详情页面广告');
INSERT INTO `h5_ad_position` VALUES ('9', '客服广告', '750', '300', '客服广告');

-- ----------------------------
-- Table structure for `h5_admin_user`
-- ----------------------------
DROP TABLE IF EXISTS `h5_admin_user`;
CREATE TABLE `h5_admin_user` (
  `user_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `add_time` int(11) NOT NULL,
  `last_login_time` int(11) NOT NULL,
  `last_login_ip` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of h5_admin_user
-- ----------------------------
INSERT INTO `h5_admin_user` VALUES ('1', 'admin', '3d6771c12763556169c6968dd555e64b', '1478829886', '1480383290', '222.95.204.3', '1');
INSERT INTO `h5_admin_user` VALUES ('6', 'test', '96e79218965eb72c92a549dd5a330112', '1479907906', '1479907924', '49.77.147.248', '1');
INSERT INTO `h5_admin_user` VALUES ('5', '管理员', '51a0f22a5bf6903916b4df0a80a385e6', '1479894177', '1480312986', '222.95.204.3', '1');

-- ----------------------------
-- Table structure for `h5_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `h5_auth_group`;
CREATE TABLE `h5_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组所属模块',
  `type` varchar(10) NOT NULL DEFAULT '' COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of h5_auth_group
-- ----------------------------
INSERT INTO `h5_auth_group` VALUES ('10', 'admin', '1', '普通管理员', '', '1', '1,20,16,17,18,21,22,23,24,27,29,30,31,33,34,35,36,37');

-- ----------------------------
-- Table structure for `h5_auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `h5_auth_group_access`;
CREATE TABLE `h5_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of h5_auth_group_access
-- ----------------------------
INSERT INTO `h5_auth_group_access` VALUES ('2', '10');
INSERT INTO `h5_auth_group_access` VALUES ('4', '10');
INSERT INTO `h5_auth_group_access` VALUES ('5', '10');
INSERT INTO `h5_auth_group_access` VALUES ('6', '10');

-- ----------------------------
-- Table structure for `h5_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `h5_auth_rule`;
CREATE TABLE `h5_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `group` char(20) NOT NULL DEFAULT '' COMMENT '权限节点分组',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  `sort` smallint(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of h5_auth_rule
-- ----------------------------
INSERT INTO `h5_auth_rule` VALUES ('1', 'admin', '2', 'admin/index/index', '后台首页', '首页管理', '0', 'index', '1');
INSERT INTO `h5_auth_rule` VALUES ('2', 'admin', '2', 'admin/user/index', '管理员列表', '管理员管理', '1', 'user,group', '2');
INSERT INTO `h5_auth_rule` VALUES ('3', 'admin', '1', 'admin/user/add', '添加管理员', '管理员管理', '1', '', '1');
INSERT INTO `h5_auth_rule` VALUES ('4', 'admin', '1', 'admin/user/edit', '编辑管理员', '管理员管理', '1', '', '2');
INSERT INTO `h5_auth_rule` VALUES ('5', 'admin', '1', 'admin/user/delete', '删除管理员', '管理员管理', '1', '', '3');
INSERT INTO `h5_auth_rule` VALUES ('6', 'admin', '2', 'admin/group/index', '用户组列表', '管理员管理', '1', 'user,group', '3');
INSERT INTO `h5_auth_rule` VALUES ('7', 'admin', '1', 'admin/group/add', '添加用户组', '管理员管理', '1', '', '1');
INSERT INTO `h5_auth_rule` VALUES ('8', 'admin', '1', 'admin/group/edit', '编辑用户组', '管理员管理', '1', '', '2');
INSERT INTO `h5_auth_rule` VALUES ('9', 'admin', '1', 'admin/group/del', '删除用户组', '管理员管理', '1', '', '3');
INSERT INTO `h5_auth_rule` VALUES ('10', 'admin', '1', 'admin/group/auth', '用户组授权', '管理员管理', '1', '', '4');
INSERT INTO `h5_auth_rule` VALUES ('11', 'admin', '2', 'admin/group/access', '权限列表', '管理员管理', '1', 'user,group', '4');
INSERT INTO `h5_auth_rule` VALUES ('12', 'admin', '1', 'admin/group/addNode', '添加节点', '管理员管理', '1', '', '1');
INSERT INTO `h5_auth_rule` VALUES ('13', 'admin', '1', 'admin/group/editNode', '编辑节点', '管理员管理', '1', '', '2');
INSERT INTO `h5_auth_rule` VALUES ('14', 'admin', '1', 'admin/group/delNode', '删除节点', '管理员管理', '1', '', '3');
INSERT INTO `h5_auth_rule` VALUES ('20', 'admin', '2', 'admin/goods/index', '商品列表', '商品管理', '1', 'goods', null);
INSERT INTO `h5_auth_rule` VALUES ('16', 'admin', '2', 'admin/goods/category', '商品分类', '商品管理', '1', 'goods', null);
INSERT INTO `h5_auth_rule` VALUES ('17', 'admin', '1', 'admin/goods/addCat', '添加商品分类', '商品管理', '1', 'goods', null);
INSERT INTO `h5_auth_rule` VALUES ('18', 'admin', '1', 'admin/goods/editCat', '编辑商品分类', '商品管理', '1', 'goods', null);
INSERT INTO `h5_auth_rule` VALUES ('19', 'admin', '1', 'admin/goods/delCat', '删除商品分类', '商品管理', '1', 'goods', null);
INSERT INTO `h5_auth_rule` VALUES ('21', 'admin', '1', 'admin/goods/add', '添加商品', '商品管理', '1', 'goods', null);
INSERT INTO `h5_auth_rule` VALUES ('22', 'admin', '1', 'admin/goods/edit', '编辑商品', '商品管理', '1', 'goods', null);
INSERT INTO `h5_auth_rule` VALUES ('23', 'admin', '1', 'admin/goods/del', '删除商品', '商品管理', '1', 'goods', null);
INSERT INTO `h5_auth_rule` VALUES ('24', 'admin', '2', 'admin/ad/index', '首页轮播图', '广告管理', '1', 'ad', null);
INSERT INTO `h5_auth_rule` VALUES ('25', 'admin', '2', 'admin/ad/adPosition', '广告位置', '广告管理', '1', 'ad', null);
INSERT INTO `h5_auth_rule` VALUES ('26', 'admin', '1', 'admin/ad/addAdPosition', '添加广告位', '广告管理', '1', 'ad', null);
INSERT INTO `h5_auth_rule` VALUES ('27', 'admin', '1', 'admin/ad/editAdPosition', '编辑广告位', '广告管理', '1', 'ad', null);
INSERT INTO `h5_auth_rule` VALUES ('28', 'admin', '1', 'admin/ad/delAdPosition', '删除广告位', '广告管理', '1', 'ad', null);
INSERT INTO `h5_auth_rule` VALUES ('29', 'admin', '2', 'admin/ad/adList', '广告列表', '广告管理', '1', 'ad', null);
INSERT INTO `h5_auth_rule` VALUES ('30', 'admin', '1', 'admin/ad/addAd', '添加广告', '广告管理', '1', 'ad', null);
INSERT INTO `h5_auth_rule` VALUES ('31', 'admin', '1', 'admin/ad/editAd', '编辑广告', '广告管理', '1', 'ad', null);
INSERT INTO `h5_auth_rule` VALUES ('32', 'admin', '1', 'admin/ad/delAd', '删除广告', '广告管理', '1', 'ad', null);
INSERT INTO `h5_auth_rule` VALUES ('33', 'admin', '2', 'admin/contact/contactList', '客服列表', '客服管理', '1', 'contact', null);
INSERT INTO `h5_auth_rule` VALUES ('34', 'admin', '1', 'admin/contact/addContact', '添加客服', '客服管理', '1', 'contact', null);
INSERT INTO `h5_auth_rule` VALUES ('35', 'admin', '1', 'admin/contact/editContact', '编辑客服', '客服管理', '1', 'contact', null);
INSERT INTO `h5_auth_rule` VALUES ('36', 'admin', '1', 'admin/contact/delContact', '删除客服', '客服管理', '1', 'contact', null);
INSERT INTO `h5_auth_rule` VALUES ('37', 'admin', '2', 'admin/member/index', '会员列表', '会员管理', '1', 'member', null);

-- ----------------------------
-- Table structure for `h5_banner`
-- ----------------------------
DROP TABLE IF EXISTS `h5_banner`;
CREATE TABLE `h5_banner` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `img_path` varchar(255) NOT NULL,
  `img_url` varchar(255) DEFAULT NULL,
  `img_desc` text,
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of h5_banner
-- ----------------------------
INSERT INTO `h5_banner` VALUES ('43', 'uploads/banner/20161123/thumb_b122180550531b21bc819fb895da513f.jpg', 'http://mt.189china.cn/home', '', '1');
INSERT INTO `h5_banner` VALUES ('49', 'uploads/banner/20161123/thumb_074edde46104e7634e63b59159009a7b.jpg', 'mt.189china.cn/home', '', '1');
INSERT INTO `h5_banner` VALUES ('50', 'uploads/banner/20161123/thumb_faf4ceca8cc7fae4569f6b8a484a5c30.jpg', 'mt.189china.cn/home', '', '1');

-- ----------------------------
-- Table structure for `h5_collect`
-- ----------------------------
DROP TABLE IF EXISTS `h5_collect`;
CREATE TABLE `h5_collect` (
  `collect_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) NOT NULL,
  `goods_id` mediumint(8) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`collect_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of h5_collect
-- ----------------------------
INSERT INTO `h5_collect` VALUES ('13', '6', '10', '1479713327');
INSERT INTO `h5_collect` VALUES ('14', '7', '12', '1479714189');
INSERT INTO `h5_collect` VALUES ('10', '3', '10', '1479709334');

-- ----------------------------
-- Table structure for `h5_contact`
-- ----------------------------
DROP TABLE IF EXISTS `h5_contact`;
CREATE TABLE `h5_contact` (
  `contact_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `contact_name` varchar(255) NOT NULL,
  `weixin` varchar(255) NOT NULL,
  `weixin_img` varchar(255) NOT NULL,
  `weixin_desc` text,
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`contact_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of h5_contact
-- ----------------------------
INSERT INTO `h5_contact` VALUES ('6', 'test', 'test', 'uploads/contact/20161128/20625e47872bedff8f290dd6fb01bee3.jpg', '客服描述客服描述客服描述客服描述客服描述客服描述客服描述客服描述客服描述客服描述客服描述客服描述客服描述', '1');

-- ----------------------------
-- Table structure for `h5_goods`
-- ----------------------------
DROP TABLE IF EXISTS `h5_goods`;
CREATE TABLE `h5_goods` (
  `goods_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(255) NOT NULL,
  `cat_id` smallint(5) NOT NULL COMMENT '商品分类id',
  `goods_desc` text COMMENT '商品描述',
  `goods_info` text COMMENT '商品详情',
  `goods_price` decimal(10,2) NOT NULL,
  `goods_number` mediumint(8) NOT NULL,
  `goods_thumb` varchar(255) NOT NULL,
  `sort` smallint(5) DEFAULT NULL,
  `is_on_sale` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否上架',
  `is_show_index` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示在首页',
  `add_time` date NOT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of h5_goods
-- ----------------------------
INSERT INTO `h5_goods` VALUES ('16', '美图M6(特别版 月光白)', '11', 'Hello Kitty特别版', '<h2 class=\"m6-title\" style=\"font-size:42px;font-weight:400;text-align:center;font-family:webfont;color:#333333;background-color:#FFFFFF;\">\r\n	前置2100万像素摄像头，继续引领自拍风潮。\r\n</h2>\r\n<p class=\"m6-desc\" style=\"font-size:16px;text-align:center;color:#333333;font-family:&quot;background-color:#FFFFFF;\">\r\n	美图M6配置了2100万像素的索尼前置摄像头，搭配独立的索喜（原富士通）Milbeaut图像处理器。它拥有更快的对焦、出色的色彩还原、高像素的成像、更好的美颜，让手机也能拍出电影画质级自拍。\r\n</p>\r\n<p class=\"m6-desc\" style=\"font-size:16px;text-align:center;color:#333333;font-family:&quot;background-color:#FFFFFF;\">\r\n	<img src=\"/application/admin/static/kindeditor/attached/image/20161128/20161128161201_62525.jpg\" alt=\"\" />\r\n</p>', '2599.00', '1', 'uploads/goods/20161128/f25762fd33cc1415228b8699fb931555.jpg', '1', '1', '1', '2016-11-28');
INSERT INTO `h5_goods` VALUES ('17', '美图V4s雅致版', '11', '美图V4s雅致版 自拍旗舰', '<strong>CPU、存储与电池</strong><br />\r\n<br />\r\nCPU &nbsp; &nbsp;MT6795 64位，八核 2.0GHz<br />\r\nGPU &nbsp; &nbsp;G6200<br />\r\n系统 &nbsp; &nbsp;MEIOS3（基于Android5.1深度优化）<br />\r\nRAM &nbsp; &nbsp;3GB<br />\r\nROM &nbsp; &nbsp;128GB<br />\r\n电池容量 &nbsp; &nbsp;2650mAh 锂聚合物充电电池（不可更换）<br />\r\nSIM卡 &nbsp; &nbsp;本手机仅支持标准Nano-SIM卡（使用非标准Nano-SIM卡可能导致手机卡座损坏）<br />\r\n<p>\r\n	<strong><br />\r\n</strong>\r\n</p>\r\n<p>\r\n	<strong>屏幕</strong>\r\n</p>\r\n<br />\r\n屏幕材质 &nbsp; &nbsp;AMOLED<br />\r\n分辨率 &nbsp; &nbsp;1920*1080 FHD<br />\r\n屏幕尺寸 &nbsp; &nbsp;5.0英寸<br />\r\n触摸屏 &nbsp; &nbsp;Oncell，多点触控<br />\r\n<p>\r\n	<strong><br />\r\n</strong>\r\n</p>\r\n<p>\r\n	<strong>拍照与摄像</strong>\r\n</p>\r\n<br />\r\n前置／后置摄像头 &nbsp; &nbsp;SONY IMX230，2100万<br />\r\n图像处理芯片 &nbsp; &nbsp;索喜（原富士通）Milbeaut专业图像处理芯片<br />\r\n补光灯 &nbsp; &nbsp;前置补光灯<br />\r\n闪光灯 &nbsp; &nbsp;后置双色温LED闪光灯<br />\r\n光圈 &nbsp; &nbsp;F/2.2<br />\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src=\"/application/admin/static/kindeditor/attached/image/20161128/20161128162544_78112.png\" alt=\"\" />\r\n</p>', '4399.00', '1', 'uploads/goods/20161128/f6c5c53b4d41a83acdc6e3a3c05b4c7f.jpg', '2', '1', '1', '2016-11-28');

-- ----------------------------
-- Table structure for `h5_goods_category`
-- ----------------------------
DROP TABLE IF EXISTS `h5_goods_category`;
CREATE TABLE `h5_goods_category` (
  `cat_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(30) NOT NULL,
  `cat_desc` text,
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  `pid` smallint(5) NOT NULL,
  `level` smallint(5) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of h5_goods_category
-- ----------------------------
INSERT INTO `h5_goods_category` VALUES ('11', '手机', '', '1', '0', '1');
INSERT INTO `h5_goods_category` VALUES ('12', '相机', '', '1', '0', '1');
INSERT INTO `h5_goods_category` VALUES ('13', '配件', '', '1', '0', '1');

-- ----------------------------
-- Table structure for `h5_goods_gallery`
-- ----------------------------
DROP TABLE IF EXISTS `h5_goods_gallery`;
CREATE TABLE `h5_goods_gallery` (
  `img_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `goods_id` mediumint(8) NOT NULL,
  `img_url` varchar(255) NOT NULL,
  PRIMARY KEY (`img_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of h5_goods_gallery
-- ----------------------------
INSERT INTO `h5_goods_gallery` VALUES ('10', '7', 'uploads/goods/20161116/7dc19d521e46efe67d9285e421ffe846.jpg');
INSERT INTO `h5_goods_gallery` VALUES ('9', '7', 'uploads/goods/20161116/dfcad782121a49135e1aebd8c919cb3b.jpg');
INSERT INTO `h5_goods_gallery` VALUES ('17', '17', 'uploads/goods/20161128/f6c5c53b4d41a83acdc6e3a3c05b4c7f.jpg');
INSERT INTO `h5_goods_gallery` VALUES ('16', '16', 'uploads/goods/20161128/f25762fd33cc1415228b8699fb931555.jpg');

-- ----------------------------
-- Table structure for `h5_member`
-- ----------------------------
DROP TABLE IF EXISTS `h5_member`;
CREATE TABLE `h5_member` (
  `user_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `nick_name` varchar(255) NOT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `openid` varchar(255) NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of h5_member
-- ----------------------------
INSERT INTO `h5_member` VALUES ('6', '风一样的男人', 'http://wx.qlogo.cn/mmopen/mqDib08WI45u26VA1I1TN1GBa6Xu7AUbpL4ia4rWt6S0GVcpxTiaDW6TIJzawaYPCwQEYtEfwceWpgzP27icm00slR6icCRMqiafH8/0', 'oO-aSs0jbQ6vISq0ktdrdkB3LBSE', '1', '1479713323');
INSERT INTO `h5_member` VALUES ('3', '蓉儿', 'http://wx.qlogo.cn/mmopen/nKeyKEtrIFOSH7e57Sw7gJtE0gLjtfa8HSItyMn3icG8VGicmcpmYuTY4Ph4RNicD5ZU6c3gx21MibgucA948oV72ntDNckEANng/0', 'oO-aSswzsoS5UGiNyT7NatdFEyxs', '2', '1479709325');

-- ----------------------------
-- Table structure for `h5_trace`
-- ----------------------------
DROP TABLE IF EXISTS `h5_trace`;
CREATE TABLE `h5_trace` (
  `trace_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) NOT NULL,
  `goods_id` mediumint(8) NOT NULL,
  PRIMARY KEY (`trace_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of h5_trace
-- ----------------------------
INSERT INTO `h5_trace` VALUES ('2', '7', '12');
INSERT INTO `h5_trace` VALUES ('3', '6', '12');
INSERT INTO `h5_trace` VALUES ('4', '3', '12');
