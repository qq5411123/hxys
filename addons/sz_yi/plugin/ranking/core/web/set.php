<?php
// 唐上美联佳网络科技有限公司(技术支持)
global $_W;
global $_GPC;
ca('ranking.set');
$set = $this->getSet();

if (checksubmit('submit')) {
	$data = array_merge($set, $_GPC['setdata']);
	$this->updateSet($data);
	m('cache')->set('template_' . $this->pluginname, $data['style']);
	plog('ranking.set', '修改基本设置');
	message('设置保存成功!', referer(), 'success');
}

load()->func('tpl');
include $this->template('set');

?>
