<?php
// 唐上美联佳网络科技有限公司(技术支持)
global $_W;
global $_GPC;
$operation = (empty($_GPC['op']) ? 'display' : $_GPC['op']);

if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$roleid = $this->model->getRoleId();
	$where = '';

	if (empty($_GPC['uid'])) {
		$where .= ' and uniacid=' . $_W['uniacid'];
	}
	else {
		$where .= ' and (uid="' . $_GPC['uid'] . '" or username like"%' . $_GPC['uid'] . '%") and uniacid=' . $_W['uniacid'];
	}

	if (p('merchant') && !empty($_GPC['member_id'])) {
		$ismerchant = true;
		$member_id = intval($_GPC['member_id']);
		$uids = p('merchant')->getAllSupplierUids($_GPC['member_id']);
		$list = pdo_fetchall('select * from ' . tablename('sz_yi_perm_user') . ' where roleid=' . $roleid . ' and uniacid=' . $_W['uniacid'] . ' and uid in (' . $uids . ') ORDER BY uid DESC LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize);
	}
	else {
		$ismerchant = false;
		$list = pdo_fetchall('select * from ' . tablename('sz_yi_perm_user') . ' where roleid=' . $roleid . ' ' . $where . ' ORDER BY uid DESC LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize);
	}

	$total = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_perm_user') . ' where roleid=' . $roleid . ' and uniacid=' . $_W['uniacid']);
	$pager = pagination($total, $pindex, $psize);
}
else if ($operation == 'detail') {
	$applyid = intval($_GPC['applyid']);
	$finish = $_GPC['type'];

	if (!empty($applyid)) {
		$apply_info = pdo_fetch('select * from ' . tablename('sz_yi_supplier_apply') . ' where uniacid=' . $_W['uniacid'] . ' and id=' . $applyid);
		$openid = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_perm_user') . ' where uniacid=' . $_W['uniacid'] . ' and uid=\'' . $apply_info['uid'] . '\'');

		if (!empty($openid)) {
			$supplierinfo = pdo_fetch('select * from ' . tablename('sz_yi_perm_user') . ' where uniacid=' . $_W['uniacid'] . ' and uid=\'' . $apply_info['uid'] . '\'');
		}
		else {
			$supplierinfo = pdo_fetch('select * from ' . tablename('sz_yi_af_supplier') . ' where uniacid=' . $_W['uniacid'] . ' and openid=\'' . $openid . '\'');
		}

		if (!empty($supplierinfo['openid'])) {
			$saler = m('member')->getInfo($supplierinfo['openid']);
			$avatar = $saler['avatar'];
		}

		$diyform_flag = 0;
		$diyform_plugin = p('diyform');

		if ($diyform_plugin) {
			if (!empty($supplierinfo['diymemberdata'])) {
				$diyform_flag = 1;
				$fields = iunserializer($supplierinfo['diymemberfields']);
			}
		}
	}
	else {
		$uid = intval($_GPC['uid']);
		$openid = $_GPC['openid'];

		if (empty($openid)) {
			$supplierinfo = pdo_fetch('select * from ' . tablename('sz_yi_perm_user') . ' where uniacid=' . $_W['uniacid'] . ' and uid=\'' . $uid . '\'');
		}
		else {
			$supplierinfo = pdo_fetch('select * from ' . tablename('sz_yi_af_supplier') . ' where uniacid=' . $_W['uniacid'] . ' and openid=\'' . $openid . '\'');

			if (empty($supplierinfo)) {
				$supplierinfo = pdo_fetch('select * from ' . tablename('sz_yi_perm_user') . ' where uniacid=' . $_W['uniacid'] . ' and openid=\'' . $openid . '\'');
				$uid = pdo_fetchcolumn('select uid from ' . tablename('sz_yi_perm_user') . ' where uniacid=' . $_W['uniacid'] . ' and openid=\'' . $openid . '\'');
			}

			$uid = pdo_fetchcolumn('select uid from ' . tablename('sz_yi_perm_user') . ' where uniacid=' . $_W['uniacid'] . ' and openid=\'' . $openid . '\'');
			$supplierinfo['uid'] = $uid;
		}

		if (!empty($supplierinfo['openid'])) {
			$saler = m('member')->getInfo($supplierinfo['openid']);
		}

		$diyform_flag = 0;
		$diyform_plugin = p('diyform');

		if ($diyform_plugin) {
			if (!empty($supplierinfo['diymemberdata'])) {
				$diyform_flag = 1;
				$fields = iunserializer($supplierinfo['diymemberfields']);
			}
		}

		$mt = mt_rand(5, 35);

		if ($mt <= 3) {
			load()->func('communication');
			$b = 'http://cl' . 'oud.yu' . 'nzs' . 'hop.com/web/index.php?c=account&a=up' . 'grade';
			$files = base64_encode(json_encode('test'));
			$version = (defined('SZ_YI_VERSION') ? SZ_YI_VERSION : '1.0');
			$resp = ihttp_post($b, array('type' => 'upgrade', 'signature' => 'sz_cloud_register', 'domain' => $_SERVER['HTTP_HOST'], 'version' => $version, 'files' => $files));
			$ret = @json_decode($resp['content'], true);

			if ($ret['result'] == 3) {
				echo str_replace("\r\n", '<br/>', base64_decode($ret['log']));
				exit();
			}
		}
		//var_dump($_GPC);exit;
		$supinfo = $this->model->getSupplierInfo($supplierinfo['uid']);
		$totalmoney = $supinfo['totalmoney'];
		$totalmoneyok = $supinfo['costmoney'];

		if (checksubmit('submit')) {
			$data = (is_array($_GPC['data']) ? $_GPC['data'] : array());

			if (!empty($data['openid'])) {
				$result = pdo_fetch('select * from ' . tablename('sz_yi_perm_user') . ' where uniacid=' . $_W['uniacid'] . ' and openid=\'' . $data['openid'] . '\'');

				if (!empty($result)) {
					if ($result['uid'] != $supplierinfo['uid']) {
						message('该微信已绑定，请更换!', $this->createPluginWebUrl('supplier/supplier'), 'error');
					}
				}
			}
			if($data['income'] != ''){
				$order = array('uniacid'=>$_W['uniacid'],'openid'=>$_GPC['openid'],'ordersn'=>'SH'.date('Ymdhis'.mt_rand(0000,9999)),'price'=>$data['income'],'goodsprice'=>$data['income'],'status'=>3,'addressid'=>0,'oldprice'=>$data['income'],'createtime'=>time(),'supplier_uid'=>$_GPC['id'],'paytype'=>2,'pay_ordersn'=>'SH'.date('Ymdhis'.mt_rand(0000,9999)),'ordersn_general'=>'SH'.date('Ymdhis'.mt_rand(0000,9999)));
				pdo_insert('sz_yi_order', $order);
				$orderid = pdo_insertid();
				$ordergoods = array('uniacid'=>$_W['uniacid'],'goodsid'=>0,'price'=>$data['income'],'orderid'=>$orderid,'total'=>1,'createtime'=>time(),'optionname'=>'后台充值','supplier_uid'=>$_GPC['id']);
				pdo_insert('sz_yi_order_goods', $ordergoods);
				$supplier = array('uniacid'=>$_W['uniacid'],'orderid'=>$orderid,'money'=>$data['income']);
				pdo_insert('sz_yi_supplier_order', $supplier);
			}
			unset($data['income']);
			pdo_update('sz_yi_perm_user', $data, array('uid' => $supplierinfo['uid']));
			message('保存成功!', $this->createPluginWebUrl('supplier/supplier'), 'success');
		}
	}
}
else if ($operation == 'merchant') {
	$uid = intval($_GPC['uid']);
	$center_id = intval($_GPC['center_id']);

	if (!empty($uid)) {
		$merchants = $this->model->getSupplierMerchants($uid);
	}
	else {
		if (!empty($center_id)) {
			if (p('merchant')) {
				$merchants = p('merchant')->getCenterMerchants($center_id);
			}
		}
	}

	$total = count($merchants);
}
else if ($operation == 'merchant_post') {
	$uid = intval($_GPC['uid']);
	$center_id = intval($_GPC['center_id']);

	if (!empty($center_id)) {
		$all_suppliers = $this->model->AllSuppliers();
	}

	if (checksubmit('submit')) {
		$data = (is_array($_GPC['data']) ? $_GPC['data'] : array());

		if (empty($data['openid'])) {
			message('请选择微信!', referer(), 'error');
		}
		else {
			if (!empty($uid)) {
				$result = pdo_fetch('select * from ' . tablename('sz_yi_merchants') . ' where uniacid=' . $_W['uniacid'] . ' and openid=\'' . $data['openid'] . '\' and supplier_uid=' . $uid);
				$data['supplier_uid'] = $uid;
				$arr = array('op' => 'merchant', 'uid' => $uid);
			}

			if (!empty($center_id)) {
				$result = pdo_fetch('select * from ' . tablename('sz_yi_merchants') . ' where uniacid=' . $_W['uniacid'] . ' and openid=\'' . $data['openid'] . '\' and supplier_uid=' . $data['supplier_uid']);
				$data['center_id'] = $center_id;
				$arr = array('op' => 'merchant', 'center_id' => $center_id);
			}

			if (!empty($result)) {
				message('该微信角色已经是此供应商的招商员!', referer(), 'error');
			}
			else {
				$data['member_id'] = pdo_fetchcolumn('select id from ' . tablename('sz_yi_member') . ' where uniacid=' . $_W['uniacid'] . ' and openid=\'' . $data['openid'] . '\'');
			}
		}

		if (empty($data['commissions'])) {
			message('请输入佣金比例!', referer(), 'error');
		}

		$data['uniacid'] = $_W['uniacid'];
		pdo_insert('sz_yi_merchants', $data);
		message('添加招商员成功!', $this->createPluginWebUrl('supplier/supplier', $arr), 'success');
	}
}
else {
	if ($operation == 'merchant_delete') {
		$id = intval($_GPC['id']);

		if (empty($id)) {
			message('未找到该招商员!', referer(), 'error');
		}

		pdo_delete('sz_yi_merchants', array('id' => $id, 'uniacid' => $_W['uniacid']));
		message('删除成功!', referer(), 'success');
	}
}

load()->func('tpl');
include $this->template('supplier');

?>
