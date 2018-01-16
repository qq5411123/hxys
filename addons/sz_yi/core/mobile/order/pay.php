<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

global $_W;
global $_GPC;
$operation = (!empty($_GPC['op']) ? $_GPC['op'] : 'display');
$shopset = m('common')->getSysset('shop');
$openid = m('user')->getOpenid();

if (empty($openid)) {
	$openid = $_GPC['openid'];
}

$set = m('common')->getSysset(array('pay'));
$member = m('member')->getMember($openid);
$uniacid = $_W['uniacid'];
$orderid = intval($_GPC['orderid']);

if (!empty($orderid)) {
	$ordersn_general = pdo_fetchcolumn('select ordersn_general from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':openid' => $openid));
	$order_all = pdo_fetchall('select * from ' . tablename('sz_yi_order') . ' where ordersn_general=:ordersn_general and uniacid=:uniacid and openid=:openid', array(':ordersn_general' => $ordersn_general, ':uniacid' => $uniacid, ':openid' => $openid));

	if (1 < count($order_all)) {
		$order = array();
		$order['ordersn'] = $ordersn_general;
		$orderid = array();

		foreach ($order_all as $key => $val) {
			$order['price'] += $val['price'];
			$order['deductcredit2'] += $val['deductcredit2'];
			$order['ordersn2'] += $val['ordersn2'];
			$orderid[] = $val['id'];
		}

		$order['status'] = $val['status'];
		$order['cash'] = $val['cash'];
		$order['openid'] = $val['openid'];
		$order['pay_ordersn'] = $val['pay_ordersn'];
	}
	else {
		$order = $order_all[0];
	}
}

if (($operation == 'display') && $_W['isajax']) {
	if (empty($orderid)) {
		show_json(0, '参数错误!');
	}

	if (empty($order)) {
		show_json(0, '订单未找到!');
	}

	if ($order['status'] == -1) {
		show_json(-1, '订单已关闭, 无法付款!');
	}
	else {
		if (1 <= $order['status']) {
			show_json(-1, '订单已付款, 无需重复支付!');
		}
	}

	$log = pdo_fetch('SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid limit 1', array(':uniacid' => $uniacid, ':module' => 'sz_yi', ':tid' => $ordersn_general));
	if (!empty($log) && ($log['status'] != '0')) {
		show_json(-1, '订单已支付, 无需重复支付!');
	}

	if (!empty($log) && ($log['status'] == '0')) {
		pdo_delete('core_paylog', array('plid' => $log['plid']));
		$log = NULL;
	}

	$plid = $log['plid'];

	if (empty($log)) {
		$log = array('uniacid' => $uniacid, 'openid' => $member['openid'], 'module' => 'sz_yi', 'tid' => $ordersn_general, 'fee' => $order['price'], 'status' => 0);
		pdo_insert('core_paylog', $log);
		$plid = pdo_insertid();
	}

	$set = m('common')->getSysset(array('shop', 'pay'));
	$credit = array('success' => false);
	$currentcredit = 0;
	if (isset($set['pay']) && ($set['pay']['credit'] == 1)) {
		if ($order['deductcredit2'] <= 0) {
			$credit = array('success' => true, 'current' => m('member')->getCredit($openid, 'credit2'));
		}
	}

	$app_alipay = array('success' => false);
	if (isset($set['pay']) && ($set['pay']['app_alipay'] == 1)) {
		$app_alipay['success'] = true;
	}

	$app_wechat = array('success' => false);
	if (isset($set['pay']) && ($set['pay']['app_weixin'] == 1)) {
		$app_wechat['success'] = true;
	}

	load()->model('payment');
	$setting = uni_setting($_W['uniacid'], array('payment'));
	$wechat = array('success' => false, 'qrcode' => false);
	$jie = $set['pay']['weixin_jie'];

	if (is_weixin()) {
		if (isset($set['pay']) && ($set['pay']['weixin'] == 1) && ($jie != 1)) {
			if (is_array($setting['payment']['wechat']) && $setting['payment']['wechat']['switch']) {
				$wechat['success'] = true;
				$wechat['weixin'] = true;
				$wechat['weixin_jie'] = false;
			}
		}
	}

	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
		if ((isset($set['pay']) && ($set['pay']['weixin_jie'] == 1) && !$wechat['success']) || ($jie == 1)) {
			$wechat['success'] = true;
			$wechat['weixin_jie'] = true;
			$wechat['weixin'] = false;
		}
	}

	$wechat['jie'] = $jie;
	if (!isMobile() && isset($set['pay']) && ($set['pay']['weixin'] == 1)) {
		if (isset($set['pay']) && ($set['pay']['weixin'] == 1)) {
			if (is_array($setting['payment']['wechat']) && $setting['payment']['wechat']['switch']) {
				$wechat['qrcode'] = true;
			}
		}
	}

	$alipay = array('success' => false);
	if (isset($set['pay']) && ($set['pay']['alipay'] == 1)) {
		if (is_array($setting['payment']['alipay']) && $setting['payment']['alipay']['switch']) {
			$alipay['success'] = true;
		}
	}

	$pluginy = p('yunpay');
	$yunpay = array('success' => false);

	if ($pluginy) {
		$yunpayinfo = $pluginy->getYunpay();
		if (isset($yunpayinfo) && @$yunpayinfo['switch']) {
			$yunpay['success'] = true;
		}
	}

	$unionpay = array('success' => false);
	if (isset($set['pay']) && ($set['pay']['unionpay'] == 1)) {
		if (is_array($setting['payment']['unionpay']) && $setting['payment']['unionpay']['switch']) {
			$unionpay['success'] = true;
		}
	}

	$cash = array('success' => ($order['cash'] == 1) && isset($set['pay']) && ($set['pay']['cash'] == 1) && ($order['dispatchtype'] == 0));
	$storecash = array('success' => ($order['cash'] == 1) && isset($set['pay']) && ($set['pay']['cash'] == 1) && ($order['dispatchtype'] == 1));
	$yeepay = array('success' => false);
	if (isset($set['pay']) && ($set['pay']['yeepay'] == 1)) {
		$yeepay['success'] = true;
	}

	$paypal = array('success' => false);
	if (isset($set['pay']) && ($set['pay']['paypalstatus'] == 1)) {
		$paypal['success'] = true;
	}

	$returnurl = urlencode($this->createMobileUrl('order/pay', array('orderid' => $orderid)));

	if (is_array($orderid)) {
		$orderids = implode(',', $orderid);
		$where_orderid = 'og.orderid in (' . $orderids . ')';
	}
	else {
		$where_orderid = 'og.orderid=' . $orderid;
	}

	$order_goods = pdo_fetchall('select og.id,g.title, og.goodsid,og.optionid,g.thumb, g.total as stock,og.total as buycount,g.status,g.deleted,g.maxbuy,g.usermaxbuy,g.istime,g.timestart,g.timeend,g.buylevels,g.buygroups from  ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on og.goodsid = g.id ' . ' where ' . $where_orderid . ' and og.uniacid=:uniacid ', array(':uniacid' => $_W['uniacid']));

	foreach ($order_goods as $key => &$value) {
		if (!empty($value['optionid'])) {
			$option = pdo_fetch('select id,title,marketprice,goodssn,productsn,stock,virtual,weight from ' . tablename('sz_yi_goods_option') . ' where id=:id and goodsid=:goodsid and uniacid=:uniacid  limit 1', array(':uniacid' => $_W['uniacid'], ':goodsid' => $value['goodsid'], ':id' => $value['optionid']));

			if (!empty($option)) {
				$value['optionid'] = $value['optionid'];
				$value['optiontitle'] = $option['title'];
				$value['marketprice'] = $option['marketprice'];

				if (!empty($option['weight'])) {
					$value['weight'] = $option['weight'];
				}
			}
		}
	}

	unset($value);
	$order_goods = set_medias($order_goods, 'thumb');
	show_json(1, array('order' => $order, 'set' => $set, 'credit' => $credit, 'wechat' => $wechat, 'alipay' => $alipay, 'app_wechat' => $app_wechat, 'app_alipay' => $app_alipay, 'unionpay' => $unionpay, 'yunpay' => $yunpay, 'cash' => $cash, 'storecash' => $storecash, 'yeepay' => $yeepay, 'paypal' => $paypal, 'isweixin' => is_weixin(), 'currentcredit' => $currentcredit, 'returnurl' => $returnurl, 'goods' => $order_goods));
}
else {
	if (($operation == 'pay') && $_W['ispost']) {
		$set = m('common')->getSysset(array('shop', 'pay'));

		if (empty($order)) {
			show_json(0, '订单未找到!');
		}

		$type = $_GPC['type'];

		if (!in_array($type, array('weixin', 'alipay', 'app_alipay', 'app_weixin', 'unionpay', 'yunpay', 'yeepay', 'paypal', 'yeepay_wy'))) {
			show_json(0, '未找到支付方式');
		}

		if (($member['credit2'] < $order['deductcredit2']) && (0 < $order['deductcredit2'])) {
			show_json(0, '余额不足，请充值后在试！');
		}

		$pay_ordersn = ($order['pay_ordersn'] ? $order['pay_ordersn'] : $ordersn_general);
		$log = pdo_fetch('SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid limit 1', array(':uniacid' => $uniacid, ':module' => 'sz_yi', ':tid' => $pay_ordersn));

		if (empty($log)) {
			show_json(0, '支付出错,请重试!');
		}

		if (is_array($orderid)) {
			$orderids = implode(',', $orderid);
			$where_orderid = 'og.orderid in (' . $orderids . ')';
		}
		else {
			$where_orderid = 'og.orderid=' . $orderid;
		}

		$order_goods = pdo_fetchall('select og.id,g.title, og.goodsid,og.optionid,g.total as stock,og.total as buycount,g.status,g.deleted,g.maxbuy,g.usermaxbuy,g.istime,g.timestart,g.timeend,g.buylevels,g.buygroups from  ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on og.goodsid = g.id ' . ' where ' . $where_orderid . ' and og.uniacid=:uniacid ', array(':uniacid' => $_W['uniacid']));

		foreach ($order_goods as $data) {
			if (empty($data['status']) || !empty($data['deleted'])) {
				show_json(-1, $data['title'] . '<br/> 已下架!');
			}

			if (0 < $data['maxbuy']) {
				if ($data['maxbuy'] < $data['buycount']) {
					show_json(-1, $data['title'] . '<br/> 一次限购 ' . $data['maxbuy'] . $unit . '!');
				}
			}

			if (0 < $data['usermaxbuy']) {
				$order_goodscount = pdo_fetchcolumn('select ifnull(sum(og.total),0)  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_order') . ' o on og.orderid=o.id ' . ' where og.goodsid=:goodsid and  o.status>=1 and o.openid=:openid  and og.uniacid=:uniacid ', array(':goodsid' => $data['goodsid'], ':uniacid' => $uniacid, ':openid' => $openid));

				if ($data['usermaxbuy'] <= $order_goodscount) {
					show_json(-1, $data['title'] . '<br/> 最多限购 ' . $data['usermaxbuy'] . $unit . '!');
				}
			}

			if ($data['istime'] == 1) {
				if (time() < $data['timestart']) {
					show_json(-1, $data['title'] . '<br/> 限购时间未到!');
				}

				if ($data['timeend'] < time()) {
					show_json(-1, $data['title'] . '<br/> 限购时间已过!');
				}
			}

			if ($data['buylevels'] != '') {
				$buylevels = explode(',', $data['buylevels']);

				if (!in_array($member['level'], $buylevels)) {
					show_json(-1, '您的会员等级无法购买<br/>' . $data['title'] . '!');
				}
			}

			if ($data['buygroups'] != '') {
				$buygroups = explode(',', $data['buygroups']);

				if (!in_array($member['groupid'], $buygroups)) {
					show_json(-1, '您所在会员组无法购买<br/>' . $data['title'] . '!');
				}
			}

			if (!empty($data['optionid'])) {
				$option = pdo_fetch('select id,title,marketprice,goodssn,productsn,stock,virtual from ' . tablename('sz_yi_goods_option') . ' where id=:id and goodsid=:goodsid and uniacid=:uniacid  limit 1', array(':uniacid' => $uniacid, ':goodsid' => $data['goodsid'], ':id' => $data['optionid']));

				if (!empty($option)) {
					if ($option['stock'] != -1) {
						if (empty($option['stock'])) {
							show_json(-1, $data['title'] . '<br/>' . $option['title'] . ' 库存不足!');
						}
					}
				}
			}
			else {
				if ($data['stock'] != -1) {
					if (empty($data['stock'])) {
						show_json(-1, $data['title'] . '<br/>库存不足!');
					}
				}
			}
		}

		$plid = $log['plid'];
		$param_title = $set['shop']['name'] . '订单: ' . $order['ordersn'];

		if (is_array($orderid)) {
			$orderids = implode(',', $orderid);
			$where_update = 'id in (' . $orderids . ')';
		}
		else {
			$where_update = 'id=' . $orderid;
		}

		if ($type == 'weixin') {
			if (!empty($set['pay']['weixin']) || !empty($set['pay']['weixin_jie'])) {
			}
			else {
				show_json(0, '未开启微信支付!');
			}

			$wechat = array('success' => false);
			$params = array();
			$params['tid'] = $log['tid'];

			if (!empty($order['ordersn2'])) {
				$var = sprintf('%02d', $order['ordersn2']);
				$params['tid'] .= 'GJ' . $var;
			}

			$params['user'] = $openid;
			$params['fee'] = $order['price'];
			$params['title'] = $param_title;
			load()->model('payment');
			$setting = uni_setting($_W['uniacid'], array('payment'));

			if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
				if (is_array($setting['payment'])) {
					$options = $setting['payment']['wechat'];

					if (is_weixin()) {
						if (empty($set['pay']['weixin_jie'])) {
							$options['appid'] = $_W['account']['key'];
							$options['secret'] = $_W['account']['secret'];
							$wechat = m('common')->wechat_build($params, $options, 0);

							if (!is_error($wechat)) {
								$wechat['success'] = true;
							}
							else {
								show_json(0, $wechat['message']);
							}
						}
					}

					if (!empty($set['pay']['weixin_jie'])) {
						$options['appid'] = $set['pay']['weixin_jie_appid'];
						$options['mchid'] = $set['pay']['weixin_jie_mchid'];
						$options['apikey'] = $set['pay']['weixin_jie_apikey'];
						$wechat = m('common')->wechat_native_build($params, $options, 0);

						if (!is_error($wechat)) {
							$wechat['success'] = true;
							$wechat['weixin_jie'] = true;
						}
					}
				}

				if (!$wechat['success']) {
					show_json(0, '微信支付参数错误!');
				}

				pdo_query('update ' . tablename('sz_yi_order') . ' set paytype=21 where ' . $where_update . ' and uniacid=:uniacid ', array(':uniacid' => $uniacid));
				show_json(1, array('wechat' => $wechat));
			}
			else {
				if (is_array($setting['payment'])) {
					$params['trade_type'] = 'NATIVE';
					$options = $setting['payment']['wechat'];
					$options['appid'] = $_W['account']['key'];
					$options['secret'] = $_W['account']['secret'];
					$wechat = m('common')->wechat_build($params, $options, 0);

					if (!is_error($wechat)) {
						$wechat['success'] = true;
						$wechat['code_url'] = m('qrcode')->createWechatQrcode($wechat['code_url']);
					}
					else {
						show_json(0, $wechat['message']);
					}

					pdo_query('update ' . tablename('sz_yi_order') . ' set paytype=21 where ' . $where_update . ' and uniacid=:uniacid ', array(':uniacid' => $uniacid));
					show_json(1, array('wechat' => $wechat));
				}
			}
		}
		else if ($type == 'alipay') {
			pdo_query('update ' . tablename('sz_yi_order') . ' set paytype=22 where ' . $where_update . ' and uniacid=:uniacid ', array(':uniacid' => $uniacid));
			show_json(1);
		}
		else if ($type == 'yunpay') {
			pdo_query('update ' . tablename('sz_yi_order') . ' set paytype=24 where ' . $where_update . ' and uniacid=:uniacid ', array(':uniacid' => $uniacid));
			show_json(1);
		}
		else if ($type == 'yeepay') {
			pdo_query('update ' . tablename('sz_yi_order') . ' set paytype=25 where ' . $where_update . ' and uniacid=:uniacid ', array(':uniacid' => $uniacid));
			show_json(1);
		}
		else if ($type == 'yeepay_wy') {
			pdo_query('update ' . tablename('sz_yi_order') . ' set paytype=26 where ' . $where_update . ' and uniacid=:uniacid ', array(':uniacid' => $uniacid));
			show_json(1);
		}
		else {
			if ($type == 'paypal') {
				pdo_query('update ' . tablename('sz_yi_order') . ' set paytype=29 where ' . $where_update . ' and uniacid=:uniacid ', array(':uniacid' => $uniacid));
				show_json(1);
			}
		}
	}
	else {
		if (($operation == 'complete') && $_W['ispost']) {
			$pset = m('common')->getSysset();
			$verify_set = m('common')->getSetData();
			$allset = iunserializer($verify_set['plugins']);
			$ischannelpay = intval($_GPC['ischannelpay']);

			if (is_array($orderid)) {
				$orderids = implode(',', $orderid);
				$where_orderid = 'og.orderid in (' . $orderids . ')';
			}
			else {
				$where_orderid = 'og.orderid=' . $orderid;
			}

			$order_goods = pdo_fetchall('select og.id,g.title, og.goodsid,og.optionid,g.total as stock,og.total as buycount,g.status,g.deleted,g.maxbuy,g.usermaxbuy,g.istime,g.timestart,g.timeend,g.buylevels,g.buygroups from  ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on og.goodsid = g.id ' . ' where ' . $where_orderid . ' and og.uniacid=:uniacid ', array(':uniacid' => $_W['uniacid']));

			foreach ($order_goods as $data) {
				if (empty($data['status']) || !empty($data['deleted'])) {
					show_json(-1, $data['title'] . '<br/> 已下架!');
				}

				if (0 < $data['maxbuy']) {
					if ($data['maxbuy'] < $data['buycount']) {
						show_json(-1, $data['title'] . '<br/> 一次限购 ' . $data['maxbuy'] . $unit . '!');
					}
				}

				if (0 < $data['usermaxbuy']) {
					$order_goodscount = pdo_fetchcolumn('select ifnull(sum(og.total),0)  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_order') . ' o on og.orderid=o.id ' . ' where og.goodsid=:goodsid and  o.status>=1 and o.openid=:openid  and og.uniacid=:uniacid ', array(':goodsid' => $data['goodsid'], ':uniacid' => $uniacid, ':openid' => $openid));

					if ($data['usermaxbuy'] <= $order_goodscount) {
						show_json(-1, $data['title'] . '<br/> 最多限购 ' . $data['usermaxbuy'] . $unit . '!');
					}
				}

				if ($data['istime'] == 1) {
					if (time() < $data['timestart']) {
						show_json(-1, $data['title'] . '<br/> 限购时间未到!');
					}

					if ($data['timeend'] < time()) {
						show_json(-1, $data['title'] . '<br/> 限购时间已过!');
					}
				}

				if ($data['buylevels'] != '') {
					$buylevels = explode(',', $data['buylevels']);

					if (!in_array($member['level'], $buylevels)) {
						show_json(-1, '您的会员等级无法购买<br/>' . $data['title'] . '!');
					}
				}

				if ($data['buygroups'] != '') {
					$buygroups = explode(',', $data['buygroups']);

					if (!in_array($member['groupid'], $buygroups)) {
						show_json(-1, '您所在会员组无法购买<br/>' . $data['title'] . '!');
					}
				}

				if (!empty($data['optionid'])) {
					$option = pdo_fetch('select id,title,marketprice,goodssn,productsn,stock,virtual from ' . tablename('sz_yi_goods_option') . ' where id=:id and goodsid=:goodsid and uniacid=:uniacid  limit 1', array(':uniacid' => $uniacid, ':goodsid' => $data['goodsid'], ':id' => $data['optionid']));

					if (!empty($option)) {
						if ($option['stock'] != -1) {
							if (empty($option['stock'])) {
								show_json(-1, $data['title'] . '<br/>' . $option['title'] . ' 库存不足!');
							}
						}
					}
				}
				else {
					if ($data['stock'] != -1) {
						if (empty($data['stock'])) {
							show_json(-1, $data['title'] . '<br/>库存不足!');
						}
					}
				}
			}

			if (empty($order)) {
				show_json(0, '订单未找到!');
			}

			$type = $_GPC['type'];

			if (!in_array($type, array('weixin', 'alipay', 'credit', 'cash', 'storecash', 'paypal'))) {
				show_json(0, '未找到支付方式');
			}

			if (($member['credit2'] < $order['deductcredit2']) && (0 < $order['deductcredit2'])) {
				show_json(0, '余额不足，请充值后在试！');
			}

			$pay_ordersn = ($order['pay_ordersn'] ? $order['pay_ordersn'] : $ordersn_general);
			$log = pdo_fetch('SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid limit 1', array(':uniacid' => $uniacid, ':module' => 'sz_yi', ':tid' => $pay_ordersn));

			if (empty($log)) {
				show_json(0, '支付出错,请重试!');
			}

			$plid = $log['plid'];

			if (is_array($orderid)) {
				$orderids = implode(',', $orderid);
				$where_update = 'id in (' . $orderids . ')';
			}
			else {
				$where_update = 'id=' . $orderid;
			}

			if ($type == 'cash') {
				if (!$set['pay']['cash']) {
					show_json(0, '当前支付方式未开启,请重试!');
				}

				pdo_query('update ' . tablename('sz_yi_order') . ' set paytype=3 where ' . $where_update . ' and uniacid=:uniacid ', array(':uniacid' => $uniacid));
				$ret = array();
				$ret['result'] = 'success';
				$ret['type'] = 'cash';
				$ret['from'] = 'return';
				$ret['tid'] = $log['tid'];
				$ret['user'] = $order['openid'];
				$ret['fee'] = $order['price'];
				$ret['weid'] = $_W['uniacid'];
				$ret['uniacid'] = $_W['uniacid'];

				if (p('channel')) {
					if ($ischannelpay == 1) {
						$ret['ischannelpay'] = $ischannelpay;
					}
				}

				$pay_result = $this->payResult($ret);
				$set = m('common')->getSysset();

				if (!empty($pay_result['verifycode'])) {
					if ($pset['sms']['type'] == 1) {
						if (($pay_result['verifycode']['SubmitResult']['code'] == 2) || ($allset['verify']['sendcode'] == 0) || empty($order['isverify'])) {
							show_json(1, $pay_result);
						}
						else {
							show_json(0, $pay_result['verifycode']['SubmitResult']['msg']);
						}
					}
					else {
						if (isset($pay_result['verifycode']['result']['success']) || ($allset['verify']['sendcode'] == 0) || empty($order['isverify'])) {
							show_json(1, $pay_result);
						}
						else {
							show_json(0, $pay_result['verifycode']['msg']);
						}
					}
				}
				else {
					show_json(1, $pay_result);
				}
			}

			$ps = array();
			$ps['tid'] = $log['tid'];
			$ps['user'] = $openid;
			$ps['fee'] = $log['fee'];
			$ps['title'] = $log['title'];

			if ($type == 'storecash') {
				if (!$set['pay']['cash']) {
					show_json(0, '当前支付方式未开启,请重试!');
				}

				pdo_query('update ' . tablename('sz_yi_order') . ' set paytype=4 where ' . $where_update . ' and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid']));
				$ret = array();
				$ret['result'] = 'success';
				$ret['type'] = 'storecash';
				$ret['from'] = 'return';
				$ret['tid'] = $log['tid'];
				$ret['user'] = $order['openid'];
				$ret['fee'] = $order['price'];
				$ret['weid'] = $_W['uniacid'];
				$ret['uniacid'] = $_W['uniacid'];
				$pay_result = $this->payResult($ret);
				$set = m('common')->getSysset();

				if (!empty($pay_result['verifycode'])) {
					if ($pset['sms']['type'] == 1) {
						if (($pay_result['verifycode']['SubmitResult']['code'] == 2) || ($allset['verify']['sendcode'] == 0) || empty($order['isverify'])) {
							show_json(1, $pay_result);
						}
						else {
							show_json(0, $pay_result['verifycode']['SubmitResult']['msg']);
						}
					}
					else {
						if (isset($pay_result['verifycode']['result']['success']) || ($allset['verify']['sendcode'] == 0) || empty($order['isverify'])) {
							show_json(1, $pay_result);
						}
						else {
							show_json(0, $pay_result['verifycode']['msg']);
						}
					}
				}
				else {
					show_json(1, $pay_result);
				}
			}

			$ps = array();
			$ps['tid'] = $log['tid'];
			$ps['user'] = $openid;
			$ps['fee'] = $log['fee'];
			$ps['title'] = $log['title'];

			if ($type == 'credit') {
				if (!$set['pay']['credit']) {
					show_json(0, '余额支付未开启！');
				}

				$credits = m('member')->getCredit($openid, 'credit2');

				if ($credits < $ps['fee']) {
					show_json(0, '余额不足,请充值');
				}

				$fee = floatval($ps['fee']);
				$result = m('member')->setCredit($openid, 'credit2', 0 - $fee, array($_W['member']['uid'], '消费' . $setting['creditbehaviors']['currency'] . ':' . $fee));

				if (is_error($result)) {
					show_json(0, $result['message']);
				}

				$record = array();
				$record['status'] = '1';
				$record['type'] = 'cash';
				pdo_update('core_paylog', $record, array('plid' => $log['plid']));
				pdo_query('update ' . tablename('sz_yi_order') . ' set paytype=1 where ' . $where_update . ' and uniacid=:uniacid ', array(':uniacid' => $uniacid));
				$ret = array();
				$ret['result'] = 'success';
				$ret['type'] = $log['type'];
				$ret['from'] = 'return';
				$ret['tid'] = $log['tid'];
				$ret['user'] = $log['openid'];
				$ret['fee'] = $log['fee'];
				$ret['weid'] = $log['weid'];
				$ret['uniacid'] = $log['uniacid'];

				if (p('channel')) {
					if ($ischannelpay == 1) {
						$ret['ischannelpay'] = $ischannelpay;
					}
				}

				$pay_result = $this->payResult($ret);

				if (!empty($pay_result['verifycode'])) {
					if ($pset['sms']['type'] == 1) {
						if (($pay_result['verifycode']['SubmitResult']['code'] == 2) || ($allset['verify']['sendcode'] == 0) || empty($order['isverify'])) {
							show_json(1, $pay_result);
						}
						else {
							show_json(0, $pay_result['verifycode']['SubmitResult']['msg']);
						}
					}
					else {
						if (isset($pay_result['verifycode']['result']['success']) || ($allset['verify']['sendcode'] == 0) || empty($order['isverify'])) {
							show_json(1, $pay_result);
						}
						else {
							show_json(0, $pay_result['verifycode']['msg']);
						}
					}
				}
				else {
					show_json(1, $pay_result);
				}
			}
			else {
				if ($type == 'weixin') {
					$ordersn = $pay_ordersn;

					if (!empty($order['ordersn2'])) {
						$ordersn .= 'GJ' . sprintf('%02d', $order['ordersn2']);
					}

					$payquery = m('finance')->isWeixinPay($ordersn);

					if (!is_error($payquery)) {
						$record = array();
						$record['status'] = '1';
						$record['type'] = 'wechat';
						pdo_update('core_paylog', $record, array('plid' => $log['plid']));
						$ret = array();
						$ret['result'] = 'success';
						$ret['type'] = 'wechat';
						$ret['from'] = 'return';
						$ret['tid'] = $log['tid'];
						$ret['user'] = $log['openid'];
						$ret['fee'] = $log['fee'];
						$ret['weid'] = $log['weid'];
						$ret['uniacid'] = $log['uniacid'];
						$ret['deduct'] = intval($_GPC['deduct']) == 1;

						if (p('channel')) {
							if ($ischannelpay == 1) {
								$ret['ischannelpay'] = $ischannelpay;
							}
						}

						if (!empty($order['pay_ordersn']) && empty($order['isverify'])) {
							$price = $order['price'];
							$order = pdo_fetch('select * from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':openid' => $openid));
							$order['price'] = $price;
							$address = false;

							if (empty($order['dispatchtype'])) {
								$address = pdo_fetch('select realname,mobile,address from ' . tablename('sz_yi_member_address') . ' where id=:id limit 1', array(':id' => $order['addressid']));
							}

							$carrier = false;
							if (($order['dispatchtype'] == 1) || ($order['isvirtual'] == 1)) {
								$carrier = unserialize($order['carrier']);
							}

							$pay_result = array('result' => 'success', 'order' => $order, 'address' => $address, 'carrier' => $carrier, 'virtual' => $order['virtual'], 'goods' => $orderdetail);
						}
						else {
							$pay_result = $this->payResult($ret);
						}

						$set = m('common')->getSysset();

						if (!empty($pay_result['verifycode'])) {
							if ($pset['sms']['type'] == 1) {
								if (($pay_result['verifycode']['SubmitResult']['code'] == 2) || ($allset['verify']['sendcode'] == 0) || empty($order['isverify'])) {
									show_json(1, $pay_result);
								}
								else {
									show_json(0, $pay_result['verifycode']['SubmitResult']['msg']);
								}
							}
							else {
								if (isset($pay_result['verifycode']['result']['success']) || ($allset['verify']['sendcode'] == 0) || empty($order['isverify'])) {
									show_json(1, $pay_result);
								}
								else {
									show_json(0, $pay_result['verifycode']['msg']);
								}
							}
						}
						else {
							show_json(1, $pay_result);
						}
					}

					show_json(0, '支付出错,请重试!');
				}
			}
		}
		else if ($operation == 'return') {
			$tid = $_GPC['out_trade_no'];

			if (!m('finance')->isAlipayNotify($_GET)) {
				exit('支付出现错误，请重试!');
			}

			$trade_no = array('trade_no' => $_GPC['trade_no']);
			pdo_update('sz_yi_order', $trade_no, array('pay_ordersn' => $_GPC['out_trade_no'], 'uniacid' => $uniacid));
			$log = pdo_fetch('SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid limit 1', array(':uniacid' => $uniacid, ':module' => 'sz_yi', ':tid' => $tid));

			if (empty($log)) {
				exit('支付出现错误，请重试!');
			}

			if ($log['status'] != 1) {
				$record = array();
				$record['status'] = '1';
				$record['type'] = 'alipay';
				pdo_update('core_paylog', $record, array('plid' => $log['plid']));
				$ret = array();
				$ret['result'] = 'success';
				$ret['type'] = 'alipay';
				$ret['from'] = 'return';
				$ret['tid'] = $log['tid'];
				$ret['user'] = $log['openid'];
				$ret['fee'] = $log['fee'];
				$ret['weid'] = $log['weid'];
				$ret['uniacid'] = $log['uniacid'];
				$this->payResult($ret);
			}

			$url = $this->createMobileUrl('order/list', array('status' => 1));
			exit('<script>top.window.location.href=\'' . $url . '\'</script>');
		}
		else if ($operation == 'returnyunpay') {
			$tids = $_REQUEST['i2'];
			$strs = explode(':', $tids);
			$tid = $strs[0];
			$pluginy = p('yunpay');

			if (!$pluginy->isYunpayNotify($_GET)) {
				exit('支付出现错误，请重试!');
			}

			$log = pdo_fetch('SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid limit 1', array(':uniacid' => $uniacid, ':module' => 'sz_yi', ':tid' => $tid));

			if (empty($log)) {
				exit('支付出现错误，请重试!');
			}

			if ($log['status'] != 1) {
				$record = array();
				$record['status'] = '1';
				$record['type'] = 'yunpay';
				pdo_update('core_paylog', $record, array('plid' => $log['plid']));
				$ret = array();
				$ret['result'] = 'success';
				$ret['type'] = 'yunpay';
				$ret['from'] = 'return';
				$ret['tid'] = $log['tid'];
				$ret['user'] = $log['openid'];
				$ret['fee'] = $log['fee'];
				$ret['weid'] = $log['weid'];
				$ret['uniacid'] = $log['uniacid'];
				$this->payResult($ret);
			}

			if (is_array($orderid)) {
				$url = $this->createMobileUrl('order/list');
			}
			else {
				$orderid = pdo_fetchcolumn('select id from ' . tablename('sz_yi_order') . ' where ordersn=:ordersn and uniacid=:uniacid', array(':ordersn' => $log['tid'], ':uniacid' => $_W['uniacid']));
				$url = $this->createMobileUrl('order/detail', array('id' => $orderid));
			}

			exit('<script>top.window.location.href=\'' . $url . '\'</script>');
		}
		else if ($operation == 'returnyeepay') {
			include IA_ROOT . '/addons/sz_yi/core/inc/plugin/vendor/yeepay/yeepay/yeepayMPay.php';
			$setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
			$set = unserialize($setdata['sets']);
			$merchantaccount = $set['pay']['merchantaccount'];
			$merchantPublicKey = $set['pay']['merchantPublicKey'];
			$merchantPrivateKey = $set['pay']['merchantPrivateKey'];
			$yeepayPublicKey = $set['pay']['yeepayPublicKey'];
			$yeepay = new yeepayMPay($merchantaccount, $merchantPublicKey, $merchantPrivateKey, $yeepayPublicKey);

			try {
				if (($_GET['data'] == '') || ($_GET['encryptkey'] == '')) {
					echo '参数不正确！';
					return NULL;
				}

				$data = $_GET['data'];
				$encryptkey = $_GET['encryptkey'];
				$return = $yeepay->callback($data, $encryptkey);
				list($tid, $type) = explode(':', $return['orderid']);

				if ($return['status'] == 1) {
					$trade_no = array('trade_no' => $return['yborderid']);
					pdo_update('sz_yi_order', $trade_no, array('ordersn_general' => $tid, 'uniacid' => $uniacid));
					$log = pdo_fetch('SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid limit 1', array(':uniacid' => $uniacid, ':module' => 'sz_yi', ':tid' => $tid));

					if (empty($log)) {
						exit('支付出现错误，请重试!');
					}

					if ($log['status'] != 1) {
						$record = array();
						$record['status'] = '1';
						$record['type'] = 'alipay';
						pdo_update('core_paylog', $record, array('plid' => $log['plid']));
						$ret = array();
						$ret['result'] = 'success';
						$ret['type'] = 'alipay';
						$ret['from'] = 'return';
						$ret['tid'] = $log['tid'];
						$ret['user'] = $log['openid'];
						$ret['fee'] = $log['fee'];
						$ret['weid'] = $log['weid'];
						$ret['uniacid'] = $log['uniacid'];
						$this->payResult($ret);
					}
				}

				$url = $this->createMobileUrl('order/list', array('status' => 1));
				exit('<script>top.window.location.href=\'' . $url . '\'</script>');
			}
			catch (yeepayMPayException $e) {
				echo '支付失败！';
				return NULL;
			}
		}
		else if ($operation == 'returnyeepay_wy') {
			include IA_ROOT . '/addons/sz_yi/core/inc/plugin/vendor/yeepay/wy/yeepayCommon.php';
			$setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
			$set = unserialize($setdata['sets']);
			$merchantaccount = $set['pay']['merchantaccount'];
			$merchantPublicKey = $set['pay']['merchantPublicKey'];
			$merchantPrivateKey = $set['pay']['merchantPrivateKey'];
			$yeepayPublicKey = $set['pay']['yeepayPublicKey'];
			$p1_MerId = $set['pay']['merchantaccount'];
			$merchantKey = $set['pay']['merchantKey'];
			$data = array();

			if ($_REQUEST['r9_BType'] == 1) {
				$data['p1_MerId'] = $_REQUEST['p1_MerId'];
				$data['r0_Cmd'] = $_REQUEST['r0_Cmd'];
				$data['r1_Code'] = $_REQUEST['r1_Code'];
				$data['r2_TrxId'] = $_REQUEST['r2_TrxId'];
				$data['r3_Amt'] = $_REQUEST['r3_Amt'];
				$data['r4_Cur'] = $_REQUEST['r4_Cur'];
				$data['r5_Pid'] = iconv('utf-8', 'gbk', $_REQUEST['r5_Pid']);
				$data['r6_Order'] = $_REQUEST['r6_Order'];
				$data['r7_Uid'] = $_REQUEST['r7_Uid'];
				$data['r8_MP'] = iconv('utf-8', 'gbk', $_REQUEST['r8_MP']);
				$data['r9_BType'] = $_REQUEST['r9_BType'];
				$data['hmac'] = $_REQUEST['hmac'];
				$data['hmac_safe'] = $_REQUEST['hmac_safe'];
			}
			else {
				$data['p1_MerId'] = $_REQUEST['p1_MerId'];
				$data['r0_Cmd'] = $_REQUEST['r0_Cmd'];
				$data['r1_Code'] = $_REQUEST['r1_Code'];
				$data['r2_TrxId'] = $_REQUEST['r2_TrxId'];
				$data['r3_Amt'] = $_REQUEST['r3_Amt'];
				$data['r4_Cur'] = $_REQUEST['r4_Cur'];
				$data['r5_Pid'] = $_REQUEST['r5_Pid'];
				$data['r6_Order'] = $_REQUEST['r6_Order'];
				$data['r7_Uid'] = $_REQUEST['r7_Uid'];
				$data['r8_MP'] = $_REQUEST['r8_MP'];
				$data['r9_BType'] = $_REQUEST['r9_BType'];
				$data['hmac'] = $_REQUEST['hmac'];
				$data['hmac_safe'] = $_REQUEST['hmac_safe'];
			}

			$hmacLocal = HmacLocal($data);
			$safeLocal = gethamc_safe($data);

			if ($data['r1_Code'] == '1') {
				$tid = $data['r6_Order'];
				$trade_no = array('trade_no' => $data['r2_TrxId']);
				pdo_update('sz_yi_order', $trade_no, array('ordersn_general' => $tid, 'uniacid' => $uniacid));
				$log = pdo_fetch('SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid limit 1', array(':uniacid' => $uniacid, ':module' => 'sz_yi', ':tid' => $tid));

				if (empty($log)) {
					exit('支付出现错误，请重试!');
				}

				if ($log['status'] != 1) {
					$record = array();
					$record['status'] = '1';
					$record['type'] = 'alipay';
					pdo_update('core_paylog', $record, array('plid' => $log['plid']));
					$ret = array();
					$ret['result'] = 'success';
					$ret['type'] = 'alipay';
					$ret['from'] = 'return';
					$ret['tid'] = $log['tid'];
					$ret['user'] = $log['openid'];
					$ret['fee'] = $log['fee'];
					$ret['weid'] = $log['weid'];
					$ret['uniacid'] = $log['uniacid'];
					$this->payResult($ret);
				}

				if ($data['r9_BType'] == '1') {
					$url = $this->createMobileUrl('order/list', array('status' => 1));
					exit('<script>top.window.location.href=\'' . $url . '\'</script>');
				}
				else {
					if ($data['r9_BType'] == '2') {
						echo 'SUCCESS';
						return NULL;
					}
				}
			}
		}
		else {
			if (($operation == 'orderstatus') && $_W['isajax']) {
				global $_W;
				$order = pdo_fetch('select status from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $orderid, ':uniacid' => $uniacid));
				show_json(1, $order);
			}
		}
	}
}

if ($operation == 'display') {
	include $this->template('order/pay');
}

?>
