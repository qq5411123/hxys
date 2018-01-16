<?php
// 唐上美联佳网络科技有限公司(技术支持)
class Sz_DYi_Order
{
	public function getStoreList()
	{
		global $_W;
		return pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_store') . ' WHERE uniacid=:uniacid and status=1', array(':uniacid' => $_W['uniacid']));
	}

	public function getDispatchPrice($weight, $dispatch_data, $calculatetype = -1)
	{
		if (empty($dispatch_data)) {
			return 0;
		}

		$price = 0;

		if ($calculatetype == -1) {
			$calculatetype = $dispatch_data['calculatetype'];
		}

		if ($calculatetype == 1) {
			if ($weight <= $dispatch_data['firstnum']) {
				$price = floatval($dispatch_data['firstnumprice']);
			}
			else {
				$price = floatval($dispatch_data['firstnumprice']);
				$secondweight = $weight - floatval($dispatch_data['firstnum']);
				$dsecondweight = (floatval($dispatch_data['secondnum']) <= 0 ? 1 : floatval($dispatch_data['secondnum']));
				$secondprice = 0;

				if (($secondweight % $dsecondweight) == 0) {
					$secondprice = ($secondweight / $dsecondweight) * floatval($dispatch_data['secondnumprice']);
				}
				else {
					$secondprice = ((int) ($secondweight / $dsecondweight) + 1) * floatval($dispatch_data['secondnumprice']);
				}

				$price += $secondprice;
			}
		}
		else if ($weight <= $dispatch_data['firstweight']) {
			$price = floatval($dispatch_data['firstprice']);
		}
		else {
			$price = floatval($dispatch_data['firstprice']);
			$secondweight = $weight - floatval($dispatch_data['firstweight']);
			$dsecondweight = (floatval($dispatch_data['secondweight']) <= 0 ? 1 : floatval($dispatch_data['secondweight']));
			$secondprice = 0;

			if (($secondweight % $dsecondweight) == 0) {
				$secondprice = ($secondweight / $dsecondweight) * floatval($dispatch_data['secondprice']);
			}
			else {
				$secondprice = ((int) ($secondweight / $dsecondweight) + 1) * floatval($dispatch_data['secondprice']);
			}

			$price += $secondprice;
		}

		return $price;
	}

	public function getCityDispatchPrice($areas, $city, $param, $dispatch_data)
	{
		if (is_array($areas) && (0 < count($areas))) {
			foreach ($areas as $area) {
				$citys = explode(';', $area['citys']);

				if (mb_strlen($city) == (mb_strrpos($city, '市') + 1)) {
					$_obf_DT0yBTgVQDJAPTcKFzkmFDs3LTYEIzI_ = mb_substr($city, 0, mb_strlen($city) - 1);
					if (!empty($citys) && (in_array($city, $citys) || in_array($_obf_DT0yBTgVQDJAPTcKFzkmFDs3LTYEIzI_ . '辖区', $citys) || in_array($_obf_DT0yBTgVQDJAPTcKFzkmFDs3LTYEIzI_ . '辖县', $citys))) {
						return $this->getDispatchPrice($param, $area, $dispatch_data['calculatetype']);
					}
				}

				if (!empty($citys) && in_array($city, $citys)) {
					return $this->getDispatchPrice($param, $area, $dispatch_data['calculatetype']);
				}
			}
		}

		return $this->getDispatchPrice($param, $dispatch_data);
	}

	public function isSupportDelivery($order_data = array())
	{
		global $_W;

		foreach ($order_data as $key => $order_value) {
			$_obf_DQ4mLS0uXBwIAhIzFywiLSc0LDUtAQE_ = intval($order_value['dispatchtype']);
			$_obf_DTMSBgImBTALIx8pHi8uJDQMJzISGjI_ = explode('|', $order_value['goods']);
			$_obf_DRktBgUYAVtbJDAmCRgmIzUnMhJAMTI_ = false;

			if ($_obf_DQ4mLS0uXBwIAhIzFywiLSc0LDUtAQE_ == '2') {
				$_obf_DQ4mLS0uXBwIAhIzFywiLSc0LDUtAQE_ = '0';
				$_obf_DRktBgUYAVtbJDAmCRgmIzUnMhJAMTI_ = true;
			}

			$_obf_DTc7CDArGi8BESs8MS8MKDs0KDwfKzI_ = array();

			foreach ($_obf_DTMSBgImBTALIx8pHi8uJDQMJzISGjI_ as $row1) {
				if (!empty($row1)) {
					$row1 = explode(',', $row1);
					$_obf_DTc7CDArGi8BESs8MS8MKDs0KDwfKzI_[] = $row1[0];
				}
			}

			if (!empty($_obf_DTc7CDArGi8BESs8MS8MKDs0KDwfKzI_) && is_array($_obf_DTc7CDArGi8BESs8MS8MKDs0KDwfKzI_)) {
				$goods_data = pdo_fetchall(' SELECT id,isverify,isverifysend,dispatchsend,title FROM ' . tablename('sz_yi_goods') . ' WHERE uniacid=:uniacid AND id IN (' . implode(',', $_obf_DTc7CDArGi8BESs8MS8MKDs0KDwfKzI_) . ')', array(':uniacid' => $_W['uniacid']));
			}

			foreach ($goods_data as $gdata) {
				$_obf_DSQBJDk0OBIKIQ4wKS8yMAYVOCgfBQE_ = false;
				$_obf_DRckHCUvBRocEjk_JggZGxk3FRgcLSI_ = false;
				if (($gdata['isverify'] == 2) && !$_obf_DRktBgUYAVtbJDAmCRgmIzUnMhJAMTI_) {
					$_obf_DSQBJDk0OBIKIQ4wKS8yMAYVOCgfBQE_ = true;
				}

				if (empty($_obf_DQ4mLS0uXBwIAhIzFywiLSc0LDUtAQE_) && $_obf_DSQBJDk0OBIKIQ4wKS8yMAYVOCgfBQE_) {
					$_obf_DRckHCUvBRocEjk_JggZGxk3FRgcLSI_ = true;
				}

				if ($_obf_DRckHCUvBRocEjk_JggZGxk3FRgcLSI_) {
					if ($gdata['isverifysend'] != 1) {
						$info = array('status' => -1, 'title' => $gdata['title']);
						return $info;
					}
				}

				if ($_obf_DRktBgUYAVtbJDAmCRgmIzUnMhJAMTI_) {
					if ($gdata['dispatchsend'] != 1) {
						$info = array('status' => -2, 'title' => $gdata['title']);
						return $info;
					}
				}
			}
		}

		return array('status' => 1);
	}

	public function payResult($params)
	{
		global $_W;
		$fee = $params['fee'];
		$uniacid = $_W['uniacid'];
		$data = array('status' => $params['result'] == 'success' ? 1 : 0);
		$ordersn = $params['tid'];
		$_obf_DR82HBYYExwbJgwxJVwxFBANKSI2MAE_ = pdo_fetchall('select * from ' . tablename('sz_yi_order') . ' where ordersn_general=:ordersn_general and uniacid=:uniacid', array(':ordersn_general' => $ordersn, ':uniacid' => $uniacid));

		if (1 < count($_obf_DR82HBYYExwbJgwxJVwxFBANKSI2MAE_)) {
			$order = array();
			$order['ordersn'] = $ordersn;
			$orderid = array();

			foreach ($_obf_DR82HBYYExwbJgwxJVwxFBANKSI2MAE_ as $key => $val) {
				$order['price'] += $val['price'];
				$order['deductcredit2'] += $val['deductcredit2'];
				$order['ordersn2'] += $val['ordersn2'];
				$orderid[] = $val['id'];
			}

			$order['dispatchtype'] = $val['dispatchtype'];
			$order['addressid'] = $val['addressid'];
			$order['isvirtual'] = $val['isvirtual'];
			$order['carrier'] = $val['carrier'];
			$order['status'] = $val['status'];
			$order['virtual'] = $val['virtual'];
			$order['couponid'] = $val['couponid'];
		}
		else {
			$order = $_obf_DR82HBYYExwbJgwxJVwxFBANKSI2MAE_[0];
			$orderid = $order['id'];
			$verify_set = m('common')->getSetData();
			$allset = iunserializer($verify_set['plugins']);
			$pset = m('common')->getSysset();
			if (($order['isverify'] == 1) && isset($allset['verify']) && ($allset['verify']['sendcode'] == 1) && isset($pset['sms']) && ($pset['sms']['type'] == 1)) {
				$carriers = unserialize($order['carrier']);
				$address = unserialize($order['address']);

				if (empty($order['dispatchtype'])) {
					$mobile = $address['mobile'];
				}
				else {
					$mobile = $carriers['carrier_mobile'];
				}

				$type = 'verify';
				$order_goods = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_order_goods') . ' WHERE orderid=:id and uniacid=:uniacid', array(':id' => $orderid, ':uniacid' => $_W['uniacid']));
				$goodstitle = pdo_fetchcolumn('SELECT title FROM ' . tablename('sz_yi_goods') . ' WHERE id=:id and uniacid=:uniacid', array(':id' => $order_goods['goodsid'], ':uniacid' => $_W['uniacid']));
				$store = pdo_fetch(' SELECT * FROM ' . tablename('sz_yi_store') . ' WHERE id=' . $order['storeid']);
				$issendsms = $this->sendSms($mobile, $order['verifycode'], 'reg', $type, $carriers['carrier_realname'], $goodstitle, $order_goods['total'], $store['tel']);
			}
		}

		$log = pdo_fetch('select * from ' . tablename('core_paylog') . ' where `uniacid`=:uniacid and fee=:fee and `module`=:module and `tid`=:tid limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'sz_yi', ':fee' => $fee, ':tid' => $ordersn));

		if (empty($log)) {
			show_json(-1, '订单金额错误, 请重试!');
			exit();
		}

		if ($params['from'] == 'return') {
			$address = false;

			if (empty($order['dispatchtype'])) {
				$address = pdo_fetch('select realname,mobile,address from ' . tablename('sz_yi_member_address') . ' where id=:id limit 1', array(':id' => $order['addressid']));
			}

			$carrier = false;
			if (($order['dispatchtype'] == 1) || ($order['isvirtual'] == 1)) {
				$carrier = unserialize($order['carrier']);
			}

			if ($params['type'] == 'cash') {
				return array('result' => 'success', 'order' => $order, 'address' => $address, 'carrier' => $carrier);
			}

			if (is_array($orderid)) {
				$orderids = implode(',', $orderid);
				$order_update = 'id in (' . $orderids . ')';
				$_obf_DQY_EAIrJjkCHxMvPg8oOTQfKDgEFAE_ = 'o.id in (' . $orderids . ')';
				$goods_where = 'og.orderid in (' . $orderids . ')';
			}
			else {
				$order_update = 'id = ' . $orderid;
				$_obf_DQY_EAIrJjkCHxMvPg8oOTQfKDgEFAE_ = 'o.id = ' . $orderid;
				$goods_where = 'og.orderid = ' . $orderid;
			}

			if ($order['status'] == 0) {
				$pv = p('virtual');
				if (!empty($order['virtual']) && $pv) {
					$pv->pay($order);
				}
				else {
					if (p('channel')) {
						if ($params['ischannelpay'] == 1) {
							pdo_query('update ' . tablename('sz_yi_order') . ' set status=3, paytime=' . time() . ', finishtime=' . time() . ', pay_ordersn=ordersn_general, ordersn_general=ordersn where ' . $order_update . ' and uniacid=\'' . $uniacid . '\' ');
						}
						else {
							pdo_query('update ' . tablename('sz_yi_order') . ' set status=1, paytime=' . time() . ', pay_ordersn=ordersn_general, ordersn_general=ordersn where ' . $order_update . ' and uniacid=\'' . $uniacid . '\' ');
						}
					}
					else {
						pdo_query('update ' . tablename('sz_yi_order') . ' set status=1, paytime=' . time() . ', pay_ordersn=ordersn_general, ordersn_general=ordersn where ' . $order_update . ' and uniacid=\'' . $uniacid . '\' ');
					}

					if (0 < $order['deductcredit2']) {
						$shopset = m('common')->getSysset('shop');
						m('member')->setCredit($order['openid'], 'credit2', 0 - $order['deductcredit2'], array(0, $shopset['name'] . '余额抵扣: ' . $order['deductcredit2'] . ' 订单号: ' . $order['ordersn']));
					}

					if (is_array($orderid)) {
						foreach ($_obf_DR82HBYYExwbJgwxJVwxFBANKSI2MAE_ as $k => $v) {
							$this->setStocksAndCredits($v['id'], 1);
							if (p('coupon') && !empty($v['couponid'])) {
								p('coupon')->backConsumeCoupon($v['id']);
							}

							m('notice')->sendOrderMessage($v['id']);

							if (p('commission')) {
								p('commission')->checkOrderPay($v['id']);
							}
						}
					}
					else {
						$this->setStocksAndCredits($orderid, 1);
						if (p('coupon') && !empty($order['couponid'])) {
							p('coupon')->backConsumeCoupon($orderid);
						}

						m('notice')->sendOrderMessage($orderid);

						if (p('commission')) {
							p('commission')->checkOrderPay($orderid);
						}
					}
				}
			}

			if (p('yunprint')) {
				$_obf_DQcUGVwwNRcQNiQkDRYwEDUnBwkBMBE_ = p('yunprint')->getSet();

				if ($_obf_DQcUGVwwNRcQNiQkDRYwEDUnBwkBMBE_['isopenprint'] == 1) {
					p('yunprint')->executePrint($order['id']);
				}
			}

			if (p('hotel')) {
				$set = set_medias(m('common')->getSysset('shop'), array('logo', 'img'));
				$print_order = $order;
				$ordergoods = pdo_fetchall('select * from ' . tablename('sz_yi_order_goods') . ' where uniacid=' . $_W['uniacid'] . ' and orderid=' . $orderid);

				foreach ($ordergoods as $key => $value) {
					$ordergoods[$key]['goodstitle'] = pdo_fetchcolumn('select title from ' . tablename('sz_yi_goods') . ' where uniacid=' . $_W['uniacid'] . ' and id=' . $value['goodsid']);
					$ordergoods[$key]['totalmoney'] = number_format($ordergoods[$key]['price'] * $value['total'], 2);
					$ordergoods[$key]['print_id'] = pdo_fetchcolumn('select print_id from ' . tablename('sz_yi_goods') . ' where uniacid=' . $_W['uniacid'] . ' and id=' . $value['goodsid']);
					$ordergoods[$key]['type'] = pdo_fetchcolumn('select type from ' . tablename('sz_yi_goods') . ' where uniacid=' . $_W['uniacid'] . ' and id=' . $value['goodsid']);
				}

				$print_order['goods'] = $ordergoods;
				$print_id = $print_order['goods'][0]['print_id'];
				$goodtype = $print_order['goods'][0]['type'];

				if ($print_id != '') {
					$print_detail = pdo_fetch('select * from ' . tablename('sz_yi_print_list') . ' where uniacid=' . $_W['uniacid'] . ' and id=' . $print_id);
					if (!empty($print_detail) && ($print_detail['status'] == '0')) {
						$member_code = $print_detail['member_code'];
						$device_no = $print_detail['print_no'];
						$key = $print_detail['key'];
						include IA_ROOT . '/addons/sz_yi/core/model/print.php';

						if ($goodtype == '99') {
							$sql2 = 'SELECT * FROM ' . tablename('sz_yi_order_room') . ' WHERE `orderid` = :orderid';
							$params2 = array(':orderid' => $orderid);
							$price_list = pdo_fetchall($sql2, $params2);
							$msgNo = testSendFreeMessage($print_order, $member_code, $device_no, $key, $set, $price_list);
						}
						else {
							if ($goodtype == '1') {
								$msgNo = testSendFreeMessageshop($print_order, $member_code, $device_no, $key, $set);
							}
						}
					}
				}
			}

			$orderdetail = pdo_fetch('select o.dispatchprice,o.ordersn,o.price,og.optionname as optiontitle,og.optionid,og.total from ' . tablename('sz_yi_order') . ' o left join ' . tablename('sz_yi_order_goods') . 'og on og.orderid = o.id where ' . $_obf_DQY_EAIrJjkCHxMvPg8oOTQfKDgEFAE_ . ' and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
			$sql = 'SELECT og.goodsid,og.total,g.title,g.thumb,og.price,og.optionname as optiontitle,og.optionid FROM ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on og.goodsid = g.id ' . ' where ' . $goods_where . ' order by og.id asc';
			$orderdetail['goods1'] = set_medias(pdo_fetchall($sql), 'thumb');
			$_obf_DSELDycVLjgrHAQFHBIlERgtHTYHFSI_ = p('love');

			if ($_obf_DSELDycVLjgrHAQFHBIlERgtHTYHFSI_) {
				$_obf_DSELDycVLjgrHAQFHBIlERgtHTYHFSI_->checkOrder($goods_where, $order['openid'], 0);
			}

			$orderdetail['goodscount'] = count($orderdetail['goods1']);
			return array('result' => 'success', 'order' => $order, 'address' => $address, 'carrier' => $carrier, 'virtual' => $order['virtual'], 'goods' => $orderdetail, 'verifycode' => $issendsms);
		}
	}

	public function sendSms($mobile, $code, $templateType = 'reg', $type = 'check', $name = '', $title = '', $total = '', $tel = '')
	{
		$set = m('common')->getSysset();

		if ($set['sms']['type'] == 1) {
			return send_sms($set['sms']['account'], $set['sms']['password'], $mobile, $code, $type, $name, $title, $total, $tel);
		}

		return send_sms_alidayu($mobile, $code, $templateType);
	}

	public function setStocksAndCredits($orderid = '', $type = 0)
	{
		global $_W;
		$verifyset = m('common')->getSetData();
		$allset = iunserializer($verifyset['plugins']);
		$store_total = false;
		if (isset($allset['verify']) && ($allset['verify']['store_total'] == 1)) {
			$store_total = true;
		}

		$order = pdo_fetch('select id,ordersn,price,openid,dispatchtype,addressid,carrier,status,storeid from ' . tablename('sz_yi_order') . ' where id=:id limit 1', array(':id' => $orderid));
		$cond = '';

		if (p('channel')) {
			$cond = ',og.channel_id,og.ischannelpay';
		}

		$goods = pdo_fetchall('select og.id,og.goodsid' . $cond . ',og.total,g.totalcnf,og.realprice, g.credit,og.optionid,g.total as goodstotal,og.optionid,g.sales,g.salesreal from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid ' . ' where og.orderid=:orderid and og.uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':orderid' => $orderid));
		$credits = 0;

		foreach ($goods as $g) {
			$stocktype = 0;

			if ($type == 0) {
				if ($g['totalcnf'] == 0) {
					$stocktype = -1;
				}
			}
			else if ($type == 1) {
				if ($g['totalcnf'] == 1) {
					$stocktype = -1;
				}
			}
			else {
				if ($type == 2) {
					if (1 <= $order['status']) {
						if ($g['totalcnf'] == 1) {
							$stocktype = 1;
						}
					}
					else {
						if ($g['totalcnf'] == 0) {
							$stocktype = 1;
						}
					}
				}
			}

			if (!empty($stocktype)) {
				if (!empty($g['optionid'])) {
					if (p('channel')) {
						if (!empty($g['channel_id'])) {
							$my_info = p('channel')->getInfo($order['openid'], $g['goodsid'], $g['optionid'], $g['total']);

							if (!empty($my_info['up_level']['stock'])) {
								$stock = -1;

								if ($stocktype == 1) {
									$stock = $my_info['up_level']['stock']['stock_total'] + $g['total'];
								}
								else {
									if ($stocktype == -1) {
										$stock = $my_info['up_level']['stock']['stock_total'] - $g['total'];
									}
								}

								if ($stock != -1) {
									pdo_update('sz_yi_channel_stock', array('stock_total' => $stock), array('uniacid' => $_W['uniacid'], 'goodsid' => $g['goodsid'], 'openid' => $my_info['up_level']['openid'], 'optionid' => $g['optionid']));
									$channel = true;
								}

								$goods_price = pdo_fetchcolumn('SELECT marketprice FROM ' . tablename('sz_yi_goods') . ' WHERE uniacid=' . $_W['uniacid'] . ' AND id=' . $g['goodsid']);
								$up_mem = m('member')->getInfo($order['openid']);
								$log_data = array('goodsid' => $g['goodsid'], 'optionid' => $g['optionid'], 'order_goodsid' => $g['id'], 'uniacid' => $_W['uniacid'], 'every_turn' => $g['total'], 'goods_price' => $goods_price, 'surplus_stock' => $stock, 'mid' => $up_mem['id'], 'paytime' => time());

								if (!empty($my_info['up_level'])) {
									$log_data['openid'] = $my_info['up_level']['openid'];
								}

								if (!empty($g['ischannelpay'])) {
									$log_data['every_turn_price'] = ($goods_price * $my_info['my_level']['purchase_discount']) / 100;
									$log_data['every_turn_discount'] = $my_info['my_level']['purchase_discount'];
									$log_data['type'] = 2;
									pdo_insert('sz_yi_channel_stock_log', $log_data);
								}
								else {
									$log_data['every_turn_price'] = $goods_price;
									$log_data['every_turn_discount'] = 0;
									$log_data['type'] = 3;
									pdo_insert('sz_yi_channel_stock_log', $log_data);
								}
							}
						}
					}

					if (empty($channel) && !$store_total) {
						$option = m('goods')->getOption($g['goodsid'], $g['optionid']);
						if (!empty($option) && ($option['stock'] != -1)) {
							$stock = -1;

							if ($stocktype == 1) {
								$stock = $option['stock'] + $g['total'];
							}
							else {
								if ($stocktype == -1) {
									$stock = $option['stock'] - $g['total'];
									($stock <= 0) && ($stock = 0);
								}
							}

							if ($stock != -1) {
								pdo_update('sz_yi_goods_option', array('stock' => $stock), array('uniacid' => $_W['uniacid'], 'goodsid' => $g['goodsid'], 'id' => $g['optionid']));
							}
						}
					}

					if (!empty($order['storeid']) && $store_total) {
						$option = pdo_fetch('SELECT total as stock FROM ' . tablename('sz_yi_store_goods') . ' WHERE goodsid=:goodsid and optionid=:optionid and uniacid=:uniacid and storeid=:storeid', array(':goodsid' => $g['goodsid'], ':optionid' => $g['optionid'], ':uniacid' => $_W['uniacid'], ':storeid' => $order['storeid']));
						if (!empty($option) && ($option['stock'] != -1)) {
							$stock = -1;

							if ($stocktype == 1) {
								$stock = $option['stock'] + $g['total'];
							}
							else {
								if ($stocktype == -1) {
									$stock = $option['stock'] - $g['total'];
									($stock <= 0) && ($stock = 0);
								}
							}

							if ($stock != -1) {
								pdo_update('sz_yi_store_goods', array('total' => $stock), array('uniacid' => $_W['uniacid'], 'goodsid' => $g['goodsid'], 'optionid' => $g['optionid'], 'storeid' => $order['storeid']));
							}
						}
					}
				}
				else {
					if (p('channel')) {
						if (empty($channel)) {
							if (!empty($g['channel_id'])) {
								$my_info = p('channel')->getInfo($order['openid'], $g['goodsid'], 0, $g['total']);

								if (!empty($my_info['up_level']['stock'])) {
									$totalstock = -1;

									if ($stocktype == 1) {
										$totalstock = $my_info['up_level']['stock']['stock_total'] + $g['total'];
									}
									else {
										if ($stocktype == -1) {
											$totalstock = $my_info['up_level']['stock']['stock_total'] - $g['total'];
										}
									}

									if ($totalstock != -1) {
										pdo_update('sz_yi_channel_stock', array('stock_total' => $totalstock), array('uniacid' => $_W['uniacid'], 'goodsid' => $g['goodsid'], 'openid' => $my_info['up_level']['openid']));
										$channels = true;
									}

									$goods_price = pdo_fetchcolumn('SELECT marketprice FROM ' . tablename('sz_yi_goods') . ' WHERE uniacid=' . $_W['uniacid'] . ' AND id=' . $g['goodsid']);
									$up_mem = m('member')->getInfo($order['openid']);
									$log_data = array('goodsid' => $g['goodsid'], 'order_goodsid' => $g['id'], 'uniacid' => $_W['uniacid'], 'every_turn' => $g['total'], 'goods_price' => $goods_price, 'surplus_stock' => $totalstock, 'mid' => $up_mem['id'], 'paytime' => time());

									if (!empty($my_info['up_level'])) {
										$log_data['openid'] = $my_info['up_level']['openid'];
									}

									if (!empty($g['ischannelpay'])) {
										$log_data['every_turn_price'] = ($goods_price * $my_info['my_level']['purchase_discount']) / 100;
										$log_data['every_turn_discount'] = $my_info['my_level']['purchase_discount'];
										$log_data['type'] = 2;
										pdo_insert('sz_yi_channel_stock_log', $log_data);
									}
									else {
										$log_data['every_turn_price'] = $goods_price;
										$log_data['every_turn_discount'] = 0;
										$log_data['type'] = 3;
										pdo_insert('sz_yi_channel_stock_log', $log_data);
									}
								}
							}
						}
					}

					if (empty($channels) && !$store_total) {
						if (!empty($g['goodstotal']) && ($g['goodstotal'] != -1)) {
							$totalstock = -1;

							if ($stocktype == 1) {
								$totalstock = $g['goodstotal'] + $g['total'];
							}
							else {
								if ($stocktype == -1) {
									$totalstock = $g['goodstotal'] - $g['total'];
									($totalstock <= 0) && ($totalstock = 0);
								}
							}

							if ($totalstock != -1) {
								pdo_update('sz_yi_goods', array('total' => $totalstock), array('uniacid' => $_W['uniacid'], 'id' => $g['goodsid']));
							}
						}
					}

					if (!empty($order['storeid']) && $store_total) {
						$store_goods = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_store_goods') . ' WHERE goodsid=:goodsid and storeid=:storeid and optionid=0', array(':goodsid' => $g['goodsid'], ':storeid' => $order['storeid']));
						if (!empty($store_goods['total']) && ($store_goods['total'] != -1)) {
							$totalstock = -1;

							if ($stocktype == 1) {
								$totalstock = $store_goods['total'] + $g['total'];
							}
							else {
								if ($stocktype == -1) {
									$totalstock = $store_goods['total'] - $g['total'];
									($totalstock <= 0) && ($totalstock = 0);
								}
							}

							if ($totalstock != -1) {
								pdo_update('sz_yi_store_goods', array('total' => $totalstock), array('uniacid' => $_W['uniacid'], 'goodsid' => $g['goodsid'], 'storeid' => $order['storeid'], 'optionid' => 0));
							}
						}
					}
				}
			}

			$gcredit = trim($g['credit']);

			if (!empty($gcredit)) {
				if (strexists($gcredit, '%')) {
					$credits += intval((floatval(str_replace('%', '', $gcredit)) / 100) * $g['realprice']);
				}
				else {
					$credits += intval($g['credit']) * $g['total'];
				}
			}

			if ($type == 0) {
				pdo_update('sz_yi_goods', array('sales' => $g['sales'] + $g['total']), array('uniacid' => $_W['uniacid'], 'id' => $g['goodsid']));
			}
			else {
				if ($type == 1) {
					if (1 <= $order['status']) {
						$salesreal = pdo_fetchcolumn('select ifnull(sum(total),0) from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where og.goodsid=:goodsid and o.status>=1 and o.uniacid=:uniacid limit 1', array(':goodsid' => $g['goodsid'], ':uniacid' => $_W['uniacid']));
						pdo_update('sz_yi_goods', array('salesreal' => $salesreal), array('id' => $g['goodsid']));
					}
				}
			}
		}

		if (0 < $credits) {
			$shopset = m('common')->getSysset('shop');

			if ($type == 1) {
				m('member')->setCredit($order['openid'], 'credit1', $credits, array(0, $shopset['name'] . '购物积分 订单号: ' . $order['ordersn']));
				pdo_update('sz_yi_order', array('credit1' => $credits), array('ordersn' => $order['ordersn'], 'uniacid' => $_W['uniacid']));
				return NULL;
			}

			if ($type == 2) {
				if (1 <= $order['status']) {
					m('member')->setCredit($order['openid'], 'credit1', 0 - $credits, array(0, $shopset['name'] . '购物取消订单扣除积分 订单号: ' . $order['ordersn']));
				}
			}
		}
	}

	public function getDefaultDispatch($supplier_uid = 0)
	{
		global $_W;
		$sql = 'select * from ' . tablename('sz_yi_dispatch') . ' where isdefault=1 and uniacid=:uniacid and enabled=1 and supplier_uid=:supplier_uid Limit 1';
		$prem = array(':supplier_uid' => $supplier_uid, ':uniacid' => $_W['uniacid']);
		$_obf_DQguISQsFTMsDTM7PhctMRcoWwE4EDI_ = pdo_fetch($sql, $prem);
		return $_obf_DQguISQsFTMsDTM7PhctMRcoWwE4EDI_;
	}

	public function getNewDispatch($supplier_uid = 0)
	{
		global $_W;
		$sql = 'select * from ' . tablename('sz_yi_dispatch') . ' where uniacid=:uniacid and enabled=1 and supplier_uid=:supplier_uid order by id desc Limit 1';
		$prem = array(':supplier_uid' => $supplier_uid, ':uniacid' => $_W['uniacid']);
		$_obf_DQEvJh1ACj0KKFw4PiQVJi4jGxYePzI_ = pdo_fetch($sql, $prem);
		return $_obf_DQEvJh1ACj0KKFw4PiQVJi4jGxYePzI_;
	}

	public function getOneDispatch($dispatch_id, $supplier_uid = 0)
	{
		global $_W;
		$sql = 'select * from ' . tablename('sz_yi_dispatch') . ' where id=:id and uniacid=:uniacid and enabled=1 and supplier_uid=:supplier_uid Limit 1';
		$prem = array(':supplier_uid' => $supplier_uid, ':id' => $dispatch_id, ':uniacid' => $_W['uniacid']);
		$_obf_DT0VWxAICkAxDy0OFz0VIQ4SBQk_LTI_ = pdo_fetch($sql, $prem);
		return $_obf_DT0VWxAICkAxDy0OFz0VIQ4SBQk_LTI_;
	}

	public function autoexec($uniacid = 0)
	{
		global $_W;
		global $_GPC;

		if (empty($uniacid)) {
			return NULL;
		}

		$_W['uniacid'] = $uniacid;
		$trade = m('common')->getSysset('trade', $_W['uniacid']);
		$days = intval($trade['receive']);

		if (0 < $days) {
			$daytimes = 86400 * $days;
			$p = p('commission');
			$pcoupon = p('coupon');
			$orders = pdo_fetchall('select id,couponid from ' . tablename('sz_yi_order') . ' where uniacid=' . $_W['uniacid'] . ' and status=2 and sendtime + ' . $daytimes . ' <=unix_timestamp() ', array(), 'id');

			if (!empty($orders)) {
				$orderkeys = array_keys($orders);
				$orderids = implode(',', $orderkeys);

				if (!empty($orderids)) {
					pdo_query('update ' . tablename('sz_yi_order') . ' set status=3,finishtime=' . time() . ' where id in (' . $orderids . ')');

					foreach ($orders as $orderid => $o) {
						m('notice')->sendOrderMessage($orderid);

						if ($pcoupon) {
							if (!empty($o['couponid'])) {
								$pcoupon->backConsumeCoupon($o['id']);
							}
						}

						if ($p) {
							$p->checkOrderFinish($orderid);
						}
					}
				}
			}
		}

		$days = intval($trade['closeorder']);

		if (0 < $days) {
			$daytimes = 86400 * $days;
			$orders = pdo_fetchall('select id from ' . tablename('sz_yi_order') . ' where  uniacid=' . $_W['uniacid'] . ' and status=0 and paytype<>3  and createtime + ' . $daytimes . ' <=unix_timestamp() ');
			$p = p('coupon');

			foreach ($orders as $o) {
				$onew = pdo_fetch('select status from ' . tablename('sz_yi_order') . ' where id=:id and status=0 and paytype<>3  and createtime + ' . $daytimes . ' <=unix_timestamp()  limit 1', array(':id' => $o['id']));
				if (!empty($onew) && ($onew['status'] == 0)) {
					pdo_query('update ' . tablename('sz_yi_order') . ' set status=-1,canceltime=' . time() . ' where id=' . $o['id']);

					if ($p) {
						if (!empty($o['couponid'])) {
							$p->returnConsumeCoupon($o['id']);
						}
					}
				}
			}
		}
	}
}

if (!defined('IN_IA')) {
	exit('Access Denied');
}

?>
