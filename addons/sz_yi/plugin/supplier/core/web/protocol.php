<?php
// 唐上美联佳网络科技有限公司(技术支持)
global $_W;
global $_GPC;
load()->func('tpl');
// ca('commission.set');
// $set = $this->getSet();

$data = pdo_fetch('select * from ' . tablename('protocol') . ' where id= 1 ');
//var_dump($data);die();

if (checksubmit('btn')) {
	pdo_update('protocol', array('title'=> $_GPC['title'], 'content'=> $_GPC['content']), array('id' => 1 ));
	//message('保存成功!', $this->createPluginWebUrl('plugin/protocol'), 'success');
	message('保存成功!', referer(), 'success');
}



include $this->template('protocol');


?>
