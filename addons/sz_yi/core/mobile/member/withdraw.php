<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

global $_W;
global $_GPC;
$operation = (!empty($_GPC['op']) ? $_GPC['op'] : 'display');
$openid = m('user')->getOpenid();
$uniacid = $_W['uniacid'];
$set = m('common')->getSysset();
$shopset = m('common')->getSysset('shop');
if (($operation == 'display') && $_W['isajax']) {
	$credit = m('member')->getCredit($openid, 'credit2');
	$member = m('member')->getMember($openid);
	$returnurl = urlencode($this->createMobileUrl('member/withdraw'));
	$infourl = $this->createMobileUrl('member/info', array('returnurl' => $returnurl));
	show_json(1, array('credit' => $credit, 'infourl' => $infourl, 'noinfo' => empty($member['realname'])));
}
else {
	if (($operation == 'submit') && $_W['ispost']) {
		$money = floatval($_GPC['money']);
		$credit = m('member')->getCredit($openid, 'credit2');



		if ($money < 0) {
			show_json(0, '非法提现金额!');
		}

		if (empty($money)) {
			show_json(0, '申请金额为空!');
		}

		if ($credit < $money) {
			show_json(0, '提现金额过大!');
		}

		m('member')->setCredit($openid, 'credit2', 0 - $money, array(0, '余额提现：-' . $money . ' 元'));
		$logno = m('common')->createNO('member_log', 'logno', 'RW');
		$poundage = ($money * $set['trade']['poundage']) / 100;
		$actual = $money - $poundage;
		switch ($_GPC['type']) {
			case 1:
				$logdata = array('uniacid' => $uniacid, 'logno' => $logno, 'openid' => $openid, 'title' => '余额提现', 'type' => 1, 'createtime' => time(), 'status' => 0, 'money' => $actual, 'poundage' => $poundage, 'withdrawal_money' => $money,'types'=>$_GPC['type'],'alipay_account'=>$_GPC['alipay_account'],'alipay_name'=>$_GPC['alipay_name']);
				break;
			case 2:
				$logdata = array('uniacid' => $uniacid, 'logno' => $logno, 'openid' => $openid, 'title' => '余额提现', 'type' => 1, 'createtime' => time(), 'status' => 0, 'money' => $actual, 'poundage' => $poundage, 'withdrawal_money' => $money,'types'=>$_GPC['type'],'wechat_account'=>$_GPC['wechat_account'],'wechat_name'=>$_GPC['wechat_name']);
			 	break;
			case 3:
				$logdata = array('uniacid' => $uniacid, 'logno' => $logno, 'openid' => $openid, 'title' => '余额提现', 'type' => 1, 'createtime' => time(), 'status' => 0, 'money' => $actual, 'poundage' => $poundage, 'withdrawal_money' => $money,'types'=>$_GPC['type'],'bank_number' => $_GPC['bank_number'],'bank_id' => $_GPC['bank_id'] ,'bank_name'=>$_GPC['bank_name']);
				break;
			default:
				if ($_GPC['type'] == 0) {
					show_json(0, '请选择提现方式!');
				}
				break;
		}

		pdo_insert('sz_yi_member_log', $logdata);
		$logid = pdo_insertid();
		if (($set['trade']['withdrawnocheck'] == 1) && (empty($set['trade']['withdrawautomoney']) || ($money <= $set['trade']['withdrawautomoney']))) {
			$log = pdo_fetch('select * from ' . tablename('sz_yi_member_log') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $logid, ':uniacid' => $uniacid));

			if (empty($log)) {
				show_json(0, '未找到记录!');
			}

			$member = m('member')->getMember($log['openid']);

			if ($set['pay']['weixin'] != '1') {
				show_json(0, '商城未开启微信支付功能,请联系管理员手动打款!');
			}

			$result = m('finance')->pay($log['openid'], 1, $log['money'] * 100, $log['logno'], $set['name'] . '余额提现');

			if (is_error($result)) {
				show_json(0, '微信钱包提现失败: ' . $result['message'] . '请联系管理员手动打款！');
			}

			pdo_update('sz_yi_member_log', array('status' => 1), array('id' => $logid, 'uniacid' => $uniacid));
			m('notice')->sendMemberLogMessage($log['id']);
			plog('finance.withdraw.withdraw', '余额提现 ID: ' . $log['id'] . ' 方式: 微信 金额: ' . $log['money'] . ' <br/>会员信息:  ID: ' . $member['id'] . ' / ' . $member['openid'] . '/' . $member['nickname'] . '/' . $member['realname'] . '/' . $member['mobile']);
			show_json(1);
		}
		else {
			m('notice')->sendMemberLogMessage($logid);
			show_json(2);
		}
	}
}
$bank_list = pdo_fetchall('SELECT * FROM' . tablename('sz_yi_banklist') . ' where is_show = 1 order by create_time desc');
include $this->template('member/withdraw');

?>
