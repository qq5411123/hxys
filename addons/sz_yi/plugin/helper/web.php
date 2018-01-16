<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class HelperWeb extends Plugin
{
	public function __construct()
	{
		parent::__construct('helper');
	}

	public function index()
	{
		$this->_exec_plugin('index');
	}

	public function upgrade()
	{
		$this->_exec_plugin('upgrade');
	}
}

?>
