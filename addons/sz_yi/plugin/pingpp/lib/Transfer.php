<?php
// ��������������Ƽ����޹�˾(����֧��)
namespace Pingpp;

class Transfer extends ApiResource
{
	static public function retrieve($id, $options = NULL)
	{
		return self::_retrieve($id, $options);
	}

	static public function all($params = NULL, $options = NULL)
	{
		return self::_all($params, $options);
	}

	static public function create($params = NULL, $options = NULL)
	{
		return self::_create($params, $options);
	}
}

?>
