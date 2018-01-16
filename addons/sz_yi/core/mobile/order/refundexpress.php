<?php
// ��������������Ƽ����޹�˾(����֧��)
function sortByTime($a, $b)
{
	if ($a['ts'] == $b['ts']) {
		return 0;
	}

	return $b['ts'] < $a['ts'] ? 1 : -1;
}

if (!defined('IN_IA')) {
	exit('Access Denied');
}

global $_W;
global $_GPC;
$operation = (!empty($_GPC['op']) ? $_GPC['op'] : 'display');
$openid = m('user')->getOpenid();
$uniacid = $_W['uniacid'];
$orderid = intval($_GPC['id']);

if ($_W['isajax']) {
	if ($operation == 'display') {
		$order = pdo_fetch('select refundid from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':openid' => $openid));

		if (empty($order)) {
			show_json(0);
		}

		$refundid = $order['refundid'];
		$refund = pdo_fetch('select * from ' . tablename('sz_yi_order_refund') . ' where id=:id and uniacid=:uniacid  limit 1', array(':id' => $refundid, ':uniacid' => $uniacid));
		$set = set_medias(m('common')->getSysset('shop'), 'logo');
		show_json(1, array('order' => $order, 'refund' => $refund, 'set' => $set));
	}
	else {
		if ($operation == 'step') {
			$express = trim($_GPC['express']);
			$expresssn = trim($_GPC['expresssn']);
			$content = getExpress($express, $expresssn);

			if (!$content) {
				$content = getExpress($express, $expresssn);

				if (!$content) {
					show_json(1, array(
	'list' => array()
	));
				}
			}

			foreach ($content as $data) {
				$list[] = array('time' => $data->time, 'step' => $data->context, 'ts' => $data->time);
			}

			show_json(1, array('list' => $list));
		}
	}
}

include $this->template('order/refundexpress');

?>
