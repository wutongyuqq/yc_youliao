<?php

$installSql = <<<sql



DROP TABLE IF EXISTS `ims_mihua_sq_account`;
CREATE TABLE `ims_mihua_sq_account` (
  `cash_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `username` varchar(64) DEFAULT NULL,
  `money` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '0未审核1通过2拒绝',
  `reason` text,
  `account` varchar(64) DEFAULT NULL,
  `bank_name` varchar(128) DEFAULT NULL,
  `bank_num` varchar(32) DEFAULT NULL,
  `bank_branch` varchar(128) DEFAULT NULL,
  `bank_realname` varchar(64) DEFAULT NULL,
  `ordersn` varchar(20) DEFAULT NULL,
  `paytype` tinyint(1) DEFAULT NULL COMMENT '1微信2支付宝3银行卡',
  `alipay_account` varchar(128) DEFAULT NULL,
  `alipay_name` varchar(64) DEFAULT NULL,
  `transfer` decimal(10,2) DEFAULT '0.00' COMMENT '手续费',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '提现金额',
  `admin_id` int(11) DEFAULT NULL,
  `openid` varchar(100) NOT NULL,
  `checktime` int(11) DEFAULT NULL COMMENT '处理申请时间',
  `check_admin` int(11) DEFAULT NULL COMMENT '处理人',
  `type` tinyint(3) DEFAULT NULL COMMENT '1用户提现 2商户提现',
  PRIMARY KEY (`cash_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_address`;
CREATE TABLE `ims_mihua_sq_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `area` varchar(30) NOT NULL,
  `address` varchar(300) NOT NULL,
  `isdefault` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `lat` varchar(255) DEFAULT '',
  `lng` varchar(255) DEFAULT '',
  `inco` varchar(300) DEFAULT NULL COMMENT '标签',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_admin`;
CREATE TABLE `ims_mihua_sq_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `admin_name` varchar(100) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `passport` varchar(50) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `openid` varchar(100) NOT NULL,
  `addtime` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0' COMMENT '0开启  1暂停',
  `msg_flag` tinyint(1) DEFAULT '0' COMMENT '0=>发送通知,1=>不发送通知',
  `mobile` char(15) DEFAULT NULL,
  `admin_uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_adv`;
CREATE TABLE `ims_mihua_sq_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `type` text COMMENT '1首页，2首页中部，3首页底部，4拼团，5秒杀，6首单，7买单，8同城',
  `add_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_area`;
CREATE TABLE `ims_mihua_sq_area` (
  `area_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `city_id` smallint(5) DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  `area_name` varchar(32) DEFAULT NULL,
  `orderby` tinyint(3) DEFAULT '100',
  `is_hot` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`area_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_balance`;
CREATE TABLE `ims_mihua_sq_balance` (
  `balance_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `money` float(10,2) DEFAULT '0.00',
  `uid` int(11) DEFAULT '0',
  `addtime` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0' COMMENT '0=>未支付,1=>支付完成',
  `openid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`balance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_black`;
CREATE TABLE `ims_mihua_sq_black` (
  `black_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` varchar(50) DEFAULT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`black_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_cart`;
CREATE TABLE `ims_mihua_sq_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `optionid` int(10) DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_cash`;
CREATE TABLE `ims_mihua_sq_cash` (
  `cash_id` int(11) NOT NULL AUTO_INCREMENT,
  `cash_ordersn` varchar(20) NOT NULL,
  `cash_type` tinyint(1) DEFAULT '1' COMMENT '操作类别(1抢红包，2打赏)',
  `create_time` int(11) NOT NULL COMMENT '该记录创建时间',
  `openid` varchar(50) NOT NULL DEFAULT '用户openid',
  `from_openid` varchar(50) NOT NULL DEFAULT '从哪个用户的opneid得到钱',
  `uniacid` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL COMMENT '金额',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态(0.付款中，1:成功)',
  `red_id` int(11) NOT NULL,
  PRIMARY KEY (`cash_id`),
  UNIQUE KEY `id` (`cash_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_channel`;
CREATE TABLE `ims_mihua_sq_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `sharetitle` varchar(50) NOT NULL,
  `sharethumb` varchar(100) NOT NULL,
  `sharedes` varchar(100) NOT NULL,
  `displayorder` int(11) NOT NULL,
  `canrelease` tinyint(1) NOT NULL DEFAULT '1',
  `isshenhe` tinyint(1) NOT NULL DEFAULT '1',
  `iscang` tinyint(1) NOT NULL,
  `minscore` smallint(6) NOT NULL,
  `isneedpay` tinyint(1) NOT NULL,
  `needpay` decimal(10,2) NOT NULL,
  `templateid` smallint(6) NOT NULL,
  `detailtemplateid` smallint(6) NOT NULL,
  `module` tinyint(4) NOT NULL,
  `autourl` varchar(200) NOT NULL,
  `listhtml` longtext NOT NULL,
  `detailhtml` longtext NOT NULL,
  `defult_list` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1使用默认列表页 2自定义',
  `defult_detail` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1使用默认详情页 2自定义',
  `ison` tinyint(1) NOT NULL DEFAULT '1',
  `zdprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `haibaobg` varchar(100) NOT NULL,
  `template` varchar(20) NOT NULL,
  `template2` varchar(20) NOT NULL,
  `template3` varchar(20) NOT NULL,
  `fid` smallint(6) NOT NULL,
  `bgparam` varchar(10240) NOT NULL,
  `show_location` tinyint(1) DEFAULT '1' COMMENT '1显示发布位置 2不显示',
  `show_comment` tinyint(1) DEFAULT '1' COMMENT '1开启评论 2不开启',
  `minusscore` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_chat`;
CREATE TABLE `ims_mihua_sq_chat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `toopenid` varchar(100) NOT NULL,
  `content` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `hasread` tinyint(1) NOT NULL,
  `deleteid` int(11) DEFAULT '0' COMMENT '删除者id',
  `userid` varchar(100) DEFAULT NULL,
  `user1` int(11) DEFAULT NULL COMMENT '标识发送消息人',
  `user2` int(11) DEFAULT NULL COMMENT '标识接收消息人',
  `flag` tinyint(3) DEFAULT '0' COMMENT '0文字 1图片 2语音',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_city`;
CREATE TABLE `ims_mihua_sq_city` (
  `city_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `pinyin` varchar(32) DEFAULT NULL,
  `isopen` tinyint(2) DEFAULT '1',
  `lng` varchar(15) DEFAULT NULL,
  `lat` varchar(15) DEFAULT NULL,
  `orderby` tinyint(3) DEFAULT '100',
  `first_letter` char(1) DEFAULT NULL,
  `is_hot` tinyint(1) DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_citys`;
CREATE TABLE `ims_mihua_sq_citys` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `district` varchar(50) NOT NULL,
  `is_hot` tinyint(1) NOT NULL,
  `firstz` char(1) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `displayorder` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_collect`;
CREATE TABLE `ims_mihua_sq_collect` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `message_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `type` tinyint(3) DEFAULT '1' COMMENT '1同城信息 2收藏的商品 3收藏的店铺',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_comment`;
CREATE TABLE `ims_mihua_sq_comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `comment_content` text,
  `mid` int(11) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `ordersn` char(20) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `reply` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_discount`;
CREATE TABLE `ims_mihua_sq_discount` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned DEFAULT '0',
  `shop_id` int(11) DEFAULT '0',
  `cardtype` tinyint(3) unsigned DEFAULT '0' COMMENT '1满减2打折3随机减4积分抵扣',
  `cardname` varchar(100) DEFAULT NULL,
  `needcredit` int(11) unsigned DEFAULT '0' COMMENT '扣除积分',
  `cardvalue` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '面额',
  `fullmoney` decimal(10,2) unsigned DEFAULT '0.00',
  `randomnum` decimal(10,2) unsigned DEFAULT '0.00',
  `totalnum` int(11) unsigned DEFAULT '0',
  `takednum` int(11) unsigned DEFAULT '0',
  `lastnum` int(11) unsigned DEFAULT '0',
  `usednum` int(11) unsigned DEFAULT '0',
  `limitnum` int(11) unsigned DEFAULT '0',
  `expire` int(11) unsigned DEFAULT '0',
  `starttime` int(11) unsigned DEFAULT '0',
  `endtime` int(11) unsigned DEFAULT '0',
  `status` tinyint(3) unsigned DEFAULT '0',
  `time` int(11) unsigned DEFAULT '0',
  `isrecommand` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_discount_record`;
CREATE TABLE `ims_mihua_sq_discount_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `discount_id` int(10) unsigned NOT NULL,
  `aftermoney` float(10,2) DEFAULT '0.00',
  `paymoney` float(10,2) DEFAULT '0.00',
  `shop_id` int(11) DEFAULT '0',
  `mid` int(11) DEFAULT '0' COMMENT '对应member表id',
  `createtime` int(11) DEFAULT '0',
  `ordersn` varchar(20) DEFAULT NULL,
  `status` tinyint(2) DEFAULT '0' COMMENT '0未支付1已支付',
  `paytype` tinyint(1) DEFAULT NULL COMMENT '1为余额，2微信支付，3支付宝，4银行版收银台,5货到付款',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_fields`;
CREATE TABLE `ims_mihua_sq_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `enname` varchar(30) NOT NULL,
  `mtype` varchar(20) NOT NULL,
  `mtypecon` varchar(255) NOT NULL,
  `canvoice` tinyint(1) NOT NULL,
  `canvideo` tinyint(1) NOT NULL,
  `isrequired` tinyint(1) NOT NULL,
  `islenval` tinyint(1) NOT NULL,
  `minlen` smallint(6) NOT NULL,
  `maxlen` smallint(6) NOT NULL,
  `displayorder` int(11) NOT NULL,
  `defaultval` varchar(100) NOT NULL,
  `showname` varchar(50) NOT NULL,
  `sharetype` tinyint(1) NOT NULL,
  `isfilter` tinyint(1) NOT NULL,
  `danwei` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `ims_mihua_sq_fields` (`id`, `weid`, `mid`, `name`, `enname`, `mtype`, `mtypecon`, `canvoice`, `canvideo`, `isrequired`, `islenval`, `minlen`, `maxlen`, `displayorder`, `defaultval`, `showname`, `sharetype`, `isfilter`, `danwei`) VALUES
(1,	5,	1,	'租房标题',	'title',	'text',	'',	0,	0,	1,	1,	12,	120,	1,	'',	'租房标题',	1,	0,	''),
(2,	5,	1,	'租金',	'price',	'number',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'租金',	0,	0,	''),
(3,	5,	1,	'房屋图片',	'thumbs',	'images',	'',	0,	0,	1,	0,	0,	0,	5,	'',	'房屋图片',	0,	0,	''),
(4,	5,	1,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'联系人',	0,	0,	''),
(5,	5,	1,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'手机号码',	0,	0,	''),
(6,	5,	1,	'房屋描述',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	8,	'',	'房屋描述',	2,	0,	''),
(7,	5,	1,	'户型',	'huxing',	'radio',	'三室两厅|三室一厅|两室一厅|一室户|其他',	0,	0,	1,	0,	0,	0,	9,	'',	'户型',	0,	0,	''),
(8,	5,	1,	'面积',	'areas',	'text',	'',	0,	0,	1,	0,	0,	0,	10,	'',	'面积',	0,	0,	''),
(9,	5,	1,	'所在小区',	'xiaoqu',	'text',	'',	0,	0,	1,	0,	0,	0,	11,	'',	'所在小区',	0,	0,	''),
(10,	5,	1,	'楼层',	'fllor',	'text',	'',	0,	0,	1,	0,	0,	0,	13,	'',	'楼层',	0,	0,	''),
(11,	5,	1,	'总楼层',	'allfllor',	'text',	'',	0,	0,	1,	0,	0,	0,	14,	'',	'总楼层',	0,	0,	''),
(12,	5,	1,	'付款方式',	'paytype',	'radio',	'押一付一|押一付二|押一付三|面议',	0,	0,	1,	0,	0,	0,	15,	'押一付三',	'付款方式',	0,	0,	''),
(13,	5,	1,	'朝向',	'chaoxiang',	'text',	'',	0,	0,	1,	0,	0,	0,	16,	'',	'朝向',	0,	0,	''),
(14,	5,	1,	'装修',	'zhuangxiu',	'radio',	'精装修|简单装修',	0,	0,	1,	0,	0,	0,	17,	'',	'装修',	0,	0,	''),
(15,	5,	1,	'房屋配置',	'peizhi',	'checkbox',	'床|衣柜|沙发|电视',	0,	0,	1,	0,	0,	0,	19,	'',	'房屋配置',	0,	0,	''),
(16,	5,	2,	'拼车标题',	'title',	'text',	'',	0,	0,	1,	1,	12,	120,	1,	'',	'拼车标题',	1,	0,	''),
(17,	5,	2,	'费用',	'price',	'text',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'费用',	0,	0,	''),
(18,	5,	2,	'车辆照片',	'thumbs',	'images',	'',	0,	0,	1,	0,	0,	0,	4,	'',	'车辆照片',	0,	0,	''),
(19,	5,	2,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	5,	'',	'联系人',	0,	0,	''),
(20,	5,	2,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'手机号码',	0,	0,	''),
(21,	5,	2,	'详细描述',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'详细描述',	2,	0,	''),
(22,	5,	2,	'出发地点',	'cfcity',	'text',	'',	0,	0,	1,	0,	0,	0,	8,	'',	'出发地点',	0,	0,	''),
(23,	5,	2,	'目的地',	'ddcity',	'text',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'目的地',	0,	0,	''),
(24,	5,	2,	'出行时间',	'gotime',	'datetime',	'',	0,	0,	1,	0,	0,	0,	13,	'',	'出行时间',	0,	0,	''),
(25,	5,	2,	'座位数',	'weizi',	'number',	'',	0,	0,	1,	0,	0,	0,	14,	'',	'座位数',	0,	0,	''),
(26,	5,	2,	'拼车类型',	'type',	'radio',	'长途拼车|上下班拼车',	0,	0,	1,	0,	0,	0,	14,	'',	'拼车类型',	0,	0,	''),
(27,	5,	3,	'个人说明',	'jyxuqiu',	'longtext',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'个人说明',	2,	0,	''),
(28,	5,	3,	'体重',	'weight',	'number',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'体重',	0,	0,	''),
(29,	5,	3,	'身高',	'height',	'number',	'',	0,	0,	1,	0,	0,	0,	5,	'',	'身高',	0,	0,	''),
(30,	5,	3,	'标题',	'title',	'text',	'',	0,	0,	1,	1,	10,	100,	1,	'',	'标题',	1,	0,	''),
(31,	5,	3,	'年龄',	'age',	'text',	'',	0,	0,	1,	0,	0,	0,	4,	'',	'年龄',	0,	0,	''),
(32,	5,	3,	'个人照片',	'zhaopian',	'images',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'个人照片',	0,	0,	''),
(33,	5,	3,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	8,	'',	'联系人',	0,	0,	''),
(34,	5,	3,	'电话',	'dianhua',	'telphone',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'电话',	0,	0,	''),
(35,	5,	3,	'性别',	'sex',	'radio',	'男|女',	0,	0,	1,	0,	0,	0,	9,	'',	'性别',	0,	0,	''),
(36,	5,	4,	'二手物品标题',	'title',	'text',	'',	0,	0,	1,	1,	12,	120,	1,	'',	'二手物品标题',	1,	0,	''),
(37,	5,	4,	'二手物品图片',	'thumbs',	'images',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'二手物品图片',	0,	0,	''),
(38,	5,	4,	'价格',	'price',	'radio',	'100元以下|100元以上',	0,	0,	1,	0,	0,	0,	3,	'',	'价格',	0,	0,	''),
(39,	5,	4,	'原价',	'yuanjia',	'text',	'',	0,	0,	1,	0,	0,	0,	4,	'',	'原价',	0,	0,	''),
(40,	5,	4,	'详细描述',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	5,	'',	'详细描述',	2,	0,	''),
(41,	5,	4,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	8,	'',	'联系人',	0,	0,	''),
(42,	5,	4,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'手机号码',	0,	0,	''),
(43,	5,	4,	'交易类型',	'type',	'radio',	'个人|商家',	0,	0,	1,	0,	0,	0,	3,	'',	'交易类型',	0,	0,	''),
(44,	5,	5,	'照片',	'tupian',	'images',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'照片',	0,	0,	''),
(45,	5,	5,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'联系人',	0,	0,	''),
(46,	5,	5,	'电话',	'dianhua',	'telphone',	'',	0,	0,	1,	0,	0,	0,	10,	'',	'电话',	0,	0,	''),
(47,	5,	5,	'标题',	'title',	'text',	'',	0,	0,	1,	1,	10,	90,	1,	'',	'标题',	1,	0,	''),
(48,	5,	5,	'类型',	'type',	'radio',	'寻人|寻物|寻宠物',	0,	0,	1,	0,	0,	0,	0,	'',	'类型',	0,	0,	''),
(49,	5,	5,	'描述',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	0,	'',	'描述',	2,	0,	''),
(50,	5,	6,	'活动标题',	'title',	'text',	'',	0,	0,	1,	1,	10,	100,	1,	'',	'活动标题',	1,	0,	''),
(51,	5,	6,	'图片',	'tupian',	'images',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'图片',	0,	0,	''),
(52,	5,	6,	'活动时间',	'huodongtime',	'datetime',	'',	0,	0,	1,	0,	0,	0,	3,	'',	'活动时间',	0,	0,	''),
(53,	5,	6,	'活动地点',	'huodongaddress',	'text',	'',	0,	0,	1,	0,	0,	0,	4,	'',	'活动地点',	0,	0,	''),
(54,	5,	6,	'活动费用',	'feiyong',	'text',	'',	0,	0,	1,	0,	0,	0,	5,	'',	'活动费用',	0,	0,	''),
(55,	5,	6,	'活动介绍',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'活动介绍',	2,	0,	''),
(56,	5,	6,	'活动类型',	'type',	'radio',	'看演唱会|看电影|户外旅游',	0,	0,	1,	0,	0,	0,	6,	'',	'活动类型',	0,	0,	''),
(57,	5,	7,	'职位名称',	'zwtitle',	'text',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'职位名称',	0,	0,	''),
(58,	5,	7,	'招聘标题',	'title',	'text',	'',	0,	0,	1,	1,	12,	120,	1,	'',	'招聘标题',	0,	0,	''),
(59,	5,	7,	'薪水范围',	'xinshui',	'radio',	'1000-3000元/月|3000-6000元/月|6000-10000元/月|10000-20000元/月|20000元/月以上',	0,	0,	1,	0,	0,	0,	4,	'',	'薪水范围',	0,	0,	''),
(60,	5,	7,	'招聘人数',	'needpeople',	'number',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'招聘人数',	0,	0,	''),
(61,	5,	7,	'工作地点',	'workaddress',	'text',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'工作地点',	0,	0,	''),
(62,	5,	7,	'职位简介',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	8,	'',	'职位简介',	0,	0,	''),
(63,	5,	7,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'联系人',	0,	0,	''),
(64,	5,	7,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	10,	'',	'手机号码',	0,	0,	''),
(65,	5,	7,	'福利',	'fuli',	'checkbox',	'五险一金|交通补助|餐补|加班少|下午茶|周末双休|话补|房补|加班补助|年底双薪|包吃住|包住|包吃',	0,	0,	1,	0,	0,	0,	15,	'',	'福利',	0,	0,	''),
(66,	5,	7,	'公司名称',	'companyname',	'text',	'',	0,	0,	1,	1,	5,	100,	16,	'',	'公司名称',	0,	0,	''),
(67,	5,	7,	'要求学历',	'xueli',	'radio',	'高中|大专|本科|研究生',	0,	0,	1,	0,	0,	0,	17,	'',	'要求学历',	0,	0,	''),
(68,	5,	8,	'标题',	'title',	'text',	'',	0,	0,	1,	1,	10,	100,	1,	'',	'标题',	1,	0,	''),
(69,	5,	8,	'图片',	'tupian',	'images',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'图片',	0,	0,	''),
(70,	5,	8,	'品种',	'pinzhong',	'radio',	'泰迪|金毛|比熊|萨摩耶|阿拉斯加|博美|哈士奇|拉布拉多|德国牧羊犬|松狮|秋田犬|吉娃娃|藏獒|雪纳瑞|贵宾|边境牧羊犬|巴哥犬|古牧|罗威纳|银狐犬|杜宾犬|京巴|比特|苏格兰牧羊犬|高加索犬|灵缇犬|西高地|马犬|喜乐蒂|牛头梗|雪橇犬|西施犬|大白熊|卡斯罗|沙皮犬|蝴蝶犬|伯恩山犬|斗牛犬|万能梗|小鹿犬|猎狐梗|威玛烈犬|柴犬|斑点狗|巴吉度猎犬|阿富汗猎犬|格力犬|比格犬|大丹犬|腊肠犬|可卡犬|柯基犬|圣伯纳|其他',	0,	0,	1,	0,	0,	0,	9,	'',	'品种',	0,	0,	''),
(71,	5,	8,	'描述',	'detailmsg',	'longtext',	'',	0,	0,	1,	0,	0,	0,	5,	'',	'描述',	2,	0,	''),
(72,	5,	8,	'价格',	'price',	'text',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'价格',	0,	0,	''),
(73,	5,	8,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'联系人',	0,	0,	''),
(74,	5,	8,	'联系手机',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	8,	'',	'联系手机',	0,	0,	''),
(75,	5,	8,	'所在区域',	'servicearea',	'text',	'',	0,	0,	1,	0,	0,	0,	10,	'',	'所在区域',	0,	0,	''),
(76,	3,	9,	'租房标题',	'title',	'text',	'',	0,	0,	1,	1,	12,	120,	1,	'',	'租房标题',	1,	0,	''),
(77,	3,	9,	'租金',	'price',	'number',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'租金',	0,	0,	''),
(78,	3,	9,	'房屋图片',	'thumbs',	'images',	'',	0,	0,	1,	0,	0,	0,	5,	'',	'房屋图片',	0,	0,	''),
(79,	3,	9,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'联系人',	0,	0,	''),
(80,	3,	9,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'手机号码',	0,	0,	''),
(81,	3,	9,	'房屋描述',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	8,	'',	'房屋描述',	2,	0,	''),
(82,	3,	9,	'户型',	'huxing',	'radio',	'三室两厅|三室一厅|两室一厅|一室户|其他',	0,	0,	1,	0,	0,	0,	9,	'',	'户型',	0,	0,	''),
(83,	3,	9,	'面积',	'areas',	'text',	'',	0,	0,	1,	0,	0,	0,	10,	'',	'面积',	0,	0,	''),
(84,	3,	9,	'所在小区',	'xiaoqu',	'text',	'',	0,	0,	1,	0,	0,	0,	11,	'',	'所在小区',	0,	0,	''),
(85,	3,	9,	'楼层',	'fllor',	'text',	'',	0,	0,	1,	0,	0,	0,	13,	'',	'楼层',	0,	0,	''),
(86,	3,	9,	'总楼层',	'allfllor',	'text',	'',	0,	0,	1,	0,	0,	0,	14,	'',	'总楼层',	0,	0,	''),
(87,	3,	9,	'付款方式',	'paytype',	'radio',	'押一付一|押一付二|押一付三|面议',	0,	0,	1,	0,	0,	0,	15,	'押一付三',	'付款方式',	0,	0,	''),
(88,	3,	9,	'朝向',	'chaoxiang',	'text',	'',	0,	0,	1,	0,	0,	0,	16,	'',	'朝向',	0,	0,	''),
(89,	3,	9,	'装修',	'zhuangxiu',	'radio',	'精装修|简单装修',	0,	0,	1,	0,	0,	0,	17,	'',	'装修',	0,	0,	''),
(90,	3,	9,	'房屋配置',	'peizhi',	'checkbox',	'床|衣柜|沙发|电视',	0,	0,	1,	0,	0,	0,	19,	'',	'房屋配置',	0,	0,	''),
(361,	5,	33,	'标题',	'title',	'text',	'',	0,	0,	1,	1,	10,	100,	1,	'',	'标题',	1,	0,	''),
(360,	5,	33,	'身高',	'height',	'number',	'',	0,	0,	1,	0,	0,	0,	5,	'',	'身高',	0,	0,	''),
(359,	5,	33,	'体重',	'weight',	'number',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'体重',	0,	0,	''),
(358,	5,	33,	'个人说明',	'jyxuqiu',	'longtext',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'个人说明',	2,	0,	''),
(357,	5,	31,	'交易类型',	'type',	'radio',	'个人|商家',	0,	0,	1,	0,	0,	0,	3,	'',	'交易类型',	0,	0,	''),
(356,	5,	31,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'手机号码',	0,	0,	''),
(355,	5,	31,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	8,	'',	'联系人',	0,	0,	''),
(354,	5,	31,	'详细描述',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	5,	'',	'详细描述',	2,	0,	''),
(353,	5,	31,	'原价',	'yuanjia',	'text',	'',	0,	0,	1,	0,	0,	0,	4,	'',	'原价',	0,	0,	''),
(352,	5,	31,	'价格',	'price',	'radio',	'100元以下|100元以上',	0,	0,	1,	0,	0,	0,	3,	'',	'价格',	0,	0,	''),
(351,	5,	31,	'二手物品图片',	'thumbs',	'images',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'二手物品图片',	0,	0,	''),
(350,	5,	31,	'二手物品标题',	'title',	'text',	'',	0,	0,	1,	1,	12,	120,	1,	'',	'二手物品标题',	1,	0,	''),
(138,	5,	15,	'期望工资',	'price',	'text',	'',	0,	0,	0,	0,	0,	0,	2,	'',	'期望工资',	0,	0,	''),
(193,	5,	14,	'',	'zhaop',	'images',	'',	0,	0,	0,	0,	0,	0,	10,	'',	'',	0,	0,	''),
(135,	5,	14,	'要求学历',	'xueli',	'radio',	'初中|高中|大专|本科|研究生|不限',	0,	0,	0,	0,	0,	0,	3,	'',	'要求学历',	0,	0,	''),
(194,	5,	19,	'租房标题',	'title',	'text',	'',	0,	0,	1,	1,	12,	120,	1,	'',	'租房标题',	1,	0,	''),
(133,	5,	14,	'',	'fuli',	'checkbox',	'五险一金|交通补助|餐补|加班少|下午茶|周末双休|油补|话补|房补|加班补助|年底双薪|包吃住|包住|包吃',	0,	0,	0,	0,	0,	0,	9,	'',	'',	0,	0,	''),
(132,	5,	14,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	12,	'',	'手机号码',	0,	0,	''),
(129,	5,	14,	'工作地点',	'workaddress',	'text',	'',	0,	0,	0,	0,	0,	0,	7,	'',	'工作地点',	0,	0,	''),
(130,	5,	14,	'职位简介',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	8,	'',	'职位简介',	2,	0,	''),
(131,	5,	14,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	11,	'',	'联系人',	0,	0,	''),
(128,	5,	14,	'招聘人数',	'needpeople',	'number',	'',	0,	0,	0,	0,	0,	0,	6,	'',	'招聘人数',	0,	0,	'名'),
(136,	5,	15,	'标题',	'title',	'text',	'',	0,	0,	1,	0,	0,	0,	1,	'',	'标题',	1,	0,	''),
(127,	5,	14,	'薪水范围',	'xinshui',	'radio',	'0-1000元/月|1000-2000元/月|2000-4000元/月|4000-6000元/月|6000-10000元/月|10000元/月以上',	0,	0,	1,	0,	0,	0,	4,	'',	'薪水范围',	0,	0,	''),
(126,	5,	14,	'公司/店铺名称',	'title',	'text',	'',	0,	0,	1,	0,	0,	0,	1,	'',	'公司/店铺名称',	1,	0,	''),
(167,	5,	17,	'户型',	'huxing',	'radio',	'别墅|复式|三室两厅|三室一厅|两室一厅|一室户|车库|店铺|办公|写字楼|厂房|其他',	0,	0,	0,	0,	0,	0,	3,	'',	'户型',	0,	0,	''),
(165,	5,	17,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'手机号码',	0,	0,	''),
(166,	5,	17,	'求购描述',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	4,	'',	'求购描述',	2,	0,	''),
(164,	5,	17,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'联系人',	0,	0,	''),
(323,	5,	28,	'类型',	'huxing',	'radio',	'厂房|产业|资产|设备|店铺|办公室|代理|授权|专利|经营许可证|其他',	0,	0,	1,	0,	0,	0,	3,	'',	'类型',	0,	0,	''),
(161,	5,	17,	'求购标题',	'title',	'text',	'',	0,	0,	1,	0,	0,	0,	1,	'',	'',	1,	0,	''),
(162,	5,	17,	'期望价格',	'price',	'radio',	'10万内|10-30万|30-50万|50-100万|100万以上',	0,	0,	0,	0,	0,	0,	2,	'',	'期望价格',	0,	0,	''),
(139,	5,	15,	'学历',	'xueli',	'radio',	'小学|初中|高中|中专|大专|本科|研究生|博士|博士以上',	0,	0,	0,	0,	0,	0,	4,	'',	'学历',	0,	0,	''),
(140,	5,	15,	'工作地点',	'workaddress',	'text',	'',	0,	0,	0,	0,	0,	0,	3,	'',	'工作地点',	0,	0,	''),
(141,	5,	15,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	10,	'',	'联系人',	0,	0,	''),
(142,	5,	15,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	10,	'',	'手机号码',	0,	0,	''),
(143,	5,	15,	'性别',	'sex',	'radio',	'男|女',	0,	0,	1,	0,	0,	0,	5,	'男',	'性别',	0,	0,	''),
(147,	5,	15,	'',	'shuxing',	'checkbox',	'沟通能力|学习能力|执行能力|有亲和力|有责任心|能吃苦|开朗健谈|创业经历|沉稳内敛|人脉广泛',	0,	0,	0,	0,	0,	0,	8,	'',	'',	0,	0,	''),
(145,	5,	15,	'自我评价',	'pingjia',	'longtext',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'自我评价',	2,	0,	''),
(146,	5,	15,	'年龄',	'age',	'number',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'年龄',	0,	0,	''),
(148,	5,	15,	'添加照片',	'zhaop',	'images',	'',	0,	0,	0,	0,	0,	0,	9,	'',	'',	0,	0,	''),
(201,	5,	19,	'面积',	'areas',	'text',	'',	0,	0,	0,	0,	0,	0,	4,	'',	'面积',	0,	0,	'平米'),
(198,	5,	19,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	12,	'',	'手机号码',	0,	0,	''),
(199,	5,	19,	'房屋描述',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'房屋描述',	2,	0,	''),
(200,	5,	19,	'类型',	'huxing',	'radio',	'复式|三室两厅|三室一厅|两室一厅|一室户|车库|别墅|商铺|写字楼|厂房|其他',	0,	0,	1,	0,	0,	0,	3,	'',	'类型',	0,	0,	''),
(160,	5,	15,	'期望职位',	'qiwangzw',	'radio',	'坐办公室|跑业务|闷头苦干|司机|市场开拓',	0,	0,	0,	0,	0,	0,	1,	'',	'期望职位',	0,	0,	''),
(202,	5,	19,	'所在小区/商圈',	'xiaoqu',	'text',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'所在小区/商圈',	0,	0,	''),
(339,	5,	29,	'',	'fenlei',	'checkbox',	'保修期内|全新未用|专柜正品|验货面付|有发票|快递包邮',	0,	0,	1,	0,	0,	0,	7,	'',	'',	0,	0,	''),
(335,	5,	29,	'新旧程度',	'yuanjia',	'radio',	'95成新|9成新|8成新|7成新|6成新|5成新及以下',	0,	0,	1,	0,	0,	0,	5,	'',	'新旧程度',	0,	0,	''),
(330,	5,	28,	'行业',	'zhuangxiu',	'select',	'酒店餐饮|娱乐休闲|零售百货|生活服务|电子通讯|汽车美容|医药保健|教育培训|公司工程',	0,	0,	0,	0,	0,	0,	4,	'',	'行业',	0,	0,	''),
(334,	5,	29,	'价格',	'price',	'text',	'',	0,	0,	1,	0,	0,	0,	4,	'',	'价格',	0,	0,	''),
(192,	5,	14,	'性质',	'xingzhi',	'radio',	'全职|兼职',	0,	0,	1,	0,	0,	0,	2,	'全职',	'',	0,	0,	''),
(322,	5,	28,	'情况描述',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	5,	'',	'情况描述',	2,	0,	''),
(321,	5,	28,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'手机号码',	0,	0,	''),
(195,	5,	19,	'租金',	'price',	'number',	'',	0,	0,	1,	0,	0,	0,	5,	'0',	'租金',	0,	0,	'元/月'),
(196,	5,	19,	'房屋图片',	'thumbs',	'images',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'房屋图片',	0,	0,	''),
(197,	5,	19,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	11,	'',	'联系人',	0,	0,	''),
(191,	5,	17,	'',	'paytype',	'checkbox',	'中介勿扰|全款买房|学校附近|交通便利|双证齐全|电梯房|三楼以上|七楼以上|不要一楼',	0,	0,	0,	0,	0,	0,	5,	'',	'',	0,	0,	''),
(209,	5,	20,	'售房标题',	'title',	'text',	'',	0,	0,	1,	0,	0,	0,	1,	'',	'售房标题',	1,	0,	''),
(205,	5,	19,	'租赁方式',	'paytype',	'checkbox',	'押一付一|押一付二|押一付三|最短半年|最短一年|面议',	0,	0,	1,	0,	0,	0,	6,	'押一付三',	'租赁方式',	0,	0,	''),
(207,	5,	19,	'',	'zhuangxiu',	'checkbox',	'随时看房|中介勿扰|交通便利|拎包入驻|精装修|简单装修|南向|电梯房|靠近学校',	0,	0,	0,	0,	0,	0,	8,	'',	'',	0,	0,	''),
(208,	5,	19,	'房屋配置',	'peizhi',	'checkbox',	'床|衣柜|沙发|电视|空调|冰箱|洗衣机|wifi',	0,	0,	0,	0,	0,	0,	10,	'',	'房屋配置',	0,	0,	''),
(210,	5,	20,	'售价',	'price',	'text',	'',	0,	0,	1,	0,	0,	0,	5,	'0',	'售价',	0,	0,	'万元'),
(211,	5,	20,	'房屋图片',	'thumbs',	'images',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'房屋图片',	0,	0,	''),
(212,	5,	20,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	10,	'',	'联系人',	0,	0,	''),
(213,	5,	20,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	11,	'',	'手机号码',	0,	0,	''),
(214,	5,	20,	'房屋描述',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'房屋描述',	2,	0,	''),
(215,	5,	20,	'类型',	'huxing',	'radio',	'复式|三室两厅|三室一厅|两室一厅|一室户|车库|别墅|商铺|写字楼|厂房|其他',	0,	0,	1,	0,	0,	0,	3,	'',	'类型',	0,	0,	''),
(216,	5,	20,	'面积',	'areas',	'text',	'',	0,	0,	0,	0,	0,	0,	4,	'',	'面积',	0,	0,	'平米'),
(217,	5,	20,	'所在小区/商圈',	'xiaoqu',	'text',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'所在小区/商圈',	0,	0,	''),
(226,	5,	21,	'货物照片',	'thumbs',	'images',	'',	0,	0,	0,	0,	0,	0,	9,	'',	'货物照片',	0,	0,	''),
(222,	5,	20,	'装修情况',	'zhuangxiu',	'radio',	'精装修|简单装修|毛坯房',	0,	0,	0,	0,	0,	0,	6,	'',	'装修情况',	0,	0,	''),
(223,	5,	20,	'',	'peizhi',	'checkbox',	'随时看房|中介勿扰|交通便利|拎包入驻|南向|电梯房|公园附近|靠近学校|物流园|带车位|家具赠送|电器赠送',	0,	0,	0,	0,	0,	0,	8,	'',	'',	0,	0,	''),
(224,	5,	21,	'货物名称',	'title',	'text',	'',	0,	0,	1,	0,	0,	0,	1,	'',	'货物名称',	1,	0,	''),
(225,	5,	21,	'预期费用',	'price',	'text',	'',	0,	0,	1,	0,	0,	0,	3,	'0',	'预期费用',	0,	0,	'元'),
(227,	5,	21,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	10,	'',	'联系人',	0,	0,	''),
(228,	5,	21,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	11,	'',	'手机号码',	0,	0,	''),
(229,	5,	21,	'详细描述',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'详细描述',	2,	0,	''),
(230,	5,	21,	'出发地点',	'cfcity',	'text',	'',	0,	0,	1,	0,	0,	0,	4,	'',	'起点',	0,	0,	''),
(231,	5,	21,	'目的地',	'ddcity',	'text',	'',	0,	0,	1,	0,	0,	0,	5,	'',	'终点',	0,	0,	''),
(232,	5,	21,	'出行时间',	'gotime',	'datetime',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'出行时间',	0,	0,	''),
(233,	5,	21,	'货物重量',	'weizi',	'radio',	'0-5公斤|5-20公斤|20-100公斤|100-500公斤|0.5-1吨|1-5吨|5-10吨|10吨以上',	0,	0,	1,	0,	0,	0,	2,	'',	'货物重量',	0,	0,	''),
(234,	5,	21,	'拼车类型',	'type',	'radio',	'顺带|拼货|整车租赁',	0,	0,	1,	0,	0,	0,	8,	'',	'拼车类型',	0,	0,	''),
(235,	5,	22,	'车辆类型',	'title',	'radio',	'面包车|班车|小型货车|中型货车|大型货车|牵引车',	0,	0,	1,	0,	0,	0,	1,	'',	'',	1,	0,	''),
(236,	5,	22,	'载重',	'price',	'radio',	'0-100公斤|100-500公斤|0.5-1吨|1-5吨|5-10吨|10吨以上',	0,	0,	1,	0,	0,	0,	2,	'',	'载重',	0,	0,	''),
(237,	5,	22,	'车辆照片',	'thumbs',	'images',	'',	0,	0,	1,	0,	0,	0,	8,	'',	'车辆照片',	0,	0,	''),
(238,	5,	22,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'联系人',	0,	0,	''),
(239,	5,	22,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	10,	'',	'手机号码',	0,	0,	''),
(240,	5,	22,	'详细描述（途径）',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'详细描述',	2,	0,	''),
(241,	5,	22,	'出发地点',	'cfcity',	'text',	'',	0,	0,	0,	0,	0,	0,	3,	'',	'起点',	0,	0,	''),
(242,	5,	22,	'目的地',	'ddcity',	'text',	'',	0,	0,	0,	0,	0,	0,	4,	'',	'终点',	0,	0,	''),
(243,	5,	22,	'出行时间',	'gotime',	'datetime',	'',	0,	0,	0,	0,	0,	0,	5,	'',	'出行时间',	0,	0,	''),
(245,	5,	22,	'',	'type',	'checkbox',	'带小货物|拼货|整车出租|长途|短途',	0,	0,	0,	0,	0,	0,	7,	'',	'',	0,	0,	''),
(246,	5,	23,	'车型',	'title',	'text',	'',	0,	0,	1,	0,	0,	0,	1,	'',	'车型',	1,	0,	''),
(247,	5,	23,	'费用',	'price',	'text',	'',	0,	0,	1,	0,	0,	0,	3,	'',	'费用',	0,	0,	'元'),
(248,	5,	23,	'车辆照片',	'thumbs',	'images',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'车辆照片',	0,	0,	''),
(249,	5,	23,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	10,	'',	'联系人',	0,	0,	''),
(250,	5,	23,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	11,	'',	'手机号码',	0,	0,	''),
(251,	5,	23,	'详细描述（途径）',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'详细描述',	2,	0,	''),
(252,	5,	23,	'出发地点',	'cfcity',	'text',	'',	0,	0,	0,	0,	0,	0,	4,	'',	'起点',	0,	0,	''),
(253,	5,	23,	'目的地',	'ddcity',	'text',	'',	0,	0,	0,	0,	0,	0,	5,	'',	'终点',	0,	0,	''),
(254,	5,	23,	'出行时间',	'gotime',	'datetime',	'',	0,	0,	0,	0,	0,	0,	6,	'',	'出行时间',	0,	0,	''),
(255,	5,	23,	'座位数',	'weizi',	'number',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'座位数',	0,	0,	'座'),
(256,	5,	23,	'',	'type',	'checkbox',	'免费|带小物件|顺风车|拼车|日租|月租|包车|短途|长途|女性免单|不带小孩',	0,	0,	0,	0,	0,	0,	8,	'',	'',	0,	0,	''),
(257,	5,	24,	'拼车标题',	'title',	'text',	'',	0,	0,	1,	0,	0,	0,	1,	'',	'拼车标题',	1,	0,	''),
(258,	5,	24,	'预期费用',	'price',	'text',	'',	0,	0,	1,	0,	0,	0,	4,	'',	'预期费用',	0,	0,	''),
(259,	5,	24,	'照片',	'thumbs',	'images',	'',	0,	0,	0,	0,	0,	0,	5,	'',	'',	0,	0,	''),
(260,	5,	24,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	11,	'',	'联系人',	0,	0,	''),
(261,	5,	24,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	12,	'',	'手机号码',	0,	0,	''),
(262,	5,	24,	'详细描述',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'详细描述',	2,	0,	''),
(263,	5,	24,	'出发地点',	'cfcity',	'text',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'起点',	0,	0,	''),
(264,	5,	24,	'目的地',	'ddcity',	'text',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'终点',	0,	0,	''),
(265,	5,	24,	'出行时间',	'gotime',	'datetime',	'',	0,	0,	1,	0,	0,	0,	8,	'',	'出行时间',	0,	0,	''),
(266,	5,	24,	'需要座位数',	'weizi',	'number',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'需要座位数',	0,	0,	'位'),
(267,	5,	24,	'',	'type1',	'checkbox',	'求带|拼车|顺风车|包车|出油费|送到家门口|有小孩',	0,	0,	0,	0,	0,	0,	10,	'',	'',	0,	0,	''),
(268,	5,	24,	'性别',	'type',	'radio',	'男|女|有男有女',	0,	0,	1,	0,	0,	0,	3,	'',	'性别',	0,	0,	''),
(338,	5,	29,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	10,	'',	'手机号码',	0,	0,	''),
(337,	5,	29,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'联系人',	0,	0,	''),
(336,	5,	29,	'详细描述',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'详细描述',	2,	0,	''),
(331,	5,	28,	'',	'peizhi',	'checkbox',	'低租金|营业中|可空转|靠近学校|物流园|设备齐全|接手营业|包技术|证件齐全|临街店铺',	0,	0,	0,	0,	0,	0,	6,	'',	'',	0,	0,	''),
(313,	5,	27,	'',	'paytype',	'checkbox',	'中介勿扰|全款买房|学校附近|交通便利|双证齐全|电梯房|三楼以上|七楼以上|不要一楼',	0,	0,	0,	0,	0,	0,	5,	'',	'',	0,	0,	''),
(320,	5,	28,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	8,	'',	'联系人',	0,	0,	''),
(317,	5,	28,	'转让标题',	'title',	'text',	'',	0,	0,	1,	0,	0,	0,	1,	'',	'转让标题',	1,	0,	''),
(318,	5,	28,	'一口价',	'price',	'text',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'一口价',	0,	0,	''),
(319,	5,	28,	'',	'thumbs',	'images',	'',	0,	0,	0,	0,	0,	0,	7,	'',	'',	0,	0,	''),
(302,	5,	27,	'求租标题',	'title',	'text',	'',	0,	0,	1,	0,	0,	0,	1,	'',	'求租标题',	1,	0,	''),
(307,	5,	27,	'求租描述',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	4,	'',	'求租描述',	2,	0,	''),
(308,	5,	27,	'户型',	'huxing',	'radio',	'别墅|复式|三室两厅|三室一厅|两室一厅|一室户|车库|店铺|办公|写字楼|厂房|其他',	0,	0,	0,	0,	0,	0,	3,	'',	'户型',	0,	0,	''),
(325,	5,	28,	'地址',	'xiaoqu',	'text',	'',	0,	0,	1,	0,	0,	0,	4,	'',	'地址',	0,	0,	''),
(333,	5,	29,	'二手物品图片',	'thumbs',	'images',	'',	0,	0,	0,	0,	0,	0,	8,	'',	'二手物品图片',	0,	0,	''),
(332,	5,	29,	'二手物品标题',	'title',	'text',	'',	0,	0,	1,	0,	0,	0,	1,	'',	'二手物品标题',	1,	0,	''),
(305,	5,	27,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'联系人',	0,	0,	''),
(306,	5,	27,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'手机号码',	0,	0,	''),
(303,	5,	27,	'期望租金',	'price',	'number',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'期望租金',	0,	0,	'元/月以内'),
(340,	5,	29,	'商品分类',	'shenfen',	'radio',	'电脑办公|家具家电|手机ipad|文体户外|服装配饰|儿童母婴|美容保健|数码产品|居家日常|自行车|电动车|票务|优惠券|其他',	0,	0,	0,	0,	0,	0,	2,	'',	'商品分类',	0,	0,	''),
(341,	5,	29,	'身份',	'type',	'radio',	'个人|商家',	0,	0,	1,	0,	0,	0,	3,	'',	'身份',	0,	0,	''),
(342,	2,	30,	'二手物品标题',	'title',	'text',	'',	0,	0,	1,	1,	12,	120,	1,	'',	'二手物品标题',	1,	0,	''),
(343,	2,	30,	'二手物品图片',	'thumbs',	'images',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'二手物品图片',	0,	0,	''),
(344,	2,	30,	'价格',	'price',	'radio',	'100元以下|100元以上',	0,	0,	1,	0,	0,	0,	3,	'',	'价格',	0,	0,	''),
(345,	2,	30,	'原价',	'yuanjia',	'text',	'',	0,	0,	1,	0,	0,	0,	4,	'',	'原价',	0,	0,	''),
(346,	2,	30,	'详细描述',	'des',	'longtext',	'',	0,	0,	1,	0,	0,	0,	5,	'',	'详细描述',	2,	0,	''),
(347,	2,	30,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	8,	'',	'联系人',	0,	0,	''),
(348,	2,	30,	'手机号码',	'shouji',	'telphone',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'手机号码',	0,	0,	''),
(349,	2,	30,	'交易类型',	'type',	'radio',	'个人|商家',	0,	0,	1,	0,	0,	0,	3,	'',	'交易类型',	0,	0,	''),
(362,	5,	33,	'年龄',	'age',	'text',	'',	0,	0,	1,	0,	0,	0,	4,	'',	'年龄',	0,	0,	''),
(363,	5,	33,	'个人照片',	'zhaopian',	'images',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'个人照片',	0,	0,	''),
(364,	5,	33,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	8,	'',	'联系人',	0,	0,	''),
(365,	5,	33,	'电话',	'dianhua',	'telphone',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'电话',	0,	0,	''),
(366,	5,	33,	'性别',	'sex',	'radio',	'男|女',	0,	0,	1,	0,	0,	0,	9,	'',	'性别',	0,	0,	''),
(367,	5,	34,	'个人说明',	'jyxuqiu',	'longtext',	'',	0,	0,	1,	0,	0,	0,	7,	'',	'个人说明',	2,	0,	''),
(368,	5,	34,	'体重',	'weight',	'number',	'',	0,	0,	1,	0,	0,	0,	6,	'',	'体重',	0,	0,	''),
(369,	5,	34,	'身高',	'height',	'number',	'',	0,	0,	1,	0,	0,	0,	5,	'',	'身高',	0,	0,	''),
(370,	5,	34,	'标题',	'title',	'text',	'',	0,	0,	1,	1,	10,	100,	1,	'',	'标题',	1,	0,	''),
(371,	5,	34,	'年龄',	'age',	'text',	'',	0,	0,	1,	0,	0,	0,	4,	'',	'年龄',	0,	0,	''),
(372,	5,	34,	'个人照片',	'zhaopian',	'images',	'',	0,	0,	1,	0,	0,	0,	2,	'',	'个人照片',	0,	0,	''),
(373,	5,	34,	'联系人',	'lianxiren',	'text',	'',	0,	0,	1,	0,	0,	0,	8,	'',	'联系人',	0,	0,	''),
(374,	5,	34,	'电话',	'dianhua',	'telphone',	'',	0,	0,	1,	0,	0,	0,	9,	'',	'电话',	0,	0,	''),
(375,	5,	34,	'性别',	'sex',	'radio',	'男|女',	0,	0,	1,	0,	0,	0,	9,	'',	'性别',	0,	0,	'');

DROP TABLE IF EXISTS `ims_mihua_sq_footmark`;
CREATE TABLE `ims_mihua_sq_footmark` (
  `foot_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `content` text COMMENT '只存5条浏览记录|type:1频道 2商品|id：类目id|name:类目名称',
  PRIMARY KEY (`foot_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_get_redpackage`;
CREATE TABLE `ims_mihua_sq_get_redpackage` (
  `get_id` int(11) NOT NULL AUTO_INCREMENT,
  `red_id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) NOT NULL DEFAULT '',
  `get_amount` decimal(10,2) NOT NULL COMMENT '领取金额',
  `create_time` int(11) NOT NULL COMMENT '该记录创建时间',
  PRIMARY KEY (`get_id`),
  UNIQUE KEY `id` (`get_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_goods`;
CREATE TABLE `ims_mihua_sq_goods` (
  `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `xsthumb` varchar(255) DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `productprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `costprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` int(10) NOT NULL DEFAULT '0',
  `sales` int(10) NOT NULL DEFAULT '0',
  `spec` varchar(5000) NOT NULL,
  `createtime` int(10) NOT NULL,
  `maxbuy` int(11) DEFAULT '0',
  `hasoption` int(11) DEFAULT '0',
  `thumb_url` text,
  `astrict` int(11) DEFAULT NULL COMMENT '限购',
  `isfirstcut` decimal(10,2) DEFAULT NULL COMMENT '首单优惠',
  `credit` decimal(10,2) DEFAULT NULL COMMENT '积分抵扣',
  `is_hot` int(11) DEFAULT '0' COMMENT '1首页推荐0不推荐',
  `is_time` tinyint(1) DEFAULT '0' COMMENT '1参与秒杀',
  `time_id` int(10) DEFAULT '0' COMMENT '秒杀专场id',
  `datestart` int(11) DEFAULT '0',
  `dateend` int(11) DEFAULT '0',
  `time_num` int(11) DEFAULT '0',
  `time_money` decimal(10,2) DEFAULT NULL COMMENT '秒杀价格',
  `is_group` tinyint(3) DEFAULT NULL COMMENT '1开启团购',
  `groupprice` decimal(10,2) DEFAULT NULL COMMENT '团购价',
  `groupnum` int(10) DEFAULT NULL COMMENT '团购人数',
  `groupendtime` decimal(10,2) DEFAULT NULL COMMENT '团购时间',
  `isshowgroup` tinyint(3) DEFAULT NULL COMMENT '1显示最近团0不显示',
  `group_num` int(11) DEFAULT '0',
  `inco` text COMMENT '商品标签',
  `share_img` varchar(255) DEFAULT NULL,
  `share_title` varchar(100) DEFAULT NULL,
  `share_info` varchar(255) DEFAULT NULL,
  `orderby` tinyint(3) DEFAULT '100' COMMENT 'desc排序',
  `status` tinyint(1) DEFAULT '0' COMMENT '0上架 1下架',
  `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减',
  `commission` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '该订单的推荐佣金',
  `commission2` decimal(10,2) unsigned DEFAULT '0.00',
  `commission3` decimal(10,2) unsigned DEFAULT '0.00',
  `iscanrefund` tinyint(3) DEFAULT NULL COMMENT '0支持退款 1不支持退款',
  `goods_cate` text COMMENT '商品分类，默认为0全部',
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_goods_cate`;
CREATE TABLE `ims_mihua_sq_goods_cate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) DEFAULT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `cate_url` varchar(250) DEFAULT NULL,
  `shop_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_group`;
CREATE TABLE `ims_mihua_sq_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `gid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `fullnumber` int(11) DEFAULT NULL,
  `lastnumber` int(11) DEFAULT NULL,
  `overtime` int(11) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  `createrid` int(11) DEFAULT NULL,
  `finishtime` int(11) DEFAULT NULL,
  `isrefund` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_info`;
CREATE TABLE `ims_mihua_sq_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `mid` smallint(6) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `nickname` varchar(30) NOT NULL,
  `avatar` varchar(155) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `district` varchar(30) NOT NULL,
  `content` text NOT NULL,
  `createtime` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0未审核,1已审核通过,2审核不通过',
  `lng` varchar(30) NOT NULL,
  `lat` varchar(30) NOT NULL,
  `module` smallint(6) NOT NULL,
  `isneedpay` tinyint(1) NOT NULL,
  `haspay` tinyint(1) NOT NULL,
  `isding` tinyint(1) NOT NULL,
  `dingtime` int(11) NOT NULL,
  `fmid` smallint(6) NOT NULL,
  `freshtime` int(11) NOT NULL,
  `admin_msg` tinyint(1) NOT NULL COMMENT '1已发通知给管理员',
  `msg` tinyint(1) NOT NULL COMMENT '1已发通知给用户',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_infoorder`;
CREATE TABLE `ims_mihua_sq_infoorder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `transid` varchar(30) NOT NULL DEFAULT '0',
  `paydetail` varchar(255) NOT NULL,
  `paytype` tinyint(1) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `message_id` int(11) NOT NULL,
  `module` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_info_comment`;
CREATE TABLE `ims_mihua_sq_info_comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `info_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(100) NOT NULL,
  `info` varchar(255) DEFAULT NULL,
  `addtime` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_integral`;
CREATE TABLE `ims_mihua_sq_integral` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `num` smallint(6) NOT NULL,
  `time` int(11) NOT NULL,
  `explain` varchar(50) NOT NULL,
  `type` tinyint(1) DEFAULT '0' COMMENT '0奖励，1扣除',
  `message_id` int(11) NOT NULL,
  `status` tinyint(3) unsigned DEFAULT '0' COMMENT '0处理 ，1已处理',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_member`;
CREATE TABLE `ims_mihua_sq_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `createtime` int(11) NOT NULL,
  `telphone` varchar(20) NOT NULL,
  `pwd` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `idcard` char(18) NOT NULL,
  `realname` varchar(30) NOT NULL,
  `hasrealname` tinyint(1) NOT NULL,
  `hasidcard` tinyint(1) NOT NULL,
  `gender` varchar(10) NOT NULL COMMENT '0男，1女',
  `isagent` tinyint(1) NOT NULL,
  `expirationtime` int(11) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `district` varchar(50) NOT NULL,
  `logintime` int(11) DEFAULT NULL,
  `fmid` smallint(6) NOT NULL,
  `mid` smallint(6) NOT NULL,
  `shareid` int(11) DEFAULT NULL,
  `flag` tinyint(1) DEFAULT '0' COMMENT '0为会推广人，1为推广人',
  `flagtime` int(10) DEFAULT NULL COMMENT '为成推广人的时间',
  `mobile` varchar(11) DEFAULT NULL,
  `commission` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '已结佣佣金',
  `zhifu` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '已打款佣金',
  `uid` int(11) DEFAULT NULL,
  `shop_id` int(11) DEFAULT NULL COMMENT '扫的是哪家店铺二维码关注',
  `unionId` varchar(150) DEFAULT NULL COMMENT '用于与小程序标识是否为同一个用户',
  `wxapp` tinyint(1) DEFAULT '0' COMMENT '1微信小程序用户',
  `balance` decimal(10,2) DEFAULT '0.00' COMMENT '可提现余额',
  `msg_id_str` text COMMENT '公告ID',
  `openid_wxapp` varchar(100) DEFAULT '0' COMMENT '小程序openid',
  `lng` varchar(30) DEFAULT NULL,
  `lat` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_msg`;
CREATE TABLE `ims_mihua_sq_msg` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `msg_title` tinytext,
  `msg_content` text,
  `status` tinyint(1) DEFAULT '1',
  `addtime` int(11) DEFAULT NULL,
  `add_time` int(11) NOT NULL DEFAULT '0',
  `uniacid` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1' COMMENT '1用户通知 2商家通知',
  PRIMARY KEY (`msg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_mstime`;
CREATE TABLE `ims_mihua_sq_mstime` (
  `time_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `timestart` int(11) DEFAULT '0',
  `timeend` int(11) DEFAULT '0',
  PRIMARY KEY (`time_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_option`;
CREATE TABLE `ims_mihua_sq_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  `virtual` int(11) DEFAULT '0',
  `groupprice` decimal(10,2) DEFAULT NULL COMMENT '团购价',
  `time_money` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_order`;
CREATE TABLE `ims_mihua_sq_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(11) DEFAULT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `gid` int(11) DEFAULT NULL,
  `openid` varchar(100) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0普通状态，1为已付款，2为已发货，3为成功，4,退款中，6已取消，7已退款',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2微信支付，3支付宝，4银行版收银台,5货到付款',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `createtime` int(10) unsigned NOT NULL,
  `paytime` int(11) DEFAULT '0',
  `finishtime` int(11) DEFAULT '0',
  `verifytime` int(11) DEFAULT '0',
  `admin_remark` varchar(1000) DEFAULT NULL COMMENT '用户不可见备注',
  `groupid` int(11) DEFAULT NULL COMMENT '拼团id',
  `ordertype` tinyint(3) DEFAULT '1' COMMENT '1单独订单，2参团订单，3建团订单 4秒杀订单 5优惠买单',
  `cardid` int(11) DEFAULT '0',
  `cardcutmoney` decimal(10,2) DEFAULT NULL,
  `iscomplete` tinyint(3) DEFAULT NULL COMMENT '0未完成,1已完成',
  `refundstatus` tinyint(3) DEFAULT '0',
  `canceltime` int(11) DEFAULT '0',
  `refundtime` int(11) DEFAULT '0',
  `refundmoney` varchar(10) DEFAULT NULL,
  `addressid` int(11) DEFAULT NULL,
  `gmessage` tinyint(3) DEFAULT '0' COMMENT '0未发失败团消息,1已发',
  `userid` int(11) DEFAULT NULL COMMENT '关联mihua_sq_member表id',
  `ded_money` decimal(10,2) DEFAULT '0.00',
  `deductible` int(11) DEFAULT '0',
  `m_status` tinyint(3) DEFAULT '0' COMMENT '是否已发送消息0未发 1已发',
  `shareid` int(10) unsigned DEFAULT '0' COMMENT '推荐人ID',
  `shareid2` int(10) DEFAULT '0' COMMENT '2级代理id',
  `shareid3` int(10) DEFAULT '0' COMMENT '3级代理id',
  `xscut` decimal(10,2) DEFAULT NULL COMMENT '限时抢购减去的金额',
  `firstcut` decimal(10,2) DEFAULT NULL COMMENT '首单优惠减去的金额',
  `isremind` tinyint(3) DEFAULT '0' COMMENT ' 1已自动处理',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_order_goods`;
CREATE TABLE `ims_mihua_sq_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `shop_id` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `content` text,
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `optionid` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `optionname` text,
  `verifyopenid` varchar(255) DEFAULT NULL COMMENT '核销员',
  `qr_code` text COMMENT '核销二维码',
  `commission` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '该订单的推荐佣金',
  `commission2` decimal(10,2) unsigned DEFAULT '0.00',
  `commission3` decimal(10,2) unsigned DEFAULT '0.00',
  `order_status` tinyint(3) DEFAULT NULL COMMENT '商品订单状态，0正常状态 1已支付  2申请中 3审核通过 4提交退货信息 5收到退货 6退款中 7已退款 8审核不通过',
  `refund_id` int(10) DEFAULT NULL COMMENT '退款表',
  `isverify` tinyint(3) DEFAULT '0' COMMENT '1不支持线下核销，2支持',
  `verified` tinyint(3) DEFAULT '0' COMMENT '订单是否已核销',
  `verifytime` int(11) DEFAULT '0',
  `iscopy` tinyint(3) DEFAULT '0' COMMENT '0未同步资金表，1已同步',
  `iscomplete` tinyint(3) DEFAULT NULL COMMENT '0未完成,1已完成',
  `qr_code_str` varchar(100) DEFAULT NULL COMMENT '核销码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_order_record`;
CREATE TABLE `ims_mihua_sq_order_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `ordersn` varchar(20) NOT NULL COMMENT '对应order表ordersn',
  `price` float(10,2) DEFAULT '0.00',
  `ogid` int(11) DEFAULT '0',
  `shop_id` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `iscopy` tinyint(3) DEFAULT '0' COMMENT '0未同步到余额，1已同步',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_param`;
CREATE TABLE `ims_mihua_sq_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_qiandao`;
CREATE TABLE `ims_mihua_sq_qiandao` (
  `qiandao_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `timestr` char(12) DEFAULT NULL,
  PRIMARY KEY (`qiandao_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_redmsg`;
CREATE TABLE `ims_mihua_sq_redmsg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `red_id` int(10) DEFAULT NULL,
  `uniacid` int(11) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `status` tinyint(2) DEFAULT '0' COMMENT '0未发送 1已发送',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_redpackage`;
CREATE TABLE `ims_mihua_sq_redpackage` (
  `red_id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL DEFAULT '',
  `uniacid` int(11) DEFAULT NULL,
  `mid` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `distance` int(11) DEFAULT NULL,
  `lng` varchar(15) DEFAULT '',
  `lat` varchar(15) DEFAULT '',
  `model` tinyint(1) DEFAULT '1' COMMENT '模型(1:普通红包，2：口令红包)',
  `total_amount` decimal(10,2) NOT NULL COMMENT '发出总金额',
  `total_num` int(11) NOT NULL COMMENT '发出总个数',
  `send_num` int(11) DEFAULT '0' COMMENT '发出总个数',
  `allocation_way` int(1) DEFAULT '0' COMMENT '分钱方式：0随机分配，1平均分配',
  `rob_plan` mediumtext NOT NULL COMMENT '红包分配方案',
  `total_pay` decimal(10,2) DEFAULT NULL COMMENT '应付总额',
  `ordersn` varchar(30) DEFAULT NULL COMMENT '订单号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:未生效（未付款），1：有效，2：已经抢完，3：待审核，4：审核不通过，5：审核不通过并退款',
  `content` mediumtext COMMENT '内容',
  `xsthumb` text COMMENT '图片',
  `create_time` int(11) NOT NULL COMMENT '该记录创建时间',
  `kouling` varchar(255) NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`red_id`),
  UNIQUE KEY `id` (`red_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_refund`;
CREATE TABLE `ims_mihua_sq_refund` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `shop_id` int(11) DEFAULT '0',
  `from_user` varchar(50) NOT NULL,
  `goodsid` int(11) NOT NULL,
  `ogid` int(10) DEFAULT NULL,
  `applytime` int(10) DEFAULT NULL COMMENT '申请退款时间',
  `resulttime` int(10) DEFAULT NULL COMMENT '处理申请时间',
  `remark` varchar(200) DEFAULT NULL COMMENT '审核说明',
  `money` decimal(10,2) DEFAULT '0.00',
  `type` tinyint(1) DEFAULT NULL COMMENT '1退货退款 2仅退款',
  `resean` text COMMENT '退款原因',
  `ordersn` varchar(20) NOT NULL,
  `expresscom` varchar(30) DEFAULT NULL COMMENT '快递公司val',
  `expresssn` varchar(50) DEFAULT NULL COMMENT '快递单号',
  `express` varchar(50) DEFAULT NULL COMMENT '快递公司text',
  `expresstime` int(10) DEFAULT NULL COMMENT '提交物流时间',
  `addressid` int(11) DEFAULT NULL COMMENT '退款地址',
  `refundtime` int(11) DEFAULT '0',
  `back_remark` varchar(200) DEFAULT NULL COMMENT '退款说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_ring`;
CREATE TABLE `ims_mihua_sq_ring` (
  `ring_id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `openid` varchar(100) NOT NULL,
  `mid` int(11) DEFAULT NULL,
  `addtime` int(11) unsigned DEFAULT '0',
  `info` varchar(1000) DEFAULT NULL,
  `lng` varchar(15) DEFAULT NULL,
  `lat` varchar(15) DEFAULT NULL,
  `xsthumb` varchar(255) DEFAULT '',
  PRIMARY KEY (`ring_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_ring_zan`;
CREATE TABLE `ims_mihua_sq_ring_zan` (
  `zan_id` int(11) NOT NULL AUTO_INCREMENT,
  `ring_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(100) NOT NULL,
  `zan_type` tinyint(1) DEFAULT NULL COMMENT '1点赞，2评论，3关注',
  `info` varchar(255) DEFAULT NULL,
  `addtime` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`zan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_sensitiveword`;
CREATE TABLE `ims_mihua_sq_sensitiveword` (
  `word_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `sensitiveword` text,
  `replace` text,
  `type` tinyint(1) DEFAULT '0' COMMENT '0敏感字  1协议',
  `contract` text,
  PRIMARY KEY (`word_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_share_history`;
CREATE TABLE `ims_mihua_sq_share_history` (
  `sharemid` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `joinway` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0默认驱动加入,1二维码加入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_shop`;
CREATE TABLE `ims_mihua_sq_shop` (
  `shop_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `shop_name` varchar(64) DEFAULT NULL,
  `city_id` int(11) DEFAULT '0',
  `area_id` int(11) DEFAULT '0',
  `business_id` int(11) DEFAULT '0',
  `pcate_id` int(11) DEFAULT NULL,
  `ccate_id` int(11) DEFAULT NULL,
  `openid` int(11) DEFAULT NULL COMMENT '申请人',
  `lng` varchar(32) DEFAULT NULL,
  `lat` varchar(32) DEFAULT NULL,
  `is_open` tinyint(1) DEFAULT '0' COMMENT '1代表营业中',
  `fan_money` int(10) DEFAULT NULL,
  `is_new` tinyint(1) DEFAULT NULL,
  `dp` decimal(10,2) DEFAULT NULL COMMENT '点评满分5分',
  `month_num` int(10) DEFAULT NULL,
  `rate` int(11) DEFAULT '0' COMMENT '费率 每个商品的结算价格',
  `orderby` tinyint(3) DEFAULT '100' COMMENT '数字越小排序越高',
  `status` tinyint(3) unsigned DEFAULT '0' COMMENT '0未审核1成功入驻2未通过3暂停中',
  `logo` varchar(255) DEFAULT '',
  `shop_cert` varchar(255) DEFAULT '',
  `bgpic` varchar(255) DEFAULT '',
  `intro` varchar(1024) DEFAULT NULL,
  `telphone` varchar(20) DEFAULT NULL,
  `manage` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT '',
  `inco` text COMMENT '店铺标签',
  `starttime` int(11) unsigned DEFAULT NULL COMMENT '入驻时间',
  `is_hot` tinyint(1) DEFAULT '0' COMMENT '1已开通首页推荐',
  `is_group` tinyint(1) DEFAULT '0' COMMENT '1已开通拼团',
  `is_discount` tinyint(1) DEFAULT '0' COMMENT '1已开通优惠买单',
  `is_time` tinyint(1) DEFAULT '0',
  `opendtime` int(11) unsigned DEFAULT NULL COMMENT '开店时间',
  `closetime` int(11) unsigned DEFAULT NULL COMMENT '打烊时间',
  `renjun` decimal(10,2) DEFAULT NULL COMMENT '人均消费',
  `balance` decimal(10,2) DEFAULT '0.00' COMMENT '余额',
  `mid` int(11) DEFAULT NULL,
  `qr_code` text COMMENT '商户二维码',
  `address_detail` varchar(255) DEFAULT NULL,
  `endtime` int(11) unsigned DEFAULT NULL COMMENT '到期时间',
  PRIMARY KEY (`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_shopinorder`;
CREATE TABLE `ims_mihua_sq_shopinorder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `transid` varchar(30) NOT NULL DEFAULT '0',
  `paydetail` varchar(255) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_shop_admin`;
CREATE TABLE `ims_mihua_sq_shop_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `admin_name` varchar(100) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `passport` varchar(50) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `openid` varchar(100) NOT NULL,
  `addtime` int(11) DEFAULT '0',
  `admin_type` tinyint(1) DEFAULT NULL COMMENT '1超级管理员：全部权限，2操作员：商品管理、订单管理、核销订单，3核销员：仅能核销订单',
  `status` tinyint(1) DEFAULT '0' COMMENT '0开启  1暂停',
  `msg_flag` tinyint(1) DEFAULT '0' COMMENT '0=>发送通知,1=>不发送通知',
  `mobile` char(15) DEFAULT NULL,
  `salt` char(20) DEFAULT NULL,
  `admin_uid` int(11) DEFAULT NULL,
  `msg_id_str` text COMMENT '公告ID',
  `customer` tinyint(1) DEFAULT '0' COMMENT '0非客服  1客服',
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_shop_apply`;
CREATE TABLE `ims_mihua_sq_shop_apply` (
  `aplly_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) unsigned NOT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `applytime` int(11) unsigned DEFAULT NULL COMMENT '申请时间',
  `f_type` tinyint(3) DEFAULT '0' COMMENT '0无申请 1入驻申请，2首页推荐，3秒杀，4拼团，5优惠买单',
  `mid` int(11) DEFAULT NULL,
  PRIMARY KEY (`aplly_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_shop_cate`;
CREATE TABLE `ims_mihua_sq_shop_cate` (
  `cate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `cate_name` varchar(32) DEFAULT NULL,
  `parent_id` int(11) DEFAULT '0',
  `orderby` tinyint(3) DEFAULT '100',
  `is_hot` tinyint(1) DEFAULT '0',
  `title` varchar(128) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT '',
  `cate_url` varchar(255) DEFAULT '',
  `cate_type` tinyint(1) DEFAULT '0' COMMENT '0商铺消费类,1酒店预订,2影院订座,3外卖点餐,4微商城',
  PRIMARY KEY (`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_shop_cfg`;
CREATE TABLE `ims_mihua_sq_shop_cfg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `shop_id` int(11) DEFAULT '0',
  `cfg` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_shop_renew`;
CREATE TABLE `ims_mihua_sq_shop_renew` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `price` float(10,2) DEFAULT '0.00',
  `shop_id` int(11) DEFAULT '0',
  `mid` int(11) DEFAULT '0' COMMENT '对应member表id',
  `createtime` int(11) DEFAULT '0',
  `starttime` int(11) DEFAULT '0' COMMENT '续费开始日期',
  `endtime` int(11) DEFAULT '0' COMMENT '续费到期日期',
  `type` tinyint(3) DEFAULT NULL COMMENT '1续1年，2续2年，3续3年',
  `ordersn` varchar(20) DEFAULT NULL,
  `status` tinyint(2) DEFAULT '0' COMMENT '0未支付1已支付',
  `paytype` tinyint(1) DEFAULT NULL COMMENT '1为余额，2微信支付，3支付宝，4银行版收银台,5货到付款',
  `flag` tinyint(2) DEFAULT NULL COMMENT '0入驻 1续费',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_shop_user`;
CREATE TABLE `ims_mihua_sq_shop_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `mid` int(11) DEFAULT NULL COMMENT '扫过商户二维码的用户',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_spec`;
CREATE TABLE `ims_mihua_sq_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_spec_item`;
CREATE TABLE `ims_mihua_sq_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_specid` (`specid`),
  KEY `indx_show` (`show`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_topic`;
CREATE TABLE `ims_mihua_sq_topic` (
  `topic_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `starttime` int(11) unsigned DEFAULT NULL,
  `endtime` int(11) unsigned DEFAULT NULL,
  `topic_name` varchar(64) DEFAULT NULL,
  `intro` varchar(255) DEFAULT NULL,
  `city_id` smallint(5) DEFAULT '0',
  `area_id` smallint(5) DEFAULT '0',
  `thumb` varchar(255) DEFAULT '',
  PRIMARY KEY (`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_tpl`;
CREATE TABLE `ims_mihua_sq_tpl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tplbh` varchar(50) NOT NULL,
  `tpl_id` varchar(80) DEFAULT NULL,
  `tpl_title` varchar(20) DEFAULT NULL,
  `tpl_key` varchar(500) DEFAULT NULL,
  `tpl_example` varchar(500) DEFAULT NULL,
  `uniacid` int(5) DEFAULT NULL,
  `adv` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_userdiscount`;
CREATE TABLE `ims_mihua_sq_userdiscount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `cardid` int(11) DEFAULT '0',
  `userid` int(11) DEFAULT NULL,
  `openid` varchar(64) DEFAULT NULL,
  `status` tinyint(3) DEFAULT '0',
  `overtime` int(11) DEFAULT '0',
  `taketime` int(11) DEFAULT '0',
  `usetime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_user_setting`;
CREATE TABLE `ims_mihua_sq_user_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) DEFAULT NULL,
  `uniacid` int(11) NOT NULL,
  `red_msg` tinyint(1) DEFAULT '1' COMMENT '1发红包通知 2不发',
  `footer` tinyint(1) DEFAULT '0' COMMENT '0不记录足迹  1记录足迹',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_waitmessage`;
CREATE TABLE `ims_mihua_sq_waitmessage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `type` tinyint(3) DEFAULT NULL,
  `str` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_mihua_sq_zdorder`;
CREATE TABLE `ims_mihua_sq_zdorder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(40) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `transid` varchar(30) NOT NULL DEFAULT '0',
  `paydetail` varchar(255) NOT NULL,
  `paytype` tinyint(1) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `message_id` int(11) NOT NULL,
  `module` smallint(6) NOT NULL,
  `days` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;





sql;
$row = pdo_run($installSql);


