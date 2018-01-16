<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('PHPEXCEL_ROOT')) {
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../../');
	require PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php';
}

class PHPExcel_Reader_DefaultReadFilter implements PHPExcel_Reader_IReadFilter
{
	public function readCell($column, $row, $worksheetName = '')
	{
		return true;
	}
}

?>
