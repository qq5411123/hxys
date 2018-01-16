<?php
// 唐上美联佳网络科技有限公司(技术支持)
class PHPExcel_Chart_Layout
{
	private $_layoutTarget;
	private $_xMode;
	private $_yMode;
	private $_xPos;
	private $_yPos;
	private $_width;
	private $_height;
	private $_showLegendKey;
	private $_showVal;
	private $_showCatName;
	private $_showSerName;
	private $_showPercent;
	private $_showBubbleSize;
	private $_showLeaderLines;

	public function __construct($layout = array())
	{
		if (isset($layout['layoutTarget'])) {
			$this->_layoutTarget = $layout['layoutTarget'];
		}

		if (isset($layout['xMode'])) {
			$this->_xMode = $layout['xMode'];
		}

		if (isset($layout['yMode'])) {
			$this->_yMode = $layout['yMode'];
		}

		if (isset($layout['x'])) {
			$this->_xPos = (double) $layout['x'];
		}

		if (isset($layout['y'])) {
			$this->_yPos = (double) $layout['y'];
		}

		if (isset($layout['w'])) {
			$this->_width = (double) $layout['w'];
		}

		if (isset($layout['h'])) {
			$this->_height = (double) $layout['h'];
		}
	}

	public function getLayoutTarget()
	{
		return $this->_layoutTarget;
	}

	public function setLayoutTarget($value)
	{
		$this->_layoutTarget = $value;
	}

	public function getXMode()
	{
		return $this->_xMode;
	}

	public function setXMode($value)
	{
		$this->_xMode = $value;
	}

	public function getYMode()
	{
		return $this->_xMode;
	}

	public function setYMode($value)
	{
		$this->_xMode = $value;
	}

	public function getXPosition()
	{
		return $this->_xPos;
	}

	public function setXPosition($value)
	{
		$this->_xPos = $value;
	}

	public function getYPosition()
	{
		return $this->_yPos;
	}

	public function setYPosition($value)
	{
		$this->_yPos = $value;
	}

	public function getWidth()
	{
		return $this->_width;
	}

	public function setWidth($value)
	{
		$this->_width = $value;
	}

	public function getHeight()
	{
		return $this->_height;
	}

	public function setHeight($value)
	{
		$this->_height = $value;
	}

	public function getShowLegendKey()
	{
		return $this->_showLegendKey;
	}

	public function setShowLegendKey($value)
	{
		$this->_showLegendKey = $value;
	}

	public function getShowVal()
	{
		return $this->_showVal;
	}

	public function setShowVal($value)
	{
		$this->_showVal = $value;
	}

	public function getShowCatName()
	{
		return $this->_showCatName;
	}

	public function setShowCatName($value)
	{
		$this->_showCatName = $value;
	}

	public function getShowSerName()
	{
		return $this->_showSerName;
	}

	public function setShowSerName($value)
	{
		$this->_showSerName = $value;
	}

	public function getShowPercent()
	{
		return $this->_showPercent;
	}

	public function setShowPercent($value)
	{
		$this->_showPercent = $value;
	}

	public function getShowBubbleSize()
	{
		return $this->_showBubbleSize;
	}

	public function setShowBubbleSize($value)
	{
		$this->_showBubbleSize = $value;
	}

	public function getShowLeaderLines()
	{
		return $this->_showLeaderLines;
	}

	public function setShowLeaderLines($value)
	{
		$this->_showLeaderLines = $value;
	}
}


?>
