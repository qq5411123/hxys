<?php
// ��������������Ƽ����޹�˾(����֧��)
global $_W;
global $_GPC;
$openid = m('user')->getOpenid();
$member = $this->model->getInfo($openid);
$condition = '';
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$list = array();
$total = 0;

if (!empty($member['agentcount'])) {
	$total = $member['agentcount'];
	$inagents = implode(',', $member['agentids']);

	if (!empty($_GPC['id'])) {
		$condition .= ' AND id<' . $_GPC['id'];
	}

	$sql = 'select * from ' . tablename('sz_yi_member') . ' where id in (' . $inagents . ') and uniacid = ' . $_W['uniacid'] . ' ' . $condition . '  ORDER BY id desc limit ' . (($pindex - 1) * $psize) . ',' . $psize;
	$list = pdo_fetchall($sql);
}

if ($_W['isajax']) {
	foreach ($list as &$row) {
		$row['createtime'] = date('Y-m-d H:i', $row['createtime']);
		$myorder = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.openid=:openid and o.status>=3 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $row['openid']));
		$row['ordercount'] = intval($myorder['ordercount']);
		$row['moneycount'] = number_format($myorder['ordermoney'], 2);
	}

	unset($row);
	return show_json(1, array('list' => $list, 'pagesize' => $psize, 'set' => $this->set, 'total' => $total));
}

include $this->template('customer');

?>
