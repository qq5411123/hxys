<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

if (!class_exists('ChooseModel')) {
	class ChooseModel extends PluginModel
	{
		public function getUid()
		{
			global $_W;
			global $_GPC;
			$a = pdo_fetch('select * from ' . tablename('sz_yi_chooseagent'));

			if ($a['isopen'] == 1) {
				return $a['uid'];
			}

			return false;
		}
	}
}

?>
