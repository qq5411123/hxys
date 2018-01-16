<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_Calculation_Logical
{
	static public function TRUE()
	{
		return true;
	}

	static public function FALSE()
	{
		return false;
	}

	static public function LOGICAL_AND()
	{
		$returnValue = true;
		$aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
		$argCount = -1;

		foreach ($aArgs as $argCount => $arg) {
			if (is_bool($arg)) {
				$returnValue = $returnValue && $arg;
			}
			else {
				if (is_numeric($arg) && !is_string($arg)) {
					$returnValue = $returnValue && ($arg != 0);
				}
				else {
					if (is_string($arg)) {
						$arg = strtoupper($arg);
						if (($arg == 'TRUE') || ($arg == PHPExcel_Calculation::getTRUE())) {
							$arg = true;
						}
						else {
							if (($arg == 'FALSE') || ($arg == PHPExcel_Calculation::getFALSE())) {
								$arg = false;
							}
							else {
								return PHPExcel_Calculation_Functions::VALUE();
							}
						}

						$returnValue = $returnValue && ($arg != 0);
					}
				}
			}
		}

		if ($argCount < 0) {
			return PHPExcel_Calculation_Functions::VALUE();
		}

		return $returnValue;
	}

	static public function LOGICAL_OR()
	{
		$returnValue = false;
		$aArgs = PHPExcel_Calculation_Functions::flattenArray(func_get_args());
		$argCount = -1;

		foreach ($aArgs as $argCount => $arg) {
			if (is_bool($arg)) {
				$returnValue = $returnValue || $arg;
			}
			else {
				if (is_numeric($arg) && !is_string($arg)) {
					$returnValue = $returnValue || ($arg != 0);
				}
				else {
					if (is_string($arg)) {
						$arg = strtoupper($arg);
						if (($arg == 'TRUE') || ($arg == PHPExcel_Calculation::getTRUE())) {
							$arg = true;
						}
						else {
							if (($arg == 'FALSE') || ($arg == PHPExcel_Calculation::getFALSE())) {
								$arg = false;
							}
							else {
								return PHPExcel_Calculation_Functions::VALUE();
							}
						}

						$returnValue = $returnValue || ($arg != 0);
					}
				}
			}
		}

		if ($argCount < 0) {
			return PHPExcel_Calculation_Functions::VALUE();
		}

		return $returnValue;
	}

	static public function NOT($logical = false)
	{
		$logical = PHPExcel_Calculation_Functions::flattenSingleValue($logical);

		if (is_string($logical)) {
			$logical = strtoupper($logical);
			if (($logical == 'TRUE') || ($logical == PHPExcel_Calculation::getTRUE())) {
				return false;
			}

			if (($logical == 'FALSE') || ($logical == PHPExcel_Calculation::getFALSE())) {
				return true;
			}

			return PHPExcel_Calculation_Functions::VALUE();
		}

		return !$logical;
	}

	static public function STATEMENT_IF($condition = true, $returnIfTrue = 0, $returnIfFalse = false)
	{
		$condition = (is_null($condition) ? true : (bool) PHPExcel_Calculation_Functions::flattenSingleValue($condition));
		$returnIfTrue = (is_null($returnIfTrue) ? 0 : PHPExcel_Calculation_Functions::flattenSingleValue($returnIfTrue));
		$returnIfFalse = (is_null($returnIfFalse) ? false : PHPExcel_Calculation_Functions::flattenSingleValue($returnIfFalse));
		return $condition ? $returnIfTrue : $returnIfFalse;
	}

	static public function IFERROR($testValue = '', $errorpart = '')
	{
		$testValue = (is_null($testValue) ? '' : PHPExcel_Calculation_Functions::flattenSingleValue($testValue));
		$errorpart = (is_null($errorpart) ? '' : PHPExcel_Calculation_Functions::flattenSingleValue($errorpart));
		return self::STATEMENT_IF(PHPExcel_Calculation_Functions::IS_ERROR($testValue), $errorpart, $testValue);
	}
}

if (!defined('PHPEXCEL_ROOT')) {
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../../');
	require PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php';
}

?>
