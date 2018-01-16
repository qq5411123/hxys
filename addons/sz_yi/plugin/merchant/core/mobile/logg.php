<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

global $_W;
global $_GPC;
$operation = (!empty($_GPC['op']) ? $_GPC['op'] : 'display');
$openid = m('user')->getOpenid();
$member = m('member')->getMember($openid);
$uniacid = $_W['uniacid'];

if ($_W['isajax']) {
	$iscenter = intval($_GPC['iscenter']);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition = ' and `member_id`=:id and uniacid=:uniacid';

	if (!empty($iscenter)) {
		$condition .= ' and iscenter=1';
	}

	$params = array(':id' => $member['id'], ':uniacid' => $uniacid);
	$status = trim($_GPC['status']);

	if ($status != '') {
		$condition .= ' and status=' . intval($status);
	}

	$list = pdo_fetchall('select * from ' . tablename('sz_yi_merchant_apply') . ' where 1 ' . $condition . ' order by id desc LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize, $params);
	$total = count($list);

	foreach ($list as &$row) {
		$row['apply_money'] = number_format($row['money'], 2);

		if ($row['status'] == 0) {
			$row['statusstr'] = '待审核';
			$row['dealtime'] = date('Y-m-d H:i', $row['apply_time']);
		}
		else {
			if ($row['status'] == 1) {
				$row['statusstr'] = '已打款';
				$row['dealtime'] = date('Y-m-d H:i', $row['finish_time']);
			}
		}
	}

	unset($row);
	show_json(1, array('total' => $total, 'list' => $list, 'pagesize' => $psize));
}

if ($operation == 'display') {
	include $this->template('logg');
}

?>
