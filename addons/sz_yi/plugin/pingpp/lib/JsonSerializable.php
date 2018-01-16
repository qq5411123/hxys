<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace Pingpp;

if (interface_exists('\\JsonSerializable', false)) {
	interface JsonSerializable extends \JsonSerializable
	{	}
}

interface JsonSerializable
{
	public function jsonSerialize();
}

?>
