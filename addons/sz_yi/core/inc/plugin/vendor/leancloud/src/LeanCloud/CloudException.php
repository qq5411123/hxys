<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace LeanCloud;

class CloudException
{
	public function __construct($message, $code = 0)
	{
		parent::__construct($message, $code);
	}

	public function __toString()
	{
		return 'LeanCloud\\CloudException' . ': [' . $this->code . ']: ' . $this->message . "\n";
	}
}


?>
