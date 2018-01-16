<?php
// 唐上美联佳网络科技有限公司(技术支持)
global $_W;
global $_GPC;
load()->func('tpl');

$protocol = pdo_fetch('select * from ' . tablename('protocol') . ' where id= 2 ');

include $this->template('protocol');

?>
