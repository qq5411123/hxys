<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	print('Access Denied');
}

class ExhelperMobile extends Plugin
{
	public function __construct()
	{
		parent::__construct('exhelper');
		$this->set = $this->getSet();
	}
}

?>
