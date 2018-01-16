<?php
// 唐上美联佳网络科技有限公司(技术支持)
global $_W;
global $_GPC;
session_start();
$_SESSION['helper'] = true;
message('', $this->createPluginWebUrl('article', array('is_helper' => 1)), 'success');

?>
