<?php
// ��������������Ƽ����޹�˾(����֧��)
class PHPExcel_Calculation_Exception extends PHPExcel_Exception
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
