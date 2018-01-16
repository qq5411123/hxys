<?php
// ��������������Ƽ����޹�˾(����֧��)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class VerifyWeb extends Plugin
{
	public function __construct()
	{
		parent::__construct('verify');
	}

	public function index()
	{
		global $_W;

		if (cv('verify.keyword')) {
			header('location: ' . $this->createPluginWebUrl('verify/keyword'));
			exit();
			return NULL;
		}

		if (cv('verify.saler')) {
			header('location: ' . $this->createPluginWebUrl('verify/saler'));
			exit();
			return NULL;
		}

		if (cv('verify.store')) {
			header('location: ' . $this->createPluginWebUrl('verify/store'));
			exit();
		}
	}

	public function keyword()
	{
		$this->_exec_plugin('keyword');
	}

	public function saler()
	{
		$this->_exec_plugin('saler');
	}

	public function store()
	{
		$this->_exec_plugin('store');
	}

	public function withdraw()
	{
		$this->_exec_plugin('withdraw');
	}

	public function stock()
	{
		$this->_exec_plugin('stock');
	}

	public function category()
	{
		$this->_exec_plugin('category');
	}
}

?>
