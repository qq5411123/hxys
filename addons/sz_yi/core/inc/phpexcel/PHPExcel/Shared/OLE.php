<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_Shared_OLE
{
	const OLE_PPS_TYPE_ROOT = 5;
	const OLE_PPS_TYPE_DIR = 1;
	const OLE_PPS_TYPE_FILE = 2;
	const OLE_DATA_SIZE_SMALL = 4096;
	const OLE_LONG_INT_SIZE = 4;
	const OLE_PPS_SIZE = 128;

	public $_file_handle;
	public $_list = array();
	public $root;
	public $bbat;
	public $sbat;
	public $bigBlockSize;
	public $smallBlockSize;

	public function read($file)
	{
		$fh = fopen($file, 'r');

		if (!$fh) {
			throw new PHPExcel_Reader_Exception('Can\'t open file ' . $file);
		}

		$this->_file_handle = $fh;
		$signature = fread($fh, 8);

		if ("\xd0\xcf\x11\xe0\xa1\xb1\x1a\xe1" != $signature) {
			throw new PHPExcel_Reader_Exception('File doesn\'t seem to be an OLE container.');
		}

		fseek($fh, 28);

		if (fread($fh, 2) != "\xfe\xff") {
			throw new PHPExcel_Reader_Exception('Only Little-Endian encoding is supported.');
		}

		$this->bigBlockSize = pow(2, self::_readInt2($fh));
		$this->smallBlockSize = pow(2, self::_readInt2($fh));
		fseek($fh, 44);
		$bbatBlockCount = self::_readInt4($fh);
		$directoryFirstBlockId = self::_readInt4($fh);
		fseek($fh, 56);
		$this->bigBlockThreshold = self::_readInt4($fh);
		$sbatFirstBlockId = self::_readInt4($fh);
		$sbbatBlockCount = self::_readInt4($fh);
		$mbatFirstBlockId = self::_readInt4($fh);
		$mbbatBlockCount = self::_readInt4($fh);
		$this->bbat = array();
		$mbatBlocks = array();
		$i = 0;

		while ($i < 109) {
			$mbatBlocks[] = self::_readInt4($fh);
			++$i;
		}

		$pos = $this->_getBlockOffset($mbatFirstBlockId);
		$i = 0;

		while ($i < $mbbatBlockCount) {
			fseek($fh, $pos);
			$j = 0;

			while ($j < (($this->bigBlockSize / 4) - 1)) {
				$mbatBlocks[] = self::_readInt4($fh);
				++$j;
			}

			$pos = $this->_getBlockOffset(self::_readInt4($fh));
			++$i;
		}

		$i = 0;

		while ($i < $bbatBlockCount) {
			$pos = $this->_getBlockOffset($mbatBlocks[$i]);
			fseek($fh, $pos);
			$j = 0;

			while ($j < ($this->bigBlockSize / 4)) {
				$this->bbat[] = self::_readInt4($fh);
				++$j;
			}

			++$i;
		}

		$this->sbat = array();
		$shortBlockCount = ($sbbatBlockCount * $this->bigBlockSize) / 4;
		$sbatFh = $this->getStream($sbatFirstBlockId);
		$blockId = 0;

		while ($blockId < $shortBlockCount) {
			$this->sbat[$blockId] = self::_readInt4($sbatFh);
			++$blockId;
		}

		fclose($sbatFh);
		$this->_readPpsWks($directoryFirstBlockId);
		return true;
	}

	public function _getBlockOffset($blockId)
	{
		return 512 + ($blockId * $this->bigBlockSize);
	}

	public function getStream($blockIdOrPps)
	{
		static $isRegistered = false;

		if (!$isRegistered) {
			stream_wrapper_register('ole-chainedblockstream', 'PHPExcel_Shared_OLE_ChainedBlockStream');
			$isRegistered = true;
		}

		$GLOBALS['_OLE_INSTANCES'][] = $this;
		$instanceId = end(array_keys($GLOBALS['_OLE_INSTANCES']));
		$path = 'ole-chainedblockstream://oleInstanceId=' . $instanceId;

		if ($blockIdOrPps instanceof PHPExcel_Shared_OLE_PPS) {
			$path .= '&blockId=' . $blockIdOrPps->_StartBlock;
			$path .= '&size=' . $blockIdOrPps->Size;
		}
		else {
			$path .= '&blockId=' . $blockIdOrPps;
		}

		return fopen($path, 'r');
	}

	static private function _readInt1($fh)
	{
		list(, $tmp) = unpack('c', fread($fh, 1));
		return $tmp;
	}

	static private function _readInt2($fh)
	{
		list(, $tmp) = unpack('v', fread($fh, 2));
		return $tmp;
	}

	static private function _readInt4($fh)
	{
		list(, $tmp) = unpack('V', fread($fh, 4));
		return $tmp;
	}

	public function _readPpsWks($blockId)
	{
		$fh = $this->getStream($blockId);
		$pos = 0;

		while (true) {
			fseek($fh, $pos, SEEK_SET);
			$nameUtf16 = fread($fh, 64);
			$nameLength = self::_readInt2($fh);
			$nameUtf16 = substr($nameUtf16, 0, $nameLength - 2);
			$name = str_replace("\x00", '', $nameUtf16);
			$type = self::_readInt1($fh);

			switch ($type) {
			case self::OLE_PPS_TYPE_ROOT:
				$pps = new PHPExcel_Shared_OLE_PPS_Root(NULL, NULL, array());
				$this->root = $pps;
				break;

			case self::OLE_PPS_TYPE_DIR:
				$pps = new PHPExcel_Shared_OLE_PPS(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, array());
				break;

			case self::OLE_PPS_TYPE_FILE:
				$pps = new PHPExcel_Shared_OLE_PPS_File($name);
				break;

			default:
				continue;
			}

			fseek($fh, 1, SEEK_CUR);
			$pps->Type = $type;
			$pps->Name = $name;
			$pps->PrevPps = self::_readInt4($fh);
			$pps->NextPps = self::_readInt4($fh);
			$pps->DirPps = self::_readInt4($fh);
			fseek($fh, 20, SEEK_CUR);
			$pps->Time1st = self::OLE2LocalDate(fread($fh, 8));
			$pps->Time2nd = self::OLE2LocalDate(fread($fh, 8));
			$pps->_StartBlock = self::_readInt4($fh);
			$pps->Size = self::_readInt4($fh);
			$pps->No = count($this->_list);
			$this->_list[] = $pps;
			if (isset($this->root) && $this->_ppsTreeComplete($this->root->No)) {
				break;
			}

			$pos += 128;
		}

		fclose($fh);

		foreach ($this->_list as $pps) {
			if (($pps->Type == self::OLE_PPS_TYPE_DIR) || ($pps->Type == self::OLE_PPS_TYPE_ROOT)) {
				$nos = array($pps->DirPps);
				$pps->children = array();

				while ($nos) {
					$no = array_pop($nos);

					if ($no != -1) {
						$childPps = $this->_list[$no];
						$nos[] = $childPps->PrevPps;
						$nos[] = $childPps->NextPps;
						$pps->children[] = $childPps;
					}
				}
			}
		}

		return true;
	}

	public function _ppsTreeComplete($index)
	{
		return isset($this->_list[$index]) && $pps = $this->_list[$index] && (($pps->PrevPps == -1) || $this->_ppsTreeComplete($pps->PrevPps)) && (($pps->NextPps == -1) || $this->_ppsTreeComplete($pps->NextPps)) && (($pps->DirPps == -1) || $this->_ppsTreeComplete($pps->DirPps));
	}

	public function isFile($index)
	{
		if (isset($this->_list[$index])) {
			return $this->_list[$index]->Type == self::OLE_PPS_TYPE_FILE;
		}

		return false;
	}

	public function isRoot($index)
	{
		if (isset($this->_list[$index])) {
			return $this->_list[$index]->Type == self::OLE_PPS_TYPE_ROOT;
		}

		return false;
	}

	public function ppsTotal()
	{
		return count($this->_list);
	}

	public function getData($index, $position, $length)
	{
		if (!isset($this->_list[$index]) || ($this->_list[$index]->Size <= $position) || ($position < 0)) {
			return '';
		}

		$fh = $this->getStream($this->_list[$index]);
		$data = stream_get_contents($fh, $length, $position);
		fclose($fh);
		return $data;
	}

	public function getDataLength($index)
	{
		if (isset($this->_list[$index])) {
			return $this->_list[$index]->Size;
		}

		return 0;
	}

	static public function Asc2Ucs($ascii)
	{
		$rawname = '';
		$i = 0;

		while ($i < strlen($ascii)) {
			$rawname .= $ascii[$i] . "\x00";
			++$i;
		}

		return $rawname;
	}

	static public function LocalDate2OLE($date = NULL)
	{
		if (!isset($date)) {
			return "\x00\x00\x00\x00\x00\x00\x00\x00";
		}

		$factor = pow(2, 32);
		$days = 134774;
		$big_date = ($days * 24 * 3600) + gmmktime(date('H', $date), date('i', $date), date('s', $date), date('m', $date), date('d', $date), date('Y', $date));
		$big_date *= 10000000;
		$high_part = floor($big_date / $factor);
		$low_part = floor((($big_date / $factor) - $high_part) * $factor);
		$res = '';
		$i = 0;

		while ($i < 4) {
			$hex = $low_part % 256;
			$res .= pack('c', $hex);
			$low_part /= 256;
			++$i;
		}

		$i = 0;

		while ($i < 4) {
			$hex = $high_part % 256;
			$res .= pack('c', $hex);
			$high_part /= 256;
			++$i;
		}

		return $res;
	}

	static public function OLE2LocalDate($string)
	{
		if (strlen($string) != 8) {
			return new PEAR_Error('Expecting 8 byte string');
		}

		$factor = pow(2, 32);
		list(, $high_part) = unpack('V', substr($string, 4, 4));
		list(, $low_part) = unpack('V', substr($string, 0, 4));
		$big_date = ($high_part * $factor) + $low_part;
		$big_date /= 10000000;
		$days = 134774;
		$big_date -= $days * 24 * 3600;
		return floor($big_date);
	}
}

$GLOBALS['_OLE_INSTANCES'] = array();

?>
