<?php
// ��������������Ƽ����޹�˾(����֧��)
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
