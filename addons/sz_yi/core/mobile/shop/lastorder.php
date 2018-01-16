<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

global $_W;
global $_GPC;
$operation = (!empty($_GPC['op']) ? $_GPC['op'] : 'display');
$trade = m('common')->getSysset('trade');

if ($_W['isajax']) {
	if ($operation == 'display') {
		if ($trade['showlastorder'] == 1) {
			$ret = pdo_fetchall('SELECT m.nickname,m.avatar,o.createtime FROM ' . tablename('sz_yi_order') . ' o left join ' . tablename('sz_yi_member') . ' m on o.openid = m.openid WHERE o.createtime >= :time AND m.uniacid = :uniacid ORDER BY o.createtime DESC LIMIT 1', array(':time' => time() - 86400, ':uniacid' => $_W['uniacid']));
			$count = count($ret);
			$num = rand(0, $count - 1);
			$time = time() - $ret[$num]['createtime'];
			$showtime = '1分钟前';

			if ($time < 0) {
				$showtime = '1分钟前';
			}
			else if ($time < 3600) {
				$showtime = ceil($time / 60) . '分钟前';
			}
			else {
				if ($time < 86400) {
					$showtime = ceil($time / 3600) . '小时前';
				}
			}

			if (empty($ret)) {
				show_json(0);
				return 1;
			}

			show_json(1, array('nickname' => $ret[$num]['nickname'], 'avatar' => $ret[$num]['avatar'], 'time' => $showtime));
			return 1;
		}

		show_json(0);
	}
}

?>
