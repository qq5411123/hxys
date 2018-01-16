<?php
// 唐上美联佳网络科技有限公司(技术支持)
abstract class PHPExcel_Writer_Excel2007_WriterPart
{
	private $_parentWriter;

	public function setParentWriter(PHPExcel_Writer_IWriter $pWriter = NULL)
	{
		$this->_parentWriter = $pWriter;
	}

	public function getParentWriter()
	{
		if (!is_null($this->_parentWriter)) {
			return $this->_parentWriter;
		}

		throw new PHPExcel_Writer_Exception('No parent PHPExcel_Writer_IWriter assigned.');
	}

	public function __construct(PHPExcel_Writer_IWriter $pWriter = NULL)
	{
		if (!is_null($pWriter)) {
			$this->_parentWriter = $pWriter;
		}
	}
}


?>
