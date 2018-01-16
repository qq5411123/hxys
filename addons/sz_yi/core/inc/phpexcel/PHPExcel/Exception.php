<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_Exception
{
	static public function errorHandlerCallback($code, $string, $file, $line, $context)
	{
		$e = new self($string, $code);
		$e->line = $line;
		$e->file = $file;
		throw $e;
	}
}


?>
