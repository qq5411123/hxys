<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class PosterMobile extends Plugin
{
	public function __construct()
	{
		parent::__construct('poster');
	}

	public function build()
	{
		$this->_exec_plugin('build', false);
	}
}

?>
