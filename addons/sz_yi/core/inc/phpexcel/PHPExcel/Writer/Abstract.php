<?php
// 唐上美联佳网络科技有限公司(技术支持)
abstract class PHPExcel_Writer_Abstract implements PHPExcel_Writer_IWriter
{
	protected $_includeCharts = false;
	protected $_preCalculateFormulas = true;
	protected $_useDiskCaching = false;
	protected $_diskCachingDirectory = './';

	public function getIncludeCharts()
	{
		return $this->_includeCharts;
	}

	public function setIncludeCharts($pValue = false)
	{
		$this->_includeCharts = (bool) $pValue;
		return $this;
	}

	public function getPreCalculateFormulas()
	{
		return $this->_preCalculateFormulas;
	}

	public function setPreCalculateFormulas($pValue = true)
	{
		$this->_preCalculateFormulas = (bool) $pValue;
		return $this;
	}

	public function getUseDiskCaching()
	{
		return $this->_useDiskCaching;
	}

	public function setUseDiskCaching($pValue = false, $pDirectory = NULL)
	{
		$this->_useDiskCaching = $pValue;

		if ($pDirectory !== NULL) {
			if (is_dir($pDirectory)) {
				$this->_diskCachingDirectory = $pDirectory;
			}
			else {
				throw new PHPExcel_Writer_Exception('Directory does not exist: ' . $pDirectory);
			}
		}

		return $this;
	}

	public function getDiskCachingDirectory()
	{
		return $this->_diskCachingDirectory;
	}
}

?>
