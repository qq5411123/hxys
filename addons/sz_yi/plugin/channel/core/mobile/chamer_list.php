<?php
// ��������������Ƽ����޹�˾(����֧��)
global $_W;
global $_GPC;
$openid = m('user')->getOpenid();

if ($_W['isajax']) {
	$channelinfo = $this->model->getInfo($openid);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$sql = 'SELECT o.id,o.ordersn,o.price,o.openid,o.address,o.createtime FROM ' . tablename('sz_yi_order') . ' o ' . ' RIGHT JOIN  ' . tablename('sz_yi_order_goods') . '  og on o.id=og.orderid AND og.ischannelpay=1 left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id ' . ' WHERE 1 AND o.uniacid=' . $_W['uniacid'] . ' AND o.openid in (' . $channelinfo['channel']['lower_openids'] . ') AND o.status>=3 group by o.id ORDER BY o.createtime DESC ';
	$sql .= 'LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;
	$list = pdo_fetchall($sql);

	foreach ($list as $key => &$rowp) {
		$commission = pdo_fetchcolumn('SELECT commission FROM ' . tablename('sz_yi_channel_merchant') . ' WHERE uniacid=' . $_W['uniacid'] . ' AND openid=\'' . $openid . '\' AND lower_openid=\'' . $rowp['openid'] . '\'');
		$rowp['commission'] = number_format(($rowp['price'] * $commission) / 100, 2);
		$sql = 'SELECT og.goodsid,og.total,g.title,g.thumb,og.price,og.optionname as optiontitle,og.optionid FROM ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on og.goodsid = g.id ' . ' WHERE og.orderid=:orderid order by og.id asc';
		$rowp['goods'] = set_medias(pdo_fetchall($sql, array(':orderid' => $rowp['id'])), 'thumb');
		$rowp['goodscount'] = count($rowp['goods']);
	}

	show_json(2, array('list' => $list, 'pagesize' => $psize));
}

include $this->template('chamer_list');

?>
