<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

global $_W;
global $_GPC;
$openid = m('user')->getOpenid();

if ($_W['isajax']) {
	if ($_W['ispost']) {
		$mobile = (!empty($_GPC['mobile']) ? $_GPC['mobile'] : show_json(0, '手机号不能为空！'));
		$password = (!empty($_GPC['password']) ? $_GPC['password'] : show_json(0, '密码不能为空！'));
		$code = (!empty($_GPC['code']) ? $_GPC['code'] : show_json(0, '验证码不能为空！'));

		if (($_SESSION['codetime'] + (60 * 5)) < time()) {
			show_json(0, '验证码已过期,请重新获取');
		}

		if ($_SESSION['code'] != $code) {
			show_json(0, '验证码错误,请重新获取');
		}

		if ($_SESSION['code_mobile'] != $mobile) {
			show_json(0, '注册手机号与验证码不匹配！');
		}

		$member = pdo_fetch('select * from ' . tablename('sz_yi_member') . ' where mobile=:mobile and pwd!="" and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':mobile' => $mobile));

		if (!empty($member)) {
			show_json(0, '该手机号已被注册！');
		}

		pdo_update('sz_yi_member', array('mobile' => $mobile, 'pwd' => md5($password), 'bindapp' => 1), array('openid' => $openid));
		show_json(1, $this->createMobileUrl('shop/download'));
	}
}

include $this->template('member/bindapp');

?>
