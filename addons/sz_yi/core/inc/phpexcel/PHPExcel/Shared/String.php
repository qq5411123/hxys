<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_Shared_String
{
	const STRING_REGEXP_FRACTION = '(-?)(\\d+)\\s+(\\d+\\/\\d+)';

	static private $_controlCharacters = array();
	static private $_SYLKCharacters = array();
	static private $_decimalSeparator;
	static private $_thousandsSeparator;
	static private $_currencyCode;
	static private $_isMbstringEnabled;
	static private $_isIconvEnabled;

	static private function _buildControlCharacters()
	{
		$i = 0;

		while ($i <= 31) {
			if (($i != 9) && ($i != 10) && ($i != 13)) {
				$find = '_x' . sprintf('%04s', strtoupper(dechex($i))) . '_';
				$replace = chr($i);
				self::$_controlCharacters[$find] = $replace;
			}

			++$i;
		}
	}

	static private function _buildSYLKCharacters()
	{
		self::$_SYLKCharacters = array("\x1b 0" => chr(0), "\x1b 1" => chr(1), "\x1b 2" => chr(2), "\x1b 3" => chr(3), "\x1b 4" => chr(4), "\x1b 5" => chr(5), "\x1b 6" => chr(6), "\x1b 7" => chr(7), "\x1b 8" => chr(8), "\x1b 9" => chr(9), "\x1b :" => chr(10), "\x1b ;" => chr(11), "\x1b <" => chr(12), "\x1b :" => chr(13), "\x1b >" => chr(14), "\x1b ?" => chr(15), "\x1b!0" => chr(16), "\x1b!1" => chr(17), "\x1b!2" => chr(18), "\x1b!3" => chr(19), "\x1b!4" => chr(20), "\x1b!5" => chr(21), "\x1b!6" => chr(22), "\x1b!7" => chr(23), "\x1b!8" => chr(24), "\x1b!9" => chr(25), "\x1b!:" => chr(26), "\x1b!;" => chr(27), "\x1b!<" => chr(28), "\x1b!=" => chr(29), "\x1b!>" => chr(30), "\x1b!?" => chr(31), "\x1b'?" => chr(127), "\x1b(0" => 'EUR', "\x1b(2" => '‘', "\x1b(3" => 'f', "\x1b(4" => '"', "\x1b(5" => '…', "\x1b(6" => '+', "\x1b(7" => '', "\x1b(8" => '^', "\x1b(9" => '‰', "\x1b(:" => 'S', "\x1b(;" => '<', "\x1bNj" => 'OE', "\x1b(>" => 'Z', "\x1b)1" => '‘', "\x1b)2" => '’', "\x1b)3" => '“', "\x1b)4" => '”', "\x1b)5" => 'o', "\x1b)6" => '–', "\x1b)7" => '—', "\x1b)8" => '~', "\x1b)9" => 'TM', "\x1b):" => 's', "\x1b);" => '>', "\x1bNz" => 'oe', "\x1b)>" => 'z', "\x1b)?" => '"Y', "\x1b*0" => ' ', "\x1bN!" => '!', "\x1bN\"" => 'c', "\x1bN#" => 'lb', "\x1bN(" => '¤', "\x1bN%" => 'yen', "\x1b*6" => '|', "\x1bN'" => '§', "\x1bNH " => '¨', "\x1bNS" => '(c)', "\x1bNc" => 'a', "\x1bN+" => '<<', "\x1b*<" => 'not', "\x1b*=" => '-', "\x1bNR" => '(R)', "\x1b*?" => '', "\x1bN0" => '°', "\x1bN1" => '±', "\x1bN2" => '^2', "\x1bN3" => '^3', "\x1bNB " => ''', "\x1bN5" => 'u', "\x1bN6" => 'P', "\x1bN7" => '·', "\x1b+8" => ',', "\x1bNQ" => '^1', "\x1bNk" => 'o', "\x1bN;" => '>>', "\x1bN<" => ' 1/4 ', "\x1bN=" => ' 1/2 ', "\x1bN>" => ' 3/4 ', "\x1bN?" => '?', "\x1bNAA" => '`A', "\x1bNBA" => ''A', "\x1bNCA" => '^A', "\x1bNDA" => '~A', "\x1bNHA" => '"A', "\x1bNJA" => 'A', "\x1bNa" => 'AE', "\x1bNKC" => 'C', "\x1bNAE" => '`E', "\x1bNBE" => ''E', "\x1bNCE" => '^E', "\x1bNHE" => '"E', "\x1bNAI" => '`I', "\x1bNBI" => ''I', "\x1bNCI" => '^I', "\x1bNHI" => '"I', "\x1bNb" => 'D', "\x1bNDN" => '~N', "\x1bNAO" => '`O', "\x1bNBO" => ''O', "\x1bNCO" => '^O', "\x1bNDO" => '~O', "\x1bNHO" => '"O', "\x1b-7" => '×', "\x1bNi" => 'O', "\x1bNAU" => '`U', "\x1bNBU" => ''U', "\x1bNCU" => '^U', "\x1bNHU" => '"U', "\x1b-=" => ''Y', "\x1bNl" => 'Th', "\x1bN{" => 'ss', "\x1bNAa" => 'à', "\x1bNBa" => 'á', "\x1bNCa" => '^a', "\x1bNDa" => '~a', "\x1bNHa" => '"a', "\x1bNJa" => 'a', "\x1bNq" => 'ae', "\x1bNKc" => 'c', "\x1bNAe" => 'è', "\x1bNBe" => 'é', "\x1bNCe" => 'ê', "\x1bNHe" => '"e', "\x1bNAi" => 'ì', "\x1bNBi" => 'í', "\x1bNCi" => '^i', "\x1bNHi" => '"i', "\x1bNs" => 'd', "\x1bNDn" => '~n', "\x1bNAo" => 'ò', "\x1bNBo" => 'ó', "\x1bNCo" => '^o', "\x1bNDo" => '~o', "\x1bNHo" => '"o', "\x1b/7" => '÷', "\x1bNy" => 'o', "\x1bNAu" => 'ù', "\x1bNBu" => 'ú', "\x1bNCu" => '^u', "\x1bNHu" => 'ü', "\x1b/=" => ''y', "\x1bN|" => 'th', "\x1bNHy" => '"y');
	}

	static public function getIsMbstringEnabled()
	{
		if (isset($_isMbstringEnabled)) {
			return self::$_isMbstringEnabled;
		}

		self::$_isMbstringEnabled = (function_exists('mb_convert_encoding') ? true : false);
		return self::$_isMbstringEnabled;
	}

	static public function getIsIconvEnabled()
	{
		if (isset($_isIconvEnabled)) {
			return self::$_isIconvEnabled;
		}

		if (!function_exists('iconv')) {
			self::$_isIconvEnabled = false;
			return false;
		}

		if (!@iconv('UTF-8', 'UTF-16LE', 'x')) {
			self::$_isIconvEnabled = false;
			return false;
		}

		if (!@iconv_substr('A', 0, 1, 'UTF-8')) {
			self::$_isIconvEnabled = false;
			return false;
		}

		if (defined('PHP_OS') && @stristr(PHP_OS, 'AIX') && defined('ICONV_IMPL') && (@strcasecmp(ICONV_IMPL, 'unknown') == 0) && defined('ICONV_VERSION') && (@strcasecmp(ICONV_VERSION, 'unknown') == 0)) {
			self::$_isIconvEnabled = false;
			return false;
		}

		self::$_isIconvEnabled = true;
		return true;
	}

	static public function buildCharacterSets()
	{
		if (empty($_controlCharacters)) {
			self::_buildControlCharacters();
		}

		if (empty($_SYLKCharacters)) {
			self::_buildSYLKCharacters();
		}
	}

	static public function ControlCharacterOOXML2PHP($value = '')
	{
		return str_replace(array_keys(self::$_controlCharacters), array_values(self::$_controlCharacters), $value);
	}

	static public function ControlCharacterPHP2OOXML($value = '')
	{
		return str_replace(array_values(self::$_controlCharacters), array_keys(self::$_controlCharacters), $value);
	}

	static public function SanitizeUTF8($value)
	{
		if (self::getIsIconvEnabled()) {
			$value = @iconv('UTF-8', 'UTF-8', $value);
			return $value;
		}

		if (self::getIsMbstringEnabled()) {
			$value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
			return $value;
		}

		return $value;
	}

	static public function IsUTF8($value = '')
	{
		return utf8_encode(utf8_decode($value)) === $value;
	}

	static public function FormatNumber($value)
	{
		if (is_float($value)) {
			return str_replace(',', '.', $value);
		}

		return (string) $value;
	}

	static public function UTF8toBIFF8UnicodeShort($value, $arrcRuns = array())
	{
		$ln = self::CountCharacters($value, 'UTF-8');

		if (empty($arrcRuns)) {
			$opt = (self::getIsIconvEnabled() || self::getIsMbstringEnabled() ? 1 : 0);
			$data = pack('CC', $ln, $opt);
			$data .= self::ConvertEncoding($value, 'UTF-16LE', 'UTF-8');
		}
		else {
			$data = pack('vC', $ln, 9);
			$data .= pack('v', count($arrcRuns));
			$data .= self::ConvertEncoding($value, 'UTF-16LE', 'UTF-8');

			foreach ($arrcRuns as $cRun) {
				$data .= pack('v', $cRun['strlen']);
				$data .= pack('v', $cRun['fontidx']);
			}
		}

		return $data;
	}

	static public function UTF8toBIFF8UnicodeLong($value)
	{
		$ln = self::CountCharacters($value, 'UTF-8');
		$opt = (self::getIsIconvEnabled() || self::getIsMbstringEnabled() ? 1 : 0);
		$chars = self::ConvertEncoding($value, 'UTF-16LE', 'UTF-8');
		$data = pack('vC', $ln, $opt) . $chars;
		return $data;
	}

	static public function ConvertEncoding($value, $to, $from)
	{
		if (self::getIsIconvEnabled()) {
			return iconv($from, $to, $value);
		}

		if (self::getIsMbstringEnabled()) {
			return mb_convert_encoding($value, $to, $from);
		}

		if ($from == 'UTF-16LE') {
			return self::utf16_decode($value, false);
		}

		if ($from == 'UTF-16BE') {
			return self::utf16_decode($value);
		}

		return $value;
	}

	static public function utf16_decode($str, $bom_be = true)
	{
		if (strlen($str) < 2) {
			return $str;
		}

		$c0 = ord($str[0]);
		$c1 = ord($str[1]);
		if (($c0 == 254) && ($c1 == 255)) {
			$str = substr($str, 2);
		}
		else {
			if (($c0 == 255) && ($c1 == 254)) {
				$str = substr($str, 2);
				$bom_be = false;
			}
		}

		$len = strlen($str);
		$newstr = '';
		$i = 0;

		while ($i < $len) {
			if ($bom_be) {
				$val = ord($str[$i]) << 4;
				$val += ord($str[$i + 1]);
			}
			else {
				$val = ord($str[$i + 1]) << 4;
				$val += ord($str[$i]);
			}

			$newstr .= ($val == 552 ? "\n" : chr($val));
			$i += 2;
		}

		return $newstr;
	}

	static public function CountCharacters($value, $enc = 'UTF-8')
	{
		if (self::getIsMbstringEnabled()) {
			return mb_strlen($value, $enc);
		}

		if (self::getIsIconvEnabled()) {
			return iconv_strlen($value, $enc);
		}

		return strlen($value);
	}

	static public function Substring($pValue = '', $pStart = 0, $pLength = 0)
	{
		if (self::getIsMbstringEnabled()) {
			return mb_substr($pValue, $pStart, $pLength, 'UTF-8');
		}

		if (self::getIsIconvEnabled()) {
			return iconv_substr($pValue, $pStart, $pLength, 'UTF-8');
		}

		return substr($pValue, $pStart, $pLength);
	}

	static public function StrToUpper($pValue = '')
	{
		if (function_exists('mb_convert_case')) {
			return mb_convert_case($pValue, MB_CASE_UPPER, 'UTF-8');
		}

		return strtoupper($pValue);
	}

	static public function StrToLower($pValue = '')
	{
		if (function_exists('mb_convert_case')) {
			return mb_convert_case($pValue, MB_CASE_LOWER, 'UTF-8');
		}

		return strtolower($pValue);
	}

	static public function StrToTitle($pValue = '')
	{
		if (function_exists('mb_convert_case')) {
			return mb_convert_case($pValue, MB_CASE_TITLE, 'UTF-8');
		}

		return ucwords($pValue);
	}

	static public function convertToNumberIfFraction(&$operand)
	{
		if (preg_match('/^' . self::STRING_REGEXP_FRACTION . '$/i', $operand, $match)) {
			$sign = ($match[1] == '-' ? '-' : '+');
			$fractionFormula = '=' . $sign . $match[2] . $sign . $match[3];
			$operand = PHPExcel_Calculation::getInstance()->_calculateFormulaValue($fractionFormula);
			return true;
		}

		return false;
	}

	static public function getDecimalSeparator()
	{
		if (!isset($_decimalSeparator)) {
			$localeconv = localeconv();
			self::$_decimalSeparator = ($localeconv['decimal_point'] != '' ? $localeconv['decimal_point'] : $localeconv['mon_decimal_point']);

			if (self::$_decimalSeparator == '') {
				self::$_decimalSeparator = '.';
			}
		}

		return self::$_decimalSeparator;
	}

	static public function setDecimalSeparator($pValue = '.')
	{
		self::$_decimalSeparator = $pValue;
	}

	static public function getThousandsSeparator()
	{
		if (!isset($_thousandsSeparator)) {
			$localeconv = localeconv();
			self::$_thousandsSeparator = ($localeconv['thousands_sep'] != '' ? $localeconv['thousands_sep'] : $localeconv['mon_thousands_sep']);

			if (self::$_thousandsSeparator == '') {
				self::$_thousandsSeparator = ',';
			}
		}

		return self::$_thousandsSeparator;
	}

	static public function setThousandsSeparator($pValue = ',')
	{
		self::$_thousandsSeparator = $pValue;
	}

	static public function getCurrencyCode()
	{
		if (!isset($_currencyCode)) {
			$localeconv = localeconv();
			self::$_currencyCode = ($localeconv['currency_symbol'] != '' ? $localeconv['currency_symbol'] : $localeconv['int_curr_symbol']);

			if (self::$_currencyCode == '') {
				self::$_currencyCode = '$';
			}
		}

		return self::$_currencyCode;
	}

	static public function setCurrencyCode($pValue = '$')
	{
		self::$_currencyCode = $pValue;
	}

	static public function SYLKtoUTF8($pValue = '')
	{
		if (strpos($pValue, "\x1b") === false) {
			return $pValue;
		}

		foreach (self::$_SYLKCharacters as $k => $v) {
			$pValue = str_replace($k, $v, $pValue);
		}

		return $pValue;
	}

	static public function testStringAsNumeric($value)
	{
		if (is_numeric($value)) {
			return $value;
		}

		$v = floatval($value);
		return is_numeric(substr($value, 0, strlen($v))) ? $v : $value;
	}
}


?>
