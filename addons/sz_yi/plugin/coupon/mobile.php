<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class CouponMobile extends Plugin
{
	public function __construct()
	{
		parent::__construct('coupon');
	}

	public function index()
	{
		$this->_exec_plugin('index', false);
	}

	public function detail()
	{
		$this->_exec_plugin('detail', false);
	}

	public function my()
	{
		$this->_exec_plugin('my', false);
	}

	public function mydetail()
	{
		$this->_exec_plugin('mydetail', false);
	}

	public function util()
	{
		$this->_exec_plugin('util', false);
	}
}

?>
