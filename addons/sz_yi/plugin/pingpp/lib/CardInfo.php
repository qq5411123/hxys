<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace Pingpp;

class CardInfo extends ApiResource
{
	static public function className()
	{
		return 'card_info';
	}

	static public function classUrl()
	{
		$base = static::className();
		return '/v1/' . $base;
	}

	static public function query($params = NULL, $options = NULL)
	{
		return self::_create($params, $options);
	}
}

?>
