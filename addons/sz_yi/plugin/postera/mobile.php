<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class PosteraMobile extends Plugin
{
	public function __construct()
	{
		parent::__construct('postera');
	}

	public function build()
	{
		$this->_exec_plugin('build', false);
	}
}

?>
