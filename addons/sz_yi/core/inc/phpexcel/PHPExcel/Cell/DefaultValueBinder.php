<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('PHPEXCEL_ROOT')) {
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../../');
	require PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php';
}

class PHPExcel_Cell_DefaultValueBinder implements PHPExcel_Cell_IValueBinder
{
	public function bindValue(PHPExcel_Cell $cell, $value = NULL)
	{
		if (is_string($value)) {
			$value = PHPExcel_Shared_String::SanitizeUTF8($value);
		}

		$cell->setValueExplicit($value, self::dataTypeForValue($value));
		return true;
	}

	static public function dataTypeForValue($pValue = NULL)
	{
		if (is_null($pValue)) {
			return PHPExcel_Cell_DataType::TYPE_NULL;
		}

		if ($pValue === '') {
			return PHPExcel_Cell_DataType::TYPE_STRING;
		}

		if ($pValue instanceof PHPExcel_RichText) {
			return PHPExcel_Cell_DataType::TYPE_INLINE;
		}

		if (($pValue[0] === '=') && (1 < strlen($pValue))) {
			return PHPExcel_Cell_DataType::TYPE_FORMULA;
		}

		if (is_bool($pValue)) {
			return PHPExcel_Cell_DataType::TYPE_BOOL;
		}

		if (is_float($pValue) || is_int($pValue)) {
			return PHPExcel_Cell_DataType::TYPE_NUMERIC;
		}

		if (preg_match('/^\\-?([0-9]+\\.?[0-9]*|[0-9]*\\.?[0-9]+)$/', $pValue)) {
			return PHPExcel_Cell_DataType::TYPE_NUMERIC;
		}

		if (is_string($pValue) && array_key_exists($pValue, PHPExcel_Cell_DataType::getErrorCodes())) {
			return PHPExcel_Cell_DataType::TYPE_ERROR;
		}

		return PHPExcel_Cell_DataType::TYPE_STRING;
	}
}

?>
