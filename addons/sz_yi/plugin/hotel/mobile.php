<?php
// 唐上美联佳网络科技有限公司(技术支持)
function sortByCreateTime($a, $b)
{
	if ($a['createtime'] == $b['createtime']) {
		return 0;
	}

	return $a['createtime'] < $b['createtime'] ? 1 : -1;
}

if (!defined('IN_IA')) {
	exit('Access Denied');
}

class BonusMobile extends Plugin
{
	protected $set;

	public function __construct()
	{
		parent::__construct('bonus');
		$this->set = $this->getSet();
		global $_GPC;
	}

	public function index()
	{
		$this->_exec_plugin('index', false);
	}
}

?>
