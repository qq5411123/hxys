<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class QiniuWeb extends Plugin
{
	public function __construct()
	{
		parent::__construct('qiniu');
	}

	public function check($config)
	{
		return p('qiniu')->save('http://www.baidu.com/img/bdlogo.png', $config);
	}

	public function index()
	{
		$this->_exec_plugin('index');
	}
}

?>
