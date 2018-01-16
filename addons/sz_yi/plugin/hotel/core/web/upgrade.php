<?php
// 唐上美联佳网络科技有限公司(技术支持)
global $_W;

if (!defined('IN_IA')) {
	exit('Access Denied');
}

$result = pdo_fetchcolumn('select id from ' . tablename('sz_yi_plugin') . ' where identity=:identity', array(':identity' => 'hotel'));

if (empty($result)) {
	$displayorder_max = pdo_fetchcolumn('select max(displayorder) from ' . tablename('sz_yi_plugin'));
	$displayorder = $displayorder_max + 1;
	$sql = 'INSERT INTO ' . tablename('sz_yi_plugin') . ' (`displayorder`,`identity`,`name`,`version`,`author`,`status`,`category`) VALUES(' . $displayorder . ',\'hotel\',\'酒店插件\',\'1.0\',\'官方\',\'1\',\'biz\');';
	pdo_query($sql);
}

$sql = 'CREATE TABLE IF NOT EXISTS ' . tablename('sz_yi_hotel_room') . " (\n  `id` int(11) NOT NULL AUTO_INCREMENT,\n  `uniacid` int(11) DEFAULT '0',\n  `goodsid` int(11) DEFAULT '0',\n  `title` varchar(255) DEFAULT '',\n  `thumb` varchar(255) DEFAULT '',\n  `oprice` decimal(10,2) DEFAULT '0.00',\n  `cprice` decimal(10,2) DEFAULT '0.00',\n  `deposit`  decimal(10,2) DEFAULT '0.00',\n  PRIMARY KEY (`id`)\n) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='酒店房间表' AUTO_INCREMENT=1 ;\n\nCREATE TABLE IF NOT EXISTS " . tablename('sz_yi_hotel_room_price') . " (\n  `id` int(11) NOT NULL AUTO_INCREMENT,\n  `roomid` int(11) DEFAULT '0',\n  `roomdate` int(11) DEFAULT '0',\n  `thisdate` varchar(255) DEFAULT '',\n  `oprice` decimal(10,2) DEFAULT '0.00',\n  `cprice` decimal(10,2) DEFAULT '0.00',\n  `mprice` decimal(10,2) DEFAULT '0.00',\n  `num` varchar(255) DEFAULT '',\n  `status` int(11) DEFAULT '0',\n\n  PRIMARY KEY (`id`)\n) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='酒店房间价格表' AUTO_INCREMENT=1 ;\n\nCREATE TABLE IF NOT EXISTS " . tablename('sz_yi_order_room') . " (\n  `id` int(11) NOT NULL AUTO_INCREMENT,\n  `orderid` int(11) DEFAULT '0',\n  `roomdate` int(11) DEFAULT '0',\n  `thisdate` varchar(255) DEFAULT '',\n  `oprice` decimal(10,2) DEFAULT '0.00',\n  `cprice` decimal(10,2) DEFAULT '0.00',\n  `mprice` decimal(10,2) DEFAULT '0.00',\n  `roomid` int(11) DEFAULT '0',\n  PRIMARY KEY (`id`)\n) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='订单关联酒店房间表' AUTO_INCREMENT=1 ;\n\nCREATE TABLE IF NOT EXISTS " . tablename('sz_yi_book') . " (\n  `id` int(11) NOT NULL AUTO_INCREMENT,\n  `uniacid` int(11) DEFAULT '0',\n  `uid` int(11) DEFAULT '0',\n  `mobile` varchar(30) DEFAULT '',\n  `time` varchar(255) DEFAULT '',\n  `contact` text,\n  `goods` int(11) DEFAULT '0',\n  `message` text,\n  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,\n  `type` int(11) DEFAULT '0',\n  `status` int(1) DEFAULT '0',\n  `delete` int(1) DEFAULT '0',\n  PRIMARY KEY (`id`)\n) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会议餐饮预约表' AUTO_INCREMENT=1 ;\n\nCREATE TABLE IF NOT EXISTS " . tablename('sz_yi_print_list') . " (\n  `id` int(11) NOT NULL AUTO_INCREMENT,\n  `uniacid` int(11) DEFAULT '0',\n  `name` varchar(45) DEFAULT '',\n  `key` varchar(30) DEFAULT '',\n  `print_no` varchar(30) DEFAULT '',\n  `type` int(1) DEFAULT '0',\n  `status` int(3) DEFAULT '0',\n  `member_code` varchar(50) DEFAULT '',\n  PRIMARY KEY (`id`)\n) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='酒店房间价格表' AUTO_INCREMENT=1 ;\n";
pdo_query($sql);

if (!pdo_fieldexists('sz_yi_goods', 'deposit')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_goods') . ' ADD `deposit` DECIMAL(10,2) DEFAULT \'0.00\' AFTER `isreturnqueue`;');
}

if (!pdo_fieldexists('sz_yi_goods', 'print_id')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_goods') . ' ADD `print_id` INT(11) DEFAULT \'0\' AFTER `deposit`;');
}

if (!pdo_fieldexists('sz_yi_order', 'checkname')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `checkname` varchar(255) DEFAULT \'\' AFTER `ordersn_general`;');
}

if (!pdo_fieldexists('sz_yi_order', 'realmobile')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `realmobile` varchar(255) DEFAULT \'\' AFTER `checkname`;');
}

if (!pdo_fieldexists('sz_yi_order', 'realsex')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `realsex` INT(1) DEFAULT \'0\' AFTER `realmobile`;');
}

if (!pdo_fieldexists('sz_yi_order', 'invoice')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `invoice`  INT(1) DEFAULT \'0\'  AFTER `realsex`;');
}

if (!pdo_fieldexists('sz_yi_order', 'invoiceval')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `invoiceval` INT(1) DEFAULT \'0\' AFTER `invoice`;');
}

if (!pdo_fieldexists('sz_yi_order', 'invoicetext')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `invoicetext` varchar(255) DEFAULT \'\' AFTER `invoiceval`;');
}

if (!pdo_fieldexists('sz_yi_order', 'num')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `num` INT(1) DEFAULT \'0\' AFTER `invoicetext`;');
}

if (!pdo_fieldexists('sz_yi_order', 'btime')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `btime` INT(11) DEFAULT \'0\' AFTER `num`;');
}

if (!pdo_fieldexists('sz_yi_order', 'etime')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `etime` INT(11) DEFAULT \'0\' AFTER `btime`;');
}

if (!pdo_fieldexists('sz_yi_order', 'depositprice')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `depositprice`  DECIMAL(10,2) DEFAULT \'0.00\' AFTER `etime`;');
}

if (!pdo_fieldexists('sz_yi_order', 'returndepositprice')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `returndepositprice`   DECIMAL(10,2) DEFAULT \'0.00\' AFTER `depositprice`;');
}

if (!pdo_fieldexists('sz_yi_order', 'depositpricetype')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `depositpricetype` INT(1) DEFAULT \'0\' AFTER `returndepositprice`;');
}

if (!pdo_fieldexists('sz_yi_order', 'room_number')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `room_number` varchar(11) DEFAULT \'\' AFTER `depositpricetype`;');
}

if (!pdo_fieldexists('sz_yi_order', 'roomid')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `roomid` INT(11) DEFAULT \'0\' AFTER `room_number`;');
}

if (!pdo_fieldexists('sz_yi_order', 'order_type')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `order_type`  INT(11) DEFAULT \'0\' AFTER `roomid`;');
}

if (!pdo_fieldexists('sz_yi_order', 'days')) {
	pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `days`  INT(11) DEFAULT \'0\' AFTER `order_type`;');
}

message('酒店插件安装成功', $this->createPluginWebUrl('hotel/room_status'), 'success');

?>
