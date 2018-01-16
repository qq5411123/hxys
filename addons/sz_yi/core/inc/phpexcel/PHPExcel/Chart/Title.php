<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_Chart_Title
{
	private $_caption;
	private $_layout;

	public function __construct($caption = NULL, PHPExcel_Chart_Layout $layout = NULL)
	{
		$this->_caption = $caption;
		$this->_layout = $layout;
	}

	public function getCaption()
	{
		return $this->_caption;
	}

	public function setCaption($caption = NULL)
	{
		$this->_caption = $caption;
	}

	public function getLayout()
	{
		return $this->_layout;
	}
}


?>
