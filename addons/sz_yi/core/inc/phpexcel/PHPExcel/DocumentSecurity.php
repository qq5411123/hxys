<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_DocumentSecurity
{
	private $_lockRevision;
	private $_lockStructure;
	private $_lockWindows;
	private $_revisionsPassword;
	private $_workbookPassword;

	public function __construct()
	{
		$this->_lockRevision = false;
		$this->_lockStructure = false;
		$this->_lockWindows = false;
		$this->_revisionsPassword = '';
		$this->_workbookPassword = '';
	}

	public function isSecurityEnabled()
	{
		return $this->_lockRevision || $this->_lockStructure || $this->_lockWindows;
	}

	public function getLockRevision()
	{
		return $this->_lockRevision;
	}

	public function setLockRevision($pValue = false)
	{
		$this->_lockRevision = $pValue;
		return $this;
	}

	public function getLockStructure()
	{
		return $this->_lockStructure;
	}

	public function setLockStructure($pValue = false)
	{
		$this->_lockStructure = $pValue;
		return $this;
	}

	public function getLockWindows()
	{
		return $this->_lockWindows;
	}

	public function setLockWindows($pValue = false)
	{
		$this->_lockWindows = $pValue;
		return $this;
	}

	public function getRevisionsPassword()
	{
		return $this->_revisionsPassword;
	}

	public function setRevisionsPassword($pValue = '', $pAlreadyHashed = false)
	{
		if (!$pAlreadyHashed) {
			$pValue = PHPExcel_Shared_PasswordHasher::hashPassword($pValue);
		}

		$this->_revisionsPassword = $pValue;
		return $this;
	}

	public function getWorkbookPassword()
	{
		return $this->_workbookPassword;
	}

	public function setWorkbookPassword($pValue = '', $pAlreadyHashed = false)
	{
		if (!$pAlreadyHashed) {
			$pValue = PHPExcel_Shared_PasswordHasher::hashPassword($pValue);
		}

		$this->_workbookPassword = $pValue;
		return $this;
	}

	public function __clone()
	{
		$vars = get_object_vars($this);

		foreach ($vars as $key => $value) {
			if (is_object($value)) {
				$this->$key = clone $value;
			}
			else {
				$this->$key = $value;
			}
		}
	}
}


?>
