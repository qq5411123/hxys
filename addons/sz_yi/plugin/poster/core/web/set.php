<?php
// 唐上美联佳网络科技有限公司(技术支持)
global $_W;
global $_GPC;
$set = $this->getSet();

if (checksubmit('submit')) {
	$set['user'] = is_array($_GPC['user']) ? $_GPC['user'] : array();
	$this->updateSet($set);
	message('设置保存成功!', referer(), 'success');
}

include $this->template('set');

?>
