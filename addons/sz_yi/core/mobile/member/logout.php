<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

global $_W;
global $_GPC;
@session_start();
$cookieid = '__cookie_sz_yi_userid_' . $_W['uniacid'];
setcookie($cookieid, '', time() - 1);
$url = $this->createMobileUrl('shop');
redirect($url);

?>
