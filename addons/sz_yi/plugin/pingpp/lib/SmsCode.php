<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace Pingpp;

class SmsCode extends ApiResource
{
	static public function className()
	{
		return 'sms_code';
	}

	static public function retrieve($id, $opts = NULL)
	{
		return self::_retrieve($id, $opts);
	}
}

?>
