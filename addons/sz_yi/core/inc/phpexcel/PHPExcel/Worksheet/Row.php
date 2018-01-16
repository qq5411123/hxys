<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_Worksheet_Row
{
	private $_parent;
	private $_rowIndex = 0;

	public function __construct(PHPExcel_Worksheet $parent = NULL, $rowIndex = 1)
	{
		$this->_parent = $parent;
		$this->_rowIndex = $rowIndex;
	}

	public function __destruct()
	{
		unset($this->_parent);
	}

	public function getRowIndex()
	{
		return $this->_rowIndex;
	}

	public function getCellIterator()
	{
		return new PHPExcel_Worksheet_CellIterator($this->_parent, $this->_rowIndex);
	}
}


?>
