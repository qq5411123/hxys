<?php
// ��������������Ƽ����޹�˾(����֧��)
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
