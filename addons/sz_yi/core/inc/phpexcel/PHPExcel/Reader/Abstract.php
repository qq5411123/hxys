<?php
// 唐上美联佳网络科技有限公司(技术支持)
abstract class PHPExcel_Reader_Abstract implements PHPExcel_Reader_IReader
{
	protected $_readDataOnly = false;
	protected $_includeCharts = false;
	protected $_loadSheetsOnly;
	protected $_readFilter;
	protected $_fileHandle;

	public function getReadDataOnly()
	{
		return $this->_readDataOnly;
	}

	public function setReadDataOnly($pValue = false)
	{
		$this->_readDataOnly = $pValue;
		return $this;
	}

	public function getIncludeCharts()
	{
		return $this->_includeCharts;
	}

	public function setIncludeCharts($pValue = false)
	{
		$this->_includeCharts = (bool) $pValue;
		return $this;
	}

	public function getLoadSheetsOnly()
	{
		return $this->_loadSheetsOnly;
	}

	public function setLoadSheetsOnly($value = NULL)
	{
		$this->_loadSheetsOnly = is_array($value) ? $value : array($value);
		return $this;
	}

	public function setLoadAllSheets()
	{
		$this->_loadSheetsOnly = NULL;
		return $this;
	}

	public function getReadFilter()
	{
		return $this->_readFilter;
	}

	public function setReadFilter(PHPExcel_Reader_IReadFilter $pValue)
	{
		$this->_readFilter = $pValue;
		return $this;
	}

	protected function _openFile($pFilename)
	{
		if (!file_exists($pFilename) || !is_readable($pFilename)) {
			throw new PHPExcel_Reader_Exception('Could not open ' . $pFilename . ' for reading! File does not exist.');
		}

		$this->_fileHandle = fopen($pFilename, 'r');

		if ($this->_fileHandle === false) {
			throw new PHPExcel_Reader_Exception('Could not open file ' . $pFilename . ' for reading.');
		}
	}

	public function canRead($pFilename)
	{
		try {
			$this->_openFile($pFilename);
		}
		catch (Exception $e) {
			return false;
		}

		$readable = $this->_isValidFormat();
		fclose($this->_fileHandle);
		return $readable;
	}
}

?>
