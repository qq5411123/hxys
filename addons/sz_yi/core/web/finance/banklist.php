<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

global $_W;
global $_GPC;
$op = $operation = ($_GPC['op'] ? $_GPC['op'] : 'display');

$uniacid = $_W['uniacid'];
$template_flag = 0;
if ($op == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$sql = 'select * from ' . tablename('sz_yi_banklist') .'  ORDER BY create_time DESC LIMIT '. (($pindex - 1) * $psize) . ',' . $psize;
	$list = pdo_fetchall($sql);
	$total = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_banklist') );
	$pager = pagination($total, $pindex, $psize);
}
else if($op == 'adds'){
	if($_GPC['id'] != ''){
		$list = pdo_fetch('select * from ' . tablename('sz_yi_banklist') .' where id = '.$_GPC['id']);
	}
	load()->func('tpl');
	include $this->template('web/finance/adds');
	exit;

}
else if($op == 'add'){
	$id = intval($_GPC['id']);
	$data['bank_name'] = $_GPC['bank_name'];
	$data['bank_logo'] = $_GPC['bank_logo'];
	$data['is_show'] = $_GPC['is_show'];
	$data['create_time'] = time();
	if($id == ''){
		pdo_insert('sz_yi_banklist', $data);
		message('银行添加成功！', $this->createWebUrl('finance/banklist'), 'success');
	}else{
		pdo_update('sz_yi_banklist', $data, array('id' => $id));
		message('银行修改成功！', $this->createWebUrl('finance/banklist'), 'success');
	}
	//exit;
}
else if ($op == 'delete'){
	$id = intval($_GPC['id']);
	pdo_delete('sz_yi_banklist', array('id' => $id));
	message('删除成功！', $this->createWebUrl('finance/banklist'), 'success');
}
load()->func('tpl');
include $this->template('web/finance/banklist');

?>
