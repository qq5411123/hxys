<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

if (!class_exists('RankingModel')) {
	class RankingModel extends PluginModel
	{
		public function getSet()
		{
			$set = parent::getSet();
			return $set;
		}

		public function getMember($id)
		{
			global $_W;
			global $_GPC;
			$list = pdo_fetch('select * from ' . tablename('sz_yi_member') . ' where uniacid = \'' . $_W['uniacid'] . '\' and id = \'' . $id . '\'');
			return $list;
		}
	}
}

?>
