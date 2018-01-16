<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace Pingpp\Error;

class InvalidRequest extends Base
{
	public function __construct($message, $param, $httpStatus = NULL, $httpBody = NULL, $jsonBody = NULL)
	{
		parent::__construct($message, $httpStatus, $httpBody, $jsonBody);
		$this->param = $param;
	}
}

?>
