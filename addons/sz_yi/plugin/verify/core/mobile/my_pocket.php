<?php
// ��������������Ƽ����޹�˾(����֧��)
global $_W;
global $_GPC;
$openid = m('user')->getOpenid();

if ($_W['isajax']) {
	$id = $_GPC['id'];
	$store = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_store') . ' WHERE id=:id and uniacid=:uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
	$totalprice = $this->model->getTotalPrice($id);
	$ordercount = $this->model->getTotal($id);
	$totalwithdrawprice = $this->model->getRealPrice($id);
	$totalwithdraws = $this->model->getWithdrawed($id);
	$canwithdraw = $totalwithdrawprice - $totalwithdraws;
	$wait_apply = pdo_fetchall('SELECT money FROM ' . tablename('sz_yi_store_withdraw') . ' WHERE uniacid = :uniacid AND store_id = :id AND status = 0', array(':uniacid' => $_W['uniacid'], ':id' => $id));

	if (!empty($wait_apply)) {
		foreach ($wait_apply as $value) {
			$wait_applyed += $value['money'];
		}
	}
	else {
		$wait_applyed = 0;
	}

	show_json(1, array('totalprice' => $totalprice, 'ordercount' => $ordercount, 'store' => $store, 'canwithdraw' => $canwithdraw, 'withdraw_money' => $totalwithdraws, 'canwithdrawtotal' => $totalwithdrawprice, 'wait_apply' => $wait_applyed, 'storeid' => $id));
}

include $this->template('my_pocket');

?>
