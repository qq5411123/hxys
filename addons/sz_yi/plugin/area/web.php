<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

require_once 'model.php';
class AreaWeb extends Plugin
{
	public function __construct()
	{
		parent::__construct('area');
	}

	public function index()
	{
		$this->_exec_plugin('index');
	}

	public function statistics()
	{
		$this->_exec_plugin('statistics');
	}

	public function upgrade()
	{
		$this->_exec_plugin('upgrade');
	}
}

?>
