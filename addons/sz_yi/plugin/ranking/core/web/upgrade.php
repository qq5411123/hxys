<?php
// 唐上美联佳网络科技有限公司(技术支持)
global $_W;
$result = pdo_fetchcolumn('select id from ' . tablename('sz_yi_plugin') . ' where identity=:identity', array(':identity' => 'ranking'));

if (empty($result)) {
	$displayorder_max = pdo_fetchcolumn('select max(displayorder) from ' . tablename('sz_yi_plugin'));
	$displayorder = $displayorder_max + 1;
	$sql = 'INSERT INTO ' . tablename('sz_yi_plugin') . ' (`displayorder`,`identity`,`name`,`version`,`author`,`status`,`category`) VALUES(' . $displayorder . ',\'ranking\',\'排行榜\',\'1.0\',\'官方\',\'1\',\'help\');';
	pdo_fetchall($sql);
}

$sql = 'CREATE TABLE IF NOT EXISTS ' . tablename('sz_yi_ranking') . " (\n  `id` int(11) NOT NULL AUTO_INCREMENT,\n  `uniacid` int(11) NOT NULL,\n  `mid` int(11) NOT NULL,\n  `credit` decimal(10,2) NOT NULL,\n  PRIMARY KEY (`id`)\n) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;\n\n ALTER TABLE " . tablename('sz_yi_order_goods') . "  ADD  `rankingstatus` TINYINT( 1 ) NOT NULL COMMENT  '排行状态';\n";
pdo_query($sql);
message('排行榜插件安装成功', $this->createPluginWebUrl('ranking/set'), 'success');

?>
