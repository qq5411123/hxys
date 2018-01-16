<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace Pingpp;

class Token extends ApiResource
{
	static public function retrieve($id, $opts = NULL)
	{
		return self::_retrieve($id, $opts);
	}

	static public function create($params = NULL, $opts = NULL)
	{
		return self::_create($params, $opts);
	}
}

?>
