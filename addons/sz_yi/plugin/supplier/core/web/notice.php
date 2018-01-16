<?php
// 唐上美联佳网络科技有限公司(技术支持)
global $_W;
global $_GPC;
ca('commission.notice');
$set = $this->getSet();

if (checksubmit('submit')) {
	$set['tm'] = is_array($_GPC['tm']) ? $_GPC['tm'] : array();
	$this->updateSet($set);
	plog('commission.notice', '修改通知设置');
	message('设置保存成功!', referer(), 'success');
}

load()->func('tpl');
include $this->template('notice');

?>
