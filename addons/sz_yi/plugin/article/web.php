<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

require_once 'model.php';
class ArticleWeb extends Plugin
{
	public function __construct()
	{
		parent::__construct('article');
	}

	public function index()
	{
		$this->_exec_plugin('index');
	}

	public function api()
	{
		$this->_exec_plugin('api');
	}
}

?>
