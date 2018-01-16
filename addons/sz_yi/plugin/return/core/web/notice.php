<?php
// 唐上美联佳网络科技有限公司(技术支持)
global $_W;
global $_GPC;
$set = $this->getSet();

if (checksubmit('submit')) {
	$data = (is_array($_GPC['setdata']) ? array_merge($set, $_GPC['setdata']) : array());
	$this->updateSet($data);
	m('cache')->set('template_' . $this->pluginname, $data['style']);
	plog('return.notice', '全返插件通知设置');
	message('设置保存成功!', referer(), 'success');
}

load()->func('tpl');
include $this->template('notice');

?>
