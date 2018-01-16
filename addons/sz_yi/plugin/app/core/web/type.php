<?php
// 唐上美联佳网络科技有限公司(技术支持)
global $_W;
global $_GPC;
$setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
$set = unserialize($setdata['sets']);
$setting = uni_setting($_W['uniacid'], array('payment'));
$pay = $setting['payment'];

if (!is_array($pay)) {
	$pay = array();
}

if (checksubmit()) {
	$set['pay']['app_weixin'] = $_GPC['pay']['app_weixin'];
	$set['pay']['app_alipay'] = $_GPC['pay']['app_alipay'];
	$data = array('uniacid' => $_W['uniacid'], 'sets' => iserializer($set));

	if (empty($setdata)) {
		pdo_insert('sz_yi_sysset', $data);
	}
	else {
		pdo_update('sz_yi_sysset', $data, array('uniacid' => $_W['uniacid']));
	}

	$setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
	m('cache')->set('sysset', $setdata);
	$ping = array_elements(array('partner', 'secret'), $_GPC['ping']);
	$ping['switch'] = 1;
	$ping['partner'] = trim($ping['partner']);
	$ping['secret'] = trim($ping['secret']);
	$pay['ping'] = $ping;
	$dat = iserializer($pay);
	pdo_update('uni_settings', array('payment' => $dat), array('uniacid' => $_W['uniacid']));
	cache_delete('unisetting:' . $_W['uniacid']);
	message('设置保存成功!', $this->createWebUrl('plugin/app', array('method' => 'type', 'op' => $op)), 'success');
}

load()->func('tpl');
include $this->template('type');
exit();

?>
