<?php
// 唐上美联佳网络科技有限公司(技术支持)
global $_W;
global $_GPC;
$operation = (empty($_GPC['op']) ? 'display' : $_GPC['op']);

if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$daytime = strtotime(date('Y-m-d 00:00:00'));
	$stattime = $daytime - 86400;
	$endtime = $daytime - 1;
	$sql = 'select sum(o.price) from ' . tablename('sz_yi_order') . ' o left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid=' . $_W['uniacid'] . ' and  o.finishtime >=' . $stattime . ' and o.finishtime < ' . $endtime . '  ORDER BY o.finishtime DESC,o.status DESC';
	$ordermoney = pdo_fetchcolumn($sql);
	$ordermoney = floatval($ordermoney);
	$profit_sql = 'select o.id, o.price, g.marketprice, g.costprice, og.total from ' . tablename('sz_yi_order') . " o \n    left join " . tablename('sz_yi_order_goods') . " og on (o.id = og.orderid) \n    left join " . tablename('sz_yi_goods') . " g on (og.goodsid = g.id) \n    left join " . tablename('sz_yi_order_refund') . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 \n    where 1 and o.status>=3 and o.uniacid=" . $_W['uniacid'] . ' and  o.finishtime >=' . $stattime . ' and o.finishtime < ' . $endtime . '  ORDER BY o.finishtime DESC,o.status DESC';
	$dayorder = pdo_fetchall($profit_sql);

	foreach ($dayorder as $key => $value) {
		$profit += $value['price'] - ($value['costprice'] * $value['total']);
	}

	$params = array();
	$condition = '';

	if (!empty($_GPC['mid'])) {
		$condition .= ' and id=:mid';
		$condition1 .= ' and r.mid=:mid';
		$params[':mid'] = intval($_GPC['mid']);
	}

	if (!empty($_GPC['realname'])) {
		$_GPC['realname'] = trim($_GPC['realname']);
		$condition .= ' and ( realname like :realname or nickname like :realname or mobile like :realname)';
		$condition1 .= ' and ( m.realname like :realname or m.nickname like :realname or m.mobile like :realname)';
		$params[':realname'] = '%' . $_GPC['realname'] . '%';
	}

	$total = pdo_fetchall('select * from' . tablename('sz_yi_return') . " r \n        left join " . tablename('sz_yi_member') . ' m on (r.mid = m.id ) where r.uniacid =' . $_W['uniacid'] . '    ' . $condition1 . ' group by mid', $params);
	$total = count($total);
	$list_group = pdo_fetchall(' select * from ' . tablename('sz_yi_return') . " r \n        left join " . tablename('sz_yi_member') . ' m on (r.mid = m.id ) where r.uniacid= ' . $_W['uniacid'] . '  ' . $condition1 . '  group by mid  limit ' . (($pindex - 1) * $psize) . ',' . $psize, $params);
	$pager = pagination($total, $pindex, $psize);
	$totals = 0;

	foreach ($list_group as $row1) {
		$infomation = pdo_fetch('select * from ' . tablename('sz_yi_member') . '  where uniacid=' . $_W['uniacid'] . ' and id=' . $row1['mid']);
		$list_group1[$row1['mid']] = pdo_fetchall(' select * from ' . tablename('sz_yi_return') . ' where uniacid=' . $_W['uniacid'] . ' and  mid = ' . $row1['mid']);

		foreach ($list_group1[$row1['mid']] as $row2) {
			if ($row2['delete'] < '1') {
				$asd[$row1['mid']]['money1'] += $row2['money'];
			}
			else {
				$asd[$row1['mid']]['money1'] += $row2['return_money'];
			}

			$asd[$row1['mid']]['return_money1'] += $row2['return_money'];
			$asd[$row1['mid']]['status'] = $row2['status'];
			$asd[$row1['mid']]['delete'] = $row2['delete'];
		}

		$asd[$row1['mid']]['realname'] = $infomation['realname'];
		$asd[$row1['mid']]['avatar'] = $infomation['avatar'];
		$asd[$row1['mid']]['mid'] = $infomation['id'];
		$asd[$row1['mid']]['unreturnmoney'] = $asd[$row1['mid']]['money1'] - $asd[$row1['mid']]['return_money1'];
		$asd[$row1['mid']]['data'] = json_encode($asd[$row1['mid']]);
		// $asd[$row1['mid']]['data2'] = json_encode( array( 'money1' => '25200' ,'return_money1' => '13205.55') );

		$m_totals = pdo_fetchall('select * from' . tablename('sz_yi_return') . " r \n        left join " . tablename('sz_yi_member') . ' m on (r.mid = m.id ) where r.uniacid =' . $_W['uniacid'] . ' and r.mid = ' . $row1['mid'], $params);
		$totals += count($m_totals);
	}

	unset($row);
}
else if ($operation == 'detail') {
	$str = $_GPC['data'];
	$data = stripslashes(html_entity_decode($_GPC['data'])); //$info是传递过来的json字符串
	$list_group[0] = json_decode($data,TRUE);


/*	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$params = ' and r.delete = \'0\' ';
	$total = pdo_fetchall('select * from' . tablename('sz_yi_return') . " r \n        left join " . tablename('sz_yi_member') . ' m on (r.mid = m.id ) where r.uniacid =' . $_W['uniacid'] . '  and r.mid = ' . $_GPC['mid'] . $params);
	$total = count($total);
	$list_group = pdo_fetchall(' select r.id, r.uniacid, r.mid, r.money, r.return_money, r.create_time, r.status, m.realname, m.nickname, m.avatar from ' . tablename('sz_yi_return') . " r \n        left join " . tablename('sz_yi_member') . ' m on (r.mid = m.id ) where r.uniacid= ' . $_W['uniacid'] . '  and r.mid = ' . $_GPC['mid'] . $params . 'limit ' . (($pindex - 1) * $psize) . ',' . $psize);

	foreach ($list_group as $key => $value) {
		$list_group[$key]['unreturnmoney'] = $value['money'] - $value['return_money'];
		$list_group[$key]['create_time'] = date('Y-m-d H:i', $value['create_time']);
	}

	$pager = pagination($total, $pindex, $psize);
	unset($row);*/
}
else if ($operation == 'delete') {
		$data = array('delete' => '1');
		// pdo_update('sz_yi_return', $data, array('id' => $_GPC['id']));
		//--GC--
		pdo_delete('sz_yi_return',array('id' => $_GPC['id']));
		//--GC--
		message('删除成功！', referer(), 'success');
}
//---GC---
else{

	if(p('return')){
		if($_POST['sign']=='+')
			p('return')->addUnreturn();
		else
			p('return')->reduceUnreturn();
	}

exit;
}
//---GC---

include $this->template('return_tj');

?>
