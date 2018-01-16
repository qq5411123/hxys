<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_CalcEngine_Logger
{
	private $_writeDebugLog = false;
	private $_echoDebugLog = false;
	private $_debugLog = array();
	private $_cellStack;

	public function __construct(PHPExcel_CalcEngine_CyclicReferenceStack $stack)
	{
		$this->_cellStack = $stack;
	}

	public function setWriteDebugLog($pValue = false)
	{
		$this->_writeDebugLog = $pValue;
	}

	public function getWriteDebugLog()
	{
		return $this->_writeDebugLog;
	}

	public function setEchoDebugLog($pValue = false)
	{
		$this->_echoDebugLog = $pValue;
	}

	public function getEchoDebugLog()
	{
		return $this->_echoDebugLog;
	}

	public function writeDebugLog()
	{
		if ($this->_writeDebugLog) {
			$message = implode(func_get_args());
			$cellReference = implode(' -> ', $this->_cellStack->showStack());

			if ($this->_echoDebugLog) {
				echo $cellReference;
				echo 0 < $this->_cellStack->count() ? ' => ' : '';
				echo $message;
				echo PHP_EOL;
			}

			$this->_debugLog[] = $cellReference . (0 < $this->_cellStack->count() ? ' => ' : '') . $message;
		}
	}

	public function clearLog()
	{
		$this->_debugLog = array();
	}

	public function getLog()
	{
		return $this->_debugLog;
	}
}


?>
