<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class AreaMobile extends Plugin
{
	public function __construct()
	{
		parent::__construct('area');
	}

	public function area()
	{
		$this->_exec_plugin('area', false);
	}

	public function area_list()
	{
		$this->_exec_plugin('area_list', false);
	}

	public function area_detail()
	{
		$this->_exec_plugin('area_detail', false);
	}
}

?>
