<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace api\controller\order;

class ChangeStatus extends \api\YZ
{
	private $order_info;

	public function __construct()
	{
		parent::__construct();
		$para = $this->getPara();
		$order_model = new \api\model\order();
		$this->order_info = $order_model->getInfo(array('id' => $para['order_id'], 'uniacid' => $para['uniacid']));
	}

	public function close()
	{
		global $_W;
		global $_GPC;
		$this->ca('order.op.close');
		$order = $this->order_info;

		if ($order['status'] == -1) {
			$this->returnError('订单已关闭，无需重复关闭！');
		}
		else {
			if (1 <= $order['status']) {
				$this->returnError('订单已付款，不能关闭！');
			}
		}

		if (!empty($order['transid'])) {
			changeWechatSend($order['ordersn'], 0, $_GPC['reson']);
		}

		$time = time();
		if ((0 < $order['refundstate']) && !empty($order['refundid'])) {
			$data = array();
			$data['status'] = -1;
			$data['refundtime'] = $time;
			pdo_update('sz_yi_order_refund', $data, array('id' => $order['refundid'], 'uniacid' => $_W['uniacid']));
		}

		pdo_update('sz_yi_order', array('status' => -1, 'refundstate' => 0, 'canceltime' => time(), 'remark' => $order['remark'] . '' . $_GPC['remark']), array('id' => $order['id'], 'uniacid' => $_W['uniacid']));

		if (0 < $order['deductcredit']) {
			$shopset = m('common')->getSysset('shop');
			m('member')->setCredit($order['openid'], 'credit1', $order['deductcredit'], array('0', $shopset['name'] . '购物返还抵扣积分 积分: ' . $order['deductcredit'] . ' 抵扣金额: ' . $order['deductprice'] . ' 订单号: ' . $order['ordersn']));
		}

		if (p('coupon') && !empty($order['couponid'])) {
			p('coupon')->returnConsumeCoupon($order['id']);
		}

		plog('order.op.close', '订单关闭 ID: ' . $order['id'] . ' 订单号: ' . $order['ordersn']);
		$res = array(
			'status' => array('name' => '已关闭', 'value' => '-1')
			);
		$this->returnSuccess($res, '订单关闭操作成功！');
	}

	public function cancelSend()
	{
		global $_W;
		global $_GPC;
		$this->ca('order.op.sendcancel');
		$order = $this->order_info;

		if ($order['status'] != 2) {
			$this->returnError('订单未发货，不需取消发货！');
		}

		if (!empty($order['transid'])) {
			changeWechatSend($order['ordersn'], 0, $_GPC['cancelreson']);
		}

		pdo_update('sz_yi_order', array('status' => 1, 'sendtime' => 0), array('id' => $order['id'], 'uniacid' => $_W['uniacid']));
		plog('order.op.sencancel', '订单取消发货 ID: ' . $order['id'] . ' 订单号: ' . $order['ordersn']);
		$res = array(
			'status' => array('name' => '待发货', 'value' => '1')
			);
		$this->returnSuccess($res, '取消发货操作成功！');
	}

	public function finish()
	{
		global $_W;
		$this->ca('order.op.finish');
		$order = $this->order_info;
		pdo_update('sz_yi_order', array('status' => 3, 'finishtime' => time()), array('id' => $order['id'], 'uniacid' => $_W['uniacid']));
		m('member')->upgradeLevel($order['openid']);
		m('notice')->sendOrderMessage($order['id']);
		if (p('coupon') && !empty($order['couponid'])) {
			p('coupon')->backConsumeCoupon($order['id']);
		}

		if (p('commission')) {
			p('commission')->checkOrderFinish($order['id']);
		}

		if (p('return')) {
			p('return')->cumulative_order_amount($order['id']);
		}

		if (0 < $order['redprice']) {
			m('finance')->sendredpack($order['openid'], $order['redprice'] * 100, $order['id'], $desc = '购买商品赠送红包', $act_name = '购买商品赠送红包', $remark = '购买商品确认收货发送红包');
		}

		plog('order.op.finish', '订单完成 ID: ' . $order['id'] . ' 订单号: ' . $order['ordersn']);
		$res = array(
			'status' => array('name' => '已完成', 'value' => '3')
			);
		$this->returnSuccess($res, '订单操作成功！');
	}

	public function getCounty()
	{
		$list = pdo_fetchall('SELECT * FROM ' . tablename('county'));
		$list = json_encode($list, JSON_UNESCAPED_UNICODE);
		dump($list);
		exit();
		return $list;
	}

	public function test($province_list)
	{
		unset($province_list[0]);

		foreach ($province_list as $province) {
			$province_name = $province['@attributes']['name'];
			pdo_insert('province', array('name' => $province_name));
			$province_id = pdo_insertid();

			foreach ($province['city'] as $city) {
				$city_name = $city['@attributes']['name'];
				pdo_insert('city', array('name' => $city_name, 'pid' => $province_id));
				$city_id = pdo_insertid();

				foreach ($city['county'] as $county) {
					$county_name = $county['@attributes']['name'];
					pdo_insert('county', array('name' => $county_name, 'pid' => $city_id));
				}
			}
		}
	}

	public function getShippingInfo()
	{
		$order = $this->order_info;
		dump($order);
		exit();
	}

	public function getExpressInfo()
	{
		$order = $this->order_info;
		$address = unserialize($order['address']);
		$address = array('addressid' => $address['id'], 'realname' => $address['realname'], 'mobile' => $address['mobile'], 'address' => array_part('province,city,area,address', $address));
		$_obf_DRUYBg0xPTQeHxwEMCFANyM_MVsBFBE_ = file_get_contents(__API_ROOT__ . '/source/expresscom.json');
		$company_list = json_decode($_obf_DRUYBg0xPTQeHxwEMCFANyM_MVsBFBE_, true);
		$res = array('company_list' => $company_list, 'address' => $address);
		dump($res);
		$this->returnSuccess($res);
	}

	public function confirmSend()
	{
		$this->ca('order.op.send');
		global $_W;
		$para = $this->getPara();
		$order = $this->order_info;

		if (empty($order['addressid'])) {
			$this->returnError('无收货地址，无法发货！');
		}

		if ($order['paytype'] != 3) {
			if ($order['status'] != 1) {
				$this->returnError('订单未付款，无法发货！');
			}
		}

		if (!empty($para['isexpress']) && empty($para['expresssn'])) {
			$this->returnError('请输入快递单号！');
		}

		if (!empty($order['transid'])) {
			changeWechatSend($order['ordersn'], 1);
		}

		pdo_update('sz_yi_order', array('status' => 2, 'express' => trim($para['express']), 'expresscom' => trim($para['expresscom']), 'expresssn' => trim($para['expresssn']), 'sendtime' => time()), array('id' => $order['id'], 'uniacid' => $_W['uniacid']));

		if (!empty($order['refundid'])) {
			$_obf_DSMHBy0aBDwtCz4ZCDgPJiwbEhAjMAE_ = pdo_fetch('select * from ' . tablename('sz_yi_order_refund') . ' where id=:id limit 1', array(':id' => $order['refundid']));

			if (!empty($_obf_DSMHBy0aBDwtCz4ZCDgPJiwbEhAjMAE_)) {
				pdo_update('sz_yi_order_refund', array('status' => -1), array('id' => $order['refundid']));
				pdo_update('sz_yi_order', array('refundid' => 0), array('id' => $order['id']));
			}
		}

		m('notice')->sendOrderMessage($order['id']);
		plog('order.op.send', '订单发货 ID: ' . $order['id'] . ' 订单号: ' . $order['ordersn'] . ' <br/>快递公司: ' . $para['expresscom'] . ' 快递单号: ' . $para['expresssn']);
		$res = array(
			'status' => array('name' => '待收货', 'value' => '2')
			);
		$this->returnSuccess($res, '发货操作成功！');
	}

	public function confirmFetch()
	{
		$para = $this->getPara();
		$order = $this->order_info;
		$this->ca('order.op.fetch');

		if ($order['status'] != 1) {
			$this->returnError('订单未付款，无法确认取货！');
		}

		$now_time = time();
		$update_data = array('status' => 3, 'sendtime' => $now_time, 'finishtime' => $now_time);

		if ($order['isverify'] == 1) {
			$update_data['verified'] = 1;
			$update_data['verifytime'] = $now_time;
			$update_data['verifyopenid'] = '';
		}

		pdo_update('sz_yi_order', $update_data, array('id' => $order['id'], 'uniacid' => $para['uniacid']));

		if (!empty($order['refundid'])) {
			$update_result = pdo_fetch('select * from ' . tablename('sz_yi_order_refund') . ' where id=:id limit 1', array(':id' => $order['refundid']));

			if (!empty($update_result)) {
				pdo_update('sz_yi_order_refund', array('status' => -1), array('id' => $order['refundid']));
				pdo_update('sz_yi_order', array('refundid' => 0), array('id' => $order['id']));
			}
		}

		m('member')->upgradeLevel($order['openid']);
		m('notice')->sendOrderMessage($order['id']);

		if (p('commission')) {
			p('commission')->checkOrderFinish($order['id']);
		}

		if (p('return')) {
			p('return')->cumulative_order_amount($order['id']);
		}

		plog('order.op.fetch', '订单确认取货 ID: ' . $order['id'] . ' 订单号: ' . $order['ordersn']);
		$res = array(
			'status' => array('name' => '已完成', 'value' => '3')
			);
		$this->returnSuccess($res, '发货操作成功！');
	}

	public function confirmPay()
	{
		$this->ca('order.op.pay');
		$para = $this->getPara();
		$order = $this->order_info;

		if (1 < $order['status']) {
			$this->returnError('订单已付款，不需重复付款！');
		}

		$virtual = p('virtual');
		if (!empty($order['virtual']) && $virtual) {
			$virtual->pay($order);
			$res = array(
				'status' => array('name' => '已完成', 'value' => '3')
				);
		}
		else {
			$ordersn_general = pdo_fetchcolumn('select ordersn_general from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $order['id'], ':uniacid' => $para['uniacid']));
			$order_all = pdo_fetchall('select * from ' . tablename('sz_yi_order') . ' where ordersn_general=:ordersn_general and uniacid=:uniacid', array(':ordersn_general' => $ordersn_general, ':uniacid' => $para['uniacid']));
			$plugin_coupon = p('coupon');
			$plugin_commission = p('commission');
			$orderid = array();

			foreach ($order_all as $key => $val) {
				m('order')->setStocksAndCredits($val['id'], 1);
				m('notice')->sendOrderMessage($val['id']);
				if ($plugin_coupon && !empty($val['couponid'])) {
					$plugin_coupon->backConsumeCoupon($val['id']);
				}

				if ($plugin_commission) {
					$plugin_commission->checkOrderPay($val['id']);
				}

				$price += $val['price'];
				$orderid[] = $val['id'];
			}

			$log = pdo_fetch('SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid limit 1', array(':uniacid' => $para['uniacid'], ':module' => 'sz_yi', ':tid' => $ordersn_general));
			if (!empty($log) && ($log['status'] != '0')) {
				$this->returnError('订单已支付, 无需重复支付!');
			}

			if (!empty($log) && ($log['status'] == '0')) {
				pdo_delete('core_paylog', array('plid' => $log['plid']));
				$log = null;
			}

			if (empty($log)) {
				$log = array('uniacid' => $para['uniacid'], 'openid' => $order['openid'], 'module' => 'sz_yi', 'tid' => $ordersn_general, 'fee' => $price, 'status' => 0);
				pdo_insert('core_paylog', $log);
			}

			if (is_array($orderid)) {
				$orderids = implode(',', $orderid);
				$where_update = 'id in (' . $orderids . ')';
			}

			pdo_query('update ' . tablename('sz_yi_order') . ' set paytype=11 where ' . $where_update . ' and uniacid=:uniacid ', array(':uniacid' => $para['uniacid']));
			$ret = array();
			$ret['result'] = 'success';
			$ret['from'] = 'return';
			$ret['tid'] = $log['tid'];
			$ret['user'] = $order['openid'];
			$ret['fee'] = $price;
			$ret['weid'] = $para['uniacid'];
			$ret['uniacid'] = $para['uniacid'];
			$payresult = m('order')->payResult($ret);
			$res = array(
				'status' => array('name' => '待发货', 'value' => '1')
				);
		}

		plog('order.op.pay', '订单确认付款 ID: ' . $order['id'] . ' 订单号: ' . $order['ordersn']);
		$this->returnSuccess($res, '确认订单付款操作成功！');
		exit();
	}

	public function changeWechatSend($zym_var_2, $zym_var_4, $zym_var_1 = '')
	{
		global $_W;
		$_obf_DQ4wDCcXLQoUJCUaESgQJB8XMzAXMiI_ = pdo_fetch('SELECT plid, openid, tag FROM ' . tablename('core_paylog') . ' WHERE tid = \'' . $zym_var_2 . '\' AND status = 1 AND type = \'wechat\'');

		if (!empty($_obf_DQ4wDCcXLQoUJCUaESgQJB8XMzAXMiI_['openid'])) {
			$_obf_DQ4wDCcXLQoUJCUaESgQJB8XMzAXMiI_['tag'] = iunserializer($_obf_DQ4wDCcXLQoUJCUaESgQJB8XMzAXMiI_['tag']);
			$_obf_DRQbQDUbMx8SPSMcH0AGWzsjOzAhBTI_ = $_obf_DQ4wDCcXLQoUJCUaESgQJB8XMzAXMiI_['tag']['acid'];
			load()->model('account');
			$_obf_DQ0LFAsoFAkLBTQDEQE_CgoQES8FDDI_ = account_fetch($_obf_DRQbQDUbMx8SPSMcH0AGWzsjOzAhBTI_);
			$_obf_DTg9JkAEOSUJIkAfFD0CGSU3L1wWBjI_ = uni_setting($_obf_DQ0LFAsoFAkLBTQDEQE_CgoQES8FDDI_['uniacid'], 'payment');

			if ($_obf_DTg9JkAEOSUJIkAfFD0CGSU3L1wWBjI_['payment']['wechat']['version'] == '2') {
				return true;
			}

			$_obf_DQguFj8eCQk_MAMqEQJbJzYVMBsDPQE_ = array('appid' => $_obf_DQ0LFAsoFAkLBTQDEQE_CgoQES8FDDI_['key'], 'openid' => $_obf_DQ4wDCcXLQoUJCUaESgQJB8XMzAXMiI_['openid'], 'transid' => $_obf_DQ4wDCcXLQoUJCUaESgQJB8XMzAXMiI_['tag']['transaction_id'], 'out_trade_no' => $_obf_DQ4wDCcXLQoUJCUaESgQJB8XMzAXMiI_['plid'], 'deliver_timestamp' => TIMESTAMP, 'deliver_status' => $zym_var_4, 'deliver_msg' => $zym_var_1);
			$_obf_DQsUDjMmPS0iEh0XHQQ3CgEiKQU_JhE_ = $_obf_DQguFj8eCQk_MAMqEQJbJzYVMBsDPQE_;
			$_obf_DQsUDjMmPS0iEh0XHQQ3CgEiKQU_JhE_['appkey'] = $_obf_DTg9JkAEOSUJIkAfFD0CGSU3L1wWBjI_['payment']['wechat']['signkey'];
			ksort($_obf_DQsUDjMmPS0iEh0XHQQ3CgEiKQU_JhE_);
			$_obf_DQE4NDcPJisTWyo3QAc8Kh8MPg4fOwE_ = '';

			foreach ($_obf_DQsUDjMmPS0iEh0XHQQ3CgEiKQU_JhE_ as $_obf_DRU9Jh8hIgYdJhIJPgkTHVs8JAc8DBE_ => $_obf_DRMeFzIDNDJbFj05Fy00BBQTFDY0LwE_) {
				$_obf_DRU9Jh8hIgYdJhIJPgkTHVs8JAc8DBE_ = strtolower($_obf_DRU9Jh8hIgYdJhIJPgkTHVs8JAc8DBE_);
				$_obf_DQE4NDcPJisTWyo3QAc8Kh8MPg4fOwE_ .= $_obf_DRU9Jh8hIgYdJhIJPgkTHVs8JAc8DBE_ . '=' . $_obf_DRMeFzIDNDJbFj05Fy00BBQTFDY0LwE_ . '&';
			}

			$_obf_DQguFj8eCQk_MAMqEQJbJzYVMBsDPQE_['app_signature'] = sha1(rtrim($_obf_DQE4NDcPJisTWyo3QAc8Kh8MPg4fOwE_, '&'));
			$_obf_DQguFj8eCQk_MAMqEQJbJzYVMBsDPQE_['sign_method'] = 'sha1';
			$_obf_DQ0LFAsoFAkLBTQDEQE_CgoQES8FDDI_ = WeAccount::create($_obf_DRQbQDUbMx8SPSMcH0AGWzsjOzAhBTI_);
			$_obf_DR02FBsnORceKCgiKD8WJCcxJjY4HwE_ = $_obf_DQ0LFAsoFAkLBTQDEQE_CgoQES8FDDI_->changeOrderStatus($_obf_DQguFj8eCQk_MAMqEQJbJzYVMBsDPQE_);

			if (is_error($_obf_DR02FBsnORceKCgiKD8WJCcxJjY4HwE_)) {
				$this->returnError($_obf_DR02FBsnORceKCgiKD8WJCcxJjY4HwE_['message']);
			}
		}
	}

	public function refund()
	{
		global $_W;
		$this->ca('order.op.refund');
		$para = $this->getPara();
		$order = $this->order_info;
		$shopset = m('common')->getSysset('shop');

		if (empty($order['refundstate'])) {
			$this->returnError('订单未申请退款，不需处理！');
		}

		$refund = pdo_fetch('select * from ' . tablename('sz_yi_order_refund') . ' where id=:id and (status=0 or status>1) order by id desc limit 1', array(':id' => $order['refundid']));

		if (empty($refund)) {
			pdo_update('sz_yi_order', array('refundstate' => 0), array('id' => $order['id'], 'uniacid' => $_W['uniacid']));
			$this->returnError('未找到退款申请，不需处理！');
		}

		if (empty($refund['refundno'])) {
			$refund['refundno'] = m('common')->createNO('order_refund', 'refundno', 'SR');
			pdo_update('sz_yi_order_refund', array('refundno' => $refund['refundno']), array('id' => $refund['id']));
		}

		$refundstatus = intval($para['refundstatus']);
		$refundcontent = trim($para['refundcontent']);
		$time = time();
		$data = array();
		$uniacid = $_W['uniacid'];

		if ($refundstatus == 0) {
			$this->returnError('暂不处理', referer());
		}
		else if ($refundstatus == 3) {
			$_obf_DQw4GiI8PA1bNSE_Gz87HA8fHDwYOQE_ = $para['raid'];
			$_obf_DRw2Cxk1JTkaQBQjMSEHEyIZGCYbNyI_ = trim($para['message']);

			if ($_obf_DQw4GiI8PA1bNSE_Gz87HA8fHDwYOQE_ == 0) {
				$_obf_DRwHKB8cCg49Kg0eFy0cGwYdHD5bPDI_ = pdo_fetch('select * from ' . tablename('sz_yi_refund_address') . ' where isdefault=1 and uniacid=:uniacid limit 1', array(':uniacid' => $uniacid));
			}
			else {
				$_obf_DRwHKB8cCg49Kg0eFy0cGwYdHD5bPDI_ = pdo_fetch('select * from ' . tablename('sz_yi_refund_address') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $_obf_DQw4GiI8PA1bNSE_Gz87HA8fHDwYOQE_, ':uniacid' => $uniacid));
			}

			if (empty($_obf_DRwHKB8cCg49Kg0eFy0cGwYdHD5bPDI_)) {
				$_obf_DRwHKB8cCg49Kg0eFy0cGwYdHD5bPDI_ = pdo_fetch('select * from ' . tablename('sz_yi_refund_address') . ' where uniacid=:uniacid order by id desc limit 1', array(':uniacid' => $uniacid));
			}

			unset($_obf_DRwHKB8cCg49Kg0eFy0cGwYdHD5bPDI_['uniacid']);
			unset($_obf_DRwHKB8cCg49Kg0eFy0cGwYdHD5bPDI_['openid']);
			unset($_obf_DRwHKB8cCg49Kg0eFy0cGwYdHD5bPDI_['isdefault']);
			unset($_obf_DRwHKB8cCg49Kg0eFy0cGwYdHD5bPDI_['deleted']);
			$_obf_DRwHKB8cCg49Kg0eFy0cGwYdHD5bPDI_ = iserializer($_obf_DRwHKB8cCg49Kg0eFy0cGwYdHD5bPDI_);
			$data['reply'] = '';
			$data['refundaddress'] = $_obf_DRwHKB8cCg49Kg0eFy0cGwYdHD5bPDI_;
			$data['refundaddressid'] = $_obf_DQw4GiI8PA1bNSE_Gz87HA8fHDwYOQE_;
			$data['message'] = $_obf_DRw2Cxk1JTkaQBQjMSEHEyIZGCYbNyI_;

			if (empty($refund['operatetime'])) {
				$data['operatetime'] = $time;
			}

			if ($refund['status'] != 4) {
				$data['status'] = 3;
			}

			pdo_update('sz_yi_order_refund', $data, array('id' => $order['refundid']));
			m('notice')->sendOrderMessage($order['id'], true);
		}
		else if ($refundstatus == 5) {
			$data['rexpress'] = $para['rexpress'];
			$data['rexpresscom'] = $para['rexpresscom'];
			$data['rexpresssn'] = trim($para['rexpresssn']);
			$data['status'] = 5;
			if (($refund['status'] != 5) && empty($refund['returntime'])) {
				$data['returntime'] = $time;
			}

			pdo_update('sz_yi_order_refund', $data, array('id' => $order['refundid']));
			m('notice')->sendOrderMessage($order['id'], true);
		}
		else if ($refundstatus == 10) {
			$_obf_DT8QMTIwBzImAiIRB1wRGC4MBwcqBwE_['status'] = 1;
			$_obf_DT8QMTIwBzImAiIRB1wRGC4MBwcqBwE_['refundtime'] = $time;
			pdo_update('sz_yi_order_refund', $_obf_DT8QMTIwBzImAiIRB1wRGC4MBwcqBwE_, array('id' => $order['refundid'], 'uniacid' => $uniacid));
			$_obf_DSsvGzkOQD4eCyo_MTgELxsiOz4HMCI_ = array();
			$_obf_DSsvGzkOQD4eCyo_MTgELxsiOz4HMCI_['refundstate'] = 0;
			$_obf_DSsvGzkOQD4eCyo_MTgELxsiOz4HMCI_['status'] = 1;
			$_obf_DSsvGzkOQD4eCyo_MTgELxsiOz4HMCI_['refundtime'] = $time;
			pdo_update('sz_yi_order', $_obf_DSsvGzkOQD4eCyo_MTgELxsiOz4HMCI_, array('id' => $order['id'], 'uniacid' => $uniacid));
			m('notice')->sendOrderMessage($order['id'], true);
		}
		else if ($refundstatus == 1) {
			$ordersn = $order['ordersn'];

			if (!empty($order['ordersn2'])) {
				$var = sprintf('%02d', $order['ordersn2']);
				$ordersn .= 'GJ' . $var;
			}

			$realprice = $refund['applyprice'];
			$goods = pdo_fetchall('SELECT g.id,g.credit, o.total,o.realprice FROM ' . tablename('sz_yi_order_goods') . ' o left join ' . tablename('sz_yi_goods') . ' g on o.goodsid=g.id ' . ' WHERE o.orderid=:orderid and o.uniacid=:uniacid', array(':orderid' => $order['id'], ':uniacid' => $uniacid));
			$credits = 0;

			foreach ($goods as $g) {
				$gcredit = trim($g['credit']);

				if (!empty($gcredit)) {
					if (strexists($gcredit, '%')) {
						$credits += intval((floatval(str_replace('%', '', $gcredit)) / 100) * $g['realprice']);
					}
					else {
						$credits += intval($g['credit']) * $g['total'];
					}
				}
			}

			$refundtype = 0;

			if ($order['paytype'] == 1) {
				m('member')->setCredit($order['openid'], 'credit2', $realprice, array(0, $shopset['name'] . '退款: ' . $realprice . '元 订单号: ' . $order['ordersn']));
				$result = true;
			}
			else if ($order['paytype'] == 21) {
				$realprice = round($realprice - $order['deductcredit2'], 2);
				$result = m('finance')->refund($order['openid'], $ordersn, $refund['refundno'], $order['price'] * 100, $realprice * 100);
				$refundtype = 2;
			}
			else {
				if ($realprice < 1) {
					$this->returnError('退款金额必须大于1元，才能使用微信企业付款退款!');
				}

				$realprice = round($realprice - $order['deductcredit2'], 2);
				$result = m('finance')->pay($order['openid'], 1, $realprice * 100, $refund['refundno'], $shopset['name'] . '退款: ' . $realprice . '元 订单号: ' . $order['ordersn']);
				$refundtype = 1;
			}

			if (is_error($result)) {
				$this->returnError($result['message']);
			}

			if (0 < $credits) {
				m('member')->setCredit($order['openid'], 'credit1', 0 - $credits, array(0, $shopset['name'] . '退款扣除积分: ' . $credits . ' 订单号: ' . $order['ordersn']));
			}

			if (0 < $order['deductcredit']) {
				m('member')->setCredit($order['openid'], 'credit1', $order['deductcredit'], array('0', $shopset['name'] . '购物返还抵扣积分 积分: ' . $order['deductcredit'] . ' 抵扣金额: ' . $order['deductprice'] . ' 订单号: ' . $order['ordersn']));
			}

			if (!empty($refundtype)) {
				if (0 < $order['deductcredit2']) {
					m('member')->setCredit($order['openid'], 'credit2', $order['deductcredit2'], array('0', $shopset['name'] . '购物返还抵扣余额 积分: ' . $order['deductcredit2'] . ' 订单号: ' . $order['ordersn']));
				}
			}

			$data['reply'] = '';
			$data['status'] = 1;
			$data['refundtype'] = $refundtype;
			$data['price'] = $realprice;
			$data['refundtime'] = $time;
			pdo_update('sz_yi_order_refund', $data, array('id' => $order['refundid']));
			m('notice')->sendOrderMessage($order['id'], true);
			pdo_update('sz_yi_order', array('refundstate' => 0, 'status' => -1, 'refundtime' => $time), array('id' => $order['id'], 'uniacid' => $uniacid));

			foreach ($goods as $g) {
				$salesreal = pdo_fetchcolumn('select ifnull(sum(total),0) from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where og.goodsid=:goodsid and o.status>=1 and o.uniacid=:uniacid limit 1', array(':goodsid' => $g['id'], ':uniacid' => $uniacid));
				pdo_update('sz_yi_goods', array('salesreal' => $salesreal), array('id' => $g['id']));
			}

			plog('order.op.refund', '订单退款 ID: ' . $order['id'] . ' 订单号: ' . $order['ordersn']);
		}
		else if ($refundstatus == -1) {
			pdo_update('sz_yi_order_refund', array('reply' => $refundcontent, 'status' => -1), array('id' => $order['refundid']));
			m('notice')->sendOrderMessage($order['id'], true);
			plog('order.op.refund', '订单退款拒绝 ID: ' . $order['id'] . ' 订单号: ' . $order['ordersn'] . ' 原因: ' . $refundcontent);
			pdo_update('sz_yi_order', array('refundstate' => 0), array('id' => $order['id'], 'uniacid' => $uniacid));
		}
		else {
			if ($refundstatus == 2) {
				$refundtype = 2;
				$data['reply'] = '';
				$data['status'] = 1;
				$data['refundtype'] = $refundtype;
				$data['price'] = $refund['applyprice'];
				$data['refundtime'] = $time;
				pdo_update('sz_yi_order_refund', $data, array('id' => $order['refundid']));
				m('notice')->sendOrderMessage($order['id'], true);
				pdo_update('sz_yi_order', array('refundstate' => 0, 'status' => -1, 'refundtime' => $time), array('id' => $order['id'], 'uniacid' => $uniacid));
				$goods = pdo_fetchall('SELECT g.id,g.credit, o.total,o.realprice FROM ' . tablename('sz_yi_order_goods') . ' o left join ' . tablename('sz_yi_goods') . ' g on o.goodsid=g.id ' . ' WHERE o.orderid=:orderid and o.uniacid=:uniacid', array(':orderid' => $order['id'], ':uniacid' => $uniacid));

				foreach ($goods as $g) {
					$salesreal = pdo_fetchcolumn('select ifnull(sum(total),0) from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where og.goodsid=:goodsid and o.status>=1 and o.uniacid=:uniacid limit 1', array(':goodsid' => $g['id'], ':uniacid' => $uniacid));
					pdo_update('sz_yi_goods', array('salesreal' => $salesreal), array('id' => $g['id']));
				}
			}
		}

		$this->returnSuccess(array(), '退款申请处理成功!');
	}

	public function sendRedPack()
	{
		$order = $this->order_info;

		if (empty($order['redstatus'])) {
			$this->returnError('红包已发送，不可重复发送！');
		}

		if (0 < $order['redprice']) {
			if ((1 <= $order['redprice']) && ($order['redprice'] <= 200)) {
				$result = m('finance')->sendredpack($order['openid'], $order['redprice'] * 100, $order['id'], $desc = '购买商品赠送红包', $act_name = '购买商品赠送红包', $remark = '购买商品确认收货发送红包');

				if (is_error($result)) {
					$this->returnError($result['message']);
					return NULL;
				}

				pdo_update('sz_yi_order', array('redstatus' => ''), array('id' => $order['id']));
				$this->returnSuccess('红包补发成功！');
				return NULL;
			}

			$this->returnError('红包金额错误！发送失败！红包金额在1-200元之间！');
		}
	}
}

?>
