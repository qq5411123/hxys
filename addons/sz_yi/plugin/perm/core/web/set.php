<?php
// 唐上美联佳网络科技有限公司(技术支持)
global $_W;
global $_GPC;
ca('perm.set');
$type = m('cache')->getString('permset', 'global');
$set = array('type' => intval($type));

if (checksubmit('submit')) {
	m('cache')->set('permset', intval($_GPC['data']['type']), 'global');
	message('设置成功!', referer(), 'success');
}

load()->func('tpl');
include $this->template('index');

?>
